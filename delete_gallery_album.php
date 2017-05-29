<?php

if (!defined('ABSPATH'))
    exit;

Class CGallery_DeleteGalleryImages {

    public function __construct() {
        $this->gallery = new Categorised_Gallery_plugin();
        $this->base1 = $this->gallery->upload_dir;
    }

    /**
     * 
     * @global type $wpdb
     * @return type
     */
    function CGallery_delete_gallery_album() {
        $capability = apply_filters('gallery-capability', 'edit_others_posts');
        if (!current_user_can($capability)) {
            return;
        }
        if (!isset($_REQUEST['image_delete_nonce'], $_GET['id']) || !wp_verify_nonce($_REQUEST['image_delete_nonce'], 'deleteimages_' . $_GET['id'])) {

            die("<div style='color:red;padding: 15px;' id='message' class='error notice'>Failed Security Check</div>");
        } else {
            global $wpdb;
            $table_name = $wpdb->prefix . "galimage";
            $id = intval($_GET["id"]);
            $result = $wpdb->get_results("SELECT * from $table_name where imgid='$id'");
            foreach ($result as $res) {
                $catid = $res->catid;
                $name = $res->imagecrop;
            }
            $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE imgid = %s", $id));
            unlink($this->base1 . "/categoryimg/" . $name);
//        wp_redirect(admin_url("/admin.php?page=add_gallary_images&catid=" . $res->catid, 'http'), 301);
            $redirect = self_admin_url('admin.php?page=add_gallary_images&catid=' . $catid);
            wp_redirect(add_query_arg('add_images_nonce', wp_create_nonce('add_images_' . $res->catid), $redirect));
            $this->CGallery_delete_multiple_image();
        }
    }

    /**
     * 
     * @global type $wpdb
     * @return type
     */
    function CGallery_delete_multiple_image() {
        $capability = apply_filters('gallery-capability', 'edit_others_posts');
        if (!current_user_can($capability)) {
            return;
        }
        $catid = intval($_POST['catid']);
        global $wpdb;
        $table_name = $wpdb->prefix . "galimage";
        if (count($_POST['checked_id']) > 0) {
            $all = filter_input(INPUT_POST, 'checked_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
            foreach ($all as $id1) {
                $result1 = $wpdb->get_results("SELECT * from $table_name where imgid='$id1'");
                foreach ($result1 as $res) {
                    $catid = $res->catid;
                    $name1 = $res->imagecrop;
                }
                unlink($this->base1 . "/categoryimg/" . $name1);
                $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE imgid = %s", $id1));
                $redirect = self_admin_url('admin.php?page=add_gallary_images&catid=' . $catid);
                wp_redirect(add_query_arg('add_images_nonce', wp_create_nonce('add_images_' . $catid), $redirect));
            }
        }
    }

}
