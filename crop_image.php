<?php

if (!defined('ABSPATH'))
    exit;

Class  CGallery_ImageresizeCrop {

    public $upload_path;
    public $random;

    public function __construct() {
        $this->gallery = new Categorised_Gallery_plugin();
        $this->upload_path = $this->gallery->dir_path . '/';
        $this->random = strtotime(date('Y-m-d H:i:s'));
    }
    /**
     * 
     * @global type $wpdb
     */
    function CGallery_image_resize_crop1() {

        $retrieved_nonce = $_REQUEST['_wpnonce'];
        if (!wp_verify_nonce($retrieved_nonce, 'crop_image'))
            die("<div style='color:red;padding: 15px;' id='message' class='error notice'>Failed Security Check</div>");
        $imgid = intval($_GET["id"]);
        global $wpdb;
        $plugpath = plugin_dir_url(__FILE__);
        $table_name = $wpdb->prefix . "galimage";
        $result = $wpdb->get_results("SELECT * from $table_name where imgid='$imgid'");
        foreach ($result as $res) {
            $img1 = $res->imagecrop;
        }
        require_once(ROOTDIRPATH . 'html/image_crop.php');
        $this->CGallery_crop_image($imgid);
    }
    /**
     * 
     * @param type $imgid
     */                
    function CGallery_crop_image($imgid) {
        if (isset($_POST['crop_img'])) {
             if (isset($_POST['cropimage']) &&
                    wp_verify_nonce($_POST['cropimage'], 'crop_image')) {
            $thumb_width = $w = intval($_POST["w"]);
            $thumb_height = $h = intval($_POST["h"]);

            function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale) {
                list($imagewidth, $imageheight, $imageType) = getimagesize($image);
                $imageType = image_type_to_mime_type($imageType);
                $newImageWidth = ceil($width * $scale);
                $newImageHeight = ceil($height * $scale);
                $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
                switch ($imageType) {
                    case "image/gif":
                        $source = imagecreatefromgif($image);
                        break;
                    case "image/pjpeg":
                    case "image/jpeg":
                    case "image/jpg":
                        $source = imagecreatefromjpeg($image);
                        break;
                    case "image/png":
                    case "image/x-png":
                        $source = imagecreatefrompng($image);
                        break;
                }
                imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
                switch ($imageType) {
                    case "image/gif":
                        imagegif($newImage, $thumb_image_name);
                        break;
                    case "image/pjpeg":
                    case "image/jpeg":
                    case "image/jpg":
                        imagejpeg($newImage, $thumb_image_name, 100);
                        break;
                    case "image/png":
                    case "image/x-png":
                        imagepng($newImage, $thumb_image_name);
                        break;
                }
                return $thumb_image_name;
            }
            } else {
                die("<div style='color:red;padding: 15px;' id='message' class='error notice'>Failed Security Check</div>");
            }
            $this->CGallery_insert_image($imgid, $thumb_width, $thumb_height);
        }
    }
    /**
     * 
     * @global type $wpdb
     * @param type $imgid
     * @param type $thumb_width
     * @param type $thumb_height
     */
    function CGallery_insert_image($imgid, $thumb_width, $thumb_height) {
        global $wpdb;
        $table_name1 = $wpdb->prefix . "galimage";
        $result = $wpdb->get_results("SELECT * from $table_name1 where imgid='$imgid'");
        foreach ($result as $res) {
            $img1 = $res->imagecrop;
            $filename = $img1;
            $catid = $res->catid;
        }
        $large_image_location = $this->upload_path . "/" . $filename;

        $thumb_image_location = $this->upload_path . "thumb_" . $this->random . $filename;

        $thumb_nm = "thumb_" . $this->random . $filename;
        echo $thumb_nm . '<br>';
        echo $thumb_image_location;
        $x1 = intval($_POST["x"]);
        $y1 = intval($_POST["y"]);
        $w = intval($_POST["w"]);
        $h = intval($_POST["h"]);
        $scale = $thumb_width / $w;
        $cropped = resizeThumbnailImage($thumb_image_location, $large_image_location, $w, $h, $x1, $y1, $scale);
        $wpdb->update(
                $table_name1, array('imagenm' => $thumb_nm), array('imgid' => $imgid), array('%s'), array('%s'));
        wp_redirect(admin_url("/admin.php?page=add_gallary_images&catid='$catid'", 'http'), 301);
    }
    /**
     * 
     * @global type $wpdb
     */                
    function CGallery_reset_image() {
        $imgid = intval($_REQUEST["id"]);
        global $wpdb;
        $table_name = $wpdb->prefix . "galimage";
        $result = $wpdb->get_results("SELECT * from $table_name where imgid='$imgid'");
        foreach ($result as $res) {
            $img1 = $res->imagecrop;
            $catid = $res->catid;
        }
        $wpdb->update(
                $table_name, array('imagenm' => $img1), array('imgid' => $imgid), array('%s'), array('%s'));
        wp_redirect(admin_url("/admin.php?page=add_gallary_images&catid='$catid'", 'http'), 301);
    }

}

