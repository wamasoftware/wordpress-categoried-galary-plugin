<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
function add_new_gallery_images() {
    $gallery = new Categorised_Gallery_plugin();
    $upload_path = $gallery->dir_path;
    if (isset($_POST["btnsubmit"]) != "") {
        $datetime = current_time('mysql');
        $allowed_filetypes = array('.jpeg', '.png', '.jpg', '.gif', '.ico'); // These will be the types of file that will pass the validation.
        $max_filesize = 524288; // Maximum filesize in BYTES (currently 0.5MB).
        $filename = $_FILES['fileup1']['name']; // Get the name of the file (including file extension).
        $ext = substr($filename, strpos($filename, '.'), strlen($filename) - 1); // Get the extension from the filename.
        if (!in_array($ext, $allowed_filetypes))
            die('The file you attempted to upload is not allowed.');
        if (filesize($_FILES['userfile']['tmp_name']) > $max_filesize)
            die('The file you attempted to upload is too large.');
        if (!is_writable($upload_path))
            die('You cannot upload to the specified directory, please CHMOD it to 777.');
        if (move_uploaded_file($_FILES['fileup1']['tmp_name'], $upload_path . "/" . $filename)) {
            //echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
        } else {
            echo 'There was an error during the file upload.  Please try again.';
        }
        $title = $_POST['gallery'];
        global $wpdb;
        $table_name = $wpdb->prefix . "galcategory";
        $p = 1;
        if ($title != "" && $filename != "") {
            $wpdb->insert($table_name, array('categorynm' => $title, 'catimage' => $filename, 'date' => $datetime, 'publish' => $p
                    )
            );
            wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);
        } else {
            echo '<div id="message" class="updated notice notice-success is-dismissible">please enter name and upload image.</div>';
        }
    } elseif (isset($_POST["btnupdate"]) != "") {
            $allowed_filetypes = array('.jpeg', '.png', '.jpg', '.gif', '.ico'); // These will be the types of file that will pass the validation.
            $max_filesize = 524288; // Maximum filesize in BYTES (currently 0.5MB).
            $filename = $_FILES['catfile1']['name']; // Get the name of the file (including file extension).
            $ext = substr($filename, strpos($filename, '.'), strlen($filename) - 1); // Get the extension from the filename.

            if (filesize($_FILES['userfile']['tmp_name']) > $max_filesize)
                die('The file you attempted to upload is too large.');
            if (!is_writable($upload_path))
                die('You cannot upload to the specified directory, please CHMOD it to 777.');
            if (move_uploaded_file($_FILES['catfile1']['tmp_name'], $upload_path . "/" . $filename)) {
                //echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
            } else {
                echo 'There was an error during the file upload.  Please try again.';
            }
            $id = $_REQUEST['id'];
            $title = $_POST['gallery1'];
            $galimg = $_POST['catfile2'];
            global $wpdb;
            $table_name = $wpdb->prefix . "galcategory";
        if ($filename != "") {
            $wpdb->update(
                    $table_name, //table
                    array('categorynm' => $title, 'catimage' => $filename), //data
                    array('catid' => $id), //where
                    array('%s'), //data format
                    array('%s') //where format
            );
        } else {
                 $wpdb->update(
                    $table_name, //table
                    array('categorynm' => $title, 'catimage' => $galimg), //data
                    array('catid' => $id), //where
                    array('%s'), //data format
                    array('%s') //where format
            );
            }
            wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);
    }
    if (isset($_POST["btncancel"]) != "") {
        wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);
    }
    ?>
    <?php
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
            <form method="post" class="validate" enctype="multipart/form-data">
                <table id="createuser" class="form-table">
                    <tbody>
                        <tr class="form-field">
                            <th scope="row">
                                <label>Name</label>
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
                                <input name="catfile1" id="gallary-image"  type="file">
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
                    <button type="submit" value="cancel" name="btncancel" class="button button-primary button-large" style="margin-right: 10px;" formnovalidate>cancel</button>
                    <button type="submit" value="Update gallery" name="btnupdate" class="button button-primary button-large">Update gallery</button>
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
                                    <label for="gallery">Name</label>
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
                                    <input name="fileup1" id="gallary-image" type="file" required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="submit">
                        <button type="submit" value="cancel" name="btncancel" class="button button-primary button-large" style="margin-right: 10px;" formnovalidate>cancel</button>
                        <button type="submit" value="Add gallery" name="btnsubmit" class="button button-primary button-large">Add gallery</button>
                    </p>
                </form>
            </div>
            <?php
        }
    }
    ob_flush();
    ?>