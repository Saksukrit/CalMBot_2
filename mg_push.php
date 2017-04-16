<?php

/**
 *
 */
class Push
{
  public function push_message($pushdata,$strAccessToken)
  {
    $strUrlpush = "https://api.line.me/v2/bot/message/push";

    // Header
        $arrHeader = array();
        $arrHeader[] = "Content-Type: application/json";
        $arrHeader[] = "Authorization: Bearer {$strAccessToken}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$strUrlpush);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($pushdata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close ($ch);

  }


}


 ?>
