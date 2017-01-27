
<?php
	function list_gallery_images()
	{
		$plugpath = plugin_dir_url( __FILE__ );
		$url=admin_url('admin.php?page=add_new_gallery_images');
		$i=1;
		global $wpdb;
	   $table_name=$wpdb->prefix . "galcategory";
	    $table_name1=$wpdb->prefix . "galimage";
		$result = $wpdb->get_results("SELECT * from $table_name");
		?>
		<h2>List of Gallery</h2>
		<form>
		<button type="Button" onclick="javascript:window.location='<?php echo $url ?>';" class="button button-primary button-large">Add gallery</button>

		<br><br>
		</form>
		<div class="wrap">
		<table border="1" class="widefat striped" style="text-align:center;"  >
			<thead>
			<tr>
				<th style="text-align:center;">Id</th>
				<th style="text-align:center;">Title</th>
				<th style="text-align:center;">Image</th>
				<th style="text-align:center;">Media</th>
				<th style="text-align:center;">Date</th>
				<th style="text-align:center;">Publish</th>
				<th style="text-align:center;">Add images</th>
				<th style="text-align:center;">Short code</th>
				<th style="text-align:center;">Edit</th>
				<th style="text-align:center;">Delete</th>
			</tr>
		</thead>
			<?php
			$upload_dir = wp_upload_dir();
			foreach($result as $res)
			{
				$catid=$res->catid;
				$img1=$res->catimage;
				$pub=$res->publish;
				//add_shortcode( 'image-gallery-'.$res->catid, 'add_gallary_images' );
				$result1 = $wpdb->get_results("SELECT COUNT(*) AS `count` from $table_name1 where catid='$catid'");
				//print_r($result1);
				foreach($result1 as $res1)
				{
					$coun=$res1->count;
				}

			?>
			<tbody>
					<tr>
						<td><?php echo $i++; ?></td>
						<td><?php echo $res->categorynm; ?></td>
						<td><img src="<?php echo $upload_dir[baseurl] . "/categoryimg/$img1"; ?>" height="100" width="100"/></td>
						<td><?php echo "$coun images" ?></td>
						<td><?php echo "published <br>".$res->date; ?></td>
						<?php if($pub==1)
						{
						?>
						<td><a href="<?php echo admin_url('admin.php?page=update_publish_gallery_image&id=' . $res->catid . "&pubid=" . $pub); ?>" title="publish"><img src="<?php echo $plugpath.'/icons/publish.png'?>" height="30" width="30"></a></td>
						<?php 
						}
						else
						{
						?>
						<td><a href="<?php echo admin_url('admin.php?page=update_publish_gallery_image&id=' . $res->catid . "&pubid=" . $pub); ?>" title="unpublish"><img src="<?php echo $plugpath.'/icons/unpublish.png'?>" height="30" width="30"></a></td>
						<?php
						
						} ?>
						<td align="center"><a href="<?php echo admin_url('admin.php?page=add_gallary_images&catid=' . $res->catid); ?>" title="Add gallery images"><img src="<?php echo $plugpath.'/icons/add.png'?>" height="30" width="30"></a></td>
						<td><?php echo "[image_gallery field='$catid']"?></td>
						<td><a href="<?php echo admin_url('admin.php?page=add_new_gallery_images&id=' . $res->catid); ?>" title="Edit"><img src="<?php echo $plugpath.'/icons/edit.png'?>" height="30" width="30"></a></td>
						<td><a href="<?php echo admin_url('admin.php?page=delete_gallery_title&id=' . $res->catid); ?>" onclick="return checkDelete()" title="delete"  ><img src="<?php echo $plugpath.'/icons/delete.png'?>" height="30" width="30"></a></td>
					</tr>
		</tbody>
			<?php
				}
				
			?>
		</table>
	</div>
	<html>
<head>
	<script>
	function checkDelete(){
    return confirm('Are you sure want to Delete?');
}
</script>
</head>
	<?php
	}
	?>
	</html>