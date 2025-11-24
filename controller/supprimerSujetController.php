<?php
include __DIR__ . "/crudSujet.php";

$id=$_GET['id'];
$position=$_GET['position'];
deletepost($id);
if($position=='front')
{
   header('Location:../view/frontoffice/forum.php');
}
else
{
 header('Location:../view/backoffice/build/pages/posts.php');
}





?>