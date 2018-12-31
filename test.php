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

print 'anything';


$post_title=$rows[$i][1];
$my_subject = ((strpos($post_title, 'Re: ') !== 0) ? 'Re: ' : '') . censor_text($post_title);

// note that multibyte support is enabled here 
$my_text    = $rows[$i][1];

// variables to hold the parameters for submit_post
$poll = $uid = $bitfield = $options = ''; 

generate_text_for_storage($my_subject, $uid, $bitfield, $options, false, false, false);
generate_text_for_storage($my_text, $uid, $bitfield, $options, true, true, true);

$poll = array();

 $user->session_create(2);
                   $auth->acl($user->data);
                   $user->setup();

     $message_parser = new parse_message();
                    $message_parser->message = $my_text;
                    $message_md5 = md5($message_parser->message);

   //select * from phpbb_forums f join phpbb_topics t on f.forum_id=t.forum_id where f.forum_name="Your first forum" and t.topic_title =""

                     $usql1 = 'SELECT * FROM ' . USERS_TABLE . " WHERE username = '" . $username . "' ";
                  $uresult1 = $db->sql_query($usql1);
                  $urow1 = $db->sql_fetchrow($uresult1);
                  $db->sql_freeresult($uresult1);
   
   $sql = 'SELECT *
				FROM ' . FORUMS_TABLE . ' f
				JOIN ' . TOPICS_TABLE . ' t on  f.forum_id=t.forum_id  WHERE f.forum_name="main forums" and t.topic_title ="Gameing access"
				';
			$result = $db->sql_query($sql); 
			$r1 = $db->sql_fetchrow($result);  
			$db->sql_freeresult($result);

			echo "<pre>"; print_r($r1);exit;   

			          

 $data = array(
                        'topic_title'     => $my_subject,
                        'topic_first_post_id' => 0,
                        'topic_last_post_id'  => 0,
                        'topic_time_limit'    => 0,
                        'topic_attachment'    => 0,
                        'post_id'       => 1,
                        'topic_id'        => 1,
                        'forum_id'        => 1,
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
                        'forum_name'      => "main forums",
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
                        'topic_first_poster_name' => "admin",
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
/*
$data = array( 
    // General Posting Settings
    'forum_id'            => 1,    // The forum ID in which the post will be placed. (int)
    'topic_id'            => 1,    // Post a new topic or in an existing one? Set to 0 to create a new one, if not, specify your topic ID here instead.
    'icon_id'            => false,    // The Icon ID in which the post will be displayed with on the viewforum, set to false for icon_id. (int)

    // Defining Post Options
    'enable_bbcode'    => true,    // Enable BBcode in this post. (bool)
    'enable_smilies'    => true,    // Enabe smilies in this post. (bool)
    'enable_urls'        => true,    // Enable self-parsing URL links in this post. (bool)
    'enable_sig'        => true,    // Enable the signature of the poster to be displayed in the post. (bool)

    // Message Body
    'message'            => $my_text,        // Your text you wish to have submitted. It should pass through generate_text_for_storage() before this. (string)
    'message_md5'    => md5($my_text),// The md5 hash of your message

    // Values from generate_text_for_storage()
    'bbcode_bitfield'    => $bitfield,    // Value created from the generate_text_for_storage() function.
    'bbcode_uid'        => $uid,        // Value created from the generate_text_for_storage() function.

    // Other Options
    'post_edit_locked'    => 0,        // Disallow post editing? 1 = Yes, 0 = No
    'topic_title'        => $subject,    // Subject/Title of the topic. (string)

    // Email Notification Settings
    'notify_set'        => false,        // (bool)
    'notify'            => false,        // (bool)
    'post_time'         => 0,        // Set a specific time, use 0 to let submit_post() take care of getting the proper time (int)
    'forum_name'        => '',        // For identifying the name of the forum in a notification email. (string)

    // Indexing
    'enable_indexing'    => true,        // Allow indexing the post? (bool)

    // 3.0.6
    'force_approved_state'    => true, // Allow the post to be submitted without going into unapproved queue
);
print 'anything';
submit_post('reply', $subject, '', 'POST_NORMAL', $poll, $data, $update_message = true, $update_search_index = true);*/
?>