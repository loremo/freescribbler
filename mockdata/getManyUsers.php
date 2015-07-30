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
    $art = $_POST['user'];
    if (isset($_POST['target'])) $art = $_POST['target']; 
    $act = 'subscribers';
    if (isset($_POST['act'])) $act = $_POST['act']; 
    $lastUser = 9999999999;
    if (isset($_POST['lastUser'])) $lastUser = $_POST['lastUser']; 
    $userLimit = 30;
    if (isset($_POST['userLimit'])) $userLimit = $_POST['userLimit']; 
    $sql = "SELECT * FROM `user` where USER_ID = '".$_POST['user']."' AND USER_TOKEN = '".$_POST['token']."'";
    $db_erg = mysql_query( $sql );
    if ($db_erg) {
        $_daten = mysql_fetch_array($db_erg, MYSQL_ASSOC);
        if ($_daten) {
            $sql_target = "
                        SELECT 
                            u.USER_ID,
                            u.USER_NAME,
                            u.USER_PRIVACY,
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
                            end as MY_IGNORER
                        FROM `user` u";
            
            if ($act == 'subscribers') $sql_target .= " INNER JOIN `friends` z ON u.USER_ID = z.USER_ID";
            if ($act == 'subscribes') $sql_target .= " INNER JOIN `friends` z ON u.USER_ID = z.REFERENCED_USER_ID";
            if ($act == 'ignors') $sql_target .= " INNER JOIN `ignor` z ON u.USER_ID = z.REFERENCED_USER_ID";
            
            $sql_target .= " LEFT JOIN (SELECT 1 as ME_SUBSCRIBED, REFERENCED_USER_ID FROM `friends` WHERE USER_ID = ".$_POST['user'].") f1 ON u.USER_ID = f1.REFERENCED_USER_ID
                        LEFT JOIN (SELECT 1 as MY_SUBSCRIBER, USER_ID FROM `friends` WHERE REFERENCED_USER_ID = ".$_POST['user'].") f2 ON u.USER_ID = f2.USER_ID
                        LEFT JOIN (SELECT 1 as ME_IGNORED, REFERENCED_USER_ID FROM `ignor` WHERE USER_ID = ".$_POST['user'].") i1 ON u.USER_ID = i1.REFERENCED_USER_ID 
                        LEFT JOIN (SELECT 1 as MY_IGNORER, USER_ID FROM `ignor` WHERE REFERENCED_USER_ID = ".$_POST['user'].") i2 ON u.USER_ID = i2.USER_ID
                        WHERE u.USER_ID < ".$lastUser." and i2.MY_IGNORER is null and (u.USER_PRIVACY = 0 or f2.MY_SUBSCRIBER is not null)";
            if ($act == 'search') $sql_target .= " and u.USER_NAME LIKE '%".$art."%'";
            if ($act == 'subscribers') $sql_target .= " and z.REFERENCED_USER_ID = ".$art;
            if ($act == 'subscribes') $sql_target .= " and z.USER_ID = ".$art;
            if ($act == 'ignors') $sql_target .= " and z.USER_ID = ".$art;
            
            $sql_target .= " order by u.USER_ID desc limit ".$userLimit;
            $db_erg_target = mysql_query( $sql_target );
            if ($db_erg_target) {
                while($row = mysql_fetch_array($db_erg_target, MYSQL_ASSOC)) $rows[] = $row;
                if (isset($rows)) foreach($rows as $row) {
                    $a = null;
                    if(isset($row['USER_ID']) && glob('img/'.$row['USER_ID'].'.*') != null)
                        $a = glob('img/'.$row['USER_ID'].'.*');
                    else
                        $a = glob('img/nopic.jpg');
                    $res['userId'] = $row['USER_ID'];
                    $res['userAvatar'] = 'mockdata/'.$a[0];
                    $res['userName'] = $row['USER_NAME'];
                    $res['isPrivate'] = $row['USER_PRIVACY'];
                    $res['mySubscriber'] = $row['MY_SUBSCRIBER'];
                    $res['meSubscribed'] = $row['ME_SUBSCRIBED'];
                    $res['myIgnorer'] = $row['MY_IGNORER'];
                    $res['meIgnored'] = $row['ME_IGNORED'];
                    
                    $users[] = $res;
                }
                $result['users'] = $users;
                $result['lastUser'] = $res['userId'];
                $result['act'] = $act;
                $result['success'] = true;
            }
            else $result['answer'] = 'TECH_FAIL';
        }
        else $result['answer'] = 'USER_FAIL';
    }
    else $result['answer'] = 'DB_FAIL';
    mysql_close();
    echo json_encode($result);
?>