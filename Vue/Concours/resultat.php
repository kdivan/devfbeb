<section id="resultat">
    <img src="Contenu/img/appareil-photo.png" alt="" />
    <h2>Résultats Concours photo</h2>
    <div class="space">&nbsp;</div>
    <p>
        Le concours est maintenant terminé.Nous remercions l'ensemble des participants et votants.
    </p>
    <p>
        Félicitations à <strong><?=$winnersArray[0]['from']->name?></strong> qui à remporté le premier prix et qui va recevoir
        la <?= $concoursPrize[0]['prize_name'] ?>,<br><strong><?=$winnersArray[1]['from']->name?></strong>  qui termine deuxième du classement
        et remporte les <?= $concoursPrize[1]['prize_name'] ?> et enfin <br><strong><?=$winnersArray[2]['from']->name?></strong> qui termine
        troisième du classement et remporte les <?= $concoursPrize[2]['prize_name']?>.
    </p>
    <br>&nbsp;
    <p>A bientôt pour un nouveau concours !</p>
</section>

<section id="podium">

    <h2 class="podium-title">
        <img src="images/podium.png" alt="" />
        <span>Podium</span>
    </h2>

    <div class="second-prix">
        <div class="bloc-prix">
            <h3 class="prix-title">second prix</h3>
            <img src="<?=$winnersArray[1]['source']?>" alt="img" width="185"  />
        </div>
        <img class="item-prix item-second" src="Contenu/img/prix-2.png" alt="img" />
    </div>

    <div class="premier-prix">
        <div class="bloc-prix">
            <h3 class="prix-title">premier prix</h3>
            <img src="<?=$winnersArray[0]['source']?>" alt="img" width="185" />
        </div>
        <img class="item-prix item-premier" src="Contenu/img/prix-1.png" alt="img" />
    </div>

    <div class="troisieme-prix">
        <div class="bloc-prix">
            <h3 class="prix-title">troisieme prix</h3>
            <img src="<?=$winnersArray[2]['source']?>" alt="img" width="185" />
        </div>
        <img class="item-prix item-troisieme" src="Contenu/img/prix-3.png" alt="img" />
    </div>

</section>