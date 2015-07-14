/**
 *
 */

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#photo_prev').attr('src', e.target.result);
        }
        console.log(input.files[0]);
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).ready(function () {
    $("#fichier").change(function(){
        readURL(this);
        $("#is_new_image").val('true');
        setPhotoFrom("local");
    });
});

/**
 * param local ou fb
 * @param photoFrom
 */
function setPhotoFrom(photoFrom){
    console.log("photo from "+photoFrom);
    $("#photo_from").val(photoFrom);
}


