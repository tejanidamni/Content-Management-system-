<?php require_once('../../../private/initialize.php'); ?>
<?php require_login(); ?> 

<?php
if (!isset($_GET['id'])){
  redirect_to(url_for('/staff/admins/index.php'));
}
$id= $_GET['id'];

if (is_post_request()) {
  delete_admin($id);
  $_SESSION['message']= "The admin was deleted successfully";
  redirect_to(url_for('/staff/admins/index.php'));


}else{
  $admin=find_admin_by_id($id);
}


 ?>
<?php $page_title="Delete Page" ;?>
<?php include(SHARED_PATH.'/staff_header.php'); ?>

<div id="content">
  <a class="back-link" href="<?php echo url_for('/staff/admins/index.php') ;?>">&laquo; Back to List</a>
  <div class="Admin Delete">
    <h1>Delete Admin</h1>
    <p>Are you sure you want to delete this page?</p>

    <?php echo $admin['first_name']; ?>



    <form action="<?php echo url_for('/staff/admins/delete.php?id='.h($admin['id'])); ?>" method = "post" >
      <br>
      <div class="operations">
        <input type="submit" name="" value="Delete Admin">

      </div>

    </form>

  </div>


</div>

<?php include(SHARED_PATH.'/staff_footer.php'); ?>
