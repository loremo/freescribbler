<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<body>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="language" content="en" />
        <meta name="verify-admitad" content="99af352f5a" />
        <meta name="dumedia-verify" content="e30e13c2deb9">
        <meta name="site-verify" content="72e5f3e919334ea96b284de43b1826c4">
        <meta http-equiv="Cache-control" content="No-Cache">
        
        <link rel="apple-touch-icon" sizes="57x57" href="/images/icons/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/images/icons/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/images/icons/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/images/icons/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/images/icons/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/images/icons/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/images/icons/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/images/icons/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/images/icons/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/images/icons/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/icons/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/images/icons/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/icons/favicon/favicon-16x16.png">
        <link rel="manifest" href="/images/icons/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/images/icons/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
</head>
<?php
        $db_servername = 'localhost';
        $db_username = 'artem';
        $db_password = 'i9l0XNvj_2';
        $datenbank = 'journalist';
        
        $db_servername2 = '85.143.217.242';
        $db_username2 = 'freescribbler';
        $db_password2 = 'i9l0XNvj';
        $datenbank2 = 'journalist';
        
        $db_connection = new mysqli($db_servername, $db_username, $db_password, $datenbank);
		$db_connection->set_charset("utf8");
        
        $pg_string = "host=85.143.217.242 port=5432 dbname=journalist user=freescribbler password=i9l0XNvj";
        $pg_connection = pg_connect($pg_string) or die('Verbindungsaufbau fehlgeschlagen: ' . pg_last_error());
         
        if ($_GET['act'] == 'article') {
        
            $mysql = "SELECT * FROM `article` where USER_ID = 78 and ARTICLE_ID < 10000";
            $db_result = $db_connection->query($mysql);
            if ($db_result && $db_result->num_rows > 0) {
                echo '1 йцу';
                while($row = $db_result->fetch_object()) $rows[] = $row;
                foreach($rows as $row) {
                    echo 'a';
                    $sql_insert = '
                        INSERT INTO article ("ARTICLE_ID", "ARTICLE_CONTENT", "ARTICLE_TIMESTAMP", "CREATION_USER_IP", "VALIDATION_FLAG", "USER_ID", "ARTICLE_CATEGORY_ID", "ARTICLE_POINTS") 
                        VALUES ('.$row->ARTICLE_ID.', \''.$row->ARTICLE_CONTENT.'\', \''.$row->ARTICLE_TIMESTAMP.'\', \''.$row->CREATION_USER_IP.'\', '.$row->VALIDATION_FLAG.', '.$row->USER_ID.', '.$row->ARTICLE_CATEGORY_ID.', '.$row->ARTICLE_POINTS.')';
                    echo $sql_insert.'</p>';
					$db_erg_insert = pg_query( $sql_insert );
                    if ($db_erg_insert) echo 'b';
                }
                echo $row->ARTICLE_ID;
            }
        
        }
        
        if ($_GET['act'] == 'users') {
            $a = 0;
            $mysql = "SELECT * FROM `user` where USER_ID < 100";
            $db_result = $db_connection->query($mysql);
            if ($db_result && $db_result->num_rows > 0) {
                echo '1 ';
                while($row = $db_result->fetch_object()) $rows[] = $row;
                foreach($rows as $row) {
                    echo 'a';
                    
                    if ($row->LAST_LOGIN) $row->LAST_LOGIN = '\''.$row->LAST_LOGIN.'\'';
                    else $row->LAST_LOGIN = 'NULL';
                    if ($row->LAST_LOGIN_BACK) $row->LAST_LOGIN_BACK = '\''.$row->LAST_LOGIN_BACK.'\'';
                    else $row->LAST_LOGIN_BACK = 'NULL';
                    if ($row->LAST_EVENTS_VIEW) $row->LAST_EVENTS_VIEW = '\''.$row->LAST_EVENTS_VIEW.'\'';
                    else $row->LAST_EVENTS_VIEW = 'NULL';
                    if ($row->LAST_TALKS_VIEW) $row->LAST_TALKS_VIEW = '\''.$row->LAST_TALKS_VIEW.'\'';
                    else $row->LAST_TALKS_VIEW = 'NULL';
                    if ($row->USER_PAYPAL) $row->USER_PAYPAL = '\''.$row->USER_PAYPAL.'\'';
                    else $row->USER_PAYPAL = 'NULL';
                    if ($row->USER_WEBMONEY) $row->USER_WEBMONEY = '\''.$row->USER_WEBMONEY.'\'';
                    else $row->USER_WEBMONEY = 'NULL';
                    
                    
                    $sql_insert = 'INSERT INTO  users (
                                "USER_ID" ,
                                "USER_NAME" ,
                                "USER_PASSWORD" ,
                                "USER_SALT" ,
                                "USER_EMAIL" ,
                                "USER_FIRSTNAME" ,
                                "USER_SURNAME" ,
                                "USER_DESC" ,
                                "USER_PAYPAL" ,
                                "USER_WEBMONEY" ,
                                "USER_MONEY_PRIVACY" ,
                                "ROLE_ID" ,
                                "USER_PIC_LIMIT" ,
                                "ACTIVATION_FLAG" ,
                                "ACTIVATION_SALT" ,
                                "USER_TIMESTAMP" ,
                                "USER_PRIVACY" ,
                                "LAST_LOGIN" ,
                                "LAST_LOGIN_BACK" ,
                                "LAST_EVENTS_VIEW" ,
                                "LAST_TALKS_VIEW" ,
                                "NOTICE_FRIENDS" ,
                                "NOTICE_COMMENTS" ,
                                "NOTICE_LINKS" ,
                                "NOTICE_COM_LINKS" ,
                                "NOTICE_DA" ,
                                "NOTICE_SUMMARY" ,
                                "USER_COEF" ,
                                "VIEWED" ,
                                "EARNED"
                                )
                                VALUES (
                                '.$row->USER_ID.',  \''.$row->USER_NAME.'\',  \''.$row->USER_PASSWORD.'\',  \''.$row->USER_SALT.'\',  \''.$row->USER_EMAIL.'\',  \''.$row->USER_FIRSTNAME.'\',  \''.$row->USER_SURNAME.'\',  \''.nl2br(htmlspecialchars($row->USER_DESC)).'\',  '.$row->USER_PAYPAL.',  '.$row->USER_WEBMONEY.',  '.$row->USER_MONEY_PRIVACY.',  '.$row->ROLE_ID.',  '.$row->USER_PIC_LIMIT.',  '.$row->ACTIVATION_FLAG.',  \''.$row->ACTIVATION_SALT.'\', 
                                \''.$row->USER_TIMESTAMP.'\' , '.$row->USER_PRIVACY.', '.$row->LAST_LOGIN.' , '.$row->LAST_LOGIN_BACK.' , '.$row->LAST_EVENTS_VIEW.' , '.$row->LAST_TALKS_VIEW.' ,  '.$row->NOTICE_FRIENDS.',  '.$row->NOTICE_COMMENTS.',  '.$row->NOTICE_LINKS.',  '.$row->NOTICE_COM_LINKS.',  '.$row->NOTICE_DA.',  '.$row->NOTICE_SUMMARY.',  '.$row->USER_COEF.',  '.$row->VIEWED.',  '.$row->EARNED.')';
                    echo $sql_insert.'</p>';
                    $db_erg_insert = pg_query( $sql_insert );
                    if ($db_erg_insert) echo 'b';
                    $a = $row->ARTICLE_ID;
                }
                echo $a;
            }
        }    
            
        
        if ($_GET['act'] == 'albums') {
            $a = 0;
            $mysql = "SELECT * FROM `albums` where USER_ID < 1000";
            $db_result = $db_connection->query($mysql);
            if ($db_result && $db_result->num_rows > 0) {
                echo '1 ';
                while($row = $db_result->fetch_object()) $rows[] = $row;
                foreach($rows as $row) {
                    echo 'a';
                    
                    $sql_insert = 'INSERT INTO  albums (
                        "ALBUM_ID" ,
                        "ALBUM_NAME" ,
                        "USER_ID" ,
                        "ALBUM_PRIVACY" ,
                        "ALBUM_CREATION_TIME"
                        )
                        VALUES (
                        '.$row->ALBUM_ID.',  \'Downloads\',  '.$row->USER_ID.',  '.$row->ALBUM_PRIVACY.', 
                        \''.$row->ALBUM_CREATION_TIME.'\')';
                    echo $sql_insert.'</p>';
                    $db_erg_insert = pg_query( $sql_insert );
                    if ($db_erg_insert) echo 'b';
                    $a = $row->ARTICLE_ID;
                }
                echo $a;
            }
        
        }
        
        if ($_GET['act'] == 'articlepoint') {
            $a = 0;
            $mysql = "SELECT * FROM `article_point` where USER_ID < 100";
            $db_result = $db_connection->query($mysql);
            if ($db_result && $db_result->num_rows > 0) {
                echo '1 ';
                while($row = $db_result->fetch_object()) $rows[] = $row;
                foreach($rows as $row) {
                    echo 'a';
                    if ($row->ARTICLE_POINT_TIMESTAMP) $row->ARTICLE_POINT_TIMESTAMP = '\''.$row->ARTICLE_POINT_TIMESTAMP.'\'';
                    else $row->ARTICLE_POINT_TIMESTAMP = 'NULL';
                    if ($row->CREATION_USER_IP) $row->CREATION_USER_IP = '\''.$row->CREATION_USER_IP.'\'';
                    else $row->CREATION_USER_IP = 'NULL';
                    
                    $sql_insert = 'INSERT INTO  article_point (
                                        "ARTICLE_POINT_ID" ,
                                        "ARTICLE_POINT_TIMESTAMP" ,
                                        "CREATION_USER_IP" ,
                                        "ARTICLE_ID" ,
                                        "USER_ID"
                                        )
                                        VALUES (
                                        '.$row->ARTICLE_POINT_ID.',  '.$row->ARTICLE_POINT_TIMESTAMP.',  '.$row->CREATION_USER_IP.',  '.$row->ARTICLE_ID.', 
                                        '.$row->USER_ID.')';
                    echo $sql_insert.'</p>';
                    $db_erg_insert = pg_query( $sql_insert );
                    if ($db_erg_insert) echo 'b';
                    $a = $row->ARTICLE_ID;
                }
                echo $a;
            }
        
        }     
        
        if ($_GET['act'] == 'bill') {
            $a = 0;
            $mysql = "SELECT * FROM `bill`";
            $db_result = $db_connection->query($mysql);
            if ($db_result && $db_result->num_rows > 0) {
                echo '1 ';
                while($row = $db_result->fetch_object()) $rows[] = $row;
                foreach($rows as $row) {
                    echo 'a';
                    $sql_insert = 'INSERT INTO bill (
                                    "BILL_ID" ,
                                    "USER_ID" ,
                                    "BILL_TIMESTAMP" ,
                                    "BILL_IP" ,
                                    "WALLET_TYPE" ,
                                    "WALLET_ID" ,
                                    "BILL_AMOUNT" ,
                                    "BILL_PAID"
                                    )
                                    VALUES (
                                    '.$row->BILL_ID.',  '.$row->USER_ID.',  \''.$row->BILL_TIMESTAMP.'\',  \''.$row->BILL_IP.'\', 
                                        \''.$row->WALLET_TYPE.'\', \''.$row->WALLET_ID.'\', '.$row->BILL_AMOUNT.', '.$row->BILL_PAID.')';
                    echo $sql_insert.'</p>';
                    $db_erg_insert = pg_query( $sql_insert );
                    if ($db_erg_insert) echo 'b';
                    $a = $row->ARTICLE_ID;
                }
                echo $a;
            }
        
        }   
        
        if ($_GET['act'] == 'comments') {
            $a = 0;
            $mysql = "SELECT * FROM `comments` where comment_id < 1000";
            $db_result = $db_connection->query($mysql);
            if ($db_result && $db_result->num_rows > 0) {
                echo '1 ';
                while($row = $db_result->fetch_object()) $rows[] = $row;
                foreach($rows as $row) {
                    echo 'a';
                    if ($row->user_id) $row->user_id = $row->user_id;
                    else $row->user_id = 'NULL';
                    
                    $sql_insert = 'INSERT INTO  comments (
                                    "comment_id" ,
                                    "ARTICLE_ID" ,
                                    "user_id" ,
                                    "comment_timestamp" ,
                                    "comment_insert_ip" ,
                                    "comment_text" ,
                                    "comment_activ"
                                    )
                                    VALUES (
                                    '.$row->comment_id.',  '.$row->ARTICLE_ID.',  '.$row->user_id.',  \''.$row->comment_timestamp.'\', 
                                        \''.$row->comment_insert_ip.'\', \''.$row->comment_text.'\', '.$row->comment_activ.')';
                    echo $sql_insert.'</p>';
                    $db_erg_insert = pg_query( $sql_insert );
                    if ($db_erg_insert) echo 'b';
                    $a = $row->ARTICLE_ID;
                }
                echo $a;
            }
        
        }   
		
        if ($_GET['act'] == 'pics') {
            $a = 0;
            $mysql = "SELECT * FROM `pics` where PIC_ID < 1000";
            $db_result = $db_connection->query($mysql);
            if ($db_result && $db_result->num_rows > 0) {
                echo '1 ';
                while($row = $db_result->fetch_object()) $rows[] = $row;
                foreach($rows as $row) {
                    echo 'a';
                    if ($row->PIC_DESC) $row->PIC_DESC = '\''.$row->PIC_DESC.'\'';
                    else $row->PIC_DESC = 'NULL';
                    
                    $sql_insert = 'INSERT INTO  pics (
									"PIC_ID" ,
									"PIC_NAME" ,
									"PIC_DESC" ,
									"ALBUM_ID" ,
									"USER_ID" ,
									"PIC_HEIGHT" ,
									"PIC_WIDTH" ,
									"PIC_SIZE" ,
									"PIC_CREATION_TIME" ,
									"IS_ACTIVE")
									VALUES (
									'.$row->PIC_ID.',  \''.$row->PIC_NAME.'\',  '.$row->PIC_DESC.',  '.$row->ALBUM_ID.', 
                                        '.$row->USER_ID.', '.$row->PIC_HEIGHT.', '.$row->PIC_WIDTH.', '.$row->PIC_SIZE.', \''.$row->PIC_CREATION_TIME.'\', '.$row->IS_ACTIVE.')';
                    echo $sql_insert.'</p>';
                    $db_erg_insert = pg_query( $sql_insert );
                    if ($db_erg_insert) echo 'b';
                    $a = $row->ARTICLE_ID;
                }
                echo $a;
            }
        
        } 
		
        if ($_GET['act'] == 'articlemedia') {
            $a = 0;
            $mysql = "SELECT * FROM `article_media` where PIC_ID < 1000";
            $db_result = $db_connection->query($mysql);
            if ($db_result && $db_result->num_rows > 0) {
                echo '1 ';
                while($row = $db_result->fetch_object()) $rows[] = $row;
                foreach($rows as $row) {
                    echo 'a';
                    if ($row->COMMENT_ID) $row->COMMENT_ID = '\''.$row->COMMENT_ID.'\'';
                    else $row->COMMENT_ID = 'NULL';
                    
                    $sql_insert = 'INSERT INTO  article_media (
									"ARTICLE_MEDIA_RELATION_ID" ,
									"ARTICLE_ID" ,
									"COMMENT_ID" ,
									"MEDIA_ID" ,
									"ARTICLE_MEDIA_RELATION_TIMESTAMP" ,
									"USER_ID"
									)
									VALUES (
									'.$row->ARTICLE_MEDIA_RELATION_ID.',  '.$row->ARTICLE_ID.',  '.$row->COMMENT_ID.',  '.$row->MEDIA_ID.', 
                                        '.$row->ARTICLE_MEDIA_RELATION_TIMESTAMP.', '.$row->USER_ID.')';
                    echo $sql_insert.'</p>';
                    $db_erg_insert = pg_query( $sql_insert );
                    if ($db_erg_insert) echo 'b';
                    $a = $row->ARTICLE_ID;
                }
                echo $a;
            }
        
        }
?>
</body>
</html>