<?php
$nbPhotosInAlbum = count($albumPhotosArray['data']);
$nbPage = ceil($nbPhotosInAlbum/MAX_ELEM_PER_PAGE);
$this->js.='<script src="Contenu/js/ajax_handler.js"></script>
            <script src="Contenu/js/functions.js"></script>';
?>

<form>
    <input type="hidden" id="nbPage" value="<?=$nbPage?>">
</form>

<div align="center" id="loadingDiv" style="display: none">
    <img src="Contenu/img/ajax-loader-fb.gif" alt="Chargement ..."/>
</div>

<div>
    <h6>Photos</h6>
    <?php if($nbPage > 1){ ?>
        <ol>
            <li class="prev" onclick="prevPage()" > < </li>
            <?php for($i=1;$i<=$nbPage;$i++){ ?>
                <li onclick="displayPhotoPage('<?=$i?>')"><?=$i?></li>
            <?php } ?>
            <li class="next" onclick="nextPage()"> > </li>
        </ol>
    <?php } ?>
</div>

<ol>
<?php
    $cptPhotos = 0;
    $numPage = 1 ;
    foreach($albumPhotosArray['data'] as $albumPhotos) {
    $cptPhotos+=1;
    if($nbPhotosInAlbum > MAX_ELEM_PER_PAGE){
        $currPage = ceil($cptPhotos/MAX_ELEM_PER_PAGE);
        $style = ($currPage==1)? "style='display:inline'" : "style='display:none'" ;
?>
        <li class="<?='elem_'.$currPage.' photo'?>"  <?=$style?>>
<?php }else { ?>
        <li  class="elem_1 photo" style="display:inline">
    <?php } ?>

            <span class="container" onclick="showPhotoToPreview('<?=$albumPhotos->source?>');setFacebookId('<?=$albumPhotos->id?>')">
            <img src='<?=$albumPhotos->source?>' width=50 height=50>
            <p class="text">Selectionner</p>
            </span>
        </li>

<?php } ?>
</ol>
