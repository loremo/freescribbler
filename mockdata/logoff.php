<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $datenbank = 'journalist';
    $res = null;

    // Create connection
    mysql_connect($servername, $username, $password);
    mysql_select_db($datenbank);

    
    $sql = "SELECT * FROM `user` where USER_NAME = '".$_POST['userId']."' AND USER_TOKEN = '".$_POST['sessionToken']."'";
    $db_erg = mysql_query( $sql );
    if ($db_erg) {
        $_daten = mysql_fetch_array($db_erg, MYSQL_ASSOC);
        if ($_daten) {
            $sql_update = "UPDATE `user` SET USER_TOKEN = NULL WHERE USER_NAME = '".$_POST['user']."'";
            if (mysql_query( $sql_update )) {
                $res['answer'] = 'LOGOFF';
            }
            else $res['answer'] = 'TECH_FAIL';
        }
        else $res['answer'] = 'USER_FAIL';
    }
    else $res['answer'] = 'DB_FAIL';

    echo json_encode($res);
?>