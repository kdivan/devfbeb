<section id="body">
    <img src="Contenu/img/appareil-photo.png" alt="" />
    <h2>Concours photo</h2>
    <div class="space">&nbsp;</div>

    <?php if ( $editMode=="true" ) { ?>
    <?php $str = "<p>Vos modifications ont bien été prise en compte !</p><p>Merci une nouvelle fois d'avoir participer  ! Vous pouvez dès maintenant partager notre page pour multiplier vos chances de gagner,
    partager votre photo et également voter !</p>";
    } else {
    $str = "<p>Merci d'avoir participer  !</p><p>Vous pouvez dès maintenant partager notre page pour multiplier vos chances de gagner,
    partager votre photo et également voter !</p>";
    }
    echo $str;
    ?>
</section>

<section class="cta-merci">
    <a href="http://www.facebook.com/sharer/sharer.php?u=https://www.facebook.com/KotorsOfficiel?ref=hl&title=test"
       target="_blank" onclick="return !window.open(this.href, 'Facebook', 'width=640,height=300')" >Partager Kotors</a>
    <a href="photo/gallery">Voter</a>
    <a href="photo/participation/<?=$fbPariticipationId ?>">Voir photo</a>
</section>

<div class="clear"></div>

<?php
//var_dump($lastId);