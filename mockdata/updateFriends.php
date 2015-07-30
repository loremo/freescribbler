<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $datenbank = 'journalist';
    $res = null;

    // Create connection
    mysql_connect($servername, $username, $password);
    mysql_select_db($datenbank);

    $users = Array();
    $sql = "SELECT * FROM `user` where USER_ID = '".$_POST['user']."' AND USER_TOKEN = '".$_POST['token']."'";
    $db_erg = mysql_query( $sql );
    if (isset($_POST['target']) && $db_erg) {
        $art = $_POST['target']; 
        $_daten = mysql_fetch_array($db_erg, MYSQL_ASSOC);
        if ($_daten) {
            $sql_target = "
                        SELECT 
                            *
                        FROM `friends` f WHERE f.USER_ID = ".$_POST['user']." AND f.REFERENCED_USER_ID = ".$art;
            
            $db_erg_target = mysql_query( $sql_target );
            if ($db_erg_target) {
                $row = mysql_fetch_array($db_erg_target, MYSQL_ASSOC);
                if ($row) {
                    $sql_delete = "
                        DELETE FROM `friends` WHERE USER_ID = ".$_POST['user']." AND REFERENCED_USER_ID = ".$art;
                    $db_erg_delete = mysql_query( $sql_delete );
                    if ($db_erg_delete) {
                        $result['success'] = true;
                        $result['ergebnis'] = 0;
                    }
                    else $result['success'] = false;
                }
                else {
                    $sql_insert = "
                        INSERT INTO `friends` (USER_ID, REFERENCED_USER_ID) VALUES (".$_POST['user'].", ".$art.")";
                    $db_erg_insert = mysql_query( $sql_insert );
                    if ($db_erg_insert) {
                        $result['success'] = true;
                        $result['ergebnis'] = 1;
                    }
                    else $result['success'] = false;
                }
            }
            else $result['answer'] = 'TECH_FAIL';
        }
        else $result['answer'] = 'USER_FAIL';
    }
    else $result['answer'] = 'DB_FAIL';
    mysql_close();
    echo json_encode($result);
?>