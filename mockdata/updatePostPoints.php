<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $datenbank = 'journalist';
    $res = null;
    

    // Create connection
    mysql_connect($servername, $username, $password);
    mysql_select_db($datenbank);

    
    $sql = "SELECT * FROM `user` where USER_ID = '".$_POST['user']."' AND USER_TOKEN = '".$_POST['token']."'";
    $db_erg = mysql_query( $sql );
    $results = array();
    if ($db_erg) {
        $data = json_decode($_POST['data'], true);
        $sql_valid = "SELECT a.*, ap.ARTICLE_POINT_ID, e.EVENT_ID FROM `article` a 
                            LEFT JOIN `article_point` ap ON ap.USER_ID = ".$_POST['user']." and ap.ARTICLE_ID = a.ARTICLE_ID
                            LEFT JOIN `event` e ON e.SUBJECT_USER_ID = ".$_POST['user']." and e.ARTICLE_ID = a.ARTICLE_ID
                        where a.ARTICLE_ID = ".$data['postId'];
        $db_erg_valid = mysql_query( $sql_valid );
        if ($db_erg_valid) {    
            $_daten = mysql_fetch_array($db_erg_valid, MYSQL_ASSOC);
            if ($_daten['USER_ID'] == $_POST['user']) {
                $new_points = $_daten['ARTICLE_POINTS'];
                if (isset($_daten['ARTICLE_POINT_ID']) and $_daten['ARTICLE_POINT_ID'] != '') {
                    $sql_points = "DELETE FROM `article_point` where ARTICLE_ID = ".$data['postId']." and USER_ID = ".$_POST['user'];
                    $db_erg_points = mysql_query( $sql_points );
                    
                     
                    $sql_post = "UPDATE `article` 
                                SET ARTICLE_POINTS = ".($_daten['ARTICLE_POINTS'] - 1)."
                                where ARTICLE_ID = ".$data['postId'];
                    $new_points = $_daten['ARTICLE_POINTS'] - 1;
                    $db_erg_points = mysql_query( $sql_post );
                    $res['act'] = 'minus';
                    $res['points'] = $new_points;
                }
                     
                else {
                    $sql_points = "INSERT INTO `article_point` (CREATION_USER_IP, ARTICLE_ID, USER_ID) 
                                    VALUES ('".$_SERVER['REMOTE_ADDR']."', ".$data['postId'].", ".$_POST['user'].")";
                    $db_erg_points = mysql_query( $sql_points );
                    
                     
                    $sql_post = "UPDATE `article` 
                                SET ARTICLE_POINTS = ".($_daten['ARTICLE_POINTS'] + 1)."
                                where ARTICLE_ID = ".$data['postId'];
                    $new_points = $_daten['ARTICLE_POINTS'] + 1;
                    $db_erg_points = mysql_query( $sql_post );
                    
                    if (isset($_daten['EVENT_ID']) and $_daten['EVENT_ID'] != '') {}
                    else {
                        $sql_event = "INSERT INTO `event` (EVENT_ART, OBJECT_USER_ID, SUBJECT_USER_ID, ARTICLE_ID) 
                                        VALUES ('DA', ".$_daten['USER_ID'].", ".$_POST['user'].", ".$data['postId'].")";
                        $db_erg_event = mysql_query( $sql_event );
                    }
                    $res['act'] = 'plus';
                    $res['points'] = $new_points;
                }
                $res['answer'] = 'SUCCESS';
            }
            else $res['answer'] = 'USER_FAIL';
        }
    }
    else $res['answer'] = 'DB_FAIL';
    mysql_close();
    echo json_encode($res);
?>