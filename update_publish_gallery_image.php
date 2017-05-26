<?php

if (!defined('ABSPATH'))
    exit;

Class CGallery_UpdatePublishGallery {

    /**
     * publish or unpublish gallery
     * @global type $wpdb
     */
    function CGallery_update_publish_gallery_image() {
        $capability = apply_filters('gallery-capability', 'edit_others_posts');
        if (!current_user_can($capability)) {
            return;
        }
        $id = intval($_GET['id']);
        $pubid = intval($_GET['pubid']);
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

    /**
     * publish or unplublish images 
     * @global type $wpdb
     */
    function CGallery_update_publish_gallery_album() {
        $capability = apply_filters('gallery-capability', 'edit_others_posts');
        if (!current_user_can($capability)) {
            return;
        }
        $id = intval($_GET['id']);
        $pubid = intval($_GET['pubid']);
        $catid = intval($_GET['catid']);
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
