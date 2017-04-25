<h2>List Of Gallery</h2>

<div class="wrap">
    <form>
        <button type="Button" onclick="javascript:window.location = '<?php echo esc_url($this->url) ?>';" class="button button-primary button-large">Add Gallery</button>
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
                <th>Add Gallery Images</th>
                <th>Short code</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody >
            <?php
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
                    <td><a href="<?php echo wp_nonce_url('admin.php?page=add_gallary_images&catid=' . $res->catid,'add_images'); ?>" title="Add gallery images"><?php echo ucfirst($res->categorynm); ?></a></td>
                    <td><a class="thumbnail-zoom" href="#thumb"><img src="<?php echo $this->gallery->basedirurl . "/$img1"; ?>" width="100px" height="100px" border="0" /><span><img src="<?php echo $this->gallery->basedirurl . "/$img1"; ?>" height="250px" width="300px" /></span></a></td>
                    <td><?php echo "$coun images" ?></td>
                    <td><?php echo "published <br>" . $res->date; ?></td>
                    <?php
                    if ($pub == 1) {
                        ?>
                        <td><a href="<?php echo wp_nonce_url('admin.php?page=update_publish_gallery_image&id=' . $res->catid . "&pubid=" . $pub,'category_publish'); ?>" title="publish" onclick="return checkunPublish()"><img src="<?php echo $this->plugpath . '/icons/publish.png' ?>" height="30" width="30"></a></td>
                        <?php
                    } else {
                        ?>
                        <td><a href="<?php echo wp_nonce_url('admin.php?page=update_publish_gallery_image&id=' . $res->catid . "&pubid=" . $pub,'category_publish'); ?>" title="unpublish" onclick="return checkPublish()"><img src="<?php echo $this->plugpath . '/icons/unpublish.png' ?>" height="30" width="30"></a></td>
                    <?php }
                    ?>
                    <td align="center"><a href="<?php echo wp_nonce_url('admin.php?page=add_gallary_images&catid=' . $res->catid,'add_images'); ?>" title="Add gallery images"><img src="<?php echo $this->plugpath . '/icons/addimg.png' ?>" height="30" width="30"></a></td>
                    <td><?php echo "[image_gallery field='$catid']" ?></td>
                    <td><a href="<?php echo  wp_nonce_url('admin.php?page=add_new_gallery_images&id=' . $res->catid, 'edit_title'); ?>" title="Edit"><img src="<?php echo $this->plugpath . '/icons/edit.png' ?>" height="30" width="30"></a></td>
                    <td><a href="<?php echo wp_nonce_url('admin.php?page=delete_gallery_title&id=' . $res->catid,'delete_title'); ?>" onclick="return checkDelete()" title="delete"  ><img src="<?php echo $this->plugpath . '/icons/delete.png' ?>"></a></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

