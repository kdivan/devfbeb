<?php
//var_dump($participationDataArray);
?>

<div id="dialog_main">

    <div id="upload_by">

    </div>

    <div id="photo_main">
        <img id="participation_photo" src="<?=$participationDataArray['source']?>" width="250px" height="400px" align="middle"/>
    </div>

    <div id="participation_message">
        <p><?=$participationDataArray['name']?></p>
    </div>

    <div class="fb-like" data-href="<?=SERVER_NAME?>photo/participation/<?=$participationDataArray['id']?>" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
    <br/>
    <div class="fb-comments" data-href="<?=SERVER_NAME?>photo/participation/<?=$participationDataArray['id']?>" data-numposts="5" data-colorscheme="light"></div>

    <div id="social_buttons">

    </div>

    <div id="module_facebook">

    </div>
</div>
