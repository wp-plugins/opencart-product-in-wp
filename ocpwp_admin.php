<?php 
    if($_POST['ocpwp_hidden'] == 'Y') {
	  $dbhost = sanitize_text_field($_POST['ocpwp_dbhost']);
	  update_option('ocpwp_dbhost', $dbhost);
	   
	  $dbname = sanitize_text_field($_POST['ocpwp_dbname']);
	  update_option('ocpwp_dbname', $dbname);
	   
	  $dbuser = sanitize_text_field($_POST['ocpwp_dbuser']);
	  update_option('ocpwp_dbuser', $dbuser);
	  
	  $dbpwd = sanitize_text_field($_POST['ocpwp_dbpwd']);
	  update_option('ocpwp_dbpwd', $dbpwd);
	   
	  $dbprefix = sanitize_text_field($_POST['ocpwp_dbprefix']);
	  update_option('ocpwp_dbprefix', $dbprefix);

	  $prod_img_folder = sanitize_text_field($_POST['ocpwp_prod_img_folder']);
	  update_option('ocpwp_prod_img_folder', $prod_img_folder);

	  $store_url = sanitize_text_field($_POST['ocpwp_store_url']);
	  update_option('ocpwp_store_url', $store_url);
?>
      <div class="updated">
        <p><strong><?php _e('Options saved.'); ?></strong></p>
      </div>
<?php
    } else {
	  $dbhost 	= get_option('ocpwp_dbhost');
	  $dbname 	= get_option('ocpwp_dbname');
	  $dbuser 	= get_option('ocpwp_dbuser');
	  $dbpwd  	= get_option('ocpwp_dbpwd');
	  $dbprefix = get_option('ocpwp_dbprefix');
	  $prod_img_folder = get_option('ocpwp_prod_img_folder');
	  $store_url = get_option('ocpwp_store_url');
    }
?>
    <div class="wrap"> <?php echo "<h2>" . __('Opencart Product WP Options') . "</h2>"; ?>
      <form name="ocpwp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="ocpwp_hidden" value="Y">
        <?php echo "<h4>" . __('Opencart Database Settings') . "</h4>"; ?>
        <table class="form-table">
          <tr valign="top">
            <th scope="row"><?php _e("Database host name: "); ?></th>
            <td><input type="text" name="ocpwp_dbhost" value="<?php echo $dbhost; ?>" size="20" />
              <br />
              <?php _e("ex: localhost/ip address/domain name"); ?></td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e("Database user name: "); ?></th>
            <td><input type="text" name="ocpwp_dbuser" value="<?php echo $dbuser; ?>" size="20" />
              <br />
              <?php _e("ex: dbuser"); ?></td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e("Database password: " ); ?></th>
            <td><input type="text" name="ocpwp_dbpwd" value="<?php echo $dbpwd; ?>" size="20" />
              <br />
              <?php _e("ex: secretpassword"); ?></td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e("Database name: "); ?></th>
            <td><input type="text" name="ocpwp_dbname" value="<?php echo $dbname; ?>" size="20" />
              <br />
              <?php _e("ex: opencart_shop"); ?></td>
          </tr>          
          <tr valign="top">
            <th scope="row"><?php _e("Database table prefix: "); ?></th>
            <td><input type="text" name="ocpwp_dbprefix" value="<?php echo $dbprefix; ?>" size="20" />
              <br />
              <?php _e("ex: oc"); ?></td>
          </tr>
        </table>
        <hr />
        <?php echo "<h4>" . __( 'Opencart Store Settings', 'ocpwp_trdom' ) . "</h4>"; ?>
        <table class="form-table">
          <tr valign="top">
            <th scope="row"><?php _e("Store URL: "); ?></th>
            <td><input type="text" name="ocpwp_store_url" value="<?php echo $store_url; ?>" size="20" />
              <br />
              <?php _e("ex: http://www.yourstore.com/"); ?></td>
          </tr>
          <tr valign="top">
            <th scope="row"> <?php _e("Product image folder: "); ?></th>
            <td><input type="text" name="ocpwp_prod_img_folder" value="<?php echo $prod_img_folder; ?>" size="20">
              <br>
              <?php _e("ex: http://www.yourstore.com/image/"); ?></td>
          </tr>
          <tr valign="top">
            <td colspan="2"><p class="submit">
                <input type="submit" name="Submit" value="<?php _e('Update Options') ?>" />
              </p></td>
          </tr>
        </table>
      </form>
    </div>