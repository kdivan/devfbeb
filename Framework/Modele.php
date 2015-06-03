<?php

require_once 'Configuration.php';

/**
 * Classe abstraite Modèle.
 * Centralise les services d'accès à une base de données.
 * Utilise l'API PDO de PHP
 *
 * @version 1.0
 * @author Baptiste Pesquet
 */
abstract class Modele {

    /** Objet PDO d'accès à la BD 
        Statique donc partagé par toutes les instances des classes dérivées */
    private static $bdd;


    /**
     * @param $nomTable
     * @param $data
     * @return string
     */
    public function insert($nomTable,$data){
        $cpt            = 0;
        $prepareQuery   = "";
        $nomVal         = "";
        //Creation de la requete
        $sql='INSERT INTO '.$nomTable.' ';
        foreach($data as $cle => $valeur){
            if($cpt==0){
                $prepareQuery.= $cle;
                $nomVal.=":".$cle;
            }else{
                $prepareQuery.=",".$cle;
                $nomVal.=", :".$cle;
            }
            $execArray[":".$cle]=$valeur;
            $cpt++;
        }
        //ecriture de la requête
        $sql=$sql."(".$prepareQuery.") VALUES (".$nomVal.")";
        //Prepare sql
        $resultat       = self::getBdd()->prepare($sql); // requête préparée
        $resultat->execute($data);
        $lastInsertId   = self::getBdd()->lastInsertId();
        return $lastInsertId;
    }

    /**
     * Exécute une requête SQL
     * 
     * @param string $sql Requête SQL
     * @param array $params Paramètres de la requête
     * @return PDOStatement Résultats de la requête
     */
    protected function executerRequete($sql, $params = null) {
        if ($params == null) {
            $resultat = self::getBdd()->query($sql);   // exécution directe
        }
        else {
            $resultat = self::getBdd()->prepare($sql); // requête préparée
            $resultat->execute($params);
        }
        return $resultat;
    }

    /**
     * Renvoie un objet de connexion à la BDD en initialisant la connexion au besoin
     * 
     * @return PDO Objet PDO de connexion à la BDD
     */
    private static function getBdd() {
        if (self::$bdd === null) {
            // Récupération des paramètres de configuration BD
            $dsn = Configuration::get("dsn");
            $login = Configuration::get("login");
            $mdp = Configuration::get("mdp");
            // Création de la connexion
            self::$bdd = new PDO($dsn, $login, $mdp, 
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        return self::$bdd;
    }

}
