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

              $f1 = 'SELECT COUNT(*)   AS forum_count FROM '  . FORUMS_TABLE;
              $fs = $db->sql_query($f1);
              $f_count= (int) $db->sql_fetchfield('forum_count');
              $db->sql_freeresult($fs);

              $d = $rows[$i][3];
              $date = new DateTime($d);
              $dateee = $date->getTimestamp();

                  if($f_count==0)
                  {
                      $sql_arr = array(
                        'forum_id'    => 1,
                        'parent_id'   => 0,
                        'left_id'     => 1,
                        'right_id'    => 2,
                        'forum_parents' =>'',
                        'forum_name'  => 'Main Category',
                        'forum_desc'  => '',
                        'forum_desc_bitfield' => '',
                        'forum_desc_options' => 7,
                        'forum_desc_uid' => '',
                        'forum_link'    => '',
                        'forum_password'  => '',
                        'forum_style'  => 0,
                        'forum_image' => '',
                        'forum_rules'  => '',
                        'forum_rules_link' => '',
                        'forum_rules_bitfield' => '',
                        'forum_rules_options' => 7,
                        'forum_rules_uid' => '',
                        'forum_topics_per_page' => 0,
                        'forum_type' => 1,
                        'forum_status' => 0,
                        'forum_last_post_id' => 1,
                        'forum_last_poster_id' => 2,
                        'forum_last_post_subject' => '',
                        'forum_last_post_time' => $dateee,
                        'forum_last_poster_name' => 'admin',
                        'forum_last_poster_colour' => 'AA0000', 
                        'forum_flags'  => 32, 
                        'display_on_index' => 1,
                        'enable_indexing' => 1, 
                        'enable_icons'  => 1,
                        'enable_prune'  => 0,
                        'prune_next'  => 0,
                        'prune_days'   =>  0, 
                        'prune_viewed'  => 0,
                        'prune_freq'  =>  0,
                        'display_subforum_list' => 1,
                        'forum_options'  => 0,
                        'forum_posts_approved'  =>  0,
                        'forum_posts_unapproved'  =>  0,
                        'forum_posts_softdeleted'  => 0,
                        'forum_topics_approved'  =>  0, 
                        'forum_topics_unapproved'  => 0,
                        'forum_topics_softdeleted' => 0,
                        'enable_shadow_prune'  => 0,
                        'prune_shadow_days'  => 7,
                        'prune_shadow_freq' => 1, 
                        'prune_shadow_next' => 0
                    );

                    $fdata = 'INSERT INTO ' . FORUMS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_arr);
                    $db->sql_query($fdata);

                  }
              
              for($i=1;$i<=count($rows);$i++)
              {
                  $forumname = $rows[$i][0];
                  $fsql1 = 'SELECT * FROM ' . FORUMS_TABLE . " WHERE forum_name = '" . $forumname . "' ";
                  $fresult1 = $db->sql_query($fsql1);
                  $frow1 = $db->sql_fetchrow($fresult1);
                  $db->sql_freeresult($fresult1);

                  if(empty($frow1))
                  {
                      $fsql2 = 'SELECT * FROM ' . FORUMS_TABLE . " order by right_id desc limit 1 ";
                      $fresult2 = $db->sql_query($fsql2);
                      $frow2 = $db->sql_fetchrow($fresult2);
                      $db->sql_freeresult($fresult2);

                      if(!empty($frow2))
                      {
                        $fid = $frow2['forum_id'];
                        $forum_id = create_forum($fid, $forumname);  
                      }

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

                    $f = $rows[$i][0];
                    $t = $rows[$i][1];

                    $sql = 'SELECT * FROM phpbb_forums f join phpbb_topics t on f.forum_id=t.forum_id where f.forum_name="'.$f.'" and t.topic_title="'.$t.'" ';
                    $result = $db->sql_query($sql); 
                    $r1 = $db->sql_fetchrow($result); 

                    if(empty($r1))
                    {

                            $d = $rows[$i][3];
                            $date = new DateTime($d);
                            $dateee = $date->getTimestamp();

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
                              'post_time'        => $dateee,
                              'topic_time'      => $dateee,
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
                  else{

                    $d = $rows[$i][3];
                    $date = new DateTime($d);
                    $dateee = $date->getTimestamp();

                    $post_title=$rows[$i][1];
                    $my_subject = ((strpos($post_title, 'Re: ') !== 0) ? 'Re: ' : '') . censor_text($post_title);

                    // note that multibyte support is enabled here 
                    $my_text    = $rows[$i][1];

                    $data = array(
                        'topic_title'     => $my_subject,
                        'topic_first_post_id' => 0,
                        'topic_last_post_id'  => 0,
                        'topic_time_limit'    => 0,
                        'topic_attachment'    => 0,
                        'topic_id'        => $r1['topic_id'],
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
                        'post_time'        => $dateee,
                        'topic_time'      => $dateee,
                        'post_edit_user'    => 0,
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

                   submit_post('reply', $my_subject, '', 'POST_NORMAL', $poll, $data, $update_message = true, $update_search_index = true);  

                    $p = $data['post_id'];
                   $t= $data['topic_id'];

                   $s = 'UPDATE ' . POSTS_TABLE . " SET post_postcount = '1' WHERE post_id = $p";
                   $db->sql_query($s);

                   $s1 = 'UPDATE ' . TOPICS_TABLE . " SET topic_first_poster_name = '".$username."' WHERE topic_id = $t";
                   $db->sql_query($s1);              

                   $user->session_kill(false);  
                  }

                                  
                }
                echo '<span class="msg1">'.count($rows).' Posts inserted  Successfully.</span>';
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


function create_forum($parent_forum_id, $new_forum){

    global $request;
    $phpbb_root_path = $request->server('DOCUMENT_ROOT').'/forum/';
    include_once($phpbb_root_path . 'config.php');

   /* @$db = new mysqli('localhost', "root", "", "forum");*/
     @$db = new mysqli('ns17.refineriaweb.com', "bieninve", "8j9(:v9Wi2lCWQ", "bieninve_forum");
    if (mysqli_connect_errno())
    {
      die("Sorry, cant connect to the database!".mysqli_connect_errno().' '.mysqli_connect_error()."<br />");
    }

    /*
      This function takes a forum_id, a name for the new forum, and the database handle as arguments.
      It creates the new forum as child under the given parent forum_id.
    */

    $rc = 0;

    // Get right_id of parent forum
    // Calc left_id and right_id for new forum

    $query = "SELECT `right_id` FROM `phpbb_forums` where `forum_id` = ".$parent_forum_id.";";
    $parent = $db->query($query);
    $obj = $parent->fetch_object();
    //echo "<pre>";print_r($obj);exit;
    $right_id = $obj->right_id;
    $next_leftid  = $right_id;
    $next_rightid = $next_leftid+1;

    // Increase left_id and right_id by 2 in all forums where they are >= new left_id

    $query = "UPDATE phpbb_forums SET left_id = left_id + 2 where left_id >= ".$next_leftid.";";
    //echo $query.'  ';
    $result = $db->query($query);
    if ($result)
     $db->affected_rows." records changed.<br />";
    else $rc += 1;

    $query = "UPDATE phpbb_forums SET right_id = right_id + 2 where right_id >= ".$next_leftid.";";
    //echo $query.'  ';
    $result = $db->query($query);
    if ($result)
      $db->affected_rows." records changed.<br />";
    else $rc += 1;

    // Create new forum phpbb_forums

    $query = 'INSERT INTO `phpbb_forums` (`parent_id`, `left_id`, `right_id`,`forum_parents`, `forum_name`, `forum_desc`, `forum_type`, `forum_status`, `display_subforum_list`, `display_on_index`, `enable_icons`, `forum_flags` ,`forum_rules` ) Values (0, '.$next_leftid.', '.$next_rightid.', "" ,"'.$new_forum.'", "", 1, 0, 1, 1, 1, 32,"");';
    //echo $query.'  ';
    $result = $db->query($query);
    if ($result)
      $db->affected_rows." record added.<br />";
    else $rc += 1;
    $new_forum_id = $db->insert_id;
    // Get forum_id of new forum

    $query = 'SELECT `forum_id` FROM `phpbb_forums` WHERE `forum_name` LIKE "'.$new_forum.'";';
    //echo $query.'  ';
    $result = $db->query($query);
    if ($result)
      $db->affected_rows." record retrieved.<br />";
    else $rc += 1;
    $forum_obj = $result->fetch_object();
    //$new_forum_id = $forum_obj->forum_id;

    // correct right_id of parent forum

    $query = 'UPDATE `phpbb_forums` SET `right_id` ='. ($next_rightid + 1).' WHERE `forum_id` = '.$parent_forum_id.';';
    //echo $query.'  ';
    $result = $db->query($query);
    if ($result)
      $db->affected_rows." record changed.<br />";
    else $rc += 1;

    // set forum permissions for the new forum in phpbb_acl_groups
    // this probably could be done much more efficient but my SQL isn't what it used to be.

    // sample values below set permissions to read only for registered members, as that is what I needed.
    // you probably wish to changes these values (copy them from a forum with the desired settings).

    $query = 'INSERT INTO `phpbb_acl_groups` (`group_id`, `forum_id`, `auth_option_id`, `auth_role_id`, auth_setting)'.
           'VALUES (1, '.$new_forum_id.', 0, 17, 0);';
    //echo $query.'  ';
    $result = $db->query($query);
    if ($result)
      $db->affected_rows." records added.<br />";
    else $rc += 1;

    $query = 'INSERT INTO `phpbb_acl_groups` (`group_id`, `forum_id`, `auth_option_id`, `auth_role_id`, auth_setting)'.
           'VALUES (2, '.$new_forum_id.', 0, 17, 0);';
    //echo $query.'  ';
    $result = $db->query($query);
    if ($result)
      $db->affected_rows." records added.<br />";
    else $rc += 1;

    $query = 'INSERT INTO `phpbb_acl_groups` (`group_id`, `forum_id`, `auth_option_id`, `auth_role_id`, auth_setting)'.
           'VALUES (3, '.$new_forum_id.', 0, 17, 0);';
    //echo $query.'  ';
    $result = $db->query($query);
    if ($result)
      $db->affected_rows." records added.<br />";
    else $rc += 1;

    $query = 'INSERT INTO `phpbb_acl_groups` (`group_id`, `forum_id`, `auth_option_id`, `auth_role_id`, auth_setting)'.
           'VALUES (4, '.$new_forum_id.', 0, 14, 0);';
    //echo $query.'  ';
    $result = $db->query($query);
    if ($result)
      $db->affected_rows." records added.<br />";
    else $rc += 1;

    $query = 'INSERT INTO `phpbb_acl_groups` (`group_id`, `forum_id`, `auth_option_id`, `auth_role_id`, auth_setting)'.
           'VALUES (5, '.$new_forum_id.', 0, 14, 0);';
    //echo $query.'  ';
    $result = $db->query($query);
    if ($result)
       $db->affected_rows." records added.<br />";
    else $rc += 1;

    $query = 'INSERT INTO `phpbb_acl_groups` (`group_id`, `forum_id`, `auth_option_id`, `auth_role_id`, auth_setting)'.
           'VALUES (5, '.$new_forum_id.', 0, 10, 0);';
    //echo $query.'  ';
    $result = $db->query($query);
    if ($result)
      $db->affected_rows." records added.<br />";
    else $rc += 1;

    $query = 'INSERT INTO `phpbb_acl_groups` (`group_id`, `forum_id`, `auth_option_id`, `auth_role_id`, auth_setting)'.
           'VALUES (6, '.$new_forum_id.', 0, 19, 0);';
    //echo $query.'  ';
    $result = $db->query($query);
    if ($result)
      $db->affected_rows." records added.<br />";
    else $rc += 1;

    $query = 'INSERT INTO `phpbb_acl_groups` (`group_id`, `forum_id`, `auth_option_id`, `auth_role_id`, auth_setting)'.
           'VALUES (7, '.$new_forum_id.', 0, 16, 0);';
    //echo $query.'  ';
    $result = $db->query($query);
    if ($result)
      $db->affected_rows." records added.<br />";
    else $rc += 1;

    return $new_forum_id;
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


