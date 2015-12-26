<?php 
class DAO {

  private $db; // L'objet de la base de donnée

  // Ouverture de la base de donnée
  function __construct() {
  try {
  		// IMPORTANT //
  		// A CHANGER SI ON EST EN LOCAL OU PAS

  		// LOCAL (Valentin)
  		$this->db = new PDO('mysql:host=localhost;dbname=2v5m01ch;charset=utf8', 'root','');

  		// SERVEUR
      	//$this->db = new PDO('mysql:host=sql2.olympe.in;dbname=2v5m01ch;charset=utf8', '2v5m01ch', 'banana:)', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) {
      exit("Erreur ouverture BD : ".$e->getMessage());
    }
  }
  //////////////////////////////////////////////////////////////
  //              REQUETE                                     //
  //////////////////////////////////////////////////////////////

  public function exec_req($req)
  {
    try {
      $res = $this->db->query($req);
    } catch (Exception $e) {
      $res = "PDO Error : ".$e->getMessage();
    }
    return $res;
  }

  //////////////////////////////////////////////////////////////
  //							USER 						                          	//
  //////////////////////////////////////////////////////////////
  /*
	  Retourne l'id de l'user si il arrive a le créé
	  Sinon retourne 0
  */
  private function createUser($password,$pseudo,$type,$nom,$facebook,$tweeter,$email,$numeroTel) {
  	$quser = "INSERT INTO user VALUES ('','$password','$pseudo','$type','$nom','$facebook','$tweeter','$email','$numeroTel','0')";
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
  /*
  	Supprime l'user possèdant cette id
  	Renvois 1 si réussi
  	Sinon renvois 0
  */
  	public function deleteUser($id) {
  		if ($this->existe($id)) {
  			// A FINIR
  			// TROUVER LE TYPE, SUPRIMER DANS LE TYPE (BOOKER, GROUPE ..)

  			$type = $this->db->query("SELECT type FROM user WHERE id=$id")->fetch(); // On récupère le type de l'user
  			switch ($type) {
  				case "booker":
  					$this->deleteBooker($id);
  					break;
  				case "resplieu":
  					$this->deleteResplieu($id);
  					break;
  				case "groupe":
  					$this->deleteGroupe($id);
  					break;
  				default:
  					die("erreur deleteUser __LINE__ : type de l'user non reconnu.");
  					break; // innutile
  			}


	  		try {
	      		$this->db->exec("DELETE FROM user WHERE id=$id");
	   		} catch (PDOException $e) {
	    	 	die("PDO Error :".$e->getMessage());
	    	}


    	}
  	}

  /*
	Retourne l'id de l'user ayant le pseudo $pseudo
	Sinon retourne 0
  */
  public function getIdFromPseudo($pseudo) {
  	try {
      $numid = $this->db->query("SELECT id FROM user WHERE pseudo=$pseudo")->fetch();
      if ($result) {
      	return $result[0];
      } else {
      	return 0;
      }
    } catch (PDOException $e) {
      die("PDO Error :".$e->getMessage());
    }
  }


  /*
	Retourne 1 si l'id existe
	Sinon retourne 0
  */
  public function existe($id) {
  	$q = "SELECT * FROM user WHERE id=$id";
  	
  	try {
      $result = $this->db->query($q)->fetch();
      if ($result) {
      	return 1;
      } else {
      	return 0;
      }
    } catch (PDOException $e) {
      die("PDO Error :".$e->getMessage());
    }

  }


  /*
  	Retourne 1 si un user ayant ce pseudo et ce password existe
  	Sinon retourne 0
  */
  public function connexion($pseudo,$password) {
  	$q = "SELECT * FROM user WHERE pseudo='$pseudo' AND password='$password'";
  	var_dump($q);
  	try {
      $result = $this->db->query($q);
      if ($result) {
      	return 1;
      } else {
      	return 0;
      }
    } catch (PDOException $e) {
      die("PDO Error :".$e->getMessage());
    }
  }

  //////////////////////////////////////////////////////////////
  //							GROUPE 							                        //
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
  //							LIEU 							                          //
  //////////////////////////////////////////////////////////////

  private function createLieu($nom, $ville, $codePoste, $adresse, $lieudit, $nbPlace, $facebook, $tweeter) {
    if (!$id || !$nom || !$ville || !$codePoste || !$adresse || !$nbPlace ) {
      try {
        $q = "INSERT INTO lieu VALUES ('', '$nom', '$ville', $codePoste, '$adresse', '$lieudit', $nbPlace, '$facebook', '$tweeter')";
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
  //							BOOKER 						                        	//
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

  private function deleteBooker($id) {
  	$this->deleteSoccupeFromBooker($id);
  	$this->db->exec("DELETE FROM booker WHERE userid=$id");
  }
  //////////////////////////////////////////////////////////////
  //							RESPLIEU						                        //
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
  //							JOUE							                          //
  //////////////////////////////////////////////////////////////

  //////////////////////////////////////////////////////////////
  //						APPARTIENT							                      //
  //////////////////////////////////////////////////////////////

  //////////////////////////////////////////////////////////////
  //							OUVRIR							                        //
  //////////////////////////////////////////////////////////////

  //////////////////////////////////////////////////////////////
  //							SOCCUPE							                        //
  //////////////////////////////////////////////////////////////
  function createSoccupe($idgroupe, $idbook, $numcontrat) {
  	try (
  		if (!$this->db->query("SELECT * FROM soccupe where groupeuserid = $idgroupe AND bookeruserid = $idbook")->fetch()) { // Si la relation n'existe pas déjà
  			if (!$this->db->query("SELECT * FROM groupe where userid = $idgroupe ")->fetch()) die("createSoccupe error __LINE__ : le groupe n'existe pas");
  			if (!$this->db->query("SELECT * FROM booker where userid = $idbook ")->fetch()) die("createSoccupe error __LINE__ : le booker n'existe pas");
  			$this->db->exec("INSERT INTO soccupe VALUES ($idgroupe, $idbook, $numcontrat)");
  		} else {
  			die("createSoccupe error __LINE__ : la relation existe déjà");
  		}
  	) catch (PDOException $e) {
  		die("PDO Error :".$e->getMessage());
  	}
  	
  }
  function deleteSoccupeFromBooker($id) {
  	try (
  		$this->db->exec("DELETE FROM soccupe WHERE bookeruserid=$id");
  	catch (PDOException $e) {
  		die("PDO Error :".$e->getMessage());
  	}
  }
}
// TODO : FINIR DELETEUSER
?>

