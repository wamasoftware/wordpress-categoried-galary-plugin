<?php
if (!defined('ABSPATH'))
    exit;

function add_gallary_images() {
    $url = admin_url('admin.php?page=gallery_list');
        $gallery = new Categorised_Gallery_plugin();
        $base1 = $gallery->upload_dir;
    ?>

    <div class="wrap">
        <h1>List of Gallery albums</h1>
        <hr/>
        <form method="post">
            <input type="submit" name="btnsubmit" value="Add Gallery Albums" class="button button-primary button-large">
            <button type="Button" onclick="javascript:window.location = '<?php echo $url ?>';" class="button button-primary button-large">Back</button>
        </form>
    </div>
    <?php
    if (isset($_POST['btnsubmit'])) {
        ?>
        <form method="post" enctype="multipart/form-data">
            <div class="wrap manage-menus">
                <h3 class="">Add Album image</h3>
                <div class="upload-images">
                    <input type="file" multiple name="fileup[]" required>
                </div>
                <div class="">
                    <input type="submit" value="save" name="btnsave" class="button button-primary button-large">
                </div>
            </div>
        </form>
        <?php
    }
    $category = $_REQUEST['catid'];
    if (isset($_POST['btnsave']) && (isset($_POST['btnsave'])) != "") {
        $images_arr = array();
        $allowed_filetypes = array('.jpeg', '.png', '.jpg', '.gif', '.ico'); // These will be the types of file that will pass the validation.
        $max_filesize = 524288;


        foreach ($_FILES['fileup']['name'] as $key => $val) {
                $upload_path = $gallery->dir_path;
            $filename = $_FILES['fileup']['name'][$key];
            $ext = substr($filename, strpos($filename, '.'), strlen($filename) - 1); // Get the extension from the filename.
            if (!in_array($ext, $allowed_filetypes))
                die('The file you attempted to upload is not allowed.');
            if (!is_writable($upload_path))
                die('You cannot upload to the specified directory, please CHMOD it to 777.');
            if (move_uploaded_file($_FILES['fileup']['tmp_name'][$key], $upload_path . "/" . $filename)) {
                //echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
            } else {
                echo 'There was an error during the file upload.  Please try again.';
            }
            global $wpdb;
            $table_name = $wpdb->prefix . "galimage";
            if ($filename != "") {

                $wpdb->insert($table_name, array('catid' => $category, 'imagenm' => $filename, 'imagecrop' => $filename, 'publish' => '1', 'catpub' => '1'));
            }
        }
    }
    $i = 1;
    global $wpdb;
    $plugpath = plugin_dir_url(__FILE__);
    $table_name = $wpdb->prefix . "galimage";
    $result = $wpdb->get_results("SELECT * from $table_name where catid='$category'");
    ?>
    <div class="wrap">
        <form method="post" name="f1" Action="<?php echo admin_url('admin.php?page=delete_gallery_album'); ?>" onSubmit="validate();">      
            <table id="example" class="wp-list-table widefat fixed striped pages" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <td class="manage-column column-cb check-column"><input type="checkbox" name="select_all" id="select_all" value="" onClick="EnableCheckBox(this)" /></td>
                        <th>No</th>
                        <th>Image</th>
                        <th>Image name</th>
                        <th>Publish</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>	

                    <?php
                    foreach ($result as $res) {
                        $img = $res->imagenm;
                        $img1 = $res->imagecrop;
                        $catid = $res->catid;
                        $pub = $res->publish;
                        $catid = $res->catid;
                        $upload_dir = wp_upload_dir();
                        ?>

                        <tr>
                    <input type="hidden" value="<?php echo $catid; ?>" name="catid">
                    <td><input type="checkbox" name="checked_id[]" class="checkbox" value="<?php echo stripslashes($res->imgid); ?>" onClick="EnableSubmit(this)" id="cb1"/></td> 
                    <td><?php echo $i++; ?></td>


                        <td><img src="<?php echo $gallery->basedirurl . "/$img"; ?>" height="100" width="150" title="Image" style="cursor:pointer" /></td>
                    <td>
                        <?php echo $img1; ?>
                        <div>
                            <a href="<?php echo admin_url('admin.php?page=image_resize_crop1&id=' . $res->imgid); ?>">Crop</a>&VerticalBar;<a href="<?php echo admin_url('admin.php?page=reset_image&id=' . $res->imgid); ?>">Reset</a>
                        </div>
                    </td>
                    <?php
                    if ($pub == 1) {
                        ?>
                        <td><a href="<?php echo admin_url('admin.php?page=update_publish_gallery_album&id=' . $res->imgid . "&pubid=" . $pub . "&catid=" . $catid); ?>" title="publish"><img src="<?php echo $plugpath . '/icons/publish.png' ?>" height="30" width="30"></a></td>
                        <?php
                    } else {
                        ?>
                        <td><a href="<?php echo admin_url('admin.php?page=update_publish_gallery_album&id=' . $res->imgid . "&pubid=" . $pub . "&catid=" . $catid); ?>" title="unpublish"><img src="<?php echo $plugpath . '/icons/unpublish.png' ?>" height="30" width="30"></a></td>
                    <?php }
                    ?>
                    <td><a href="<?php echo admin_url('admin.php?page=delete_gallery_album&id=' . $res->imgid); ?>" onclick="return checkDelete()" title="Delete"><img src="<?php echo $plugpath . '/icons/delete.png' ?>" height="30" width="30"></a></td>
                    </tr>
                    <?php
                }
                ?>

                </tbody>
            </table>
            <div class="tablenav bottom">
                <input type="submit" name="btn1" value="Remove" id="btn1"class="button button-primary button-large"  disabled >
                <div class="tablenav-pages one-page">
                        <span class="displaying-num"><?php echo $i - 1; ?>items</span>
                </div>
            </div>
        </form>
    </div>
    <script>
        function checkDelete() {
            return confirm('Are you sure you  want to Delete?');
        }
        jQuery(document).ready(function () {
            jQuery('#select_all').on('click', function () {
                if (this.checked) {
                    jQuery('.checkbox').each(function () {
                        this.checked = true;
                    });
                } else {
                    jQuery('.checkbox').each(function () {
                        this.checked = false;
                    });
                }
            });
        });
        jQuery(document).ready(function () {
            jQuery('#example').DataTable();
        });

        function EnableSubmit(val)
        {
            var sbmt = document.getElementById("btn1");
            var check = jQuery("input:checkbox:checked").length;
            if (check == 0)
            {
                sbmt.disabled = true;
            } else
            {
                sbmt.disabled = false;
            }
        }

        function EnableCheckBox(val)
        {
            var sbmt = document.getElementById("btn1");
            if (val.checked == true)
            {
                sbmt.disabled = false;
            } else
            {
                sbmt.disabled = true;
            }
        }
    </script>
    <?php
}


?>