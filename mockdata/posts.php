<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $datenbank = 'journalist';
    $res = null;
    

    // Create connection
    mysql_connect($servername, $username, $password);
    mysql_select_db($datenbank);

    
    $art = $_POST['user'];
    if (isset($_POST['target'])) $art = $_POST['target']; 
    $sql = "SELECT * FROM `user` where USER_ID = '".$_POST['user']."' AND USER_TOKEN = '".$_POST['token']."'";
    $db_erg = mysql_query( $sql );
    $result = null;
    $posts = array();
    if ($db_erg) {
        $_daten = mysql_fetch_array($db_erg, MYSQL_ASSOC);
        if ($_daten) {
            $sql_target = "
                    SELECT 
                        u.USER_NAME, 
                        u.USER_ID,
                        a.ARTICLE_ID,
                        a.ARTICLE_CONTENT,
                        a.ARTICLE_TIMESTAMP,
                        a.CREATION_USER_IP,
                        a.VALIDATION_FLAG,
                        a.ARTICLE_POINTS, 
                        count(c.comment_id) as COMMENT_NUM,
                        case
                            when p.ARTICLE_POINT_ID is null then 0
                            else 1
                        end as IS_LIKED
                    FROM 
                        `article` a 
                        INNER JOIN `user` u ON a.USER_ID = u.USER_ID 
                        LEFT JOIN `comments` c ON a.ARTICLE_ID = c.ARTICLE_ID
                        LEFT JOIN `article_point` p ON (a.ARTICLE_ID = p.ARTICLE_ID and p.USER_ID = ".$_POST['user'].")
                    WHERE 
                        u.USER_ID = ".$art." and
                        a.ARTICLE_ID < ".$_POST['offsetPostId']."
                    GROUP BY
                    	u.USER_NAME, 
                        u.USER_ID,
                        a.ARTICLE_ID,
                        a.ARTICLE_CONTENT,
                        a.ARTICLE_TIMESTAMP,
                        a.CREATION_USER_IP,
                        a.VALIDATION_FLAG,
                        a.ARTICLE_POINTS,
                        case
                            when p.ARTICLE_POINT_ID is null then 0
                            else 1
                        end
                    ORDER BY 
                        a.ARTICLE_ID desc
                    LIMIT ".$_POST['postsLimit'];
            $db_erg_target = mysql_query( $sql_target );
            if ($db_erg_target) {
                while($row = mysql_fetch_array($db_erg_target, MYSQL_ASSOC)) $rows[] = $row;
                if (isset($rows)) foreach($rows as $row) {
                    if(isset($row['USER_ID']) && glob('img/'.$row['USER_ID'].'.*') != null)
                        $a = glob('img/'.$row['USER_ID'].'.*');
                    else
                        $a = glob('img/nopic.jpg');
                    
                    $format = '';
                    $art_date = new DateTime($row['ARTICLE_TIMESTAMP']);
                    $formated_date = null;
                    if ($art_date->diff(new DateTime())->days >=7) {
                        $formated_date = floor($art_date->diff(new DateTime())->days / 7);
                        if ($formated_date % 10 == 1) $format = 'неделю';
                        elseif (floor($formated_date / 10) != 1 && ($formated_date % 10 == 2 || $formated_date % 10 == 3 || $formated_date % 10 == 4)) $format = 'недели';
                        else $format = 'недель';
                    } elseif ($art_date->diff(new DateTime())->days >=1) {
                        $formated_date = $art_date->diff(new DateTime())->days;
                        if ($formated_date % 10 == 1) $format = 'день';
                        elseif (floor($formated_date / 10) != 1 && ($formated_date % 10 == 2 || $formated_date % 10 == 3 || $formated_date % 10 == 4)) $format = 'дня';
                        else $format = 'дней';
                    } elseif ($art_date->diff(new DateTime())->h >=1) {
                        $formated_date = $art_date->diff(new DateTime())->h;
                        if ($formated_date % 10 == 1) $format = 'час';
                        elseif (floor($formated_date / 10) != 1 && ($formated_date % 10 == 2 || $formated_date % 10 == 3 || $formated_date % 10 == 4)) $format = 'часа';
                        else $format = 'часов';
                    } elseif ($art_date->diff(new DateTime())->i >=1) {
                        $formated_date = $art_date->diff(new DateTime())->i;
                        if ($formated_date % 10 == 1) $format = 'минуту';
                        elseif (floor($formated_date / 10) != 1 && ($formated_date % 10 == 2 || $formated_date % 10 == 3 || $formated_date % 10 == 4)) $format = 'минуты';
                        else $format = 'минут';
                    } else {
                        $formated_date = $art_date->diff(new DateTime())->s;
                        if ($formated_date % 10 == 1) $format = 'секунду';
                        elseif (floor($formated_date / 10) != 1 && ($formated_date % 10 == 2 || $formated_date % 10 == 3 || $formated_date % 10 == 4)) $format = 'секунды';
                        else $format = 'секунд';
                    }
                    
                    $res['avatar'] = 'mockdata/'.$a[0];
                    $res['userId'] = $row['USER_ID'];
                    $res['userName'] = $row['USER_NAME'];
                    $res['postId'] = $row['ARTICLE_ID'];
                    $res['postContent'] = $row['ARTICLE_CONTENT'];
                    $res['postTime'] = $formated_date.' '.$format.' назад';
                    $res['commentNum'] = $row['COMMENT_NUM'];
                    $res['postLikes'] = $row['ARTICLE_POINTS'];
                    $res['postLiked'] = $row['IS_LIKED'];
                    
                    $sql_pics = "
                            SELECT 
                                p.*
                            FROM 
                                `article_media` m 
                                INNER JOIN `pics` p ON m.MEDIA_ID = p.PIC_ID 
                            WHERE 
                                m.ARTICLE_ID = ".$row['ARTICLE_ID']." AND p.IS_ACTIVE = 1
                            ORDER BY 
                                m.ARTICLE_MEDIA_RELATION_ID";
                    $db_erg_pics = mysql_query( $sql_pics );

                    if ($db_erg_pics) {
                        $rows_pics = Array();
                        $results_pic = Array();
                        while($row_pics = mysql_fetch_array($db_erg_pics, MYSQL_ASSOC)) $rows_pics[] = $row_pics;
                        foreach($rows_pics as $row_pics) {
                            $res_pic['picName'] = $row_pics['PIC_NAME'];
                            $res_pic['picWidth'] = $row_pics['PIC_WIDTH'];
                            $res_pic['picHeight'] = $row_pics['PIC_HEIGHT'];
                            $results_pic[] = $res_pic;
                        }
                        $res['pics'] = $results_pic;
                    }
                    
                    $posts[] = $res;
                }
                $result['posts'] = $posts;
                $result['offsetPostId'] = $res['postId'];
            }
            else $res['answer'] = 'TECH_FAIL';
        }
        else $res['answer'] = 'USER_FAIL';
    }
    else $res['answer'] = 'DB_FAIL';
    mysql_close();
    echo json_encode($result);
?>