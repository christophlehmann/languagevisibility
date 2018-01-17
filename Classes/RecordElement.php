<?php

namespace AOE\Languagevisibility;

/***************************************************************
 * Copyright notice
 *
 * (c) 2016 AOE GmbH <dev@aoe.com>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class Recordelement
 * @package AOE\Languagevisibility
 */
class RecordElement extends Element {

	/**
	 * Returns a formal description of the record element.
	 *
	 * (non-PHPdoc)
	 * @see classes/tx_languagevisibility_element#getElementDescription()
	 * @return string
	 */
	public function getElementDescription() {
		return 'TYPO3-Record';
	}

	/**
	 * This method is the implementation of an abstract parent method.
	 * The method should return the overlay record for a certain language.
	 *
	 * (non-PHPdoc)
	 * @see classes/tx_languagevisibility_element#getOverLayRecordForCertainLanguageImplementation($languageId)
	 */
	protected function getOverLayRecordForCertainLanguageImplementation($languageId) {
		if (empty($this->table)) {
			return array();
		}

		$ctrl = $GLOBALS['TCA'][$this->table]['ctrl'];

			// we can't use the exclude fields here because we might loose (hidden) parent-records
		if (is_object($GLOBALS['TSFE']->sys_page)) {
			$excludeClause = $GLOBALS['TSFE']->sys_page->deleteClause($this->table);
		} else {
			$excludeClause = \TYPO3\CMS\Backend\Utility\BackendUtility::deleteClause($this->table);
		}


		if (isset($ctrl['versioningWS']) && $ctrl['versioningWS'] > 0) {
			$workspaces = '0,' . $GLOBALS['BE_USER']->workspace;
			$workspaceCondition = 't3ver_wsid IN (' . rtrim($workspaces, ',') . ') AND ';
		} else {
			$workspaceCondition = '';
		}

			// Select overlay record (Live workspace, initial placeholders included):
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			$this->table,
				// from the current pid and only records from the live workspace or initial placeholder
			'pid=' . intval($this->getPid()) . ' AND ' .
				$workspaceCondition .
				$ctrl['languageField'] . '=' . intval($languageId) .
					// With L=0 transOrigPointerField is not set, so uid should be used instead (see #31607)
				($languageId > 0 ? ' AND ' . $ctrl['transOrigPointerField'] . '=' . intval($this->getUid()) : ' AND uid=' . intval($this->getUid())) .
				$excludeClause,
			'',
			'',
			'1'
		);

		$olrow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		$olrow = $this->getContextIndependentWorkspaceOverlay($this->table, $olrow);
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		if (!$this->getEnableFieldResult($olrow)) {
			$olrow = array();
		}

		return $olrow;
	}

	/**
	 * This method is used to check if this element has any translation in any workspace.
	 *
	 * @return boolean
	 */
	public function hasOverLayRecordForAnyLanguageInAnyWorkspace() {
		$table = $this->table;

		if ($this->isOrigElement()) {
			$fields = 'count(*) as ANZ';

			$where = 'deleted = 0 AND ' . $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField'] . '=' . $this->getUid();
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($fields, $table, $where);

			return ($res[0]['ANZ'] > 0);
		} else {
				// if this is a translation is clear that an overlay must exist
			return TRUE;
		}
	}

	/**
	 * Returns the fallback order of an record element.
	 *
	 * (non-PHPdoc)
	 * @see classes/tx_languagevisibility_element#getFallbackOrder($language)
	 */
	public function getFallbackOrder(Language $language) {
		return $language->getFallbackOrderElement($this);
	}
}