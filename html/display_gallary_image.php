<div class="wrap">
    <form method="post" name="f1" Action="<?php echo admin_url('admin.php?page=delete_multiple_image'); ?>" onsubmit="return checkmultipledelete()">      
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
                 $i = 1;
                foreach ($this->result as $res) {
                    ?>
                    <tr>
                <input type="hidden" value="<?php echo $res->catid; ?>" name="catid">
                <td><input type="checkbox" name="checked_id[]" class="checkbox" value="<?php echo stripslashes($res->imgid); ?>" onClick="EnableSubmit(this)" id="cb1"/></td> 
                <td><?php echo $i++; ?></td>
                <td><a class="thumbnail-zoom" href="#thumb"><img src="<?php echo $this->gallery->basedirurl . "/$res->imagenm"; ?>" width="150px" height="100px" border="0" /><span><img src="<?php echo $this->gallery->basedirurl . "/$res->imagenm"; ?>" height="250px" width="300px" /></span></a></td>
                <td>
                    <?php echo $res->imagecrop; ?>
                    <div>
                        <a href="<?php echo admin_url('admin.php?page=image_resize_crop1&id=' . $res->imgid); ?>">Crop</a>
                        <?php if ($res->imagenm != $res->imagecrop) { ?>
                            &VerticalBar;<a href="<?php echo admin_url('admin.php?page=reset_image&id=' . $res->imgid); ?>" onclick="return checkreset()">Reset</a>
                        <?php } ?>
                    </div>
                </td>
                <?php
                if ($res->publish == 1) {
                    ?>
                    <td><a href="<?php echo admin_url('admin.php?page=update_publish_gallery_album&id=' . $res->imgid . "&pubid=" . $res->publish . "&catid=" . $res->catid); ?>" title="publish" onclick="return checkunimgPublish()"><img src="<?php echo $this->plugpath . '/icons/publish.png' ?>" height="30" width="30"></a></td>
                    <?php
                } else {
                    ?>
                    <td><a href="<?php echo admin_url('admin.php?page=update_publish_gallery_album&id=' . $res->imgid . "&pubid=" . $res->publish . "&catid=" . $res->catid); ?>" title="unpublish" onclick="return checkimgPublish()"><img src="<?php echo $this->plugpath . '/icons/unpublish.png' ?>" height="30" width="30"></a></td>
                <?php }
                ?>
                <td><a href="<?php echo admin_url('admin.php?page=delete_gallery_album&id=' . $res->imgid); ?>" onclick="return checkDeleteimg()" title="Delete"><img src="<?php echo $this->plugpath . '/icons/delete.png' ?>" height="30" width="30"></a></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <div class="tablenav bottom">
            <input type="submit" name="btn1" value="Remove" id="btn1"class="button button-primary button-large" disabled>
            <div class="tablenav-pages one-page">
                <span class="displaying-num"><?php echo $i - 1; ?>items</span>
            </div>
        </div>
    </form>
</div>