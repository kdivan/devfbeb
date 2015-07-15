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

    <section class="zone-image" >
        <div id="photo-preview-container" >
            <img id="photo_prev" src="<?= $imgSrc ?>" alt="Preview de votre photo (600px x 600px maximum)" width="300px" height="300px" />
        </div>
    </section>


    <div id="from_facebook_modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Mes photos facebook</h4>
                </div>
                <div class="modal-body">
                    <div class="albums"><h6>Albums</h6>
                        <ul class="list-inline">
                            <?php foreach($albumsArray as $album) { ?>
                                <li onclick="displayPhotos('<?= $album['id'] ?>')">
                                                <span    class="container">
                                                    <img width="<?=ALBUM_WIDTH?>" height="<?=ALBUM_HEIGHT?>" class="image" src="<?=$album['source']?>">
                                                    <p class="text">Selectionner</p>
                                                    <div class="album-name"><?=$album['name']?></div>
                                                </span>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="photos"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <aside class="zone-cta">
        <div id="photo-choice-container" >
            <form id="form" method="post" action="photo/participer" enctype="multipart/form-data">

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#from_facebook_modal">
                    Photo de Facebook
                </button>

                <div id="from_computer">
                        <span class="btn btn-default btn-file">
                        <input type="file" name="fichier" id="fichier" />
                        Photo de mon ordinateur
                        </span>
                    <p><label for="name">Message : </label><input value="<?= $usrMsg ?>" id="user_message" type="text" name="message" placeholder="<?php echo $message; ?>" /></p>
                    <input type="hidden" name="MAX_FILE_SIZE" value="1048576000" />
                    <br />
                    <div id="photo_error"></div>
                    <input type="submit" name="submit" value="Valider" />
                </div>

                <input type="hidden" name="is_new_image" id="is_new_image">
                <input type="hidden" name="photo_facebook_id" id="photo_facebook_id" />
                <input type="hidden" name="photo_from" id="photo_from" />
                <?php if ( isset($editParticipation) ) {
                    ?> <input type="hidden" name="id_participation" id="id_participation" value="<?=$participationDataArray[0]['id_participation']?>" />
                        <input type="hidden" name="edit_mode" id="edit_mode" value="<?=$editParticipation?>" />
                <?php } ?>
            </form>

            <div id="show_gallery"><a href="photo/gallery">Voir la galerie</a></div>

        </div>
    </aside>
<?php } ?>
<?=$customJsLink?>