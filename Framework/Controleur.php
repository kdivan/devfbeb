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
    protected function genererVue($donneesVue = array(),$withGabarit=true, $action="")
    {
        // Détermination du nom du fichier vue à partir du nom du contrôleur actuel
        $classeControleur = get_class($this);
        $controleur = str_replace("Controleur", "", $classeControleur);
        
        // Instanciation et génération de la vueF
        if(strlen($action)>0){
            $vue = new Vue($action, $controleur);
        } else {
            $vue = new Vue($this->action, $controleur);
        }
        $vue->generer($donneesVue,$withGabarit);
    }

    /**
     * @param $url
     * @param int $statusCode
     */
    public function redirect($url, $statusCode = 303)
    {
        header('Location: ' . $url, true, $statusCode);
        die();
    }

    /**
     * @param $array
     * @param $on
     * @param int $order
     * @return array
     */
    public function array_sort($array, $on, $order=SORT_ASC, $slice="")
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }
            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }
            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }
        if(strlen($slice)>0){
            return array_slice($new_array,0,$slice);
        }
        return $new_array;
    }

}
