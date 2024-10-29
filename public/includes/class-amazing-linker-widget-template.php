<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://coderockz.com
 * @since      1.0.0
 *
 * @package    Amazing_Linker
 * @subpackage Amazing_Linker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Amazing_Linker
 * @subpackage Amazing_Linker/admin
 * @author     CodeRockz
 */

if( !class_exists( 'Amazing_Linker_Widget_Template' ) ) {

	class Amazing_Linker_Widget_Template {

		private $helper;

		private $database_query;

		private $new_tab;

		private $no_follow;

		private $update_info;

		private $show_review;

		private $show_rating;

		private $button_text;

		private $button_color;

		private $button_text_color;

		public function __construct() {

    		require_once AMAZING_LINKER_PLUGIN_DIR . 'includes/class-amazing-linker-helper.php';
    		$this->helper = new Amazing_Linker_Helper();

    		require_once AMAZING_LINKER_PLUGIN_DIR . 'includes/class-amazing-linker-database-query.php';
    		$this->database_query = new Amazing_Linker_Database_Query();

    		$this->settings = (array)get_option('amazing_linker_settings_tab_option');

    		$this->new_tab = '_blank';
            $this->no_follow = 'nofollow';
            $this->update_info = true;
            $this->show_review = false;
            $this->show_rating = false;
            $this->button_text = "SEE DETAILS";
            $this->button_color = "#222f3d";
            $this->button_text_color = "#ffffff";
            
            if( $this->settings != false ) {
                if(array_key_exists('new_tab_open', $this->settings)) {
                	$this->settings['new_tab_open'] ? $this->new_tab = '_blank' : $this->new_tab = '';
                } 
                if(array_key_exists('nofollow_attribute', $this->settings)) {
                	$this->settings['nofollow_attribute'] ? $this->no_follow = 'nofollow' : $this->no_follow = '';
                }
                if(array_key_exists('update_info', $this->settings)) {
                	$this->settings['update_info'] ? $this->update_info = true : $this->update_info = false;
                }
                if(array_key_exists('show_review', $this->settings)) {
                	$this->settings['show_review'] ? $this->show_review = true : $this->show_review = false;
                }
                if(array_key_exists('show_rating', $this->settings)) {
                	$this->settings['show_rating'] ? $this->show_rating = true : $this->show_rating = false;
                }

                if(array_key_exists('button_text', $this->settings)) {
                	$this->button_text = sanitize_text_field($this->settings['button_text']);
                }

                if(array_key_exists('button_color', $this->settings)) {
                	$this->button_color = sanitize_text_field($this->settings['button_color']);
                }

                if(array_key_exists('button_text_color', $this->settings)) {
                	$this->button_text_color = sanitize_text_field($this->settings['button_text_color']);
                }
            }

		}

		public function amazing_linker_product_horizontal_widget($items) {
			$updated_at = [];
			$horizontal_product_widget = '';
			$horizontal_product_widget .= '<div class="amazing-linker-product-widget-horizontal-wrapper">'; 
		    foreach($items as $item) {

				$horizontal_product_widget_item = $this->database_query->single_item_query($item);

				if(count($horizontal_product_widget_item) == 0) {
					return;
				} else {

					$images = unserialize($horizontal_product_widget_item[0]['images']);
					$image_set = unserialize($horizontal_product_widget_item[0]['image_set']);
					
					$image_url = $this->helper->image_grabber($images,$image_set);

					$updated_at[] = (int)$horizontal_product_widget_item[0]['updated_at'];

					$horizontal_product_widget .= '<div class="amazing-linker-product-widget-horizontal-item">';
					if(!is_null($horizontal_product_widget_item[0]['sale_lebel'])) {
						$horizontal_product_widget .= '<p class="amazing-linker-sale-text">Sale</p>';
					}
			    	$horizontal_product_widget .= '<div class="amazing-linker-product-widget-horizontal-image"><img src="'.sanitize_text_field(urldecode($image_url)).'" alt="'.sanitize_text_field($horizontal_product_widget_item[0]['title']).'"></div>';		    	
			    	$horizontal_product_widget .='<div class="amazing-linker-product-widget-horizontal-title"><a href="'.urldecode($horizontal_product_widget_item[0]['affiliate_link']).'" rel="'.$this->no_follow.'" target="'.$this->new_tab.'">'.sanitize_text_field($horizontal_product_widget_item[0]['title']).'</a></div>';
			    	$horizontal_product_widget .= '<div class="hr"></div>';
			    	$horizontal_product_widget .='<ul class="amazing-linker-product-widget-horizontal-features">';
			    	if($this->show_review) {
			    		(!is_null($horizontal_product_widget_item[0]['review']) ? $horizontal_product_widget .='<li>'.sanitize_text_field($horizontal_product_widget_item[0]['review']).' Reviews</li>' : $horizontal_product_widget .= '<li>No Review</li>');
			    		$horizontal_product_widget .= '<div class="hr"></div>';
			    	}
			    	if($this->show_rating) {
			    		(!is_null($horizontal_product_widget_item[0]['rating']) ? $horizontal_product_widget .='<li><div class="amazing-linker-al-stars">'.sanitize_text_field($horizontal_product_widget_item[0]['rating']).'</div><span class="amazing-linker-rating-text">'.sanitize_text_field($horizontal_product_widget_item[0]['rating']).' out of 5 stars</span></li>' : '');
			    		$horizontal_product_widget .= '<div class="hr"></div>';
			    	}

			    	if(sanitize_text_field($horizontal_product_widget_item[0]['price']) == 'Unavailable'){
			    		$horizontal_product_widget .='<li class="hr-widget-product-price-block" style="color:#B12704!important;"><section class="amazing-linker-verticaly-middle">'.sanitize_text_field($horizontal_product_widget_item[0]['price']).'</section></li>';
			    	} elseif(is_null($horizontal_product_widget_item[0]['price'])) {
			    		$horizontal_product_widget .='<li class="hr-widget-product-price-block" style="color:#B12704!important;"><section class="amazing-linker-verticaly-middle">Unavailable</section></li>';
			    	} else {

		    			if(!is_null($horizontal_product_widget_item[0]['saleitem_reg_price'])) {
		    				$horizontal_product_widget .='<li class="hr-widget-product-price-block"><section class="amazing-linker-verticaly-middle"><span style="text-decoration: line-through;color:#ccb3c1!important">'.sanitize_text_field($horizontal_product_widget_item[0]['saleitem_reg_price']).'</span>&nbsp;';
		    			} else {
		    				$horizontal_product_widget .='<li class="hr-widget-product-price-block"><section class="amazing-linker-verticaly-middle">';
		    			}

		    			$horizontal_product_widget .= '<span>'.sanitize_text_field($horizontal_product_widget_item[0]['price']).'</span>';
			    		if(sanitize_text_field($horizontal_product_widget_item[0]['eligible_prime']) == true) {
			    			$horizontal_product_widget .='&nbsp;<img src="'.AMAZING_LINKER_PLUGIN_URL.'public/images/prime-logo.png" class="amazing-linker-prime-logo" alt="prime-logo">';
			    		}
			    		$horizontal_product_widget .='</section></li>';
			    		
			    	}

			    	$horizontal_product_widget .= '<div class="hr"></div>';
			    	
			    	$horizontal_product_widget .= '</ul>';
			    	$horizontal_product_widget .= '<div class="amazing-linker-product-widget-horizontal-select"><a style="background:'.$this->button_color.'!important;background: linear-gradient(to right, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -webkit-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -moz-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;color:'.$this->button_text_color.'!important;" href="'.urldecode($horizontal_product_widget_item[0]['affiliate_link']).'" rel="'.$this->no_follow.'" target="'.$this->new_tab.'">'.$this->button_text.'</a></div>';
			    	$horizontal_product_widget .= '</div>';
			    	
			    }
			    
			}
			$horizontal_product_widget .= '<div style="clear: both;"></div>';
			if($this->update_info) {
				$horizontal_product_widget .= $this->helper->amazon_price_notice($updated_at,"widget");
			}
			$horizontal_product_widget .= '</div>';

			echo $horizontal_product_widget;
		}

		public function amazing_linker_product_vertical_widget($items) {
			$updated_at = [];
			$vertical_product_widget = '';
			$vertical_product_widget .= '<div class="amazing-linker-product-widget-vertical-wrapper">'; 
		    foreach($items as $item) {

				$vertical_product_widget_item = $this->database_query->single_item_query($item);

				if(count($vertical_product_widget_item) == 0) {
					return;
				} else {

					$images = unserialize($vertical_product_widget_item[0]['images']);
					$image_set = unserialize($vertical_product_widget_item[0]['image_set']);
					
					$image_url = $this->helper->image_grabber($images,$image_set);

					$updated_at[] = (int)$vertical_product_widget_item[0]['updated_at'];

					$vertical_product_widget .= '<div class="amazing-linker-product-widget-vertical-item">';
					if(!is_null($vertical_product_widget_item[0]['sale_lebel'])) {
						$vertical_product_widget .= '<p class="amazing-linker-sale-text">Sale</p>';
					}
					$vertical_product_widget .='<div class="amazing-linker-product-widget-vertical-title"><a href="'.urldecode($vertical_product_widget_item[0]['affiliate_link']).'" rel="'.$this->no_follow.'" target="'.$this->new_tab.'">'.sanitize_text_field($vertical_product_widget_item[0]['title']).'</a></div>';
			    	$vertical_product_widget .= '<div class="amazing-linker-product-widget-vertical-image"><img src="'.sanitize_text_field(urldecode($image_url)).'" alt="'.sanitize_text_field($vertical_product_widget_item[0]['title']).'"></div>';	
			    	$vertical_product_widget .='<ul class="amazing-linker-product-widget-vertical-features">';
			    	if($this->show_review) {
			    		(!is_null($vertical_product_widget_item[0]['review']) ? $vertical_product_widget .='<li>'.sanitize_text_field($vertical_product_widget_item[0]['review']).' Reviews</li>' : $vertical_product_widget .= '<li>No Review</li>');
			    	}
			    	if($this->show_rating) {
			    		(!is_null($vertical_product_widget_item[0]['rating']) ? $vertical_product_widget .='<li><div class="amazing-linker-al-stars">'.sanitize_text_field($vertical_product_widget_item[0]['rating']).'</div><span class="amazing-linker-rating-text">'.sanitize_text_field($vertical_product_widget_item[0]['rating']).' out of 5 stars</span></li>' : '');
			    	}

			    	if(sanitize_text_field($vertical_product_widget_item[0]['price']) == 'Unavailable'){
			    		$vertical_product_widget .='<li style="color:#B12704!important;"><span class="amazing-linker-price-lebel">Price: </span>'.sanitize_text_field($vertical_product_widget_item[0]['price']).'</li>';
			    	} elseif(is_null($vertical_product_widget_item[0]['price'])) {
			    		$vertical_product_widget .='<li style="color:#B12704!important;"><span class="amazing-linker-price-lebel">Price: </span>Unavailable</li>';
			    	} else {

		    			if(!is_null($vertical_product_widget_item[0]['saleitem_reg_price'])) {
		    				$vertical_product_widget .='<li><span style="text-decoration: line-through;color:#ccb3c1!important">'.sanitize_text_field($vertical_product_widget_item[0]['saleitem_reg_price']).'</span>&nbsp;';
		    			} else {
		    				$vertical_product_widget .='<li>';
		    			}

		    			$vertical_product_widget .= '<span>'.sanitize_text_field($vertical_product_widget_item[0]['price']).'</span>';
			    		if(sanitize_text_field($vertical_product_widget_item[0]['eligible_prime']) == true) {
			    			$vertical_product_widget .='&nbsp;<img src="'.AMAZING_LINKER_PLUGIN_URL.'public/images/prime-logo.png" class="amazing-linker-prime-logo" alt="prime-logo">';
			    		}
			    		$vertical_product_widget .='</li>';
			    		
			    	}

			    	$vertical_product_widget .= '</ul>';
			    	$vertical_product_widget .= '<div style="clear: both;"></div>';
			    	$vertical_product_widget .= '<div class="amazing-linker-product-widget-vertical-select"><a style="background:'.$this->button_color.'!important;background: linear-gradient(to right, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -webkit-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -moz-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;color:'.$this->button_text_color.'!important;" href="'.urldecode($vertical_product_widget_item[0]['affiliate_link']).'" rel="'.$this->no_follow.'" target="'.$this->new_tab.'">'.$this->button_text.'</a></div>';
			    	$vertical_product_widget .= '</div>';
			    	
			    }
			    
			}
			$vertical_product_widget .= '<div style="clear: both;"></div>';
			if($this->update_info) {
				$vertical_product_widget .= $this->helper->amazon_price_notice($updated_at,"widget");
			}
			$vertical_product_widget .= '</div>';

			echo $vertical_product_widget;
		}

	}

}