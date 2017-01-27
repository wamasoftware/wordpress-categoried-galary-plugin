<?php
function delete_gallery_album()
	{
		 global $wpdb;
    $table_name = $wpdb->prefix . "galimage";
    $id = $_GET["id"];
    $result = $wpdb->get_results("SELECT * from $table_name where imgid='$id'");
      print_r($result);
      foreach($result as $res)
		{
			$catid=$res->catid;
		}
      $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE imgid = %s", $id));
      
      wp_redirect(admin_url("/admin.php?page=add_gallary_images&catid=". $res->catid , 'http'), 301);
  
	}
?>
