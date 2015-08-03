
<html>
    <head>
        <title>listing</title>
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
        <script src="<?php echo site_url('/wp-content/plugins/wordpress-categoried-galary-plugin/js/jquery.dataTables.min.js') ?>"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('/wp-content/plugins/wordpress-categoried-galary-plugin/css/jquery.dataTables.min.css') ?>">
    </head>
    <script>
        var siteUrl = '<?php echo admin_url('admin.php?page=gallerylistdata'); ?>';
        $(document).ready(function() {
            $("#gallery-table").dataTable({
                "bProcessing": true,
                "bServerSide": false,
                // "sAjaxSource": siteUrl,
                "aaSorting": [[0, "desc"]],
                "aoColumnDefs": [
                      {"bSortable": false, "aTargets": [2,3]},
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
    <body>
        <br/>
        <a class="button-primary" href="<?php echo admin_url('admin.php?page=galleryadd'); ?>">Add Gallery</a><br /><br /><br />
        <table border="1" id="gallery-table">
            <thead>
            <th>GALLERY TITLE</th>
            <th>IMAGE</th>
            <th>UPDATE</th>
            <th>DELETE</th>
        </thead>
        <tbody> 
            <?php foreach ($result as $key => $val) { ?>
                <tr>
                    <td><a href="<?php echo admin_url('admin.php?page=addgalleryimage&id=' . $val->id); ?>"><?php echo $val->gallery_name; ?></a></td>
                    <td><img src="<?php echo site_url() . "/wp-content/uploads/" . $val->gallery_image ?>"></td>
                    <td><a href="<?php echo admin_url('admin.php?page=galleryadd&id=' . $val->id . '&method=update'); ?>">Update</a></td>
                    <td><a href="<?php echo admin_url('admin.php?page=galleryadd&id=' . $val->id . '&method=delete'); ?>" class="deleteRecord">Delete</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>