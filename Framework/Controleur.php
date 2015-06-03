<?php

require_once 'Requete.php';
require_once 'Vue.php';

/**
 * Classe abstraite Controleur
 * Fournit des services communs aux classes Controleur dérivées
 * 
 * @version 1.0
 * @author Baptiste Pesquet
 */
abstract class Controleur {

    /** Action à réaliser */
    private $action;
    
    /** Requête entrante */
    protected $requete;

    /**
     * Définit la requête entrante
     * 
     * @param Requete $requete Requete entrante
     */
    public function setRequete(Requete $requete)
    {
        $this->requete = $requete;
    }

    /**
     * Exécute l'action à réaliser.
     * Appelle la méthode portant le même nom que l'action sur l'objet Controleur courant
     * param ajouter par Divan
     * @throws Exception Si l'action n'existe pas dans la classe Controleur courante
     */
    public function executerAction($action,$param=NULL)
    {
        if (method_exists($this, $action)) {
            $this->action = $action;
            if( isset($param) ){
                $this->{$this->action}($param);
            }else{
                $this->{$this->action}();
            }
        }
        else {
            $classeControleur = get_class($this);
            throw new Exception("Action '$action' non définie dans la classe $classeControleur");
        }
    }

    /**
     * Méthode abstraite correspondant à l'action par défaut
     * Oblige les classes dérivées à implémenter cette action par défaut
     */
    public abstract function index();

    /**
     * Génère la vue associée au contrôleur courant
     * 
     * @param array $donneesVue Données nécessaires pour la génération de la vue
     */
    protected function genererVue($donneesVue = array(),$withGabarit=true)
    {
        // Détermination du nom du fichier vue à partir du nom du contrôleur actuel
        $classeControleur = get_class($this);
        $controleur = str_replace("Controleur", "", $classeControleur);
        
        // Instanciation et génération de la vueF
        $vue = new Vue($this->action, $controleur);
        $vue->generer($donneesVue,$withGabarit);
    }

    /**
     * @param $url
     * @param int $statusCode
     */
    function redirect($url, $statusCode = 303)
    {
        header('Location: ' . $url, true, $statusCode);
        die();
    }

}
