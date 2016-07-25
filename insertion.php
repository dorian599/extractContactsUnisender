<?php

function insertion($_file_name){

    $file_name = $_file_name;

    $db='db_name';
    $user='user_name';
    $passwd='password';
    $host='host';

    $conn = mysql_connect($host, $user, $passwd);

    if(! $conn ){
        die('Could not connect: ' . mysql_error());
    }

    mysql_select_db( $db );

    if (($handle = fopen($file_name, "r")) !== FALSE) {
        while (($value = fgetcsv($handle, 1000, "|")) !== FALSE) {

            $sql = "INSERT IGNORE INTO unisender VALUES('".$value[0]."','".$value[1]."','".$value[2]."','".$value[3]."','".$value[4]."','".$value[5]."','".$value[6]."','".$value[7]."','".$value[8]."','".$value[9]."')";

            //echo $sql."\n";
            $retval = mysql_query( $sql, $conn );
        }
        fclose($handle);
    }

    mysql_close($conn);

}

// Unit Test
//
//insertion('contacts-20160723.txt');

insertion( $argv[1] );

?>
