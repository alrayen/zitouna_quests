<?php

include __DIR__ . "/crudSujet.php";
include_once __DIR__ . "/../model/sujets.php";
$nom= $_POST["nom"];
/*var_dump($nom);
die;*/
$date=date("Y-m-d");
$titre='dfghj';
$image='ghjh';
$sujet= new Sujet($nom,$date,$titre,$image);

/*var_dump($sujet->getNom());
die;*/
createSujet($sujet);
$position=$_GET["position"];
if($position=='front')
{
     header('Location:../view/frontoffice/forum.php');
}
else
{
     header('Location:../view/backoffice/build/pages/posts.php');
}
?>