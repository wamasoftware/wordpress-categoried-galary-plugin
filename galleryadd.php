<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
<br/>
<script>
        var siteUrl = '<?php echo admin_url('admin.php?page=gallerylistdata'); ?>';
        $(document).ready(function() {
            
        });
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
<br/>
<a class="button-primary" href="<?php echo admin_url('admin.php?page=addgalleryalbum'); ?>">Add Gallery Album </a>