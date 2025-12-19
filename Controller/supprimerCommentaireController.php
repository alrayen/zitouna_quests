<?php
include __DIR__ . "/crudCommentaire.php";

$id=$_GET['id'];
$position=$_GET['position'];

deleteCommentaires($id);
if($position=='front')
{
   header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/forum.php?success=comment_deleted');
}
else
{
 header('Location: ../View/BACK OFFICE/VIEW/build/pages/posts.php');
}





?>