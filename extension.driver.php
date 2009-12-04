<?php
	
	class Extension_SystemIdField extends Extension {
	/*-------------------------------------------------------------------------
		Definition:
	-------------------------------------------------------------------------*/
		
		public function about() {
			return array(
				'name'			=> 'Field: System Id',
				'version'		=> '1.0.1',
				'release-date'	=> '2009-12-04',
				'author'		=> array(
					'name'			=> 'Rowan Lewis',
					'website'		=> 'http://rowanlewis.com/',
					'email'			=> 'me@rowanlewis.com'
				),
				'description' => 'Displays system ids in a column on the publish page.'
			);
		}
	}
	
?>
