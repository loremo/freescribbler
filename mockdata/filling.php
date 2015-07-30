<?php

    $db_servername = 'localhost';
    $db_username = 'artem';
    $db_password = 'i9l0XNvj_2';
    $datenbank = 'journalist';
    $result = Array();
    $appcode = '123123123';
    $success = false;

    $db_connection = new mysqli($db_servername, $db_username, $db_password, $datenbank);
    
    if ($db_connection->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    else {
        echo 'JA!';
    }

    $sql_target = '
            SELECT 
                a.*,
                u.USER_NAME,
                u.USER_PRIVACY
            FROM 
                `article` a,
                `user` u
            WHERE 
               a.USER_ID = u.USER_ID and a.USER_NAME IS NULL and a.ARTICLE_ID >= 90000 and a.ARTICLE_ID < 100000';
    
    $db_target_result = $db_connection->query( $sql_target );
    echo $db_target_result->num_rows;
    if ($db_target_result && $db_target_result->num_rows > 0) {
        while($row = $db_target_result->fetch_object()) $rows[] = $row;
        if (isset($rows)) foreach($rows as $row) {
            $sql_update = "UPDATE `article` SET USER_NAME = '".$row->USER_NAME."', USER_PRIVACY = ".$row->USER_PRIVACY." WHERE ARTICLE_ID = ".$row->ARTICLE_ID;
            $db_pics_result = $db_connection->query( $sql_update );
            
        }
        else {
            echo 'NEIN2';
        }
    }
    else {
        echo 'NEIN1';
    }
?>