<?php 
class resplieu {
        private $prenom;

        function __construct() {
        }

        function prenom() {
          return $this->prenom;
        }


        function createResplieu($prenom) {
          $this->prenom = $prenom;
        }



}       
        ?>