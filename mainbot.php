<?php
//
require_once ('./LINEBot.php');
require_once ('./LINEBotTiny.php');
//
//
$channelAccessToken = 'uEaFS7lHeCcF0FEBVNQtuBTVpwVzjMCSebgBPdA/XUqgxzpYg8MHySfkmKpKys/TTEvQO99XihXnZaPKVO/4VsQXLqs8LQZdmskXuwncFHyI8/GZjv91J9Q/YN/pmATJTvlp6YOxOBypA2QFg1r6OwdB04t89/1O/w1cDnyilFU=';
$channelSecret = '98ca0db8ed81032c7d483cef30bcb190';
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

    if (($event['type'] == 'message')&& ($event['message']['type'] == 'text')) {
                  // Get text sent
      $text = $event['message']['text'];
                  // Get replyToken
      $replyToken = $event['replyToken'];
      //
      //


      $messages = [
      'type' => 'text',
      'text' => 'ด้วยความยินดี'];

      $client->replyMessage(
        array(
          'replyToken' => $event['replyToken'],
          'messages' => [$messages,$messages]
          )
        );

      //
      //
    }
  }
}

//
//
echo "cal 2 OK";
?>
