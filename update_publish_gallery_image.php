<?php
if (!defined('ABSPATH'))
    exit;
function update_publish_gallery_image()
{
	$id=$_REQUEST['id'];
	$pubid=$_REQUEST['pubid'];
	 global $wpdb;
            $table_name=$wpdb->prefix . "galcategory";
            $table_name1=$wpdb->prefix . "galimage";
	if($pubid==1)
	{
            $wpdb->update(
                $table_name, //table
                array('publish' =>0), //data
                array('catid' => $id), //where
                array('%s'), //data format
                array('%s') //where format
        );
            $wpdb->update(
                $table_name1, //table
                array('catpub' =>0), //data
                array('catid' => $id), //where
                array('%s'), //data format
                array('%s') //where format
        );
        wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);
     }
     if($pubid==0)
     {

            $wpdb->update(
                $table_name, //table
                array('publish' =>1), //data
                array('catid' => $id), //where
                array('%s'), //data format
                array('%s') //where format
        );
            $wpdb->update(
                $table_name1, //table
                array('catpub' =>1), //data
                array('catid' => $id), //where
                array('%s'), //data format
                array('%s') //where format
        );
        wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);
     }
}

function update_publish_gallery_album()
{
	$id=$_REQUEST['id'];
	$pubid=$_REQUEST['pubid'];
	$catid=$_REQUEST['catid'];
	 global $wpdb;
            $table_name=$wpdb->prefix . "galimage";
	if($pubid==1)
	{
            $wpdb->update(
                $table_name, //table
                array('publish' =>0), //data
                array('imgid' => $id), //where
                array('%s'), //data format
                array('%s') //where format
        );
          wp_redirect(admin_url("/admin.php?page=add_gallary_images&catid='$catid'" , 'http'), 301);
     }
     if($pubid==0)
     {

            $wpdb->update(
                $table_name, //table
                array('publish' =>1), //data
                array('imgid' => $id), //where
                array('%s'), //data format
                array('%s') //where format
        );
         wp_redirect(admin_url("/admin.php?page=add_gallary_images&catid='$catid'" , 'http'), 301);
     }
}
?>