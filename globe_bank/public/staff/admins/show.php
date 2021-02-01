<?php require_once('../../../private/initialize.php'); ?>
<?php require_login(); ?> 

<?php $page_title="Show Page"; ?>
<?php include(SHARED_PATH.'/staff_header.php'); ?>

<?php

$id=$_GET['id']?? 1;
$admin = find_admin_by_id($id);

?>
<div id="content">
  <a class="back-list" href="<?php echo url_for('/staff/admins/index.php'); ?>">&laquo; Back to list</a>
  <div class="Page Show">
    <h1>Admin: <?php echo h($admin['first_name']); ?></h1>
    <div class="attributes">
      <dl>
        <dt>First Name</dt>
        <dd><?php echo h($admin['first_name']); ?></dd>

      </dl>

      <dl>
        <dt>Last Name</dt>
        <dd><?php echo h($admin['last_name']); ?></dd>

      </dl>

      <dl>
        <dt>Username</dt>
        <dd><?php echo h($admin['username']); ?></dd>

      </dl>

      <dl>
        <dt>Email</dt>
        <dd><?php echo h($admin['email']); ?></dd>

      </dl>

    </div>

  </div>

</div>

<?php include(SHARED_PATH.'/staff_footer.php'); ?>
