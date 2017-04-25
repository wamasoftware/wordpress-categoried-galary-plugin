<?php

if (!defined('ABSPATH'))
    exit;

Class CGallery_AddGalleryImage {
  
    public $url;
    public $result;
    public $plugpath;

    public function __construct() {
        $this->url = admin_url('admin.php?page=gallery_list');
        $this->gallery = new Categorised_Gallery_plugin();
        $this->plugpath = plugin_dir_url(__FILE__);
        $this->deletegalleryimages = new CGallery_DeleteGalleryImages();
        $this->obj = array($this->deletegalleryimages, 'delete_multiple_image');
    }
    /**
     * Add gallery images list
     */
    public function CGallery_add_gallary_images_list() {

        require_once(ROOTDIRPATH . 'html/add_gallary_images_header.php');

        $category = $_REQUEST['catid'];
        $this->CGallery_saveImage($category);
        $this->CGallery_displayImages($category);

        require_once(ROOTDIRPATH . 'html/display_gallary_image.php');
    }
   /**
    * 
    * @global type $wpdb
    * @param type $category
    */
    function CGallery_saveImage($category) {
        if (isset($_POST['btnsave']) && (isset($_POST['btnsave'])) != "") {
            $images_arr = array();
            $allowed_filetypes = array('.jpeg', '.png', '.jpg', '.gif', '.bmp'); // These will be the types of file that will pass the validation.
            $max_filesize = 524288;
            foreach ($_FILES['fileup']['name'] as $key => $val) {
                $upload_path = $this->gallery->dir_path;
                $filename = $_FILES['fileup']['name'][$key];
                $ext = substr($filename, strpos($filename, '.'), strlen($filename) - 1); // Get the extension from the filename.
                if (!in_array($ext, $allowed_filetypes))
                //die('The file you attempted to upload is not allowed.');
                    if (!is_writable($upload_path))
                        die('You cannot upload to the specified directory, please CHMOD it to 777.');
                if (move_uploaded_file($_FILES['fileup']['tmp_name'][$key], $upload_path . "/" . sanitize_file_name($filename . time()))) {
                    //echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
                } else {
                    echo 'There was an error during the file upload.  Please try again.';
                }
                global $wpdb;
                $table_name = $wpdb->prefix . "galimage";
                if ($filename != "") {
                    $wpdb->insert($table_name, array('catid' =>$category, 'imagenm' => sanitize_file_name($filename . time()), 'imagecrop' => $filename . time(), 'publish' => '1', 'catpub' => '1'));
                }
            }
        }
    }
   /**
    * 
    * @global type $wpdb
    * @param type $category
    */
    function CGallery_displayImages($category) {
        global $wpdb;
        $table_name = $wpdb->prefix . "galimage";
        $this->result = $wpdb->get_results("SELECT * from $table_name where catid='$category'");
    }

}
