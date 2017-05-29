<?php

if (!defined('ABSPATH'))
    exit;

Class  CGallery_DeleteGalleryTitle {

    public function __construct() {
        $this->gallery = new Categorised_Gallery_plugin();
        $this->base1 = $this->gallery->upload_dir;
    }
 /**
  * 
  * @global type $wpdb
  */
    function CGallery_delete_gallery_title() {
        $capability = apply_filters( 'gallery-capability', 'edit_others_posts' );
        if ( !is_admin() && ! current_user_can( $capability ) ) {
            return;
        }
         if (!isset($_REQUEST['delete_title_nonce'], $_GET['id']) || !wp_verify_nonce($_REQUEST['delete_title_nonce'], 'deleteimage_' . $_GET['id'])) {

            die("<div style='color:red;padding: 15px;' id='message' class='error notice'>Failed Security Check</div>");
        } else {
        global $wpdb;
        $table_name = $wpdb->prefix . "galimage";
        $table_name1 = $wpdb->prefix . "galcategory";
        $id = intval($_GET["id"]);
        $result = $wpdb->get_results("SELECT * from $table_name1 where catid='$id'");
        foreach ($result as $res) {
            $name = $res->catimage;
            unlink($this->base1 . "/categoryimg/" . $name);
        }
        $resultimg = $wpdb->get_results("SELECT * from $table_name where catid='$id'");
        foreach ($resultimg as $res) {
            $name = $res->imagecrop;
            unlink($this->base1 . "/categoryimg/" . $name);
        }
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE catid = %s", $id));
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name1 WHERE catid = %s", $id));
        wp_redirect(admin_url("/admin.php?page=gallery_list", 'http'));
        }
     }

}
