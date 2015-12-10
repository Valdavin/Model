<?php 
class groupe {
        private $nbPersGroupe;
        private $styleGroupe;
        // Ouverture de la base de donnée

        function __construct() {
        }

        function nbPers() {
          return $this->nbPersGroupe;
        }

        function style() {
          return $this->styleGroupe;
        }


        function createGroupe($style,$nb) {
          $this->nbPersGroupe = $nb;
          $this->styleGroupe = $style;
        }



}       
        ?>