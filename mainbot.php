<?php
//
require_once ('./LINEBot.php');
require_once ('./LINEBotTiny.php');
include 'food.php';
include 'foodsave.php';
//
//
$channelAccessToken = 'uEaFS7lHeCcF0FEBVNQtuBTVpwVzjMCSebgBPdA/XUqgxzpYg8MHySfkmKpKys/TTEvQO99XihXnZaPKVO/4VsQXLqs8LQZdmskXuwncFHyI8/GZjv91J9Q/YN/pmATJTvlp6YOxOBypA2QFg1r6OwdB04t89/1O/w1cDnyilFU=';
$channelSecret = '7250dbd91d435551040866aed3c4b3ef';
//
$client = new LINEBotTiny($channelAccessToken, $channelSecret);
//
$access_token = 'uEaFS7lHeCcF0FEBVNQtuBTVpwVzjMCSebgBPdA/XUqgxzpYg8MHySfkmKpKys/TTEvQO99XihXnZaPKVO/4VsQXLqs8LQZdmskXuwncFHyI8/GZjv91J9Q/YN/pmATJTvlp6YOxOBypA2QFg1r6OwdB04t89/1O/w1cDnyilFU=';
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
//
//

if (!is_null($events['events']))
{

  foreach ($events['events'] as $event)
  {

    if ($event['type'] == 'message'&& $event['message']['type'] == 'text')
    {
                  // Get text sent
      $text = $event['message']['text'];
                  // Get replyToken
      $replyToken = $event['replyToken'];
      //

      // condition to class food check
      $checkfood = new FoodCheck;





      //  select menu    ****************************************
      if ($text == "เมนู" || $text == "พอแล้ว") {
        $ms1 = [
        'type' => 'text',
        'text' => 'เมนูการใช้งาน
        1.บันทึกมื้ออาหาร
        2.ค้นหาข้อมูลอาหาร
        3.ค้นหาข้อมูลการออกกำลังกาย
        4.ดูข้อมูลผู้ใช้'];

        $ms2 = [
        'type' => 'template',
        'altText' => 'เลือกเมนูการใช้งาน',
        'template' => array(
          'type' => 'buttons',
          'title' => 'เมนูการใช้งาน',
          'text' => 'เลือกเมนูที่ต้องการ',
          'actions' => array(
            array(
              'type' => 'postback',
              'label' => '1',
              'data' => 'save_dialy',
              'text' => 'บันทึกมื้ออาหาร')
            ,array(
              'type' => 'postback',
              'label' => '2',
              'data' => 'search_food',
              'text' => 'ค้นหาข้อมูลอาหาร')
            ,array(
              'type' => 'postback',
              'label' => '3',
              'data' => 'search_exercise',
              'text' => 'ค้นหาข้อมูลการออกกำลังกาย')
            ,array(
              'type' => 'postback',
              'label' => '4',
              'data' => 'get_profile',
              'text' => 'ดูข้อมูลผู้ใช้')
            )
          )
        ];

        // send
        $client->replyMessage(
          array(
            'replyToken' => $event['replyToken'],
            'messages' => [$ms1,$ms2]
            )
          );
      }

      // select repast
      else if ($text == "บันทึกมื้ออาหาร") {

        // create food_dialy
        $food_dialy = new Food_save;
        $food_dialy->save_food_dialy("2",date('Y-m-d'));

        $save_dialy = [
        'type' => 'template',
        'altText' => 'OK บันทึกมื้ออาหาร',
        'template' => array(

          'type' => 'buttons',
          'title' => 'OK บันทึกมื้ออาหาร',
          'text' => 'เลือกมื้ออาหารที่ต้องการ',
          'actions' => array(
            array(
              'type' => 'postback',
              'label' => 'มื้อเช้า',
              'data' => 'มื้อเช้า',
              'text' => 'มื้อเช้า')
            ,array(
              'type' => 'postback',
              'label' => 'มื้อเที่ยง',
              'data' => 'มื้อเที่ยง',
              'text' => 'มื้อเที่ยง')
            ,array(
              'type' => 'postback',
              'label' => 'มื้อเย็น',
              'data' => 'มื้อเย็น',
              'text' => 'มื้อเย็น')
            ,array(
              'type' => 'postback',
              'label' => 'ระหว่างมื้อ',
              'data' => 'ระหว่างมื้อ',
              'text' => 'ระหว่างมื้อ')
            )
          )
        ];
        $client->replyMessage(
          array(
            'replyToken' => $event['replyToken'],
            'messages' => [$save_dialy]
            )
          );
      }

      //มื้อเช้า   ------------------------------------
      else if ($text == "มื้อเช้า") {
        $ms_repast = [
        'type' => 'text',
        'text' => 'คุณทานอะไรมา'];

        $client->replyMessage(
          array(
            'replyToken' => $event['replyToken'],
            'messages' => [$ms_repast]
            )
          );
      }


      else if ($checkfood->check_food($text) == "food") {
        $ms_food = [
        'type' => 'text',
        'text' => 'จำนวนเท่าไหร่ (จาน)'];

        $client->replyMessage(
          array(
            'replyToken' => $event['replyToken'],
            'messages' => [$ms_food]
            )
          );
      }

      else if ($checkfood->check_num($text) == "ok") {

        // save food_dialy list
        $food_dialy = new Food_save;
        $food_dialy->save_food_dialy_list("15","ข้าวขาหมู","1","breakfast");

        $ms_food = [
        'type' => 'text',
        'text' => 'ข้าวขาหมู 1 จาน เท่ากับ 690 กิโลแคลอรี่'];

        $ms_num = [
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
              'data' => 'เพิ่มอีก',
              'text' => 'เพิ่มอีก')
            ,array(
              'type' => 'postback',
              'label' => 'พอแล้ว',
              'data' => 'พอแล้ว',
              'text' => 'พอแล้ว')
            )
          )
        ];

        $client->replyMessage(
          array(
            'replyToken' => $event['replyToken'],
            'messages' => [$ms_food,$ms_num]
            )
          );
      }

      // else if ($text == "พอแล้ว") {
      //   $ms_con = [
      //   'type' => 'text',
      //   'text' => ''];
      //
      //   $client->replyMessage(
      //     array(
      //       'replyToken' => $event['replyToken'],
      //       'messages' => [$ms_con]
      //       )
      //     );
      // }


      // ********************************************************************************


      //
      //
      // other
      else {
        $messages = [
        'type' => 'text',
        'text' => 'ขอโทษ ฉันไม่เข้าใจ'];
      }
      /*-------------------------------------------  Make a POST  -----------------------------------------*/

            // Make a POST Request to Messaging API to reply to sender
      $url = 'https://api.line.me/v2/bot/message/reply';
      $data = [
      'replyToken' => $replyToken,
      'messages' => [$messages]
      ];
      $post = json_encode($data);
      $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      $result = curl_exec($ch);
      curl_close($ch);

      echo $result . "\r\n";
// //
    }
  }
}

//
//
echo "cal 2 OK <br>";
echo date('Y-m-d');
?>
