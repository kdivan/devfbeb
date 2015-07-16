var userCurrPage = 1;
var nbPage;

function displayPhotos(albumId){
    $.ajax({
        type : 'POST',
        url: 'photo/getphotos',
        data: {
            'albumId' : albumId
        },
        success: function(data) {
            $(".photos").html("");
            $(".photos").html(data);
            $(".photos").show();
        },
        error: function(xhr, status, error) {
            document.getElementById("errorLog").innerHTML="";
            document.getElementById("errorLog").innerHTML="Erreur - Impossible d'enregistrer. Contactez l'admin du site.";
            console.log(xhr);
            console.log(status);
        }
    });
}

function openDialog(){
    $("#from_facebook_dialog").dialog({
        title: 'Sélectionnez un album puis une photo : ',
        resizable: false,
        width: 'auto',
        height : 'auto'
    });
}

function closeDialog(){
    $("#from_facebook_dialog").dialog('close');
}


function checkNextPrevButton(){
    if(userCurrPage > 1 && userCurrPage < nbPage){
        //les deux boutons sont actifs
        $( ".next").removeClass("disable");
        $( ".prev").removeClass("disable");

        $( ".next").addClass("enable");
        $( ".prev").addClass("enable");
    }else if(userCurrPage == 1){
        //bouton previous desactivé et next activé
        $( ".next").removeClass("disable");
        $( ".prev").removeClass("enable");

        $( ".next").addClass("enable");
        $( ".prev").addClass("disable");
    }else if(userCurrPage == nbPage){
        //bouton next desactivé
        $( ".prev").removeClass("disable");
        $( ".next").removeClass("enable");

        $( ".prev").addClass("enable");
        $( ".next").addClass("disable");
    }
}

function displayPhotoPage(currentPage){
    userCurrPage = parseInt(currentPage);
    $(".photo").hide();
    $(".elem_"+currentPage).show();
}

function nextPage(){
    console.log("nextpage");
    if(userCurrPage < nbPage) {
        $(".photo").hide();
        var newPage = parseInt(userCurrPage) + 1;
        $(".elem_" +newPage).show();
        userCurrPage = newPage;
        console.log("user currpage now " + userCurrPage)
        checkNextPrevButton();
    }
}

function prevPage(){
    if(userCurrPage > 1) {
        $(".photo").hide();
        var newPage =parseInt(userCurrPage) - 1;
        $(".elem_" +newPage).show();
        userCurrPage = newPage;
        console.log("user currpage now " + userCurrPage)
        checkNextPrevButton();
    }

}

function addHover(){
    $(".container").hover(
        function () {
            //console.log($(this).children(":nth-child(2)"));
            $(this).children(":nth-child(2)").show();
        }, function() {
            $(this).children(":nth-child(2)").hide();
        }
    );
}

function setFacebookId(photoId){
    console.log("photo fb id "+photoId);
    $("#photo_facebook_id").val(photoId);
}

function showPhotoToPreview(photoSource){
    if (photoSource) {
        $('#photo_prev').attr('src', photoSource);
    }
    $("#is_new_image").val('true');
    console.log(photoSource);
    setPhotoFrom("fb");
    closeDialog();
}


/**
 * param local ou fb
 * @param photoFrom
 */
function setPhotoFrom(photoFrom){
    console.log("photo from "+photoFrom);
    $("#photo_from").val(photoFrom);
}

$( document ).ajaxComplete(function() {
    nbPage = $( "#nbPage").val();
    console.log($( "#nbPage").val());
});


$(document).ready(function() {
    $(".container").hover(
        function () {
            //console.log($(this).children(":nth-child(2)"));
            $(this).children(":nth-child(2)").show();
        }, function() {
            $(this).children(":nth-child(2)").hide();
        }
    );
});

$(document)
    .ajaxStart(function () {
        console.log("ajax start");
        $('#loadingDiv').show();
    })
    .ajaxStop(function () {
        console.log("ajax stop");
        $('#loadingDiv').hide();
        addHover();
    });

$("#form").submit(function( event ){
    console.log(event);
    console.log($("#fichier").val());
    var photo_from  = $("#photo_from").val();
    var usrMsg      = $("#user_message").val();
    var isNewImg    = $("#is_new_image").val();
    console.log(isNewImg);
    if(isNewImg.trim().localeCompare('true') == 0){

    }else {
        //afficher un message d'erreur
        event.preventDefault();
        console.log('Erreur');
        return false;
    }
});

$("#filter").change(function() {
    console.log($("#filter"));
    console.log($("#filter").val());
    refreshGallery();
    console.log("test");
});

function refreshGallery(){
    var selectedFilter = $("#filter").val();
    $.ajax({
        type : 'POST',
        url: 'photo/gallery',
        data: {
            'filter'   : selectedFilter
        },
        success: function(data) {
            $("#contenu").html("");
            $("#contenu").html(data);
        },
        error: function(xhr, status, error) {
            //document.getElementById("errorLog").innerHTML="Erreur - Impossible d'enregistrer. Contactez l'admin du site.";
            console.log(xhr);
            console.log(status);
        }
    });
}