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

if( !class_exists( 'Amazing_Linker_Manager_Callbacks' ) ) {

	class Amazing_Linker_Manager_Callbacks {

		public function amazing_linker_checkbox_field( $args )
		{
			$name = $args['label_for'];
			$classes = $args['class'];
			$option_name = $args['option_name'];
			$checkbox = get_option( $option_name );
			$checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;
			if($name == 'new_tab_open' || $name == 'nofollow_attribute' || $name == 'update_info' ) {
				/*$checked = true;*/
				$helper_text= '*ON is recommended';
			} elseif($name == 'show_review' || $name == 'show_rating') {
				$helper_text= '*OFF is recommended';
			} elseif($name == 'show_popup') {
				$helper_text= '*Popup only showing in text link & button link';
			} else {
				$helper_text='';
			}
			echo '<div class="' . $classes . '"><span class="off-text">Off</span><input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="1" ' . ( $checked ? 'checked' : '') . '><label for="' . $name . '"><div></div></label><span class="on-text">On</span><p style="display: inline-block;margin-left: 20px;font-weight: 700;color: #4B4B4B;font-size: 12px;">'.$helper_text.'</p></div>';
		}


		public function amazing_linker_input_field( $args ) {
			$name = $args['label_for'];
			$option_name = $args['option_name'];
			$options = (array)get_option( $option_name );
			if(array_key_exists($name, $options)) {
				$$name = $options[$name];				
			} else {
				$$name='';
			}

			if($name == "button_text") {
				echo '<input type="text" class="amazing-linker-input-field" name="' . $option_name . '[' . $name . ']" value="' . $$name . '" placeholder="'.$args['placeholder'].'"required/>';
			} elseif($name == "large_button_text") {
				echo '<input type="text" class="amazing-linker-input-field" name="' . $option_name . '[' . $name . ']" value="' . $$name . '" placeholder="'.$args['placeholder'].'"required/>';
			} elseif($name == "onelink_ad_instance_id") {
				echo '<input type="text" class="amazing-linker-input-field amazing-linker-onelink-input-field" name="' . $option_name . '[' . $name . ']" value="' . $$name . '" placeholder="'.$args['placeholder'].'"/><p class="amazing-linker-onelink-helper">OneLink is only available for United States associate</p>';
			} else {
				echo '<input type="text" class="amazing-linker-input-field" name="' . $option_name . '[' . $name . ']" value="' . $$name . '" placeholder="'.$args['placeholder'].'"required/>';
			}

			
		}


		public function amazing_linker_textarea_field( $args ) {

			$name = $args['label_for'];
			$option_name = $args['option_name'];
			$options = (array)get_option( $option_name );
			if(array_key_exists($name, $options)) {
				$$name = $options[$name];				
			} else {
				$$name='';
			}

			if($name == "custom_css") { 
				echo '<textarea id="code_editor_page_css" name="' . $option_name . '[' . $name . ']" class=" textarea">'.$$name.'</textarea>';
			} else {
				echo '<textarea name="' . $option_name . '[' . $name . ']" class=" textarea">'.$$name.'</textarea>';
			}
			
		}

		public function amazing_linker_colorpicker_field( $args ) {

			$name = $args['label_for'];
			$option_name = $args['option_name'];
			$options = (array)get_option( $option_name );
			if(array_key_exists($name, $options)) {
				$$name = $options[$name];				
			} else {
				if ($name == 'button_color') {
					$$name='#222f3d';
				} elseif($name == 'button_text_color') {
					$$name='#ffffff';
				}
			} 

			$colorpicker = '<input type="text" class="amazing-linker-input-field amazing-linker-colorpicker-input-field" name="' . $option_name . '[' . $name . ']" value="' . $$name . '" placeholder="'.$args['placeholder'].'"/>';
		    
		    echo $colorpicker;
		}


		public function amazing_linker_county_select_field ( $args ) {
			$name = $args['label_for'];
			$option_name = $args['option_name'];
			$options = (array)get_option( $option_name );
			if(array_key_exists($name, $options)) {
				$$name = $options[$name];				
			} else {
				$$name='';
			}
			
			$select = '<div class="country-select-field">';
			$select .= '<select class="amazing-linker-select-field change-country-flag" name="'.$option_name.'['.$name.']">';
	    
	        $select .= '<option value="com"' . selected( $$name, 'com', false) . '>United States (amazon.com)</option>';
	        $select .= '<option value="co.uk"' . selected( $$name, 'co.uk', false) . '>United Kingdom (amazon.co.uk)</option>';
	        $select .= '<option value="ca"' . selected( $$name, 'ca', false) . '>Canada (amazon.ca)</option>';
	        $select .= '<option value="es"' . selected( $$name, 'es', false) . '>España (amazon.es)</option>';
	        $select .= '<option value="fr"' . selected( $$name, 'fr', false) . '>France (amazon.fr)</option>';
	        $select .= '<option value="it"' . selected( $$name, 'it', false) . '>Italia (amazon.it)</option>';
	        $select .= '<option value="de"' . selected( $$name, 'de', false) . '>Deutschland (amazon.de)</option>';
	        $select .= '<option value="com.mx"' . selected( $$name, 'com.mx', false) . '>Maxico (amazon.com.mx)</option>';
	        $select .= '<option value="cn"' . selected( $$name, 'cn', false) . '>China (amazon.cn)</option>';
	        $select .= '<option value="co.jp"' . selected( $$name, 'co.jp', false) . '>Japan (amazon.co.jp)</option>';
	        $select .= '<option value="com.br"' . selected( $$name, 'com.br', false) . '>Brazil (amazon.com.br)</option>';
	        $select .= '<option value="in"' . selected( $$name, 'in', false) . '>India (amazon.in)</option>';
		    $select .= '</select>';
			switch ($$name) {
			    case "com":
			    	$select .= '<span style ="position:relative;top:13px;left:25px;" class="flag flag-us"></span>';
			        break;
			    case "co.uk":
			    	$select .= '<span style ="position:relative;top:13px;left:25px;" class="flag flag-gb"></span>';
			        break;
			    case "ca":
			    	$select .= '<span style ="position:relative;top:13px;left:25px;" class="flag flag-ca"></span>';
			        break;
			    case "es":
			    	$select .= '<span style ="position:relative;top:13px;left:25px;" class="flag flag-es"></span>';
			        break;
			    case "fr":
			    	$select .= '<span style ="position:relative;top:13px;left:25px;" class="flag flag-fr"></span>';
			        break;
			    case "it":
			    	$select .= '<span style ="position:relative;top:13px;left:25px;" class="flag flag-it"></span>';
			        break;
			    case "de":
			    	$select .= '<span style ="position:relative;top:13px;left:25px;" class="flag flag-de"></span>';
			        break;
			    case "com.mx":
			    	$select .= '<span style ="position:relative;top:13px;left:25px;" class="flag flag-mx"></span>';
			        break;
			    case "cn":
			    	$select .= '<span style ="position:relative;top:13px;left:25px;" class="flag flag-cn"></span>';
			        break;
			    case "co.jp":
			    	$select .= '<span style ="position:relative;top:13px;left:25px;" class="flag flag-jp"></span>';
			        break;
			    case "com.br":
			    	$select .= '<span style ="position:relative;top:13px;left:25px;" class="flag flag-br"></span>';
			        break;
			    case "in":
			    	$select .= '<span style ="position:relative;top:13px;left:25px;" class="flag flag-in"></span>';
			        break;
			    default:
			    	$select .= '<span style ="position:relative;top:13px;left:25px;" class="flag flag-us"></span>';
			        break;
				}
			$select .= '</div>';

		    echo $select;
		}


		public function amazing_linker_interval_select_field( $args ) {
			$name = $args['label_for'];
			$option_name = $args['option_name'];
			$options = (array)get_option( $option_name );
			if(array_key_exists($name, $options)) {
				$$name = $options[$name];				
			} else {
				$$name='21600';
			}
			
			$select = '<select class="amazing-linker-select-field" name="'.$option_name.'['.$name.']">';
	        $select .= '<option value="3600"' . selected( $$name, '3600', false) . '>1 Hour</option>';
	        $select .= '<option value="10800"' . selected( $$name, '10800', false) . '>3 Hours</option>';
	        $select .= '<option value="21600"' . selected( $$name, '21600', false) . '>6 Hours</option>';
	        $select .= '<option value="43200"' . selected( $$name, '43200', false) . '>12 Hours</option>';
	        $select .= '<option value="86400"' . selected( $$name, '86400', false) . '>24 Hours</option>';
		    $select .= '</select>';

		    $select .= '<button class="amazing-linker-update-btn" style="margin: 2px 0 0 20px;"><span class="dashicons dashicons-update"></span>&nbsp;&nbsp;&nbsp;Update Now</button>';
		    $select .= '<p class="amazing-linker-update-status"></p>';

		    echo $select;
		}


		public function input_field_sanitization( $options ) { 
	
			$sanitized_options = array();
			
			foreach( $options as $option_key => $option_val ) {
				$sanitized_options[ $option_key ] = sanitize_text_field( $option_val );
			} // end foreach
			
			return $sanitized_options;
			
		}

		public function select_field_sanitization( $options ) { 
    
		    $sanitized_options = array();

		    foreach( $options as $option_key => $option_val ) {
		    if ( isset( $options[$option_key] ) && array_key_exists( $option_key, $options ) )
		        $sanitized_options[$option_key] = $options[$option_key];
			}
		   return $sanitized_options;

		}

		public function textarea_field_sanitization( $options ) { 
    
		    $sanitized_options = array();

		    foreach( $options as $option_key => $option_val ) {
		    if ( isset( $options[$option_key] ) && ! empty( $options[$option_key] ) )
		        $sanitized_options[$option_key] = sanitize_text_field( $options[$option_key] );
			}
		    
		    return $sanitized_options;
		    
		}

		public function checkbox_field_sanitization( $options )
		{

			$sanitized_options = array();

			foreach( $options as $option_key => $option_val ) {
				$sanitized_options[$option_key] = isset($options[$option_key]) ? ($options[$option_key] ? $option_val : false) : false;
			}	

			return $sanitized_options;
		}

		public function credential_tab_helper_text() {
			echo '<p style="font-weight:700;font-size:14px;"><a href="https://coderockz.com/docs/amazing-linker/amazon-product-advertising-api/creating-credentials-for-amazon-product-advertising-api/" target="_blank" >Click here</a> to know how to get your Access Key ID & Secret Access Key</p>';
		}

		public function associate_tab_helper_text() {
			echo '<p style="font-weight:700;font-size:14px;"><a href="https://coderockz.com/docs/amazing-linker/amazon-product-advertising-api/how-to-get-your-onelink-ad-instance-id/" target="_blank" >Click here</a> to know how to get your Onelink Ad Instance ID</p>';
		}


	}

}