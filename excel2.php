<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Demo example on Read excel file and import data into MySQL database using PHPExcel | Mitrajit's Tech Blog</title>
	<script src="js/jquery.min.js"></script>
  </head>
  <body>
	<style>
	span { clear:both; display:block; margin-bottom:30px; }
	span a { font-weight:bold; color:#0099FF; }
	
	

	table { border:1px solid #ccc; color:#fff; margin-top:20px; }
	table th {
		background-color:#0099FF;
		color:#fff;
	}
	td {
		background-color:#00CCFF;
		height:15px;
		text-align:center;
	}
	
	span.msg {
		display:block;
		margin-top:20px;
	}
	h4 {
		border-bottom:1px solid #000;
		margin-top:30px;
	}
	</style>
	<body>
		<span>Read the full article -- <a href="http://www.mitrajit.com/read-excel-file-import-data-mysql-database-using-php/" target="_blank">Read excel file and import data into MySQL database using PHPExcel</a> in <a href="http://www.mitrajit.com/">Mitrajit's Tech Blog</a></span>
		<div class="wrapperDiv">
			<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
				Upload excel file : 
				<input type="file" name="uploadFile" value="" />
				<input type="submit" name="submit" value="Upload" />
			</form>
			N.B. - 
			<!-- <ul>
				<li>1. <a href="sample.xlsx">Sample excel file</a></li>
				<li>2. After download, change the cell values and upload it again.</li>
				<li>3. Do not increase or decrease number of columns of the excel file. Otherwise, although it will display the data from the excel file but it can't store data into database.</li>
			</ul> -->
				
			<?php
			if(isset($_POST['submit'])) {
				if(isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != "") {
					$allowedExtensions = array("xls","xlsx");
					$ext = pathinfo($_FILES['uploadFile']['name'], PATHINFO_EXTENSION);
					var_dump($ext);exit;
					if(in_array($ext, $allowedExtensions)) {
						$file_size = $_FILES['uploadFile']['size'] / 1024;
						if($file_size < 50) {
							$file = "uploads/".$_FILES['uploadFile']['name'];
							$isUploaded = copy($_FILES['uploadFile']['tmp_name'], $file);
							if($isUploaded) {
								include("db.php");
								include("Classes/PHPExcel/IOFactory.php");
								try {
									//Load the excel(.xls/.xlsx) file
									$objPHPExcel = PHPExcel_IOFactory::load($file);

								} catch (Exception $e) {
									die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME). '": ' . $e->getMessage());
								}
									
								//An excel file may contains many sheets, so you have to specify which one you need to read or work with.
								$sheet = $objPHPExcel->getSheet(0);

								$excelsheet = $objPHPExcel->getActiveSheet();

								$rows = $excelsheet->toArray();

								var_dump($rows[0][0]);exit;

								
								//It returns the highest number of rows
								$total_rows = $sheet->getHighestRow();
								//It returns the highest number of columns
								$highest_column = $sheet->getHighestColumn();
								
								echo '<h4>Data from excel file</h4>';
								echo '<table cellpadding="5" cellspacing="1" border="1" class="responsive">';
								
								$query = "insert into `phpbb_users` (`username`, `user_email`) VALUES ";

								//Loop through each row of the worksheet
								for($row =2; $row <= $total_rows; $row++) {
									//Read a single row of data and store it as a array.
									//This line of code selects range of the cells like A1:D1
									$single_row = $sheet->rangeToArray('A' . $row . ':' . $highest_column . $row, NULL, TRUE, FALSE);
									echo "<tr>";

									//Creating a dynamic query based on the rows from the excel file
									$query .= "(";
									//Print each cell of the current row
									foreach($single_row[0] as $key=>$value) {
										echo "<td>".$value."</td>";
										$query .= "'".mysqli_real_escape_string($con, $value)."',";
									}
									$query = substr($query, 0, -1);
									$query .= "),";
									echo "</tr>";
								}
								$query = substr($query, 0, -1);
								echo '</table>';
								var_dump($query);
								// At last we will execute the dynamically created query an save it into the database
								mysqli_query($con, $query);
								if(mysqli_affected_rows($con) > 0) {
									echo '<span class="msg">Database table updated!</span>';
								} else {
									echo '<span class="msg">Can\'t update database table! try again.</span>';
								}
								// Finally we will remove the file from the uploads folder (optional) 
								unlink($file);
							} else {
								echo '<span class="msg">File not uploaded!</span>';
							}
						} else {
							echo '<span class="msg">Maximum file size should not cross 50 KB on size!</span>';	
						}
					} else {
						echo '<span class="msg">This type of file not allowed!</span>';
					}
				} else {
					echo '<span class="msg">Select an excel file first!</span>';
				}
			}
			?>
	</div>
</body>