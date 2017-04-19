<?php
if (!defined('ABSPATH'))
    exit;
function list_gallery_images() {
    $gallery = new Categorised_Gallery_plugin();
    $plugpath = plugin_dir_url(__FILE__);
    $url = admin_url('admin.php?page=add_new_gallery_images');
    $i = 1;
    global $wpdb;
    $table_name = $wpdb->prefix . "galcategory";
    $table_name1 = $wpdb->prefix . "galimage";
    $result = $wpdb->get_results("SELECT * from $table_name");
    ?>

    <h2>List Of Gallery</h2>

    <div class="wrap">
        <form>
            <button type="Button" onclick="javascript:window.location = '<?php echo $url ?>';" class="button button-primary button-large">Add gallery</button>
            <br><br>
        </form>
        <table id="example1" class="wp-list-table widefat fixed striped pages" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Category <br>Image</th>
                    <th>Media</th>
                    <th>Date</th>
                    <th>Publish</th>
                    <th>Add Gallery images</th>
                    <th>Short code</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody >
                <?php
                $upload_dir = wp_upload_dir();
                foreach ($result as $res) {
                    $catid = $res->catid;
                    $img1 = $res->catimage;
                    $pub = $res->publish;
                    $result1 = $wpdb->get_results("SELECT COUNT(*) AS `count` from $table_name1 where catid='$catid'");
                    foreach ($result1 as $res1) {
                        $coun = $res1->count;
                    }
                    ?>

                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><a href="<?php echo admin_url('admin.php?page=add_gallary_images&catid=' . $res->catid); ?>" title="Add gallery images"><?php echo ucfirst($res->categorynm); ?></a></td>
                        <td><a class="thumbnail-zoom" href="#thumb"><img src="<?php echo $gallery->basedirurl . "/$img1"; ?>" width="100px" height="100px" border="0" /><span><img src="<?php echo $gallery->basedirurl . "/$img1"; ?>" /></span></a></td>
                        <td><?php echo "$coun images" ?></td>
                        <td><?php echo "published <br>" . $res->date; ?></td>
                        <?php
                        if ($pub == 1) {
                            ?>
                            <td><a href="<?php echo admin_url('admin.php?page=update_publish_gallery_image&id=' . $res->catid . "&pubid=" . $pub); ?>" title="publish" onclick="return checkunPublish()"><img src="<?php echo $plugpath . '/icons/publish.png' ?>" height="30" width="30"></a></td>
                            <?php
                        } else {
                            ?>
                            <td><a href="<?php echo admin_url('admin.php?page=update_publish_gallery_image&id=' . $res->catid . "&pubid=" . $pub); ?>" title="unpublish" onclick="return checkPublish()"><img src="<?php echo $plugpath . '/icons/unpublish.png' ?>" height="30" width="30"></a></td>
                        <?php }
                        ?>
                        <td align="center"><a href="<?php echo admin_url('admin.php?page=add_gallary_images&catid=' . $res->catid); ?>" title="Add gallery images"><img src="<?php echo $plugpath . '/icons/addimg.png' ?>" height="30" width="30"></a></td>
                        <td><?php echo "[image_gallery field='$catid']" ?></td>
                        <td><a href="<?php echo admin_url('admin.php?page=add_new_gallery_images&id=' . $res->catid); ?>" title="Edit"><img src="<?php echo $plugpath . '/icons/edit.png' ?>" height="30" width="30"></a></td>
                        <td><a href="<?php echo admin_url('admin.php?page=delete_gallery_title&id=' . $res->catid); ?>" onclick="return checkDelete()" title="delete"  ><img src="<?php echo $plugpath . '/icons/delete.png' ?>"></a></td>
                    </tr>

                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <html>
        <head>
            <script>

                jQuery(document).ready(function () {
                    jQuery('#example1').DataTable();
                });
                function checkDelete()
                {
                    return confirm('Are you sure you want to Delete All images of this gallery?');
                }
                function checkunPublish()
                {
                    return confirm('Are you sure you want to unpublish?');
                }
                function checkPublish()
                {
                    return confirm('Are you sure you want to publish?');
                }
            </script>
        </head>
    </html>
    <?php
}
?>
