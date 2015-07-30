<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $datenbank = 'journalist';
    $res_pic = null;
    $results_pic = Array();

    // Create connection
    mysql_connect($servername, $username, $password);
    mysql_select_db($datenbank);

    
    $art = 0;
    if ($_POST['target']) $art = $_POST['target']; 
    $sql = "SELECT * FROM `user` where USER_ID = '".$_POST['user']."' AND USER_TOKEN = '".$_POST['token']."'";
    $db_erg = mysql_query( $sql );
    $results = array();
    if ($db_erg) {
        $_daten = mysql_fetch_array($db_erg, MYSQL_ASSOC);
        if ($_daten) {
            $sql_pics = "
            SELECT 
                p.*
            FROM 
                `article_media` m 
                INNER JOIN `pics` p ON m.MEDIA_ID = p.PIC_ID 
            WHERE 
                m.ARTICLE_ID = ".$art." AND p.IS_ACTIVE = 1
            ORDER BY 
                m.ARTICLE_MEDIA_RELATION_ID";
            $db_erg_pics = mysql_query( $sql_pics );
            
            if ($db_erg_pics) {
                $rows_pics = Array();
                while($row_pics = mysql_fetch_array($db_erg_pics, MYSQL_ASSOC)) $rows_pics[] = $row_pics;
                foreach($rows_pics as $row_pics) {
                    $res_pic['picName'] = $row_pics['PIC_NAME'];
                    $res_pic['picWidth'] = $row_pics['PIC_WIDTH'];
                    $res_pic['picHeight'] = $row_pics['PIC_HEIGHT'];
                    $results_pic[] = $res_pic;
                }
            }
            else $res_pic['answer'] = 'TECH_FAIL';
        }
        else $res_pic['answer'] = 'USER_FAIL';
    }
    else $res_res['answer'] = 'DB_FAIL';
    mysql_close();
    echo json_encode($results_pic);
?>