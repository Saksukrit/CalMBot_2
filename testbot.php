<?php
date_default_timezone_set('Asia/Bangkok');

include 'food_check.php';
include 'food_save.php';
include 'user.php';
include 'search_food.php';
include 'search_exercise.php';
include 'postback.php';
include 'req_manage.php';

$strAccessToken = "8RNNBRGbDOu0y/MAr0BnuajV46/YU3MVzA0rA4m4t6F1orO6PHx6b913ABPg3bR7TEvQO99XihXnZaPKVO/4VsQXLqs8LQZdmskXuwncFHyyQ824y7XOt9GLFJOgodw9zUS5/9qgrff265ZoTF3e9QdB04t89/1O/w1cDnyilFU=";

$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);

// url reply
$strUrl = "https://api.line.me/v2/bot/message/reply";
// datapostback
$data = array();

//Postback
$obdata = new Postback;

if (!is_null($arrJson['events'])) {
  foreach ($arrJson['events'] as $event) {

    // get save postback
    // if ($event['type'] == 'postback') {
    //
    //     $datapostback = $event['postback']['data'];
    //     $userIdpostback = $event['source']['userId'];
    //     $obdata->setpostback($userIdpostback,$datapostback);
    //
    // }


    // messages_back
    if ($event['type'] == 'message' && $event['message']['type'] == 'text') {


    // get replyToken
      $replyToken = $event['replyToken'];
    // get_userId
      $userId = $event['source']['userId'];
    // Get text sent
      $text = $event['message']['text'];

      $text_type = explode(' ', $text);
      $confirm_food = explode(' ',$obdata->getpostback($userId));

      $user = new User;
      $food_dialy = new Food_save;
      $searchfood = new Searchfood;
      $searchexercise = new Searchexercise;
      $req = new Req_manage;





      if($text == "สวัสดี"){
        $data['replyToken'] = $replyToken;
        $data['messages'][0]['type'] = "text";
        $data['messages'][0]['text'] = "สวัสดี ID คุณคือ ".$arrJson['events'][0]['source']['userId'];
      }else if($text == "ชื่ออะไร"){
        $data['replyToken'] = $replyToken;
        $data['messages'][0]['type'] = "text";
        $data['messages'][0]['text'] = "ฉันยังไม่มีชื่อนะ";
      }else if($text == "หลายอัน"){
        $data['replyToken'] = $replyToken;
        $data['messages'][0]['type'] = "text";
        $data['messages'][0]['text'] = "ฉันทำอะไรไม่ได้เลย คุณต้องสอนฉันอีกเยอะ";
      }else{
        $ms2 = [
        'type' => 'template',
        'altText' => 'เมนูการใช้งาน',
        'template' => array(
        'type' => 'buttons',
        'title' => 'เมนูการใช้งาน',
        'text' => 'สวัสดี
        เมนูการใช้งาน',
        'actions' => array(
        array(
        'type' => 'postback',
        'label' => 'บันทึกมื้ออาหาร',
        'data' => 'save_dialy',
        'text' => 'บันทึกมื้ออาหาร')
        ,array(
        'type' => 'postback',
        'label' => 'ข้อมูลอาหาร',
        'data' => 'search_food',
        'text' => 'ค้นหาข้อมูลอาหาร')
        ,array(
        'type' => 'postback',
        'label' => 'ข้อมูลออกกำลังกาย',
        'data' => 'search_exercise',
        'text' => 'ค้นหาข้อมูลการออกกำลังกาย')
        ,array(
        'type' => 'postback',
        'label' => 'ดูข้อมูลผู้ใช้',
        'data' => 'get_profile',
        'text' => 'ดูข้อมูลผู้ใช้')
        )
        )
        ];
        $messages = [
        'type' => "text",
        'text' => "ขอโทษ ฉันไม่เข้าใจ"];

        $data['replyToken'] = $replyToken;
        $data['messages'][0] = $ms2;
  // $arrPostData['messages'][0]['type'] = "text";
  // $arrPostData['messages'][0]['text'] = "ฉันไม่เข้าใจคำสั่ง";
      }



// Header
      $arrHeader = array();
      $arrHeader[] = "Content-Type: application/json";
      $arrHeader[] = "Authorization: Bearer {$strAccessToken}";

// json_encode
      $post = json_encode($data);

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$strUrl);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $result = curl_exec($ch);
      curl_close ($ch);

    }
  }

}
// test push by date
// if (date('Y-m-d') == "2017-04-16") {
//   $strUrl = "https://api.line.me/v2/bot/message/push";
//   $arrPostData = array();
//   $arrPostData['to'] = "U223a593a6474192e91019c67a657ab7f";
//   $arrPostData['messages'][0]['type'] = "text";
//   $arrPostData['messages'][0]['text'] = "นี้คือการทดสอบ Push Message";
//
//   $ch = curl_init();
//   curl_setopt($ch, CURLOPT_URL,$strUrl);
//   curl_setopt($ch, CURLOPT_HEADER, false);
//   curl_setopt($ch, CURLOPT_POST, true);
//   curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
//   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
//   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//   $result = curl_exec($ch);
//   curl_close ($ch);
// }

echo "OK";

?>
