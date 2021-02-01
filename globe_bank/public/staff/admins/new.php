<?php require_once('../../../private/initialize.php'); ?>
<?php require_login(); ?>

<?php
if (is_post_request()){
  $admin=[];
  $admin['first_name']=$_POST['first_name']??'';
  $admin['last_name']=$_POST['last_name']??'';
  $admin['username']=$_POST['username']??'';
  $admin['email']=$_POST['email']??'';
  $admin['password']=$_POST['password']??'';
  $admin['confirm_password']=$_POST['confirm_password']??'';

  $result= insert_admin($admin);
  if ($result===true){
    $_SESSION['message']='The admin was inserted successfully.';
    $new_id=mysqli_insert_id($db);
    redirect_to(url_for('/staff/admins/show.php?id='. $new_id));
  }else{
    $errors=$result;
  }

}else{

  $admin=[];
  $admin['first_name']='';
  $admin['last_name']='';
  $admin['username']='';
  $admin['email']='';
  $admin['password']='';
  $admin['confirm_password']='';

}

 ?>


<?php $page_title='Create Admin' ;?>
<?php include (SHARED_PATH.'/staff_header.php'); ?>


<div id="content">
  <a class="back-link" href="<?php echo url_for('staff/admins/index.php') ;?>">&laquo; Back to list</a>
  <div class="New Admin">
    <h1>Create Admin</h1>
    <?php echo display_errors($errors); ?>
    <form action="<?php echo url_for('/staff/admins/new.php') ;?>" method="post">
      <dl>
        <dt>First Name</dt>
        <dd><input type="text" name="first_name" value="<?php echo h($admin['first_name']); ?>"></dd>

      </dl>
      <dl>
        <dt>Last Name</dt>
        <dd><input type="text" name="last_name" value="<?php echo h($admin['last_name']); ?>"></dd>

      </dl>
      <dl>
        <dt>Username</dt>
        <dd><input type="text" name="username" value="<?php echo h($admin['username']); ?>"></dd>

      </dl>
      <dl>
        <dt>Email</dt>
        <dd><input type="email" name="email" value="<?php echo h($admin['email']); ?>"></dd>

      </dl>
      <dl>
        <dt>Password</dt>
        <dd><input type="password" name="password" value="<?php echo h($admin['password']); ?>"></dd>

      </dl>
      <dl>
        <dt>Confirm Password</dt>
        <dd><input type="password" name="confirm_password" value="<?php echo h($admin['confirm_password']); ?>"></dd>

      </dl>

      <p>Passwords should be atleast 12 characters and include atleast one uppercase, one lowercase, number and symbol.</p>

      <div class="operations">
        <input type="submit" value="Create Admin">
      </div>

    </form>

  </div>

</div>



<?php include (SHARED_PATH.'/staff_footer.php'); ?>
