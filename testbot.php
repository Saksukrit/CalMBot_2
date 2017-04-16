<?php

$strAccessToken = "8RNNBRGbDOu0y/MAr0BnuajV46/YU3MVzA0rA4m4t6F1orO6PHx6b913ABPg3bR7TEvQO99XihXnZaPKVO/4VsQXLqs8LQZdmskXuwncFHyyQ824y7XOt9GLFJOgodw9zUS5/9qgrff265ZoTF3e9QdB04t89/1O/w1cDnyilFU=";

$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);

$strUrl = "https://api.line.me/v2/bot/message/reply";

$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";

if($arrJson['events'][0]['message']['text'] == "สวัสดี"){
  $arrPostData = array();
  $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
  $arrPostData['messages'][0]['type'] = "text";
  $arrPostData['messages'][0]['text'] = "สวัสดี ID คุณคือ ".$arrJson['events'][0]['source']['userId'];
}else if($arrJson['events'][0]['message']['text'] == "ชื่ออะไร"){
  $arrPostData = array();
  $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
  $arrPostData['messages'][0]['type'] = "text";
  $arrPostData['messages'][0]['text'] = "ฉันยังไม่มีชื่อนะ";
}else if($arrJson['events'][0]['message']['text'] == "หลายอัน"){
  $arrPostData = array();
  $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
  $arrPostData['messages'][0]['type'] = "text";
  $arrPostData['messages'][0]['text'] = "ฉันทำอะไรไม่ได้เลย คุณต้องสอนฉันอีกเยอะ";
  $arrPostData['messages'][1]['type'] = "text";
  $arrPostData['messages'][1]['text'] = "ฉันทำอะไรไม่ได้เลย คุณต้องสอนฉันอีกเยอะ";
}else if($arrJson['events'][0]['message']['text'] == "ลองสิ"){
  $strUrl = "https://api.line.me/v2/bot/message/push";
  $arrPostData = array();
  $arrPostData['to'] = "U223a593a6474192e91019c67a657ab7f";
  $arrPostData['messages'][0]['type'] = "text";
  $arrPostData['messages'][0]['text'] = "นี้คือการทดสอบ Push Message";
}else{

  $messages = [
  'type' => "text",
  'text' => "ขอโทษ ฉันไม่เข้าใจ"];

  $arrPostData = array();
  $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
  $arrPostData['messages'][0] = $messages;
  $arrPostData['messages'][1] = $messages;
  // $arrPostData['messages'][0]['type'] = "text";
  // $arrPostData['messages'][0]['text'] = "ฉันไม่เข้าใจคำสั่ง";
}



$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$strUrl);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close ($ch);

// test push by date
if (date('Y-m-d H:i') == "2017-04-16 19:50") {
  $strUrl = "https://api.line.me/v2/bot/message/push";
  $arrPostData = array();
  $arrPostData['to'] = "U223a593a6474192e91019c67a657ab7f";
  $arrPostData['messages'][0]['type'] = "text";
  $arrPostData['messages'][0]['text'] = "นี้คือการทดสอบ Push Message";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$strUrl);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($ch);
  curl_close ($ch);
}

echo "OK";

?>
