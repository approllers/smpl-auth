<?php	
// stripped down version of smpl.php just for aggregator app
// last updated October 31th, 2015
// make sure session_start() has already been established - if not set it here






#######################################################################
#########################                       #######################
######################### SESSION NOTIFICATIONS #######################
#########################                       #######################
#######################################################################

function n($action, $page, $type = '', $message = '') {
	switch($action) {
		case '+':
			$_SESSION['n'][$page][$type][] = $message;
			return true;
			break;
		case '-':
			if ($type != '') {
				unset($_SESSION['n'][$page][$type]);
			}
			else {
				$_SESSION['n'][$page] = array();
				return true;
			}
			break;
		case '?':
			if (isset($_SESSION['n'][$page][$type])) {
				return true;
			}
			else { return false; }
			break;
		case '|':
			if (
				isset($_SESSION['n'][$page][$type]) &&
				!empty($_SESSION['n'][$page][$type])
			) {
				return $_SESSION['n'][$page][$type];
			}
			else { return array(); }
			break;
			
	}
} // end function n()






#######################################################################
##########################                      #######################
##########################     SESSION FORMS    #######################
##########################                      #######################
#######################################################################

function i($action, $page = '', $input = '') {
	switch($action) {
		case '+':
			clean($input);
			if (isset($_SESSION['i'][$page])) {
				$_SESSION['i'][$page] = array_merge($_SESSION['i'][$page], $input);
				//$input + $_SESSION['i'][$page];
			}
			else { $_SESSION['i'][$page] = $input; }
			return true;
			break;
		case '+@':
			clean($input);
			$_SESSION['i'][$page] = $input;
			return true;
			break;
		case '-@':
			foreach ($input as $val) {
				unset($_SESSION['i'][$page][$val]);
			}
			return true;
			break;
		case '@':
			if (isset($_SESSION['i'][$page])) {
				return $_SESSION['i'][$page];
			}
			else { return false; }
			break;
		case '-':
			if ($page == '') {
				$_SESSION['i'] = array();
				return true;
			}
			elseif ($input != '') {
				if (is_array($input)) {
					foreach ($input as $k => $v) {
						unset($_SESSION['i'][$page][$v]);
					}
				}
				else { unset($_SESSION['i'][$page][$input]); }
				return true;
			}
			else {
				$_SESSION['i'][$page] = array();
				return true;
			}
			break;
		case '|':
			if (isset($_SESSION['i'][$page][$input])) {
				return $_SESSION['i'][$page][$input];
			}
			//else { return false; }
			break;
		case '?':
			if (isset($_SESSION['i'][$page][$input])) {
				return true;
			}
			else { return false; }
			break;
	}
} // end function i()






#######################################################################
#########################                      ########################
#########################   CLEAN FORM INPUT   ########################
#########################                      ########################
#######################################################################

function clean($input) {
	global $conn;
	if (is_array($input)) {
		foreach ($input as $key => $val) {
			$input[$key] = mysqli_real_escape_string($conn, $val);
			$input[$key] = trim($input[$key]);
			$input[$key] = rtrim($input[$key]);
		}
	}
	else {
		$input = mysqli_real_escape_string($conn, $input);
		$input = trim($input);
		$input = rtrim($input);
	}
} // end function clean()
	
	







#######################################################################
##########################                      #######################
##########################   FORM VALIDATION    #######################
##########################                      #######################
#######################################################################

	function valid($type, $input, $min = 1, $max = 999999999) {
		if ($type == 'email') {
			// eregi("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email_string)
			// eregi("^[a-z0-9\._-]+@([a-z0-9][a-z0-9-]*[a-z0-9]\.)+([a-z]+\.)?([a-z]+)$", $email_string)
			if (!preg_match('/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/i', $input)) {
				if(preg_match('/^.+\@(\[?)[-a-zA-Z0-9\.]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/i', $input)) {
					return true;
				} else { return false; }
			} else { return false; }
		}
				
		if ($type == 'text') {
			if (strlen($input) >= $min && strlen($input) <= $max) {
				return true;
			}
			else { return false; }
		}
		
		if ($type == 'username') {
			if (strlen($input) >= $min && strlen($input) <= $max) {
				if (preg_match('/^[a-zA-Z0-9._-]+/', $input)) {
					return true;
				}
				else { return false; }
			}
			else { return false; }
		}
		
		if ($type == 'int') {
			if (preg_match('/^\d+$/', $input)) {
				return true;
			}
			else { return false; }
		}
		
		if ($type == 'regex') {
			if (preg_match($min, $input)) {
				return true;
			}
			else { return false; }
		}
		if ($type == 'url') {
			if (preg_match('_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS', $input)) {
				return true;
			}
			else { return false; }
		}
		
		
	} // end validate functions
	
	
	





