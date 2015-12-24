<!doctype html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<?php 
        // Test de la classe DAO
        require_once('DAO.class.php');
        require_once('Groupe.class.php');

        $dao=new DAO();
        //$dao->newUserGroupe('1234','Diwali','Valendtin','azeazeazeFacebook','lolilocnhe','lolijlol@hotmail.fr','0670551260',5,'Metal','lolilolSoundcloud');
        //$dao->newUserGroupe('1234','Diwazerli','Vaelentin','azeazeazeFacebook','lolilocnhe','lolilohl@hotmail.fr','0270551260',5,'Metal','lolilolSoundcloud');
        //$dao->newUserGroupe('1234','Dizerwali','Vadlentin','azeazeazeFacebook','lolilocnhe','lolilol@hogtmail.fr','0620551260',5,'Metal','lolilolSoundcloud');
        //$dao->newUserGroupe('1234','Dizerzerwali','Valerntin','azeazeazeFacebook','lolilocnhe','lolilol@hodtmail.fr','0670251230',5,'Metal','lolilolSoundcloud');
        //$dao->newUserGroupe('1234','Diwrtrtali','Valtentgin','azeazeazeFacebook','lolilocnhe','loleilol@hotmail.fr','0370552260',5,'Metal','lolilolSoundcloud');
        //$dao->newUserGroupe('1234','Diwzzrali','Valehntin','azeazeazeFacebook','lolilocnhe','lolilrol@hotmail.fr','0670553260',5,'Metal','lolilolSoundcloud');
        $dao->newUserGroupe('1234','Diryhali','Valenztin','azeazeazeFacebook','lolilocnhe','lolilol@hotmrail.fr','0670551363',5,'Metal','lolilolSoundcloud');
        //($password,$pseudo,$nom,$facebook,$tweeter,$email,$numeroTel,$nbPers,$style,$soundcloud)
        

        $dao->newUserBooker('1234','Diwrtrtali','Valtentgin','azeazeazeFacebook','lolilocnhe','loleilol@hotmail.fr','0370552260','Paul');
        
        $dao->newUserResplieu('1234','Diwrdtrtali','Valtefntgin','azeazeazeFacebook','lolilocnhe','loleilol@hotmail.fr','0370552260','Paul');

?>
</head>
</html>