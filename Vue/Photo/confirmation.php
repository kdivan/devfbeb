<div id="confirmation_message">
    <?php if ( $editMode=="true" ) { ?>
    Vos modifications ont bien été prise en compte ! Merci une nouvelle fois d'avoir participer  ! Vous pouvez dès maintenant partager notre page pour multiplier vos chances de gagner,
    partager votre photo et également voter !
    <?php } else { ?>
    Merci d'avoir participer  ! Vous pouvez dès maintenant partager notre page pour multiplier vos chances de gagner,
    partager votre photo et également voter !
    <?php } ?>
    <br>
    <a href="http://www.facebook.com/sharer/sharer.php?u=https://www.facebook.com/KotorsOfficiel?ref=hl&title=test"
       target="_blank" class="facebook" onclick="return !window.open(this.href, 'Facebook', 'width=640,height=300')" >Partager Kotors</a>
    <a href="photo/gallery">Voter</a>
    <a href="photo/pariticipation/<?=$lastId ?>">Voir photo</a>
</div>
<?php
var_dump($lastId);