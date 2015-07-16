<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("error_display",1);
$this->titre    = "Facebook | Participation";
$customJsLink   ='<script src="Contenu/js/ajax_handler.js"></script>
            <script src="Contenu/js/functions.js"></script>';
$imgSrc = "#";
$usrMsg = "";
if( isset( $redirectLink ) ){
    echo $redirectLink ;
}
?>

<?php if ( isset($errorMessage) || !(is_null($errorMessage)) || isset($notAllowed) ) { ?>
    <?php $errorMessage = ($notAllowed)?"Vous n'êtes pas autorisé à modifier cette photo":$errorMessage;?>
    <div id="error_message"><?=$errorMessage?></div>
<?php } else { ?>


    <?php if ( isset($editParticipation) ) {
        $imgSrc = $participationDataArray[0]['source'];
        $usrMsg = $participationDataArray[0]['name'];
        var_dump($participationDataArray);
    } ?>
    <section id="upload">
        <img src="Contenu/img/appareil-photo.png" alt="" />
        <h2>télécharger ma photo</h2>
    </section>

    <section class="zone-image" >
        <div id="photo-preview-container" >
            <img id="photo_prev" src="<?= $imgSrc ?>" alt="Preview de votre photo (600px x 600px maximum)" width="300px" height="300px" />
        </div>
    </section>

    <div id="from_facebook_dialog" style="display: none;" >
        <div class="albums"><h6>Albums</h6>
            <ul class="list-inline">
                <?php foreach($albumsArray as $album) { ?>
                    <li onclick="displayPhotos('<?= $album['id'] ?>')">
                        <div class="container">
                            <img width="<?=ALBUM_WIDTH?>" height="<?ALBUM_HEIGHT?>" class="image" src="<?=$album['source']?>">
                            <p class="text">Selectionner</p>
                            <div class="album-name">'<?=$album['name']?>'</div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="photos"></div>
    </div>

    <section class="zone-cta">
        <form id="form" method="post" action="photo/participer" enctype="multipart/form-data">

            <div id="from_facebook" class="cta-photo" onclick="openDialog()">Photo Facebook</div>

            <div id="from_computer">
                <div class="cta-photo" id="choose_file">
                    <input type="file" name="fichier" id="fichier" /><br />
                    Mon Ordinateur
                </div>
                <br><p><label for="name">Message : </label><input value="<?= $usrMsg ?>" id="user_message" type="text" name="message" placeholder="<?php echo $message; ?>" /></p>
                <input type="hidden" name="MAX_FILE_SIZE" value="1048576000" />
                <div id="photo_error"></div>
                <input  id="valid-button" class="cta-valider" type="submit" name="submit" value="Valider" />
            </div>

            <input type="hidden" name="is_new_image" id="is_new_image">
            <input type="hidden" name="photo_facebook_id" id="photo_facebook_id" />
            <input type="hidden" name="photo_from" id="photo_from" />
            <?php if ( isset($editParticipation) ) {
                ?> <input type="hidden" name="id_participation" id="id_participation" value="<?=$participationDataArray[0]['id_participation']?>" />
                <input type="hidden" name="edit_mode" id="edit_mode" value="<?=$editParticipation?>" />
            <?php } ?>
        </form>
        <a href="photo/gallery" id="show_gallery" class="cta-galerie" >Voir la galerie</a>
    </section>

    <div class="clear"></div>
<?php } ?>
<?=$customJsLink?>