<div id="dialog_main">

    <div>
        <div>
            <a href="photo/gallery">Retour à la gallerie</a>
        </div>
    </div>

    <div id="photo_main">
        <img id="participation_photo" src="<?=$participationDataArray['facebook_photo_link']?>" width="250px" height="400px" align="middle"/>
    </div>

    <div id="participation_message">
        <p><?=$participationDataArray['message']?></p>
    </div>

    <div class="fb-like" data-href="<?=SERVER_NAME?>photo/participation/<?=$participationDataArray['facebook_photo_id']?>" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
    <br/>
    <div class="fb-comments" data-href="<?=SERVER_NAME?>photo/participation/<?=$participationDataArray['facebook_photo_id']?>" data-numposts="5" data-colorscheme="light"></div>

    <div id="social_buttons">

    </div>

    <div id="module_facebook">

    </div>
</div>
