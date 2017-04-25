<?php

if (!defined('ABSPATH'))
    exit;
ob_start();

class CGallery_AddNewgallery {

    public $upload_path;
    public $allowed_filetypes;

    public function __construct() {
        $this->gallery = new Categorised_Gallery_plugin();
    }

    /**
     * Add new gallery images title
     */
    function CGallery_add_new_gallery_images() {
        $this->upload_path = $this->gallery->dir_path;
        $this->allowed_filetypes = array('.jpeg', '.png', '.jpg', '.gif', '.bmp');
        if (isset($_POST["btnsubmit"]) != "") {
            $this->CGallery_insert_gallery_image();
        } elseif (isset($_POST["btnupdate"]) != "") {
            $this->CGallery_update_gallery_image();
        }
        if (isset($_POST["btncancel"]) != "") {
            wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);
        }
        require_once(ROOTDIRPATH . 'html/add_update_gallery.php');
    }

    /**
     * 
     * @global type $wpdb
     */
    function CGallery_insert_gallery_image() {
        global $wpdb;
        $datetime = current_time('mysql');
        $filename = $_FILES['fileup1']['name'];
        $ext = substr($filename, strpos($filename, '.'), strlen($filename) - 1);
        $title = sanitize_text_field($_POST['gallery']);
        $table_name = $wpdb->prefix . "galcategory";
        $p = 1;
        if (strlen($title) > 20) {
            echo $title = substr($title, 0, 20);
        }
        if ($title != "" && $filename != "") {
            if (!in_array($ext, $this->allowed_filetypes)) {
                echo "<div style='color:red;padding: 15px;' id='message' class='error notice'>Please select only [.jpeg , .jpg , .png , .gif , .bmp] file</div>";
            } else {
                if (!is_writable($this->upload_path))
                    echo "<div style='color:red;padding: 15px;' id='message' class='error notice'>You cannot upload to the specified directory, please CHMOD it to 777.</div>";
                if (move_uploaded_file($_FILES['fileup1']['tmp_name'], $this->upload_path . "/" . sanitize_file_name($filename . time()))) {
                    //echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
                } else {
                    echo "<div style='color:red;padding: 15px;' id='message' class='error notice'>There was an error during the file upload.  Please try again.</div>";
                }
                $wpdb->insert($table_name, array('categorynm' => trim($title), 'catimage' => sanitize_file_name($filename . time()), 'date' => $datetime, 'publish' => $p));
                wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);
            }
        } else {
            echo '<div id="message" class="updated notice notice-success is-dismissible">please enter name and upload image.</div>';
        }
    }

    /**
     * 
     * @global type $wpdb
     */
    function CGallery_update_gallery_image() {
        global $wpdb;
        $filename = $_FILES['catfile1']['name'];
        $ext = substr($filename, strpos($filename, '.'), strlen($filename) - 1);
        $id = intval($_REQUEST['id']);
        $title = sanitize_text_field($_POST['gallery1']);
        $galimg = sanitize_file_name($_POST['catfile2']);
        $table_name = $wpdb->prefix . "galcategory";
        if (strlen($title) > 20) {
            echo $title = substr($title, 0, 20);
        }
        if ($filename != "") {
            if (!in_array($ext, $this->allowed_filetypes)) {
                echo "<div style='color:red;padding: 15px;' id='message' class='error notice'>Please select only [.jpeg , .jpg , .png , .gif , .bmp] file</div>";
            } else {
                if (!is_writable($this->upload_path))
                    echo "<div style='color:red;padding: 15px;' id='message' class='error notice'>You cannot upload to the specified directory, please CHMOD it to 777.</div>";
                if (move_uploaded_file($_FILES['catfile1']['tmp_name'], $this->upload_path . "/" . sanitize_file_name($filename . time()))) {
                    //echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
                } else {
                    echo "<div style='color:red;padding: 15px;' id='message' class='error notice'>There was an error during the file upload.  Please try again.</div>";
                }
                $wpdb->update($table_name, array('categorynm' => $title, 'catimage' => sanitize_file_name($filename . time())), array('catid' => $id), array('%s'), array('%s'));
                wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);
            }
        } else {
            $wpdb->update($table_name, array('categorynm' => trim($title), 'catimage' => $galimg), array('catid' => $id), array('%s'), array('%s'));
            wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);
        }
    }

}
