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
			$field_id = $this->get('id');

			return Symphony::Database()->query("
				CREATE TABLE IF NOT EXISTS `tbl_entries_data_{$field_id}` (
					`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`entry_id` INT(11) UNSIGNED NOT NULL,
					`value` INT(11) UNSIGNED DEFAULT NULL,
					PRIMARY KEY (`id`),
					KEY `entry_id` (`entry_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
			");
		}

		public function canFilter() {
			return true;
		}

		public function isSortable() {
			return true;
		}

	/*-------------------------------------------------------------------------
		Settings:
	-------------------------------------------------------------------------*/

		public function displaySettingsPanel(&$wrapper, $errors = null, $append_before = null, $append_after = null) {
			parent::displaySettingsPanel($wrapper, $errors);
			$this->appendShowColumnCheckbox($wrapper);
		}

		public function buildSummaryBlock($errors = null) {
			$order = $this->get('sortorder');
			$div = new XMLElement('div');
			$div->appendChild(Widget::Input(
				"fields[{$order}][location]",
				'main', 'hidden'
			));

			$label = Widget::Label(__('Label'));
			$label->appendChild(Widget::Input(
				"fields[{$order}][label]",
				$this->get('label')
			));

			if (isset($errors['label'])) {
				$label = Widget::Error($label, $errors['label']);
			}

			$div->appendChild($label);

			return $div;
		}

	/*-------------------------------------------------------------------------
		Publish:
	-------------------------------------------------------------------------*/

		public function displayPublishPanel(&$wrapper, $data = null, $error = null, $prefix = null, $postfix = null) {
			$label = Widget::Label($this->get('label'));
			$text = new XMLElement('div', $data['value']);

			$wrapper->appendChild($label);
			$wrapper->appendChild($text);
		}

	/*-------------------------------------------------------------------------
		Input:
	-------------------------------------------------------------------------*/

		public function checkPostFieldData($data, &$message, $entry_id = null) {
			$message = null;

			return self::__OK__;
		}

		public function processRawFieldData($data, &$status, $simulate = false, $entry_id = null) {
			$status = self::__OK__;

			return array(
				'value'		=> $entry_id
			);
		}

	/*-------------------------------------------------------------------------
		Output:
	-------------------------------------------------------------------------*/

		public function appendFormattedElement(&$wrapper, $data, $encode = false, $mode = null) {
			$element = new XMLElement($this->get('element_name'));
			$element->setAttribute('hash', @dechex($data['value']));
			$element->setValue(@$data['value'] ? $data['value'] : '0');
			$wrapper->appendChild($element);
		}

		public function prepareReadableValue($data, $entry_id = null, $truncate = false, $defaultValue = null) {
			return isset($data['value']) ? $data['value'] : $entry_id;
		}

		public function prepareTableValue($data, XMLElement $link = null, $entry_id = null) {
			if (is_null($entry_id)) return;

			return parent::prepareTableValue(
				array(
					'value'		=> $entry_id
				), $link
			);
		}

	/*-------------------------------------------------------------------------
		Filtering:
	-------------------------------------------------------------------------*/

		public function displayDatasourceFilterPanel(&$wrapper, $data = null, $errors = null, $prefix = null, $postfix = null) {
			$wrapper = new XMLElement('p');
			$wrapper->setAttribute('style', 'display: none;');
		}

	/*-------------------------------------------------------------------------
		Sorting:
	-------------------------------------------------------------------------*/

		public function buildSortingSQL(&$joins, &$where, &$sort, $order = 'ASC') {
			$sort = 'ORDER BY ' . (strtolower($order) == 'random' ? 'RAND()' : "`e`.`id` {$order}");
		}

		public function buildSortingSelectSQL($sort, $order = 'ASC') {
			return null;
		}
	}

