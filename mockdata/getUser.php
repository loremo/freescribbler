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
    if ($db_erg) {
        $_daten = mysql_fetch_array($db_erg, MYSQL_ASSOC);
        if ($_daten) {
            $sql_target = "
                        SELECT 
                            u.USER_ID,
                            u.USER_NAME,
                            u.USER_DESC,
                            u.EARNED,
                            u.USER_PRIVACY,
                            u.USER_MONEY_PRIVACY,
                            case
                                WHEN f1.ME_SUBSCRIBED IS NOT NULL THEN 1
                                ELSE 0
                            end as ME_SUBSCRIBED,
                            case
                                WHEN f2.MY_SUBSCRIBER IS NOT NULL THEN 1
                                ELSE 0
                            end as MY_SUBSCRIBER,
                            case
                                WHEN i1.ME_IGNORED IS NOT NULL THEN 1
                                ELSE 0
                            end as ME_IGNORED,
                            case
                                WHEN i2.MY_IGNORER IS NOT NULL THEN 1
                                ELSE 0
                            end as MY_IGNORER,
                            fa1.SUBSCRIBERS_NUM,
                            fa2.SUBSCRIBES_NUM,
                            a.ARTICLES_NUM
                        FROM `user` u
                        LEFT JOIN (SELECT 1 as ME_SUBSCRIBED, REFERENCED_USER_ID FROM `friends` WHERE USER_ID = ".$_POST['user'].") f1 ON u.USER_ID = f1.REFERENCED_USER_ID
                        LEFT JOIN (SELECT 1 as MY_SUBSCRIBER, USER_ID FROM `friends` WHERE REFERENCED_USER_ID = ".$_POST['user'].") f2 ON u.USER_ID = f2.USER_ID
                        LEFT JOIN (SELECT 1 as ME_IGNORED, REFERENCED_USER_ID FROM `ignor` WHERE USER_ID = ".$_POST['user'].") i1 ON u.USER_ID = i1.REFERENCED_USER_ID 
                        LEFT JOIN (SELECT 1 as MY_IGNORER, USER_ID FROM `ignor` WHERE REFERENCED_USER_ID = ".$_POST['user'].") i2 ON u.USER_ID = i2.USER_ID
                        INNER JOIN (SELECT count(1) as SUBSCRIBERS_NUM from `friends` where REFERENCED_USER_ID = ".$art.") fa1 
                        INNER JOIN (SELECT count(1) as SUBSCRIBES_NUM from `friends` where USER_ID = ".$art.") fa2
                        INNER JOIN (SELECT count(1) as ARTICLES_NUM from `article` where USER_ID = ".$art.") a
                        where u.USER_ID = ".$art;
            $db_erg_target = mysql_query( $sql_target );
            if ($db_erg_target) {
                $_daten_target = mysql_fetch_array($db_erg_target, MYSQL_ASSOC);
                if ($_daten_target) {
                    $a = null;
                    if(isset($_daten_target['USER_ID']) && glob('img/'.$_daten_target['USER_ID'].'.*') != null)
                        $a = glob('img/'.$_daten_target['USER_ID'].'.*');
                    else
                        $a = glob('img/nopic.jpg');
                    $res['userId'] = $_daten_target['USER_ID'];
                    $res['userAvatar'] = $a[0];
                    $res['userName'] = $_daten_target['USER_NAME'];
                    $res['isPrivate'] = $_daten_target['USER_PRIVACY'];
                    $res['mySubscriber'] = $_daten_target['MY_SUBSCRIBER'];
                    $res['meSubscribed'] = $_daten_target['ME_SUBSCRIBED'];
                    $res['myIgnorer'] = $_daten_target['MY_IGNORER'];
                    $res['meIgnored'] = $_daten_target['ME_IGNORED'];
                    if (($_daten_target['USER_PRIVACY'] == 0 or $_daten_target['MY_SUBSCRIBER'] == 1) and  $_daten_target['MY_SUBSCRIBER'] == 0){
                        $res['userDescription'] = $_daten_target['USER_DESC'];
                        $res['moneyPrivate'] = $_daten_target['USER_MONEY_PRIVACY'];
                        $res['postNum'] = $_daten_target['ARTICLES_NUM'];
                        $res['subscribersNum'] = $_daten_target['SUBSCRIBERS_NUM'];
                        $res['subscribesNum'] = $_daten_target['SUBSCRIBES_NUM'];
                        if ($_daten_target['USER_MONEY_PRIVACY'] == 0) $res['userMoney'] = $_daten_target['EARNED'];
                    }
                    $result['success'] = true;
                    $result['result'] = $res;
                }
            }
            else $res['answer'] = 'TECH_FAIL';
        }
        else $res['answer'] = 'USER_FAIL';
    }
    else $res['answer'] = 'DB_FAIL';
    mysql_close();
    echo json_encode($res);
?>