<div id="dialog_main">

    <section id="back_gallery">
        <a href="photo/gallery">Retour à la galerie</a>
    </section>

    <section id="participer_link">
        <a href="photo/">Participer</a>
    </section>

    <section id="participation_display">
        <img class="photo_main" id="participation_photo" src="<?=$participationDataArray['facebook_photo_link']?>" width="250px" height="400px" align="center"/>

        <div id="participation_message">
            <p>"<?=$participationDataArray['message']?>"</p>
        </div>

        <div class="fb-like" data-href="<?=SERVER_NAME?>photo/participation/<?=$participationDataArray['facebook_photo_id']?>" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
        <br/>
        <div class="fb-comments" data-href="<?=SERVER_NAME?>photo/participation/<?=$participationDataArray['facebook_photo_id']?>" data-numposts="5" data-colorscheme="light"></div>

    </section>
</div>
