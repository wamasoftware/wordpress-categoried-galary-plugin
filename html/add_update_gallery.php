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
                        <img src="<?php echo $this->gallery->basedirurl . "/$img1"; ?>" height="100" width="100"/> 
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <button type="submit" value="Cancel" name="btncancel" class="button button-primary button-large" style="margin-right: 10px;" formnovalidate>Cancel</button>
            <button type="submit" value="Update Gallery" name="btnupdate" class="button button-primary button-large">Update Gallery</button>
        </p>       
    </form>
</div>
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
    <?php
}
?>