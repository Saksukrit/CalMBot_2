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
include 'wordcut.php';
include_once 'THSplitLib/segment.php';


$strAccessToken = "8RNNBRGbDOu0y/MAr0BnuajV46/YU3MVzA0rA4m4t6F1orO6PHx6b913ABPg3bR7TEvQO99XihXnZaPKVO/4VsQXLqs8LQZdmskXuwncFHyyQ824y7XOt9GLFJOgodw9zUS5/9qgrff265ZoTF3e9QdB04t89/1O/w1cDnyilFU=";

$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);

// url reply
$strUrl = "https://api.line.me/v2/bot/message/reply";
// datapostback
$data = array();

//Postback
$obdata = new Postback;
$user = new User;
$food_dialy = new Food_save;
$searchfood = new Searchfood;
$searchexercise = new Searchexercise;
$req = new Req_manage;

if (!is_null($arrJson['events'])) {
  foreach ($arrJson['events'] as $event) {

    // =============================================================================
    // start action by postback
    if ($event['type'] == 'postback') {

      // get replyToken
      $replyToken = $event['replyToken'];
      $datapostback = $event['postback']['data'];
      $userId = $event['source']['userId'];

      $text_type = explode(':', $datapostback);
      $key = $text_type[0];
      $value = $text_type[1];

      // user_confirm *********
      if ($key == "user_confirm") {
        $obdata->setpostback($userId,$key);
        $data['replyToken'] = $replyToken;
        $data['messages'][0]['type'] = "text";
        $data['messages'][0]['text'] = "กรุณากรอก Username ของคุณ";
      }
      //postback repast
      else if ($key == "repast") {
        $req->save_repast($userId,$value);
        $ms_repast = [
        'type' => 'text',
        'text' => 'คุณทานอะไรใน'. $value];
        $data['replyToken'] = $replyToken;
        $data['messages'][0] = $ms_repast;
      }
      // postback food_selected
      else if ($key == "food_selected") {
        $req->save_food($userId,$value);
        // get_unit
        $unittext = $searchfood->get_unit($value);
        $ms_food = [
        'type' => 'template',
        'altText' => 'จำนวน',
        'template' => array(
          'type' => 'buttons',
          'title' => ' ',
          'text' => 'จำนวนกี่'.$unittext,
          'actions' => array(
            array(
              'type' => 'postback',
              'label' => '1',
              'data' => 'num_food:1')
            ,array(
              'type' => 'postback',
              'label' => '2',
              'data' => 'num_food:2')
            ,array(
              'type' => 'postback',
              'label' => '3',
              'data' => 'num_food:3')
            )
          )
        ];
        $data['replyToken'] = $replyToken;
        $data['messages'][0] = $ms_food;
      }
      //postback num_food
      else if ($key == "num_food") {
        $req->save_unit($userId,$value);
        // get data from Req_manage
        $food = $req->get_food($userId);
        $unit = $value;
        $unittext = $searchfood->get_unit($food);
        $calorie = $searchfood->get_calorie($food);
        $caloriesum = $unit * $calorie;

        $messagess = [
        "type"=> "template",
        "altText"=> "ยืนยันรายการ",
        "template"=> array(
          "type"=> "confirm",
          "text"=> ''.$food.' '.$unit.' '.$unittext.' เท่ากับ '.$caloriesum.' กิโลแคลอรี่
          ยืนยันบันทึกรายการนี้',
          "actions"=> array(
            array(
              "type"=> "postback",
              "label"=> "ยืนยัน",
              'data' => 'confirm_food:ยืนยัน'),
            array(
              "type"=> "postback",
              "label"=> "ยกเลิก",
              'data' => 'no_confirm_food:ยกเลิก')
            )
          )
        ];
        $data['replyToken'] = $replyToken;
        $data['messages'][0] = $messagess;
      }
      // no_confirm_food
      else if ($key == "no_confirm_food") {
        $req->delete_req($userId);

        $ms = [
        'type' => 'text',
        'text' => 'ยกเลิก และออกจากเมนูการบันทึกแล้ว'];
        $data['replyToken'] = $replyToken;
        $data['messages'][0] = $ms;
      }
      // confirm_food
      else if ($key == "confirm_food") {
        // get data from Req_manage
        $repast = $req->get_repast($userId);
        $food = $req->get_food($userId);
        $unit = intval($req->get_unit($userId));
        $unittext = $searchfood->get_unit($food);
        $calorie = $searchfood->get_calorie($food);
        $caloriesum = $unit * $calorie;

        // if to eng repast     Breakfast  Lunch  Dinner  Supper

        // get userId
        $get_userId = $user->get_userId($userId);
        // get dialyId
        $get_food_dialyId = $food_dialy->check_food_dialy($get_userId,date('Y-m-d'));
        // save food_dialy list
        $food_dialy->save_food_dialy_list($get_food_dialyId,$food,$unit,$caloriesum,$repast);

        $ms = [
        'type' => 'template',
        'altText' => 'บันทึกรายการอาหารแล้ว',
        'template' => array(
          'type' => 'buttons',
          'title' => 'บันทึกรายการอาหารแล้ว',
          'text' => 'ต้องการบันทึกเพิ่มเติมหรือไม่',
          'actions' => array(
            array(
              'type' => 'postback',
              'label' => 'เพิ่มอีก',
              'data' => 'more:'.$repast.'')
            ,array(
              'type' => 'postback',
              'label' => 'พอแล้ว',
              'data' => 'enough:'.$repast.'')
            )
          )
        ];
        $data['replyToken'] = $replyToken;
        $data['messages'][0] = $ms;

        // delete request
        $req->delete_req($userId);
      }

      // more save
      else if ($key == "more") {
        $req->save_repast($userId,$value);

        $ms_repast = [
        'type' => 'text',
        'text' => 'คุณทานอะไรเพิ่มอีกใน'. $value];
        $data['replyToken'] = $replyToken;
        $data['messages'][0] = $ms_repast;
      }
      // enough save
      else if ($key == "enough") {
        // summary
        // get userId
        $get_userId = $user->get_userId($userId);
        // get dialyId
        $get_food_dialyId = $food_dialy->check_food_dialy($get_userId,date('Y-m-d'));

        // get repast calorie
        $calorie_repast = $food_dialy->get_repast_calorie($get_food_dialyId,$value);
        // get summary calorie
        $calorie_all = $food_dialy->get_all_calorie($get_food_dialyId);
        // update summary calorie
        $food_dialy->update_total_calorie($get_food_dialyId,$calorie_all);

        $ms_summary = [
        'type' => 'text',
        'text' => 'สรุปรายการของ'.$value.'
        พลังงานรวมที่ได้รับ
        เท่ากับ '.$calorie_repast.' กิโลแคลอรี่

        ออกจากเมนูการบันทึกแล้ว'];
        $data['replyToken'] = $replyToken;
        $data['messages'][0] = $ms_summary;
      }
      // -----------------------------------------------------------------------



    }
    // =============================================================================


    // start action by postback messages ===========================================
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


        // =============================================================================
        // check user maping id
        $checkuser = $user->get_userId($userId);
        if ($checkuser == "null") {

          if ($obdata->getpostback($userId) == "user_confirm") {
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
              'text' => 'กรุณาเลือก',
              'actions' => array(
                array(
                  'type' => 'uri',
                  'label' => 'สมัครบัญชีใหม',
                  'uri' => 'https://arcane-sands-19975.herokuapp.com/web/register.php')
                ,
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
        // =============================================================================

        // start system for user in sytem
        else {

          // key_word cutting to action *****************************


          //  select menu    ****************************************
          // $keyword = explode(" ", "เมนู");
          // $check = check($keyword,$text);
          // if ($check == "true") {
          if ($text == "เมนู") {
            $displayname = $user->get_displayname($userId);
// '.$displayname.'
            $ms_menu = [
            'type' => 'template',
            'altText' => 'เมนูการใช้งาน',
            'template' => array(
              'type' => 'buttons',
              'title' => 'เมนูการใช้งาน',
              'text' => 'สวัสดี
              เลือกเมนูที่ต้องการ',
              'actions' => array(
                array(
                  'type' => 'messages',
                  'label' => 'บันทึกมื้ออาหาร',
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
            // $messages = [
            // 'type' => "text",
            // 'text' => "ใช่ เมนู"];
            $messages = [
            'type' => 'template',
            'altText' => 'บันทึกมื้ออาหาร',
            'template' => array(
              'type' => 'buttons',
              'title' => 'บันทึกมื้ออาหาร',
              'text' => 'เลือกมื้ออาหารที่ต้องการ ',
              'actions' => array(
                array(
                  'type' => 'postback',
                  'label' => 'มื้อเช้า',
                  'data' => 'repast:มื้อเช้า')
                ,array(
                  'type' => 'postback',
                  'label' => 'มื้อเที่ยง',
                  'data' => 'repast:มื้อเที่ยง')
                ,array(
                  'type' => 'postback',
                  'label' => 'มื้อเย็น',
                  'data' => 'repast:มื้อเย็น')
                ,array(
                  'type' => 'postback',
                  'label' => 'ระหว่างมื้อ',
                  'data' => 'repast:ระหว่างมื้อ')
                )
              )
            ];

            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $messages;

          }

          // ***********  Food_save  ************************************************************************************
          // select repast
          else if ($text == "บันทึกมื้ออาหาร") {
            // check userId
            $get_userId = $user->get_userId($userId);
            // check date
            $check_food_dialy = $food_dialy->check_food_dialy($get_userId,date('Y-m-d'));
            //
            if ($check_food_dialy == "null") {
                // if null => create food_dialy
              $food_dialy->save_food_dialy($get_userId,date('Y-m-d'));
            }

            $save_dialy = [
            'type' => 'template',
            'altText' => 'บันทึกมื้ออาหาร',
            'template' => array(
              'type' => 'buttons',
              'title' => 'บันทึกมื้ออาหาร',
              'text' => 'เลือกมื้ออาหารที่ต้องการ ',
              'actions' => array(
                array(
                  'type' => 'postback',
                  'label' => 'มื้อเช้า',
                  'data' => 'repast:มื้อเช้า')
                ,array(
                  'type' => 'postback',
                  'label' => 'มื้อเที่ยง',
                  'data' => 'repast:มื้อเที่ยง')
                ,array(
                  'type' => 'postback',
                  'label' => 'มื้อเย็น',
                  'data' => 'repast:มื้อเย็น')
                ,array(
                  'type' => 'postback',
                  'label' => 'ระหว่างมื้อ',
                  'data' => 'repast:ระหว่างมื้อ')
                )
              )
            ];

            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $save_dialy;
          }

          // search for save
          // show list food by name
          else if ((($search = $searchfood->searchfood_forsave($text)) != "null") && ($req->get_repast($userId) != "null--")) {

            $ms_array = array();
            $ms_array = $search;

            if (count($ms_array) == 1) {
              $data['replyToken'] = $replyToken;
              $data['messages'][0] = $ms_array[0];
            }elseif (count($ms_array) == 2) {
              $data['replyToken'] = $replyToken;
              $data['messages'][0] = $ms_array[0];
              $data['messages'][1] = $ms_array[1];
            }elseif (count($ms_array) == 3) {
              $data['replyToken'] = $replyToken;
              $data['messages'][0] = $ms_array[0];
              $data['messages'][1] = $ms_array[1];
              $data['messages'][2] = $ms_array[2];
            }

          }



          else if($text == "สวัสดี"){
            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $ms;

          }else if($text == "ชื่ออะไร"){
            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $ms;

          }else if($text == "หลายอัน"){
            $data['replyToken'] = $replyToken;
            $data['messages'][0]['type'] = "text";
            $data['messages'][0]['text'] = "ฉันทำอะไรไม่ได้เลย คุณต้องสอนฉันอีกเยอะ";
          }else{

            $messages = [
            'type' => "text",
            'text' => "ขอโทษ ฉันไม่เข้าใจ"];

            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $messages;
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

$word = "เมนูใช้งาน";
$keyword = explode(" ", "เมนู");
check($keyword,$word);
function check($keyword,$word)
{

  $segment = new Segment();
  $result = $segment->get_segment_array($word);
  $mapper = 0;
  // loop mapping algorithm
  for ($i=0; $i < count($result); $i++) {
    if ($mapper < count($keyword)) {
      if ($result[$i] == $keyword[$mapper]) {
        $mapper++;
      }
    }
  }
  // check mapper
  if ($mapper == count($keyword)) {
    echo '<br>'.$word;
    return "true";
  }else {
    echo '<br> false';
    return "false";
  }
}

?>
