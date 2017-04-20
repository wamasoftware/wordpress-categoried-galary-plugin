<?php

if (!defined('ABSPATH'))
    exit;

function delete_gallery_album() {
    global $wpdb;
    $gallery = new Categorised_Gallery_plugin();
    $base1 = $gallery->upload_dir;
    $table_name = $wpdb->prefix . "galimage";
    $id = $_GET["id"];

    $result = $wpdb->get_results("SELECT * from $table_name where imgid='$id'");
    foreach ($result as $res) {
        $catid = $res->catid;
        $name = $res->imagecrop;
    }
    $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE imgid = %s", $id));
    unlink($base1 . "/categoryimg/" . $name);
    wp_redirect(admin_url("/admin.php?page=add_gallary_images&catid=" . $res->catid, 'http'), 301);


    $catid = $_POST['catid'];
    echo($catid);
    if (count($_POST['checked_id']) > 0) {

        $all = $_POST['checked_id'];
        foreach ($all as $id1) {
            $result1 = $wpdb->get_results("SELECT * from $table_name where imgid='$id1'");
            foreach ($result1 as $res) {
                $catid = $res->catid;
                $name1 = $res->imagecrop;
            }
             unlink($base1 . "/categoryimg/" . $name1);
            $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE imgid = %s", $id1));
            wp_redirect(admin_url("/admin.php?page=add_gallary_images&catid= " . $catid), 301);
        }
    }
}

?>
