<?php
date_default_timezone_set('Asia/Bangkok');

include 'food_check.php';
include 'food_save.php';
include 'user.php';
include 'search_food.php';
include 'search_exercise.php';
include 'postback.php';
include 'req_manage.php';
include 'mg_push.php';

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
    if ($event['type'] == 'postback') {

      // get replyToken
      $replyToken = $event['replyToken'];
      $datapostback = $event['postback']['data'];
      $userIdpostback = $event['source']['userId'];

      $text_type = explode(':', $datapostback);
      $key = $text_type[0];
      $value = $text_type[1];
      if ($key == "user_confirm") {
        $obdata->setpostback($userIdpostback,$key);
        $data['replyToken'] = $replyToken;
        $data['messages'][0]['type'] = "text";
        $data['messages'][0]['text'] = $value;
      }


      //
      // $data['replyToken'] = $replyToken;
      // $data['messages'][0]['type'] = "text";
      // $data['messages'][0]['text'] = $key." ".$value;


    }


    // messages_back
    else if ($event['type'] == 'message' ) {
      if ($event['message']['type'] == 'text') {
        # code...


    // get replyToken
        $replyToken = $event['replyToken'];
    // get_userId
        $userId = $event['source']['userId'];
    // Get text sent
        $text = $event['message']['text'];

        $text_type = explode(' ', $text);
      // $confirm_food = explode(' ',$obdata->getpostback($userId));

        $user = new User;
        $food_dialy = new Food_save;
        $searchfood = new Searchfood;
        $searchexercise = new Searchexercise;
        $req = new Req_manage;

        // check user maping id
        $checkuser = $user->get_userId($userId);
        if ($checkuser == "null") {

          if ($text == "ต้องการยืนยันตัวตน") {
            $ms = [
            'type' => 'text',
            'text' => 'กรุณากรอก Username ของคุณ'. $text];
          }
          else if ($obdata->getpostback($userId) == "user_confirm") {
            if ($user->update_userid_line($text,$userId) == "success") {
              $displayname = $user->get_displayname($userId);
              $ms = [
              'type' => 'text',
              'text' => 'ยืนยันตัวตนสำเร็จ คุณ'.$displayname];
              $obdata->deletepostback($userId);
            }else {
              $ms = [
              'type' => 'text',
              'text' => 'ยืนยันตัวตนไม่สำเร็จ กรุณาลองอีกครั้ง'];
            }
          }
          else {
            $ms = [
            'type' => 'template',
            'altText' => 'สวัสดี คุณคือใคร',
            'template' => array(
              'type' => 'buttons',
              'title' => 'สวัสดี คุณคือใคร',
              'text' => 'กรุณาเลือกยืนยันตัวตนด้วย Username ของคุณ
              หรือ เลือกสมัครบัญชีใหม่',
              'actions' => array(
                // array(
                //   'type' => 'uri',
                //   'label' => 'สมัครบัญชีใหม',
                //   'uri' => 'https://arcane-sands-19975.herokuapp.com/web/register.php')
                // ,
                array(
                  'type' => 'postback',
                  'label' => 'ยืนยันตัวตน',
                  'data' => 'user_confirm:ต้องการยืนยันตัวตน')
                )
              )
            ];
          }

          $data['replyToken'] = $replyToken;
          $data['messages'][0] = $ms;
        }
        else {


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
            $ms = [
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
                  'data' => 'save_dialy:บันทึกมื้ออาหาร')
                )
              )
            ];
            $messages = [
            'type' => "text",
            'text' => "ขอโทษ ฉันไม่เข้าใจ"];

            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $ms;
          }
        }

      }
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

    // }
  }

}
// test push by date
// if (date('Y-m-d') == "2017-04-16") {
// // push_message
// $push = new Push;
//
// $pushdata = array();
// $pushdata['to'] = $userIdpostback;
// $pushdata['messages'][0]['type'] = "text";
// $pushdata['messages'][0]['text'] = " Push Message : ".$datapostback;
//
// $push->push_message($pushdata,$strAccessToken);
// }

echo "OK";

?>
