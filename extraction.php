<?php

function connectUnisender($action,$options){

  // Your key success to API
  $api_key = "API KEY";

  // Create POST request
  $POST = array (
    'api_key' => $api_key,
  );

  if(!empty($options)){
    $POST = array_merge($POST, $options);
  }

  //Establish a connection
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_URL,
              'http://api.unisender.com/ru/api/'.$action.'?format=json');
  $result = curl_exec($ch);
  //print_r($result);

  return $result;

}

function extractContactsHeader($_fileName, $_list_id){

    $fileName = $_fileName;
    $list_id = $_list_id;

    $options = array(
      'list_id' => $list_id,
      'limit' => 1
      ,'field_names[0]' => 'email'
      ,'field_names[1]' => 'Name'
      ,'field_names[2]' => 'email_status'
      ,'field_names[3]' => 'email_availability'
      ,'field_names[4]' => 'email_add_time'
      ,'field_names[5]' => 'email_confirm_time'
      ,'field_names[6]' => 'email_list_ids'
      ,'field_names[7]' => 'email_subscribe_times'
      ,'field_names[8]' => 'email_unsubscribed_list_ids'
      ,'field_names[9]' => 'tags'
    );

    $result = connectUnisender('exportContacts',$options);

    $jsonObj = json_decode($result);

    $line = implode( '|', $jsonObj->result->field_names );
    $data = $line.PHP_EOL;
    $fp = fopen($fileName, 'a');
    fwrite($fp, $data);

}

function extractContacts($_limit, $_offset, $_fileName, $_list_id){

    $limit  = $_limit;
    $offset = $_offset;
    $fileName = $_fileName;
    $list_id = $_list_id;

    $options = array(
      'list_id' => $list_id,
      'limit' => $limit,
      'offset' => $offset
      ,'field_names[0]' => 'email'
      ,'field_names[1]' => 'Name'
      ,'field_names[2]' => 'email_status'
      ,'field_names[3]' => 'email_availability'
      ,'field_names[4]' => 'email_add_time'
      ,'field_names[5]' => 'email_confirm_time'
      ,'field_names[6]' => 'email_list_ids'
      ,'field_names[7]' => 'email_subscribe_times'
      ,'field_names[8]' => 'email_unsubscribed_list_ids'
      ,'field_names[9]' => 'tags'
    );

    do {
      sleep(5);
      $result = connectUnisender('exportContacts',$options);
    } while (!$result);

    $jsonObj = json_decode ($result);
    //echo '<pre>'; print_r( $jsonObj->result ); echo '</pre>'; die();

    foreach ($jsonObj->result->data as $key => $value) {

        $line = implode( '|', $value );

        $data = utf8_decode($line).PHP_EOL;
        $fp = fopen($fileName, 'a');
        fwrite($fp, $data);
    }
}


function listContactCounts($_list_id){

    $list_id = $_list_id;

    $options = array(
      'list_id' => $list_id
    );

    $result = connectUnisender('getListContactCounts',$options);

    $jsonObj = json_decode ($result);

    //echo '<pre>'; print_r( $jsonObj->result->num_total_address ); echo '</pre>';

    return $jsonObj->result->num_total_address;

}

function getEmailLists(){

  $api_key = "API KEY";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL,'http://api.unisender.com/ru/api/getLists?format=json&api_key='.$api_key);
  $result = curl_exec($ch);
  // print_r($result);

  $jsonObj = json_decode($result);

  $listas=[];

  foreach ($jsonObj->result as $key => $value) {
    array_push($listas, $value->id);
  }

  return $listas;

}

function exportContacts($_list_id){
    $limit = 1000 ;
    $offset = 0 ;
    $saveFile = "unisender-contacts.txt";
    $list_id = $_list_id;

    $lc = listContactCounts($list_id);

    $listContactCounts = ( $lc / $limit);
    $laps = round($listContactCounts, 0, PHP_ROUND_HALF_DOWN)+1;

    echo "\n\n";
    echo "### List ID: ".$list_id.", Contacts: ".$lc." ###\n\n";

    //extractContactsHeader( $saveFile, $list_id );

    for ($i=1; $i <= $laps ; $i++) {
        extractContacts( $limit, $offset, $saveFile, $list_id);

        echo "Vuelta: ".$i.", Limite: ".$limit.", Offset: ".$offset."\n";

        $offset = $offset+$limit ;

        sleep(10);
    }

}

function main(){

  $listLoop = getEmailLists();

  foreach ($listLoop as $list) {

    exportContacts($list);

  }

}

main();

?>
