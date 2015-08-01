<?php

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
    add_options_page('gallery', 'gallery', 'manage_options', 'gallery', 'parse_gallery_shortcode');
    add_options_page('galleryadd', 'galleryadd', 'manage_options', 'galleryadd', 'add_gallery_shortcode');
}

add_action('admin_menu', 'gallery_plugin_menu');

//function gallery_plugin_add()
//{
//    exit('call');
//    add_options_page('galleryadd', 'galleryadd', 'manage_options', 'galleryadd', 'add_gallery_shortcode');
//}
//add_action('galleryadd', 'gallery_plugin_add');
//if ($GET['page'] == 'galleryadd') {
//
//    global $wpdb;
//    if (isset($_GET['id'])) { //for getting selected id data in form for update operation
//        $query = 'select * from wp_gallery WHERE id=' . $_GET['id'];
//        $record = (array) $wpdb->get_row($query);
//    }
//    include ('galleryadd.php');
//}
//if ($GET['page'] == 'gallery') {
//
//
//    global $wpdb;
//    if (isset($_GET['id'])) { //for getting selected id data in form for update operation
//        $query = 'select * from wp_gallery WHERE id=' . $_GET['id'];
//        $record = (array) $wpdb->get_row($query);
//    }
//    include ('gallerylist.php');
//}

function parse_gallery_shortcode()
{

    //function for insert, update, and delete gallery
    //include_once 'gallerylist.php';
    function save_gallery()
    {
        global $wpdb;
        if (isset($_GET['id']) && isset($_GET['method']) && !empty($_GET['id']) && $_GET['method'] == 'delete') { // For Delete
            $wpdb->query('DELETE FROM wp_gallery WHERE id=' . $_GET['id']);
            header('Location: ' . get_permalink(20));
            exit;
        }

        if ($_POST['id']) {
            //echo '<pre>';print_r($_POST);print_r($_FILES['galleryimages']['name']);exit;
            if (isset($_GET['id']) && isset($_GET['method']) && !empty($_GET['id']) && $_GET['method'] == 'update') { // For Update
                $insert = $_POST;
                $wpdb->update('wp_gallery', array(gallery_id => $insert[galleryid], gallery_name => $insert[gallerytitle], file => $_FILES['galleryimages']['name']), array('id' => $_GET['id']));
            } else { // For Insert
                $fileName = $_FILES["galleryimages"]["name"];
                $fileTmpLoc = $_FILES["galleryimages"]["tmp_name"];
                $pathAndName = "wp-content/plugins/gallery/images/" . $fileName;
                $moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);
                if ($moveResult == true) {
                    //echo "File has been moved from " . $fileTmpLoc . " to" . $pathAndName;
                    //exit;
                } else {
                    //echo "ERROR: File not moved correctly";
                    //exit;
                }
                $insert = $_POST;
                //echo '<pre>';print_r($insert);exit;
                $wpdb->insert('wp_gallery', array(gallery_id => $insert[galleryid], gallery_name => $insert[gallerytitle], file => $_FILES['galleryimages']['name']));
            }
            header('Location: ' . get_permalink(20));
            exit;
        }
    }

    //save_gallery();
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
}

function add_gallery_shortcode()
{
    global $wpdb;
    if ($_POST) {

        $fileName = $_FILES["galleryimages"]["name"];
        $fileTmpLoc = $_FILES["galleryimages"]["tmp_name"];
        $pathAndName = wp_upload_dir("/wordpress-categoried-galary-plugin/gallery/images/");
       // var_dump($pathAndName['basedir']);exit;
       $pathAndName =  $pathAndName['basedir'];
        //$pathAndName = site_url('galary/images/');
        if (!file_exists($pathAndName)) {
            mkdir($pathAndName, 0777, true);
        }
         $fileName = time() . $fileName;
         //$moveResult = wp_upload_bits($pathAndName, null, file_get_contents($fileTmpLoc));
        $moveResult = move_uploaded_file($fileTmpLoc, $pathAndName.'/'.$fileName);

        $insert = $_POST;
   
        $wpdb->insert('wp_gallery', array(gallery_name => $_POST['gallerytitle'], gallery_image => $fileName));
        header('Location: ' . get_permalink(20));
        exit;
    }

    if (isset($_GET['id'])) { //for getting selected id data in form for update operation
        $query = 'select * from wp_gallery WHERE id=' . $_GET['id'];
        $record = (array) $wpdb->get_row($query);
    }
    if (isset($_GET['id']) && isset($_GET['method']) && !empty($_GET['id']) && $_GET['method'] == 'delete') { // For Delete
        $wpdb->query('DELETE FROM wp_gallery WHERE id=' . $_GET['id']);
        header('Location: ' . get_permalink(20));
        exit;
    }
    include ('galleryadd.php');
}
?>