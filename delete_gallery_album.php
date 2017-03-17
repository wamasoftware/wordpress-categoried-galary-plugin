<?php
if(!defined('ABSPATH')) 
    exit;
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
	

   $catid=$_POST['catid'];
   echo($catid);
    if (count($_POST['checked_id']) > 0 ) 
    {

       $all =$_POST['checked_id'];
        foreach($all as $id1)
        {
              $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE imgid = %s", $id1));
              wp_redirect(admin_url("/admin.php?page=add_gallary_images&catid= ".$catid), 301);
        }
      }
    }  
?>
