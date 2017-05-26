
<div class="wrap">
    <h1>List Of Gallery Images</h1>
    <hr/>    
</div>
<form method="post" enctype="multipart/form-data">
  <?php wp_nonce_field( 'addimages', 'addimg' );?>
    <div class="wrap manage-menus">
        <h3 class="">Add Gallery Image</h3>
        <div class="upload-images">
            <input type="file" multiple name="fileup[]"  id="img1" required onchange="validateImage('img1')">
            <font color='red'> <div id="error"> </div> </font>
        </div>
        <div class="">
            <input type="submit" value="Save" name="btnsave" class="button button-primary button-large">
            <button type="Button" onclick="javascript:window.location = '<?php echo esc_url($this->url) ?>';" class="button button-primary button-large">Back</button>            
        </div>
    </div>
</form>