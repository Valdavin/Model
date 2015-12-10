<?php 
class DAO {

        private $db; // L'objet de la base de donnée

        // Ouverture de la base de donnée
        function __construct() {
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=projet;charset=utf8', 'root','');
          } catch (PDOException $e) {
            exit("Erreur ouverture BD : ".$e->getMessage());
          }
        }

        //////////////////////////////////////////////////////////////
        //							USER 							//
        //////////////////////////////////////////////////////////////


        //////////////////////////////////////////////////////////////
        //							GROUPE 							//
        //////////////////////////////////////////////////////////////
        // Renvois un objet de type Groupe ayant l'ID rentré en paramètre
        function readGroupefromId($id_g) { 
          require_once('Groupe.class.php');
          $q = "SELECT * FROM groupe WHERE userid=$id_g";
          try {
            $r = $this->db->query($q); 
            $result=$r->fetchAll(PDO::FETCH_CLASS, "Groupe");
            if ($result) {
            	return $result[0];
            } else {
            	return NULL;
            }
           
          } catch (PDOException $e) {
            die("PDO Error :".$e->getMessage());
          }

        }

        //Renvois tout les groupe existants sous la forme d'un array de groupes
        function readAllGroupe() {
        	require_once('Groupe.class.php');
        	$q = "SELECT * FROM groupe";
        	try {
	            $r = $this->db->query($q); 
	            $result=$r->fetchAll(PDO::FETCH_CLASS, "Groupe");
	            return $result; // ATTENTION : Contrairement a readGroupefromId, il renvois un array et non pas un groupe
           
          	} catch (PDOException $e) {
            	die("PDO Error :".$e->getMessage());
          	}
        }


        //Créer dans la base de données le Groupe donné en paramètre
        private function createGroupe(Groupe $g, $id) {
          if ($g != NULL) {
            try {
              $nom = $g->nom();
              $nbPers = $g->nbPers();
              $style = $g->style();
              $numG = $g->numeroTel();
              $q = "INSERT INTO groupe VALUES ($id,'$nom','$nbPers','$style','$numG')";
              $r = $this->db->exec($q);
              printf(" l %d l ",$r);
              if ($r == 0) {
                die("createGroupe error: no group inserted\n");
              }
            } catch (PDOException $e) {
              die("PDO Error :".$e->getMessage());
            }
          }
        }

        function newUserGroupe ($password,$pseudo,$nom,$facebook,$tweeter,$email,$numeroTel,$nbPers,$style,$soundcloud) {
        	require_once('Groupe.class.php');
        	if (!$password||!$pseudo||!$nom||!$email) {
        		die("newUserGroupe error: parameters missing\n");
        	}
        	try {
	        	$g = new Groupe();
	        	$quser = "INSERT INTO user VALUES ('','$password','$pseudo','groupe','$nom','$facebook','$tweeter','$email','$numeroTel')";
	        	var_dump($quser);
	        	$qnbr = "SELECT max(id) FROM user";
	        	$ruser = $this->db->exec($quser);
	        	if ($ruser == 0) {
	                die("newUserGroupe error: no user inserted\n");
	            } else {
	              	$rnbr = $this->db->query($qnbr);
	              	$nbr = $rnbr->fetch();
	              	$qgroupe = "INSERT INTO groupe VALUES ($nbr[0],$nbPers,'$style')";
	              	$rgroupe = $this->db->exec($qgroupe);
	              	if ($rgroupe == 0) {
	              		$q = "TRUNCATE TABLE user";
	              		$this->db->exec($q);
	                	die("newUserGroupe error: no groupe inserted\n");
	              	}
	            }
        	} catch (PDOException $e) {
        		die("PDO Error :".$e->getMessage());
        	}
        }



        //////////////////////////////////////////////////////////////
        //							LIEU 							//
        //////////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////////
        //							BOOKER 							//
        //////////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////////
        //							RESPLIEU						//
        //////////////////////////////////////////////////////////////
       

}       

        ?>