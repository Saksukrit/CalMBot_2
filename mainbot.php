<?php
date_default_timezone_set('Asia/Bangkok');
//
// include 'operate.php';
require_once ('./LINEBot.php');
require_once ('./LINEBotTiny.php');
// require_once ('./LINEBot/HTTPClient/CurlHTTPClient.php');
// require_once ('./LINEBot/MessageBuilder/TextMessageBuilder.php');
include 'food_check.php';
include 'food_save.php';
include 'user.php';
include 'search_food.php';
include 'search_exercise.php';
include 'postback.php';
include 'req_manage.php';
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
//Postback
$obdata = new Postback;
//
if (!is_null($events['events']))
{
    
    foreach ($events['events'] as $event)
    {
        // get save postback
        if ($event['type'] == 'postback') {
            
            $datapostback = $event['postback']['data'];
            $userIdpostback = $event['source']['userId'];
            $obdata->setpostback($userIdpostback,$datapostback);
            
        }
        if (($event['type'] == 'message')&& $event['message']['type'] == 'text')
        {
            // Get text sent
            $text = $event['message']['text'];
            // Get replyToken
            $replyToken = $event['replyToken'];
            
            $userId = $event['source']['userId'];
            
            //
            $text_type = explode(' ', $text);
            $confirm_food = explode(' ',$obdata->getpostback($userId));
            
            // condition to class food check
            $user = new User;
            // $checkfood = new FoodCheck;
            $food_dialy = new Food_save;
            $searchfood = new Searchfood;
            $searchexercise = new Searchexercise;
            $req = new Req_manage;
            
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
            
            // user
            else
            {
                //  select menu    ****************************************
                if ($text == "เมนู") {
                    // || $text == "พอแล้ว"
                    $displayname = $user->get_displayname($userId);
                    
                    // $ms1 = [
                    // 'type' => 'text',
                    // 'text' => 'สวัสดี '.$displayname.'
                    // เมนูการใช้งาน
                    // 1.บันทึกมื้ออาหาร
                    // 2.ค้นหาข้อมูลอาหาร
                    // 3.ค้นหาข้อมูลการออกกำลังกาย
                    // 4.ดูข้อมูลผู้ใช้'];
                    
                    $ms2 = [
                    'type' => 'template',
                    'altText' => 'เมนูการใช้งาน',
                    'template' => array(
                    'type' => 'buttons',
                    'title' => 'เมนูการใช้งาน',
                    'text' => 'สวัสดี '.$displayname.'
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
                    
                    // send
                    $client->replyMessage(
                    array(
                    'replyToken' => $event['replyToken'],
                    'messages' => [$ms2]
                    )
                    );
                }
                
                // ***********  Food_save  ************************************************************************************
                // select repast
                else if ($obdata->getpostback($userId) == "save_dialy") {
                    // else if ($text == "บันทึกมื้ออาหาร") {
                    // check userId
                    $get_userId = $user->get_userId($userId);
                    // check date
                    $check_food_dialy = $food_dialy->check_food_dialy($get_userId,date('Y-m-d'));
                    //
                    if ($check_food_dialy == "null") {
                        // if null => create food_dialy
                        $food_dialy->save_food_dialy($get_userId,date('Y-m-d'));
                    }
                    
                    
                    //delete
                    $obdata->deletepostback($userId);
                    
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
                    'data' => 'repast',
                    'text' => 'มื้อเช้า')
                    ,array(
                    'type' => 'postback',
                    'label' => 'มื้อเที่ยง',
                    'data' => 'repast',
                    'text' => 'มื้อเที่ยง')
                    ,array(
                    'type' => 'postback',
                    'label' => 'มื้อเย็น',
                    'data' => 'repast',
                    'text' => 'มื้อเย็น')
                    ,array(
                    'type' => 'postback',
                    'label' => 'ระหว่างมื้อ',
                    'data' => 'repast',
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
                //postback repast
                else if ($obdata->getpostback($userId) == "repast") {
                    
                    $req->save_repast($userId,$text);
                    
                    $ms_repast = [
                    'type' => 'text',
                    'text' => 'คุณทานอะไรมา'. $text];
                    
                    $client->replyMessage(
                    array(
                    'replyToken' => $event['replyToken'],
                    'messages' => [$ms_repast]
                    )
                    );
                    
                    
                    // change postback
                    $obdata->changepostback($userId,'food');
                    // //delete
                    // $obdata->deletepostback($userId);
                    
                }
                
                // search for save
                // show list food by name
                else if (($searchfood->searchfood_forsave($text) != "null") && ($obdata->getpostback($userId) == "food")) {
                    
                    $ms_array = array();
                    $ms_array = $searchfood->searchfood_forsave($text);
                    
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
                    // //delete
                    $obdata->deletepostback($userId);
                    
                }
                
                // number of foods
                else if ($obdata->getpostback($userId) == "food_selected") {
                    //
                    $req->save_food($userId,$text);
                    // $food = $req->get_food($userId);
                    $unittext = $searchfood->get_unit($text);
                    
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
                    'data' => 'num_food',
                    'text' => '1')
                    ,array(
                    'type' => 'postback',
                    'label' => '2',
                    'data' => 'num_food',
                    'text' => '2')
                    ,array(
                    'type' => 'postback',
                    'label' => '3',
                    'data' => 'num_food',
                    'text' => '3')
                    )
                    )
                    ];
                    
                    $client->replyMessage(
                    array(
                    'replyToken' => $event['replyToken'],
                    'messages' => [$ms_food]
                    )
                    );
                    
                    // //delete
                    $obdata->deletepostback($userId);
                    
                }
                
                // confirm save
                else if ($obdata->getpostback($userId) == "num_food") {
                    $req->save_unit($userId,$text);
                    // get data from Req_manage
                    $food = $req->get_food($userId);
                    $unit = intval($text);
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
                    'data' => 'confirm_food',
                    "text"=> "ยืนยัน"
                    ),
                    array(
                    "type"=> "postback",
                    "label"=> "ยกเลิก",
                    'data' => 'no_confirm_food',
                    "text"=> "ยกเลิก"
                    )
                    )
                    )
                    ];
                    $client->replyMessage(
                    array(
                    'replyToken' => $event['replyToken'],
                    'messages' => [$messagess]
                    )
                    );
                    // //delete
                    $obdata->deletepostback($userId);
                }
                
                // no_confirm_food
                else if ($obdata->getpostback($userId) == "no_confirm_food") {
                    // delete req
                    $req->delete_req($userId);
                    //delete
                    $obdata->deletepostback($userId);
                    
                    $ms = [
                    'type' => 'text',
                    'text' => 'ยกเลิก ออกจากเมนูการบันทึกแล้ว'];
                    
                    $client->replyMessage(
                    array(
                    'replyToken' => $event['replyToken'],
                    'messages' => [$ms]
                    )
                    );
                }
                
                // confirm_food
                else if ($obdata->getpostback($userId) == "confirm_food") {
                    
                    // get data from Req_manage
                    $food = $req->get_food($userId);
                    $unit = intval($req->get_unit($userId));
                    $unittext = $searchfood->get_unit($food);
                    $calorie = $searchfood->get_calorie($food);
                    $caloriesum = $unit * $calorie;
                    
                    $repast = $req->get_repast($userId);
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
                    'data' => 'more '.$repast.'',
                    'text' => 'เพิ่มอีก')
                    ,array(
                    'type' => 'postback',
                    'label' => 'พอแล้ว',
                    'data' => 'enough '.$repast.'',
                    'text' => 'พอแล้ว')
                    )
                    )
                    ];
                    
                    $client->replyMessage(
                    array(
                    'replyToken' => $event['replyToken'],
                    'messages' => [$ms]
                    )
                    );
                    
                    // $total_calorie;
                    // update
                    // $food_dialy->update_total_calorie($get_food_dialyId,$total_calorie);
                    //delete
                    $obdata->deletepostback($userId);
                    // delete request
                    $req->delete_req($userId);
                }
                
                // more save
                else if ($confirm_food[0] == "more") {
                    //delete
                    $obdata->deletepostback($userId);
                    
                    $req->save_repast($userId,$confirm_food[1]);
                    
                    $ms_repast = [
                    'type' => 'text',
                    'text' => 'คุณทานอะไรเพิ่มอีก'. $text];
                    
                    $client->replyMessage(
                    array(
                    'replyToken' => $event['replyToken'],
                    'messages' => [$ms_repast]
                    )
                    );
                    
                    // set postback
                    $obdata->setpostback($userId,'food');
                    // change postback
                    // $obdata->changepostback($userId,'food');
                    
                }
                
                // enough save
                else if ($confirm_food[0] == "enough") {
                    
                    // delete req
                    // $req->delete_req($userId);
                    //delete
                    $obdata->deletepostback($userId);
                    // สรุปรายการ
                    // get userId
                    $get_userId = $user->get_userId($userId);
                    // get dialyId
                    $get_food_dialyId = $food_dialy->check_food_dialy($get_userId,date('Y-m-d'));
                    
                    // get repast calorie
                    $calorie_repast = $food_dialy->get_repast_calorie($get_food_dialyId,$confirm_food[1]);
                    // get summary calorie
                    $calorie = $food_dialy->get_all_calorie($get_food_dialyId);
                    // update summary calorie
                    $food_dialy->update_total_calorie($get_food_dialyId,$calorie);
                    
                    $ms_summary = [
                    'type' => 'text',
                    'text' => 'สรุปรายการของ'.$confirm_food[1].'
                    พลังงานรวมที่ได้รับเท่ากับ'.$calorie_repast.'
                    
                    ออกจากเมนูการบันทึกแล้ว'];
                    
                    // $ms = [
                    // 'type' => 'text',
                    // 'text' => 'ออกจากเมนูการบันทึกแล้ว'];
                    
                    $client->replyMessage(
                    array(
                    'replyToken' => $event['replyToken'],
                    'messages' => [$ms_summary]
                    )
                    );
                }
                
                // -----------------------------------------------------------------------------------------------------------------------------
                
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
                    'data' => 'searchfood_byname',
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
                else if ($obdata->getpostback($userId) == "searchfood_byname") {
                    // change postback
                    $obdata->changepostback($userId,'foodname_to_search');
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
                else if (($obdata->getpostback($userId) == "foodname_to_search") && ($searchfood->searchfood_byname($text) != "null")) {
                    
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
                    // //delete
                    $obdata->deletepostback($userId);
                    
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
                    // //delete
                    $obdata->deletepostback($userId);
                    
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
                    // //delete
                    $obdata->deletepostback($userId);
                    
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
                    // //delete
                    $obdata->deletepostback($userId);
                    
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
                // ค้นหาข้อมูลการออกกำลังกาย
                // ************  search exercise *****************************************************************************************************
                else if ($text == "ค้นหาข้อมูลการออกกำลังกาย") {
                    // //delete
                    $obdata->deletepostback($userId);
                    
                    $ms_menu_search = [
                    'type' => 'template',
                    'altText' => 'ค้นหาข้อมูลการออกกำลังกาย',
                    'template' => array(
                    'type' => 'buttons',
                    'title' => 'ค้นหาข้อมูลการออกกำลังกาย',
                    'text' => 'ต้องการค้นหาแบบใด',
                    'actions' => array(
                    array(
                    'type' => 'postback',
                    'label' => 'พลังงานที่เผาพลาญ',
                    'data' => 'พลังงานที่เผาพลาญ',
                    'text' => 'พลังงานที่เผาพลาญ')
                    ,array(
                    'type' => 'postback',
                    'label' => 'ชนิดการออกกำลังกาย',
                    'data' => 'ชนิดการออกกำลังกาย',
                    'text' => 'ชนิดการออกกำลังกาย')
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
                
                // search exercise by calorie ++++++++++++++++++++++++++++++++++
                else if ($text == "พลังงานที่เผาพลาญ") {
                    // //delete
                    $obdata->deletepostback($userId);
                    
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
                // show list exercise by calorie
                else if (($text_type[0] == "สูงสุด") && ($searchexercise->searchexercise_bycalorie($text_type[1]) != "null")) {
                    
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
                
                //
                // search exercise by type ++++++++++++++++++++++++++++++++++
                else if ($text == "ชนิดการออกกำลังกาย") {
                    // //delete
                    $obdata->deletepostback($userId);
                    
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
                    'label' => 'Low',
                    'data' => 'Low',
                    'text' => 'Low')
                    ,array(
                    'type' => 'postback',
                    'label' => 'Moderate',
                    'data' => 'Moderate',
                    'text' => 'Moderate')
                    ,array(
                    'type' => 'postback',
                    'label' => 'High',
                    'data' => 'High',
                    'text' => 'High')
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
                // show list exercise by calorie
                else if ($searchexercise->searchexercise_bytype($text) != "null") {
                    // //delete
                    $obdata->deletepostback($userId);
                    
                    $ms_array = array();
                    $ms_array = $searchexercise->searchexercise_bytype($text);
                    
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
                
                // ดูข้อมูลผู้ใช้
                // get profile
                else if ($text == "ดูข้อมูลผู้ใช้") {
                    
                    $ms_profile = $user->get_profile($userId);
                    //delete
                    $obdata->deletepostback($userId);
                    
                    $client->replyMessage(
                    array(
                    'replyToken' => $event['replyToken'],
                    'messages' => [$ms_profile]
                    )
                    );
                }
                
                
                // -----------------------------------------------------------------------------------------------------------------------------
                
                // else if ($text == "เพิ่มอีก") {
                //     $ms_con = [
                //     'type' => 'text',
                //     'text' => 'ยังไม่สามารถ'];
                
                //     $client->replyMessage(
                //     array(
                //     'replyToken' => $event['replyToken'],
                //     'messages' => [$ms_con]
                //     )
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