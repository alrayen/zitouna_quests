<?php
include __DIR__ . "/crudSujet.php";

$id=$_GET['id'];
$position=$_GET['position'];
deletepost($id);
if($position=='front')
{
   header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/forum.php?success=post_deleted');
}
else
{
 header('Location: ../View/BACK OFFICE/VIEW/build/pages/posts.php');
}





?>