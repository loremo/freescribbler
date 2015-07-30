<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $datenbank = 'journalist';
    $res = null;

    // Create connection
    mysql_connect($servername, $username, $password);
    mysql_select_db($datenbank);
    
    $sql = "SELECT * FROM `user` where USER_NAME = '".$_POST['user']."'";
    $db_erg = mysql_query( $sql );
    if ($db_erg) {
        $_daten = mysql_fetch_array($db_erg, MYSQL_ASSOC);
        if ($_daten) {
            if ($_daten['USER_PASSWORD'] === md5($_daten['USER_SALT'].$_POST['pwd'])) {
                $token = md5(uniqid(mt_rand(), true));
                $sql_update = "UPDATE `user` SET USER_TOKEN = '".$token."' WHERE USER_NAME = '".$_POST['user']."'";
                if (mysql_query( $sql_update )) {
                    $db_erg_2 = mysql_query( $sql );
                    $_daten = mysql_fetch_array($db_erg_2, MYSQL_ASSOC);
                    if ($_daten) {
                        $res['userId'] = $_daten['USER_ID'];
                        $res['token'] = $_daten['USER_TOKEN'];
                        $res['userName'] = $_daten['USER_NAME'];
                        $res['answer'] = 'LOGIN';
                    }
                }
                else $res['answer'] = 'TECH_FAIL';
            }
            else $res['answer'] = 'PWD_FAIL';
        }
        else $res['answer'] = 'USER_FAIL';
    }
    else $res['answer'] = 'DB_FAIL';

    echo json_encode($res);
?>