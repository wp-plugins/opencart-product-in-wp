<?php 
/*
	Plugin Name: Opencart Product in WP
	Plugin URI: http://www.rajibdewan.com/opencart-product-wp/
	Description: Plugin for displaying list of Category or Product from external Opencart store
	Author: Md. Rajib Dewan
	Version: 1.0.1
	Author URI: http://www.rajibdewan.com
*/
	function ocpwp_admin() {
		include('ocpwp_admin.php');
	}
		 
	function ocpwp_admin_actions() {
	  	add_options_page("Opencart Product in WP", "Opencart Product in WP", 1, "ocpwp_admin", "ocpwp_admin");		
	}
	
	function enqueue_scripts() {
		$path = plugins_url('', __FILE__ );
		wp_enqueue_style( 'style-ocpwp', $path . '/css/ocpwp.css' );		
	}
	
	add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );
	add_action( 'admin_menu', 'ocpwp_admin_actions' );
	add_action( 'plugins_loaded', 'ocpwp_widget_generator_init' );	
	
	function ocpwp_connectdb(){
		$connetion = new wpdb(get_option('ocpwp_dbuser'), get_option('ocpwp_dbpwd'), get_option('ocpwp_dbname'), get_option('ocpwp_dbhost'));		
		return $connetion;
	}
	function ocpwp_getcategory($selected_item){
	  $ocdb = ocpwp_connectdb();
	  if(!$ocdb->ready){
	   return '<span class="err">Unable to connect Opencart store</span>';
	  } else {
		$store_url = get_option('ocpwp_store_url');
		$table_pre = get_option('ocpwp_dbprefix');
		$table_pre .= '_';
		
		$query = "SELECT cat.category_id, cd.name
					FROM {$table_pre}category AS cat
					LEFT JOIN {$table_pre}category_description AS cd ON cat.category_id = cd.category_id
					WHERE cat.parent_id = 0
					ORDER BY cd.name ASC";
		$categories = $ocdb->get_results($query);
		
		$options = '';
		foreach($categories as $category){
			$sel = ($selected_item == $category->category_id) ? 'selected="selected"' : '';
			$options .= '<option value="'.$category->category_id.'" '.$sel.'>'.$category->name.'</option>';

			$query2 = "SELECT cat.category_id, cd.name
					FROM {$table_pre}category AS cat
					LEFT JOIN {$table_pre}category_description AS cd ON cat.category_id = cd.category_id
					WHERE cat.parent_id = {$category->category_id}
					ORDER BY cd.name ASC";
			$sub_categories = $ocdb->get_results($query2);
			foreach($sub_categories as $sub_category){
				$sel = ($selected_item == $sub_category->category_id) ? 'selected="selected"' : '';
				$options .= '<option value="'.$sub_category->category_id.'" '.$sel.'> -- '.$sub_category->name.'</option>';
			}
		}
		return $options;
	  }
	}
	function ocpwp_category_list(){
	  $ocdb = ocpwp_connectdb();
	  
	  if(!$ocdb->ready){
	   return '<span class="err">Unable to connect Opencart store</span>';
	  } else {
		$store_url = get_option('ocpwp_store_url');
		$table_pre = get_option('ocpwp_dbprefix');
		$table_pre .= '_';
		
		$query = "SELECT cat.category_id, cd.name, cat.image
					FROM {$table_pre}category AS cat
					LEFT JOIN {$table_pre}category_description AS cd ON cat.category_id = cd.category_id
					WHERE cat.parent_id = 0
					ORDER BY cd.name ASC";		
		$categories = $ocdb->get_results($query);
				
		if($categories){			
		  $options = '<ul class="ocpwp-widget-category">';
		  foreach($categories as $category){
			$options .= '<li><a target="_blank" href="'.$store_url.'index.php?route=product/category&path=' . $category->category_id . '">'. $category->name .'</a>';
			
			$query2 = "SELECT cat.category_id, cd.name
					  FROM {$table_pre}category AS cat
					  LEFT JOIN {$table_pre}category_description AS cd ON cat.category_id = cd.category_id
					  WHERE cat.parent_id = {$category->category_id}
					  ORDER BY cd.name ASC";
			$sub_categories = $ocdb->get_results($query2);
			  
			if($sub_categories){
			  $options .= '<ul class="ocpwp-widget-subcat">';				
			  foreach($sub_categories as $sub_category){
				  $options .= '<li><a target="_blank" href="'.$store_url.'index.php?route=product/category&path=' . $sub_category->category_id . '">'. $sub_category->name .'</a></li>';
			  }
			  $options .= '</ul>';			 
			}			  
			echo '</li>';
		  }
		  $options .= '</ul>';
		  return $options;
		}
	  }
	}
	
	function ocpwp_getproducts_bycat($category_id=1, $prod_count=1) {
	  $ocdb = ocpwp_connectdb();
	  if(!$ocdb->ready){
	   return '<span class="err">Unable to connect Opencart store</span>';
	  } else {
		$store_url 	  = get_option('ocpwp_store_url');
		$table_pre 	  = get_option('ocpwp_dbprefix');
		$table_pre 	 .= '_';
		$image_folder = get_option('ocpwp_prod_img_folder');
		
		$qry = "SELECT value FROM {$table_pre}setting AS st WHERE st.key = 'config_currency'";
		$cur_qry  = $ocdb->get_row($qry);
		$cur_code = $cur_qry->value;
		
		$qry1 = "SELECT symbol_left, symbol_right, value FROM {$table_pre}currency AS currency WHERE currency.code = '{$cur_code}'";
		$cur_qry1 = $ocdb->get_row($qry1);
		$cur_conv_rate = $cur_qry1->value;
		
		$query = "SELECT ptc.product_id, pd.name, p.image, p.price
					FROM {$table_pre}product_to_category AS ptc
					LEFT JOIN {$table_pre}product_description AS pd ON ptc.product_id = pd.product_id
					LEFT JOIN {$table_pre}product AS p ON ptc.product_id = p.product_id
					WHERE ptc.category_id = ".$category_id." 
					ORDER BY p.date_added DESC 
					LIMIT {$prod_count}";

		$products = $ocdb->get_results($query);
		
		$retval = '<ul class="ocpwp-cat-prod">';
		foreach($products as $product){
			$prod_url = $store_url . 'index.php?route=product/product&product_id=' . $product->product_id;
			
			$prod_img = ($product->image == '') ? 'no_image.jpg' : $product->image;			
			$prod_img = $image_folder . $prod_img ;
			
			$retval .= '<li>';
			$retval .= '<img src="'.$prod_img. '" class="prod-thumb" /><br />';
			$retval .= '<a href="'.$prod_url.'" class="prod-name" target="_blank">' . $product->name . '</a><br />';
			$retval .= 'Price: <span class="prod-price">' . $cur_qry1->symbol_left . number_format ($product->price * $cur_conv_rate, 2) . $cur_qry1->symbol_right . '</span><br />';
			$retval .= '<a href="'.$prod_url.'" target="_blank"> <input class="button" type="button" value="View Details" />'.'</a><br />';
			$retval .= '</li>';
		}
		$retval .='</ul>';
		return $retval;
	  }
	}	
	include('ocpwp_widgets.php');
?>