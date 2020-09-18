<?php
ob_start();
define('API_KEY','1264988188:AAErVBNgo9g7fDBxJr9r3m9z2sdZQTlT3sc');
$admin =  835459207;
$update = json_decode(file_get_contents('php://input'));
$from_id = $update->message->from->id;
$name = $update->message->from->first_name;
$chat_id = $update->message->chat->id;
$chatid = $update->callback_query->message->chat->id;
$data = $update->callback_query->data;
$text = $update->message->text;
$message_id = $update->callback_query->message->message_id;
$message_id_feed = $update->message->message_id;
function coding($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
  var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

//stat-sorce

if(preg_match('/^\/([Ss]tart)/',$text)){
coding('sendMessage',[
    'chat_id'=>$chat_id,
    'text'=>"سلام انتخاب کنید",
    'parse_mode'=>'html', 'reply_markup'=>json_encode([
      'inline_keyboard'=>[
  [
  ['text'=>' متن دکمه شیشه ای ','callback_data'=>'first']
         ]
   ]
  ])
  ]);
}
  elseif ($data == "first") {
  coding('editMessagetext',[
  'chat_id'=>$chatid,
  'message_id'=>$message_id,
  'text'=>"ممبر",
  'parse_mode'=>'html',
  'reply_markup'=>json_encode([
  'inline_keyboard'=>[
  [
   ['text'=>"ورود به کانال",url=>"https://t.me/players98_ir"]
        ]
      ]
    ])
  ]);
 }

//panel

elseif(preg_match('/^\/([Pp]anel)/',$text) and $from_id == $admin){
    $user = file_get_contents('members.txt');
    $member_id = explode("\n",$user);
    $member_count = count($member_id) -1;
    coding('sendMessage',[
      'chat_id'=>$chat_id,
      'text'=>"تعداد کل اعضا: $member_count",
      'parse_mode'=>'HTML'
    ]);
}
unlink("error_log");
$user = file_get_contents('members.txt');
    $members = explode("\n",$user);
    if (!in_array($chat_id,$members)){
      $add_user = file_get_contents('members.txt');
      $add_user .= $chat_id."\n";
     file_put_contents('members.txt',$add_user);
    }
elseif(preg_match('/^\/([Ss]tats)/',$text) and $from_id == $admin){
    $user = file_get_contents('members.txt');
    $member_id = explode("\n",$user);
    $member_count = count($member_id) -1;
    coding('sendMessage',[
      'chat_id'=>$chat_id,
      'text'=>"تعداد کل اعضا: $member_count",
      'parse_mode'=>'HTML'
    ]);
}
unlink("error_log");
$user = file_get_contents('members.txt');
    $members = explode("\n",$user);
    if (!in_array($chat_id,$members)){
      $add_user = file_get_contents('members.txt');
      $add_user .= $chat_id."\n";
     file_put_contents('members.txt',$add_user);
    }
?>
