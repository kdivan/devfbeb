<?php
$customJs   ='<script src="Contenu/js/ajax_handler.js"></script>
            <script src="Contenu/js/functions.js"></script>';
//var_dump($_SESSION);
echo $redirectLink;
if($session){ ?>
    <a href="photo/">Participer</a>
    <a href="photo/gallery">Galerie</a>
<?php }else{ ?>
    <div class="error"><?= $logMessage ?> </div>
<?php } ?>
<?=$customJs?>