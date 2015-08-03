<?php

ob_start();
/*
  Plugin Name: Gallery
  Plugin URI:
  Description: Admin can add images in gallery.
  Author: Wamasoftware
  Version: 1.0.0
  Author URI:
 */

function gallery_plugin_menu()
{
    add_menu_page('gallery', 'gallery', 'manage_options', 'gallery', 'parse_gallery_shortcode');
    // add_options_page('galleryadd', 'galleryadd', 'manage_options', 'galleryadd', 'add_gallery_shortcode');
    add_submenu_page('gallery', 'Add Gallery', 'Add Gallery', 'manage_options', 'galleryadd', 'add_gallery_shortcode');
    //add_media_page( 'addgalleryAlbum', 'addgalleryAlbum', 'manage_options', 'addgalleryalbum', 'add_gallery_album');
}

add_action('admin_menu', 'gallery_plugin_menu');

//function add_gallery_album(){
//    
//}
function parse_gallery_shortcode()
{

    //function for insert, update, and delete gallery
    //include_once 'gallerylist.php';

   
//function for insert page and select data for update 

    function galleryadd()
    {
        global $wpdb;
        if (isset($_GET['id'])) { //for getting selected id data in form for update operation
            $query = 'select * from wp_gallery WHERE id=' . $_GET['id'];
            $record = (array) $wpdb->get_row($query);
        }
        include ('galleryadd.php');
    }

    //galleryadd();
//function for listing page
    //$result = '';
    function gallerylist()
    {
        global $wpdb;

        $query = 'select * from wp_gallery;';
        $result = $wpdb->get_results($query);

        include ('gallerylist.php');
    }

    function get_gallery_list()
    {
        global $wpdb;

        $query = 'select * from wp_gallery;';
        $result = $wpdb->get_results($query);

        ob_start();
        require_once 'gallerylist.php';
        $output_string = ob_get_contents();

        ob_end_clean();
        return $output_string;
    }

    add_shortcode('gallery_list', 'get_gallery_list');

    gallerylist();
    
    function get_gallery_list_table()
    {
        global $wpdb;

        $query = 'select * from wp_gallery;';
        $result = $wpdb->get_results($query);
        return json_encode($result);
    }

    add_shortcode('gallerylistdata', 'get_gallery_list_table');
    
}



/**
 * add new gallery
 * @global type $wpdb
 */
function add_gallery_shortcode()
{
    //create DB object
    global $wpdb;
    if (isset($_GET['id']) && isset($_GET['method']) && !empty($_GET['id']) && $_GET['method'] == 'update' && $_POST['action'] == null) {
        $query = 'select * from wp_gallery WHERE id=' . $_GET['id'];
        $record = (array) $wpdb->get_row($query);
    } else if (isset($_GET['id']) && isset($_GET['method']) && !empty($_GET['id']) && $_GET['method'] == 'delete') { // For Delete
        $wpdb->query('DELETE FROM wp_gallery WHERE id=' . $_GET['id']);
        wp_redirect(admin_url('options-general.php?page=gallery'));
        exit;
    } else if ($_POST['action'] == 'add_gallery') {
        
        if ($_POST['id'] == '') {
            $fileName = '';

            if ($_FILES['galleryimages']['size'] != 0) {
                $fileName = $_FILES["galleryimages"]["name"];
                $fileTmpLoc = $_FILES["galleryimages"]["tmp_name"];
                $pathAndName = wp_upload_dir();
              
                //set file path
                $pathAndName = $pathAndName['basedir'];
                //create directory if not exists
                if (!file_exists($pathAndName)) {
                    mkdir($pathAndName, 0777, true);
                }
                //set file name
                $fileName = time() . $fileName;
                $moveResult = move_uploaded_file($fileTmpLoc, $pathAndName . '/' . $fileName);
            }
            //insert gallery
           
            $wpdb->insert('wp_gallery', array(gallery_name => $_POST['gallerytitle'], gallery_image => $fileName));
        } else {
            
            $fileName = '';
            
            if ($_FILES['galleryimages']['size'] != 0) {
                $fileName = $_FILES["galleryimages"]["name"];
                $fileTmpLoc = $_FILES["galleryimages"]["tmp_name"];
                //$pathAndName = plugin_dir_path("/wordpress-categoried-galary-plugin/gallery/images/");
                   $pathAndName = wp_upload_dir();
                //set file path
                $pathAndName = $pathAndName['basedir'];
                //create directory if not exists
                if (!file_exists($pathAndName)) {
                    mkdir($pathAndName, 0777, true);
                }
                $query = 'select gallery_image from wp_gallery WHERE id=' . $_GET['id'];
                $image = (array) $wpdb->get_row($query);
                unlink($pathAndName.'/'.$image['gallery_image']);
                //set file name
                $fileName = time() . $fileName;
                $moveResult = move_uploaded_file($fileTmpLoc, $pathAndName . '/' . $fileName);
            }
            $wpdb->update('wp_gallery', array(id => $_POST[id], gallery_name => $_POST[gallerytitle], gallery_image => $fileName), array('id' => $_POST['id']));
        }
        ob_start();
        wp_redirect(admin_url('options-general.php?page=gallery'));
        // header('Location: ' . $_SERVER['PHP_SELF'] . '?page=gallery');
        exit;
    }

    if (isset($_GET['id'])) { //for getting selected id data in form for update operation
        $query = 'select * from wp_gallery WHERE id=' . $_GET['id'];
        $record = (array) $wpdb->get_row($query);
    }


    if ($_GET['page'] == 'galleryadd') {
        // ob_start();
        require_once 'galleryadd.php';
        $output_string = ob_get_contents();
    }
}

?>