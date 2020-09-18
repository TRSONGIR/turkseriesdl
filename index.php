<?php
define("API_KEY","1264988188:AAErVBNgo9g7fDBxJr9r3m9z2sdZQTlT3sc");
//============================//  bot \\===============================
function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
};
  
function sendmessage($chat_id, $text){
 bot('sendMessage',[
 'chat_id'=>$chat_id,
 'text'=>$text,
 'parse_mode'=>"MarkDown"
 ]);
 };
//============================\\update// ==============================
$update = json_decode(file_get_contents('php://input'));
if(isset($update->message)){
$message = $update->message;
$message_id = $message->message_id;
$text = convert($message->text);
$chat_id = $message->chat->id;
$tc = $message->chat->type;
$first_name = $message->from->first_name;
$from_id = $message->from->id;};
//==============================//start//=========================
	if(preg_match('/^\/([Ss]tart)/',$text)){
sendmessage($chat_id, "Hi");
};
