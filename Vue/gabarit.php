<?php
require_once "header.php";
?>

    <div id="global">
        <header id="header_gabarit">
            <h1>Kotors</h1>
        </header>
        <div id="contenu">
            <?= $contenu ?>
        </div> <!-- #contenu -->
        <footer id="footer_gabarit">
            <ul>
                <li>
                    <a href="http://www.kotors.fr">Lien du site</a>
                </li>
                <li>
                    <a href="http://www.kotors.fr">Règlement</a>
                </li>
                <li>
                    <a href="">Social Networks</a>
                </li>
            </ul>
            <!-- Latest compiled and minified CSS -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

            <!-- Optional theme -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

            <!-- Latest compiled and minified JavaScript -->
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        </footer>
    </div> <!-- #global -->
<?php
require_once "footer.php";
?>