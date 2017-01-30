
<?php
// Crop image
  ob_start();
// include css and js function
function add_css_js() {
wp_enqueue_script('inkthemes1', plugins_url( 'js/jquery.Jcrop.min.js' , __FILE__ ) , array( 'jquery' ));
wp_enqueue_script('inkthemes2', plugins_url( 'js/jquery.Jcrop.js' , __FILE__ ) , array( 'jquery' ));
wp_enqueue_script('inkthemes2', plugins_url( 'js/jquery-pack.js' , __FILE__ ) , array( 'jquery' ));
wp_enqueue_script('inkthemes5s', plugins_url( 'js/jquery.imgareaselect.min.js' , __FILE__ ) , array( 'jquery' ));
wp_enqueue_style('inkthemes3', plugins_url( "/css/jquery.Jcrop.css" , __FILE__ ));
wp_localize_script( 'inkthemes4', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php')));
}
add_action( 'admin_init','add_css_js');
// including ajax script in the plugin Myajax.ajaxurl
function image_resize_crop1()
{
     $imgid=$_REQUEST[id];
    global $wpdb;
     $plugpath = plugin_dir_url( __FILE__ );
      $upload_dir = wp_upload_dir();
       $table_name=$wpdb->prefix . "galimage";
        $result = $wpdb->get_results("SELECT * from $table_name where imgid='$imgid'");
        foreach($result as $res)
                {
                    $img1=$res->imagecrop;
                   ?>  
                        <img src="<?php echo $upload_dir[baseurl] . "/categoryimg/$img1"; ?>" align="center" id="cropbox" name="thumbnail"/>
                    <?php
                }
?>
<html>
    <head>
<!-- script for crop image-->
<script type="text/javascript">
            jQuery('document').ready(function(){
                                 jQuery('#cropbox').imgAreaSelect({
                        onSelectEnd: function (img, selection) {
                            jQuery('input[name="x"]').val(selection.x1);
                            jQuery('input[name="y"]').val(selection.y1);
                            jQuery('input[name="w"]').val(selection.x2);
                            jQuery('input[name="h"]').val(selection.y2);            
                        }
                    });
                jQuery('#cropbox').Jcrop({
                    aspectRatio: 1,
                    onSelect: updateCoords
                });
                      jQuery('#cropbox').imgAreaSelect({
                       aspectRatio: '1:1',
                        onSelectChange: preview 
                });
            });
            function updateCoords(c)
            {
                jQuery('#x').val(c.x);
                jQuery('#y').val(c.y);
                jQuery('#w').val(c.w);
                jQuery('#h').val(c.h);
            };
            function checkCoords()
            {
                if (parseInt(jQuery('#w').val())) return true;
                alert('Select where you want to Crop.');
                return false;
            };
        </script>

</head>
<div id="outer">
    <div class="jcExample">
        <div class="article">
            <form action="" method="post" onsubmit="return checkCoords();" enctype="multipart/form-data">
                <input type="hidden" id="x" name="x" />
                <input type="hidden" id="y" name="y" />
                <input type="hidden" id="w" name="w" />
                <input type="hidden" id="h" name="h" /><br><br>
                <input type="submit" name="crop_img" value="Crop Image" class="button button-primary button-large"/>
            </form>
        </div>
    </div>
</div>
    <?php
if(isset($_POST['crop_img']))
{    
        $upload_path =   $src = $upload_dir['basedir'] . "/categoryimg/";
        $random= strtotime(date('Y-m-d H:i:s')); //assign the timestamp to the session variable
        $thumb_width =   $w = $_POST["w"];
        $thumb_height = $h = $_POST["h"];
        function resizeThumbnailImage($thumb_image_name, $image, $width, $height, 
        $start_width, $start_height, $scale){
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);
        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
        switch($imageType) {
        case "image/gif":
        $source=imagecreatefromgif($image); 
        break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
        $source=imagecreatefromjpeg($image); 
        break;
        case "image/png":
        case "image/x-png":
        $source=imagecreatefrompng($image); 
        break;
        }
        imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,
        $newImageWidth,$newImageHeight,$width,$height);
        switch($imageType) {
        case "image/gif":
        imagegif($newImage,$thumb_image_name); 
        break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
        imagejpeg($newImage,$thumb_image_name,100); 
        break;
        case "image/png":
        case "image/x-png":
        imagepng($newImage,$thumb_image_name); 
        break;
        }
        return $thumb_image_name;
        }

        $table_name1=$wpdb->prefix . "galimage";
      
            $result = $wpdb->get_results("SELECT * from $table_name1 where imgid='$imgid'");
        foreach($result as $res)
                {
                    $img1=$res->imagecrop;
                    $filename = $img1;
                    $catid=$res->catid;
                }
        $large_image_location = $upload_path."/".$filename;
        $thumb_image_location = $upload_path."thumb_".$random.$filename;
        $thumb_nm="thumb_".$random.$filename;
        echo $thumb_nm.'<br>';
        echo  $thumb_image_location;
        $x1 = $_POST["x"];
        $y1 = $_POST["y"];
        $w = $_POST["w"];
        $h = $_POST["h"];
        $scale = $thumb_width/$w;
        $cropped = resizeThumbnailImage($thumb_image_location, 
        $large_image_location,$w,$h,$x1,$y1,$scale);
        $wpdb->update(
                $table_name1, //table
                array('imagenm' =>$thumb_nm), //data
                array('imgid' => $imgid), //where
                array('%s'), //data format
                array('%s')); //where format
         wp_redirect(admin_url("/admin.php?page=add_gallary_images&catid='$catid'" , 'http'), 301);
        
        exit();
       
        }           
    ?>
    <img src="<?php echo $cropped; ?>" align="center" id="cropbox" name="thumbnail"/>
<?php
}
?>

<?php
    // reset image
    function reset_image()
    {
            $imgid=$_REQUEST[id];
                global $wpdb;
         $table_name=$wpdb->prefix . "galimage";
        $result = $wpdb->get_results("SELECT * from $table_name where imgid='$imgid'");
        foreach($result as $res)
                {
                    $img1=$res->imagecrop;
                    $catid=$res->catid;
                }
                 $wpdb->update(
                $table_name, //table
                array('imagenm' =>$img1), //data
                array('imgid' => $imgid), //where
                array('%s'), //data format
                array('%s')); //where format
         wp_redirect(admin_url("/admin.php?page=add_gallary_images&catid='$catid'" , 'http'), 301);
    }
?>
<?php
ob_flush();
?>