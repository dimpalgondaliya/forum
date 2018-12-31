<!DOCTYPE>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>

</head>

<body>
    <div id="wrap">
        <div class="container">
            <div class="row">

                <form class="form-horizontal" action="excel.php" method="post" name="upload_excel" enctype="multipart/form-data">
                    <fieldset>

                        <!-- Form Name -->
                        <legend>Form Name</legend>

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

function get_all_records(){
    $con = getdb();
    $Sql = "SELECT * FROM phpbb_users";
    $result = mysqli_query($con, $Sql);  

    if (mysqli_num_rows($result) > 0) {  echo "<div class='table-
    responsive'><table id='myTable' class='table table-striped table-
    bordered'>  <thead><tr><th>user ID</th>   <th>username</th>   <th>Email</th>  </tr></thead><tbody>";

         while($row = mysqli_fetch_assoc($result)) {

             echo "<tr><td>" . $row['user_id']."</td>
                       <td>" . $row['username']."</td>
                       <td>" . $row['user_email']."</td>
                       </tr>";        
         }
         echo "</tbody></table></div>";
    } 
    else {
       echo "you have no records";
    }
}

if(isset($_POST["Export"])){
  header('Content-Type: text/csv; charset=utf-8');  
  header('Content-Disposition: attachment; filename=data.csv');  
  $output = fopen("php://output", "w");  
  fputcsv($output, array('username', 'user_email'));  
  $query = "SELECT * from phpbb_users ORDER BY user_id DESC";  
  $result = mysqli_query($con, $query);  
  while($row = mysqli_fetch_assoc($result))  
  {  
       fputcsv($output, $row);  
  }  
  fclose($output);  
} 

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
            $file = fopen($filename, "r");
            $flag=true;
             while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
             {
              if($flag)
              {
                $flag=false;
                continue;
              }

               $sql = "INSERT into phpbb_users (username,user_email) 
                   values ('".$getData[0]."','".$getData[1]."')";
                   $result = mysqli_query($con, $sql);
                if(!isset($result))
                {
                    echo "<script type=\"text/javascript\">
                            alert(\"Invalid File:Please Upload CSV File.\");
                            window.location = \"excel2.php\"
                          </script>";       
                }
                else {
                      echo "<script type=\"text/javascript\">
                        alert(\"CSV File has been successfully Imported.\");
                        window.location = \"excel2.php\"
                    </script>";
                }
             }
            
             fclose($file); 
         }
    }    
 ?>

