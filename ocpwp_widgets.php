<?php
	/*Category Widget*/
	function ocpwp_widget_category_display($args) {
		$options = get_option("ocpwp_widget_category_display");
		echo $args['before_widget'];		
		echo $args['before_title'];
		echo $options['title'];
		echo $args['after_title'];		
		echo ocpwp_category_list();
		echo $args['after_widget'];
	}

	/* User controls for Category Widget. */
	function ocpwp_widget_category_control() {
		$options = get_option('ocpwp_widget_category_display');
	
		if (!is_array($options)) {
			$options = array(
				'title' => 'Product Category',				
			);
		}
	
		if ($_POST['opencart_product_display_submit']) {
			$options['title'] = sanitize_text_field($_POST['ocpwp_widget1_title']);			
			update_option('ocpwp_widget_category_display', $options);
		}
	
		echo '<p>';
		echo '<label for="ocpwp_widget1_title">Title:</label><br />';
		echo '<input type="text" id="ocpwp_widget1_title" name="ocpwp_widget1_title" value="'.$options['title'].'" /><br /><br />';
		echo '<input type="hidden" id="opencart_product_display_submit" name="opencart_product_display_submit" value="1" />';
		echo '</p>';
	}


	/*Product Widget*/
	function ocpwp_widget_product_display($args) {
		$options = get_option("ocpwp_widget_product");
		echo $args['before_widget'];		
		echo $args['before_title'];
		echo $options['title'];
		echo $args['after_title'];		
		echo ocpwp_getproducts_bycat($options['prod_cat'], $options['num_products']);
		echo $args['after_widget'];
	}
	
	/* User controls for Product Widget. */
	function ocpwp_widget_product_control() {
		$options = get_option('ocpwp_widget_product');
	
		if (!is_array($options)) {
			$options = array(
				'title' => 'Our Product',
				'prod_cat' => 1,
				'num_products' => 3,
			);
		}
	
		if ($_POST['ocw2_submit']) {
			$options['title'] 		 = sanitize_text_field($_POST['ocw2_title']);
			$options['prod_cat'] 	 = sanitize_text_field($_POST['ocw2_prod_cat']);
			$options['num_products'] = sanitize_text_field($_POST['ocw2_num_prod']);
			
			update_option('ocpwp_widget_product', $options);
		}

		echo '<p>';
		echo '<label for="ocw2_title">Title: </label><br />';
		echo '<input type="text" id="ocw2_title" name="ocw2_title" value="'.$options['title'].'" />';
		echo '<br /><br />';		
		
		echo '<label for="ocw2_prod_cat">Select Category: </label><br />';
		echo '<select name="ocw2_prod_cat" id="ocw2_prod_cat">';
		echo ocpwp_getcategory($options['prod_cat']);
		echo '</select>';
		echo '<br /><br />';
		
		echo '<label for="ocw2_num_prod">Number of products to show: </label><br />';
		echo '<input type="text" id="ocw2_num_prod" name="ocw2_num_prod" value="'.$options['num_products'].'" />';
		
		echo '<input type="hidden" id="ocw2_submit" name="ocw2_submit" value="1" />';
		echo '</p>';
	}

	function ocpwp_widget_generator_init() {
		wp_register_sidebar_widget('ocpwp_widget_1', 'Opencart Category Display', 'ocpwp_widget_category_display', 
		  array('description' => 'Displays list of category from Opencart store'));
		wp_register_widget_control('ocpwp_widget_1', 'Opencart Category Display', 'ocpwp_widget_category_control');
		
		wp_register_sidebar_widget('ocpwp_widget_2', 'Opencart Product Display', 'ocpwp_widget_product_display', 
		  array('description' => 'Displays list of product from Opencart store'));
		wp_register_widget_control('ocpwp_widget_2', 'Opencart Product Display', 'ocpwp_widget_product_control');
	}
?>