<?php

if (!defined('ABSPATH'))
    exit;

class UpdatePublishGallery {

    function update_publish_gallery_image() {
        $id = $_REQUEST['id'];
        $pubid = $_REQUEST['pubid'];
        global $wpdb;
        $table_name = $wpdb->prefix . "galcategory";
        $table_name1 = $wpdb->prefix . "galimage";
        if ($pubid == 1) {
            $wpdb->update($table_name, array('publish' => 0), array('catid' => $id), array('%s'), array('%s'));
            $wpdb->update($table_name1, array('catpub' => 0), array('catid' => $id), array('%s'), array('%s'));
            wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);
        }
        if ($pubid == 0) {

            $wpdb->update($table_name, array('publish' => 1), array('catid' => $id), array('%s'), array('%s'));
            $wpdb->update($table_name1, array('catpub' => 1), array('catid' => $id), array('%s'), array('%s'));
            wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);
        }
    }

    function update_publish_gallery_album() {
        $id = $_REQUEST['id'];
        $pubid = $_REQUEST['pubid'];
        $catid = $_REQUEST['catid'];
        global $wpdb;
        $table_name = $wpdb->prefix . "galimage";
        if ($pubid == 1) {
            $wpdb->update(
                    $table_name, array('publish' => 0), array('imgid' => $id), array('%s'), array('%s'));
            wp_redirect(admin_url("/admin.php?page=add_gallary_images&catid='$catid'", 'http'), 301);
        }
        if ($pubid == 0) {

            $wpdb->update(
                    $table_name, array('publish' => 1), array('imgid' => $id), array('%s'), array('%s'));
            wp_redirect(admin_url("/admin.php?page=add_gallary_images&catid='$catid'", 'http'), 301);
        }
    }

}
