<?php

namespace GeorgRinger\BeAclFile\Hooks;

use GeorgRinger\BeAclFile\Exception;
use GeorgRinger\BeAclFile\Service\IniAcl;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * Class BackendUserAuthentication
 */
class BackendUserAuthentication {

	/** @var \TYPO3\CMS\Core\Log\Logger */
	protected $logger;

	/** @var  IniAcl */
	protected $iniAcl;

	public function __construct() {
		$this->logger = GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
		$this->iniAcl = GeneralUtility::makeInstance('GeorgRinger\\BeAclFile\\Service\\IniAcl');
	}

	/**
	 * @param array $params
	 * @param \TYPO3\CMS\Core\Authentication\BackendUserAuthentication $backendUserAuthentication
	 */
	public function get(array $params, \TYPO3\CMS\Core\Authentication\BackendUserAuthentication $backendUserAuthentication) {
		try {
			$permissions = $this->iniAcl->overload($backendUserAuthentication->dataLists);
			$backendUserAuthentication->dataLists = $permissions;
		} catch (Exception $e) {
			$this->logger->error($e->getMessage());
		}
	}
}