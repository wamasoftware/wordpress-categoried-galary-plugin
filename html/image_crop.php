 <img src="<?php echo $this->gallery->basedirurl . "/$img1"; ?>" align="center" id="cropbox" name="thumbnail"/>
        <html>
            <head>
            </head>
            <div id="outer">
                <div class="jcExample">
                    <div class="article">
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="x" name="x" />
                            <input type="hidden" id="y" name="y" />
                            <input type="hidden" id="w" name="w" />
                            <input type="hidden" id="h" name="h" /><br><br>
                            <input type="submit" name="crop_img" value="Crop Image" class="button button-primary button-large" onclick="return checkCoords();"/>
                        </form>
                    </div>
                </div>
            </div>

