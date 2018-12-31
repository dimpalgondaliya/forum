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
include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include($phpbb_root_path . 'includes/message_parser.' . $phpEx);
include($phpbb_root_path . "Classes/PHPExcel/IOFactory.". $phpEx);
include($phpbb_root_path . "Classes/PHPExcel/Settings.". $phpEx);

global $config, $db, $user, $request;
global $request, $phpbb_container, $phpbb_dispatcher;

// Start session management
/*$user->session_begin();
$auth->acl($user->data);
$user->setup();*/
$submit = ($request->is_set_post('submit')) ? true : false;
if($submit)
{
  $file  = $request->file("uploadFile");
  $filename= iconv('UTF-8', 'ISO-8859-2//TRANSLIT', strip_tags($file['name']));
  if(isset($filename) && $filename != "") {
    $passwords_manager = $phpbb_container->get('passwords.manager');
    if(!empty($file)){
        $allowedExtensions = array("xls","xlsx");
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(in_array($ext, $allowedExtensions)) {
          $file_size = $file['size'] / 1024;
          if($file_size > 0) {
                $file1 = "uploads/".$filename;
                $isUploaded = copy($file['tmp_name'], $file1);
            if($isUploaded) {
            try {
              \PHPExcel_Settings::setZipClass(\PHPExcel_Settings::PCLZIP);
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

                  $forumname = $rows[$i][0];
                  $fsql1 = 'SELECT * FROM ' . FORUMS_TABLE . " WHERE forum_name = '" . $forumname . "' ";
                  $fresult1 = $db->sql_query($fsql1);
                  $frow1 = $db->sql_fetchrow($fresult1);
                  $db->sql_freeresult($fresult1);

                  if(empty($frow1))
                  {
                      echo "empty"; exit;
                  }
                  else
                  {
                    $forum_id =  $frow1['forum_id']; 
                  }

                  $username = $rows[$i][2];
                  $usql1 = 'SELECT * FROM ' . USERS_TABLE . " WHERE username = '" . $username . "' ";
                  $uresult1 = $db->sql_query($usql1);
                  $urow1 = $db->sql_fetchrow($uresult1);
                  $db->sql_freeresult($uresult1);
                  
                  if (empty($urow1)){
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

                        $sql = 'SELECT * FROM ' . USERS_TABLE . " WHERE username = '" . $db->sql_escape($group_name) . "' AND group_id = 3";
                        $result = $db->sql_query($sql);
                        $row = $db->sql_fetchrow($result);
                        $db->sql_freeresult($result);

                        $user_row = array(
                            'username'           => (!empty($rows[$i][2])? $rows[$i][2] : ""),
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
                   else
                   {
                       $user_id = $urow1['user_id'];
                   }

                   $user->session_create($user_id);
                   $auth->acl($user->data);
                   $user->setup();

                    $message_parser = new parse_message();
                    $message_parser->message = $rows[$i][4];
                    $message_md5 = md5($message_parser->message);

                   $data = array(
                        'topic_title'     => $rows[$i][1],
                        'topic_first_post_id' => 0,
                        'topic_last_post_id'  => 0,
                        'topic_time_limit'    => 0,
                        'topic_attachment'    => 0,
                        'post_id'       => 0,
                        'topic_id'        => 0,
                        'forum_id'        => $forum_id,
                        'icon_id'       => 0,
                        'enable_sig'      => true,
                        'enable_bbcode'     => true,
                        'enable_smilies'    => true,
                        'enable_urls'     => true,
                        'enable_indexing'   => true,
                        'message_md5'     => (string) $message_md5,
                        'post_checksum'     => '',
                        'post_edit_reason'    => "",
                        'post_edit_user'    => 0,
                        //'forum_parents'     => $post_data['forum_parents'],
                        'forum_name'      => $forumname,
                        'notify'        => false,
                        'notify_set'      => false,
                        'poster_ip'       => $user->ip,
                        'post_edit_locked'    => 0,
                        'bbcode_bitfield'   => $message_parser->bbcode_bitfield,
                        'bbcode_uid'      => $message_parser->bbcode_uid,
                        'message'       => $message_parser->message,
                        'attachment_data'   => $message_parser->attachment_data,
                        'filename_data'     => $message_parser->filename_data,
                        'topic_status'      => 0,
                        'force_approved_state' => true,
                        'topic_first_poster_name' => $username,
                        'topic_first_poster_colour' => $user->data['user_colour'],
                        'topic_visibility'      => false,
                        'post_visibility'     => false,
                   );

                   $poll = array();

                   submit_post('post',$rows[$i][1],"", POST_NORMAL ,$poll,$data);

                   $p = $data['post_id'];
                   $t= $data['topic_id'];

                   $s = 'UPDATE ' . POSTS_TABLE . " SET post_postcount = '1' WHERE post_id = $p";
                   $db->sql_query($s);

                   $s1 = 'UPDATE ' . TOPICS_TABLE . " SET topic_first_poster_name = '".$username."' WHERE topic_id = $t";
                   $db->sql_query($s1);        

                   $user->session_kill(false);                  
                }
                echo '<span class="msg1">Post inserted  Successfully.</span>';
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


