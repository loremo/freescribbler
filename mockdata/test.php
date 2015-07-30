<?php
    if (isset($_GET)) foreach($_GET as $v => $row) {
        $notallowed  = array(" ", "/", "\\", "&");
        $_GET[$v] = str_replace($notallowed, '', $_GET[$v]);
    }
    if (isset($_GET['act']) && (strtoupper($_GET['act']) == 'LOGIN'                 // done
                              || strtoupper($_GET['act']) == 'LOGOUT'               // done
                              || strtoupper($_GET['act']) == 'GETUSER'              // done
                              || strtoupper($_GET['act']) == 'GETFOLLOWERS'         // done
                              || strtoupper($_GET['act']) == 'GETFOLLOWED'          // done
                              || strtoupper($_GET['act']) == 'GETMEIGNORED'         // done
                              || strtoupper($_GET['act']) == 'GETONEPOST'           // done
                              || strtoupper($_GET['act']) == 'GETUSERPOSTS'         // done
                              || strtoupper($_GET['act']) == 'GETBESTPOSTS'         // done
                              || strtoupper($_GET['act']) == 'GETFRIENDSPOSTS'      // done
                              || strtoupper($_GET['act']) == 'GETHASHPOSTS'         // done
                              || strtoupper($_GET['act']) == 'GETCOMMENTS'          // done
                              || strtoupper($_GET['act']) == 'GETEVENTS'            // done
                              || strtoupper($_GET['act']) == 'FINDUSER'             // done
                              || strtoupper($_GET['act']) == 'UPDATEFRIEND'         // done
                              || strtoupper($_GET['act']) == 'UPDATELIKE'
                              || strtoupper($_GET['act']) == 'UPDATEIGNOR'          // done
                              || strtoupper($_GET['act']) == 'UPDATEPOST'
                              || strtoupper($_GET['act']) == 'UPDATEPROFILE'
                              || strtoupper($_GET['act']) == 'DELETEPOST'
                              || strtoupper($_GET['act']) == 'DELETECOMMENT'
                              || strtoupper($_GET['act']) == 'CREATECOMMENT'
                              || strtoupper($_GET['act']) == 'CREATEPOST')) {
        
        // Create connection
        $act = strtoupper($_GET['act']);
        $db_servername = 'localhost';
        $db_username = 'artem';
        $db_password = 'i9l0XNvj_2';
        $datenbank = 'journalist';
        $result = Array();
        $appcode = '123123123';
        $success = false;

        $db_connection = new mysqli($db_servername, $db_username, $db_password, $datenbank);
         
        switch ($act) {
            case "LOGIN":
                if (isset($_GET['username']) && isset($_GET['userpassword']) && isset($_GET['clientcode'])) {
                    $sql = "SELECT * FROM `user` where USER_NAME = '".$_GET['username']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result) {
                        if ($db_result->num_rows > 0) {
                            $user = $db_result->fetch_object();
                            if ($user->USER_PASSWORD == md5($user->USER_SALT.$_GET['userpassword'])) {
                                if ($_GET['clientcode'] == $appcode) {
                                    $token = md5(uniqid(mt_rand(), true));
                                    $token_sql = 'UPDATE `user` SET token = "'.$token.'" WHERE USER_ID = '.$user->USER_ID;
                                    if ($db_connection->query($token_sql)) {
                                        $success = true;
                                        $result['connectid'] = $user->USER_ID;
                                        $result['token'] = $token;
                                        $result['error'] = 0;
                                        $result['response'] = 'token sent';
                                    }
                                    else {
                                        $result['connectid'] = -1;
                                        $result['token'] = -1;
                                        $result['error'] = 1;
                                        $result['response'] = 'technical error. Can not create token';
                                    }
                                }
                                else {
                                    $result['connectid'] = -1;
                                    $result['token'] = -1;
                                    $result['error'] = 2;
                                    $result['response'] = 'application can not be authorized';
                                }
                            }
                            else {
                                $result['connectid'] = -1;
                                $result['token'] = -1;
                                $result['error'] = 3;
                                $result['response'] = 'user password is wrong';
                            }
                        }
                        else {
                            $result['connectid'] = -1;
                            $result['token'] = -1;
                            $result['error'] = 4;
                            $result['response'] = 'user not exists';
                        }
                    }
                    else {
                        $result['connectid'] = -1;
                        $result['token'] = -1;
                        $result['error'] = 5;
                        $result['response'] = 'cannot connect to server';
                    }
                }
                else {
                    $result['connectid'] = -1;
                    $result['token'] = -1;
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
                break;
            case "LOGOUT":
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = '".$_GET['connectid']."' and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $sql_target = 'UPDATE `user` SET token = -1 WHERE USER_ID = '.$_GET['connectid'];
                            if ($db_connection->query($sql_target)) {
                                $success = true;
                                $result['error'] = 0;
                                $result['response'] = 'token deleted';
                            }
                            else {
                                $result['error'] = 9;
                                $result['response'] = 'technical error. Can not delete token';
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
                break;
            case "GETUSER":
                $result['data'] = -1;
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode']) && isset($_GET['userid'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = '".$_GET['connectid']."' and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $sql_target = "
                                        SELECT 
                                            u.USER_ID,
                                            u.USER_NAME,
                                            u.USER_DESC,
                                            u.EARNED,
                                            u.USER_WEBMONEY,
                                            u.USER_PAYPAL,
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
                                        LEFT JOIN (SELECT 1 as ME_SUBSCRIBED, REFERENCED_USER_ID FROM `friends` WHERE USER_ID = ".$_GET['connectid'].") f1 ON u.USER_ID = f1.REFERENCED_USER_ID
                                        LEFT JOIN (SELECT 1 as MY_SUBSCRIBER, USER_ID FROM `friends` WHERE REFERENCED_USER_ID = ".$_GET['connectid'].") f2 ON u.USER_ID = f2.USER_ID
                                        LEFT JOIN (SELECT 1 as ME_IGNORED, REFERENCED_USER_ID FROM `ignor` WHERE USER_ID = ".$_GET['connectid'].") i1 ON u.USER_ID = i1.REFERENCED_USER_ID 
                                        LEFT JOIN (SELECT 1 as MY_IGNORER, USER_ID FROM `ignor` WHERE REFERENCED_USER_ID = ".$_GET['connectid'].") i2 ON u.USER_ID = i2.USER_ID
                                        INNER JOIN (SELECT count(1) as SUBSCRIBERS_NUM from `friends` where REFERENCED_USER_ID = ".$_GET['userid'].") fa1 
                                        INNER JOIN (SELECT count(1) as SUBSCRIBES_NUM from `friends` where USER_ID = ".$_GET['userid'].") fa2
                                        INNER JOIN (SELECT count(1) as ARTICLES_NUM from `article` where USER_ID = ".$_GET['userid'].") a
                                        where u.USER_ID = ".$_GET['userid'];
                            $db_target_result = $db_connection->query( $sql_target );
                            if ($db_target_result && $db_target_result->num_rows > 0) {
                                $user = $db_target_result->fetch_object();
                                $a = null;
                                if(isset($user->USER_ID) && glob('img/'.$user->USER_ID.'.*') != null) 
                                    $a = glob('img/'.$user->USER_ID.'.*');
                                else 
                                    $a = glob('img/nopic.jpg');
                                $res['userid'] = $user->USER_ID;
                                $res['useravatar'] = 'http://freescribbler.com/images/scribblers/thumbs/'.$a[0];
                                $res['username'] = $user->USER_NAME;
                                $res['isprivat'] = $user->USER_PRIVACY;
                                $res['mysubscriber'] = $user->MY_SUBSCRIBER;
                                $res['mesubscribed'] = $user->ME_SUBSCRIBED;
                                $res['myignorer'] = $user->MY_IGNORER;
                                $res['meignored'] = $user->ME_IGNORED;
                                if (($user->USER_PRIVACY == 0 or $user->MY_SUBSCRIBER == 1 or $user->USER_ID == $_GET['connectid']) and  $user->MY_IGNORER == 0){
                                    $res['userdescription'] = $user->USER_DESC;
                                    $res['moneyprivat'] = $user->USER_MONEY_PRIVACY;
                                    $res['postnum'] = $user->ARTICLES_NUM;
                                    $res['subscribersnum'] = $user->SUBSCRIBERS_NUM;
                                    $res['subscribesnum'] = $user->SUBSCRIBES_NUM;
                                    if ($user->USER_MONEY_PRIVACY == 0 or $user->USER_ID == $_GET['connectid']) $res['usermoney'] = $user->EARNED;
                                    if ($user->USER_ID == $_GET['connectid']) {
                                        $res['walletwebmoney'] = $user->USER_WEBMONEY;
                                        $res['walletpaypal'] = $user->USER_PAYPAL;
                                    }
                                }
                                $success = true;
                                $result['error'] = 0;
                                $result['response'] = 'user sent';
                                $result['data'] = $res;
                            }
                            else {
                                $result['error'] = 10;
                                $result['response'] = 'user not exists';
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                
                $result['success'] = $success;
                break;
                
            case "GETFOLLOWERS":
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode']) && isset($_GET['userid'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = '".$_GET['connectid']."' and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $lastUser = 9999999999;
                            if (isset($_GET['offset'])) $lastUser = $_GET['offset']; 
                            $userLimit = 30;
                            if (isset($_GET['limit'])) $userLimit = $_GET['limit']; 

                            $sql_target = 'SELECT 
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
                                        FROM `user` u
                                        INNER JOIN `friends` z ON u.USER_ID = z.USER_ID
                                        LEFT JOIN (SELECT 1 as ME_SUBSCRIBED, REFERENCED_USER_ID FROM `friends` WHERE USER_ID = '.$_GET['connectid'].') f1 ON u.USER_ID = f1.REFERENCED_USER_ID
                                        LEFT JOIN (SELECT 1 as MY_SUBSCRIBER, USER_ID FROM `friends` WHERE REFERENCED_USER_ID = '.$_GET['connectid'].') f2 ON u.USER_ID = f2.USER_ID
                                        LEFT JOIN (SELECT 1 as ME_IGNORED, REFERENCED_USER_ID FROM `ignor` WHERE USER_ID = '.$_GET['connectid'].') i1 ON u.USER_ID = i1.REFERENCED_USER_ID 
                                        LEFT JOIN (SELECT 1 as MY_IGNORER, USER_ID FROM `ignor` WHERE REFERENCED_USER_ID = '.$_GET['connectid'].') i2 ON u.USER_ID = i2.USER_ID
                                        WHERE u.USER_ID < '.$lastUser.' and i2.MY_IGNORER is null and (u.USER_PRIVACY = 0 or f2.MY_SUBSCRIBER is not null) and z.REFERENCED_USER_ID = '.$_GET['userid'].'
                                        ORDER BY u.USER_ID desc limit '.$userLimit;
                            $db_target_result = $db_connection->query( $sql_target );
                            if ($db_target_result && $db_target_result->num_rows > 0) {
                                while($row = $db_target_result->fetch_object()) $rows[] = $row;
                                if (isset($rows)) foreach($rows as $row) {
                                    $a = null;
                                    if(isset($row->USER_ID) && glob('img/'.$row->USER_ID.'.*') != null)
                                        $a = glob('img/'.$row->USER_ID.'.*');
                                    else
                                        $a = glob('img/nopic.jpg');
                                    $res['userid'] = $row->USER_ID;
                                    $res['useravatar'] = 'http://freescribbler.com/images/scribblers/thumbs_small/'.$a[0];
                                    $res['username'] = $row->USER_NAME;
                                    $res['isprivat'] = $row->USER_PRIVACY;
                                    $res['mysubscriber'] = $row->MY_SUBSCRIBER;
                                    $res['mesubscribed'] = $row->ME_SUBSCRIBED;
                                    $res['myignorer'] = $row->MY_IGNORER;
                                    $res['meignored'] = $row->ME_IGNORED;

                                    $users[] = $res;
                                }
                                $success = true;
                                $result['error'] = 0;
                                $result['response'] = $db_target_result->num_rows.' user got';
                                $result['data'] = $users;
                                $result['lastuser'] = $res['userid'];
                            }
                            else {
                                $result['error'] = 10;
                                $result['response'] = 'users not exist';
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
                break;
            
            case "GETFOLLOWED":
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode']) && isset($_GET['userid'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = '".$_GET['connectid']."' and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $lastUser = 9999999999;
                            if (isset($_GET['offset'])) $lastUser = $_GET['offset']; 
                            $userLimit = 30;
                            if (isset($_GET['limit'])) $userLimit = $_GET['limit']; 

                            $sql_target = 'SELECT 
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
                                        FROM `user` u
                                        INNER JOIN `friends` z ON u.USER_ID = z.REFERENCED_USER_ID
                                        LEFT JOIN (SELECT 1 as ME_SUBSCRIBED, REFERENCED_USER_ID FROM `friends` WHERE USER_ID = '.$_GET['connectid'].') f1 ON u.USER_ID = f1.REFERENCED_USER_ID
                                        LEFT JOIN (SELECT 1 as MY_SUBSCRIBER, USER_ID FROM `friends` WHERE REFERENCED_USER_ID = '.$_GET['connectid'].') f2 ON u.USER_ID = f2.USER_ID
                                        LEFT JOIN (SELECT 1 as ME_IGNORED, REFERENCED_USER_ID FROM `ignor` WHERE USER_ID = '.$_GET['connectid'].') i1 ON u.USER_ID = i1.REFERENCED_USER_ID 
                                        LEFT JOIN (SELECT 1 as MY_IGNORER, USER_ID FROM `ignor` WHERE REFERENCED_USER_ID = '.$_GET['connectid'].') i2 ON u.USER_ID = i2.USER_ID
                                        WHERE u.USER_ID < '.$lastUser.' and i2.MY_IGNORER is null and (u.USER_PRIVACY = 0 or f2.MY_SUBSCRIBER is not null) and z.USER_ID = '.$_GET['userid'].'
                                        ORDER BY u.USER_ID desc limit '.$userLimit;
                            $db_target_result = $db_connection->query( $sql_target );
                            if ($db_target_result && $db_target_result->num_rows > 0) {
                                while($row = $db_target_result->fetch_object()) $rows[] = $row;
                                if (isset($rows)) foreach($rows as $row) {
                                    $a = null;
                                    if(isset($row->USER_ID) && glob('img/'.$row->USER_ID.'.*') != null)
                                        $a = glob('img/'.$row->USER_ID.'.*');
                                    else
                                        $a = glob('img/nopic.jpg');
                                    $res['userid'] = $row->USER_ID;
                                    $res['useravatar'] = 'http://freescribbler.com/images/scribblers/thumbs_small/'.$a[0];
                                    $res['username'] = $row->USER_NAME;
                                    $res['isprivat'] = $row->USER_PRIVACY;
                                    $res['mysubscriber'] = $row->MY_SUBSCRIBER;
                                    $res['mesubscribed'] = $row->ME_SUBSCRIBED;
                                    $res['myignorer'] = $row->MY_IGNORER;
                                    $res['meignored'] = $row->ME_IGNORED;

                                    $users[] = $res;
                                }
                                $success = true;
                                $result['error'] = 0;
                                $result['response'] = $db_target_result->num_rows.' user got';
                                $result['data'] = $users;
                                $result['lastuser'] = $res['userid'];
                            }
                            else {
                                $result['error'] = 10;
                                $result['response'] = 'users not exist';
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
                break;
            
            case "GETMEIGNORED":
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = '".$_GET['connectid']."' and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $lastUser = 9999999999;
                            if (isset($_GET['offset'])) $lastUser = $_GET['offset']; 
                            $userLimit = 30;
                            if (isset($_GET['limit'])) $userLimit = $_GET['limit']; 

                            $sql_target = 'SELECT 
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
                                            1 as ME_IGNORED,
                                            case
                                                WHEN i2.MY_IGNORER IS NOT NULL THEN 1
                                                ELSE 0
                                            end as MY_IGNORER
                                        FROM `user` u
                                        INNER JOIN `ignor` z ON u.USER_ID = z.REFERENCED_USER_ID
                                        LEFT JOIN (SELECT 1 as ME_SUBSCRIBED, REFERENCED_USER_ID FROM `friends` WHERE USER_ID = '.$_GET['connectid'].') f1 ON u.USER_ID = f1.REFERENCED_USER_ID
                                        LEFT JOIN (SELECT 1 as MY_SUBSCRIBER, USER_ID FROM `friends` WHERE REFERENCED_USER_ID = '.$_GET['connectid'].') f2 ON u.USER_ID = f2.USER_ID 
                                        LEFT JOIN (SELECT 1 as MY_IGNORER, USER_ID FROM `ignor` WHERE REFERENCED_USER_ID = '.$_GET['connectid'].') i2 ON u.USER_ID = i2.USER_ID
                                        WHERE u.USER_ID < '.$lastUser.' and i2.MY_IGNORER is null and (u.USER_PRIVACY = 0 or f2.MY_SUBSCRIBER is not null) and z.USER_ID = '.$_GET['connectid'].'
                                        ORDER BY u.USER_ID desc limit '.$userLimit;
                            $db_target_result = $db_connection->query( $sql_target );
                            if ($db_target_result && $db_target_result->num_rows > 0) {
                                while($row = $db_target_result->fetch_object()) $rows[] = $row;
                                if (isset($rows)) foreach($rows as $row) {
                                    $a = null;
                                    if(isset($row->USER_ID) && glob('img/'.$row->USER_ID.'.*') != null)
                                        $a = glob('img/'.$row->USER_ID.'.*');
                                    else
                                        $a = glob('img/nopic.jpg');
                                    $res['userid'] = $row->USER_ID;
                                    $res['useravatar'] = 'http://freescribbler.com/images/scribblers/thumbs_small/'.$a[0];
                                    $res['username'] = $row->USER_NAME;
                                    $res['isprivat'] = $row->USER_PRIVACY;
                                    $res['mysubscriber'] = $row->MY_SUBSCRIBER;
                                    $res['mesubscribed'] = $row->ME_SUBSCRIBED;
                                    $res['myignorer'] = $row->MY_IGNORER;
                                    $res['meignored'] = $row->ME_IGNORED;

                                    $users[] = $res;
                                }
                                $success = true;
                                $result['error'] = 0;
                                $result['response'] = $db_target_result->num_rows.' user got';
                                $result['data'] = $users;
                                $result['lastuser'] = $res['userid'];
                            }
                            else {
                                $result['error'] = 10;
                                $result['response'] = 'users not exist';
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
                break;
            
            case "GETONEPOST":
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode']) && isset($_GET['postid'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = '".$_GET['connectid']."' and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $db_row = $db_result->fetch_object();

                            $sql_privat = 'SELECT 
                                        u.USER_PRIVACY,
                                        case
                                            WHEN f2.MY_SUBSCRIBER IS NOT NULL THEN 1
                                            ELSE 0
                                        end as MY_SUBSCRIBER,
                                        case
                                            WHEN i2.MY_IGNORER IS NOT NULL THEN 1
                                            ELSE 0
                                        end as MY_IGNORER
                                    FROM 
                                        `user` u
                                        INNER JOIN `article` a ON a.USER_ID = u.USER_ID
                                        LEFT JOIN (SELECT 1 as MY_SUBSCRIBER, USER_ID FROM `friends` WHERE REFERENCED_USER_ID = '.$_GET['connectid'].') f2 ON u.USER_ID = f2.USER_ID
                                        LEFT JOIN (SELECT 1 as MY_IGNORER, USER_ID FROM `ignor` WHERE REFERENCED_USER_ID = '.$_GET['connectid'].') i2 ON u.USER_ID = i2.USER_ID
                                        
                                    where 
                                        a.ARTICLE_ID = '.$_GET['postid'];
                            $db_privat_result = $db_connection->query($sql_privat);
                            if ($db_privat_result && $db_privat_result->num_rows > 0) {
                                $privat_row = $db_privat_result->fetch_object();
                                
                                if ($db_row->ROLE_ID == 3 or ($privat_row->MY_IGNORER == 0 and ($privat_row->USER_PRIVACY == 0 or $privat_row->MY_SUBSCRIBER == 1))) {

                                    $sql_target = '
                                            SELECT 
                                                u.USER_NAME, 
                                                u.USER_ID,
                                                a.ARTICLE_ID,
                                                a.ARTICLE_CONTENT,
                                                a.ARTICLE_TIMESTAMP,
                                                a.CREATION_USER_IP,
                                                a.VALIDATION_FLAG,
                                                a.ARTICLE_POINTS, 
                                                c.COMMENT_NUM,
                                                case
                                                    when p.ARTICLE_POINT_ID is null then 0
                                                    else 1
                                                end as IS_LIKED
                                            FROM 
                                                `article` a 
                                                INNER JOIN `user` u ON a.USER_ID = u.USER_ID 
                                                LEFT JOIN (select ARTICLE_ID, count(1) as COMMENT_NUM FROM `comments` group by ARTICLE_ID) c ON a.ARTICLE_ID = c.ARTICLE_ID
                                                LEFT JOIN `article_point` p ON (a.ARTICLE_ID = p.ARTICLE_ID and p.USER_ID = '.$_GET['connectid'].')
                                            WHERE 
                                                a.ARTICLE_ID = '.$_GET['postid'];
                                    $db_target_result = $db_connection->query( $sql_target );
                                    if ($db_target_result && $db_target_result->num_rows > 0) {
                                        $post = $db_target_result->fetch_object();
                                        $a = null;
                                        if(isset($post->USER_ID) && glob('img/'.$post->USER_ID.'.*') != null) 
                                            $a = glob('img/'.$post->USER_ID.'.*');
                                        else 
                                            $a = glob('img/nopic.jpg');
                                        $res['useravatar'] = 'http://freescribbler.com/images/scribblers/thumbs_small/'.$a[0];
                                        $res['userid'] = $post->USER_ID;
                                        $res['username'] = $post->USER_NAME;
                                        $res['postid'] = $post->ARTICLE_ID;
                                        $res['postcontent'] = $post->ARTICLE_CONTENT;
                                        $res['posttime'] = $post->ARTICLE_TIMESTAMP;
                                        $res['commentnum'] = $post->COMMENT_NUM;
                                        $res['postlikes'] = $post->ARTICLE_POINTS;
                                        $res['postliked'] = $post->IS_LIKED;

                                        $sql_pics = "
                                                SELECT 
                                                    p.*
                                                FROM 
                                                    `article_media` m 
                                                    INNER JOIN `pics` p ON m.MEDIA_ID = p.PIC_ID 
                                                WHERE 
                                                    m.ARTICLE_ID = ".$res['postid']." AND p.IS_ACTIVE = 1
                                                ORDER BY 
                                                    m.ARTICLE_MEDIA_RELATION_ID";
                                        $db_pics_result = $db_connection->query( $sql_pics );
                                        if ($db_pics_result && $db_pics_result->num_rows > 0) {
                                            $rows_pics = Array();
                                            $results_pic = Array();
                                            while($row_pics = $db_pics_result->fetch_object()) $rows_pics[] = $row_pics;
                                            foreach($rows_pics as $row_pics) {
                                                $res_pic['picname'] = 'http://freescribbler.com/images/'.$row_pics->PIC_NAME;
                                                $res_pic['picwidth'] = $row_pics->PIC_WIDTH;
                                                $res_pic['picheight'] = $row_pics->PIC_HEIGHT;
                                                $results_pic[] = $res_pic;
                                            }
                                            $res['pics'] = $results_pic;
                                        }
                                        else {
                                            $res['pics'] = -1;
                                        }

                                        $success = true;
                                        $result['error'] = 0;
                                        $result['response'] = $db_target_result->num_rows.' user got';
                                        $result['data'] = $res;
                                    }
                                    else {
                                        $result['error'] = 12;
                                        $result['response'] = 'post not exist';
                                    }
                                }
                                else {
                                    $result['error'] = 13;
                                    $result['response'] = ' data is hidden';
                                    $result['data'] = -1;
                                }
                            }
                            else {
                                $result['error'] = 12;
                                $result['response'] = 'post not exist';
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
                break;
            
            case "GETUSERPOSTS":
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode']) && isset($_GET['userid'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = ".$_GET['connectid']." and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $db_row = $db_result->fetch_object();
                            $sql_privat = 'SELECT 
                                        u.USER_PRIVACY,
                                        case
                                            WHEN f2.MY_SUBSCRIBER IS NOT NULL THEN 1
                                            ELSE 0
                                        end as MY_SUBSCRIBER,
                                        case
                                            WHEN i2.MY_IGNORER IS NOT NULL THEN 1
                                            ELSE 0
                                        end as MY_IGNORER
                                    FROM 
                                        `user` u
                                        LEFT JOIN (SELECT 1 as MY_SUBSCRIBER, USER_ID FROM `friends` WHERE REFERENCED_USER_ID = '.$_GET['connectid'].') f2 ON u.USER_ID = f2.USER_ID
                                        LEFT JOIN (SELECT 1 as MY_IGNORER, USER_ID FROM `ignor` WHERE REFERENCED_USER_ID = '.$_GET['connectid'].') i2 ON u.USER_ID = i2.USER_ID
                                        
                                    where 
                                        u.USER_ID = '.$_GET['userid'];
                            
                            $db_privat_result = $db_connection->query($sql_privat);
                            if ($db_privat_result && $db_privat_result->num_rows > 0) {
                                $privat_row = $db_privat_result->fetch_object();
                                
                                if ($db_row->ROLE_ID == 3 or ($privat_row->MY_IGNORER == 0 and ($privat_row->USER_PRIVACY == 0 or $privat_row->MY_SUBSCRIBER == 1))) {

                                    $offsetid = 999999999999;
                                    if (isset($_GET['offset'])) $offsetid = $_GET['offset'];
                                    $limit = 30;
                                    if (isset($_GET['limit'])) $limit = $_GET['limit'];

                                    $sql_target = '
                                            SELECT 
                                                u.USER_NAME, 
                                                u.USER_ID,
                                                a.ARTICLE_ID,
                                                a.ARTICLE_CONTENT,
                                                a.ARTICLE_TIMESTAMP,
                                                a.CREATION_USER_IP,
                                                a.VALIDATION_FLAG,
                                                a.ARTICLE_POINTS, 
                                                c.COMMENT_NUM,
                                                case
                                                    when p.ARTICLE_POINT_ID is null then 0
                                                    else 1
                                                end as IS_LIKED
                                            FROM 
                                                `article` a 
                                                INNER JOIN `user` u ON a.USER_ID = u.USER_ID 
                                                LEFT JOIN (select ARTICLE_ID, count(1) as COMMENT_NUM FROM `comments` group by ARTICLE_ID) c ON a.ARTICLE_ID = c.ARTICLE_ID
                                                LEFT JOIN `article_point` p ON (a.ARTICLE_ID = p.ARTICLE_ID and p.USER_ID = '.$_GET['connectid'].')
                                            WHERE 
                                                u.USER_ID = '.$_GET['userid'].' and
                                                a.ARTICLE_ID < '.$offsetid.'
                                            ORDER BY 
                                                a.ARTICLE_ID desc
                                            LIMIT '.$limit;
                                    $db_target_result = $db_connection->query( $sql_target );
                                    if ($db_target_result && $db_target_result->num_rows > 0) {
                                        while($row = $db_target_result->fetch_object()) $rows[] = $row;
                                        if (isset($rows)) foreach($rows as $row) {
                                            $a = null;
                                            if(isset($row->USER_ID) && glob('img/'.$row->USER_ID.'.*') != null) 
                                                $a = glob('img/'.$row->USER_ID.'.*');
                                            else 
                                                $a = glob('img/nopic.jpg');
                                            $res['useravatar'] = 'http://freescribbler.com/images/scribblers/thumbs_small/'.$a[0];
                                            $res['userid'] = $row->USER_ID;
                                            $res['username'] = $row->USER_NAME;
                                            $res['postid'] = $row->ARTICLE_ID;
                                            $res['postcontent'] = $row->ARTICLE_CONTENT;
                                            $res['posttime'] = $row->ARTICLE_TIMESTAMP;
                                            $res['commentnum'] = $row->COMMENT_NUM;
                                            $res['postlikes'] = $row->ARTICLE_POINTS;
                                            $res['postliked'] = $row->IS_LIKED;

                                            $sql_pics = "
                                                    SELECT 
                                                        p.*
                                                    FROM 
                                                        `article_media` m 
                                                        INNER JOIN `pics` p ON m.MEDIA_ID = p.PIC_ID 
                                                    WHERE 
                                                        m.ARTICLE_ID = ".$row->ARTICLE_ID." AND p.IS_ACTIVE = 1
                                                    ORDER BY 
                                                        m.ARTICLE_MEDIA_RELATION_ID";
                                            $db_pics_result = $db_connection->query( $sql_pics );
                                            if ($db_pics_result && $db_pics_result->num_rows > 0) {
                                                $rows_pics = Array();
                                                $results_pic = Array();
                                                while($row_pics = $db_pics_result->fetch_object()) $rows_pics[] = $row_pics;
                                                foreach($rows_pics as $row_pics) {
                                                    $res_pic['picname'] = 'http://freescribbler.com/images/'.$row_pics->PIC_NAME;
                                                    $res_pic['picwidth'] = $row_pics->PIC_WIDTH;
                                                    $res_pic['picheight'] = $row_pics->PIC_HEIGHT;
                                                    $results_pic[] = $res_pic;
                                                }
                                                $res['pics'] = $results_pic;
                                            }
                                            else {
                                                $res['pics'] = -1;
                                            }
                                            $posts[] = $res;
                                        }
                                        $success = true;
                                        $result['error'] = 0;
                                        $result['response'] = $db_target_result->num_rows.' user got';
                                        $result['data'] = $posts;
                                    }
                                    else {
                                        $result['error'] = 12;
                                        $result['response'] = 'posts not exist';
                                    }
                                }
                                else {
                                    $result['error'] = 13;
                                    $result['response'] = ' data is hidden';
                                }
                            }
                            else {
                                $result['error'] = 14;
                                $result['response'] = 'user not exist';
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
                break;
            
            case "GETBESTPOSTS":
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = '".$_GET['connectid']."' and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $limit = 30;
                            if (isset($_GET['limit'])) $limit = $_GET['limit'];
                            $datum = date('Y-m-d H:i:s');
                            $datum_back = date('Y-m-d H:i:s', strtotime('-4 hour', strtotime($datum)));

                            $sql_target = '
                                    SELECT
                                        o.USER_NAME, 
                                        a.USER_ID,
                                        a.ARTICLE_ID,
                                        a.ARTICLE_CONTENT,
                                        a.ARTICLE_TIMESTAMP,
                                        a.CREATION_USER_IP,
                                        a.VALIDATION_FLAG,
                                        a.ARTICLE_POINTS, 
                                        c.COMMENT_NUM,
                                        case
                                            when p.ARTICLE_POINT_ID is null then 0
                                            else 1
                                        end as IS_LIKED
                                    FROM
                                        `article` a
                                        INNER JOIN (SELECT    
                                            substr(max(CONCAT(1000000000+t.ARTICLE_POINTS, t.ARTICLE_ID)), 11) as ARTICLE_ID,
                                            u.USER_NAME,
                                         	u.USER_ID,
                                            max(t.ARTICLE_POINTS) as ARTICLE_POINTS
                                        FROM `article` t
                                            INNER JOIN `user` u ON t.USER_ID = u.USER_ID
                                            LEFT JOIN (select * from `friends` where REFERENCED_USER_ID = '.$_GET['connectid'].') f on u.USER_ID = f.USER_ID
                                            LEFT JOIN (select * from `ignor` where REFERENCED_USER_ID = '.$_GET['connectid'].') i on u.USER_ID = i.USER_ID
                                        WHERE (u.USER_PRIVACY = 0 or f.USER_ID IS NOT NULL) and (i.USER_ID IS NULL) and t.USER_ID <> '.$_GET['connectid'].' and t.ARTICLE_TIMESTAMP > "'.$datum_back.'"
                                        GROUP BY u.USER_NAME, u.USER_ID
                                        ORDER BY max(t.ARTICLE_POINTS) DESC 
                                        LIMIT '.$limit.') o on o.ARTICLE_ID = a.ARTICLE_ID
                                        LEFT JOIN (select ARTICLE_ID, count(1) as COMMENT_NUM FROM `comments` group by ARTICLE_ID) c ON a.ARTICLE_ID = c.ARTICLE_ID
                                        LEFT JOIN `article_point` p ON (a.ARTICLE_ID = p.ARTICLE_ID and p.USER_ID = '.$_GET['connectid'].')';
                            
                            $db_target_result = $db_connection->query( $sql_target );
                            if ($db_target_result && $db_target_result->num_rows > 0) {
                                while($row = $db_target_result->fetch_object()) $rows[] = $row;
                                if (isset($rows)) foreach($rows as $row) {
                                    $a = null;
                                    if(isset($row->USER_ID) && glob('img/'.$row->USER_ID.'.*') != null) 
                                        $a = glob('img/'.$row->USER_ID.'.*');
                                    else 
                                        $a = glob('img/nopic.jpg');
                                    $res['useravatar'] = 'http://freescribbler.com/images/scribblers/thumbs_small/'.$a[0];
                                    $res['userid'] = $row->USER_ID;
                                    $res['username'] = $row->USER_NAME;
                                    $res['postid'] = $row->ARTICLE_ID;
                                    $res['postcontent'] = $row->ARTICLE_CONTENT;
                                    $res['posttime'] = $row->ARTICLE_TIMESTAMP;
                                    $res['commentnum'] = $row->COMMENT_NUM;
                                    $res['postlikes'] = $row->ARTICLE_POINTS;
                                    $res['postliked'] = $row->IS_LIKED;

                                    $sql_pics = "
                                            SELECT 
                                                p.*
                                            FROM 
                                                `article_media` m 
                                                INNER JOIN `pics` p ON m.MEDIA_ID = p.PIC_ID 
                                            WHERE 
                                                m.ARTICLE_ID = ".$row->ARTICLE_ID." AND p.IS_ACTIVE = 1
                                            ORDER BY 
                                                m.ARTICLE_MEDIA_RELATION_ID";
                                    $db_pics_result = $db_connection->query( $sql_pics );
                                    if ($db_pics_result && $db_pics_result->num_rows > 0) {
                                        $rows_pics = Array();
                                        $results_pic = Array();
                                        while($row_pics = $db_pics_result->fetch_object()) $rows_pics[] = $row_pics;
                                        foreach($rows_pics as $row_pics) {
                                            $res_pic['picname'] = 'http://freescribbler.com/images/'.$row_pics->PIC_NAME;
                                            $res_pic['picwidth'] = $row_pics->PIC_WIDTH;
                                            $res_pic['picheight'] = $row_pics->PIC_HEIGHT;
                                            $results_pic[] = $res_pic;
                                        }
                                        $res['pics'] = $results_pic;
                                    }
                                    else {
                                        $res['pics'] = -1;
                                    }
                                    $posts[] = $res;
                                }
                                $success = true;
                                $result['error'] = 0;
                                $result['response'] = $db_target_result->num_rows.' user got';
                                $result['data'] = $posts;
                            }
                            else {
                                $result['error'] = 12;   
                                $result['response'] = 'posts not exist';
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
                break;
            
            case "GETFRIENDSPOSTS":
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = '".$_GET['connectid']."' and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $offsetid = 999999999999;
                            if (isset($_GET['offset'])) $offsetid = $_GET['offset'];
                            $limit = 30;
                            if (isset($_GET['limit'])) $limit = $_GET['limit'];

                            $sql_target = '
                                    SELECT
                                        u.USER_NAME, 
                                        a.USER_ID,
                                        a.ARTICLE_ID,
                                        a.ARTICLE_CONTENT,
                                        a.ARTICLE_TIMESTAMP,
                                        a.CREATION_USER_IP,
                                        a.VALIDATION_FLAG,
                                        a.ARTICLE_POINTS
                                    FROM
                                        `article` a
                                        INNER JOIN `user` u ON a.USER_ID = u.USER_ID
                                        INNER JOIN (select REFERENCED_USER_ID from `friends` where USER_ID = '.$_GET['connectid'].') m on u.USER_ID = m.REFERENCED_USER_ID
                                        LEFT JOIN (select * from `friends` where REFERENCED_USER_ID = '.$_GET['connectid'].') f on u.USER_ID = f.USER_ID
                                        LEFT JOIN (select * from `ignor` where REFERENCED_USER_ID = '.$_GET['connectid'].') i on u.USER_ID = i.USER_ID
                                    WHERE (u.USER_PRIVACY = 0 or f.USER_ID IS NOT NULL) and (i.USER_ID IS NULL) and a.USER_ID <> '.$_GET['connectid'].'
                                    ORDER BY a.ARTICLE_ID DESC 
                                    LIMIT '.$limit;
                            
                            $db_target_result = $db_connection->query( $sql_target );
                            if ($db_target_result && $db_target_result->num_rows > 0) {
                                while($row = $db_target_result->fetch_object()) $rows[] = $row;
                                if (isset($rows)) foreach($rows as $row) {
                                    $a = null;
                                    if(isset($row->USER_ID) && glob('img/'.$row->USER_ID.'.*') != null) 
                                        $a = glob('img/'.$row->USER_ID.'.*');
                                    else 
                                        $a = glob('img/nopic.jpg');
                                    $res['useravatar'] = 'http://freescribbler.com/images/scribblers/thumbs_small/'.$a[0];
                                    $res['userid'] = $row->USER_ID;
                                    $res['username'] = $row->USER_NAME;
                                    $res['postid'] = $row->ARTICLE_ID;
                                    $res['postcontent'] = $row->ARTICLE_CONTENT;
                                    $res['posttime'] = $row->ARTICLE_TIMESTAMP;
                                    $res['postlikes'] = $row->ARTICLE_POINTS;

                                    $sql_pics = "
                                            SELECT 
                                                p.*
                                            FROM 
                                                `article_media` m 
                                                INNER JOIN `pics` p ON m.MEDIA_ID = p.PIC_ID 
                                            WHERE 
                                                m.ARTICLE_ID = ".$row->ARTICLE_ID." AND p.IS_ACTIVE = 1
                                            ORDER BY 
                                                m.ARTICLE_MEDIA_RELATION_ID";
                                    $db_pics_result = $db_connection->query( $sql_pics );
                                    if ($db_pics_result && $db_pics_result->num_rows > 0) {
                                        $rows_pics = Array();
                                        $results_pic = Array();
                                        while($row_pics = $db_pics_result->fetch_object()) $rows_pics[] = $row_pics;
                                        foreach($rows_pics as $row_pics) {
                                            $res_pic['picname'] = 'http://freescribbler.com/images/'.$row_pics->PIC_NAME;
                                            $res_pic['picwidth'] = $row_pics->PIC_WIDTH;
                                            $res_pic['picheight'] = $row_pics->PIC_HEIGHT;
                                            $results_pic[] = $res_pic;
                                        }
                                        $res['pics'] = $results_pic;
                                    }
                                    else {
                                        $res['pics'] = -1;
                                    }
                                    
                                    $sql_comments = "
                                            SELECT 
                                                count(1) as COMMENT_NUM
                                            FROM 
                                                `comments` c 
                                            WHERE 
                                                c.ARTICLE_ID = ".$res['postid'];
                                    
                                    $db_com_result = $db_connection->query( $sql_comments );
                                    $row_com = $db_com_result->fetch_object();
                                    $res['commentnum'] = $row_com->COMMENT_NUM;
                                    
                                    $sql_liked = '
                                            SELECT 
                                                count(1) as IS_LIKED
                                            FROM 
                                                `article_point` ap 
                                            WHERE 
                                                ap.ARTICLE_ID = '.$res['postid'].' AND ap.USER_ID = '.$_GET['connectid'];
                                    
                                    $db_liked_result = $db_connection->query( $sql_liked );
                                    $row_liked = $db_liked_result->fetch_object();
                                    $res['postliked'] = $row_liked->IS_LIKED;
                                    
                                    
                                    $posts[] = $res;
                                    
                                }
                                $success = true;
                                $result['error'] = 0;
                                $result['response'] = $db_target_result->num_rows.' user got';
                                $result['data'] = $posts;
                                $result['lastpost'] = $res['postid'];
                            }
                            else {
                                $result['error'] = 12;   
                                $result['response'] = 'posts not exist';
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
                break;
                
            case "GETHASHPOSTS":
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode']) && isset($_GET['hashtag'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = '".$_GET['connectid']."' and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $offsetid = 999999999999;
                            if (isset($_GET['offset'])) $offsetid = $_GET['offset'];
                            $limit = 30;
                            if (isset($_GET['limit'])) $limit = $_GET['limit'];

                            $sql_target = '
                                    SELECT
                                        u.USER_NAME, 
                                        a.USER_ID,
                                        a.ARTICLE_ID,
                                        a.ARTICLE_CONTENT,
                                        a.ARTICLE_TIMESTAMP,
                                        a.CREATION_USER_IP,
                                        a.VALIDATION_FLAG,
                                        a.ARTICLE_POINTS, 
                                        c.COMMENT_NUM,
                                        case
                                            when p.ARTICLE_POINT_ID is null then 0
                                            else 1
                                        end as IS_LIKED
                                    FROM
                                        `article` a
                                        INNER JOIN `user` u ON u.USER_ID = a.USER_ID
                                        INNER JOIN `hashtag_article` ha ON ha.ARTICLE_ID = a.ARTICLE_ID
                                        INNER JOIN `hashtags` h ON h.HASHTAG_ID = ha.HASHTAG_ID
                                        LEFT JOIN (select ARTICLE_ID, count(1) as COMMENT_NUM FROM `comments` group by ARTICLE_ID) c ON a.ARTICLE_ID = c.ARTICLE_ID
                                        LEFT JOIN `article_point` p ON (a.ARTICLE_ID = p.ARTICLE_ID and p.USER_ID = '.$_GET['connectid'].')
                                        LEFT JOIN (select * from `friends` where REFERENCED_USER_ID = '.$_GET['connectid'].') f on u.USER_ID = f.USER_ID
                                        LEFT JOIN (select * from `ignor` where REFERENCED_USER_ID = '.$_GET['connectid'].') i on u.USER_ID = i.USER_ID
                                    WHERE a.ARTICLE_ID < '.$offsetid.' and (u.USER_PRIVACY = 0 or f.USER_ID IS NOT NULL) and (i.USER_ID IS NULL) and a.USER_ID <> '.$_GET['connectid'].' and upper(h.HASHTAG_STRING)= upper("'.$_GET['hashtag'].'") 
                                    ORDER BY a.ARTICLE_ID desc
                                    LIMIT '.$limit;
                            
                            $db_target_result = $db_connection->query( $sql_target );
                            if ($db_target_result && $db_target_result->num_rows > 0) {
                                while($row = $db_target_result->fetch_object()) $rows[] = $row;
                                if (isset($rows)) foreach($rows as $row) {
                                    $a = null;
                                    if(isset($row->USER_ID) && glob('img/'.$row->USER_ID.'.*') != null) 
                                        $a = glob('img/'.$row->USER_ID.'.*');
                                    else 
                                        $a = glob('img/nopic.jpg');
                                    $res['useravatar'] = 'http://freescribbler.com/images/scribblers/thumbs_small/'.$a[0];
                                    $res['userid'] = $row->USER_ID;
                                    $res['username'] = $row->USER_NAME;
                                    $res['postid'] = $row->ARTICLE_ID;
                                    $res['postcontent'] = $row->ARTICLE_CONTENT;
                                    $res['posttime'] = $row->ARTICLE_TIMESTAMP;
                                    $res['commentnum'] = $row->COMMENT_NUM;
                                    $res['postlikes'] = $row->ARTICLE_POINTS;
                                    $res['postliked'] = $row->IS_LIKED;

                                    $sql_pics = "
                                            SELECT 
                                                p.*
                                            FROM 
                                                `article_media` m 
                                                INNER JOIN `pics` p ON m.MEDIA_ID = p.PIC_ID 
                                            WHERE 
                                                m.ARTICLE_ID = ".$row->ARTICLE_ID." AND p.IS_ACTIVE = 1
                                            ORDER BY 
                                                m.ARTICLE_MEDIA_RELATION_ID";
                                    $db_pics_result = $db_connection->query( $sql_pics );
                                    if ($db_pics_result && $db_pics_result->num_rows > 0) {
                                        $rows_pics = Array();
                                        $results_pic = Array();
                                        while($row_pics = $db_pics_result->fetch_object()) $rows_pics[] = $row_pics;
                                        foreach($rows_pics as $row_pics) {
                                            $res_pic['picname'] = 'http://freescribbler.com/images/'.$row_pics->PIC_NAME;
                                            $res_pic['picwidth'] = $row_pics->PIC_WIDTH;
                                            $res_pic['picheight'] = $row_pics->PIC_HEIGHT;
                                            $results_pic[] = $res_pic;
                                        }
                                        $res['pics'] = $results_pic;
                                    }
                                    else {
                                        $res['pics'] = -1;
                                    }
                                    $posts[] = $res;
                                }
                                $success = true;
                                $result['error'] = 0;
                                $result['response'] = $db_target_result->num_rows.' user got';
                                $result['data'] = $posts;
                                $result['lastpost'] = $res['postid'];
                            }
                            else {
                                $result['error'] = 12;   
                                $result['response'] = 'posts not exist';
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
                break;
            
            case "GETCOMMENTS":
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode']) && isset($_GET['postid'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = ".$_GET['connectid']." and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $db_row = $db_result->fetch_object();
                            $sql_privat = 'SELECT 
                                        u.USER_PRIVACY,
                                        case
                                            WHEN f2.MY_SUBSCRIBER IS NOT NULL THEN 1
                                            ELSE 0
                                        end as MY_SUBSCRIBER,
                                        case
                                            WHEN i2.MY_IGNORER IS NOT NULL THEN 1
                                            ELSE 0
                                        end as MY_IGNORER
                                    FROM 
                                        `user` u
                                        INNER JOIN `article` a ON a.USER_ID = u.USER_ID
                                        LEFT JOIN (SELECT 1 as MY_SUBSCRIBER, USER_ID FROM `friends` WHERE REFERENCED_USER_ID = '.$_GET['connectid'].') f2 ON u.USER_ID = f2.USER_ID
                                        LEFT JOIN (SELECT 1 as MY_IGNORER, USER_ID FROM `ignor` WHERE REFERENCED_USER_ID = '.$_GET['connectid'].') i2 ON u.USER_ID = i2.USER_ID
                                        
                                    where 
                                        a.ARTICLE_ID = '.$_GET['postid'];
                            $db_privat_result = $db_connection->query($sql_privat);
                            if ($db_privat_result && $db_privat_result->num_rows > 0) {
                                $privat_row = $db_privat_result->fetch_object();
                                
                                if ($db_row->ROLE_ID == 3 or ($privat_row->MY_IGNORER == 0 and ($privat_row->USER_PRIVACY == 0 or $privat_row->MY_SUBSCRIBER == 1))) {

                                    $offsetid = 999999999999;
                                    if (isset($_GET['offset'])) $offsetid = $_GET['offset'];
                                    $limit = 10;
                                    if (isset($_GET['limit'])) $limit = $_GET['limit'];

                                    $sql_target = '
                                            SELECT 
                                                u.USER_NAME, 
                                                u.USER_ID,
                                                c.comment_id,
                                                c.comment_text,
                                                c.comment_timestamp,
                                                c.comment_insert_ip
                                            FROM 
                                                `comments` c
                                                INNER JOIN `user` u ON c.user_id = u.USER_ID 
                                            WHERE 
                                                c.ARTICLE_ID = '.$_GET['postid'].' and c.comment_id < '.$offsetid.'
                                            ORDER BY 
                                                c.comment_id desc
                                            LIMIT '.$limit;
                                    $db_target_result = $db_connection->query( $sql_target );
                                    if ($db_target_result && $db_target_result->num_rows > 0) {
                                        while($row = $db_target_result->fetch_object()) $rows[] = $row;
                                        if (isset($rows)) foreach($rows as $row) {
                                            $a = null;
                                            if(isset($row->USER_ID) && glob('img/'.$row->USER_ID.'.*') != null) 
                                                $a = glob('img/'.$row->USER_ID.'.*');
                                            else 
                                                $a = glob('img/nopic.jpg');
                                            $res['useravatar'] = 'http://freescribbler.com/images/scribblers/thumbs_small/'.$a[0];
                                            $res['userid'] = $row->USER_ID;
                                            $res['username'] = $row->USER_NAME;
                                            $res['commentid'] = $row->comment_id;
                                            $res['commentcontent'] = $row->comment_text;
                                            $res['commenttime'] = $row->comment_timestamp;

                                            $comments[] = $res;
                                        }
                                        $success = true;
                                        $result['error'] = 0;
                                        $result['response'] = $db_target_result->num_rows.' comments got';
                                        $result['data'] = $comments;
                                        $result['lastcomment'] = $res['commentid'];
                                    }
                                    else {
                                        $result['error'] = 15;
                                        $result['response'] = 'no comments exist';
                                    }
                                }
                                else {
                                    $result['error'] = 13;
                                    $result['response'] = ' data is hidden';
                                }
                            }
                            else {
                                $result['error'] = 14;
                                $result['response'] = 'user not exist';
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
                break;
            
            case "GETEVENTS":
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = ".$_GET['connectid']." and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $offsetid = 999999999999;
                            if (isset($_GET['offset'])) $offsetid = $_GET['offset'];
                            $limit = 30;
                            if (isset($_GET['limit'])) $limit = $_GET['limit'];

                            $sql_target = '
                                    SELECT 
                                        u.USER_NAME, 
                                        u.USER_ID,
                                        e.EVENT_ID,
                                        e.EVENT_ART,
                                        e.EVENT_TIMESTAMP,
                                        e.ARTICLE_ID,
                                        e.COMMENT_ID,
                                        a.ARTICLE_CONTENT,
                                        c.comment_text
                                    FROM 
                                        `event` e
                                        INNER JOIN `user` u ON u.USER_ID = e.SUBJECT_USER_ID
                                        LEFT JOIN `article` a ON a.ARTICLE_ID = e.ARTICLE_ID
                                        LEFT JOIN `comments` c ON c.comment_id = e.COMMENT_ID
                                    WHERE 
                                        e.OBJECT_USER_ID = '.$_GET['connectid'].' and e.EVENT_ID < '.$offsetid.'
                                    ORDER BY 
                                        e.EVENT_ID desc
                                    LIMIT '.$limit;
                            $db_target_result = $db_connection->query( $sql_target );
                            if ($db_target_result && $db_target_result->num_rows > 0) {
                                while($row = $db_target_result->fetch_object()) $rows[] = $row;
                                if (isset($rows)) foreach($rows as $row) {
                                    $a = null;
                                    if(isset($row->USER_ID) && glob('img/'.$row->USER_ID.'.*') != null) 
                                        $a = glob('img/'.$row->USER_ID.'.*');
                                    else 
                                        $a = glob('img/nopic.jpg');
                                    $res['useravatar'] = 'http://freescribbler.com/images/scribblers/thumbs_small/'.$a[0];
                                    $res['userid'] = $row->USER_ID;
                                    $res['username'] = $row->USER_NAME;
                                    $res['eventid'] = $row->EVENT_ID;
                                    $res['eventart'] = $row->EVENT_ART;
                                    $res['eventtime'] = $row->EVENT_TIMESTAMP;
                                    $res['articleid'] = $row->ARTICLE_ID;
                                    $res['commentid'] = $row->COMMENT_ID;
                                    $res['articlecontent'] = $row->ARTICLE_CONTENT;
                                    $res['commentcontent'] = $row->comment_text;
                                    
                                    if ($res['articleid'] != -1) {
                                        $sql_pics = "
                                                SELECT 
                                                    p.*
                                                FROM 
                                                    `article_media` m 
                                                    INNER JOIN `pics` p ON m.MEDIA_ID = p.PIC_ID 
                                                WHERE 
                                                    m.ARTICLE_ID = ".$row->ARTICLE_ID." AND p.IS_ACTIVE = 1
                                                ORDER BY 
                                                    m.ARTICLE_MEDIA_RELATION_ID";
                                        $db_pics_result = $db_connection->query( $sql_pics );
                                        if ($db_pics_result && $db_pics_result->num_rows > 0) {
                                            $rows_pics = Array();
                                            $results_pic = Array();
                                            while($row_pics = $db_pics_result->fetch_object()) $rows_pics[] = $row_pics;
                                            foreach($rows_pics as $row_pics) {
                                                $res_pic['picname'] = 'http://freescribbler.com/images/'.$row_pics->PIC_NAME;
                                                $res_pic['picwidth'] = $row_pics->PIC_WIDTH;
                                                $res_pic['picheight'] = $row_pics->PIC_HEIGHT;
                                                $results_pic[] = $res_pic;
                                            }
                                            $res['pics'] = $results_pic;
                                        }
                                        else {
                                            $res['pics'] = -1;
                                        }
                                    }
                                    else {
                                        $res['pics'] = -1;
                                    }

                                    $events[] = $res;
                                }
                                $success = true;
                                $result['error'] = 0;
                                $result['response'] = $db_target_result->num_rows.' events got';
                                $result['data'] = $events;
                                $result['lastevent'] = $res['eventid'];
                            }
                            else {
                                $result['error'] = 15;
                                $result['response'] = 'no events exist';
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
                break;
                
            case "FINDUSER":
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode']) && isset($_GET['username'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = '".$_GET['connectid']."' and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $lastUser = 9999999999;
                            if (isset($_GET['offset'])) $lastUser = $_GET['offset']; 
                            $userLimit = 30;
                            if (isset($_GET['limit'])) $userLimit = $_GET['limit']; 

                            $sql_target = 'SELECT 
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
                                        FROM `user` u
                                        LEFT JOIN (SELECT 1 as ME_SUBSCRIBED, REFERENCED_USER_ID FROM `friends` WHERE USER_ID = '.$_GET['connectid'].') f1 ON u.USER_ID = f1.REFERENCED_USER_ID
                                        LEFT JOIN (SELECT 1 as MY_SUBSCRIBER, USER_ID FROM `friends` WHERE REFERENCED_USER_ID = '.$_GET['connectid'].') f2 ON u.USER_ID = f2.USER_ID
                                        LEFT JOIN (SELECT 1 as ME_IGNORED, REFERENCED_USER_ID FROM `ignor` WHERE USER_ID = '.$_GET['connectid'].') i1 ON u.USER_ID = i1.REFERENCED_USER_ID 
                                        LEFT JOIN (SELECT 1 as MY_IGNORER, USER_ID FROM `ignor` WHERE REFERENCED_USER_ID = '.$_GET['connectid'].') i2 ON u.USER_ID = i2.USER_ID
                                        WHERE u.USER_ID < '.$lastUser.' and i2.MY_IGNORER is null and (u.USER_PRIVACY = 0 or f2.MY_SUBSCRIBER is not null) and u.USER_NAME like "%'.$_GET['username'].'%"
                                        ORDER BY u.USER_ID desc limit '.$userLimit;
                            $db_target_result = $db_connection->query( $sql_target );
                            if ($db_target_result && $db_target_result->num_rows > 0) {
                                while($row = $db_target_result->fetch_object()) $rows[] = $row;
                                if (isset($rows)) foreach($rows as $row) {
                                    $a = null;
                                    if(isset($row->USER_ID) && glob('img/'.$row->USER_ID.'.*') != null)
                                        $a = glob('img/'.$row->USER_ID.'.*');
                                    else
                                        $a = glob('img/nopic.jpg');
                                    $res['userid'] = $row->USER_ID;
                                    $res['useravatar'] = 'http://freescribbler.com/images/scribblers/thumbs_small/'.$a[0];
                                    $res['username'] = $row->USER_NAME;
                                    $res['isprivat'] = $row->USER_PRIVACY;
                                    $res['mysubscriber'] = $row->MY_SUBSCRIBER;
                                    $res['mesubscribed'] = $row->ME_SUBSCRIBED;
                                    $res['myignorer'] = $row->MY_IGNORER;
                                    $res['meignored'] = $row->ME_IGNORED;

                                    $users[] = $res;
                                }
                                $success = true;
                                $result['error'] = 0;
                                $result['response'] = $db_target_result->num_rows.' user got';
                                $result['data'] = $users;
                                $result['lastuser'] = $res['userid'];
                            }
                            else {
                                $result['error'] = 10;
                                $result['response'] = 'users not exist';
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
                break;
                
            case "UPDATEFRIEND":
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode']) && isset($_GET['userid'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = '".$_GET['connectid']."' and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $sql_target = "SELECT 
                                                *
                                            FROM `friends` f WHERE f.USER_ID = ".$_GET["connectid"]." AND f.REFERENCED_USER_ID = ".$_GET['userid'];
                            $db_target_result = $db_connection->query( $sql_target );
                            if ($db_target_result && $db_target_result->num_rows > 0) {
                                $row = $db_target_result->fetch_object();
                                
                                $sql_delete = "
                                    DELETE FROM `friends` WHERE f.USER_ID = ".$_GET["connectid"]." AND f.REFERENCED_USER_ID = ".$_GET['userid'];
                                $db_erg_delete = $db_connection->query( $sql_delete );
                                if ($db_erg_delete) {
                                    $success = true;
                                    $result['error'] = 0;
                                    $result['response'] = ' friend deleted';
                                }
                                else {
                                    $result['error'] = 16;
                                    $result['response'] = ' cannot delete friend';
                                }
                            }
                            else {
                                $sql_insert = "
                                    INSERT INTO `friends` (USER_ID, REFERENCED_USER_ID) VALUES (".$_GET["connectid"].", ".$_GET['userid'].")";
                                $db_erg_insert = $db_connection->query( $sql_insert );
                                if ($db_erg_insert) {
                                    $success = true;
                                    $result['error'] = 0;
                                    $result['response'] = ' friend added';
                                }
                                else {
                                    $result['error'] = 17;
                                    $result['response'] = ' cannot add friend';
                                }
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
                break;
                
            case "UPDATEIGNOR":
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode']) && isset($_GET['userid'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = '".$_GET['connectid']."' and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $sql_target = "SELECT 
                                                *
                                            FROM `ignor` f WHERE f.USER_ID = ".$_GET["connectid"]." AND f.REFERENCED_USER_ID = ".$_GET['userid'];
                            $db_target_result = $db_connection->query( $sql_target );
                            if ($db_target_result && $db_target_result->num_rows > 0) {
                                $row = $db_target_result->fetch_object();
                                
                                $sql_delete = "
                                    DELETE FROM `ignor` WHERE f.USER_ID = ".$_GET["connectid"]." AND f.REFERENCED_USER_ID = ".$_GET['userid'];
                                $db_erg_delete = $db_connection->query( $sql_delete );
                                if ($db_erg_delete) {
                                    $success = true;
                                    $result['error'] = 0;
                                    $result['response'] = ' ignor deleted';
                                }
                                else {
                                    $result['error'] = 18;
                                    $result['response'] = ' cannot delete ignor';
                                }
                            }
                            else {
                                $sql_insert = "
                                    INSERT INTO `ignor` (USER_ID, REFERENCED_USER_ID) VALUES (".$_GET["connectid"].", ".$_GET['userid'].")";
                                $db_erg_insert = $db_connection->query( $sql_insert );
                                if ($db_erg_insert) {
                                    $success = true;
                                    $result['error'] = 0;
                                    $result['response'] = ' ignor added';
                                }
                                else {
                                    $result['error'] = 19;
                                    $result['response'] = ' cannot add ignor';
                                }
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
                
            case "UPDATELIKE":
                if (isset($_GET['connectid']) && isset($_GET['token']) && isset($_GET['clientcode']) && isset($_GET['postid'])) {
                    $sql = "SELECT * FROM `user` where USER_ID = '".$_GET['connectid']."' and TOKEN = '".$_GET['token']."'";
                    $db_result = $db_connection->query($sql);
                    if ($db_result && $db_result->num_rows > 0) {
                        if ($_GET['clientcode'] == $appcode) {
                            $sql_target = "SELECT a.*, ap.ARTICLE_POINT_ID, e.EVENT_ID FROM `article` a 
                                                LEFT JOIN `article_point` ap ON ap.USER_ID = ".$_GET['connectid']." and ap.ARTICLE_ID = a.ARTICLE_ID
                                                LEFT JOIN `event` e ON e.SUBJECT_USER_ID = ".$_GET['connectid']." and e.ARTICLE_ID = a.ARTICLE_ID
                                            where a.ARTICLE_ID = ".$_GET['postid'];
                            $db_target_result = $db_connection->query( $sql_target );
                            if ($db_target_result && $db_target_result->num_rows > 0) {
                                $row = $db_target_result->fetch_object();
                                if (isset($row->ARTICLE_POINT_ID) && $row->ARTICLE_POINT_ID !='') {
                                    $sql_delete = 
                                            "DELETE FROM `article_point` where ARTICLE_ID = ".$_GET['postid']." and USER_ID = ".$_GET['connectid'];
                                    $db_erg_delete = $db_connection->query( $sql_delete );
                                    $sql_update = "UPDATE `article` 
                                        SET ARTICLE_POINTS = ".($row->ARTICLE_POINTS - 1)."
                                        where ARTICLE_ID = ".$_GET['postid'];
                                    $db_erg_update = $db_connection->query( $sql_update );
                                    
                                    if ($db_erg_update) {
                                        $success = true;
                                        $result['error'] = 0;
                                        $result['response'] = ' likes  updated';
                                        $result['data'] = $row->ARTICLE_POINTS - 1;
                                    }
                                    else {
                                        $result['error'] = 18;
                                        $result['response'] = ' cannot update likes';
                                    }
                                }
                                else {
                                    
                                }
                            }
                            else {
                                $result['error'] = 20;
                                $result['response'] = 'post not exist';
                            }
                        }
                        else {
                            $result['error'] = 2;
                            $result['response'] = 'application can not be authorized';
                        }
                    }
                    else {
                        $result['error'] = 7;
                        $result['response'] = 'wrong userid/token combination';
                    }
                }
                else {
                    $result['error'] = 6;
                    $result['response'] = 'parameter not set';
                }
                $result['success'] = $success;
            
        }

        echo json_encode($result);
    }
    
    else {
        echo 'not a call';
    }
?>