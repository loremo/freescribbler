<?php
    if (isset($_POST['act']) && (strtoupper($_POST['act']) == 'LOGIN'               // done
                              || strtoupper($_POST['act']) == 'GETUSER'             // done
                              || strtoupper($_POST['act']) == 'GETFOLLOWERS'        // done
                              || strtoupper($_POST['act']) == 'GETFOLLOWED'         // done
                              || strtoupper($_POST['act']) == 'GETMYIGNORED'        // done
                              || strtoupper($_POST['act']) == 'GETONEPOST'          // done
                              || strtoupper($_POST['act']) == 'GETUSERPOSTS'        // done
                              || strtoupper($_POST['act']) == 'GETBESTPOSTS'        // done
                              || strtoupper($_POST['act']) == 'GETFRIENDSPOSTS'     // done
                              || strtoupper($_POST['act']) == 'GETHASHPOSTS'        // done
                              || strtoupper($_POST['act']) == 'GETCOMMENTS'         // done
                              || strtoupper($_POST['act']) == 'GETEVENTS'
                              || strtoupper($_POST['act']) == 'FINDUSER'
                              || strtoupper($_POST['act']) == 'UPDATEFRIEND'
                              || strtoupper($_POST['act']) == 'UPDATELIKE'
                              || strtoupper($_POST['act']) == 'UPDATEIGNOR'
                              || strtoupper($_POST['act']) == 'UPDATEPOST'
                              || strtoupper($_POST['act']) == 'UPDATEPROFILE'
                              || strtoupper($_POST['act']) == 'DELETEPOST'
                              || strtoupper($_POST['act']) == 'DELETECOMMENT'
                              || strtoupper($_POST['act']) == 'CREATECOMMENT'
                              || strtoupper($_POST['act']) == 'CREATEPOST')) {
        
        // Create connection
        $act = strtoupper($_POST['act']);
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $datenbank = 'journalist';
        $result = null;
        $act = '';
        $appcode = '123123123';
        $success = false;

        $db_connection = new mysqli($servername, $username, $password, $datenbank);
         
        switch (strtoupper($act)) {
            case "LOGIN":
                
                $sql = "SELECT * FROM `user` where USER_NAME = '".$_POST['username']."'";
                $db_result = $db_connection->query($sql);
                if ($db_result) {
                    if ($db_result->num_rows > 0) {
                        $user = $db_result->fetch_object();
                        if ($user->USER_PASSWORD == md5($user->USER_SALT.$_POST['userpassword'])) {
                            if ($_POST['clientcode'] == $appcode) {
                                $token = md5(uniqid(mt_rand(), true));
                                $token_sql = 'UPDATE `user` SET token = "'.$token.'" WHERE USER_ID = '.$user->USER_ID;
                                if ($db_connection->query($token_sql)) {
                                    $success = true;
                                    $result['userid'] = $user->USER_ID;
                                    $result['token'] = $token;
                                    $result['error'] = 0;
                                    $result['response'] = 'token sent';
                                }
                                else {
                                    $result['userid'] = -1;
                                    $result['token'] = -1;
                                    $result['error'] = 1;
                                    $result['response'] = 'technical error. Can not create token';
                                }
                            }
                            else {
                                $result['userid'] = -1;
                                $result['token'] = -1;
                                $result['error'] = 2;
                                $result['response'] = 'application can not be authorized';
                            }
                        }
                        else {
                            $result['userid'] = -1;
                            $result['token'] = -1;
                            $result['error'] = 3;
                            $result['response'] = 'user password is wrong';
                        }
                    }
                    else {
                        $result['userid'] = -1;
                        $result['token'] = -1;
                        $result['error'] = 4;
                        $result['response'] = 'user not exists';
                    }
                }
                else {
                    $result['userid'] = -1;
                    $result['token'] = -1;
                    $result['error'] = 5;
                    $result['response'] = 'cannot connect to server';
                }
                $result['success'] = $success;
                
                break;
            case "LOGOUT":
                $sql = "SELECT * FROM `user` where USER_ID = '".$_POST['userid']."'";
                $db_result = $db_connection->query($sql);
                if ($db_result) {
                    if ($db_result->num_rows > 0) {
                        $user = $db_result->fetch_object();
                        if ($user->TOKEN == $_POST['token']) {
                            if ($_POST['clientcode'] == $appcode) {
                                $token = md5(uniqid(mt_rand(), true));
                                $token_sql = 'UPDATE `user` SET token = "'.$token.'" WHERE USER_ID = '.$user->USER_ID;
                                if ($db_connection->query($token_sql)) {
                                    $success = true;
                                    $result['token'] = $token;
                                    $result['error'] = 0;
                                    $result['response'] = 'token sent';
                                }
                                else {
                                    $result['token'] = -1;
                                    $result['error'] = 1;
                                    $result['response'] = 'technical error. Can not create token';
                                }
                            }
                            else {
                                $result['token'] = -1;
                                $result['error'] = 2;
                                $result['response'] = 'application can not be authorized';
                            }
                        }
                        else {
                            $result['token'] = -1;
                            $result['error'] = 3;
                            $result['response'] = 'user password is wrong';
                        }
                    }
                    else {
                        $result['token'] = -1;
                        $result['error'] = 4;
                        $result['response'] = 'user not exists';
                    }
                }
                else {
                    $result['token'] = -1;
                    $result['error'] = 5;
                    $result['response'] = 'cannot connect to server';
                }
                $result['success'] = $success;
                
                break;
            case "Kuchen":
                echo "i ist Kuchen";
                break;
        }

        $sql = "SELECT * FROM `user` where USER_NAME = '".$_POST['user']."'";
        $db_erg = null;
        mysqli_query($db_erg, $sql);
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
    }
?>