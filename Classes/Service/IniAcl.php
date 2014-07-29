<?php

namespace GeorgRinger\BeAclFile\Service;

use Pixel418\Iniliq\Stack\Util\ArrayObject;
use Pixel418\Iniliq\Stack\Util\IniParser;
use TYPO3\CMS\Core\Tests\Exception;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class IniAcl {

	/** @var  IniParser */
	protected $iniParser;

	/** @var  \GeorgRinger\BeAclFile\Domain\Model\Dto\Configuration */
	protected $configuration;

	/** @var \GeorgRinger\BeAclFile\Service\ConfigurationMigration */
	protected $configurationMigrationService;


	public function __construct() {
		$this->loadRequiredFiles();
		$this->iniParser = new IniParser();
		$this->configurationMigrationService = GeneralUtility::makeInstance('GeorgRinger\\BeAclFile\\Service\\ConfigurationMigration');
		$this->configuration = GeneralUtility::makeInstance('GeorgRinger\\BeAclFile\\Domain\\Model\\Dto\\Configuration');
		$this->logger = GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
	}

	/**
	 * @param array $currentPermissions
	 * @return array
	 */
	public function overload(array $currentPermissions) {
		$files = $this->getFilesForCurrentUser();

		if (!empty($files)) {
			$out = $this->iniParser->parse($files);
			$newPermissions = $this->configurationMigrationService->fromIniToConfiguration($out->toArray());

			foreach ($newPermissions as $key => $value) {
				if (!empty($value)) {
					$currentPermissions[$key] = $value;
				}
			}
		}
		return $currentPermissions;
	}

	/**
	 * @return array
	 * @throws \GeorgRinger\BeAclFile\Exception
	 */
	protected function getFilesForCurrentUser() {
		$files = array();

		$userConfigurationFile = $this->configuration->getFullPath() . 'users.ini';
		if (!is_file($userConfigurationFile)) {
			throw new \GeorgRinger\BeAclFile\Exception(sprintf('Basic file "%s" not found', $userConfigurationFile));
		}

		$userFiles = $this->iniParser->parse($userConfigurationFile)->toArray();
		$username = $GLOBALS['BE_USER']->user['username'];
		$directory = $this->configuration->getFullPath() . 'groups/';
		if (isset($userFiles[$username])) {
			$groupFiles = $userFiles[$username]['groups'];
			if (!is_array($groupFiles)) {
				throw new \GeorgRinger\BeAclFile\Exception(sprintf('User "%s" got no groups assigned', $username));
			}
			foreach ($groupFiles as $file) {
				$filePath = $directory . $file;
				if (!is_file($filePath)) {
					throw new Exception(sprintf('Configuration file "%s" not found for user "%s"!', $filePath, $GLOBALS['BE_USER']->user['username']));
				}
				$files[] = $filePath;
			}
		}

		return $files;
	}

	/**
	 * Simple kind of autoloader
	 *
	 * @return void
	 */
	protected function loadRequiredFiles() {
		$path = ExtensionManagementUtility::extPath('be_acl_file') . 'Resources/Private/Contrib/';
		GeneralUtility::requireOnce($path . 'Ubiq/src/UString.php');
		GeneralUtility::requireOnce($path . 'Ubiq/src/UObject.php');
		GeneralUtility::requireOnce($path . 'Ubiq/src/UArray.php');
		GeneralUtility::requireOnce($path . 'Iniliq/src/Pixel418/Iniliq.php');
		GeneralUtility::requireOnce($path . 'Iniliq/src/Pixel418/Iniliq/Stack/Util/ArrayObject.php');
		GeneralUtility::requireOnce($path . 'Iniliq/src/Pixel418/Iniliq/Stack/Util/IniParser.php');
		GeneralUtility::requireOnce($path . 'Iniliq/src/Pixel418/Iniliq/ArrayObject.php');
		GeneralUtility::requireOnce($path . 'Iniliq/src/Pixel418/Iniliq/IniParser.php');
	}
}