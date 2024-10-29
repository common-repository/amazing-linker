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

if( !class_exists( 'Amazing_Linker_Shortcode_Template' ) ) {

	class Amazing_Linker_Shortcode_Template {

		private $helper;

		private $database_query;

		private $settings;

		private $new_tab;

		private $no_follow;

		private $update_info;

		private $show_review;

		private $show_rating;

		private $show_popup;

		private $button_text;
		
		private $large_button_text;

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
            $this->show_popup = false;
            $this->button_text = "SEE DETAILS";
            $this->large_button_text = "See Details At Amazon";
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

                if(array_key_exists('show_popup', $this->settings)) {
                	$this->settings['show_popup'] ? $this->show_popup = true : $this->show_popup = false;
                }

                if(array_key_exists('button_text', $this->settings)) {
                	$this->button_text = sanitize_text_field($this->settings['button_text']);
                }

                if(array_key_exists('large_button_text', $this->settings)) {
                	$this->large_button_text = sanitize_text_field($this->settings['large_button_text']);
                }

                if(array_key_exists('button_color', $this->settings)) {
                	$this->button_color = sanitize_text_field($this->settings['button_color']);
                }

                if(array_key_exists('button_text_color', $this->settings)) {
                	$this->button_text_color = sanitize_text_field($this->settings['button_text_color']);
                }
            }

		}

		public function amazing_linker_shortcode_hr_list($items) {
			$updated_at = [];
			$list = '';
			$list .= '<div class="amazing-linker-hr-list-wrapper">'; 
		    foreach($items as $item) {

				$list_item = $this->database_query->single_item_query($item);

				if(count($list_item) == 0) {
					return;
				} else {


					$images = unserialize($list_item[0]['images']);
					$image_set = unserialize($list_item[0]['image_set']);
					
					$image_url = $this->helper->image_grabber($images,$image_set);

					$updated_at[] = (int)$list_item[0]['updated_at'];

					$list .= '<div class="amazing-linker-hr-list-item">';
					if(!is_null($list_item[0]['sale_lebel'])) {
						$list .= '<p class="amazing-linker-sale-text">Sale</p>';
					}

			    	$list .= '<div class="amazing-linker-hr-list-item-image"><img src="'.sanitize_text_field(urldecode($image_url)).'" alt="'.sanitize_text_field($list_item[0]['title']).'"/></div>';		    	
			    	$list .='<div class="amazing-linker-hr-list-item-title"><a href="'.urldecode($list_item[0]['affiliate_link']).'" rel="'.$this->no_follow.'" target="'.$this->new_tab.'">'.sanitize_text_field($list_item[0]['title']).'</a></div>';
			    	$list .= '<div class="hr"></div>';
			    	$list .='<ul class="amazing-linker-hr-list-item-features">';
			    	
			    	if($this->show_review) {
						(!is_null($list_item[0]['review']) ? $list .='<li>'.sanitize_text_field($list_item[0]['review']).' Reviews</li>' : $list .= '<li>No Review</li>');
						$list .= '<div class="hr"></div>';
					}
			    	
					if($this->show_rating) {
						(!is_null($list_item[0]['rating']) ? $list .='<li><div class="amazing-linker-al-stars">'.sanitize_text_field($list_item[0]['rating']).'</div><span class="amazing-linker-rating-text">'.sanitize_text_field($list_item[0]['rating']).' out of 5 stars</span></li>' : '');
			    		$list .= '<div class="hr"></div>';
					}

			    	if(sanitize_text_field($list_item[0]['price']) == 'Unavailable'){
			    		$list .='<li class="amazing-linker-hr-list-price-block" style="color:#B12704!important;"><section class="amazing-linker-verticaly-middle">'.sanitize_text_field($list_item[0]['price']).'</section></li>';
			    	} elseif(is_null($list_item[0]['price'])) {
			    		$list .='<li class="amazing-linker-hr-list-price-block" style="color:#B12704!important;"><section class="amazing-linker-verticaly-middle">Unavailable</section></li>';
			    	} else {

		    			if(!is_null($list_item[0]['saleitem_reg_price'])) {
		    				$list .='<li class="amazing-linker-hr-list-price-block"><section class="amazing-linker-verticaly-middle"><span style="text-decoration: line-through;color:#ccb3c1!important">'.sanitize_text_field($list_item[0]['saleitem_reg_price']).'</span>&nbsp;';
		    			} else {
		    				$list .='<li class="amazing-linker-hr-list-price-block"><section class="amazing-linker-verticaly-middle">';
		    			}

		    			$list .= '<span>'.sanitize_text_field($list_item[0]['price']).'</span>';
			    		if(sanitize_text_field($list_item[0]['eligible_prime']) == true) {
			    			$list .='&nbsp;<img src="'.AMAZING_LINKER_PLUGIN_URL.'public/images/prime-logo.png" class="amazing-linker-prime-logo" alt="prime-logo">';
			    		}
			    		$list .='</section></li>';
			    					    		
			    	}

			    	$list .= '<div class="hr"></div>';
			    	
			    	$list .= '</ul>';
			    	$list .= '<div class="amazing-linker-hr-list-item-select"><a style="background:'.$this->button_color.'!important;background: linear-gradient(to right, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -webkit-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -moz-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;color:'.$this->button_text_color.'!important;" href="'.urldecode($list_item[0]['affiliate_link']).'" rel="'.$this->no_follow.'" target="'.$this->new_tab.'">'.$this->button_text.'</a></div>';
			    	$list .= '</div>';
			    	
			    }
			    
			}
			$list .= '<div style="clear: both;"></div>';
			
			if($this->update_info) {
				$list .= $this->helper->amazon_price_notice($updated_at);
			}
			
			$list .= '</div>';

			return $list;
		}

		public function amazing_linker_shortcode_link_button($item) {
			$updated_at = [];
			$single_item = $this->database_query->single_item_query($item);

	    	if(count($single_item) == 0) {
				return;
			} else {
			    
			    $images = unserialize($single_item[0]['images']);
				$image_set = unserialize($single_item[0]['image_set']);
				
				$image_url = $this->helper->image_grabber($images,$image_set);
				$updated_at[] = (int)$single_item[0]['updated_at'];

                $name = sanitize_text_field($single_item[0]['title']);
		    	
		    	$link_button = '';
                
                $link_button .= '<div class="amazing-linker-product-popup" style="left: 50%;transform: translateX(-50%);">';
	  
        	    if($this->show_popup) {
        	        $link_button .= '<span class="amazing-linker-product-popup-details" style="display:block;bottom: 65px;background:'.$this->button_color.'!important;background: linear-gradient(to right, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -webkit-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -moz-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;color:'.$this->button_text_color.'!important;">';
        	    } else {
        	      $link_button .= '<span class="amazing-linker-product-popup-details" style="display:none;bottom: 65px;background:'.$this->button_color.'!important;background: linear-gradient(to right, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -webkit-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -moz-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;">';
        	    }
	  
	  	        $link_button .= '<div style="width:375px;overflow:hidden;padding:15px">';
		  	    $link_button .= '<div style="float:left;width:105px">';
		  		$link_button .= '<img src="'.sanitize_text_field(urldecode($image_url)).'" style="width:100%;">';
		  	    $link_button .= '</div>';
		  	    $link_button .= '<div style="float:left;width:225px;margin-left:15px;">';
		  		$link_button .= '<p class="amazing-linker-popup-title-text" style="color:'.$this->button_text_color.'!important;margin:0;padding-top:0;padding-bottom:0;line-height:18px;font-size: 15px!important;">'.$name.'</p>';
		  		
		  		if($this->show_review) { 
		    		(!is_null($single_item[0]['review']) ? $link_button .='<div class="amazing-linker-rating-class" style="color:'.$this->button_text_color.'!important;font-size: 15px!important;">'.sanitize_text_field($single_item[0]['review']).' Reviews<br>' : $product_box .='<div class="amazing-linker-rating-class" style="color:'.$this->button_text_color.'!important;font-size: 15px!important;">No Review<br>');
		    	}
		    	
		    	if($this->show_rating) {
		    		(!is_null($single_item[0]['rating']) ? $link_button .='<div class="amazing-linker-al-stars" style="position:relative;left: unset;-webkit-transform:unset;transform: unset;">'.sanitize_text_field($single_item[0]['rating']).'</div><span class="amazing-linker-rating-text" style="position: absolute;bottom: 69px;right: 95px;">('.sanitize_text_field($single_item[0]['rating']).'/5.0)</span></div>' : '');
		    	}

		    	if(sanitize_text_field($single_item[0]['price']) == 'Unavailable'){
		    		$link_button .='<p class="amazing-linker-hr-list-price-block" style="margin: 0;padding-top:0;padding-bottom:0;text-align:unset!important;" style="color:#B12704!important;font-size: 15px!important;">'.sanitize_text_field($single_item[0]['price']).'</p>';
		    	} elseif(is_null($single_item[0]['price'])) {
		    		$link_button .='<p class="amazing-linker-hr-list-price-block" style="margin: 0;padding-top:0;padding-bottom:0;text-align:unset!important;" style="color:#B12704!important;font-size: 15px!important;">Unavailable</p>';
		    	} else {

	    			if(!is_null($single_item[0]['saleitem_reg_price'])) {
	    				$link_button .='<p class="amazing-linker-hr-list-price-block" style="margin: 0;padding-top:0;padding-bottom:0;text-align:unset!important;font-size: 15px!important;"><span style="text-decoration: line-through;color:#ccb3c1!important">'.sanitize_text_field($single_item[0]['saleitem_reg_price']).'</span>&nbsp;';
	    			} else {
	    				$link_button .='<p class="amazing-linker-hr-list-price-block" style="margin: 0;padding-top:0;padding-bottom:0;text-align:unset!important;color:'.$this->button_text_color.'!important;font-size: 15px!important;">';
	    			}

	    			$link_button .= '<span style="color:'.$this->button_text_color.'!important;">'.sanitize_text_field($single_item[0]['price']).'</span>';
		    		if(sanitize_text_field($single_item[0]['eligible_prime']) == true) {
		    			$link_button .='&nbsp;<img src="'.AMAZING_LINKER_PLUGIN_URL.'public/images/prime-logo.png" class="amazing-linker-prime-logo" alt="prime-logo">';
		    		}
		    		$link_button .='</p>';
		    		
		    	}
		  	    $link_button .= '</div>';
		  	    $link_button .= '<div style="clear:both"></div>';
    		  	if($this->update_info) {
        			$link_button .= $this->helper->amazon_price_notice($updated_at,"popup_box");
        		}
    	  	    $link_button .= '</div>';
    	  	
        	    $link_button .= '</span>';
        	  
        	    $link_button .= '<div class="amazing-linker-link-button-wrapper">';
                $link_button .= '<div class="link-button-btn" style="padding:15px 0;"><a style="background:'.$this->button_color.'!important;background: linear-gradient(to right, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -webkit-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -moz-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;color:'.$this->button_text_color.'!important;" href="'.urldecode($single_item[0]['affiliate_link']).'" rel="'.$this->no_follow.'" target="'.$this->new_tab.'" style="font-family: Raleway;border-bottom:0">'.$this->large_button_text.'&nbsp;<img src="'.AMAZING_LINKER_PLUGIN_URL.'/public/images/arrow-right.png" style="display: inline-block;width: 20px;vertical-align: middle;margin-bottom: 0!important;" alt="arrow-right"></a></div>';
                $link_button .= '</div>';
        	    $link_button .= '</div>';
		    	
          		return $link_button;
          	}
		}


		public function amazing_linker_shortcode_link_image($item,$width) {
			$single_item = $this->database_query->single_item_query($item);
			$width = sanitize_text_field($width);
	    	if(count($single_item) == 0) {
				return;
			} else {
				$images = unserialize($single_item[0]['images']);
				$image_set = unserialize($single_item[0]['image_set']);
				
				$image_url = $this->helper->image_grabber($images,$image_set);

		    	$link_image = '';
		    	$link_image .= '<div class="amazing-linker-link-button-wrapper">';
		    	$link_image .= '<a href="'.urldecode($single_item[0]['affiliate_link']).'" rel="'.$this->no_follow.'" target="'.$this->new_tab.'"><img src="'.urldecode($image_url).'" alt="'.sanitize_text_field($single_item[0]['title']).'" style="width:'.$width.';"></a>';		    	
		    	$link_image .= '</div>';
          		return $link_image;
          	}
		}

		public function amazing_linker_shortcode_link_text($item) {
			$single_item = $this->database_query->single_item_query($item);

	    	if(count($single_item) == 0) {
				return;
			} else {
				$images = unserialize($single_item[0]['images']);
				$image_set = unserialize($single_item[0]['image_set']);
				
				$image_url = $this->helper->image_grabber($images,$image_set);
				$updated_at[] = (int)$single_item[0]['updated_at'];

                $name = sanitize_text_field($single_item[0]['title']);

		    	$link_text = '';
		    	
		    	$link_text .= '<div class="amazing-linker-product-popup">';
	  
        	    if($this->show_popup) {
        	        $link_text .= '<span class="amazing-linker-product-popup-details" style="display:block;bottom: 32px;background:'.$this->button_color.'!important;background: linear-gradient(to right, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -webkit-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -moz-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;color:'.$this->button_text_color.'!important;">';
        	    } else {
        	      $link_text .= '<span class="amazing-linker-product-popup-details" style="display:none;bottom: 27px;background:'.$this->button_color.'!important;background: linear-gradient(to right, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -webkit-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;background: -moz-linear-gradient(left, '.$this->button_color.' 0%, '.$this->helper->darken_color($this->button_color).' 100%, #C59237 100%)!important;">';
        	    }
	  
    	  	    $link_text .= '<div style="width:375px;overflow:hidden;padding:15px">';
    		  	$link_text .= '<div style="float:left;width:105px">';
    		  		$link_text .= '<img src="'.sanitize_text_field(urldecode($image_url)).'" style="width:100%;">';
    		  	$link_text .= '</div>';
    		  	$link_text .= '<div style="float:left;width:225px;margin-left:15px;">';
    		  		$link_text .= '<p class="amazing-linker-popup-title-text" style="color:'.$this->button_text_color.'!important;margin:0;padding-top:0;padding-bottom:0;line-height:18px;font-size: 15px!important;">'.$name.'</p>';
    		  		if($this->show_review) { 
    		    		(!is_null($single_item[0]['review']) ? $link_text .='<div class="amazing-linker-rating-class" style="color:'.$this->button_text_color.'!important;font-size: 15px!important;">'.sanitize_text_field($single_item[0]['review']).' Reviews<br>' : $product_box .='<div class="amazing-linker-rating-class" style="color:'.$this->button_text_color.'!important;font-size: 15px!important;">No Review<br>');
    		    	}
    		    	if($this->show_rating) {
    		    		(!is_null($single_item[0]['rating']) ? $link_text .='<div class="amazing-linker-al-stars" style="position:relative;left: unset;-webkit-transform:unset;transform: unset;">'.sanitize_text_field($single_item[0]['rating']).'</div><span class="amazing-linker-rating-text" style="position: absolute;bottom: 69px;right: 95px;">('.sanitize_text_field($single_item[0]['rating']).'/5.0)</span></div>' : '');
    		    	}
    
    		    	if(sanitize_text_field($single_item[0]['price']) == 'Unavailable'){
    		    		$link_text .='<p class="amazing-linker-hr-list-price-block" style="margin: 0;padding-top:0;padding-bottom:0;text-align:unset!important;" style="color:#B12704!important;font-size: 15px!important;">'.sanitize_text_field($single_item[0]['price']).'</p>';
    		    	} elseif(is_null($single_item[0]['price'])) {
    		    		$link_text .='<p class="amazing-linker-hr-list-price-block" style="margin: 0;padding-top:0;padding-bottom:0;text-align:unset!important;" style="color:#B12704!important;font-size: 15px!important;">Unavailable</p>';
    		    	} else {
    
    	    			if(!is_null($single_item[0]['saleitem_reg_price'])) {
    	    				$link_text .='<p class="amazing-linker-hr-list-price-block" style="margin: 0;padding-top:0;padding-bottom:0;text-align:unset!important;"><span style="text-decoration: line-through;color:#ccb3c1!important;font-size: 15px!important;">'.sanitize_text_field($single_item[0]['saleitem_reg_price']).'</span>&nbsp;';
    	    			} else {
    	    				$link_text .='<p class="amazing-linker-hr-list-price-block" style="margin: 0;padding-top:0;padding-bottom:0;text-align:unset!important;font-size: 15px!important;">';
    	    			}
    
    	    			$link_text .= '<span style="color:'.$this->button_text_color.'!important;">'.sanitize_text_field($single_item[0]['price']).'</span>';
    		    		if(sanitize_text_field($single_item[0]['eligible_prime']) == true) {
    		    			$link_text .='&nbsp;<img src="'.AMAZING_LINKER_PLUGIN_URL.'public/images/prime-logo.png" class="amazing-linker-prime-logo" alt="prime-logo">';
    		    		}
    		    		$link_text .='</p>';
    		    		
    		    	}
    		  	$link_text .= '</div>';
    		  	$link_text .= '<div style="clear:both"></div>';
    		  	if($this->update_info) {
        			$link_text .= $this->helper->amazon_price_notice($updated_at,"popup_box");
        		}
        	  	$link_text .= '</div>';
        	  	
            	$link_text .= '</span>';
            	  
            	$link_text .= '<a class="amazing-linker-link-text" href="'.urldecode($single_item[0]['affiliate_link']).'" rel="'.$this->no_follow.'" target="'.$this->new_tab.'">'.sanitize_text_field($single_item[0]['title']).'</a>';
            	$link_text .= '</div>';
		    
		    	
          		return $link_text;
          	}
		}

	}

}