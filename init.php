<?php
/*
Plugin Name:Cateorized Gallery Plugin
Description:In this you can add images category wise,
Version: 1
Author: wamasoftware
Author URI: http://wamasoftware.com
*/
?>
<?php
ob_start();
function gallery_options_install()
{
				$upload = wp_upload_dir();
			    $upload_dir = $upload['basedir'];
			    $upload_dir = $upload_dir . '/categoryimg';
			    if (! is_dir($upload_dir)) {
			       mkdir( $upload_dir, 0777 );
			    }
				global $wpdb;
				$table_name=$wpdb->prefix . "galcategory";
				$charset_collate = $wpdb->get_charset_collate();
				$sql="CREATE TABLE $table_name(
					`catid` int(11) NOT NULL AUTO_INCREMENT,
					`categorynm` varchar(255) NOT NULL,
					`catimage` varchar(255) NOT NULL,
					`date` DATE NOT NULL,
					`publish` int(11) NOT NULL,
					PRIMARY KEY (`catid`) 
					) $charset_collate;";
				  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			    	dbDelta($sql);

			    	global $wpdb;
				$table_name=$wpdb->prefix . "galimage";
				$charset_collate = $wpdb->get_charset_collate();
				$sql1="CREATE TABLE $table_name(
					`imgid` int(11) NOT NULL AUTO_INCREMENT,
					`catid` int(11) NOT NULL,
					`imagenm` varchar(255) NOT NULL,
					`imagecrop` varchar(255) NOT NULL,
					`publish` int(11) NOT NULL,
					`catpub` int(11) NOT NULL,
					PRIMARY KEY (`imgid`) 
					) $charset_collate;";

				  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			    	dbDelta($sql1);
}
register_activation_hook(__FILE__, 'gallery_options_install');
add_action('admin_menu','gallery_menu');
function gallery_menu()
			{
				add_menu_page('Gallery', //page title
				'Gallery', //menu title
				'manage_options', //capabilities
				'gallery_list', //menu slug
				'list_gallery_images' //function
				);

				add_submenu_page('gallery_list', //parent slug
				'Add New category ', //page title
				'Add New category', //menu title
				'manage_options', //capability
				'add_new_gallery_images', //menu slug
				'add_new_gallery_images'); //function

				add_submenu_page(null,
				'List gallery album', 
				'list gallery album', 
				'manage_options', 
				'add_gallary_images', 
				'add_gallary_images'); 

				add_submenu_page(null,
				'delete gallery album',
				'delete gallery album',
				'manage_options',
				'delete_gallery_album',
				'delete_gallery_album');

				add_submenu_page(null, 
				'delete gallery title',
				'delete gallery title',
				'manage_options', 
				'delete_gallery_title',
				'delete_gallery_title');

				add_submenu_page(null,
				'user gallery publish',
				'user gallery publish',
				'manage_options',
				'update_publish_gallery_image',
				'update_publish_gallery_image');

				add_submenu_page(null, 
				'user album publish', 
				'user album publish',
				'manage_options',
				'update_publish_gallery_album',
				'update_publish_gallery_album'); 

				add_submenu_page(null,
				'user image crop',
				'user image crop',
				'manage_options',
				'image_resize_crop1',
				'image_resize_crop1');

				add_submenu_page(null,
				'user image reset', 
				'user image reset', 
				'manage_options', 
				'reset_image', 
				'reset_image'); 		
			}
			define('ROOTDIR1', plugin_dir_path(__FILE__));
			require_once(ROOTDIR1 . 'add_new_images.php');
			require_once(ROOTDIR1 . 'list_gallery_images.php');
			require_once(ROOTDIR1 . 'add_gallery_images.php');
			require_once(ROOTDIR1 . 'delete_gallery_album.php');
			require_once(ROOTDIR1 . 'delete_gallery_title.php');
			require_once(ROOTDIR1 . 'update_publish_gallery_image.php');
			require_once(ROOTDIR1 . 'crop_image.php');
