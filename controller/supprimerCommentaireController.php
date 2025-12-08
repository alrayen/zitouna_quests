<?php
include __DIR__ . "/crudCommentaire.php";

$id=$_GET['id'];
$position=$_GET['position'];

deleteCommentaires($id);
if($position=='front')
{
   header('Location:../view/frontoffice/forum.php?success=comment_deleted');
}
else
{
 header('Location:../view/backoffice/build/pages/posts.php');
}





?>