<?php

if( !class_exists( 'Amazing_Linker_Product_Widget' ) ) {

	class Amazing_Linker_Product_Widget extends WP_Widget { 

	    private $helper;

	    private $api;

	    private $database_query;

	    private $widget_template;

	    function __construct() {
	      parent::__construct(
	        'amazing_linker_product_widget', // Base ID
	        'amazing-linker- ' .esc_html__( 'Amazing Linker Product Widget', 'amazing-linker' ), // Name
	        array( 'description' => esc_html__( 'Amazing linker widget for displaying amazon product', 'amazing-linker' ), ) // Args
	      );

	      require_once AMAZING_LINKER_PLUGIN_DIR . 'admin/includes/class-amazing-linker-api.php';
		  $this->api = new Amazing_Linker_Api();
		  $this->api->set_credentials();

	      require_once AMAZING_LINKER_PLUGIN_DIR . 'includes/class-amazing-linker-helper.php';
    	  $this->helper = new Amazing_Linker_Helper();

    	  require_once AMAZING_LINKER_PLUGIN_DIR . 'includes/class-amazing-linker-database-query.php';
    	  $this->database_query = new Amazing_Linker_Database_Query();

    	  require_once AMAZING_LINKER_PLUGIN_DIR . 'public/includes/class-amazing-linker-widget-template.php';
    	  $this->widget_template = new Amazing_Linker_Widget_Template();
	    
	    }
	  
	    /**
	     * Front-end display of widget.
	     *
	     * @see WP_Widget::widget()
	     *
	     * @param array $args     Widget arguments.
	     * @param array $instance Saved values from database.
	     */
	    public function widget( $args, $instance ) {
	      $args['before_widget'] = '<div class="widget">';
	      $args['after_widget'] = '</div>';

	      echo $args['before_widget']; // Whatever you want to display before widget (<div>, etc)
	      if ( ! empty( $instance['title'] ) ) {
	        echo $args['before_title'] . apply_filters( 'widget_title', sanitize_text_field($instance['title']) ) . $args['after_title'];
	      }
	      // Widget Content Output

	      $items = array_map('trim', explode(",",sanitize_text_field($instance['asin']))); 

	      if ( $instance['layout'] == 'horizontal' ) {
	      	
	      	$this->widget_template->amazing_linker_product_horizontal_widget($items);	

	      } elseif ($instance['layout'] == 'vertical') {

	      	$this->widget_template->amazing_linker_product_vertical_widget($items);

	      } else {
	      	echo '';
	      }


	      echo $args['after_widget']; // Whatever you want to display after widget (</div>, etc)
	    }
	  
	    /**
	     * Back-end widget form.
	     *
	     * @see WP_Widget::form()
	     *
	     * @param array $instance Previously saved values from database.
	     */
	    public function form( $instance ) {
	      $title = ! empty( $instance['title'] ) ? sanitize_text_field($instance['title']) : esc_html__( 'Editor\'s Top Pick', 'amazing-linker' ); 
	      $asin = ! empty( $instance['asin'] ) ? sanitize_text_field($instance['asin']) : esc_html__( '', 'amazing-linker' ); 
	      $layout = ! empty( $instance['layout'] ) ? sanitize_text_field($instance['layout']) : esc_html__( 'default', 'amazing-linker' );
	  
	      ?>

	       <img src="<?php echo AMAZING_LINKER_PLUGIN_URL ?>admin/images/amazing-linker-logo.png" alt="amazing-linker-logo.png" style="width: 80px;display: block;margin: 20px auto 10px;">
	            
	      <!-- TITLE -->
	      <p>
	        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
	          <?php esc_attr_e( 'Title:', 'amazing-linker' ); ?>
	        </label> 

	        <input 
	          class="widefat" 
	          id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
	          name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
	          type="text" placeholder="title"
	          value="<?php echo esc_attr( $title ); ?>">
	      </p>

	      <!-- ASIN -->
	      <p>
	        <label for="<?php echo esc_attr( $this->get_field_id( 'asin' ) ); ?>">
	          <?php esc_attr_e( 'ASIN/ISBN/SKU/UPC/EAN:', 'amazing-linker' ); ?>
	        </label> 

	        <input 
	          class="widefat" 
	          id="<?php echo esc_attr( $this->get_field_id( 'asin' ) ); ?>" 
	          name="<?php echo esc_attr( $this->get_field_name( 'asin' ) ); ?>" 
	          type="text" placeholder="ASIN/ISBN/SKU/UPC/EAN"
	          value="<?php echo esc_attr( $asin ); ?>">
	          <small><i>To show more than one product, simply enter multiple ASIN/ISBN/SKU/UPC/EAN and separate them with comma: e.g. B07H9XKDPM,B0748FG2Z6</i></small>
	      </p>

	      <!-- LAYOUT -->
	      <p>
	        <label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>">
	          <?php esc_attr_e( 'Layout:', 'amazing-linker' ); ?>
	        </label> 

	        <select 
	          class="widefat" 
	          id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" 
	          name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>">
	          <option value="horizontal" <?php echo ($layout == 'horizontal') ? 'selected' : ''; ?>>
	            Horizontal
	          </option>
	          <option value="vertical" <?php echo ($layout == 'vertical') ? 'selected' : ''; ?>>
	            Vertical
	          </option>
	        </select>
	      </p>

	      <?php 
	    }
	  
	    /**
	     * Sanitize widget form values as they are saved.
	     *
	     * @see WP_Widget::update()
	     *
	     * @param array $new_instance Values just sent to be saved.
	     * @param array $old_instance Previously saved values from database.
	     *
	     * @return array Updated safe values to be saved.
	     */
	    public function update( $new_instance, $old_instance ) {
	      $instance = array();
	      $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	      $instance['asin'] = ( ! empty( $new_instance['asin'] ) ) ? strip_tags( $new_instance['asin'] ) : '';
	      $instance['layout'] = ( ! empty( $new_instance['layout'] ) ) ? strip_tags( $new_instance['layout'] ) : '';

	      if($instance['asin'] != '') {
	      		$items = [];
				$widget_items = array_map('trim', explode(",",sanitize_text_field($instance['asin'])));
				foreach($widget_items as $widget_item) {
					array_push($items,$widget_item);
				}

				$items = array_values(array_unique($items,SORT_REGULAR));

				/*$items = $this->helper->session_management($items);*/
				$items_confirm = [];
				foreach($items as $item) {
					if(!$this->database_query->item_exists_database($item,"single")) {
						$items_confirm[] = $item;
					}
				}
				$items = $items_confirm;

				$item_count = count($items);

				if(isset($items) && count($items)>0) {
					$items = $this->api->item_lookup($items);
				}

			    if($item_count == 1 ){
			    	$item = [];
			    	array_push($item,$items);
			    	$items = $item;	

			    }

			    if(isset($items) && count($items)>0) {
			    	$this->database_query->insert_item_database($items);
			    }
	      }

	      return $instance;
	    }

	}
}