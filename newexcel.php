<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<body>
		<div class="row">
		<div class="wrapperDiv">
			<form action="" method="post" enctype="multipart/form-data">
				  <div class="col-md-8"> <label for="file1" class="text-left">Upload excel file :</label>
                   <input type="file"  id="file1" name="uploadFile" value="" style="display: none;" /></div>
				  <div class="col-md-3"><input type="submit" name="submit" value="Upload" /></div>
			</form>
		</div>
	   </div>

<style type="text/css">
	.wrapperDiv {
    background: #d2b48c45;
    width: 50%;
    margin: 0 auto;
    position: relative;
    top: 48px;
    padding: 64px;
    height: 187px;
}
input[type="submit"] {
    background: black;
    color: #fff;
    height: 34px;
    width: 114px;
    border: none;
    position: relative;
    bottom: 5px;
    border-radius: 5px;
}
label.text-left {
    background: #ffffffbf;
    height: 36px;
    width: 91%;
    padding: 7px;
    position: relative;
    bottom: 6px;
    color: #80808085;
    text-transform: capitalize;
}
.title
{
	font-size: 18px;
	text-transform: capitalize;
	font-weight: 500;
}
span.msg {
    text-align: center;
    position: relative;
    left: 41%;
    top: 85px;
    color: red;
    text-transform: capitalize;
    font-weight: bold;
    font-size: 22px;
}
span.msg1 {
    text-align: center;
    position: relative;
    left: 41%;
    top: 85px;
    color: green;
    text-transform: capitalize;
    font-weight: bold;
    font-size: 22px;
}

</style>
<?php
ini_set('max_execution_time', 0);
define('IN_PHPBB', true);
$phpbb_root_path = $_SERVER['DOCUMENT_ROOT'].'/forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/constants.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include($phpbb_root_path . 'includes/message_parser.' . $phpEx);
include($phpbb_root_path . "Classes/PHPExcel/IOFactory.". $phpEx);

global $config, $db, $user, $request;
global $request, $phpbb_container, $phpbb_dispatcher;

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
$submit = ($request->is_set_post('submit')) ? true : false;
if($submit)
{
	$file  = $request->file("uploadFile");
	if(isset($file['name']) && $file['name'] != "") {
		$message_parser = new parse_message();
		$message_parser->message = "this is gaming section";
		$message_md5 = md5($message_parser->message);
		$passwords_manager = $phpbb_container->get('passwords.manager');
		if(!empty($file)){
		    $allowedExtensions = array("xls","xlsx");
		    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		    if(in_array($ext, $allowedExtensions)) {
			    $file_size = $file['size'] / 1024;
			    if($file_size > 0) {
				$file1 = "uploads/".$file['name'];
				$isUploaded = copy($file['tmp_name'], $file1);
				    if($isUploaded) {
						try {
							$objPHPExcel = PHPExcel_IOFactory::load($file1);
						} catch (Exception $e) {
							die('Error loading file');
						}
						$excelsheet = $objPHPExcel->getActiveSheet();
						$rows = $excelsheet->toArray();
						
						if(!empty($rows))
						{
							unset($rows[0]) ;
							for($i=1;$i<=count($rows);$i++)
							{
							    $group_name = ($coppa) ? 'REGISTERED_COPPA' : 'REGISTERED';
						        $sql = 'SELECT group_id FROM ' . GROUPS_TABLE . " WHERE group_name = '" . $db->sql_escape($group_name) . "' AND group_type = 3";
							    $result = $db->sql_query($sql);
							    $row = $db->sql_fetchrow($result);
							    $db->sql_freeresult($result);

						        if (!$row){
						          trigger_error('NO_GROUP');
						        }
					            $group_id = $row['group_id'];
					            $user_type = USER_NORMAL;
					            $user_actkey = '';
					            $user_inactive_reason = 0;
					            $user_inactive_time = 0;
					            $timezone = $config['board_timezone'];
						        $data = array(
						          'lang' => basename($request->variable('lang', $user->lang_name)),
						          'tz' => $request->variable('tz', $timezone),
						        );

								$user_row = array(
					                    'username'           => (!empty($rows[$i][0])? $rows[$i][0] : ""),
							            'user_password'         => $passwords_manager->hash("password"),
							            'user_email'            => (!empty($rows[$i][1])? $rows[$i][1] : ""),
							            'group_id'              => (int) $group_id,
							            'user_timezone'         => $data['tz'],
							            'user_lang'             => $data['lang'],
							            'user_type'             => $user_type,
							            'user_actkey'           => $user_actkey,
							            'user_ip'               => $user->ip,
							            'user_regdate'          => time(),
							            'user_inactive_reason'  => $user_inactive_reason,
							            'user_inactive_time'    => $user_inactive_time,

							    );
						       
							    $result = user_add($user_row);

							    $user_id = (int) $db->sql_nextid();
						    }
						    echo '<span class="msg1">'.count($rows).' Users inserted  Successfully.</span>';
						} 
				    }else {
					  echo '<span class="msg">File not uploaded!</span>';
				 }
			}else{
				echo '<span class="msg">Maximum file size should not cross 50 KB on size!</span>';	
			 }
		  }else{
			echo '<span class="msg">This type of file not allowed!</span>';
		   }
		}
	}else{
		echo '<span class="msg">Select an excel file first!</span>';
	}
}

?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script type="text/javascript">
	$(function() {
		  $('input[type=file]').change(function(){
		    var t = $(this).val();
		    var labelText = 'File : ' + t.substr(12, t.length);
		    $(this).prev('label').text(labelText);
		  })
	});
</script>

	</body>
</html>	