#######################################################################
##########################                      #######################
########################## USER AUTHENTICATION  #######################
##########################                      #######################
#######################################################################

function auth(
			$action = '',
			$array = array(),
			$user_id = ''
) {
	global $conn;
	if ($action == '+') {
		// add user
		// $array should be an associative array with all the data in it
		//EXAMPLE:
		// $addinfo = array('regdate' => timestamp(), 'ip' => $_SERVER['REMOTE_ADDR'], 'active' => 1);
		// auth('+', $addinfo);
		// 
		
		$sql = '
			INSERT INTO
				`users` (
					';
		$cols = array_keys($array);
		$cols = implode('`, `', $cols);
		$sql .= '`'. $cols .'`
				)
			VALUES (
				';
		
		$vals = array_values($array);
		
		foreach($vals as $k => $v) { if (is_int($v)) {} else { $vals[$k] = '"'. $v .'"'; } }
		$vals = implode(', ', $vals);
		$sql .= '
				'. $vals .'
				)
		';
		$conn->query($sql) OR die('Error in query: '. mysqli_error($conn));
		if ($conn->insert_id) { return $conn->insert_id; }
		else { return false; }
		
	}
	
	if ($action == '?') {
		// pass array of key=>val parameters to check if there is a match in the users table
		// it will return the user ID if all parameters match or false if no match
		// Example: auth('?', array('email' => 'someaddy@gmail.org', 'password' => md5('Bingo5!')));
		// 			>> returns userID if all criteria match or false if not all match
		
		if (!$array || !is_array($array)) { return false; }
		else {
			$sql = '
					SELECT
						`id`
					FROM
						`users`
					WHERE
				';
			$where = array();
			$active = false;
			foreach($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $or_condition) {
						if ($k == 'active') { $active = true; }
						$str = '`'. $k .'` = ';
						if (!is_int($or_condition)) { $str .='"'. $or_condition .'"'; }
						else { $str .= $or_condition; }
						$or[] = $str;
					}
					$orstring = implode(' OR ', $or);
					$orstring = ' ( '. $orstring .' ) ';
					$where[] = $orstring;
				}
				else {
					if ($k == 'active') { $active = true; }
					$str = '`'. $k .'` = ';
					if (!is_int($v)) { $str .='"'. $v .'"'; }
					else { $str .= $v; }
					$where[] = $str;
				}
			}
			if (!$active) { $where[] = '`active` = 1'; }
			$sql .= implode(' AND ', $where);
			$sql .= '
					ORDER BY
						`updated`
							DESC
					LIMIT
						1
			';
			//print '<pre>'; print_r($sql); print '</pre>';
			$result = $conn->query($sql) OR die('Error in query: '. mysqli_error($conn));
			if ($result->num_rows > 0) {
				$user = $result->fetch_assoc();
				return $user['id'];
			}
			else { return false; }
		}
	}
	
	
	if ($action == '@') {
		// return array of userid requested data like username, first/last name, session id, password hash, group_id, last timestamp, etc, if user is not logged in
		//EXAMPLE $user = auth('@', array(id' => 5145)); returns array of data related to that specific user id
		if (empty($array) || !is_array($array)) { return false; }
		else {
			$sql = '
					SELECT
						u.id,
						u.email,
						u.password,
						u.username,
						u.firstname,
						u.lastname,
						u.displayname,
						u.group_id,
						u.ip,
						u.created,
						u.updated,
						u.cookie,
						u.token,
						u.session_id,
						u.last_login,
						u.active,
						g.name AS group_name,
						g.slug AS group_slug
					FROM
						`users` AS u
					INNER JOIN
						user_groups AS g
					ON
						u.group_id = g.id
					WHERE
				';
			$where = array();
			$or = array();
			$active = false;
			foreach($array as $k => $v) {
				/*if ($k == 'active') {
					$active = true;
					if ($v == '0/1') { $active = true;/*'; }
				}*/
				if (is_array($v)) {
					foreach ($v as $or_condition) {
						if ($k == 'active') { $active = true; }
						if ($k == 'id') { $k = 'u`.`'. $k; }
						$str = '`'. $k .'` = ';
						if (!is_int($or_condition)) { $str .='"'. $or_condition .'"'; }
						else { $str .= $or_condition; }
						$or[] = $str;
					}
					$orstring = implode(' OR ', $or);
					$orstring = ' ( '. $orstring .' ) ';
					$where[] = $orstring;
				}
				else {
					if ($k == 'active') { $active = true; }
					if ($k == 'id') { $k = 'u`.`'. $k; }
					$str = '`'. $k .'` = ';
					if (!is_int($v)) { $str .='"'. $v .'"'; }
					else { $str .= $v; }
					$where[] = $str;
				}
			}
			if (!$active) { $where[] = '`active` = 1'; }
			$sql .= implode(' AND ', $where);
			$sql .= '
					ORDER BY
						`updated`
							DESC
					LIMIT
						1
			';
			//print '<pre>'; print_r($sql); print '</pre>';
			$result = $conn->query($sql) OR die('Error in query: '. mysqli_error($conn));
			if ($result->num_rows > 0) {
				return $result->fetch_assoc();
			}
			else { return false; }
		}
	}
	
	if ($action == '^') {
		// Usage: Update a single field for the userID or many fields at once
		// Example: update userid 5124 active = 1
		//			>>> auth('^', 5124, 'active', 1)
		// Example: update userid 5124 password and username
		//			>>> auth('^', 5124, array('password' => md5('Bingo5!'), 'username' = 'new_user_name'));
		// Example: update userid 5124 sessionid = 2jf9fjf207aAKg97g6t2BFh293fHF37gAjb83iw
		//			>>> auth('^', 5124, 'sessionid', '2jf9fjf207aAKg97g6t2BFh293fHF37gAjb83iw')
		
		if (is_array($array) && $user_id != '') {
			$sql = '
				UPDATE
					`users`
				SET
			';
			$set = array();
			foreach ($array as $k => $v) {
				$str = '`'. $k .'` = ';
				if (is_int($v)) {} else { $str .= '"'; }
				$str .= $v;
				if (is_int($v)) {} else { $str .= '"'; }
				$set[] = $str;
			}
			$sql .= implode(', ', $set);
			$sql .= '
				WHERE
					`id` = '. $user_id .'
			';
			$conn->query($sql) OR die('Error update user query: '. mysqli_error($conn) .'<br /><pre>'. $sql .'</pre>');
		}
	}
	
	if ($action == '-') {
		$sql = '
			DELETE FROM
				`users`
			WHERE
		';
		$where = array();
		$or = array();
		$active = false;
		foreach($array as $k => $v) {
			/*if ($k == 'active') {
				$active = true;
				if ($v == '0/1') { $active = true;/*'; }
			}*/
			if (is_array($v)) {
				foreach ($v as $or_condition) {
					$str = '`'. $k .'` = ';
					if (!is_int($or_condition)) { $str .='"'. $or_condition .'"'; }
					else { $str .= $or_condition; }
					$or[] = $str;
				}
				$orstring = implode(' OR ', $or);
				$orstring = ' ( '. $orstring .' ) ';
				$where[] = $orstring;
			}
			else {
				$str = '`'. $k .'` = ';
				if (!is_int($v)) { $str .='"'. $v .'"'; }
				else { $str .= $v; }
				$where[] = $str;
			}
		}
		$sql .= implode(' AND ', $where);
		$conn->query($sql) OR die('Error in query: '. mysqli_error($conn) .'<br /><pre>'. $sql .'</pre>');
	}
}


