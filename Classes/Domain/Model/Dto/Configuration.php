<?php

namespace GeorgRinger\BeAclFile\Domain\Model\Dto;


/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Class Configuration
 */
class Configuration {

	/** @var string */
	protected $path;

	/**
	 * Build the object with all given and valid input
	 * out of the EM configuration
	 */
	public function __construct() {
		$settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['be_acl_file']);
		if (is_array($settings)) {
			foreach ($settings as $key => $value) {
				$methodName = 'set' . ucfirst($key);
				if (method_exists($this, $methodName)) {
					$this->$methodName($value);
				}
			}
		}
	}

	/**
	 * @param string $path
	 */
	public function setPath($path) {
		$this->path = $path;
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	public function getFullPath() {
		return '/' . trim(PATH_site . $this->path, '/') . '/';
	}

}