<?php
//subjects function

function find_all_subjects($options=[]){

  global $db;
  $visible = $options['visible']?? false;
  $sql = "SELECT * FROM subjects ";
  if ($visible){
    $sql .= "WHERE visible = true ";
  }
  $sql.= "ORDER BY position ASC";
  $result= mysqli_query($db, $sql);
  confirm_result_set($result);
  return $result;

}

function find_subject_by_id($id,$options=[]){
  global $db;
  $visible = $options['visible']?? false;
  $sql="SELECT * FROM subjects ";
  $sql .= "WHERE id='" . db_escape($db,$id) . "' ";
  if ($visible){
    $sql .= "AND visible = true ";
  }
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  $subject = mysqli_fetch_assoc($result);
  mysqli_free_result($result);
  return $subject;

}

function validate_subject($subject){

  $errors=[];

  //menu name
  if(is_blank($subject['menu_name'])){
    $errors[]="Name cannot be blank ";
  }elseif (!has_length($subject['menu_name'],['min' => 2 ,'max' => 255])){
    $errors[]="Name must be between 2 and 255 characters.";
  }


  //position

  $position_int=(int) $subject['position'];
  if ($position_int <= 0){
    $errors[]="position must be greater than 0";
  }elseif($position_int > 999){
    $errors[]="position must be less than 1000";
  }

  //Visible
  $visible_str=(string) $subject['visible'];
  if(!has_inclusion_of($visible_str,["0","1"])){
    $errors[]="visible must be true or false";
  }

  return $errors;


}

function insert_subject($subject){
  global $db;

  $errors= validate_subject($subject);
  if(!empty($errors)){
    return $errors;
  }

  shift_subject_positions(0, $subject['position']);


  $sql="INSERT INTO subjects ";
  $sql.="(menu_name,position,visible) ";
  $sql.="VALUES( ";
  $sql.= "'" . db_escape($db,$subject['menu_name'] ). "'," ;
  $sql.= "'" . db_escape($db,$subject['position']) . "'," ;
  $sql.= "'" . db_escape($db,$subject['visible']) . "'";
  $sql.=")";

  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  //for insert, the result is either true or false.
  if ($result){
    return true;
  } else{
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }

}

