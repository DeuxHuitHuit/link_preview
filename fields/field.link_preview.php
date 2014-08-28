<?php

	/*
	Copyight: Deux Huit Huit 2013
	License: MIT, http://deuxhuithuit.mit-license.org
	*/

	if (!defined('__IN_SYMPHONY__')) die('<h2>Symphony Error</h2><p>You cannot directly access this file</p>');

	require_once(TOOLKIT . '/class.field.php');

	/**
	 *
	 * Field class that will represent the meta data about link creation
	 * @author Deux Huit Huit
	 *
	 */
	class FieldLink_Preview extends Field {

		/**
		 *
		 * Name of the field table
		 * @var string
		 */
		const FIELD_TBL_NAME = 'tbl_fields_link_preview';

		/**
		 *
		 * Constructor for the Field object
		 * @param mixed $parent
		 */
		public function __construct(){
			// call the parent constructor
			parent::__construct();
			// set the name of the field
			$this->_name = __('Link Preview');
			// permits to make it required
			$this->_required = false;
			// permits the make it show in the table columns
			$this->_showcolumn = true;
			// set as not required by default
			$this->set('required', 'no');
		}

		public function isSortable(){
			return false;
		}

		public function canFilter(){
			return false;
		}

		public function canImport(){
			return false;
		}

		public function canPrePopulate(){
			return false;
		}
		
		public function allowDatasourceOutputGrouping(){
			return false;
		}
		public function requiresSQLGrouping(){
			return false;
		}

		public function allowDatasourceParamOutput(){
			return false;
		}

		/* ********** INPUT AND FIELD *********** */


		/**
		 *
		 * Validates input
		 * Called before <code>processRawFieldData</code>
		 * @param $data
		 * @param $message
		 * @param $entry_id
		 */
		public function checkPostFieldData($data, &$message, $entry_id = null){
			// Always valid!
			$message = NULL;
			return self::__OK__;
		}


		/**
		 *
		 * Process entries data before saving into database.
		 *
		 * @param array $data
		 * @param int $status
		 * @param boolean $simulate
		 * @param int $entry_id
		 *
		 * @return Array - data to be inserted into DB
		 */
		public function processRawFieldData($data, &$status, &$message = null, $simulate = false, $entry_id = null) {
			$status = self::__OK__;

			return $data;
		}

		/**
		 * This function permits parsing different field settings values
		 *
		 * @param array $settings
		 *	the data array to initialize if necessary.
		 */
		public function setFromPOST(Array $settings = array()) {

			// call the default behavior
			parent::setFromPOST($settings);

			// declare a new setting array
			$new_settings = array();

			// always display in table mode
			$new_settings['show_column'] = $settings['show_column'];

			// set new settings
			$new_settings['format'] = $settings['format'];

			// save it into the array
			$this->setArray($new_settings);
		}

		/**
		 *
		 * Save field settings into the field's table
		 */
		public function commit() {

			// if the default implementation works...
			if(!parent::commit()) return FALSE;

			$id = $this->get('id');

			// exit if there is no id
			if($id == false) return FALSE;

			// declare an array contains the field's settings
			$settings = array();

			// the field id
			$settings['field_id'] = $id;

			// the url format
			$settings['format'] = $this->get('format');
			
			// the anchor label
			$settings['anchor_label'] = $this->get('anchor_label');
			
			// display url
			$settings['display_url'] = $this->get('display_url') == 'yes' ? 'yes' : 'no';

			// officialy save it
			return FieldManager::saveSettings( $id, $settings);
		}

		public function entryDataCleanup($entry_id, $data=NULL){
			// do nothing since we do not have any data table
		}


		/* ******* DATA SOURCE ******* */

		/**
		 * Appends data into the XML tree of a Data Source
		 * @param $wrapper
		 * @param $data
		 */
		public function appendFormattedElement(&$wrapper, $data) {
			// NOTHING
		}




		/* ********* UI *********** */


		/**
		 *
		 * Builds the UI for the publish page
		 * @param XMLElement $wrapper
		 * @param mixed $data
		 * @param mixed $flagWithError
		 * @param string $fieldnamePrefix
		 * @param string $fieldnamePostfix
		 */
		public function displayPublishPanel(&$wrapper, $data=NULL, $flagWithError=NULL, $fieldnamePrefix=NULL, $fieldnamePostfix=NULL, $entry_id = null) {
			//var_dump($data, $this->get());die;
			
			$format = $this->get('format');
			$url = $this->generateUrlFromFormat($entry_id, $format, $this->get('parent_section'));
			$anchor_label = $this->get('anchor_label');
			
			// set the label : use `preview` if no ànchor label` is defined
			$label = $anchor_label != '' ? $anchor_label : __('Preview');
			
			$wrapper->setAttribute('data-format', $format);
			$wrapper->setAttribute('data-url', $url);
			$wrapper->setAttribute('data-text', $label);
		}
		
		private function getSystemData($entryId) {
			return array(
				'system:id' => $entryId,
				'system:time' => DateTimeObj::format('now','H:i'),
				'system:date' => DateTimeObj::format('now', 'Y-m-d'),
				'system:day' => DateTimeObj::format('now','d'),
				'system:month' => DateTimeObj::format('now','m'),
				'system:year' => DateTimeObj::format('now','Y'),
			);
		}
		
		private function generateUrlFromFormat($entryId, $format, $sectionId) {
			// Get all the data for this entry
			$entryData = EntryManager::fetch($entryId);
			// Get info for each field
			$section = SectionManager::fetch($sectionId);
			$fields = $section->fetchFields();
			
			if (empty($entryData)) {
				return 'Entry not found';
			}
			
			// capture system params
			$sysData = $this->getSystemData($entryId);
			
			// get the actual data
			$entryData = $entryData[0]->getData(null, false);
			
			// find all "variables" and replace them
			return preg_replace_callback('({\$([a-zA-Z0-9:_-]+)})', function (array $matches) use ($sysData, $entryData, $fields) {
				//var_dump($matches);
				$variable = $matches[1];
				$value = '';
				$qualifier = '';
								
				//var_dump($matches);die;
				//var_dump($fields); die;
				//var_dump($sysData, $entryData);die
					
				// check variable for namescape and qualifier
				if (strpos($variable, 'system:') !== FALSE) {
					// case '$system:variable'
					if (substr_count($variable, ':') == 1) {
						$value = $sysData[$variable];
					// case '$system:variable:qualifier"
					} else {
						$fragments = explode(':',$variable,3);
						$variable = $fragments[1];
						$qualifier = $fragments[2];
						switch ($variable) {
							// format system dates
							case 'date':
								$value = DateTimeObj::format('now',$qualifier);
								break;
							// ... other system data?
						}
					}
				// case '$variable:qualifier'	
				} else if (strpos($variable, ':') !== FALSE) {
					$fragments = preg_split('[:]', $variable);
					$qualifier = $fragments[1];
					$variable = $fragments[0];
				}
				
				// check fields if no value is set yet
				if (strlen($value) < 1) {
					// find value by handle
					foreach ($fields as $field) {
						if ($field->get('element_name') == $variable) {
							$fieldValues = $entryData[intval($field->get('field_id'))];
							
							//var_dump($fieldValues);
							//var_dump($field->handle());
							
							// handle special cases
							switch ($field->handle()) {
								case 'selectbox_link':
									$relatedEntry = EntryManager::fetch($fieldValues['relation_id']);
									$relatedFields = $field->get('related_field_id');
									$relatedData = $relatedEntry[0]->getData($relatedFields[0], false);
									
									//var_dump($relatedData, $fieldValues, $field->get());die;
									$value = $relatedData['handle'];
									if (empty($value) || $qualifier == 'value') {
										$value = $relatedData['value'];
									}
									break;
								case 'date':
									$value = DateTimeObj::format($fieldValues['value'], $qualifier);
									break;
								case 'datetime':
									$value = DateTimeObj::format($fieldValues['start'], $qualifier);
									break;
								default:
									$value = $fieldValues['handle'];
									if (empty($value) || $qualifier == 'value') {
										$value = $fieldValues['value'];
									}
									break;
							}
							break;
						}
					}
				}
				
				// Shortcut for entry ID (only used if no value was found for a field that might use the 'id'-handle)
				if (strlen($value) < 1 && $variable == 'id') {
					$value = $sysData['system:'.$variable];
				}
				
				return $value;
				
			}, $format);
		}

		/**
		 *
		 * Builds the UI for the field's settings when creating/editing a section
		 * @param XMLElement $wrapper
		 * @param array $errors
		 */
		public function displaySettingsPanel(&$wrapper, $errors=NULL){

			/* first line, label and such */
			parent::displaySettingsPanel($wrapper, $errors);
			
			/* new line, anchor label */
			$anchor_wrap = new XMLElement('div', NULL, array('class'=>'link_preview_anchor'));
			$anchor_title = new XMLElement('label', __('Anchor Label <i>Optional</i>'));
			$anchor_title->appendChild(Widget::Input('fields['.$this->get('sortorder').'][anchor_label]', $this->get('anchor_label')));
			$anchor_wrap->appendChild($anchor_title);
			
			/* new line, url format */
			$url_wrap = new XMLElement('div', NULL, array('class'=>'link_preview_url'));
			$url_title = new XMLElement('label', __('URL Format <i>Use {$param} syntax</i>'));
			$url_title->appendChild(Widget::Input('fields['.$this->get('sortorder').'][format]', $this->get('format')));
			$url_wrap->appendChild($url_title);
			
			/* new line, check boxes */
			$chk_wrap = new XMLElement('div', NULL, array('class' => 'two columns'));
			$this->appendShowColumnCheckbox($chk_wrap);
			$this->appendDisplayUrlCheckbox($chk_wrap);
			
			$wrapper->appendChild($anchor_wrap);
			$wrapper->appendChild($url_wrap);
			$wrapper->appendChild($chk_wrap);
		}
		
		
		/**
		 *
		 * Utility (private) function to append a checkbox for the 'display url' setting
		 * @param XMLElement $wrapper
		 */
		private function appendDisplayUrlCheckbox(&$wrapper) {
			$label = new XMLElement('label', NULL, array('class' => 'column'));
			$chk = new XMLElement('input', NULL, array('name' => 'fields['.$this->get('sortorder').'][display_url]', 'type' => 'checkbox', 'value' => 'yes'));
			
			$label->appendChild($chk);
			$label->setValue(__('Display URL in entries table (Instead of anchor label)'), false);

			if ($this->get('display_url') == 'yes') {
				$chk->setAttribute('checked','checked');
			}
			
			$wrapper->appendChild($label);
		}
		
		
		private function createInput($text, $key, $errors=NULL) {
			$order = $this->get('sortorder');
			$lbl = new XMLElement('label', __($text), array('class' => 'column'));
			$input = new XMLElement('input', NULL, array(
					'type' => 'text',
					'value' => $this->get($key),
					'name' => "fields[$order][$key]"
			));
			$input->setSelfClosingTag(true);
		
			$lbl->prependChild($input);
		
			if (isset($errors[$key])) {
				$lbl = Widget::wrapFormElementWithError($lbl, $errors[$key]);
			}
		
			return $lbl;
		}


		/**
		 *
		 * Build the UI for the table view
		 * @param Array $data
		 * @param XMLElement $link
		 * @return string - the html of the link
		 */
		public function prepareTableValue($data, XMLElement $link = null, $entry_id = null){
			$url = $this->generateUrlFromFormat($entry_id, $this->get('format'), $this->get('parent_section'));
			
			// does this cell serve as a link ?
			if (!$link){
				// if not, wrap our html with a external link to the resource url
				$link = new XMLElement('a');
				
				$link->setAttribute('href', $url);
				$link->setAttribute('target', '_blank');
			}
			
			$display_url = $this->get('display_url');
			$anchor_label = $this->get('anchor_label');
			
			// set the label
			if ($display_url == 'yes') {
				$link->setValue($url);
			} else if ($anchor_label) {
				$link->setValue($anchor_label);
			} else {
				$link->setValue($this->get('label'));
			}
			
			return $link->generate();
		}
		
		
		/**
		 *
		 * This function allows Fields to cleanup any additional things before it is removed
		 * from the section.
		 * @return boolean
		 */
		public function tearDown() {
			// @TODO
			return false;
		}
		
		
		/* ********* SQL Data Definition ************* */
		
		/**
		 *
		 * Creates table needed for entries of invidual fields
		 */
		public function createTable(){
			// no table is needed for entries
			return true;
		}
		
		/**
		 * Creates the table needed for the settings of the field
		 */
		public static function createFieldTable() {
			
			$tbl = self::FIELD_TBL_NAME;
			
			return Symphony::Database()->query("
				CREATE TABLE IF NOT EXISTS `$tbl` (
					`id` 				int(11) unsigned NOT NULL auto_increment,
					`field_id` 		int(11) unsigned NOT NULL,
					`format`			varchar(255) NULL,
					`anchor_label` varchar(255) NULL,
					`display_url`	ENUM('yes', 'no') DEFAULT 'no',
					PRIMARY KEY (`id`),
					KEY `field_id` (`field_id`)
				)  ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
			");
		}
		
		/**
		 * Updates the table for the new settings: `anchor_label`
		 */
		public static function updateFieldTable_AnchorLabel() {

			$tbl = self::FIELD_TBL_NAME;

			return Symphony::Database()->query("
				ALTER TABLE  `$tbl`
					ADD COLUMN `anchor_label` varchar(255) NULL
			");
		}
		
		/**
		 * Updates the table for the new settings: `display_url`
		 */
		public static function updateFieldTable_DisplayUrl() {

			$tbl = self::FIELD_TBL_NAME;

			return Symphony::Database()->query("
				ALTER TABLE  `$tbl`
					ADD COLUMN `display_url` ENUM('yes','no') DEFAULT 'no'
			");
		}


		/**
		 *
		 * Drops the table needed for the settings of the field
		 */
		public static function deleteFieldTable() {
			$tbl = self::FIELD_TBL_NAME;

			return Symphony::Database()->query("
				DROP TABLE IF EXISTS `$tbl`
			");
		}

	}