#######################################################################
##########################                      #######################
##########################       SETTINGS       #######################
##########################                      #######################
#######################################################################

function settings(
			$action = '',
			$array = array(),
			$settings_id = ''
) {
	global $conn;
	
	if ($action == '?') {
		// pass array of key=>val parameters to check if there is a match in the users table
		// it will return the user ID if all parameters match or false if no match
		// Example: auth('?', array('email' => 'someaddy@gmail.org', 'password' => md5('Bingo5!')));
		// 			>> returns userID if all criteria match or false if not all match
		
		if (empty($array) || !is_array($array)) { return false; }
		else {
			$sql = '
					SELECT
						`id`
					FROM
						`settings`
					WHERE
				';
			$where = array();
			$active = false;
			foreach($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $or_condition) {
						if ($k == 'active') { $active = true; }
						$str = '`'. $k .'` = ';
						if (!is_int($or_condition)) { $str .='"'. $or_condition .'"'; }
						else { $str .= $or_condition; }
						$or[] = $str;
					}
					$orstring = implode(' OR ', $or);
					$orstring = ' ( '. $orstring .' ) ';
					$where[] = $orstring;
				}
				else {
					if ($k == 'active') { $active = true; }
					$str = '`'. $k .'` = ';
					if (!is_int($v)) { $str .='"'. $v .'"'; }
					else { $str .= $v; }
					$where[] = $str;
				}
			}
			if (!$active) { $where[] = '`active` = 1'; }
			$sql .= implode(' AND ', $where);
			$sql .= '
					ORDER BY
						`id`
							ASC
					LIMIT
						1
			';
			//print '<pre>'; print_r($sql); print '</pre>';
			$result = $conn->query($sql) OR die('Error in query: '. mysqli_error($conn));
			if ($result->num_rows > 0) {
				$settings = $result->fetch_assoc();
				return $settings['id'];
			}
			else { return false; }
		}
	}
	
	
	if ($action == '@') {
		// return array of userid requested data like username, first/last name, session id, password hash, group_id, last timestamp, etc, if user is not logged in
		//EXAMPLE $user = auth('@', array(id' => 5145)); returns array of data related to that specific user id
		if (is_array($array) && empty($array)) {
			$condition = ($settings_id != '') ? (' WHERE id = '. $settings_id .' ') : (' ORDER BY `id` ASC LIMIT 1 ');
			$sql = '
					SELECT
						id,
						email,
						main_url,
						admin_url,
						name,
						description,
						send_mail_method,
						smtp_host,
						smtp_port,
						smtp_username,
						smtp_password,
						default_new_user_group_id,
						send_welcome_email,
						send_welcome_email_subject,
						send_welcome_email_body,
						send_reset_link_email_subject,
						send_reset_link_email_body,
						send_random_password_subject,
						send_random_password_body,
						send_new_user_info_subject,
						send_new_user_info_body,
						time_zone,
						last_updated
					FROM
						`settings`
					'. $condition .'
			';
			//print '<pre>'; print_r($sql); print '</pre>';
			$result = $conn->query($sql) OR die('Error in query: '. mysqli_error($conn));
			if ($result->num_rows > 0) {
				return $result->fetch_assoc();
			}
			else { return false; }
		}
		elseif (is_array($array) && count($array) > 0) {
			$sql = '
					SELECT
			';
			$fields = implode('`, `', $array);
			$fields = '`'. $fields .'`';
			$sql .= $fields;
			$sql .= '
					FROM
						`settings`
					ORDER BY
						`id`
							ASC
					LIMIT
						1
			';
			//print '<pre>'; print_r($sql); print '</pre>';
			$result = $conn->query($sql) OR die('Error in query: '. mysqli_error($conn));
			if ($result->num_rows > 0) {
				return $result->fetch_assoc();
			}
			else { return false; }
		}
		elseif (is_string($array) && $array != '') {
			$sql = '
					SELECT
						`'. $array .'`
					FROM
						`settings`
					ORDER BY
						`id`
							ASC
					LIMIT
						1
			';
			//print '<pre>'; print_r($sql); print '</pre>';
			$result = $conn->query($sql) OR die('Error in query: '. mysqli_error($conn));
			if ($result->num_rows > 0) {
				$var = $result->fetch_assoc();
				return $var[$array];
			}
			else { return false; }
		}
	}
	
	if ($action == '^') {
		// Usage: Update a single field for the userID or many fields at once
		// Example: update userid 5124 active = 1
		//			>>> auth('^', 5124, 'active', 1)
		// Example: update userid 5124 password and username
		//			>>> auth('^', 5124, array('password' => md5('Bingo5!'), 'username' = 'new_user_name'));
		// Example: update userid 5124 sessionid = 2jf9fjf207aAKg97g6t2BFh293fHF37gAjb83iw
		//			>>> auth('^', 5124, 'sessionid', '2jf9fjf207aAKg97g6t2BFh293fHF37gAjb83iw')
		
		if (is_array($array) && count($array) > 0) {
			$sql = '
				UPDATE
					`settings`
				SET
			';
			$set = array();
			foreach ($array as $k => $v) {
				$str = '`'. $k .'` = ';
				if (is_int($v)) {} else { $str .= '"'; }
				$str .= $v;
				if (is_int($v)) {} else { $str .= '"'; }
				$set[] = $str;
			}
			$sql .= implode(', ', $set);
			$sql .= '
				WHERE
					`id` = '. $settings_id .'
			';
			$conn->query($sql) OR die('Error update user query: '. mysqli_error($conn) .'<br /><pre>'. $sql .'</pre>');
		}
		else {
			$sql = '
				UPDATE
					`users`
				SET
					`'. $array .'` = ';
			if (is_string($val)) { $sql .= '"'; }
			$sql .= $val;
			if (is_string($val)) { $sql .= '"'; }
			$sql .= '
				WHERE
					`id` = '. $array .'
			';
			//mysql_query($sql) OR die(mysql_error() .'<pre>'. $sql .'</pre>');
			//print '<pre>'; print_r($sql); print '</pre>';
			$conn->query($sql) OR die('Error update user query: '. mysqli_error($conn) .'<br /><pre>'. $sql .'</pre>');
		}
	}
	
	if ($action == '-') {
		$sql = '
			DELETE FROM
				`users`
			WHERE
				`id` = '. $array .'
		';
		$conn->query($sql) OR die('Error in query: '. mysqli_error($conn) .'<br /><pre>'. $sql .'</pre>');
	}
}



