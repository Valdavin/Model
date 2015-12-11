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
        private function createUser($password,$pseudo,$groupe,$nom,$facebook,$tweeter,$email,$numeroTel) {
        	$quser = "INSERT INTO user VALUES ('','$password','$pseudo','groupe','$nom','$facebook','$tweeter','$email','$numeroTel')";
        	$qnbr = "SELECT max(id) FROM user";
	        $ruser = $this->db->exec($quser);
	        $rnbr = $this->db->query($qnbr);
	        $nbr = $rnbr->fetch();
	        if ($ruser == 0) {
	        	return 0;
	        } else {
	        	return $nbr[0];
	        }

        }

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
              $nbPers = $g->nbPers();
              $style = $g->style();
              $q = "INSERT INTO groupe VALUES ($id,'$nbPers','$style')";
              $r = $this->db->exec($q);
              if ($r == 0) {
                return false; // Il y a un problème
              } else {
              	return true; // Tous s'est bien passé
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
	        	$ruser = $this->createUser($password,$pseudo,'groupe',$nom,$facebook,$tweeter,$email,$numeroTel);
	        	if ($ruser == 0) {
	                die("newUserGroupe error: no user inserted\n");
	            } else {
	              	$g->createGroupe($style,$nbPers);
	              	if (!$this->createGroupe($g,$ruser)) {
	              		$q = "DELETE user WHERE id=$ruser";
	              		var_dump($q);
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

        private function createLieu($id, $nom, $ville, $codePoste, $adresse, $lieudit, $nbPlace, $facebook, $tweeter) {
          if (!$id || !$nom || !$ville || !$codePoste || !$adresse || !$nbPlace ) {
            try {
              $q = "INSERT INTO lieu VALUES ($id, '$nom', '$ville', $codePoste, '$adresse', '$lieudit', $nbPlace, '$facebook', '$tweeter')";
              $r = $this->db->exec($q);
              if (!$r) {
                die("createLieu error : no lieu inserted");
              } 
            } catch (PDOException $e) {
              die("PDO Error :".$e->getMessage());
            }
          } else {
          	die("createLieu error : arguments missing");
          }
        } 

        //////////////////////////////////////////////////////////////
        //							BOOKER 							//
        //////////////////////////////////////////////////////////////


        //Créer dans la base de données le Groupe donné en paramètre
        private function createBooker(Booker $b, $id) {
          if ($b != NULL) {
            try {
              $prenom = $b->prenom();
              $q = "INSERT INTO booker VALUES ($id,'$prenom')";
              $r = $this->db->exec($q);
              if ($r == 0) {
                return false; // Il y a un problème
              } else {
              	return true; // Tous s'est bien passé
              }
            } catch (PDOException $e) {
              die("PDO Error :".$e->getMessage());
            }
          }
        }

        function newUserBooker ($password,$pseudo,$nom,$facebook,$tweeter,$email,$numeroTel,$prenom) {
        	require_once('Booker.class.php');
        	if (!$password||!$pseudo||!$nom||!$email) {
        		die("newUserBooker error: parameters missing\n");
        	}
        	try {
	        	$b = new Booker();
	        	$quser = "INSERT INTO user VALUES ('','$password','$pseudo','groupe','$nom','$facebook','$tweeter','$email','$numeroTel')";
	        	$ruser = $this->createUser($password,$pseudo,'booker',$nom,$facebook,$tweeter,$email,$numeroTel);
	        	if ($ruser == 0) {
	                die("newUserBooker error: no user inserted\n");
	            } else {
	              	$b->createBooker($prenom);
	              	if (!$this->createBooker($b,$ruser)) {
	              		$q = "DELETE FROM user WHERE id=$ruser";
	              		$this->db->exec($q);
	                	die("newUserBooker error: no booker inserted\n");
	              	}
	            }
        	} catch (PDOException $e) {
        		die("PDO Error :".$e->getMessage());
        	}
        }
        //////////////////////////////////////////////////////////////
        //							RESPLIEU						//
        //////////////////////////////////////////////////////////////
        private function createResplieu(Resplieu $r, $id) {
          if ($r != NULL) {
            try {
              $prenom = $r->prenom();
              $q = "INSERT INTO resplieu VALUES ($id,'$prenom')";
              $r = $this->db->exec($q);
              if ($r == 0) {
                return false; // Il y a un problème
              } else {
              	return true; // Tous s'est bien passé
              }
            } catch (PDOException $e) {
              die("PDO Error :".$e->getMessage());
            }
          }
        }

        function newUserResplieu ($password,$pseudo,$nom,$facebook,$tweeter,$email,$numeroTel,$prenom) {
        	require_once('Resplieu.class.php');
        	if (!$password||!$pseudo||!$nom||!$email) {
        		die("newUserResplieu error: parameters missing\n");
        	}
        	try {
	        	$r = new Resplieu();
	        	$quser = "INSERT INTO user VALUES ('','$password','$pseudo','groupe','$nom','$facebook','$tweeter','$email','$numeroTel')";
	        	$ruser = $this->createUser($password,$pseudo,'resplieu',$nom,$facebook,$tweeter,$email,$numeroTel);
	        	if ($ruser == 0) {
	                die("newUserResplieu error: no user inserted\n");
	            } else {
	              	$r->createResplieu($prenom);
	              	if (!$this->createResplieu($r,$ruser)) {
	              		$q = "DELETE FROM user WHERE id=$ruser";
	              		$this->db->exec($q);
	                	die("newUserResplieu error: no resplieu inserted\n");
	              	}
	            }
        	} catch (PDOException $e) {
        		die("PDO Error :".$e->getMessage());
        	}
        }    
           
        //////////////////////////////////////////////////////////////
        //							JOUE							//
        //////////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////////
        //						APPARTIENT							//
        //////////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////////
        //							OUVRIR							//
        //////////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////////
        //							SOCCUPE							//
        //////////////////////////////////////////////////////////////
}       

        ?>