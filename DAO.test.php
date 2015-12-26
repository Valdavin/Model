<!doctype html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<?php 
        // Test de la classe DAO
        require_once('DAO.class.php');
        require_once('Groupe.class.php');

        $dao=new DAO();

        $dao->deleteUser(2);
        /*

        $dao->newUserGroupe('motdepass','TestPseudoGroupe','Garou','https://www.facebook.com/Garou.official/?ref=ts&fref=ts','https://twitter.com/Garou_officiel','garou@hotmrail.fr','0670551363',5,'Metal','GarouSoundcloud');
        $dao->newUserBooker('motdepass','TestPseudoBooker','Boucoeur','FacebookBooker','FacebookBooker','booker@hotmail.fr','0370552260','Gérard');
        $dao->newUserResplieu('motdepass','TestPseudoResplieu','LaSaumure','FacebookResplieu','FacebookResplieu','resplieu@hotmail.fr','0370552260','Dodo');

        if ($dao->existe(1)) {
                echo ("OK");
        } else {
                echo ("PAS OK");
        }

        if($dao->connexion('Diryhali','1234')) {
                echo ("OK");
        } else {
                echo ("PAS OK");
        }
        */

?>
</head>
</html>