<!DOCTYPE>
<html lang="en">
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
            
          </div>
      </div>
  </body>
</html>

<?php

define('IN_PHPBB', true);
$phpbb_root_path = $_SERVER['DOCUMENT_ROOT'].'/forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/constants.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include($phpbb_root_path . 'includes/message_parser.' . $phpEx);


global $config, $db, $user, $request;
global $request, $phpbb_container, $phpbb_dispatcher;

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();


$file  = $request->file("file");

$message_parser = new parse_message();
$message_parser->message = "this is gaming section";
$message_md5 = md5($message_parser->message);


  
if(!empty($file)){
        
  $filename=$file["tmp_name"];      

  if($file["size"] > 0)
  {
    $file = fopen($filename, "r");
    $flag=true;

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
       $group_name = ($coppa) ? 'REGISTERED_COPPA' : 'REGISTERED';

          $sql = 'SELECT group_id
          FROM ' . GROUPS_TABLE . "
          WHERE group_name = '" . $db->sql_escape($group_name) . "'
          AND group_type = 3";
          $result = $db->sql_query($sql);
          $row = $db->sql_fetchrow($result);
          $db->sql_freeresult($result);

          if (!$row)
          {
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

         // setup the user array for the new user
         $user_row = array(
            'username'              => (!empty($getData[0])? $getData[0] : ""),
            'user_password'         => $passwords_manager->hash($getData[1]),
            'user_email'            => (!empty($getData[2])? $getData[2] : ""),
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
         echo $result = user_add($user_row);

         $user_id = (int) $db->sql_nextid();



         $bkup_data = array(
        'user_backup' => $user->data,
      ); 
      // assign a given user_id
      $user->data['user_id'] = $result;

         $data = array(
        'topic_title'     => $getData[3],
        'topic_first_post_id' => 0,
        'topic_last_post_id'  => 0,
        'topic_time_limit'    => 0,
        'topic_attachment'    => 0,
        'post_id'       => 0,
        'topic_id'        => 0,
        'forum_id'        => 2,
        'icon_id'       => 0,
        //'poster_id'       => 48,
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
        'forum_name'      => "Your first forum",
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

        'topic_visibility'      => false,
        'post_visibility'     => false,
      );

$poll = array();


  submit_post('post',$getData[3],"", POST_NORMAL ,$poll,$data);

  echo $p = $data['post_id'];
  echo $t= $data['topic_id'];
   
$sql = 'UPDATE ' . POSTS_TABLE . "
          SET post_postcount = '1'
          WHERE post_id = $p";
        $db->sql_query($sql);

$sqll = 'UPDATE ' . TOPICS_TABLE . "
          SET topic_first_poster_name = '".$getData[0]."'
          WHERE topic_id = $t";
        $db->sql_query($sqll);        

  // restore the user back to where they came from
      $user->data = $bkup_data['user_backup'];
      
      // unset the $bkup_data
      unset($bckup_data);

     }  
     fclose($file); 
   }
 }    



 ?>

