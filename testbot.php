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
include 'cal_notifications.php';
include 'show_healthyfood.php';
// include_once 'THSplitLib/segment.php';


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
$wordcut = new Wordcut;

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
      if ($text_type[0] == "savefood") {
        // savefood:repast:food_selected:num_food:confirm_food
        $key = $text_type[0];
        $repast = $text_type[1];
        $food_selected = $text_type[2];
        $num_food = $text_type[3];
        $confirm_food = $text_type[4];
      }
      $key = $text_type[0];
      $value = $text_type[1];

      // user_confirm **************************************
      if ($key == "user_confirm") {
        $obdata->setpostback($userId,$key);
        $data['replyToken'] = $replyToken;
        $data['messages'][0]['type'] = "text";
        $data['messages'][0]['text'] = "กรุณากรอก Username ของคุณ";
      }

      // ---------------------------------------------------
      //postback repast
      else if (($key == "savefood") && ($repast != "null") && ($food_selected == "null")) {
        $req->save_repast($userId,$repast);
        $ms_repast = [
        'type' => 'text',
        'text' => 'คุณทานอะไรใน'. $repast];
        $data['replyToken'] = $replyToken;
        $data['messages'][0] = $ms_repast;
      }
      // postback food_selected
      else if (($key == "savefood") && ($food_selected != "null") && ($num_food == "null")) {
        // $req->save_food($userId,$food_selected);//--
        // get_unit
        $unittext = $searchfood->get_unit($food_selected);
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
              'data' => 'savefood:'.$repast.':'.$food_selected.':1:null')
            ,array(
              'type' => 'postback',
              'label' => '2',
              'data' => 'savefood:'.$repast.':'.$food_selected.':2:null')
            ,array(
              'type' => 'postback',
              'label' => '3',
              'data' => 'savefood:'.$repast.':'.$food_selected.':3:null')
            )
          )
        ];
        $data['replyToken'] = $replyToken;
        $data['messages'][0] = $ms_food;
      }
      //postback num_food
      else if (($key == "savefood") && ($num_food != "null") && ($confirm_food == "null")) {
        // $req->save_unit($userId,$num_food); //--
        // get data from Req_manage
        // $food = $req->get_food($userId);
        $unit = intval($num_food);
        $unittext = $searchfood->get_unit($food_selected);
        $calorie = $searchfood->get_calorie($food_selected);
        $caloriesum = $unit * $calorie;

        $messagess = [
        "type"=> "template",
        "altText"=> "ยืนยันรายการ",
        "template"=> array(
          "type"=> "confirm",
          "text"=> ''.$food_selected.' '.$unit.' '.$unittext.' เท่ากับ '.$caloriesum.' กิโลแคลอรี่
          ยืนยันบันทึกรายการนี้',
          "actions"=> array(
            array(
              "type"=> "postback",
              "label"=> "ยืนยัน",
              'data' => 'savefood:'.$repast.':'.$food_selected.':'.$num_food.':confirm_food'),
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
      else if ($confirm_food == "confirm_food") {
        // get data from Req_manage
        $reqrepast = $req->get_repast($userId);
        // $food = $req->get_food($userId);
        // $unit = intval($req->get_unit($userId));
        $unit = intval($num_food);
        $unittext = $searchfood->get_unit($food_selected);
        $calorie = $searchfood->get_calorie($food_selected);
        $caloriesum = $unit * $calorie;

        // if to eng repast     Breakfast  Lunch  Dinner  Supper

        // get userId
        $get_userId = $user->get_userId($userId);
        // get dialyId
        $get_food_dialyId = $food_dialy->check_food_dialy($get_userId,date('Y-m-d'));
        // save food_dialy list
        $food_dialy->save_food_dialy_list($get_food_dialyId,$food,$unit,$caloriesum,$reqrepast);
        // get summary calorie
        $calorie_all = $food_dialy->get_all_calorie($get_food_dialyId);
        // update summary calorie
        $food_dialy->update_total_calorie($get_food_dialyId,$calorie_all);

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
              'data' => 'more:'.$reqrepast.'')
            ,array(
              'type' => 'postback',
              'label' => 'พอแล้ว',
              'data' => 'enough:'.$reqrepast.'')
            )
          )
        ];
        $data['replyToken'] = $replyToken;
        $data['messages'][0] = $ms;

        // delete request
        // $req->delete_req($userId);
      }

      // more save
      else if ($key == "more") {
        // $req->save_repast($userId,$value);//--

        $ms_repast = [
        'type' => 'text',
        'text' => 'คุณทานอะไรเพิ่มอีกใน'. $value];
        $data['replyToken'] = $replyToken;
        $data['messages'][0] = $ms_repast;
      }
      // enough save
      else if ($key == "enough") {
        // delete request
        $req->delete_req($userId);
        // summary
        // get userId
        $get_userId = $user->get_userId($userId);
        // get dialyId
        $get_food_dialyId = $food_dialy->check_food_dialy($get_userId,date('Y-m-d'));

        // get repast calorie
        $calorie_repast = $food_dialy->get_repast_calorie($get_food_dialyId,$value);
        // // get summary calorie
        // $calorie_all = $food_dialy->get_all_calorie($get_food_dialyId);
        // // update summary calorie
        // $food_dialy->update_total_calorie($get_food_dialyId,$calorie_all);

        $ms_summary = [
        'type' => 'text',
        'text' => 'สรุปรายการของ'.$value.'
        พลังงานรวมที่ได้รับ
        เท่ากับ '.$calorie_repast.' กิโลแคลอรี่

        ออกจากเมนูการบันทึกแล้ว'];
        $data['replyToken'] = $replyToken;
        $data['messages'][0] = $ms_summary;


        // *********   check over caloriesum to notifications ************************************************************************************************
        //check caloriesum
        $checkCal = new CalNotif;
        // $checkCal->checkOverCal($get_userId);
        $check_Cal = explode(':',$checkCal->checkOverCal($get_userId));
        $result = $check_Cal[0];
        $total_calorie = $check_Cal[1];
        $tdee = $check_Cal[2];

        if ($result == "over") {
          $neg_cal = $total_calorie - $tdee;
          $notify = [
          'type' => 'template',
          'altText' => 'แคลอรี่เกินกำหนดแล้ว',
          'template' => array(
            'type' => 'buttons',
            'title' => 'แคลอรี่เกินกำหนดแล้วนะ',
            'text' => $neg_cal.'
            เราขอเสนอสิ่งที่ช่วยให้ดีขึ้นได้',
            'actions' => array(
              array(
                'type' => 'postback',
                'label' => 'อาหารสุขภาพที่ใช่',
                'data' => 'healthyfood:'.$neg_cal)
              ,array(
                'type' => 'postback',
                'label' => 'การออกกำลังกายที่เหมาะ',
                'data' => 'healthyex:'.$neg_cal)
              )
            )
          ];
          $push = new Push;
          $pushdata = array();
          $pushdata['to'] = $userId;
          $pushdata['messages'][0] = $notify;

          $push->push_message($pushdata,$strAccessToken);
        }

        // ***************************************************************************************************************************************************

      }


      // show healthyfood
      else if (($key = "healthyfood") && ($healthyfood->get_healthyfood_by_cal($value) != "null")) {
        $healthyfood = new HealthyFood;
        $ms_array = array();
        $ms_array = $healthyfood->get_healthyfood_by_cal($value);
        if (count($ms_array) == 1) {
          $data['replyToken'] = $replyToken;
          $data['messages'][0] = $ms_array[0];
        }

      }

      // show exercise
      else if (($key = "healthyex") && ($searchexercise->searchexercise_bycalorie($value) != "null")) {
          $ms_array = array();
          $ms_array = $searchexercise->searchexercise_bycalorie($value);
          if (count($ms_array) == 1) {
            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $ms_array[0];
          }

      }


      // -----------------------------------------------------------------------


      // show list exercise by type ++++++++++++++++++++++++
      else if ($key = "ecallvl") {
        $ms_array = array();
        $ms_array = $searchexercise->searchexercise_bytype($value);
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

      // search food by name ++++++++++++++++++++++++++++++++++
      // else if ($key == "searchfood_byname") {
      //   // $obdata->setpostback($userId,$key);
      //   $ms_foodname = [
      //   'type' => 'text',
      //   'text' => 'บอกชื่ออาหารที่ต้องการ'];
      //
      //   $data['replyToken'] = $replyToken;
      //   $data['messages'][0] = $ms_foodname;
      // }

      // search food by calorie ++++++++++++++++++++++++++++++++++
      // else if ($key == "searchfood_bycalorie") {
      //   // $obdata->setpostback($userId,$key);
      //   $ms_foodcalorie = [
      //   'type' => 'text',
      //   'text' => 'บอกปริมาณพลังงานสูงสุดที่ต้องการ'];
      //   $data['replyToken'] = $replyToken;
      //   $data['messages'][0] = $ms_foodcalorie;
      // }


      // show list food by type
      // else if ($key == "foodtype") {
      //   $search = $searchfood->searchfood_bytype($value);
      //   $ms_array = array();
      //   $ms_array = $search;
      //   if (count($ms_array) == 1) {
      //     $data['replyToken'] = $replyToken;
      //     $data['messages'][0] = $ms_array[0];
      //   }elseif (count($ms_array) == 2) {
      //     $data['replyToken'] = $replyToken;
      //     $data['messages'][0] = $ms_array[0];
      //     $data['messages'][1] = $ms_array[1];
      //   }elseif (count($ms_array) == 3) {
      //     $data['replyToken'] = $replyToken;
      //     $data['messages'][0] = $ms_array[0];
      //     $data['messages'][1] = $ms_array[1];
      //     $data['messages'][2] = $ms_array[2];
      //   }
      // }


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

            $messages1 = [
            'type' => 'template',
            'altText' => 'เมนูการใช้งาน',
            'template' => array(
              'type' => 'buttons',
              'title' => 'เมนูการใช้งาน',
              'text' => 'สวัสดี '.$displayname.'
              เมนูการใช้งาน',
              'actions' => array(
                array(
                  'type' => 'message',
                  'label' => 'บันทึกมื้ออาหาร',
                  'text' => 'บันทึกมื้ออาหาร')
                ,array(
                  'type' => 'message',
                  'label' => 'ข้อมูลอาหาร',
                  'text' => 'ค้นหาข้อมูลอาหาร')
                ,array(
                  'type' => 'message',
                  'label' => 'ข้อมูลออกกำลังกาย',
                  'text' => 'ค้นหาข้อมูลการออกกำลังกาย')
                )
              )
            ];

            $messages2 = [
            'type' => 'template',
            'altText' => 'เมนูเพิ่มเติม',
            'template' => array(
              'type' => 'buttons',
              'title' => 'เมนูเพิ่มเติม',
              'text' => ' ',
              'actions' => array(
                array(
                  'type' => 'message',
                  'label' => 'ดูข้อมูลผู้ใช้',
                  'text' => 'ดูข้อมูลผู้ใช้')
                ,array(
                  'type' => 'uri',
                  'label' => 'เข้าเว็บไซต์',
                  'uri' => 'http://example.com/page/123')
                )
              )
            ];

            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $messages1;
            $data['messages'][1] = $messages2;

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
                  'data' => 'savefood:มื้อเช้า:null:null:null')
                ,array(
                  'type' => 'postback',
                  'label' => 'มื้อเที่ยง',
                  'data' => 'savefood:มื้อเที่ยง:null:null:null')
                ,array(
                  'type' => 'postback',
                  'label' => 'มื้อเย็น',
                  'data' => 'savefood:มื้อเย็น:null:null:null')
                ,array(
                  'type' => 'postback',
                  'label' => 'ระหว่างมื้อ',
                  'data' => 'savefood:ระหว่างมื้อ:null:null:null')
                )
              )
            ];

            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $save_dialy;

          }

          // search for save
          // show list food by name
          // else if ((($search = $searchfood->searchfood_forsave($text)) != "null") && ($req->get_repast($userId) != "null--")) {
          else if (($getrepast = $req->get_repast($userId) != "null") && (($search = $searchfood->searchfood_forsave($text,$getrepast)) != "null")) {

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
          // -------------------------------------------------------------------------

          // ************  search food *****************************************************************************************************
          // search_food
          else if ($text == "ค้นหาข้อมูลอาหาร") {
              // //delete
            $obdata->deletepostback($userId);

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
                  'data' => 'searchfood_byname:1',
                  'text' => 'ค้นหาโดยชื่ออาหาร')
                ,array(
                  'type' => 'postback',
                  'label' => 'ค้นหาโดยปริมาณพลังงาน',
                  'data' => 'searchfood_bycalorie:1',
                  'text' => 'ค้นหาโดยปริมาณพลังงาน')
                ,array(
                  'type' => 'postback',
                  'label' => 'ค้นหาโดยชนิดอาหาร',
                  'data' => 'searchfood_bytype:1',
                  'text' => 'ค้นหาโดยชนิดอาหาร')
                )
              )
            ];

            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $ms_menu_search;
          }
          // search food by name ++++++++++++++++++++++++++++++++++
          else if ($text == "ค้นหาโดยชื่ออาหาร") {
            $obdata->setpostback($userId,"searchfood_byname");
              $ms_foodname = [
              'type' => 'text',
              'text' => 'บอกชื่ออาหารที่ต้องการ'];

              $data['replyToken'] = $replyToken;
              $data['messages'][0] = $ms_foodname;
          }
          // show list food by name
          else if (($obdata->getpostback($userId) == "searchfood_byname") && (($search = $searchfood->searchfood_byname($text)) != "null")) {
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
              // //delete
            $obdata->deletepostback($userId);

          }

          // search food by calorie ++++++++++++++++++++++++++++++++++
          else if ($text == "ค้นหาโดยปริมาณพลังงาน") {
            $obdata->setpostback($userId,"searchfood_bycalorie");
              $ms_foodcalorie = [
              'type' => 'text',
              'text' => 'บอกปริมาณพลังงานสูงสุดที่ต้องการ'];

              $data['replyToken'] = $replyToken;
              $data['messages'][0] = $ms_foodcalorie;
          }
          // show list food by calorie
          else if (($obdata->getpostback($userId) == "searchfood_bycalorie") && (($search = $searchfood->searchfood_bycalorie($text)) != "null")) {
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
            // //delete
            $obdata->deletepostback($userId);
          }

          // search food by type ++++++++++++++++++++++++++++++++++
          else if ($text == "ค้นหาโดยชนิดอาหาร") {
            $ms_search_type = [
            'type' => 'template',
            'altText' => 'ค้นหาข้อมูลอาหาร',
            'template' => array(
              'type' => 'buttons',
              'title' => 'ค้นหาข้อมูลอาหาร',
              'text' => 'ต้องการค้นหาประเภทใด',
              'actions' => array(
                array(
                  'type' => 'message',
                  'label' => 'อาหารคาว',
                  'text' => 'อาหารคาว')
                ,array(
                  'type' => 'message',
                  'label' => 'อาหารหวาน',
                  'text' => 'อาหารหวาน')
                  ,array(
                    'type' => 'message',
                    'label' => 'ผลไม้',
                    'text' => 'ผลไม้')
                ,array(
                  'type' => 'message',
                  'label' => 'เครื่องดื่ม',
                  'text' => 'เครื่องดื่ม')
                )
              )
            ];
            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $ms_search_type;
          }
          // show list food by type
          // else if (($obdata->getpostback($userId) == "searchfood_bytype") && (($search = $searchfood->searchfood_bytype($text)) != "null")) {
          else if (($search = $searchfood->searchfood_bytype($text)) != "null") {
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


          // -------------------------------------------------------------------------

          // ค้นหาข้อมูลการออกกำลังกาย
          // ************  search exercise *****************************************************************************************************
          else if ($text == "ค้นหาข้อมูลการออกกำลังกาย") {
            $ms_menu_search = [
            'type' => 'template',
            'altText' => 'ค้นหาข้อมูลการออกกำลังกาย',
            'template' => array(
              'type' => 'buttons',
              'title' => 'ค้นหาข้อมูลการออกกำลังกาย',
              'text' => 'ต้องการค้นหาแบบใด',
              'actions' => array(
                array(
                  'type' => 'message',
                  'label' => 'พลังงานที่เผาพลาญ',
                  'text' => 'พลังงานที่เผาพลาญ')
                ,array(
                  'type' => 'message',
                  'label' => 'ชนิดการออกกำลังกาย',
                  'text' => 'ชนิดการออกกำลังกาย')
                )
              )
            ];

            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $ms_menu_search;
          }
          // search exercise by calorie ++++++++++++++++++++++++++++++++++
          else if ($text == "พลังงานที่เผาพลาญ") {
            $ms_foodcalorie = [
            'type' => 'text',
            'text' => 'บอกปริมาณพลังงานสูงสุดที่ต้องการ'];

            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $ms_foodcalorie;
          }
          // show list exercise by calorie
          else if (($text_type[0] == "สูงสุด") && ($searchexercise->searchexercise_bycalorie($text_type[1]) != "null")) {

            $ms_array = array();
            $ms_array = $searchexercise->searchexercise_bycalorie($text_type[1]);

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
              $data['messages'][3] = $ms_array[3];
            }

          }

          //
          // search exercise by type ++++++++++++++++++++++++++++++++++
          else if ($text == "ชนิดการออกกำลังกาย") {

            $ms_menu_search = [
            'type' => 'template',
            'altText' => 'เลือกชนิดการออกกำลังกาย',
            'template' => array(
              'type' => 'buttons',
              'title' => 'เลือกชนิดการออกกำลังกาย',
              'text' => 'กรุณาเลือก',
              'actions' => array(
                array(
                  'type' => 'postback',
                  'label' => 'ระดับเบาๆ',
                  'data' => 'ecallvl:Low')
                ,array(
                  'type' => 'postback',
                  'label' => 'ระดับปานกลาง',
                  'data' => 'ecallvl:Moderate')
                ,array(
                  'type' => 'postback',
                  'label' => 'ระดับหนักหน่วง',
                  'data' => 'ecallvl:High')
                )
              )
            ];

            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $ms_menu_search;
          }
          // ---------------------------------------------------------------



          else if($text == "สวัสดี"){
            $messages = [
            'type' => "text",
            'text' => "สวัสดี ฉันคือ Cal.MBot ผู้ช่วยให้ข้อมูลและบันทึกข้อมูลเกี่ยวกับอาหารและการออกกำลังกาย
            คุณสามารถเรียนเมนูการใช้งานได้โดยพิมพ์คำว่า 'เมนู' "];
            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $messages;

          }else if($text == "ดูข้อมูลผู้ใช้"){
            $ms_profile = $user->get_profile($userId);
            $data['replyToken'] = $replyToken;
            $data['messages'][0] = $ms_profile;

          }else  if ($text == "over") {
              $neg_cal = 1 - 0;
              $notify = [
              'type' => 'template',
              'altText' => 'แคลอรี่เกินกำหนดแล้ว',
              'template' => array(
                'type' => 'buttons',
                'title' => 'แคลอรี่เกินกำหนดแล้วนะ',
                'text' => '
                เราขอเสนอสิ่งที่ช่วยให้ดีขึ้นได้',
                'actions' => array(
                  array(
                    'type' => 'postback',
                    'label' => 'อาหารสุขภาพที่ใช่',
                    'data' => 'healthyfood:'.$neg_cal)
                  ,array(
                    'type' => 'postback',
                    'label' => 'การออกกำลังกายที่เหมาะ',
                    'data' => 'healthyex:'.$neg_cal)
                  )
                )
              ];
              $push = new Push;
              $pushdata = array();
              $pushdata['to'] = $userId;
              $pushdata['messages'][0] = $notify;

              $push->push_message($pushdata,$strAccessToken);


          }else{
            $messages = [
            'type' => "text",
            'text' => "ขอโทษ ฉันไม่เข้าใจ
            คุณสามารถเรียนเมนูการใช้งานได้โดยพิมพ์คำว่า 'เมนู'"];
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

$word = "มันใช่หรอ เมนูใช้งาน";
$keyword = explode(" ", "เมนู อาหาร");
$wc = new Wordcut;
if ($wc->check($keyword,$word) == "true") {
  echo "เข้าเมนู";
}else {
  $a = array("ไม่เข้าใจ","อะไรหรอ","ว่าไงนะ");
  echo "<br><br>".$a[array_rand($a,1)];
}


?>
