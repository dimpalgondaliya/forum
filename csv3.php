<!DOCTYPE>
<html lang="en">
<!--=========================================bootstrap links ===========================================-->
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
</head>

<!--=========================================file uploding design code===========================-->
<body>
    <div id="wrap">
        <div class="container">
            <div class="row">

                <form class="form-horizontal" action="excel.php" method="post" name="upload_excel" enctype="multipart/form-data">
                    <fieldset>

                        <!-- Form Name -->
                        <legend>Demo</legend>

                        <!-- File Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="filebutton">Select File</label>
                            <div class="col-md-4">
                                <input type="file" name="file" id="file" class="input-large">
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="singlebutton">Import data</label>
                            <div class="col-md-4">
                                <button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
                            </div>
                        </div>

                    </fieldset>
                </form>

            </div>
            <?php
               get_all_records();
            ?>
        </div>
    </div>
<!--=====================================================file expoting design code==============================-->
    <div>
      <form class="form-horizontal" action="excel.php" method="post" name="upload_excel" enctype="multipart/form-data">
          <div class="form-group">
              <div class="col-md-4 col-md-offset-4">
                  <input type="submit" name="Export" class="btn btn-success" value="export to excel"/>
              </div>
           </div>                    
      </form>           
    </div>

  </body>
</html>

<?php 
/*===============================================display record in table format===========================*/
function get_all_records(){
    $con = getdb();
    $Sql = "SELECT * FROM employeeinfo";
    $result = mysqli_query($con, $Sql);  

    if (mysqli_num_rows($result) > 0) {  echo "<div class='table-
    responsive'><table id='myTable' class='table table-striped table-
    bordered'>  <thead><tr><th>user ID</th>   <th>user type</th>   <th>user
    Name</th>   <th>Email</th>   <th>post_id</th>   <th>post_subject</th>
    </tr></thead><tbody>";

         while($row = mysqli_fetch_assoc($result)) {

             echo "<tr><td>" . $row['user_id']."</td>
                       <td>" . $row['user_type']."</td>
                       <td>" . $row['username']."</td>
                       <td>" . $row['user_email']."</td>
                       <td>" . $row['post_id']."</td>
                       <td>" . $row['post_subject']."</td>
                       </tr>";        
         }
         echo "</tbody></table></div>";
    } 
    else {
       echo "you have no records";
    }
}
/*====================================================export inserted data==============================*/
if(isset($_POST["Export"])){
  header('Content-Type: text/csv; charset=utf-8');  
  header('Content-Disposition: attachment; filename=data.csv');  
  $output = fopen("php://output", "w");  
  fputcsv($output, array('ID', 'First Name', 'Last Name', 'Email','post_subject'));  
  $query = "SELECT * from employeeinfo ORDER BY user_id DESC";  
  $result = mysqli_query($con, $query);  
  while($row = mysqli_fetch_assoc($result))  
  {  
       fputcsv($output, $row);  
  }  
  fclose($output);  
} 
/*==========================================get db connection=============================*/
function getdb(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "forum";

    try {
          $conn = mysqli_connect($servername, $username, $password, $db);
         //echo "Connected successfully"; 
        }
    catch(exception $e)
        {
          echo "Connection failed: " . $e->getMessage();
        }
        return $conn;
    }
 ?>

<?php
  $con = getdb();
  if(isset($_POST["Import"])){
        
        $filename=$_FILES["file"]["tmp_name"];      

         if($_FILES["file"]["size"] > 0)
         {

          define('IN_PHPBB', true);
          $phpbb_root_path = $_SERVER['DOCUMENT_ROOT'].'/forum/';
          $phpEx = substr(strrchr(__FILE__, '.'), 1);
          include($phpbb_root_path . 'common.' . $phpEx);
          include($phpbb_root_path . 'includes/functions_user.' . $phpEx);

          // Start session management
          $user->session_begin();
          $auth->acl($user->data);
          $user->setup();

            $file = fopen($filename, "r");
            $flag=true;


      global $config, $db, $user, $request;
      global $request, $phpbb_container, $phpbb_dispatcher;
      // Instantiate passwords manager
      /* @var $passwords_manager \phpbb\passwords\manager */
      $passwords_manager = $phpbb_container->get('passwords.manager');







             while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
             {
              if($flag)
              {
                $flag=false;
                continue;
              }

              // setup the user array for the new user
              $user_row = array(
                  'username'              => $getData[2],
                  'user_password'         => $passwords_manager->hash('admin123'),
                  'user_email'            => $getData[3],
                  'group_id'              => 2,
                  'user_timezone'         => 0,
                  'user_lang'             => 'en',
                  'user_type'             => 0,
                  'user_actkey'           => "",
                  'user_ip'               => "",
                  'user_regdate'          => time(),
                  'user_inactive_reason'  => 0,
                  'user_inactive_time'    => 0,
              );





        

              $result = user_add($user_row);




               /*$sql = "INSERT into employeeinfo (user_id,user_type,username,user_email,post_id ,post_subject) 
                   values ('".$getData[0]."','".$getData[1]."','".$getData[2]."','".$getData[3]."','".$getData[4]."','".$getData[5]."')";
                   $result = mysqli_query($con, $sql);*/
                if(!isset($result))
                {
                    echo "<script type=\"text/javascript\">
                            alert(\"Invalid File:Please Upload CSV File.\");
                            window.location = \"excel.php\"
                          </script>";       
                }
                else {
                      echo "<script type=\"text/javascript\">
                        alert(\"CSV File has been successfully Imported.\");
                        window.location = \"excel.php\"
                    </script>";
                }
             }
            
             fclose($file); 
         }
    }    
 ?>

