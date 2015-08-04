<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="<?php echo site_url('/wp-content/plugins/wordpress-categoried-galary-plugin/js/jquery.dataTables.min.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('/wp-content/plugins/wordpress-categoried-galary-plugin/css/jquery.dataTables.min.css') ?>">
<script>
    $(document).ready(function(e) {
//Album Gallery list table
        $("#album-table").dataTable({
            "bProcessing": true,
            "bServerSide": false,
            // "sAjaxSource": siteUrl,
            "aaSorting": [[0, "desc"]],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [1, 2]}
            ],
            "fnServerData": function(sSource, aoData, fnCallback) {
                $.ajax({
                    "dataType": 'json',
                    "type": "GET",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                });
            }
        });
    });
</script>
<table border="1" id="album-table">
    <thead>
    <th>No.</th>
    <th>ALBUM IMAGE</th>
<!--        <th>EDIT</th>-->
    <th>DELETE</th>
</thead>
<tbody> 
    <?php
    $cnt = 1;
    foreach ($album as $key => $val) {
        ?>
        <tr>
            <td><?php echo $cnt; ?></td>
            <td><img src="<?php echo site_url() . "/wp-content/uploads/album/" . $val->gallery_id . '/' . $val->gallery_image ?>" hieght="200" width="200"></td>
    <!--                <td><a href="<?php //echo admin_url('admin.php?page=galleryadd&galleryId='.$val->gallery_id.'&albumId=' . $val->id . '&method=edit');         ?>">Edit</a></td>-->
            <td><a href="<?php echo admin_url('admin.php?page=galleryadd&galleryId=' . $val->gallery_id . '&albumId=' . $val->id . '&method=delete'); ?>" class="deleteRecord">Delete</a></td>
        </tr>
        <?php
        $cnt++;
    }
    ?>
</tbody>
</table>