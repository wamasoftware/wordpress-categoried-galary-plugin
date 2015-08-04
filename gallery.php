<?php

ob_start();
session_start();
/*
  Plugin Name: Gallery
  Plugin URI:
  Description: Admin can add images in gallery.
  Author: Wamasoftware
  Version: 1.0.0
  Author URI:
 */
global $wpdb;
global $wnm_db_version;
$charset_collate = $wpdb->get_charset_collate();
//create table when activate
function jal_install()
{
    global $wpdb;
    //set version
    $wnm_db_version = "1.0";
    //set table name
    $table_name_p = $wpdb->prefix . "gallery";
    //create gallery table
    $sql = "CREATE TABLE $table_name_p (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `gallery_name` varchar(255) NOT NULL,
        `gallery_image` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
    $table_names = $wpdb->prefix . "gallery_albam";
    
    //create gallery_albam table
    $sql = "CREATE TABLE $table_names (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `gallery_id` int(11) NOT NULL,
        `gallery_image` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    add_option("wnm_db_version", $wnm_db_version);
}
//CREATE TABLE
register_activation_hook(__FILE__, 'jal_install');

//GALLARY MENU PAGE
function gallery_plugin_menu(){
    add_menu_page('gallery', 'gallery', 'manage_options', 'gallery', 'parse_gallery_shortcode');
    // add_options_page('galleryadd', 'galleryadd', 'manage_options', 'galleryadd', 'add_gallery_shortcode');
    add_submenu_page('gallery', 'Add Gallery', 'Add Gallery', 'manage_options', 'galleryadd', 'add_gallery_shortcode');
}

add_action('admin_menu', 'gallery_plugin_menu');

