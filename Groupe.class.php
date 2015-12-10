<?php 
class groupe {
        private $userid; 
        private $nomGroupe;
        private $nbPersGroupe;
        private $styleGroupe;
        private $numGroupe;
        // Ouverture de la base de donnée

        function __construct() {
        }

        function nom() {
          return $this->nomGroupe;
        }

        function nbPers() {
          return $this->nbPersGroupe;
        }

        function style() {
          return $this->styleGroupe;
        }

        function numeroTel() {
          return $this->numGroupe;
        }

        function userid() {
          return $this->useridGroupe;
        }

        function createGroupe($nom,$nb,$style,$numeroTel) {
          $this->nomGroupe = $nom;
          $this->nbPersGroupe = $nb;
          $this->styleGroupe = $style;
          $this->numGroupe = $numeroTel;
        }



}       
        ?>