<?php $this->titre = "Facebook | Gallerie Photos"; $customJs='<script src="Contenu/js/getparticipationdetail.js"></script><script src="Contenu/js/ajax_handler.js"></script>'; $cpt = 0;?>


    <section id="participer">
     <a href="photo/">Participer</a>
    </section>

    <section id="trier">
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
    </section>

<section id="galerie">
    <ul id="liste_photos">
    <?php foreach($photosGalleryArray as $photo) { ?>
        <?php if($cpt == MAX_IMAGE_PER_LINE) { ?>
                <br />
            <?php $cpt=0; } ?>
            <li class="photo_participation" >
                <a href="photo/participation/<?=$photo['facebook_photo_id']?>">
                    <span class="roll" ></span>
                    <img src="<?= $photo['facebook_photo_link'] ?>" width="<?= PHOTO_WIDTH ?>" height="<?= PHOTO_HEIGHT ?>"/>
                    <a href="">Voter</a>
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
</section>

    <div class="clear"></div>

<?=$customJs?>