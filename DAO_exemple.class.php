<?php 
class DAO {
        private $db; // L'objet de la base de donnée

        // Ouverture de la base de donnée
        function __construct() {
          $dsn = 'mysql:host=localhost;dbname=test;charset=utf8', 'root', ''; // Data source name
          try {
          	$this->db = new PDO($dsn);
          } catch (PDOException $e) {
          	exit("Erreur ouverture BD : ".$e->getMessage());
          }
        }


        //////////////////////////////////////////////////////////
        // Methodes CRUD sur RSS
        //////////////////////////////////////////////////////////

        // Crée un nouveau flux à partir d'une URL
        // Si le flux existe déjà on ne le crée pas
        function createRSS($url) {
          $rss = $this->readRSSfromURL($url);
          if ($rss == NULL) {
            try {
              $titre = $rss->titre();
              $date = $rss->date();
              $q = "INSERT INTO RSS (titre,url,date) VALUES ('$titre','$url','$date')";
              $r = $this->db->exec($q);
              printf(" l %d l ",$r);
              if ($r == 0) {
                die("createRSS error: no rss inserted\n");
              }
              return $this->readRSSfromURL($url);
            } catch (PDOException $e) {
              die("PDO Error :".$e->getMessage());
            }
          } else {
            // Retourne l'objet existant
            return $rss;
          }
        }

        // Renvois l'ID d'un flux grâce à son url
        function returnIdFromURL($url) {
          $q = "SELECT id FROM RSS WHERE url=('$url')";
          try {
            return $this->db->query($q);
          } catch (PDOException $e) {
            die("PDO Error :".$e->getMessage());
          }
        }

        // Renvois une liste des flux
        function allRSS() {
          $q = "SELECT * FROM RSS WHERE URL != ''";
         $rss = $this->readRSSfromURL($url);
         if ($rss == NULL) {
          try {
           $q = "INSERT INTO RSS (url) VALUES ('$url')";
           $r = $this->db->exec($q);
           printf(" l %d l ",$r);
            if ($r == 0) {
             die("createRSS error: no rss inserted\n");
           }
          return $this->readRSSfromURL($url);
        } catch (PDOException $e) {
         die("PDO Error :".$e->getMessage());
       }
     } else {
            // Retourne l'objet existant
      return $rss;
    }
  }

        // Acces à un objet RSS à partir de son URL
        function readRSSfromURL($url) {
          require_once('RSS.class.php');
          try {
            $q = "SELECT * FROM RSS WHERE url=('$url')";
            $r = $this->db->query($q);
            if ($r == NULL) {
              return(NULL);
            }
            $result=$r->fetchAll(PDO::FETCH_CLASS, "RSS");

            if($result != NULL) {
              foreach($result as $r) {
                return $r;
              }
            } else {
              return NULL;
            }
  function readRSSfromURL($url) {

   try {
    $q = "SELECT * FROM RSS WHERE url=('$url')";
    $r = $this->db->query($q);
        if ($r == NULL) {
             return(NULL);
        }
    $r=$r->fetchAll(PDO::FETCH_CLASS, "RSS");
    var_dump($r);
   return $r;
 } catch (PDOException $e) {
  die("PDO Error :".$e->getMessage());
}
        function readRSSfromID($id) {
          require_once('RSS.class.php');
          try {
            $q = "SELECT * FROM RSS WHERE id=('$id') AND URL != ''";
            $r = $this->db->query($q);
            if ($r == NULL) {
              return(NULL);
            }
            $result=$r->fetchAll(PDO::FETCH_CLASS, "RSS");

            if($result != NULL) {
              foreach($result as $r) {
                return $r;
              }
            } else {
              return NULL;
            }

          } catch (PDOException $e) {
            die("PDO Error :".$e->getMessage());
          }
        }

                // Met à jour un flux
        function updateRSS(RSS $rss) {
                  // Met à jour uniquement le titre et la date{}
          $titre = $this->db->quote($rss->titre());
          $q = "UPDATE RSS SET titre=$titre, date='".$rss->date()."' WHERE url='".$rss->url()."'";
          try {
            $r = $this->db->exec($q);
            if ($r == 0) {
              die("updateRSS error: no rss updated\n");
            }
          } catch (PDOException $e) {
            die("PDO Error :".$e->getMessage());
          }
        } 
}

        // Met à jour un flux
function updateRSS(RSS $rss) {
          // Met à jour uniquement le titre et la date
 $titre = $this->db->quote($rss->titre());
 $q = "UPDATE RSS SET titre=$titre, date='".$rss->date()."' WHERE url='".$rss->url()."'";
 try {
  $r = $this->db->exec($q);
  if ($r == 0) {
   die("updateRSS error: no rss updated\n");
 }
} catch (PDOException $e) {
  die("PDO Error :".$e->getMessage());
}
} 

        //////////////////////////////////////////////////////////
        // Methodes CRUD sur Nouvelle
        //////////////////////////////////////////////////////////

        // Acces à une nouvelle à partir de son titre et l'ID du flux
        function readNouvellefromTitre($titre,$RSS_id) {
         try {
          $q = "SELECT * FROM nouvelle WHERE titre=('$titre') AND RSS_id=('$RSS_id')";
          $r = $this->db->exec($qu);
          if ($r == 0) {
           die("SelectNouvelle error: no nouvelle with this title or RSS_id \n");
         }
         return $r;
        } catch (PDOException $e) {
          die("PDO Error :".$e->getMessage());
        }
        }

        // Crée une nouvelle dans la base à partir d'un objet nouvelle
        // et de l'id du flux auquelle elle appartient
        function createNouvelle(Nouvelle $n, $RSS_id) {
         $nouvelle = $this->readNouvellefromTitre($n->titre(), $RSS_id);
         if ($nouvelle == NULL) {
          try {
           $date=$n->date();
           $titre=$n->titre();
           $desc=$n->description();
           $url=$n->link();
           $image=$n->image();

           $q = "INSERT INTO nouvelle(date,titre,description,url,image,RSS_id) VALUES ('$date','$titre','$desc','$url','$image')";
           $r = $this->db->exec($q);
           if ($r == 0) {
            die("createNouvelle error: no nouvelle inserted\n");
          }
          return $this->readNouvellefromTitre($n->titre(), $RSS_id);
        } catch (PDOException $e) {
         die("PDO Error :".$e->getMessage());
        }
        } else {
                    // Retourne l'objet existant
          return $nouvelle;
        }
        }

                // Met à jour le champ image de la nouvelle dans la base
        function updateImageNouvelle(Nouvelle $n) {
                  // Met à jour le titre et la date
         $titre = $this->db->quote($n->titre());
         $image =$n->image();
         $q = "UPDATE nouvelle SET titre=$titre,image=$image, date='".$n->date()."' WHERE url='".$n->url()."'";
         try {
          $r = $this->db->exec($q);
          if ($r == 0) {
           die("updateNouvelle error: no nouvelle updated\n");
         }
        } catch (PDOException $e) {
          die("PDO Error :".$e->getMessage());
        }
        }
        }
        ?>