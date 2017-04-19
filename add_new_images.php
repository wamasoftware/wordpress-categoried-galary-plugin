<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
function add_new_gallery_images() {
    $gallery = new Categorised_Gallery_plugin();
    $upload_path = $gallery->dir_path;
    $allowed_filetypes = array('.jpeg', '.png', '.jpg', '.gif', '.ico','.bmp'); 
    $max_filesize = 524288;
    if (isset($_POST["btnsubmit"]) != "") {
        $datetime = current_time('mysql');
        $filename = $_FILES['fileup1']['name'];
        $ext = substr($filename, strpos($filename, '.'), strlen($filename) - 1); // Get the extension from the filename.
        if (!in_array($ext, $allowed_filetypes))
            //die('The file you attempted to upload is not allowed.');
        if (!is_writable($upload_path))
            echo "<div style='color:red;padding: 15px;' id='message' class='error notice'>You cannot upload to the specified directory, please CHMOD it to 777.</div>";
        if (move_uploaded_file($_FILES['fileup1']['tmp_name'], $upload_path . "/" . $filename)) {
            //echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
        } else {
            echo "<div style='color:red;padding: 15px;' id='message' class='error notice'>There was an error during the file upload.  Please try again.</div>";
        }
        $title = $_POST['gallery'];
        global $wpdb;
        $table_name = $wpdb->prefix . "galcategory";
        $p = 1;
        if ($title != "" && $filename != "") {
            if (!in_array($ext, $allowed_filetypes))
            {
                echo "<div style='color:red;padding: 15px;' id='message' class='error notice'>Please select only [.jpeg , .jpg , .png , .gif , .bmp] file</div>";
            }
            else {  
            $wpdb->insert($table_name, array('categorynm' => $title, 'catimage' => $filename, 'date' => $datetime, 'publish' => $p));
            wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);
            }
        } else {
            echo '<div id="message" class="updated notice notice-success is-dismissible">please enter name and upload image.</div>';
        }
    } elseif (isset($_POST["btnupdate"]) != "") {
            $filename = $_FILES['catfile1']['name']; // Get the name of the file (including file extension).
            $allowed_filetypes = array('.jpeg', '.png', '.jpg', '.gif', '.ico'); 
            $ext = substr($filename, strpos($filename, '.'), strlen($filename) - 1); // Get the extension from the filename.
            if (!in_array($ext, $allowed_filetypes))
                    
            if (!is_writable($upload_path))
                echo "<div style='color:red;padding: 15px;' id='message' class='error notice'>You cannot upload to the specified directory, please CHMOD it to 777.</div>";
            if (move_uploaded_file($_FILES['catfile1']['tmp_name'], $upload_path . "/" . $filename)) {
                //echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
            } else {
                echo "<div style='color:red;padding: 15px;' id='message' class='error notice'>There was an error during the file upload.  Please try again.</div>";
            }
            $id = $_REQUEST['id'];
            $title = $_POST['gallery1'];
            $galimg = $_POST['catfile2'];
            global $wpdb;
            $table_name = $wpdb->prefix . "galcategory";
        if ($filename != "")
        {  
            if (!in_array($ext, $allowed_filetypes))
            {
                echo "<div style='color:red;padding: 15px;' id='message' class='error notice'>Please select only [.jpeg , .jpg , .png , .gif , .bmp] file</div>";
            }
            else {
            $wpdb->update($table_name,array('categorynm' => $title, 'catimage' => $filename),array('catid' => $id),array('%s'), array('%s'));    
             wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);  
            }
        } 
        else
        {
             $wpdb->update($table_name,array('categorynm' => $title, 'catimage' => $galimg),array('catid' => $id),array('%s'), array('%s'));
              wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);  
            
        }      
    }
    if (isset($_POST["btncancel"]) != "") {
        wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);
    }
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
        global $wpdb;
        $table_name = $wpdb->prefix . "galcategory";
        $result = $wpdb->get_results("SELECT * from $table_name where catid='$id'");
        foreach ($result as $res) {
            $galnm = $res->categorynm;
            $img1 = $res->catimage;
        }
?>
        <div class="wrap">
            <div class="col-sm-offset-2 col-sm-10" style="padding: 15px 0">
                <h1 class="" > Gallery Title </h1>
            </div>
            <form method="post" class="validate" enctype="multipart/form-data" onsubmit="return Validate(this);">
                <table id="createuser" class="form-table">
                    <tbody>
                        <tr class="form-field">
                            <th scope="row">
                                <label>Gallery Title</label>
                            </th>
                            <td>
                                <input name="gallery1" size="30" class="form-control" value="<?php echo $galnm; ?>" id="title" placeholder="Gallary Title" spellcheck="true" autocomplete="on" type="text"  value="<?php echo $galnm; ?>" required>
                            </td>
                        </tr>
                        <tr class="form-field">
                            <th scope="row">
                                <label>Image</label>
                            </th>
                            <td>
                                <input name="catfile1" id="gallary-image"  type="file" onchange="validate_fileupload(this.value);">
                                <font color='red'> <div id="error"> </div> </font> 
                                <input name="catfile2" id="gallary-image1" value="<?php echo $img1; ?>" type="hidden"> 
                            </td></tr>
                        <tr class="form-field">
                            <th scope="row">
                                <label></label>
                            </th>
                            <td>
                                <img src="<?php echo $gallery->basedirurl . "/$img1"; ?>" height="100" width="100"/> 
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit">
                    <button type="submit" value="Cancel" name="btncancel" class="button button-primary button-large" style="margin-right: 10px;" formnovalidate>Cancel</button>
                    <button type="submit" value="Update Gallery" name="btnupdate" class="button button-primary button-large">Update Gallery</button>
                </p>       
            </form>
<?php
        } else {
?>
            <div class="wrap">
                <div class="col-sm-offset-2 col-sm-10" style="padding: 15px 0">
                    <h1 class="" > Gallery Title </h1>
                </div>
                <form method="post" class="validate" enctype="multipart/form-data">
                    <table id="createuser" class="form-table">
                        <tbody>
                            <tr class="form-field">
                                <th scope="row">
                                    <label for="gallery">Gallery Title</label>
                                </th>
                                <td>
                                    <input name="gallery" value="" id="title" placeholder="Gallary Title" spellcheck="true" autocomplete="on" type="text" required>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <th scope="row">
                                    <label for="fileup1">Image</label>
                                </th>
                                <td>
                                    <input name="fileup1" id="gallary-image" type="file" required onchange="validate_fileupload(this.value);">
                                    <font color='red'> <div id="error"> </div> </font>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="submit">
                        <button type="submit" value="Cancel" name="btncancel" class="button button-primary button-large" style="margin-right: 10px;" formnovalidate>Cancel</button>
                        <button type="submit" value="Add Gallery" name="btnsubmit" class="button button-primary button-large">Add Gallery</button>
                    </p>
                </form>
            </div> 
            <script type="text/javascript">
                function validate_fileupload(fileName)
                    {
                        var allowed_extensions = new Array("jpg","png","gif","jpeg","bmp");
                        var file_extension = fileName.split('.').pop(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.

                        for(var i = 0; i <= allowed_extensions.length; i++)
                        {
                            if(allowed_extensions[i]==file_extension)
                            {
                                return true; // valid file extension
                            }
                        }
                        document.getElementById('error').innerHTML = "Please select only [.jpeg , .jpg , .png , .gif , .bmp] file";
                        return false;
                    }
             </script>
<?php
        }
    }
?>