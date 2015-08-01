<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>

<html>
    <head>
        <title>listing</title>
    </head>
    <body>
<!--        <a href="<?php //echo get_permalink(18);  ?>">Add Gallery</a><br /><br /><br />-->
        <form method="post" action="?page=galleryadd">
            <input type="submit" class="button-primary" value="Add Gallery" />       
        </form>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>GALLERY TITLE</th>
                <th>IMAGE</th>
                <th>UPDATE</th>
                <th>DELETE</th>
            </tr>
            <?php foreach ($result as $key => $val) { ?>
                <tr>
                    <td><?php echo $val->id; ?></td>
                    <td><?php echo $val->gallery_name; ?></td>
                    <td><?php echo $val->file; ?></td>
                    <td><a href="<?php echo get_permalink(18); ?>&id=<?php echo $val->id; ?>&method=update">Update</a></td>
                    <td><a href="<?php echo get_permalink(20); ?>&id=<?php echo $val->id; ?>&method=delete" class="deleteRecord">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
    </body>
</html>