<?php
$cpt = 0;
    //var_dump($participationDataArray);
//exit;
?>

<?php foreach($participationDataArray as $photo) { ?>
    <?php if($cpt == MAX_IMAGE_PER_LINE) { ?>
        <br />
        <?php $cpt=0; } ?>
    <li class="photo_participation">
        <a href="photo/participation/<?=$photo['facebook_photo_id']?>">
            <span class="roll" ></span>
            <img src="<?= $photo['source'] ?>" width="<?= PHOTO_WIDTH ?>" height="<?= PHOTO_HEIGHT ?>"/>
            <a href="">Voter</a>
       </a>
        <!--On affiche dans cette div le bouton voter, le nombre de like et un message!-->
            <div class="content"></div>
    </li>
<?php $cpt++; } ?>

<script>
    $(document).ready(function() {
        $("#participationLoad").attr('class','<?=$class?>');
        $("#elementLoad").attr('value','<?=$elementLoad?>');
        console.log('<?=$class?>');
        console.log('<?=$elementLoad?>');
    });
    $(function() {
// OPACITY OF BUTTON SET TO 0%
        $(".roll").css("opacity","0");

// ON MOUSE OVER
        $(".roll").hover(function () {

// SET OPACITY TO 70%
                $(this).stop().animate({
                    opacity: .7
                }, "slow");
            },

// ON MOUSE OUT
            function () {

// SET OPACITY BACK TO 50%
                $(this).stop().animate({
                    opacity: 0
                }, "slow");
            });
    });
</script>