<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
<!--<script src="<?php //echo site_url('/wp-content/plugins/wordpress-categoried-galary-plugin/js/bootstrap/bootstrap.min.js')            ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php //echo site_url('/wp-content/plugins/wordpress-categoried-galary-plugin/css/bootstrap/bootstrap.min.css')  ?>">-->
<script src="<?php echo site_url('/wp-content/plugins/wordpress-categoried-galary-plugin/js/jquery.Jcrop.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('/wp-content/plugins/wordpress-categoried-galary-plugin/css/jquery.Jcrop.css') ?>">
<script src="<?php echo site_url('/wp-content/plugins/wordpress-categoried-galary-plugin/js/jquery.dataTables.min.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('/wp-content/plugins/wordpress-categoried-galary-plugin/css/jquery.dataTables.min.css') ?>">
<br/>
<script>
    var siteUrl = '<?php echo admin_url('admin.php?page=gallerylistdata'); ?>';
    $(document).ready(function(e) {

        $("#addAlbum").click(function(e) {
            jQuery('#galleryalbumadd').show();
        });

        jQuery(function($) {
            cropimage = function() {
                $('#homeslideimageadd').Jcrop({
                    minSize: [32, 32],
                    bgFade: true, // use fade effect
                    bgOpacity: .3, // fade opacity
                    aspectRatio: 1,
                    onSelect: updateCoords
                            // onBlur: checkCoords
                });
            };
        });
        
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


        $('#galleryadd').validate({
            rules: {
                'gallerytitle': {
                    required: true,
                }
            },
            messages: {
                'gallerytitle': {
                    required: 'Please enter gallery title.'
                }
            },
            highlight: function(element) {
                $(element).removeClass("textinput");
                $(element).addClass("errorHighlight");
            },
            unhighlight: function(element) {
                $(element).removeClass("errorHighlight");
                $(element).addClass("textinput");
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });

        $('#galleryalbumadd').validate({
            rules: {
                'albumimage': {
                    required: true,
                },
            },
            messages: {
                'albumimage': {
                    required: 'Please select album image.'
                }
            },
            highlight: function(element) {
                $(element).removeClass("textinput");
                $(element).addClass("errorHighlight");
            },
            unhighlight: function(element) {
                $(element).removeClass("errorHighlight");
                $(element).addClass("textinput");
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });
    });

    function updateCoords(c)
    {
        $('#x').val(c.x);
        $('#y').val(c.y);
        $('#w').val(c.w);
        $('#h').val(c.h);
    }
    ;

    function checkCoords()
    {
        srcvalue = $('#image').val();

        if (srcvalue == '') {
            alert('Please select a crop region then press submit.');
            return false;
        } else if (parseInt($('#w').val()) > 0) {
            return true;
        } else {
            alert('Please select a crop region then press submit.');
            return false;
        }
    }
    ;

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('homeslideimageadd').show();
                $('#homeslideimageadd').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
            setTimeout(function() {
                cropimage();
            }, 500);
        }
    }
</script>
<?php
    if ($_SESSION['error'] != '') {
        echo '<div id="message" class="error"><p>' .$_SESSION['error']. '</p></div>';
        unset($_SESSION['error']);
    }
    if ($_SESSION['success'] != '') {
        echo '<div id="message" class="updated"><p>' .$_SESSION['success']. '</p></div>';
        unset($_SESSION['success']);
    }
?>
<form class="form-horizontal" method="post" name="galleryadd" id="galleryadd" action="" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo isset($record['id']) ? $record['id'] : '' ?>">
    <div class="form-group">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <label class="form-font"> Gallery title: </label>
                    <input type="text" class="form-control" name="gallerytitle" placeholder="Gallery title" value="<?php echo isset($record['gallery_name']) ? $record['gallery_name'] : '' ?>">
                </div>
            </div>   
        </div>      
    </div><br />
    <div class="form-group">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
<?php if (isset($record['gallery_image'])) { ?>
                        <label class="form-font"> Gallery images:</label>
                        <img src="<?php echo site_url() . "/wp-content/uploads/images/" . $record['gallery_image'] ?>" hieght="200" width="200">
<?php } ?>
                    <br/>
                    <label class="form-font"> Edit Gallery images:</label>
                    <input type="file" id="galleryimages" class="form-control" name="galleryimages">
                </div>
            </div>
        </div>
    </div><br />
    <input type="hidden" name="action" value="add_gallery" />
    <div class = "form-group">
        <div class="col-sm-10 button-footer donate-project-btn">
            <input type="submit" class="btn btn-default btn-submit" value="Save" />
        </div>
    </div>
</form> 

<?php if (!empty($record['id'])) { ?>
<hr><hr/>
    <br/>
    <a class="button-primary add-new-h2" href="javascript:void(0)" id="addAlbum">Add Gallery Album </a>
    <br/>
    <form class="form-horizontal" method="post" name="galleryalbumadd" id="galleryalbumadd" action="" enctype="multipart/form-data" style="display: none;">
        <img src="" id="homeslideimageadd" style="display: none;">
        <div class="form-group">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-font"> Gallery Album image: </label>
                        <input type="file" id="galleryAlumadd" onchange="readURL(this);" class="form-control" name="albumimage">
                    </div>
                </div>   
            </div>      
        </div>
        <input type="hidden" id="x" name="x" value="0" />
        <input type="hidden" id="y" name="y" value="0"/>
        <input type="hidden" id="w" name="w" value="0"/>
        <input type="hidden" id="h" name="h" value="0"/>
        <input type="hidden" name="action" value="add_gallery_album" />
        <input type="hidden" name="gallery_id" value="<?php echo isset($record['id']) ? $record['id'] : '' ?>">
        <input type="submit" class="btn btn-default btn-submit"value="Save" />
        <input type="reset" class="btn btn-default btn-submit" value="cancel" />
    </form>
    <br/>
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
        <!--                <td><a href="<?php //echo admin_url('admin.php?page=galleryadd&galleryId='.$val->gallery_id.'&albumId=' . $val->id . '&method=edit');     ?>">Edit</a></td>-->
                <td><a href="<?php echo admin_url('admin.php?page=galleryadd&galleryId=' . $val->gallery_id . '&albumId=' . $val->id . '&method=delete'); ?>" class="deleteRecord">Delete</a></td>
            </tr>
            <?php
            $cnt++;
        }
        ?>
    </tbody>
    </table>
<?php } ?>

