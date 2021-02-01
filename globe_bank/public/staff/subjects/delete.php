<?php
require_once('../../../private/initialize.php');
require_login(); 
if(!isset($_GET['id'])){
  redirect_to(url_for('/staff/subjects/index.php'));
}

$id = $_GET['id'];

if (is_post_request()){

  delete_subject($id);
  $_SESSION['message']='The subject was deleted successfully.';
  redirect_to(url_for('/staff/subjects/index.php'));

}else{
  $subject=find_subject_by_id($id);
}


 ?>
 <?php $page_title="Delete Subject" ?>
 <?php include(SHARED_PATH . '/staff_header.php'); ?>
 <div id="content">
   <a class="back link" href="<?php echo url_for('/staff/subjects/index.php'); ?>"> &laquo; Back to List </a>

   <div class="subject delete">
     <h1>Delete Subject</h1>
     <p> Are you sure you want to delete this page? </p>
     <p class="item"><?php echo h($subject['menu_name']); ?></p>
     <form action="<?php echo url_for('/staff/subjects/delete.php?id='.h($subject['id'])) ?>" method="post">
       <div class="operations">
         <input type="submit" name="" value="Delete subject">

       </div>
     </form>



   </div>

 </div>
 <?php include(SHARED_PATH . '/staff_footer.php'); ?>
