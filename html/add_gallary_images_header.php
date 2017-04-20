<div class="wrap">
    <h1>List Of Gallery Images</h1>
    <hr/>    
</div>
<form method="post" enctype="multipart/form-data">
    <div class="wrap manage-menus">
        <h3 class="">Add gallery image</h3>
        <div class="upload-images">
            <input type="file" multiple name="fileup[]"  id="img1" required onchange="validateImage('img1')">
            <font color='red'> <div id="error"> </div> </font>
        </div>
        <div class="">
            <input type="submit" value="save" name="btnsave" class="button button-primary button-large">
            <button type="Button" onclick="javascript:window.location = '<?php echo $this->url ?>';" class="button button-primary button-large">Back</button>            
        </div>
    </div>
</form>