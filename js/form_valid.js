function checkmultipledelete(){
    return confirm('Are you sure you want to Delete?');
}
function checkDeleteimg()
{
    return confirm('Are you sure you want to Delete?');
}
function checkunimgPublish()
{
    return confirm('Are you sure you want to unpublish this image?');
}
function checkimgPublish()
{
    return confirm('Are you sure you want to publish this image?');
}
function checkreset()
{
    return confirm('Are you sure you want to reset this image?');
}
function checkDelete()
{
    return confirm('Are you sure you want to Delete All images of this gallery?');
}
function checkunPublish()
{
    return confirm('Are you sure you want to unpublish?');
}
function checkPublish()
{
    return confirm('Are you sure you want to publish?');
}

//script for multiple delete images at a same time
jQuery(document).ready(function () {
    jQuery('#select_all').on('click', function () {
        if (this.checked) {
            jQuery('.checkbox').each(function () {
                this.checked = true;
            });
        } else {
            jQuery('.checkbox').each(function () {
                this.checked = false;
            });
        }
    });
    jQuery(".checkbox").click(function () {
        if (jQuery(".checkbox").length == jQuery(".checkbox:checked").length) {
            jQuery("#select_all").attr("checked", "checked");
        } else {
            jQuery("#select_all").removeAttr("checked");
        }

    });
});
function EnableSubmit(val)
{
    var sbmt = document.getElementById("btn1");
    var check = jQuery("input:checkbox:checked").length;
    if (check == 0)
    {
        sbmt.disabled = true;
    } else
    {
        sbmt.disabled = false;
    }
}

function EnableCheckBox(val)
{
    var sbmt = document.getElementById("btn1");
    if (val.checked == true)
    {
        sbmt.disabled = false;
    } else
    {
        sbmt.disabled = true;
    }
}
//script for check validation at a time of multiple file upload
function validateImage(id) {
    var formData = new FormData();
    var file = document.getElementById(id).files[0];
    formData.append("Filedata", file);
    var t = file.type.split('/').pop().toLowerCase();
    if (t != "jpeg" && t != "jpg" && t != "png" && t != "bmp" && t != "gif") {
        document.getElementById('error').innerHTML = "Please select only [.jpeg , .jpg , .png , .gif , .bmp] file";
        document.getElementById(id).value = '';
        return false;
    }
    return true;
}

// script for check validation at upload time
function validate_fileupload(fileName)
{
    var allowed_extensions = new Array("jpg", "png", "gif", "jpeg", "bmp");
    var file_extension = fileName.split('.').pop(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.

    for (var i = 0; i <= allowed_extensions.length; i++)
    {
        if (allowed_extensions[i] == file_extension)
        {
            return true; // valid file extension
        }
    }
    document.getElementById('error').innerHTML = "Please select only [.jpeg , .jpg , .png , .gif , .bmp] file";
    return false;
}

//script for crop the image
jQuery('document').ready(function () {
    jQuery('#cropbox').imgAreaSelect({
        onSelectEnd: function (img, selection) {
            jQuery('input[name="x"]').val(selection.x1);
            jQuery('input[name="y"]').val(selection.y1);
            jQuery('input[name="w"]').val(selection.x2);
            jQuery('input[name="h"]').val(selection.y2);
            jQuery(".jcrop-holder").css("margin", "100px");
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
}
;
function checkCoords()
{
    if (parseInt(jQuery('#w').val()))
        return true;
    alert('Select where you want to Crop.');
    return false;
}
;



