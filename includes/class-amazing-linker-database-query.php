<?php
if( !class_exists( 'Amazing_Linker_Database_Query' ) ) {

	class Amazing_Linker_Database_Query {

		private $table;
		private $helper;

		public function __construct() {
			require_once AMAZING_LINKER_PLUGIN_DIR . 'includes/class-amazing-linker-product-table.php';
    		$this->table = new Amazing_Linker_Product_Table();

    		require_once AMAZING_LINKER_PLUGIN_DIR . 'includes/class-amazing-linker-helper.php';
    		$this->helper = new Amazing_Linker_Helper();

		}

		public function item_exists_database($item,$type) {
			global $wpdb;
			if($type == "single"){
				$check = $wpdb->get_results(
			        $wpdb->prepare(
			                "SELECT * from " . $this->table->amazing_linker_database_product_table() . " where asin=%s",array($item)
			        ), ARRAY_A
				);
			} elseif($type != "single") {
				$check = $wpdb->get_results(
			        $wpdb->prepare(
			                "SELECT * from " . $this->table->amazing_linker_database_list_table() . " where asin_node=%s AND type=%s",array($item,$type)
			        ), ARRAY_A
				);
			}
			
			if($check) {
				return true;
			} else {
				return false;
			}
		}

		public function list_item_query($item,$type) {
			global $wpdb;
			$list_item = $wpdb->get_results(
		        $wpdb->prepare(
		                "SELECT * from " . $this->table->amazing_linker_database_list_table() . " where asin_node=%s AND type=%s",array($item,$type)
		        ), ARRAY_A
			);

			return $list_item;
		}

		public function single_item_query($item) {
			global $wpdb;
			$item = $wpdb->get_results(
		        $wpdb->prepare(
		                "SELECT * from " . $this->table->amazing_linker_database_product_table() . " where asin=%s",array($item)
		        ), ARRAY_A
			);

			return $item;
		}

		public function comparison_table_query($id) {
			global $wpdb;
			$item = $wpdb->get_results(
		        $wpdb->prepare(
		                "SELECT * from " . $this->table->amazing_linker_database_comparison_table() . " where id=%d",array($id)
		        ), ARRAY_A
			);

			return $item;
		}

		public function asin_column_query () {
			global $wpdb;
			$database_products = $wpdb->get_results(
		        $wpdb->prepare(
		                "SELECT asin from " . $this->table->amazing_linker_database_product_table() . " where %d",array(1)
		        ), ARRAY_A
			);

			return $database_products;
		}

		public function list_asin_node_column_query($type) {
			global $wpdb;
			$database_list_products = $wpdb->get_results(
		        $wpdb->prepare(
		                "SELECT asin_node from " . $this->table->amazing_linker_database_list_table() . " where type=%s",array($type)
		        ), ARRAY_A
			);

			return $database_list_products;
		}

		public function delete_redundent_product_entry_database($item) {
			global $wpdb;
			$database_products = $wpdb->get_results(
		        $wpdb->prepare(
		                "DELETE from " . $this->table->amazing_linker_database_product_table() . " where asin=%s",array($item)
		        ), ARRAY_A
			);
		}

		public function delete_redundent_list_entry_database($item,$type) {
			global $wpdb;
			$database_list_products = $wpdb->get_results(
		        $wpdb->prepare(
		                "DELETE from " . $this->table->amazing_linker_database_list_table() . " where asin_node=%s AND type=%s",array($item,$type)
		        ), ARRAY_A
			);
		}

		public function insert_item_database($items) {

			foreach($items as $item) {

				if(gettype($item) != "string") {

					$images =null;
					$image_set=null;
					$price = null;
					$saleitem_reg_price = null;
					$sale_item_finder = null;
					$eligible_prime = '0';
					$title=null;
					$features=null;
					$review_rating=null;
					$review=null;
					$rating=null;
					$affiliate_link=null;
					$review_iframe=null;

					if(isset($item['SmallImage']))
						$images = $this->helper->array_push_assoc($images, 'SmallImage', $item['SmallImage']);
					if(isset($item['MediumImage']))
						$images = $this->helper->array_push_assoc($images, 'MediumImage', $item['MediumImage']);
					if(isset($item['LargeImage']))
						$images = $this->helper->array_push_assoc($images, 'LargeImage', $item['LargeImage']);

					if(!is_null($images)){
						$images = serialize($images);
					}

					if(isset($item['ImageSets']['ImageSet'])){
	                	$image_set = $item['ImageSets']['ImageSet'];
	                }

	                if(!is_null($image_set)){
	                	$image_set = serialize($image_set);
	                }
	                
			    	$price = $this->helper->price_grabber_database($item);
			    	
			    	$saleitem_reg_price = $this->helper->regular_price_for_sale_item_database($item);

			    	$review_rating = $this->helper->reviews_n_rating($item['ASIN']);

			    	if(!is_null($review_rating)) {
		            	if(array_key_exists('review', $review_rating)) {
		            		$review = $review_rating['review'];
		            	}
		            	if(array_key_exists('rating', $review_rating)) {
		            		$rating = $review_rating['rating'];
		            	}
		            }

			    	$eligible_prime = $this->helper->prime_eligible_grabber_database($item);

			    	$sale_item_finder = $this->helper->sale_item_finder($item);

			    	
			    	
			    	if(isset($item['ItemAttributes']['Title']))
			    		$title = sanitize_text_field($item['ItemAttributes']['Title']);

			    

			    	if(isset($item['ItemAttributes']['Feature'])) {
			    		if(is_array($item['ItemAttributes']['Feature'])){
			    			foreach($item['ItemAttributes']['Feature'] as $feature) {
			    				$features[] = sanitize_text_field($feature);
			    			}
			    		} else {
			    			$features[] = $item['ItemAttributes']['Feature'];
			    		}
			    		

			    		$features = serialize($features); 
			    	}
			    	
			    	$affiliate_link = $item['DetailPageURL'];
			    	$review_iframe = $item['CustomerReviews']['IFrameURL'];

					global $wpdb;
			    	$wpdb->insert($this->table->amazing_linker_database_product_table(), array(
	                "asin" => $item['ASIN'],
	                "title" => $title,
	                "images" => $images,            
	                "image_set" => $image_set,            
			    	"price" => $price,
			    	"saleitem_reg_price" => $saleitem_reg_price,
			    	"sale_lebel" => $sale_item_finder,
	                "features" => $features,
	                "review" => $review,
	                "rating" => $rating,
	                "eligible_prime" => $eligible_prime,
	                "affiliate_link" => $affiliate_link,
	                "review_iframe" => $review_iframe,
	                "updated_at" => time()
	            	));

				}
			}

		}

		public function update_item_database($items) {
			foreach($items as $item) {
				if(gettype($item) != "string") {
					$images =null;
					$image_set=null;
					$price = null;
					$saleitem_reg_price = null;
					$sale_item_finder = null;
					$eligible_prime = '0';
					$title=null;
					$features=null;
					$review_rating=null;
					$review=null;
					$rating=null;
					$affiliate_link=null;
					$review_iframe=null;

					if(isset($item['SmallImage']))
						$images = $this->helper->array_push_assoc($images, 'SmallImage', $item['SmallImage']);
					if(isset($item['MediumImage']))
						$images = $this->helper->array_push_assoc($images, 'MediumImage', $item['MediumImage']);
					if(isset($item['LargeImage']))
						$images = $this->helper->array_push_assoc($images, 'LargeImage', $item['LargeImage']);

					if(!is_null($images)){
						$images = serialize($images);
					}

					if(isset($item['ImageSets']['ImageSet'])){
	                	$image_set = $item['ImageSets']['ImageSet'];
	                }
	                
	                if(!is_null($image_set)){
	                	$image_set = serialize($image_set);
	                }
	                
			    	$price = $this->helper->price_grabber_database($item);
			    	
			    	$saleitem_reg_price = $this->helper->regular_price_for_sale_item_database($item);

			    	$review_rating = $this->helper->reviews_n_rating($item['ASIN']);

			    	if(!is_null($review_rating)) {
	                	if(array_key_exists('review', $review_rating)) {
	                		$review = $review_rating['review'];
	                	}
	                	if(array_key_exists('rating', $review_rating)) {
	                		$rating = $review_rating['rating'];
	                	}
	                }

			    	$eligible_prime = $this->helper->prime_eligible_grabber_database($item);

			    	$sale_item_finder = $this->helper->sale_item_finder($item);
			    	
			    	
			    	if(isset($item['ItemAttributes']['Title']))
			    		$title = strip_tags( stripslashes($item['ItemAttributes']['Title']));

			    

			    	if(isset($item['ItemAttributes']['Feature'])) {
			    		if(is_array($item['ItemAttributes']['Feature'])){
			    			foreach($item['ItemAttributes']['Feature'] as $feature) {
			    				$features[] = sanitize_text_field($feature);
			    			}
			    		} else {
			    			$features[] = $item['ItemAttributes']['Feature'];
			    		}
			    		

			    		$features = serialize($features); 
			    	}
			    	
			    	$affiliate_link = $item['DetailPageURL'];
			    	$review_iframe = $item['CustomerReviews']['IFrameURL'];

					global $wpdb;

			    	$wpdb->update($this->table->amazing_linker_database_product_table(), array(
	                "asin" => $item['ASIN'],
	                "title" => $title,
	                "images" => $images,            
	                "image_set" => $image_set,            
			    	"price" => $price,
			    	"saleitem_reg_price" => $saleitem_reg_price,
			    	"sale_lebel" => $sale_item_finder,
	                "features" => $features,
	                "review" => $review,
	                "rating" => $rating,
	                "eligible_prime" => $eligible_prime,
	                "affiliate_link" => $affiliate_link,
	                "review_iframe" => $review_iframe,
	                "updated_at" => time()
	            	), array( "asin" => $item['ASIN'] ));

				}
			}
		}

	}

}