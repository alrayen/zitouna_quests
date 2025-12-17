<?php
include __DIR__ . "/crudCommentaire.php";

$id=$_GET['id'];
$position=$_GET['position'];

deleteCommentaires($id);
if($position=='front')
{
   header('Location:../View/FRONT OFFICE/forum.php?success=comment_deleted');
}
else
{
 header('Location:../View/BACK OFFICE/build/pages/posts.php');
}





?>