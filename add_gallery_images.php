<?php
ob_start();
function add_gallary_images()
{
?>	
	<div class="wrap">
		<h1>List of Gallery albums</h1>
		<hr/>
		<form method="post">
			<input type="submit" name="btnsubmit" value="Add Gallery Albums" class="button button-primary button-large">
		</form>
	</div>
<?php
if(isset($_POST['btnsubmit'])) 
	{
?>
	<form method="post" enctype="multipart/form-data">
		<div class="wrap manage-menus">
			<h3 class="">Add Album image</h3>
			<div class="upload-images">
				<input type="file" name="fileup" required>
			</div>
			<div class="">
				<input type="submit" value="save" name="btnsave" class="button button-primary button-large">
			</div>
		</div>
	</form>
<?php
	}
	$category=$_REQUEST['catid'];
	if(isset($_POST['btnsave']) && (isset($_POST['btnsave'])) != "")
	{
				$allowed_filetypes = array('.jpeg' ,'.png','.jpg','.gif','.ico'); // These will be the types of file that will pass the validation.
				$max_filesize = 524288;
				$uploads = wp_upload_dir();
				$base1=$uploads[basedir];
				$upload_path = $base1.'/categoryimg'; // The place the files will be uploaded to (currently a 'files' directory).
				$filename = $_FILES['fileup']['name']; // Get the name of the file (including file extension).
				$ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
				if(!in_array($ext,$allowed_filetypes))
				die('The file you attempted to upload is not allowed.');
				if(filesize($_FILES['userfile']['tmp_name']) > $max_filesize)
				die('The file you attempted to upload is too large.');
				if(!is_writable($upload_path))
				die('You cannot upload to the specified directory, please CHMOD it to 777.');
				if(move_uploaded_file($_FILES['fileup']['tmp_name'],$upload_path ."/" .$filename))
				{
				//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
				}
				else
				{
				echo 'There was an error during the file upload.  Please try again.';
				}
				
			global $wpdb;
            $table_name=$wpdb->prefix . "galimage";
			if($filename != "" ) 
            {
                
                    $wpdb->insert( $table_name, 
                            array( 'catid' =>$category,'imagenm'=>$filename,'imagecrop'=>$filename,'publish'=>'1','catpub'=>'1'
                            )
                                );
            }
	}
		$i=1;
		global $wpdb;
		$plugpath = plugin_dir_url( __FILE__ );
	    $table_name=$wpdb->prefix . "galimage";
		$result = $wpdb->get_results("SELECT * from $table_name where catid='$category'");
		?>
		<div class="wrap">
			<div class="manage-menus">
				<table id="example" class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                                    <thead>
							<tr>
								<th>No</th>
								<th><input type="checkbox" name="select_all" id="select_all" value="" onClick="EnableCheckBox(this)" /></th>
								<th>Image</th>
								<th>Image name</th>
								<th>Publish</th>
								<th>Delete</th>
							</tr>
					</thead>
					<?php
					foreach($result as $res)
						{
						$img=$res->imagenm;
						$img1=$res->imagecrop;
						$catid=$res->catid;
						$pub=$res->publish;
					$upload_dir = wp_upload_dir();
					?>
					<tbody>
						<form method="post" name="f1" Action="<?php echo admin_url('admin.php?page=delete_gallery_album&catid='.$res->catid); ?>" onSubmit="validate();">
					<tr>
						<td><?php echo $i++; ?></td>
						<td align="center"><input type="checkbox" name="checked_id[]" class="checkbox" value="<?php echo stripslashes($res->imgid); ?>" onClick="EnableSubmit(this)" id="cb1"/></td> 
						<td><img src="<?php echo $upload_dir[baseurl] . "/categoryimg/$img"; ?>" height="100" width="150" title="Image" style="cursor:pointer" /></td>
						<td>
							<?php echo $img1;?>
							<div>
								<a href="<?php echo admin_url('admin.php?page=image_resize_crop1&id=' . $res->imgid ); ?>">Crop</a>&VerticalBar;<a href="<?php echo admin_url('admin.php?page=reset_image&id=' . $res->imgid ); ?>">Reset</a>
							</div>
						</td>
						<?php if($pub==1)
						{
						?>
						<td><a href="<?php echo admin_url('admin.php?page=update_publish_gallery_album&id=' . $res->imgid . "&pubid=" . $pub ."&catid=" .$catid); ?>" title="publish"><img src="<?php echo $plugpath.'/icons/publish.png'?>" height="30" width="30"></a></td>
						<?php 
						}
						else
						{
						?>
						<td><a href="<?php echo admin_url('admin.php?page=update_publish_gallery_album&id=' . $res->imgid . "&pubid=" . $pub ."&catid=" .$catid); ?>" title="unpublish"><img src="<?php echo $plugpath.'/icons/unpublish.png'?>" height="30" width="30"></a></td>
						<?php
						} ?>
						<td><a href="<?php echo admin_url('admin.php?page=delete_gallery_album&id=' . $res->imgid ); ?>" onclick="return checkDelete()" title="Delete"><img src="<?php echo $plugpath.'/icons/delete.png'?>" height="30" width="30"></a></td>
					</tr>
				</tbody>

					<?php
						}
						
					?>
					<tr><td></td><td><input type="submit" name="btn1" value="delete" id="btn1" disabled></td><td></td><td></td><td></td><td></td></tr>
				</form>
				</table>
			</div>
		</div>
	<script>
	function checkDelete(){
	    return confirm('Are you sure you want to Delete?');
	}
    jQuery(document).ready(function(){
    jQuery('#select_all').on('click',function(){
        if(this.checked){
            jQuery('.checkbox').each(function(){
                this.checked = true;
            });
        }else{
             jQuery('.checkbox').each(function(){
                this.checked = false;
            });
        }
    });
});
jQuery(document).ready(function() {
    jQuery('#example').DataTable();
} );

   function EnableSubmit(val)
	{
	    var sbmt = document.getElementById("btn1");
	    var check = jQuery("input:checkbox:checked").length;
	    if(check==0)
	    {
	        sbmt.disabled = true;
	    }
	    else
	    {
	        sbmt.disabled =false;
	    }
	} 

function EnableCheckBox(val)
{
	var sbmt = document.getElementById("btn1");
    if (val.checked == true)
    {
        sbmt.disabled = false;
    }
    else
    {
        sbmt.disabled = true;
    }
}
	</script>
<?php
}
ob_flush();
?>