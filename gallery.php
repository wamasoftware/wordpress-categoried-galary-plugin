<?php
/*
  Plugin Name: Gallery
  Plugin URI:
  Description: Admin can add images in gallery.
  Author: Wamasoftware
  Version: 1.0.0
  Author URI:
 */

function gallery_plugin_menu() {
    add_options_page('gallery', 'gallery', 'manage_options', 'gallery', 'parse_gallery_shortcode');
}

add_action('admin_menu', 'gallery_plugin_menu');

function parse_gallery_shortcode() {

    //echo 'hello';
    //function for insert, update, and delete gallery
    //include_once 'gallerylist.php';
    function save_gallery() {
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

    function galleryadd() {
        global $wpdb;
        if (isset($_GET['id'])) { //for getting selected id data in form for update operation
            $query = 'select * from wp_gallery WHERE id=' . $_GET['id'];
            $record = (array) $wpdb->get_row($query);
        }
        include ('galleryadd.php');
    }

    //galleryadd();

//function for listing page

    function gallerylist() {
        global $wpdb;

        $query = 'select * from wp_gallery;';
        $result = $wpdb->get_results($query);

        include ('gallerylist.php');
    }

    //gallerylist();
    ?>
    <div class="wrap" style="text-align: center;">
        <br/>
        <button type="button" class="btn btn-primary login-window" id="addgallery" onclick="<?php galleryadd(); ?>">Add Gallery</button>
        <button type="button" class="btn btn-primary login-window" id="listgallery" onclick="<?php gallerylist(); ?>">List Gallery</button>
        <br/><br/>
    </div>
        <?php
    }
    ?>