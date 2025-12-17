<?php
include __DIR__ . "/crudSujet.php";

$id=$_GET['id'];
$position=$_GET['position'];
deletepost($id);
if($position=='front')
{
   header('Location:../View/FRONT OFFICE/forum.php?success=post_deleted');
}
else
{
 header('Location:../View/BACK OFFICE/build/pages/posts.php');
}





?>