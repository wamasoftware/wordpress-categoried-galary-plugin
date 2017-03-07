<?php
function delete_gallery_title()
	{
		 global $wpdb;
    $table_name = $wpdb->prefix . "galimage";
     $table_name1 = $wpdb->prefix . "galcategory";
    $id = $_GET["id"];
      $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE catid = %s", $id));
      $wpdb->query($wpdb->prepare("DELETE FROM $table_name1 WHERE catid = %s", $id));      
      wp_redirect(admin_url("/admin.php?page=gallery_list", 'http'), 301); 
	}
?>