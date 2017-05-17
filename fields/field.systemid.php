<?php

	if (!defined('__IN_SYMPHONY__')) die('<h2>Symphony Error</h2><p>You cannot directly access this file</p>');

	class FieldSystemId extends Field {
	/*-------------------------------------------------------------------------
		Definition:
	-------------------------------------------------------------------------*/

		public function __construct() {
			parent::__construct();

			$this->_name = __('System Id');
			$this->_required = false;

			// Set defaults:
			$this->set('show_column', 'yes');
		}

		public function createTable() {
			return true;
		}

		public function canFilter() {
			return true;
		}

		public function isSortable() {
			return true;
		}

		public function requiresTable() {
			return false;
		}

		public function commit() {
			// if the default implementation works...
			if(!parent::commit()) {
				return false;
			}

			$id = $this->get('id');

			// exit if there is no id
			if ($id == false) {
				return false;
			}

			$settings = array();
			$settings['field_id'] = $id;
			return FieldManager::saveSettings($id, $settings);
		}

	/*-------------------------------------------------------------------------
		Settings:
	-------------------------------------------------------------------------*/

		public function displaySettingsPanel(XMLElement &$wrapper, $errors = null) {
			parent::displaySettingsPanel($wrapper, $errors);
			$this->appendShowColumnCheckbox($wrapper);
		}

	/*-------------------------------------------------------------------------
		Publish:
	-------------------------------------------------------------------------*/

		public function displayPublishPanel(XMLElement &$wrapper, $data = null, $flagWithError = null, $fieldnamePrefix = null, $fieldnamePostfix = null, $entry_id = null) {
			$label = Widget::Label($this->get('label'));
			$text = new XMLElement('div', $entry_id);

			$wrapper->appendChild($label);
			$label->appendChild($text);
		}

	/*-------------------------------------------------------------------------
		Input:
	-------------------------------------------------------------------------*/

		public function checkPostFieldData($data, &$message, $entry_id = null) {
			$message = null;

			return self::__OK__;
		}

		public function processRawFieldData($data, &$status, &$message = NULL, $simulate = false, $entry_id = NULL) {
			$status = self::__OK__;

			return $data;
		}

	/*-------------------------------------------------------------------------
		Output:
	-------------------------------------------------------------------------*/

		public function appendFormattedElement(XMLElement &$wrapper, $data, $encode = false, $mode = null, $entry_id = NULL) {
			$element = new XMLElement($this->get('element_name'));
			$element->setAttribute('hash', @dechex($data['value']));
			$element->setValue(@$data['value'] ? $data['value'] : '0');
			$wrapper->appendChild($element);
		}

		public function prepareTextValue($data, $entry_id = null)
		{
			return $entry_id;
		}

	/*-------------------------------------------------------------------------
		Filtering:
	-------------------------------------------------------------------------*/

		public function displayDatasourceFilterPanel(XMLElement &$wrapper, $data = null, $errors = null, $prefix = null, $postfix = null) {
			$wrapper = new XMLElement('p');
			$wrapper->setAttribute('style', 'display: none;');
		}

	/*-------------------------------------------------------------------------
		Sorting:
	-------------------------------------------------------------------------*/

		public function buildSortingSQL(&$joins, &$where, &$sort, $order = 'ASC')
		{
			$sort = 'ORDER BY ' . (strtolower($order) == 'random' ? 'RAND()' : "`e`.`id` {$order}");
		}

		public function buildSortingSelectSQL($sort, $order = 'ASC')
		{
			return null;
		}
	}

