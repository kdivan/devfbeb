<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("error_display",1);
$this->js.='<script src="Contenu/js/ajax_handler.js"></script>
            <script src="Contenu/js/functions.js"></script>';
?>

<?= $message ?>

<a href="photo/gallery">Gallerie</a>