#######################################################################
##########################                      #######################
##########################     PARSE MESSAGE    #######################
##########################                      #######################
#######################################################################

function parse_message($text, $array) {
	foreach ($array as $prefix => $a) {
		foreach ($a as $k => $v) {
			$text = str_replace('{{'. $prefix .'.'. $k .'}}', $v, $text);
		}
	}
	return $text;
}

/*
function parse_message($text, $user, $settings) {
	foreach ($user as $k => $v) {
		$text = str_replace('{{user_'. $k .'}}', $v, $text);
	}
	foreach ($settings as $k => $v) {
		$text = str_replace('{{settings_'. $k .'}}', $v, $text);
	}
	return $text;
}
*/

#######################################################################
##########################                      #######################
##########################    SALT GENERATOR    #######################
##########################                      #######################
#######################################################################

function chaos(
			$type = 'alphanum',
			$len = 50,
			$add = '',
			$remove = array(),
			$unique = false
) {
	$num = '0123456789';
	$alphalow = 'abcdefghijklmnopqrstuvwxyz';
	$alphacap = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$special = '~`!@#$%^&*()-_+={}[]|/\?<>,.:;'; // this list does NOT include quotation marks (') or (")
	switch($type) {
		case 'num':
			$chars = $num . $add;
			break;
		case 'alpha':
			$chars = $alphalow . $alphacap . $add;
			break;
		case 'alphalow':
			$chars = $alphalow . $add;
			break;
		case 'alphacap':
			$chars = $alphacap . $add;
			break;
		case 'alphanum':
			$chars = $num . $alphalow . $alphacap . $add;
			break;
		case 'url':
			$chars = $num . $alphalow . $alphacap .'-_'. $add;
			break;
		case 'all':
			$chars = $num . $alphalow . $alphacap . $special . $add;
			break;
	}
	
	if (!empty($remove)) {
		foreach ($remove as $k => $v) { str_replace($v, '', $chars); }
	}
	
	$str = '';
	for($i = 0; $i < $len; $i++) {
		// ensure all characters are unique
		if ($unique) {
			/*if (!strstr($str, $new_char)) {
				$str .= $new_char;
			} else { $i--; }*/
			$chars_a = str_split($chars);
			shuffle($chars_a);
			$str = implode('', $chars_a);
		}
		else {
			// get a new character
			$new_char = $chars[rand(0, strlen($chars) -1)];
			$str .= $new_char;
		}
	}
	return htmlspecialchars($str);
}


?>