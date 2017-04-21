<?php

if (!defined('ABSPATH'))
    exit;

class ImageresizeCrop {

    public $upload_path;
    public $random;

    public function __construct() {
        $this->gallery = new Categorised_Gallery_plugin();
        $this->upload_path = $this->gallery->dir_path . '/';
        $this->random = strtotime(date('Y-m-d H:i:s'));
    }

    function image_resize_crop1() {

        $imgid = $_REQUEST["id"];
        global $wpdb;
        $plugpath = plugin_dir_url(__FILE__);
        $table_name = $wpdb->prefix . "galimage";
        $result = $wpdb->get_results("SELECT * from $table_name where imgid='$imgid'");
        foreach ($result as $res) {
            $img1 = $res->imagecrop;
        }
        require_once(ROOTDIRPATH . 'html/image_crop.php');
        $this->crop_image($imgid);
    }

    function crop_image($imgid) {
        if (isset($_POST['crop_img'])) {
            $thumb_width = $w = $_POST["w"];
            $thumb_height = $h = $_POST["h"];

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

            $this->insert_image($imgid, $thumb_width, $thumb_height);
        }
    }

    function insert_image($imgid, $thumb_width, $thumb_height) {
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
        $x1 = $_POST["x"];
        $y1 = $_POST["y"];
        $w = $_POST["w"];
        $h = $_POST["h"];
        $scale = $thumb_width / $w;
        $cropped = resizeThumbnailImage($thumb_image_location, $large_image_location, $w, $h, $x1, $y1, $scale);
        $wpdb->update(
                $table_name1, array('imagenm' => $thumb_nm), array('imgid' => $imgid), array('%s'), array('%s'));
        wp_redirect(admin_url("/admin.php?page=add_gallary_images&catid='$catid'", 'http'), 301);
    }

    function reset_image() {
        $imgid = $_REQUEST["id"];
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

