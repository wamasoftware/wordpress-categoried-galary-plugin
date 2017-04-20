<?php

if (!defined('ABSPATH'))
    exit;

function delete_gallery_title() {
    global $wpdb;
    $gallery = new Categorised_Gallery_plugin();
    $base1 = $gallery->upload_dir;
    $table_name = $wpdb->prefix . "galimage";
    $table_name1 = $wpdb->prefix . "galcategory";
    $id = $_GET["id"];
     $result = $wpdb->get_results("SELECT * from $table_name1 where catid='$id'");
     foreach ($result as $res) {
        $name = $res->catimage;
        unlink($base1 . "/categoryimg/" . $name);
    }
    $resultimg = $wpdb->get_results("SELECT * from $table_name where catid='$id'");
     foreach ($resultimg as $res) {
        $name = $res->imagecrop;
        unlink($base1 . "/categoryimg/" . $name);
    }
    $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE catid = %s", $id));
    $wpdb->query($wpdb->prepare("DELETE FROM $table_name1 WHERE catid = %s", $id));
    wp_redirect(admin_url("/admin.php?page=gallery_list", 'http'), 301);
}

?>