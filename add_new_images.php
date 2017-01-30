<?php
ob_start();
function add_new_gallery_images()
{
 if(isset($_POST["btnsubmit"]) != "")
        {
            $datetime = current_time('mysql');
            $allowed_filetypes = array('.jpeg' ,'.png','.jpg','.gif','.ico'); // These will be the types of file that will pass the validation.
                $max_filesize = 524288; // Maximum filesize in BYTES (currently 0.5MB).
                $uploads = wp_upload_dir();
                $base1=$uploads[basedir];
                $upload_path = $base1.'/categoryimg'; // The place the files will be uploaded to (currently a 'files' directory).
                $filename = $_FILES['fileup1']['name']; // Get the name of the file (including file extension).
                $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
                if(!in_array($ext,$allowed_filetypes))
                die('The file you attempted to upload is not allowed.');
                if(filesize($_FILES['userfile']['tmp_name']) > $max_filesize)
                die('The file you attempted to upload is too large.');
                if(!is_writable($upload_path))
                die('You cannot upload to the specified directory, please CHMOD it to 777.');
                if(move_uploaded_file($_FILES['fileup1']['tmp_name'],$upload_path ."/" .$filename))
                {
                //echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
                }
                else
                {
                echo 'There was an error during the file upload.  Please try again.';
                }
        	$title=$_POST['gallery'];
        	global $wpdb;
            $table_name=$wpdb->prefix . "galcategory";
            $p=1;
            if($title != "" &&  $filename != "") 
            {
                    $wpdb->insert( $table_name, 
                            array( 'categorynm' =>$title,'catimage'=>$filename,'date'=>$datetime,'publish'=>$p
                            )
                                );
                  wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);
                
            }
            else
            {
            	echo '<div id="message" class="updated notice notice-success is-dismissible">please enter name and upload image.</div>';
            }
        }
    elseif(isset($_POST["btnupdate"]) != "")
        {
            $allowed_filetypes = array('.jpeg' ,'.png','.jpg','.gif','.ico'); // These will be the types of file that will pass the validation.
                $max_filesize = 524288; // Maximum filesize in BYTES (currently 0.5MB).
                $uploads = wp_upload_dir();
                $base1=$uploads[basedir];
                $upload_path = $base1.'/categoryimg'; // The place the files will be uploaded to (currently a 'files' directory).
                $filename = $_FILES['catfile1']['name']; // Get the name of the file (including file extension).
                $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
                if(!in_array($ext,$allowed_filetypes))
                die('The file you attempted to upload is not allowed.');
                if(filesize($_FILES['userfile']['tmp_name']) > $max_filesize)
                die('The file you attempted to upload is too large.');
                if(!is_writable($upload_path))
                die('You cannot upload to the specified directory, please CHMOD it to 777.');
                if(move_uploaded_file($_FILES['catfile1']['tmp_name'],$upload_path ."/" .$filename))
                {
                //echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
                }
                else
                {
                echo 'There was an error during the file upload.  Please try again.';
                }
             $id=$_REQUEST['id'];
            $title=$_POST['gallery1'];
            global $wpdb;
            $table_name=$wpdb->prefix . "galcategory";
            $wpdb->update(
                $table_name, //table
                array('categorynm' => $title,'catimage'=>$filename), //data
                array('catid' => $id), //where
                array('%s'), //data format
                array('%s') //where format
        );
        wp_redirect(admin_url('/admin.php?page=gallery_list', 'http'), 301);
            
        }
?>
<?php
    if (isset($_REQUEST['id']))
    {
        $id=$_REQUEST['id'];
        global $wpdb;
       $table_name=$wpdb->prefix . "galcategory";
        $result = $wpdb->get_results("SELECT * from $table_name where catid='$id'");
        foreach($result as $res)
        {
            $galnm=$res->categorynm;
            $img1=$res->catimage;

        }
            $upload_dir = wp_upload_dir();
?>
    <form method="post" enctype="multipart/form-data">
        <div class="wrap gallary-image">
        <h1>
            Gallery Title
        </h1>
        <div id="titlediv" class="gallary-post">
            <input name="gallery1" size="30" value="<?php echo $galnm; ?>" id="title" placeholder="Gallary Title" spellcheck="true" autocomplete="on" type="text"  value="<?php echo $galnm; ?>">
        </div>
       <h1>
            Images:
        </h1>
        <div class="gallary-image-upload">
            <input name="catfile1" id="gallary-image" type="file"> </div> 
        <img src="<?php echo $upload_dir[baseurl] . "/categoryimg/$img1"; ?>" height="100" width="100"/>
         
        <div style="margin-top:10px;">
            <input type="submit" value="Update gallery" name="btnupdate" class="button button-primary button-large">
        </div>
    </div>
</form>
<?php
    }
    else
    {

?>
<div class="wrap">
    <h1>
        Gallery Title
    </h1>
    <form method="post" enctype="multipart/form-data">
        <div class="gallary-image card">
            <div id="titlediv" class="gallary-post">
                <input name="gallery" size="30" value="" id="title" placeholder="Gallary Title" spellcheck="true" autocomplete="on" type="text">
            </div>
           <h1>
                Images:
            </h1>
            <div class="gallary-image-upload">
                <input name="fileup1" id="gallary-image" type="file">
            </div>
            <div style="margin-top:10px;">
                <input type="submit" value="Add gallery" name="btnsubmit" class="button button-primary button-large">
            </div>
        </div>
    </form>
</div>
<?php
    }
}
ob_flush();
?>