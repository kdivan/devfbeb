/**
 * Created by Divan on 19/05/2015.
 */
var elementLoad = 0;
$(document).ready(function() {
    console.log($("#elementLoad").val());
    elementLoad = $("#elementLoad").val();
});
var maxElementToLoad = 8;
elementLoad = 0;

function closeDialog(){
    $("#photo_detail_content").dialog('close');
}


function setContentAndHideLoader(data){
    console.log("facebook parse");
    $("#photo_detail_content").html("");
    $("#photo_detail_content").html(data);
    console.log("hide loader");
    $('#loading_ajax').hide();
    $("#photo_detail_content").show();
}


function loadMoreData(){
    limitMin    = elementLoad;
    elementLoad+=maxElementToLoad;
    limitMax    = elementLoad;
    console.log(limitMin);
    console.log(limitMax);
    var selectedFilter = $("#filter").val();
    /*if(isNaN(limitMax) || isNaN(limitMin)){
        alert('isnan');
    }*/
    if (!(isNaN(limitMax) || isNaN(limitMin)|| typeof limitMax=='undefined' || typeof limitMin=='undefined' )){
        $.ajax({
            type : 'POST',
            url: 'photo/getmoreparticipation',
            data: {
                'limitMin' : limitMin,
                'limitMax' : limitMax,
                'filter'   : selectedFilter
            },
            success: function(data) {
                //  console.log(data);
                $("#participationLoad").html( $("#participationLoad").html()+data);
            },
            error: function(xhr, status, error) {
                //document.getElementById("errorLog").innerHTML="Erreur - Impossible d'enregistrer. Contactez l'admin du site.";
                console.log(xhr);
                console.log(status);
            }
        });
    }
}

//loader on scroll
FB.Canvas.getPageInfo(
    function(info) {
        alert('Width: ' + info.clientWidth + ' Height: ' + info.clientHeight);
    }
);


$(window).scroll(function() {
    //control si la div est active ou non => indique si il y a des données à charger ou non
    divStatus   = $( "#participationLoad" ).attr( 'class' );
    if(divStatus.localeCompare("disable")!=0){
        console.log("window scrolltop" + $(window).scrollTop() + "document height" + $(document).height() + "window height" +  $(window).height());
        if($(window).scrollTop() == $(document).height() - $(window).height()) {
            // ajax call get data from server and append to the div
            console.log("bottom");
            loadMoreData();
        }
    }
});


$(document)
    .ajaxStart(function () {
        console.log("ajax start");
        $('#loadingDiv').show();
    })
    .ajaxStop(function () {
        console.log("ajax stop");
        $('#loadingDiv').hide();
    });

$(function() {
// OPACITY OF BUTTON SET TO 0%
    $(".roll").css("opacity","0");

// ON MOUSE OVER
    $(".roll").hover(function () {

// SET OPACITY TO 70%
            $(this).stop().animate({
                opacity: .7
            }, "slow");
        },

// ON MOUSE OUT
        function () {

// SET OPACITY BACK TO 50%
            $(this).stop().animate({
                opacity: 0
            }, "slow");
        });
});