<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("error_display",1);
$this->titre    = "Facebook | Participation";
$customJsLink   ='<script src="Contenu/js/ajax_handler.js"></script>
            <script src="Contenu/js/functions.js"></script>';
?>

<div id="show_gallery"><a href="photo/gallery">Voir la galerie</a></div>

<?php if(isset($dejaPartMessage)){ ?>

    <div id="particpate"><?=$dejaPartMessage?></div>

<?php }else { ?>
            <?php if ( isset($errorMessage) || !(is_null($errorMessage)) ) { ?>
                <div id="error_message"><?=$errorMessage?></div>
            <?php }?>
    <div id="main_photo">

        <div id="photo preview">
            <img id="photo_prev" src="#" alt="Preview de votre photo (600px x 600px maximum)" width="300px" height="300px" />
        </div>

        <div id="photo_choice">
            <form id="form" method="post" action="photo/participer" enctype="multipart/form-data">

                <div id="from_facebook_dialog" style="display: none;" >
                    <div class="albums"><h6>Albums</h6>
                        <ul>
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

                <div id="from_facebook" onclick="openDialog()">Photo Facebook</div>

                <div id="from_computer"> Photo de mon ordinateur
                    <p><label for="name">Message : </label><input type="text" name="message" placeholder="<?php echo $message; ?>" /></p>
                    <div id="choose_file">
                        <input type="file" name="fichier" id="fichier" /><br />
                        Mon Ordinateur
                    </div>
                    <input type="hidden" name="MAX_FILE_SIZE" value="1048576000" />
                    <br />
                    <div id="photo_error"></div>
                    <input type="submit" name="submit" value="Envoyer" />
                </div>

                <input type="hidden" name="photo_facebook_id" id="photo_facebook_id" />
                <input type="hidden" name="photo_from" id="photo_from" />

            </form>

        </div>

    </div>

<?php } ?>
<?=$customJsLink?>