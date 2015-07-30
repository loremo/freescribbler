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
        $sql_valid = "SELECT * FROM `article` where ARTICLE_ID = ".$data['postId'];
        $db_erg_valid = mysql_query( $sql_valid );
        if ($db_erg_valid) {    
            $_daten = mysql_fetch_array($db_erg_valid, MYSQL_ASSOC);
            if ($_daten['USER_ID'] == $_POST['user']) {
                $sql_points = "DELETE FROM `article_point` where ARTICLE_ID = ".$data['postId'];
                $db_erg_points = mysql_query( $sql_points );

                $sql_hashtags = "DELETE FROM `hashtag_article` where ARTICLE_ID = ".$data['postId'];
                $db_erg_hashtags = mysql_query( $sql_hashtags );
                
                $sql_comments = "DELETE FROM `comments` where ARTICLE_ID = ".$data['postId'];
                $db_erg_comments = mysql_query( $sql_comments );
                
                $sql_pics = "DELETE FROM `article_media` where ARTICLE_ID = ".$data['postId'];
                $db_erg_pics = mysql_query( $sql_pics );
                
                $sql_article = "DELETE FROM `article` where ARTICLE_ID = ".$data['postId'];
                $db_erg_article = mysql_query( $sql_article );
                $res['answer'] = 'SUCCESS';
            }
            else $res['answer'] = 'USER_FAIL';
        }
    }
    else $res['answer'] = 'DB_FAIL';
    mysql_close();
    echo json_encode($res);
?>