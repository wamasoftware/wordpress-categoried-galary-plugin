<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
<!--<script src="<?php //echo site_url('/wp-content/plugins/wordpress-categoried-galary-plugin/js/bootstrap/bootstrap.min.js')      ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php //echo site_url('/wp-content/plugins/wordpress-categoried-galary-plugin/css/bootstrap/bootstrap.min.css')      ?>">-->
<script src="<?php echo site_url('/wp-content/plugins/wordpress-categoried-galary-plugin/js/jquery.Jcrop.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('/wp-content/plugins/wordpress-categoried-galary-plugin/css/jquery.Jcrop.css') ?>">

<br/>
<script>
    var siteUrl = '<?php echo admin_url('admin.php?page=gallerylistdata'); ?>';
    $(document).ready(function(e) {

        $("#addAlbum").click(function(e) {
            jQuery('#galleryAlumadd').show();
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


        $('#imageUploadForm').on('submit', (function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log("success");
                    console.log(data);
                },
                error: function(data) {
                    console.log("error");
                    console.log(data);
                }
            });
        }));

        $("#ImageBrowse").on("change", function() {
            $("#imageUploadForm").submit();
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
                    <label class="form-font"> Gallery images:</label>
                    <?php if (isset($record['gallery_image'])) { ?>
                        <img src="<?php echo site_url() . "/wp-content/uploads/" . $record['gallery_image'] ?>">
                    <?php } ?>
                    <input type="file" id="galleryimages" class="form-control" name="galleryimages">
                </div>
            </div>
        </div>
    </div><br />
    <input type="hidden" name="action" value="add_gallery" />
    <div class = "form-group">
        <div class="col-sm-10 button-footer donate-project-btn">
            <input type="submit" class="btn btn-default btn-submit col-sm-3 col-xs-5" value="Save" />
        </div>
    </div>
</form> 
<br/><?php //echo admin_url('admin.php?page=addgalleryalbum');  ?>
<a class="button-primary" class="btn btn-primary mb10" href="javascript:void(0)" id="addAlbum">Add Gallery Album </a>
<form class="form-horizontal" method="post" name="galleryalbumadd" id="galleryAlumadd" action="" enctype="multipart/form-data" style="display: none;">
    <img src="" id="homeslideimageadd" style="display: none;">
    <input type="file" id="galleryimagesadd" onchange="readURL(this);" class="form-control" name="albumimage">
    <input type="hidden" id="x" name="x" value="0" />
    <input type="hidden" id="y" name="y" value="0"/>
    <input type="hidden" id="w" name="w" value="0"/>
    <input type="hidden" id="h" name="h" value="0"/>
    <input type="hidden" name="action" value="add_gallery_album" />
    <input type="hidden" name="id" value="<?php echo isset($record['id']) ? $record['id'] : '' ?>">
    <input type="submit" class="btn btn-default btn-submit col-sm-3 col-xs-5" value="Save" />
    <input type="submit" class="btn btn-default btn-submit col-sm-3 col-xs-5" value="cancel" />
</form>

