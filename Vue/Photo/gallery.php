<?php $this->titre = "Facebook | Gallerie Photos"; $customJs='<script src="Contenu/js/getparticipationdetail.js"></script><script src="Contenu/js/ajax_handler.js"></script>'; $cpt = 0;?>

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
        <select id="filter" name="filter">
            <?php foreach( $filterArray as $filter ) {
                $selected = "";
                if(strcmp($selectedFilter,$filter['filter_val']) == 0) {
                    $selected = "selected";
                }
                ?>
                <option value="<?=$filter['filter_val']?>" <?=$selected?>><?=$filter['filter_string']?></option>
            <?php } ?>
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
                    <span class="roll" ></span>
                    <img src="<?= $photo['source'] ?>" width="100px" height="100px"/>
                    <div><p>Voter   <?=(isset($photo['stats']->like_count)) ? $photo['stats']->like_count : 0?></p></div>
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