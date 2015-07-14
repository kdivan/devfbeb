<?php $this->titre = "Facebook | Gallerie Photos"; $customJs='<script src="Contenu/js/getparticipationdetail.js"></script>'; $cpt = 0;?>

<div id="top-gallery">
    <div id="participer">
    <?php
    if($hasParticipate){
        ?> <a href="photo/participation/<?=$participation['id']?>">Ma participation</a>
    <?php
    } else {
        ?><a href="photo/participer">Participer</a>
    <?php
    }
    ?>
    </div>
    <div>
        <select>
            <option></option>
            <option></option>
            <option></option>
        </select>
    </div>
</div>

<div id="gallery_photo">
    <ul class="list-inline" id="liste_photos">
    <?php foreach($photosGalleryArray as $photo) { ?>
        <?php if($cpt == MAX_IMAGE_PER_LINE) { ?>
                <br />
            <?php $cpt=0; } ?>
            <li class="photo_participation" >
                <a href="photo/participation/<?=$photo['id_participation']?>">
                <img src="<?= $photo['source'] ?>" width="100px" height="100px"/>
                <figcaption>
                    <?= "TEST" ?>
                </figcaption>
                </a>
                <!--On affiche dans cette div le bouton voter, le nombre de like et un message!-->
                <div class="content"></div>
            </li>
    <?php $cpt++; } ?>
        <form>
            <input type="hidden" value="<?=$elementLoad?>" id="elementLoad"/>
        </form>
        <div style="display: inline" class="<?=$class?>" id="participationLoad"></div>
        <div align="center" id="loadingDiv" style="display: none">
            <img src="Contenu/img/facebook-loader.gif" alt="Chargement ..."/>
        </div>
    </ul>
<!-- On va afficher les photos en ul/li avec de l'ajax !-->
    <div id="photo_detail_content" style="display:none;">
        <div align="center" id="loading_ajax" style="display: none">
            <img src="Contenu/img/facebook-loader.gif" alt="Chargement ..."/>
        </div>
    </div>
</div>
<?=$customJs?>