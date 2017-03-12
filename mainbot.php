<?php
date_default_timezone_set('Asia/Bangkok');
//
// include 'operate.php';
require_once ('./LINEBot.php');
require_once ('./LINEBotTiny.php');
// require_once ('./LINEBot/HTTPClient/CurlHTTPClient.php');
// require_once ('./LINEBot/MessageBuilder/TextMessageBuilder.php');
include 'food.php';
include 'foodsave.php';
include 'user.php';
include 'search_food.php';
include 'search_exercise.php';
// use LINE\LINEBot\HTTPClient;
// use LINE\LINEBot\MessageBuilder;
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
//

if (!is_null($events['events']))
{

  foreach ($events['events'] as $event)
  {
// $data = null;
// test postback
if ($event['type'] == 'postback') {

  $data = $event['postback']['data'];
  $ms = [
  'type' => 'text',
  'text' => 'test postback : '.$data.''];

  $client->replyMessage(
    array(
      'replyToken' => $event['replyToken'],
      'messages' => [$ms]
      )
    );
}
    if (($event['type'] == 'message')&& $event['message']['type'] == 'text')
    {
                  // Get text sent
      $text = $event['message']['text'];
                  // Get replyToken
      $replyToken = $event['replyToken'];

      $userId = $event['source']['userId'];
      //
      // $data = null;
      $data = $event['postback']['data'];
      // if (!is_null($event['postback']['data'])) {
      //   $data = $event['postback']['data'];
      // }

      //
      $text_type = explode(' ', $text);


      // condition to class food check
      $checkfood = new FoodCheck;
      $user = new User;
      $food_dialy = new Food_save;
      $searchfood = new Searchfood;
      $searchexercise = new Searchexercise;

      // check user maping id
      $checkuser = $user->get_userId($userId);
      if ($checkuser == "null") {
        $ms = [
        'type' => 'text',
        'text' => 'สวัสดี คุณคือใคร
        กรุณายืนยันตัวตนด้วย Username ของคุณ'];

        $client->replyMessage(
          array(
            'replyToken' => $event['replyToken'],
            'messages' => [$ms]
            )
          );
      }

      else{
        //  select menu    ****************************************
       if ($text == "เมนู" || $text == "พอแล้ว") {
        $displayname = $user->get_displayname($userId);

        $ms1 = [
        'type' => 'text',
        'text' => 'สวัสดี '.$displayname.'
        เมนูการใช้งาน
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
        'altText' => 'OK บันทึกมื้ออาหาร',
        'template' => array(

          'type' => 'buttons',
          'title' => 'OK บันทึกมื้ออาหาร',
          'text' => 'เลือกมื้ออาหารที่ต้องการ'.$data.'',
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

      // number of foods
      else if ($checkfood->check_food($text) == "food") {
        $ms_food = [
        'type' => 'template',
        'altText' => 'จำนวนเท่าไหร่',
        'template' => array(
          'type' => 'buttons',
          'title' => ' ',
          'text' => 'จำนวนเท่าไหร่',
          'actions' => array(
            array(
              'type' => 'postback',
              'label' => '1',
              'data' => '1',
              'text' => '1')
            ,array(
              'type' => 'postback',
              'label' => '2',
              'data' => '2',
              'text' => '2')
            ,array(
              'type' => 'postback',
              'label' => '3',
              'data' => '3',
              'text' => '3')
            ,array(
              'type' => 'postback',
              'label' => '4',
              'data' => '4',
              'text' => '4')
            )
          )
        ];

        $client->replyMessage(
          array(
            'replyToken' => $event['replyToken'],
            'messages' => [$ms_food]
            )
          );
      }

      else if ($checkfood->check_num($text) == "ok") {
        // get data from Req_manage

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
      // -----------------------------------------------------------------------------------------------------------------------------

      // ************  search food *****************************************************************************************************
      else if ($text == "ค้นหาข้อมูลอาหาร") {
        $ms_menu_search = [
        'type' => 'template',
        'altText' => 'ค้นหาข้อมูลอาหาร',
        'template' => array(
          'type' => 'buttons',
          'title' => 'ค้นหาข้อมูลอาหาร',
          'text' => 'ต้องการค้นหาแบบใด',
          'actions' => array(
            array(
              'type' => 'postback',
              'label' => 'ค้นหาโดยชื่ออาหาร',
              'data' => 'ค้นหาโดยชื่ออาหาร',
              'text' => 'ค้นหาโดยชื่ออาหาร')
            ,array(
              'type' => 'postback',
              'label' => 'ค้นหาโดยปริมาณพลังงาน',
              'data' => 'ค้นหาโดยปริมาณพลังงาน',
              'text' => 'ค้นหาโดยปริมาณพลังงาน')
            ,array(
              'type' => 'postback',
              'label' => 'ค้นหาโดยชนิดอาหาร',
              'data' => 'ค้นหาโดยชนิดอาหาร',
              'text' => 'ค้นหาโดยชนิดอาหาร')
            )
          )
        ];

        $client->replyMessage(
          array(
            'replyToken' => $event['replyToken'],
            'messages' => [$ms_menu_search]
            )
          );
      }

      // search food by name ++++++++++++++++++++++++++++++++++
      else if ($text == "ค้นหาโดยชื่ออาหาร") {
        $ms_foodname = [
        'type' => 'text',
        'text' => 'บอกชื่ออาหารที่ต้องการ'];

        $client->replyMessage(
          array(
            'replyToken' => $event['replyToken'],
            'messages' => [$ms_foodname]
            )
          );
      }

      // show list food by name
      else if ($searchfood->searchfood_byname($text) != "null") {

        $ms_array = array();
        $ms_array = $searchfood->searchfood_byname($text);

        if (count($ms_array) == 1) {
          $client->replyMessage(
            array(
              'replyToken' => $event['replyToken'],
              'messages' => [$ms_array[0]]
              )
            );
        }elseif (count($ms_array) == 2) {
          $client->replyMessage(
            array(
              'replyToken' => $event['replyToken'],
              'messages' => [$ms_array[0],$ms_array[1]]
              )
            );
        }elseif (count($ms_array) == 3) {
          $client->replyMessage(
            array(
              'replyToken' => $event['replyToken'],
              'messages' => [$ms_array[0],$ms_array[1],$ms_array[2]]
              )
            );
        }

      }

      // search food by calorie ++++++++++++++++++++++++++++++++++
      else if ($text == "ค้นหาโดยปริมาณพลังงาน") {
        $ms_foodcalorie = [
        'type' => 'text',
        'text' => 'บอกปริมาณพลังงานสูงสุดที่ต้องการ'];

        $client->replyMessage(
          array(
            'replyToken' => $event['replyToken'],
            'messages' => [$ms_foodcalorie]
            )
          );
      }
      // show list food by calorie
      else if ($searchfood->searchfood_bycalorie($text) != "null") {

        $ms_array = array();
        $ms_array = $searchfood->searchfood_bycalorie($text);

        if (count($ms_array) == 1) {
          $client->replyMessage(
            array(
              'replyToken' => $event['replyToken'],
              'messages' => [$ms_array[0]]
              )
            );
        }elseif (count($ms_array) == 2) {
          $client->replyMessage(
            array(
              'replyToken' => $event['replyToken'],
              'messages' => [$ms_array[0],$ms_array[1]]
              )
            );
        }elseif (count($ms_array) == 3) {
          $client->replyMessage(
            array(
              'replyToken' => $event['replyToken'],
              'messages' => [$ms_array[0],$ms_array[1],$ms_array[2]]
              )
            );
        }

      }

      // search food by type ++++++++++++++++++++++++++++++++++
      else if ($text == "ค้นหาโดยชนิดอาหาร") {
        $ms_search_type = [
        'type' => 'template',
        'altText' => 'ค้นหาข้อมูลอาหาร',
        'template' => array(
          'type' => 'buttons',
          'title' => 'ค้นหาข้อมูลอาหาร',
          'text' => 'ต้องการค้นหาปรพเภทใด',
          'actions' => array(
            array(
              'type' => 'postback',
              'label' => 'food',
              'data' => 'food',
              'text' => 'food')
            ,array(
              'type' => 'postback',
              'label' => 'dessert',
              'data' => 'dessert',
              'text' => 'dessert')
            ,array(
              'type' => 'postback',
              'label' => 'beverage',
              'data' => 'beverage',
              'text' => 'beverage')
            )
          )
        ];
        $client->replyMessage(
          array(
            'replyToken' => $event['replyToken'],
            'messages' => [$ms_search_type]
            )
          );
      }
      // show list food by type
      else if ($searchfood->searchfood_bytype($text) != "null") {

        $ms_array = array();
        $ms_array = $searchfood->searchfood_bytype($text);

        if (count($ms_array) == 1) {
          $client->replyMessage(
            array(
              'replyToken' => $event['replyToken'],
              'messages' => [$ms_array[0]]
              )
            );
        }elseif (count($ms_array) == 2) {
          $client->replyMessage(
            array(
              'replyToken' => $event['replyToken'],
              'messages' => [$ms_array[0],$ms_array[1]]
              )
            );
        }elseif (count($ms_array) == 3) {
          $client->replyMessage(
            array(
              'replyToken' => $event['replyToken'],
              'messages' => [$ms_array[0],$ms_array[1],$ms_array[2]]
              )
            );
        }

      }

      // -------------------------------------------------------------------------

            // search food by calorie ++++++++++++++++++++++++++++++++++
            else if ($text == "ค้นหาโดยปริมาณพลังงาน") {
              $ms_foodcalorie = [
              'type' => 'text',
              'text' => 'บอกปริมาณพลังงานสูงสุดที่ต้องการ'];

              $client->replyMessage(
                array(
                  'replyToken' => $event['replyToken'],
                  'messages' => [$ms_foodcalorie]
                  )
                );
            }
            // show list food by calorie
            else if (($text_type[0] == "เผาพลาญได้") && ($searchexercise->searchexercise_bycalorie($text_type[1]) != "null")) {

              $ms_array = array();
              $ms_array = $searchexercise->searchexercise_bycalorie($text_type[1]);

              if (count($ms_array) == 1) {
                $client->replyMessage(
                  array(
                    'replyToken' => $event['replyToken'],
                    'messages' => [$ms_array[0]]
                    )
                  );
              }elseif (count($ms_array) == 2) {
                $client->replyMessage(
                  array(
                    'replyToken' => $event['replyToken'],
                    'messages' => [$ms_array[0],$ms_array[1]]
                    )
                  );
              }elseif (count($ms_array) == 3) {
                $client->replyMessage(
                  array(
                    'replyToken' => $event['replyToken'],
                    'messages' => [$ms_array[0],$ms_array[1],$ms_array[2]]
                    )
                  );
              }

            }

      // $messagess = [
      // "type"=> "template",
      // "altText"=> "แคลอรี่ของคุณเกินกำหนดแล้ว
      // แคลอรี่ที่ได้รับตอนนี้เท่ากับ 2450 กิโลแคลอรี่",
      // "template"=> array(
      //   "type"=> "confirm",
      //   "text"=> "แคลอรี่ของคุณเกินกำหนด(TDEE)แล้ว
      //   แคลอรี่ที่ได้รับตอนนี้เท่ากับ 2450 กิโลแคลอรี่
      //   คุณต้องการคำแนะนำเกี่ยวกับอาหารสุขภาพ หรือวิธีการออกกำลังกายมั้ย ?",
      //   "actions"=> array(
      //     array(
      //       "type"=> "message",
      //       "label"=> "ใช่",
      //       "text"=> "ใช่"
      //       ),
      //     array(
      //       "type"=> "message",
      //       "label"=> "ไม่",
      //       "text"=> "ไม่"
      //       )
      //     )
      //   )
      // ];
      // -----------------------------------------------------------------------------------------------------------------------------

      else if ($text == "เพิ่มอีก") {
        $ms_con = [
        'type' => 'text',
        'text' => 'ยังไม่สามารถ'];

        $client->replyMessage(
          array(
            'replyToken' => $event['replyToken'],
            'messages' => [$ms_con]
            )
          );
      }


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
    }
  }
}
}

// //Push message
// if (date('d/m/Y')=="25/02/2017") {
//   $httpClient = new \LINEBot\HTTPClient\CurlHTTPClient('<channel access token>');
//   $bot = new LINEBot($httpClient, ['channelSecret' => '<channel secret>']);
//
//   $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello');
//   $response = $bot->pushMessage('<to>', $textMessageBuilder);
//
//   echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
// }


//
//
echo "cal 2 OK <br>";
echo date('d/m/Y');
?>
