<?php
class RSS {
        private $titre; // Titre du flux
        private $url;   // Chemin URL pour télécharger un nouvel état du flux
        private $date;  // Date du dernier téléchargement du flux
        private $nouvelles; // Liste des nouvelles du flux

        // Contructeur
        function __construct($url) {
          $this->url = $url;
        }

        // Fonctions getter
        function titre() {
          return $this->titre;
        }

        function url() {
          return $this->url;
        }

        function date() {
          return $this->date;
        }

        function nouvelles() {
          return $this->nouvelles;
        }

        // Autres methodes

        function update() {

            require_once('Nouvelle.class.php');
            require_once('DAO.class.php');
  		      // Cree un objet pour accueillir le contenu du RSS : un document XML
            $doc = new DOMDocument;

  		      //Telecharge le fichier XML dans $rss
            $doc->load($this->url);

  		      // Recupère la liste (DOMNodeList) de tous les elements de l'arbre 'title'
            $nodeList = $doc->getElementsByTagName('title');

  		      // Met à jour le titre dans l'objet
            $this->titre = $nodeList->item(0)->textContent;

  		      // Recupère la liste (DOMNodeList) de tous les elements de l'arbre 'title'
            $nodeList = $doc->getElementsByTagName('pubDate');

  		      // Met à jour la date dans l'objet
            $this->date = $nodeList->item(0)->textContent;

            $docNodeList =  $doc->getElementsByTagName('item');
            
            $dao = new DAO();
            $dao->createRSS($this->url);
            $id = $dao->returnIdFromURL($this->url);
            $table = array();
            $nomLocalImage = 1;
            foreach ($docNodeList as $nouvelle) {
              $tempNouvelle = new Nouvelle();
              $tempNouvelle->update($nouvelle);
              $tempNouvelle->downloadImage($nouvelle,$nomLocalImage);
              $dao -> createNouvelle($tempNouvelle, $id);
              $nomLocalImage++;
              $table[]= $tempNouvelle;
            }
            $this->nouvelles=$table;
            
            

        }
        
      }
      ?>
