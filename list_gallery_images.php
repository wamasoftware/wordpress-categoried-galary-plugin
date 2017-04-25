<?php
if (!defined('ABSPATH'))
    exit;

Class  CGallery_ListGalleryTitle {

    public $url;
    public $plugpath;

    public function __construct() {
        $this->gallery = new Categorised_Gallery_plugin();
        $this->plugpath = plugin_dir_url(__FILE__);
        $this->url = admin_url('admin.php?page=add_new_gallery_images');
    }

    function CGallery_list_gallery_images() {
        $i = 1;
        global $wpdb;
        $table_name = $wpdb->prefix . "galcategory";
        $table_name1 = $wpdb->prefix . "galimage";
        $result = $wpdb->get_results("SELECT * from $table_name");
        require_once(ROOTDIRPATH . 'html/display_title_image.php');
    }

}

