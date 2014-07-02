<?php

namespace GeorgRinger\BeAclFile\Hooks;

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
class DataHandler {

	/**
	 * Update ini configuration
	 *
	 * @param string $status
	 * @param string $table
	 * @param string $id
	 * @param array $fieldArray
	 * @param \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler
	 */
	public function processDatamap_afterDatabaseOperations($status, $table, $id, array &$fieldArray, \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler) {
		if ($table !== 'be_groups') {
			return;
		}

		if ($status === 'new') {
			$id = $dataHandler->substNEWwithIDs[$id];
		}

		$row = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', $table, 'uid=' . (int)$id);

		/** @var \GeorgRinger\BeAclFile\Service\ConfigurationMigration $migrationService */
		$migrationService = GeneralUtility::makeInstance('GeorgRinger\\BeAclFile\\Service\\ConfigurationMigration');
		$iniConfiguration = $migrationService->fromConfigurationToIni($row);

		$this->updateGroup($id, $iniConfiguration);
	}

	/**
	 * Update group and set configuration
	 *
	 * @param integer $id
	 * @param string $configuration
	 * @return void
	 */
	protected function updateGroup($id, $configuration) {
		$fields = array(
			'ini_export' => $configuration
		);
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('be_groups', 'uid=' . (int)$id, $fields);
	}
}