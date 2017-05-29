<?php
$capability = apply_filters('gallery-capability', 'edit_others_posts');
if (!is_admin() && !current_user_can($capability)) {
    return;
}
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    global $wpdb;
    $table_name = $wpdb->prefix . "galcategory";
    $result = $wpdb->get_results("SELECT * from $table_name where catid='$id'");
    if (empty($result)) {
        die("<div style='color:red;padding: 15px;' id='message' class='error notice'>Security Check Fail...</div>");
    }
    foreach ($result as $res) {
        $galnm = $res->categorynm;
        $img1 = $res->catimage;
    }
    ?>
    <div class="wrap">
        <div class="col-sm-offset-2 col-sm-10" style="padding: 15px 0">
            <h1 class="" > Gallery Title </h1>
        </div>
        <?php
        if (!isset($_REQUEST['edit_title_nonce'], $_GET['id']) || !wp_verify_nonce($_REQUEST['edit_title_nonce'], 'editimage_' . $_GET['id'])) {

            die("<div style='color:red;padding: 15px;' id='message' class='error notice'>Failed Security Check</div>");
        } else {
            ?>
            <form method="post" class="validate" enctype="multipart/form-data" onsubmit="return Validate(this);">
        <?php wp_nonce_field('editgaltitle', 'edittitlegal'); ?>
                <table id="createuser" class="form-table">
                    <tbody>
                        <tr class="form-field">
                            <th scope="row">
                                <label>Gallery Title</label>
                            </th>
                            <td>
                                <input name="gallery1" size="30" class="form-control"
                                       value="<?php echo esc_attr($galnm); ?>" id="title" placeholder="Gallery Title"
                                       spellcheck="true" autocomplete="on" type="text" required maxlength="20">
                            </td>
                        </tr>
                        <tr class="form-field">
                            <th scope="row">
                                <label>Image</label>
                            </th>
                            <td>
                                <input name="catfile1" id="gallary-image"  type="file" onchange="validate_fileupload(this.value);">
                                <font color='red'> <div id="error"> </div> </font> 
                                <input name="catfile2" id="gallary-image1" value="<?php echo esc_attr($img1); ?>" type="hidden"> 
                            </td></tr>
                        <tr class="form-field">
                            <th scope="row">
                                <label></label>
                            </th>
                            <td>
                                <img src="<?php echo $this->gallery->basedirurl . "/$img1"; ?>" height="100" width="100"/> 
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit">

                    <button type="submit" value="Update Gallery" name="btnupdate" class="button button-primary button-large" style="margin-right: 10px;">Update Gallery</button>
                    <button type="submit" value="Cancel" name="btncancel" class="button button-primary button-large" formnovalidate>Cancel</button>
                </p>       
            </form>
        </div>
        <?php
    }
} else {
    ?>
    <div class="wrap">
        <div class="col-sm-offset-2 col-sm-10" style="padding: 15px 0">
            <h1 class="" > Gallery Title </h1>
        </div>
        <form method="post" class="validate" enctype="multipart/form-data">
            <?php wp_nonce_field('addgaltitle', 'addtitlegal'); ?>
            <table id="createuser" class="form-table">
                <tbody>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="gallery">Gallery Title</label>
                        </th>
                        <td>
                            <input name="gallery" value="" id="title" placeholder="Gallery Title" spellcheck="true" autocomplete="on" type="text" required maxlength="20">
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
                <button type="submit" value="Add Gallery" name="btnsubmit" class="button button-primary button-large" style="margin-right: 10px;">Add Gallery</button>
                <button type="submit" value="Cancel" name="btncancel" class="button button-primary button-large"  formnovalidate>Cancel</button>
            </p>
        </form>
    </div> 
    <?php
}
?>