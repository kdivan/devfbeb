<?php
$cpt = 0;
    //var_dump($participationDataArray);
//exit;
?>

<?php foreach($participationDataArray as $photo) { ?>
    <?php if($cpt == MAX_IMAGE_PER_LINE) { ?>
        <br />
        <?php $cpt=0; } ?>
    <li class="photo_participation" onclick="displayParticipationDetail('<?=$photo['id_participation']?>')">
        <img src="<?= $photo['source'] ?>" width="100px" height="100px"/>
        <figcaption>
            <?= "TEST" ?>
        </figcaption>
        <!--On affiche dans cette div le bouton voter, le nombre de like et un message!-->
        <!--<div id="content"></div>!-->
    </li>
<?php $cpt++; } ?>

<script>
    $(document).ready(function() {
        $("#participationLoad").attr('class','<?=$class?>');
        $("#elementLoad").attr('value','<?=$elementLoad?>');
        console.log('<?=$class?>');
        console.log('<?=$elementLoad?>');
    });
</script>