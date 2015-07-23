<?php
error_reporting(E_ALL);
ini_set("error_display",1);
$this->titre = "Facebook | Concours photo ";
$this->js ='<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
            <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>';
$cssLink = '<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
            <link rel="stylesheet" href="Contenu/css/style.css" />';
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <!--
    <meta property="fb:app_id"          content="<?= FB_APPID ?>" />
    <meta property="og:type"            content="article" />
    <meta property="og:url"             content="https://devfbeb1.herokuapp.com" />
    <meta property="og:title"           content="Introducing our New Site" />
    <meta property="og:description"    content="http://samples.ogp.me/390580850990722" />!-->
    <base href="<?= $racineWeb ?>" >
    <?= $cssLink ?>
    <title><?= $this->titre ?></title>
</head>
<body>
<div id="fb-root">
</div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '<?php echo FB_APPID; ?>',
            xfbml      : true,
            version    : 'v2.3'
        });
        FB.Canvas.setSize({ width: 800, height: 650 });    };
    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/fr_FR/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<?=$this->js?>