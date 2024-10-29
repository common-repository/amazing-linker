<?php

require_once AMAZING_LINKER_PLUGIN_DIR . 'includes/class-amazing-linker-background-process.php';

class Amazing_Linker_Background_Update extends Amazing_Linker_Background_Process {


	private $helper;

	private $api;

	private $database_query;

	public function __construct() {
		
		parent::__construct();

		require_once AMAZING_LINKER_PLUGIN_DIR . 'includes/class-amazing-linker-helper.php';
		$this->helper = new Amazing_Linker_Helper();

		require_once AMAZING_LINKER_PLUGIN_DIR . 'admin/includes/class-amazing-linker-api.php';
		$this->api = new Amazing_Linker_Api();
		$this->api->set_credentials();

		require_once AMAZING_LINKER_PLUGIN_DIR . 'includes/class-amazing-linker-database-query.php';
		$this->database_query = new Amazing_Linker_Database_Query();

	}

	
	protected function task( $asin_or_node ) {

		$single_items = [];


        
        if(strpos($asin_or_node, '-single')!==false) {
			$lastpos = strpos($asin_or_node,'-single');
			$single_item = substr($asin_or_node, 0, $lastpos);
			array_push($single_items,$single_item);
			
			$single_items = array_values(array_unique($single_items,SORT_REGULAR));

			$item_count = count($single_items);
            if(isset($single_items) && count($single_items)>0) {
                $single_items = $this->api->item_lookup($single_items);
            }
            if($item_count == 1 ){
                $temp_item = [];
                array_push($temp_item,$single_items);
                $single_items = $temp_item;

            }
            if(isset($single_items) && count($single_items)>0) {
                $this->database_query->update_item_database($single_items);
            }

		}

		return false;
	}

	protected function complete() {
		parent::complete();

		// Show notice to user or perform some other arbitrary task...
	}
}