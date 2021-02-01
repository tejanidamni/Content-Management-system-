<?php require_once('../../../private/initialize.php') ;?>

<?php require_login();  ?>
<?php
  if (is_post_request()){

    $page=[];

    $page['menu_name']= $_POST['menu_name']?? '';
    $page['subject_id']=$_POST['subject_id']?? '';
    $page['position']=$_POST['position']?? '';
    $page['visible']=$_POST['visible']?? '';
    $page['content']=$_POST['content']?? '';



    $result = insert_page($page);
    if($result===true){
      $new_id = mysqli_insert_id($db);
      $_SESSION['message']='The subject was created successfully.';
      redirect_to(url_for('/staff/pages/show.php?id='. $new_id));
    }else{
      $errors = $result;
    }



  }else{

    $page=[];
    $page['menu_name']= '';
    $page['subject_id']=$_GET['subject_id']?? '1';
    $page['position']='';
    $page['visible']= '';
    $page['content']='';


  }

  $pages_count=count_pages_by_subject_id($page['subject_id'])+1;

 ?>


<?php $page_title = "Create Page"; ?>
<?php include(SHARED_PATH . '/staff_header.php') ;?>



<div id="content">
  <a class="back-list" href="<?php echo url_for('/staff/subjects/show.php?id='. h(u($page['subject_id'])));?>">&laquo; Back to Subject page</a>
  <div class="subject new">
    <h1>Create Page</h1>

    <?php echo display_errors($errors); ?>
    <form action="<?php echo url_for('/staff/pages/new.php'); ?>" method="post">
      <dl >
        <dt>Menu Name</dt>
        <dd><input type="text" name="menu_name" value="<?php echo $page['menu_name']; ?>"/></dd>

      </dl>
      <dl >
        <dt>Subject</dt>
        <dd>
          <select name="subject id">
            <?php
            $subject_set=find_all_subjects();
            while($subjects=mysqli_fetch_assoc($subject_set)){
              echo "<option value = '". h($subjects['id']). "'";
              if ($page['subject_id']==$subjects['id']){
                echo " selected";
              }
              echo ">".h($subjects['menu_name'])."</option>";

            }
            mysqli_free_result($subject_set);

            ?>


          </select>
        </dd>

      </dl>
      <dl >
        <dt>Position</dt>
        <dd>
          <select name="position">

            <?php
            for ($i=1;$i<$pages_count;$i++){
              echo "<option value={$i}";
              if ($page['position']==$i){
                echo " selected";
              }
              echo " > {$i} </option>";

            }
            ?>


          </select>
        </dd>

      </dl>
      <dl >
        <dt>visible</dt>
        <dd>
          <input type="hidden" name="visible" value="0">
          <input type="checkbox" name="visible" value="1" <?php if ($page['visible']=='1'){echo "checked";} ?>>

        </dd>

      </dl>
      <dl>
        <dt>Content</dt>
        <dd><textarea name="content" ><?php echo $page['content']; ?></textarea></dd>

      </dl>
      <div class="operations">
        <input type="submit" value="Create Page">
      </div>


    </form>

  </div>


</div>

<?php include(SHARED_PATH . '/staff_footer.php') ;?>
