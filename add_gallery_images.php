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
				<input type="file" name="fileup">
			</div>
			<div class="">
				<input type="submit" value="save" name="btnsave" class="button button-primary button-large">
			</div>
		</div>
	</form>
<?php
	}
	$category=$_REQUEST['catid'];
	//echo $category;
	if(isset($_POST['btnsave']) && (isset($_POST['btnsave'])) != "")
	{

				$allowed_filetypes = array('.jpeg' ,'.png','.jpg','.gif','.ico'); // These will be the types of file that will pass the validation.
				$max_filesize = 524288; // Maximum filesize in BYTES (currently 0.5MB).
				$uploads = wp_upload_dir();
				$base1=$uploads[basedir];
				//print_r($base1);
				$upload_path = $base1.'/categoryimg'; // The place the files will be uploaded to (currently a 'files' directory).
				//print_r($upload_path);
				$filename = $_FILES['fileup']['name']; // Get the name of the file (including file extension).
				$ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
				
				// Check if the filetype is allowed, if not DIE and inform the user.
				if(!in_array($ext,$allowed_filetypes))
				die('The file you attempted to upload is not allowed.');

				// Now check the filesize, if it is too large then DIE and inform the user.
				if(filesize($_FILES['userfile']['tmp_name']) > $max_filesize)
				die('The file you attempted to upload is too large.');

				// Check if we can upload to the specified path, if not DIE and inform the user.
				if(!is_writable($upload_path))
				die('You cannot upload to the specified directory, please CHMOD it to 777.');

				// Upload the file to your specified path.
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
                    //echo "success.......";
                
            }
	}


		//$url=admin_url('admin.php?page=add_new_gallery_images');
		$i=1;
		global $wpdb;
			$plugpath = plugin_dir_url( __FILE__ );
	   $table_name=$wpdb->prefix . "galimage";
		$result = $wpdb->get_results("SELECT * from $table_name where catid='$category'");

		?>
		<div class="wrap">
			<div class="manage-menus">
				<table border='1' class="wp-list-table widefat fixed striped pages" style="text-align:center;"  >
					<thead style="background-color:lightblue">
							<tr>
								<th style="text-align:center;font-weight:bold" >No</th>
								<th style="text-align:center;font-weight:bold" >Image</th>
								<th style="text-align:center;font-weight:bold" >Image name</th>
								<th style="text-align:center;font-weight:bold" >Publish</th>
								<th style="text-align:center;font-weight:bold" >Delete</th>
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
					//print_r($upload_dir)
						
					?>
					<tbody>
					<tr>
						<td><?php echo $i++; ?></td>
						<td><img src="<?php echo $upload_dir[baseurl] . "/categoryimg/$img"; ?>" height="100" width="150" title="Image" style="cursor:pointer"/></td>
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
				</table>
			</div>
		</div>

	<script>
	function checkDelete(){
	    return confirm('Are you sure want to Delete?');
	}
	</script>

<?php
	}
ob_flush();
?>