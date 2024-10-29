<?php

require_once dirname(__FILE__) . '/../libs/apai-io/autoload.php';

use ApaiIO\ApaiIO;
use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Lookup;
use ApaiIO\Operations\BrowseNodeLookup;
use ApaiIO\Operations\Search;
use ApaiIO\Operations\SimilarityLookup;
use GuzzleHttp\Client;
use ApaiIO\Request\GuzzleRequest;
use ApaiIO\ResponseTransformer\XmlToArray;


if( !class_exists( 'Amazing_Linker_Api' ) ) {

	class Amazing_Linker_Api {

		private $api;

        private $itemResponseGroups;

        private $similarityResponseGroups;

        private $nodeResponseGroups;

        private $credential;

        private $associate;

        private $aws_api_key;

        private $aws_api_secret_key;

        private $country;

        private $aws_associate_tag;

	    public function __construct() {

            $this->itemResponseGroups = array('ItemAttributes', 'Small', 'EditorialReview','Images', 'OfferFull', 'Reviews', 'Similarities', 'SalesRank', 'VariationOffers','BrowseNodes');
            $this->similarityResponseGroups = array('ItemAttributes', 'Small', 'EditorialReview','Images', 'OfferFull', 'Reviews', 'Similarities', 'SalesRank', 'VariationOffers');
            $this->nodeResponseGroups = array('BrowseNodeInfo', 'MostGifted', 'NewReleases','MostWishedFor', 'TopSellers');

            $this->credential = (array)get_option('amazing_linker_credential_tab_option');
            $this->associate = (array)get_option('amazing_linker_associate_tab_option');

            if( !empty($this->credential) ) {
                array_key_exists('access_key_id', $this->credential) ? $this->aws_api_key = $this->credential['access_key_id'] : $this->aws_api_key = '';
                array_key_exists('secret_access_key', $this->credential) ? $this->aws_api_secret_key = $this->credential['secret_access_key'] : $this->aws_api_secret_key = '';
            }

            if( !empty($this->associate) ) {
                array_key_exists('associate_country', $this->associate) ? $this->country = $this->associate['associate_country'] : $this->country = 'com';
                array_key_exists('tracking_id', $this->associate) ? $this->aws_associate_tag = $this->associate['tracking_id'] : $this->aws_associate_tag = '';
            }
            
	    }


	    public function set_credentials() {
	    	
            $conf = new GenericConfiguration();

            $client = new Client();
			$request = new GuzzleRequest($client);

            $conf
                ->setCountry($this->country)
                ->setAccessKey($this->aws_api_key)
                ->setSecretKey($this->aws_api_secret_key)
                ->setAssociateTag($this->aws_associate_tag)
                ->setRequest($request)
                ->setResponseTransformer(new XmlToArray());
                

            $this->api = new ApaiIO($conf);
            
	    }


	    public function item_lookup($asin, $responseGroups = array()) {

            if ( empty($responseGroups) ) {
                $responseGroups = $this->itemResponseGroups;
            }

	    	$lookup = new Lookup();

			$lookup->setItemId($asin);
			$lookup->setResponseGroup($responseGroups);


			try {
                $response = $this->api->runOperation($lookup);
				if(!empty ( $response['Items']['Request']['Errors']['Error'] )) {
					/*print("<pre>".print_r($response['Items']['Request']['Errors']['Error'],true)."</pre>");*/
                    return 'Invalid Product ASIN/ISBN/SKU/UPC/EAN or Something Went Wrong';
				} else {
                    /*print("<pre>".print_r($response['Items']['Item'],true)."</pre>");*/
					return $response['Items']['Item'];
				}
            } catch (Exception $e) {
                return 'Something Went Wrong. Check Credentials';
            }		

	    }

	    public function similarity_lookup($itemid, $type = 'Intersection', $responseGroups = array()) {

            if ( empty($responseGroups) ) {
                $responseGroups = $this->similarityResponseGroups;
            }

            $similarityLookup = new SimilarityLookup();
            $similarityLookup->setItemId($itemid);
            $similarityLookup->setResponseGroup($responseGroups);

            try {
                $response = $this->api->runOperation($similarityLookup);
				if( !isset($response['Items']['Item']) ) {
					return 'No similar products';
				} else {
                    /*print("<pre>".print_r($response['Items']['Item'],true)."</pre>");*/
					return $response['Items']['Item'];
				}
            } catch (Exception $e) {
                return 'Something Went Wrong. Check Credentials';
            }

        }

        public function browse_node_lookup($node, $responseGroups = array()) {

            if ( empty($responseGroups) ) {
                $responseGroups = $this->nodeResponseGroups;
            }

            $browseNodeLookup = new BrowseNodeLookup();
            $browseNodeLookup->setNodeId($node);
            $browseNodeLookup->setResponseGroup($responseGroups);

            try {
                $response = $this->api->runOperation($browseNodeLookup);
				if(!empty ( $response['BrowseNodes']['Request']['Errors']['Error'] )) {
					return 'Invalid Product Category';
				} else {
                    /*print("<pre>".print_r($response['BrowseNodes']['BrowseNode'],true)."</pre>");*/
					return $response['BrowseNodes']['BrowseNode'];
				}
            } catch (Exception $e) {
                return 'Something Went Wrong. Check Credentials';
            }
        }

        public function item_search($keyword, $responseGroups = array()) {

            $keyword = str_replace(' ','_',$keyword);
            if ( empty($responseGroups) ) {
                $responseGroups = $this->itemResponseGroups;
            }

            $search = new Search();
            $search->setPage(1);
            $search->setCategory('All');
            $search->setCondition('New');
            $search->setKeywords($keyword);
            $search->setResponseGroup($responseGroups);

            try {
                $response = $this->api->runOperation($search);
                if(!empty ( $response['Items']['Request']['Errors']['Error'] )) {
                    return 'No Items Found or Something Went Wrong';
                } else {
                    /*print("<pre>".print_r($response['Items']['Item'],true)."</pre>");*/
                    return $response['Items']['Item'];
                }
            } catch (Exception $e) {
                return 'Something Went Wrong. Check Credentials';
            }
        }

	}

}