function parse_gallery_shortcode()
{

    //function for insert, update, and delete gallery

    function galleryadd()
    {
        global $wpdb;
        if (isset($_GET['id'])) { //for getting selected id data in form for update operation
            $query = 'select * from ' . $wpdb->prefix . 'gallery WHERE id=' . $_GET['id'];
            $record = (array) $wpdb->get_row($query);
        }
        include ('galleryadd.php');
    }
    
    //function for listing page
    function gallerylist()
    {
        global $wpdb;

        $query = 'select * from ' . $wpdb->prefix . 'gallery;';
        $result = $wpdb->get_results($query);

        include ('gallerylist.php');
    }

    function get_gallery_list()
    {
        global $wpdb;

        $query = 'select * from ' . $wpdb->prefix . 'gallery;';
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

        $query = 'select * from ' . $wpdb->prefix . 'gallery;';
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
        //update gallery page
        $query = 'select * from ' . $wpdb->prefix . 'gallery WHERE id=' . $_GET['id'];
        $record = (array) $wpdb->get_row($query);
        $querys = 'select * from ' . $wpdb->prefix . 'gallery_albam WHERE gallery_id=' . $_GET['id'];
        $album = (array) $wpdb->get_results($querys);
    } else if (isset($_GET['id']) && isset($_GET['method']) && !empty($_GET['id']) && $_GET['method'] == 'delete') { // For Delete
        //DELETE GALLERY 
        $wpdb->query('DELETE FROM '.$wpdb->prefix.'gallery WHERE id=' . $_GET['id']);
        $_SESSION['success'] = 'Gallery album deleted successfully.';
        //wp_redirect(admin_url('admin.php?page=gallery'));
        header('Location: ' . $_SERVER['PHP_SELF'] . '?page=gallery');
        exit;
    } else if (isset($_GET['albumId']) && isset($_GET['method']) && !empty($_GET['albumId']) && $_GET['method'] == 'edit' && $_POST['action'] == null) {
        $query = 'select * from ' . $wpdb->prefix . 'wp_gallery WHERE id=' . $_GET['id'];
        $record = (array) $wpdb->get_row($query);
    } else if (isset($_POST['action']) && $_POST['action'] == 'add_gallery') {
        if (empty(esc_attr($_POST['gallerytitle']))) {
            $_SESSION['error'] = 'Gallery title is required.';
        }
        if (count($_SESSION['error']) != '') {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?page=galleryadd');
            exit;
            //  wp_redirect(admin_url('options-general.php?page=gallery'));
        }

        if (isset($_POST['id']) && $_POST['id'] == '') {
            $fileName = '';

            if ($_FILES['galleryimages']['size'] != 0) {
                $fileName = $_FILES["galleryimages"]["name"];
                $fileTmpLoc = $_FILES["galleryimages"]["tmp_name"];
                $pathAndName = wp_upload_dir();

                //set file path
                $pathAndName = $pathAndName['basedir'] . '/' . 'images';
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
            $_SESSION['success'] = 'Gallery added successfully.';
        } else {

            $fileName = '';
            if ($_FILES['galleryimages']['size'] != 0) {
                $fileName = $_FILES["galleryimages"]["name"];
                $fileTmpLoc = $_FILES["galleryimages"]["tmp_name"];
                //$pathAndName = plugin_dir_path("/wordpress-categoried-galary-plugin/gallery/images/");
                $pathAndName = wp_upload_dir();
                //set file path
                $pathAndName = $pathAndName['basedir'] . '/' . 'images';
                //create directory if not exists
                if (!file_exists($pathAndName)) {
                    mkdir($pathAndName, 0777, true);
                }
                $query = 'select gallery_image from ' . $wpdb->prefix . 'wp_gallery WHERE id=' . $_GET['id'];
                $image = (array) $wpdb->get_row($query);
                unlink($pathAndName . '/' . $image['gallery_image']);
                //set file name
                $fileName = time() . $fileName;
                $moveResult = move_uploaded_file($fileTmpLoc, $pathAndName . '/' . $fileName);
                $wpdb->update($wpdb->prefix . 'gallery', array(gallery_name => $_POST[gallerytitle], gallery_image => $fileName), array('id' => $_POST['id']));
            } else {
                //update gallery
                $wpdb->update($wpdb->prefix . 'gallery', array(gallery_name => $_POST[gallerytitle]), array('id' => $_POST['id']));
            }
            $_SESSION['success'] = 'Gallery updated successfully.';
        }
        ob_start();
        wp_redirect(admin_url('options-general.php?page=gallery'));
        // header('Location: ' . $_SERVER['PHP_SELF'] . '?page=gallery');
        exit;
    } else if (isset($_POST['action']) && $_POST['action'] == 'add_gallery_album') {
        //add album images
        $crop_file_name = '';
        if ($_FILES["albumimage"]["name"] == '') {
            $_SESSION['error'] = 'Album image is required.';
            header('Location: ' . $_SERVER['PHP_SELF'] . '?page=galleryadd&id=' . $_POST['gallery_id'] . '&method=update');
            exit;
        }
        if ($_FILES["albumimage"]["name"] != '') {

            $iWidth = $iHeight = 200; // desired image result dimensions
            $iJpgQuality = 90;

            if ($_FILES) {

                // if no errors and size less than 250kb
                if (!$_FILES['albumimage']['error']) {
                    if ($_POST['x'] == '0' || $_POST['y'] == '0') {
                        $path = $_FILES['image']['name'];
                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                        // new unique filename
                        $random_file_name = time();
                        $pathAndName = wp_upload_dir();
                        //set file path
                        $pathAndName = $pathAndName['basedir'] . '/album/' . $_POST['gallery_id'];

                        //create a Directory if not exist
                        if (!file_exists($pathAndName)) {
                            mkdir($pathAndName, 0777, true);
                        }
                        //unlink image if update
                        if (isset($_FILES['albumimage']['name']) && isset($_POST['id'])) {
                            $query = 'select gallery_image from ' . $wpdb->prefix . 'gallery_albam WHERE id=' . $_POST['id'];
                            $image = (array) $wpdb->get_row($query);
                            unlink($pathAndName . $image['gallery_image']);
                        }
                        $crop_file_name = $random_file_name . '.' . $ext;
                        $sTempFileName = $pathAndName . '/' . $random_file_name . '.' . $ext;
                        // move uploaded file into cache folder
                        move_uploaded_file($_FILES['albumimage']['tmp_name'], $sTempFileName);
                    } else {
                        $path = $_FILES['albumimage']['name'];
                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                        // new unique filename
                        $random_file_name = time();
                        $pathAndName = wp_upload_dir();
                        //set file path
                        $pathAndName = $pathAndName['basedir'] . '/album/' . $_POST['gallery_id'];

                        //create a Directory if not exist
                        if (!file_exists($pathAndName)) {
                            mkdir($pathAndName, 0777, true);
                        }
                        //unlink image if update
                        if (isset($_FILES['albumimage']['name']) && isset($_POST['id'])) {
                            $query = 'select gallery_image from ' . $wpdb->prefix . 'gallery_albam WHERE id=' . $_POST['id'];
                            $image = (array) $wpdb->get_row($query);
                            unlink($pathAndName . '/album/' . $image['gallery_image']);
                        }
                        $sTempFileName = $pathAndName . '/' . $random_file_name; //.'.'.$ext;
                        // move uploaded file into cache folder
                        move_uploaded_file($_FILES['albumimage']['tmp_name'], $sTempFileName);

                        // change file permission to 644
                        @chmod($sTempFileName, 0644);

                        if (file_exists($sTempFileName) && filesize($sTempFileName) > 0) {
                            $aSize = getimagesize($sTempFileName); // try to obtain image info
                            if (!$aSize) {
                                @unlink($sTempFileName);
                                return;
                            }
                            // check for image type
                            switch ($aSize[2]) {

                                case IMAGETYPE_JPEG:
                                    $sExt = '.jpg';
                                    // create a new image from file
                                    $vImg = @imagecreatefromjpeg($sTempFileName);
                                    break;
                                case IMAGETYPE_PNG:
                                    $sExt = '.png';
                                    // create a new image from file
                                    $vImg = @imagecreatefrompng($sTempFileName);
                                    break;
                                case IMAGETYPE_PNG:
                                    $sExt = '.gif';
                                    // create a new image from file
                                    $vImg = @imagecreatefromgif($sTempFileName);
                                    break;
                                default:
                                    @unlink($sTempFileName);
                                    return;
                            }
                            // create a new true color image
                            $vDstImg = @imagecreatetruecolor($iWidth, $iHeight);

                            // copy and resize part of an image with resampling
                            imagecopyresampled($vDstImg, $vImg, 0, 0, (int) $_POST['x'], (int) $_POST['y'], $iWidth, $iHeight, (int) $_POST['w'], (int) $_POST['h']);

                            // define a result image filename
                            $sResultFileName = $sTempFileName . $sExt;
                            $crop_file_name = $random_file_name . $sExt;
                            // output image to file
                            imagejpeg($vDstImg, $sResultFileName, $iJpgQuality);
                            @unlink($sTempFileName);
                        }
                    }
                }
            }
        }
        //INSERT
        if (empty($_POST['id'])) {
            $wpdb->insert($wpdb->prefix . 'gallery_albam', array(gallery_id => $_POST['gallery_id'], gallery_image => $crop_file_name));
            $_SESSION['success'] = 'Gallery album added successfully.';
            header('Location: ' . $_SERVER['PHP_SELF'] . '?page=galleryadd&id=' . $_POST['gallery_id'] . '&method=update');
            exit;
        } else {
            //UPDATE
            $wpdb->update($wpdb->prefix . 'gallery_albam', array(gallery_id => $_POST['gallery_id'], gallery_image => $crop_file_name), array('id' => $_POST['id']));
            $_SESSION['success'] = 'Gallery album update successfully.';
            header('Location: ' . $_SERVER['PHP_SELF'] . '?page=galleryadd&id=' . $_POST['gallery_id'] . '&method=update');
            exit;
        }
    }

    //DELETE ALBUM
    if (isset($_GET['albumId']) && isset($_GET['method']) && !empty($_GET['albumId']) && $_GET['method'] == 'delete') { // For Delete
        //DELETE GALLERY 
        $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'gallery_albam WHERE id=' . $_GET['albumId']);
        $_SESSION['success'] = 'Gallery album deleted successfully.';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?page=galleryadd&id=' . $_GET['galleryId'] . '&method=update');
        // wp_redirect(admin_url('admin.php?page=galleryadd&id='.$_GET['galleryId'].'&$method=update'));//options-general.php
        exit;
    }
    if (isset($_GET['id'])) { //for getting selected id data in form for update operation
        $query = 'select * from ' . $wpdb->prefix . 'gallery WHERE id=' . $_GET['id'];
        $record = (array) $wpdb->get_row($query);
    }

    //include add gallery page
    if (isset($_GET['page']) && $_GET['page'] == 'galleryadd') {
        // ob_start();
        require_once 'galleryadd.php';
        $output_string = ob_get_contents();
    }
}

//GET ALBUM SHORTCODE
//example ->[album-gallery-list id=2]
function image_gallery($id = null)
{
    global $wpdb;
    if (!empty($id['id']))
        $query = 'select * from ' . $wpdb->prefix . 'gallery_albam where gallery_id=' . $id['id'];
    else
        $query = 'select * from ' . $wpdb->prefix . 'gallery_albam where gallery_id';

    $album = $wpdb->get_results($query);

    ob_start();
    require_once 'albumList.php';
    $album = ob_get_contents();

    ob_end_clean();
    return $album;
}

add_shortcode('album-gallery-list', 'image_gallery');
?>