function update_subject($subject){

  global $db;

  $errors = validate_subject($subject);
  if(!empty($errors)){
    return $errors;
  }
  $old_subject = find_subject_by_id($subject['id']);
  $old_position = $old_subject['position'];
  if ($old_position!=$subject['position']){
    shift_subject_positions($old_position, $subject['position'],$subject['id']);
  }


  $sql = "UPDATE subjects SET ";
  $sql.="menu_name='".db_escape($db,$subject['menu_name'])."',";
  $sql.="position='".db_escape($db,$subject['position'])."',";
  $sql.="visible='".db_escape($db,$subject['visible'])."' ";
  $sql.="WHERE id='".db_escape($db,$subject['id'])."' ";
  $sql.="LIMIT 1";

  $result = mysqli_query($db, $sql);

  //for update, the result is either true or false.
  if ($result){
    return true;
  } else{
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}

function delete_subject($id){
  global $db;

  $old_subject=find_subject_by_id($id);
  $old_position=$old_subject['position'];
  shift_subject_positions($old_position,0,$id);


  $sql = "DELETE FROM subjects ";
  $sql .= "WHERE id='".db_escape($db,$id)."' ";
  $sql .= "LIMIT 1";

  $result = mysqli_query ($db , $sql) ;

  //FOR DELETE STATEMENTS, RESULT IS TRUE OR FALSE
  if ($result){
    return true;

  }else{
    echo mysqli_error();
    db_disconnect($db);
    exit();
  }
}

//pages function

function validate_page($page){
  $errors=[];


  //menu name
  if(is_blank($page['menu_name'])){
    $errors[]="Name cannot be blank";
  }elseif (!has_length($page['menu_name'],['min'=>2,'max' =>255])){
    $errors[]="Name must be between 2 and 255 characters";
  }
  $current_id = $page['id']??'0';



  if(!has_unique_page_menu_name($page['menu_name'],$current_id)){
    $errors[]="Menu_name must be unique";
  }

  //subject id.
  if (is_blank($page['subject_id'])){
    $errors[]="Subject id cannot be blank";
  }

  //position
  $position_int=(int) $page['position'];
  if ($position_int<=0){
    $errors[]="Position must be graeter than 0";
  } elseif($position_int>999) {
    $errors[]="Position must be less than 1000";
  }

  // visible
  $visible_str = (string) $page['visible'];
  if (!has_inclusion_of($visible_str,["0","1"])){
    $errors[] = "visible must be true or false";
  }

  //content
  if(is_blank($page['content'])){
    $errors[]="content cannot be blank";
  }
  return $errors;
}

function find_all_pages(){
  global $db;
  $sql = "SELECT * FROM pages ";
  $sql.= "ORDER BY subject_id, position ASC";
  $page_set= mysqli_query($db, $sql);
  confirm_result_set($page_set);
  return $page_set;

}

function find_page_by_id($id, $options=[]){
  global $db;
  $visible = $options['visible']?? false;
  $sql="SELECT * FROM pages ";
  $sql.="WHERE id='".db_escape($db,$id). "' ";
  if ($visible){
    $sql.="AND visible = true ";
  }
  $result_set = mysqli_query($db, $sql);
  confirm_result_set($result_set);
  $page=mysqli_fetch_assoc($result_set);
  mysqli_free_result($result_set);
  return $page;
}

function insert_page($page){

  global $db;
  $errors = validate_page($page);
  if(!empty($errors)){
    return $errors;
  }

  $sql = "INSERT INTO pages ";
  $sql.="(menu_name,subject_id,position,visible,content) ";
  $sql.="VALUES( ";
  $sql.= "'" . db_escape($db,$page['menu_name']) . "',";
  $sql.= "'" . db_escape($db,$page['subject_id']) . "',";
  $sql.= "'" . db_escape($db,$page['position']) . "',";
  $sql.= "'" . db_escape($db,$page['visible']) . "',";
  $sql.= "'" . db_escape($db,$page['id']) . "')";

  $result = mysqli_query($db, $sql);


  if ($result){
    return true;
  } else{
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }

}

function update_page($page){

  global $db;

  $errors= validate_page($page);
  if(!empty($errors)){
    return $errors;
  }

  $sql="UPDATE pages SET ";
  $sql.="menu_name='".db_escape($db,$page['menu_name']) . "', ";
  $sql.="subject_id='".db_escape($db,$page['subject_id']) . "', ";
  $sql.="position='".db_escape($db,$page['position']) . "', ";
  $sql.="visible='".db_escape($db,$page['visible']) . "', ";
  $sql.="content='".db_escape($db,$page['content']) . "' ";
  $sql.="WHERE id='".db_escape($db,$page['id']) . "' ";
  $sql.="LIMIT 1";

  echo $sql;

  $result = mysqli_query($db, $sql);

  if($result){
    return true;
  }else{
    echo mysqli_error();
    db_disconnect($db);
    exit();
  }
}

function delete_page($id){
  global $db;

  $sql = "DELETE FROM pages ";
  $sql .= "WHERE id='".db_escape($db,$id)."' ";
  $sql .= "LIMIT 1";

  $result = mysqli_query ($db , $sql) ;

  //FOR DELETE STATEMENTS, RESULT IS TRUE OR FALSE
  if ($result){
    return true;

  }else{
    echo mysqli_error();
    db_disconnect($db);
    exit();
  }

}

function find_pages_by_subject_id($subject_id,$options=[]){
  global $db;

  $sql = "SELECT * FROM pages ";
  $visible = $options['visible']?? false;
  $sql.= "WHERE subject_id='". db_escape($db,$subject_id). "' ";
  if ($visible){
    $sql .= "AND visible = true ";
  }
  $sql.= "ORDER BY position ASC";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  return $result;
}

function count_pages_by_subject_id($subject_id,$options=[]){
  global $db;

  $sql = "SELECT COUNT(id) FROM pages ";
  $visible = $options['visible']?? false;
  $sql.= "WHERE subject_id='". db_escape($db,$subject_id). "' ";
  if ($visible){
    $sql .= "AND visible = true ";
  }
  $sql.= "ORDER BY position ASC";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  $row=mysqli_fetch_row($result);
  mysqli_free_result($result);
  $count=$row[0];
  return $count;
}

// admins functions.

function find_all_admins(){
  global $db;
  $sql="SELECT * FROM admins";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  return $result;
}

function insert_admin($admin){
  global $db;
  $errors = validate_admin($admin);
  if (!empty($errors)){
    return $errors;
  }

  $admin['hashed_password'] = password_hash($admin['password'],PASSWORD_BCRYPT);
  $sql="INSERT INTO admins ";
  $sql.="(first_name,last_name,username,email,hashed_password) ";
  $sql.="VALUES(";
  $sql.= "'" . db_escape($db, $admin['first_name']) . "',";
  $sql.= "'" . db_escape($db, $admin['last_name']) . "',";
  $sql.= "'" . db_escape($db, $admin['username']) . "',";
  $sql.= "'" . db_escape($db, $admin['email']) . "',";
  $sql.= "'" . db_escape($db, $admin['hashed_password']) . "')";

  $result=mysqli_query($db,$sql);
  if ($result){
    return true;
  } else{
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }

}

function validate_admin($admin,$options=[]){
  $errors=[];
  $password_required = $options['password_required']?? true;

  if (is_blank($admin['first_name'])){
    $errors[]="Name cannot be blank";
  }elseif (!has_length($admin['first_name'],['min'=>2, 'max'=>255])){
    $errors[]="Name must be between 2 and 255 characters";
  }

  if (is_blank($admin['last_name'])){
    $errors[]="Last Name cannot be blank";
  }elseif (!has_length($admin['last_name'],['min'=>2, 'max'=>255])){
    $errors[]="Name must be between 2 and 255 characters";
  }

  if (is_blank($admin['email'])){
    $errors[]="Email cannot be blank";
  }elseif (!has_length_less_than($admin['email'],255)){
    $errors[]="Name must be less than 255 characters";
  }elseif(!has_valid_email_format($admin['email'])){
    $errors[]="Email must be in correct format";
  }

  if (is_blank($admin['username'])){
    $errors[]="Username cannot be blank";
  }elseif (!has_length($admin['username'],['min'=>8, 'max'=>255])){
    $errors[]="Name must be between 8 and 255 characters";
  }
  $current_id=$admin['id']??'0';
  if (!has_unique_admin_username($admin['username'],$current_id)){
    $errors[]="Username must be unique.";
  }

  if ($password_required){

    if (is_blank($admin['password'])){
      $errors[]="Password cannot be blank";
    }elseif (!has_length($admin['password'],['min'=>12])){
      $errors[]="password must be 12 or more characters";
    }elseif(!preg_match('/[A-Z]/',$admin['password'])){
      $errors[]="Password must contain 1 atleast uppercase letter";
    }elseif(!preg_match('/[a-z]/',$admin['password'])){
      $errors[]="Password must contain atleast 1 lowercase letter";
    }elseif(!preg_match('/[0-9]/',$admin['password'])){
      $errors[]="Password must contain atleast 1 number";
    }elseif(!preg_match('/[^A-Za-z0-9\s]/',$admin['password'])){
      $errors[]="Password must contain atleast 1 symbol";
    }

    if (is_blank($admin['confirm_password'])){
      $errors[]="Confirm Password cannot be blank";
    }elseif($admin['password']!==$admin['confirm_password']){
      $errors[]="password and confirm password must match";
    }

  }
  return $errors;
}

function find_admin_by_id($id){
  global $db;
  $sql = "SELECT * FROM admins ";
  $sql.= "WHERE id='".db_escape($db,$id)."'";
  $result = mysqli_query($db,$sql);
  confirm_result_set($result);
  $admin=mysqli_fetch_assoc($result);
  mysqli_free_result($result);
  return $admin;
}

function update_admin($admin){
  global $db;
  $password_sent = !is_blank($admin['password']);
  $error = validate_admin($admin,['password_required' => $password_sent]);
  if (!empty($errors)){
    return $errors;
  }
  $admin['hashed_password'] = password_hash($admin['password'],PASSWORD_BCRYPT);

  $sql="UPDATE admins SET ";
  $sql.="first_name='". db_escape($db, $admin['first_name']) ."', ";
  $sql.="last_name='". db_escape($db, $admin['last_name']) ."', ";

  $sql.="email='". db_escape($db, $admin['email']) ."', ";
  if ($password_sent){
    $sql.="hashed_password='". db_escape($db, $admin['hashed_password']) ."', ";
  }
  $sql.="username='". db_escape($db, $admin['username']) ."' ";
  $sql.="WHERE id='".db_escape($db, $admin['id']) ."' ";
  $sql.="LIMIT 1";

  $result=mysqli_query($db, $sql);
  if (result){
    return true;
  }else{
    echo mysqli_error();
    db_disconnect();
    exit();
  }

}

function delete_admin($id){
  global $db;
  $sql="DELETE FROM admins ";
  $sql.="WHERE id='". db_escape($db,$id)."' ";
  $sql.="LIMIT 1";

  $result = mysqli_query($db, $sql);
  if ($result){
    return true;
  }else{
    echo mysqli_error();
    db_disconnect($db);
    exit();
  }
}

function find_admin_by_username($username){
  global $db;
  $sql = "SELECT * FROM admins ";
  $sql.= "WHERE username='".db_escape($db,$username)."'";
  $result = mysqli_query($db,$sql);
  confirm_result_set($result);
  $admin=mysqli_fetch_assoc($result);
  mysqli_free_result($result);
  return $admin;
}

function shift_subject_positions($start_pos,$end_pos,$current_id=0){
  global $db;

  //if ($start_pos == $end_pos){return;}
  if ($start_pos==0){

    $sql= "UPDATE subjects SET ";
    $sql.="position = position + 1 ";
    $sql.="WHERE position >='".db_escape($db,$end_pos). "' ";
  }elseif($end_pos==0){

    $sql="UPDATE subjects SET ";
    $sql.="position = position - 1 ";
    $sql.="WHERE position >'".db_escape($db,$start_pos)."' ";

  }elseif ($start_pos < $end_pos){

    $sql = "UPDATE subjects SET ";
    $sql.="position = position - 1 ";
    $sql.="WHERE position >'".db_escape($db,$start_pos)."' ";
    $sql.="AND position <= '" . db_escape($db,$end_pos)."' ";

  }elseif ($start_pos > $end_pos){

    $sql = "UPDATE subjects SET ";
    $sql.="position = position + 1 ";
    $sql.="WHERE position >='".db_escape($db,$end_pos)."' ";
    $sql.="AND position < '" . db_escape($db,$start_pos)."' ";

  }
  $sql.= "And id!= '" . db_escape($db,$current_id) . "' ";
  $result = mysqli_query($db,$sql);

  if($result){
    return true;
  }else{
    echo mysqli_error();
    db_disconnect($db);
    exit;
  }


}

?>
