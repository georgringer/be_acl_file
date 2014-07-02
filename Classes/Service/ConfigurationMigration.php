<?php

namespace GeorgRinger\BeAclFile\Service;

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
class ConfigurationMigration {

	protected $mappingIn = array(
		'allowed_languages' => 'languages',
		'tables_select' => 'tables_select',
		'tables_modify' => 'tables_modify',
		'webmount_list' => 'webmounts',
		'filemount_list' => 'filemounts',
	);

	protected $mappingOut = array();

	public function __construct() {
		$this->mappingOut = array_flip($this->mappingIn);
	}


	public function fromIniToConfiguration(array $in) {
		$result = array();

		if (isset($in['general']['webmounts']) && is_array($in['general']['webmounts'])) {
			$result['webmount_list'] = implode(',', $in['general']['webmounts']);
		}
		if (isset($in['general']['filemounts']) && is_array($in['general']['filemounts'])) {
			$result['filemount_list'] = implode(',', $in['general']['filemounts']);
		}
		if (isset($in['general']['file_permissions']) && is_array($in['general']['file_permissions'])) {
			$result['file_permissions'] = implode(',', $in['general']['file_permissions']);
		}
		if ($this->isArray($in['general']['modules'])) {
			$result['modList'] = implode(',', $in['general']['modules']);
		}
		$result['tables_select'] = implode(',', $in['general']['tables_select']);
		$result['tables_modify'] = implode(',', $in['general']['tables_modify']);
		if (isset($in['general']['pagetypes']) && is_array($in['general']['pagetypes'])) {
			$result['pagetypes_select'] = implode(',', $in['general']['pagetypes']);
		}

		$result['allowed_languages'] = implode(',', $in['general']['languages']);
		if (is_array($in['explicit_allowdeny'])) {
			$mode = ($GLOBALS['TYPO3_CONF_VARS']['BE']['explicitADmode'] == 'explicitAllow') ? 'ALLOW' : 'DENY';
			$data = array();
			foreach ($in['explicit_allowdeny'] as $field => $values) {
				foreach ($values as $item) {
					$data[] = sprintf('tt_content:%s:%s:%s', $field, $item, $mode);
				}
			}
			$result['explicit_allowdeny'] = implode(',', $data);
		}
		if (is_array($in['fields'])) {
			$data = array();
			foreach ($in['fields'] as $table => $fields) {
				foreach ($fields as $field) {
					$data[] = $table . ':' . $field;
				}
			}
			$result['non_exclude_fields'] = implode(',', $data);
		}

		return $result;
	}

	public function fromConfigurationToIni(array $userGroupRecord) {
		// General fields
		$ini = array(
			'general' => array(
				'modules' => explode(',', $userGroupRecord['groupMods']),
				'webmounts' => explode(',', $userGroupRecord['db_mountpoints']),
				'languages' => explode(',', $userGroupRecord['allowed_languages']),
				'pagetypes' => explode(',', $userGroupRecord['pagetypes_select']),
				'tables_select' => explode(',', $userGroupRecord['tables_select']),
				'tables_modify' => explode(',', $userGroupRecord['tables_modify']),
				'file_permissions' => explode(',', $userGroupRecord['file_permissions']),
			),
		);
		// explicitly allowed fields
		if (!empty($userGroupRecord['explicit_allowdeny'])) {
			$fields = explode(',', $userGroupRecord['explicit_allowdeny']);

			$tmp = array();
			foreach ($fields as $row) {
				$split = explode(':', $row);
				$tmp[$split[0]][$split[1]][] = $split[2];
			}
			foreach ($tmp as $tableName => $tableData) {
				foreach ($tableData as $key => $value) {
					$ini['explicit_allowdeny'][$key] = $value;
				}
			}
		}

		// non exclude fields
		if (!empty($userGroupRecord['non_exclude_fields'])) {
			$fields = explode(',', $userGroupRecord['non_exclude_fields']);
			$tmp = array();
			foreach ($fields as $field) {
				$split = explode(':', $field);
				$tmp[$split[0]][$split[1]] = $split[1];
			}

			foreach ($tmp as $tableName => $tableData) {
				$ini['fields'][$tableName] = $tableData;
			}
		}

		$out = array();
		foreach ($ini as $groupName => $groupData) {
			$out[] = LF . '[' . $groupName . '+]';

			foreach ($groupData as $key => $value) {
				if (is_array($value)) {
					$tmp = array();
					foreach ($value as $fV) {
						$tmp[] = '\'' . $fV . '\'';
					}
					$finalValue = '[' . implode(', ', $tmp) . ']';
				} else {
					$finalValue = 'todo';
				}
				$out[] = $key . ' = ' . $finalValue;
			}
		}
		return trim(implode(LF, $out));
	}

	/**
	 * Check if isset and array
	 *
	 * @param mixed $property
	 * @return bool
	 */
	protected function isArray($property) {
		return (isset($property) && is_array($property));
	}

}