function gallery_plugin_remove_database() 
{
     global $wpdb;
   $table_name=$wpdb->prefix . "galcategory";
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
     delete_option("my_plugin_db_version");
     
      $table_name=$wpdb->prefix . "galimage";
     $sql1 = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql1);
     delete_option("my_plugin_db_version");
     
}    
register_deactivation_hook( __FILE__, 'gallery_plugin_remove_database' );
add_shortcode( 'image_gallery', 'category_shortcode' );
function add_css_js_galleryplug() {
    wp_enqueue_style('theme-itme1', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
    wp_enqueue_style('theme-itme2', 'https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css');
    wp_enqueue_style('theme-itme3', 'https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css');
    
    wp_enqueue_script('inkthemes1', plugins_url( 'js/jquery.Jcrop.min.js' , __FILE__ ));
    wp_enqueue_script('inkthemes2', plugins_url( 'js/jquery.Jcrop.js' , __FILE__ ));
    wp_enqueue_script('inkthemes2', plugins_url( 'js/jquery-pack.js' , __FILE__ ));
    wp_enqueue_script('inkthemes5s', plugins_url( 'js/jquery.imgareaselect.min.js', __FILE__ ));
    wp_enqueue_style('inkthemes3', plugins_url( "/css/jquery.Jcrop.css" , __FILE__ ));
    wp_localize_script('inkthemes4', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php')));
    
    //wp_enqueue_script('theme-itme5', 'https://code.jquery.com/jquery-1.12.4.js');
    wp_enqueue_script('theme-itme6', 'https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js');
     wp_enqueue_script('theme-itme7', 'https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js');
    wp_enqueue_script('theme-itme8', 'https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js');
    wp_enqueue_script('theme-itme9', 'https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js');
}

add_action('admin_head', 'add_css_js_galleryplug');

function category_shortcode($attr)
{
?>
	
<link rel="stylesheet" type="text/css" media="screen" href="http://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.css" />

<style type="text/css">
    a.fancybox img {
		max-height:200px;
		display: inline-block;
		cursor: pointer;
        border: none;
        box-shadow: 0 1px 7px rgba(0,0,0,0.6);
        -o-transform: scale(1,1); -ms-transform: scale(1,1); -moz-transform: scale(1,1); -webkit-transform: scale(1,1); transform: scale(1,1); -o-transition: all 0.2s ease-in-out; -ms-transition: all 0.2s ease-in-out; -moz-transition: all 0.2s ease-in-out; -webkit-transition: all 0.2s ease-in-out; transition: all 0.2s ease-in-out;
    } 
    a.fancybox:hover img {
        position: relative; z-index: 999; -o-transform: scale(1.03,1.03); -ms-transform: scale(1.03,1.03); -moz-transform: scale(1.03,1.03); -webkit-transform: scale(1.03,1.03); transform: scale(1.03,1.03);
    }

</style>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.pack.min.js"></script>
<script type="text/javascript">
    $(function($){
        var addToAll =false;
        var gallery = true;
        var titlePosition = 'inside';
        $(addToAll ? 'img' : 'img.fancybox').each(function(){
            var $this = $(this);
            var title = $this.attr('title');
            var src = $this.attr('src');
            //alert(src);
            var a = $('<a href="'+src+'" class="fancybox"></a>').attr('href', src).attr('title', title);
            $this.wrap(a);
        });
        if (gallery)
            $('a.fancybox').attr('rel', 'fancyboxgallery');
        $('a.fancybox').fancybox({
            titlePosition: titlePosition
        });
    });
    $.noConflict();
</script>
<?php
	if(!empty($attr))
	{
		$cat_id = $attr['field'];	
		global $wpdb;
   		$tblname=$wpdb->prefix . "galimage";
   		$result = $wpdb->get_results("SELECT * from $tblname WHERE catid = '$cat_id' AND publish='1' AND catpub='1'");
   		if(!empty($result))
   		{
   				$val=0;
   			$upload_dir = wp_upload_dir();
   		?>
   		<table>
   			<tr>
   		<?php	
			foreach($result as $res)
   			{
   		?>	
   			<td> 
			<div>
			<img class="fancybox" src="<?php echo $upload_dir[baseurl] . "/categoryimg/$res->imagenm"; ?>" data-big="<?php echo $upload_dir[baseurl] . "/categoryimg$res->imagenm"; ?>"/>
		     </div>
			</td>
			<?php
				$val++;
   				if($val==3)
   				{
   					echo "</tr><tr>";
   					$val=1;
   				}	
   			}
   			?>
   		</table>
   		<?php	
   		}
   		else
   		{
   			echo "<b style='text-align: center;color:red;'>Please publish this category.</b>";
   		}
	}
	

}			
?>

<?php
ob_flush();
?>