<?php include('init.php'); if (!$userid) { header('Location: login.php'); } ?>
<?php //print '<pre>'; print_r(i('@', 'users')); print '</pre>'; ?>
<?php

if ($_GET && isset($_GET['id'])) {
	if ($_GET['id'] == $userid) { header('Location: account.php'); }
	if (auth('?', array('id' => $_GET['id'], 'active' => array(0, 1)))) {
		$edituser = auth('@', array('id' => $_GET['id'], 'active' => array(0, 1)));
	}
}
else { $edituser = false; }

if (!isset($_GET['page'])) { $pagenum = 1; }
else { $pagenum = $_GET['page']; }

if ($_POST) {
	clean($_POST);
	i('+', 'users', $_POST);
	
	if ($_POST['action'] == 'search') {
		if ($_POST['search'] != '') {
			$where = ' WHERE ';
			if ($_POST['search_by'] == 'name') {
				$where .= '
					firstname LIKE "%'. $_POST['search'] .'%"
					OR
					lastname LIKE "%'. $_POST['search'] .'%"		
					OR
					displayname LIKE "%'. $_POST['search'] .'%"
				';
			}
			elseif ($_POST['search_by'] == 'id') {
				if (is_numeric($_POST['search'])) {
					$where .= '
						u.id = '. $_POST['search'] .'
					';
				}
				else {
					$where = '';
					i('-', 'users', 'where');
					i('-', 'users', 'search');
					n('+', 'users', 'error', 'Incorrect search format for <strong>ID</strong>. Please use an integer.');
				}
			}
			elseif ($_POST['search_by'] == 'group') {
				$where .= '
					g.name LIKE "%'. $_POST['search'] .'%"
				';
			}
			else {
				$where .= '
					'. $_POST['search_by'] .' LIKE "%'. $_POST['search'] .'%"
				';
			}
			$pagenum = 1;
			i('+', 'users', array('where' => $where));
		}
		else {
			$pagenum = 1;
			i('-', 'users', 'where');
			i('-', 'users', 'search');
			i('-', 'users', 'search_by');
			i('-', 'users', 'sort');
			i('-', 'users', 'sort_direction');
		}
	}
	
	
	if ($_POST['action'] == 'account') {
		if (!valid('email', $_POST['email'])) {
			n('+', 'user', 'error', 'Invalid email address.');
		}
		if ($edituser['email'] != $_POST['email']) {
			if (auth('?', array('email' => $_POST['email'], 'active' => array(0, 1)))) {
				n('+', 'user', 'error', 'That email address is already associated with another account.');
			}
			else {
				auth('^', array('email' => strtolower($_POST['email'])), $edituser['id']);
			}
		}
		
		/*if ($_POST['username'] != '' && !valid('username', $_POST['username'])) {
			n('+', 'user', 'error', 'Invalid username. Please use only letters, numbers, hyphens, and underscores.');
		}*/
		
		if ($edituser['username'] != $_POST['username'] && $_POST['username'] != '') {
			if (valid('username', $_POST['username'], 5)) {
				if (auth('?', array('username' => $_POST['username'], 'active' => array(0, 1)))) {
					n('+', 'user', 'error', 'That username is already associated with another account.');
				}
				else {
					auth('^', array('username' => strtolower($_POST['username'])), $edituser['id']);
				}
			}
			else {
				n('+', 'user', 'error', 'Invalid username format. Please use at least 5 characters of only letters, numbers, hyphens, and underscores.');
			}
		}
		
		if (!n('?', 'user', 'error')) {
			$userinfo = array(
				'firstname' => htmlspecialchars($_POST['firstname']),
				'lastname' => htmlspecialchars($_POST['lastname']),
				'displayname' => htmlspecialchars($_POST['displayname']),
				'username' => strtolower($_POST['username']),
				'email' => strtolower($_POST['email'])
			);
			auth('^', $userinfo, $edituser['id']);
			$edituser = auth('@', array('id' => $edituser['id'], 'active' => array(0, 1)));
			n('+', 'user', 'success', 'Account information has been updated.');
		}
	}
	
	if ($_POST['action'] == 'password') {
		if (!valid('text', $_POST['password'], 5)) {
			n('+', 'user', 'error', 'Password must be at least 5 characters in length.');
		}
		
		if (!n('?', 'user', 'error')) {
			auth('^', array('password' => md5($_POST['password'])), $edituser['id']);
			n('+', 'user', 'success', 'Password has been updated.');
		}
	}
	
	if ($_POST['action'] == 'access') {
		$active = (isset($_POST['active'])) ? (1) : (0);
		auth('^', array('group_id' => $_POST['group_id'], 'active' => $active), $edituser['id']);
		$edituser = auth('@', array('id' => $edituser['id'], 'active' => array(0, 1)));
		n('+', 'user', 'success', 'User access has been updated.');
	}
	
	if ($_POST['action'] == 'password-reset-link') {
		if ($settings['send_mail_method'] == 'php') {
			$token = chaos();
			auth('^', array('token' => $token), $edituser['id']);
			$edituser['token'] = $token;
			$to = $edituser['email'];
			$replace = array('user' => $edituser, 'settings' => $settings);
			$subject = parse_message($settings['send_reset_link_email_subject'], $replace);
			$body = parse_message($settings['send_reset_link_email_body'], $replace);
			$headers = 'From: '. $settings['email'] ."\r\n"
				.'Reply-To: '. $settings['email'] ."\r\n"
				.'X-Mailer: PHP/'. phpversion();
			mail($to, $subject, $body, $headers);
			n('+', 'user', 'success', 'A password reset link was sent to <strong>'. $edituser['email'] .'</strong>.');
		}
		elseif ($settings['send_mail_method'] == 'smtp') {
			// send mail using SMTP
		}
	}
	
	if ($_POST['action'] == 'send-random-password') {
		if ($settings['send_mail_method'] == 'php') {
			$password = chaos('alphanum', 8);
			auth('^', array('password' => md5($password)), $edituser['id']);
			$edituser['password'] = $password;
			$to = $edituser['email'];
			$replace = array('user' => $edituser, 'settings' => $settings);
			$subject = parse_message($settings['send_random_password_subject'], $replace);
			$body = parse_message($settings['send_random_password_body'], $replace);
			$headers = 'From: '. $settings['email'] ."\r\n"
				.'Reply-To: '. $settings['email'] ."\r\n"
				.'X-Mailer: PHP/'. phpversion();
			mail($to, $subject, $body, $headers);
			n('+', 'user', 'success', 'A random password was sent to <strong>'. $edituser['email'] .'</strong>.');
			$edituser['password'] = md5($password);
		}
		elseif ($settings['send_mail_method'] == 'smtp') {
			// send mail using SMTP
		}
	}
	
	if ($_POST['action'] == 'force-logout') {
		auth('^', array('session_id' => ''), $edituser['id']);
		n('+', 'user', 'success', 'User has been logged out.');
	}
	
	if ($_POST['action'] == 'delete-user') {
		auth('-', array('id' => $edituser['id']));
		n('+', 'users', 'success', 'User <b>#'. $edituser['id'] .'</b> has been deleted.');
		header('Location: users.php');
	}
	
}
i('-', 'users', 'action');
if (!i('?', 'users', 'where')) { i('+', 'users', array('where' => '')); }
if (!i('?', 'users', 'sort')) {
	i('+', 'users', array('sort' => 'email'));
	i('+', 'users', array('sort_direction' => 'asc'));
}
if (isset($_GET['sort'])) {
	i('+', 'users', array('sort' => $_GET['sort']));
	if (i('|', 'users', 'sort') == $_GET['sort']) {
		if (i('|', 'users', 'sort_direction') == 'asc') {
			i('+', 'users', array('sort_direction' => 'desc'));
		} else { i('+', 'users', array('sort_direction' => 'asc')); }
	}
	else { i('+', 'users', array('sort_direction' => 'asc')); }
}
if (isset($_GET['num-per-page'])) {
	i('+', 'users', array('num-per-page' => $_GET['num-per-page']));
}
elseif (!i('?', 'users', 'num-per-page')) {
	i('+', 'users', array('num-per-page' => 25));
}

?>
<?php include('header.php'); include('sidebar.php'); ?>
<?php if ($edituser) { ?>
		<div id="page-wrapper">

			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
<?php foreach (n('|', 'user', 'error') as $k => $message) { ?>
<div>
	<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<strong>Error:</strong> <?= $message; ?>
	</div>
</div>
<?php } ?>
<?php foreach (n('|', 'user', 'success') as $k => $message) { ?>
<div>
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<strong>Success:</strong> <?= $message; ?>
	</div>
</div>
<?php } ?>
						<h1 class="page-header">
							Edit User Account <small>#<?= $edituser['id']; ?></small>
						</h1>
						
					</div>
				</div>
				<!-- /.row -->

				<div class="row">
					<div class="col-lg-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Account Information</h3>
							</div>
							<div class="panel-body">
								
								<form role="form" action="users.php?id=<?= $edituser['id']; ?>" method="post">
									<input type="hidden" name="action" value="account">
									<div class="row">
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<label>First Name</label>
										<input type="text" class="form-control" name="firstname" value="<?= $edituser['firstname']; ?>">
									</div>
		
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<label>Last Name</label>
										<input type="text" class="form-control" name="lastname" value="<?= $edituser['lastname']; ?>">
									</div>
									</div>
									<div class="row">
									<div class="form-group col-lg-12 col-md-12 col-sm-12">
										<label>Email Address</label>
										<input type="text" class="form-control" name="email" value="<?= $edituser['email']; ?>">
									</div>
									</div>
									<div class="row">
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<label>Username</label>
										<input type="text" class="form-control" name="username" value="<?= $edituser['username']; ?>">
									</div>
									
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<label>Display Name</label>
										<input type="text" class="form-control" name="displayname" value="<?= $edituser['displayname']; ?>">
									</div>
									</div>
									<div class="form-group col-lg-12">
										<input type="submit" class="btn btn-primary" value="Update Account" />
									</div>
								</form>
							</div>
							<div class="panel-footer">
								<span class="badge"><strong>Created on</strong>: <?= date('M jS, Y', strtotime($edituser['created'])); ?></span>
								<div class="pull-right">
									<span class="badge"><strong>Last login</strong>: <?= ($edituser['last_login'] == '0000-00-00 00:00:00') ? ('<i>never</i>') : (date('M jS, Y', strtotime($edituser['last_login']))); ?></span>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-lg-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Password Management</h3>
							</div>
							<div class="panel-body">
								<form role="form" action="users.php?id=<?= $edituser['id']; ?>" method="post" class="form-inline">
									<input type="hidden" name="action" value="password">
									
									<div class="form-group col-lg-12 col-md-12 col-sm-12">
										<label>Set a New Password</label><br />
										<input type="password" class="form-control random-password-input" name="password">
										<button type="button" class="btn btn-default random-password-button">Insert random password</button>
										<input type="submit" class="btn btn-primary" value="Change Password" />
									</div>
								</form>
									<div class="form-group col-xs-12">
										<hr />
									</div>
									
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<form role="form" action="users.php?id=<?= $edituser['id']; ?>" method="post" id="password-reset-link">
											<input type="hidden" name="action" value="password-reset-link">
										</form>
										<button type="button" class="btn btn-default password-reset-link">
											<i class="fa fa-envelope-o"></i>
											Send password reset link
										</button>
									</div>
									
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<form role="form" action="users.php?id=<?= $edituser['id']; ?>" method="post" id="send-random-password">
											<input type="hidden" name="action" value="send-random-password">
										</form>
										<button type="button" class="btn btn-default send-random-password">
											<i class="fa fa-envelope-o"></i>
											Send random password
										</button>
									</div>
									
							</div>
						</div>
					</div>
					
					<div class="col-lg-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Access Management</h3>
							</div>
							<div class="panel-body">
								<form role="form" action="users.php?id=<?= $edituser['id']; ?>" method="post">
									<input type="hidden" name="action" value="access">
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<label>Group</label><br />
										<select name="group_id" class="selectpicker" data-width="125px">
<?php
$groups = '
	SELECT
		`id`,
		`name`
	FROM
		`user_groups`
	ORDER BY
		`id`
			ASC
';
$group_result = $conn->query($groups) OR die('Error in query: '. mysqli_error($conn));
if ($group_result->num_rows > 0) {
	while ($group = $group_result->fetch_assoc()) {
?>
											<option value="<?= $group['id']; ?>"<?= ($edituser['group_id'] == $group['id']) ? (' selected') : (''); ?>><?= $group['name']; ?></option>
<?php
	}
}
?>
										</select>
									</div>
									
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<label>Status</label><br />
										<input type="checkbox" name="active" value="1" class="edit-user-active-checkbox"<?= ($edituser['active']) ? (' checked="checked"') : (''); ?>>
									</div>
									
									<div class="form-group col-xs-12">
										<input type="submit" class="btn btn-primary" value="Update User Access" />
									</div>
								</form>
<?php if ($edituser['id'] != $userid) { ?>
									<div class="clearfix"></div>
									<div class="form-group col-xs-12">
										<hr />
									</div>
									
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<form role="form" action="users.php?id=<?= $edituser['id']; ?>" method="post" id="force-logout">
											<input type="hidden" name="action" value="force-logout">
										</form>
										<button type="button" class="btn btn-default force-logout">
											<i class="fa fa-power-off"></i>
											Force User Logout
										</button>
									</div>
									
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										
										<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">
											<i class="fa fa-trash-o"></i>
											Delete User Account
										</button>
									</div>
<?php } ?>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirm Delete Account <?= $edituser['id']; ?></h4>
      </div>
      <div class="modal-body">
        Are you sure you want to completely remove user <?= $edituser['id']; ?> from the system?
      </div>
      <div class="modal-footer">
        
        <form role="form" action="users.php?id=<?= $edituser['id']; ?>" method="post" id="delete-user">
			<input type="hidden" name="action" value="delete-user">
		</form>
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger delete-user">Delete Account</button>
      </div>
    </div>
  </div>
</div>
							</div>
						</div>
					</div>
					
				</div>
				<!-- /.row -->

			</div>
			<!-- /.container-fluid -->

		</div>
		<!-- /#page-wrapper -->
<?php n('-', 'user'); ?>
<?php i('-', 'users', 'where'); ?>
<?php i('-', 'users', 'search'); ?>
<?php i('-', 'users', 'search_by'); ?>
<?php } else { ?>
		<div id="page-wrapper">

			<div class="container-fluid">

				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
<?php foreach (n('|', 'users', 'error') as $k => $message) { ?>
<div>
	<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<strong>Error:</strong> <?= $message; ?>
	</div>
</div>
<?php } ?>
<?php foreach (n('|', 'users', 'success') as $k => $message) { ?>
<div>
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<strong>Success:</strong> <?= $message; ?>
	</div>
</div>
<?php } ?>
<?php

$sql = '
	SELECT
		`u`.`id`,
		`u`.`email`,
		`u`.`username`,
		`u`.`firstname`,
		`u`.`lastname`,
		`u`.`displayname`,
		`u`.`created`,
		`u`.`last_login`,
		`u`.`active`,
		`g`.`name` AS group_name
	FROM
		`users` AS u
	INNER JOIN
		`user_groups` AS g
	ON
		`u`.`group_id` = `g`.`id`
';
$where = i('|', 'users', 'where');


//print '<pre>'; print_r($sql . $where . $order . $limit); print '</pre>';
//print '<pre>'; print_r(i('@', 'users')); print '</pre>';

$q = $conn->query($sql . $where);
$totalresults = $q->num_rows;


?>
						<h1 class="page-header">
							Users
							<div class="pull-right">
								<a href="createuser.php" class="btn btn-success"><i class="fa fa-plus"></i> Create a User</a>
							</div>
						</h1>
					</div>
				</div>
				<!-- /.row -->
				
				<div class="row">
					<div class="col-xs-8">
						<form method="post" class="form-inline" role="search">
							<div class="form-group">
								<input type="hidden" name="action" value="search">
								<input type="text" name="search" class="form-control" value="<?= i('|', 'users', 'search'); ?>" placeholder="Search">
								<select name="search_by" class="selectpicker" data-width="125px">
									<option value="email"<?= (i('|', 'users', 'search_by') == 'email') ? (' selected') : (''); ?>>Email</option>
									<option value="username"<?= (i('|', 'users', 'search_by') == 'username') ? (' selected') : (''); ?>>Username</option>
									<option value="name"<?= (i('|', 'users', 'search_by') == 'name') ? (' selected') : (''); ?>>Name</option>
									<option value="id"<?= (i('|', 'users', 'search_by') == 'id') ? (' selected') : (''); ?>>ID</option>
									<option value="group"<?= (i('|', 'users', 'search_by') == 'group') ? (' selected') : (''); ?>>Group</option>
								</select>
								<input type="submit" class="btn btn-primary" value="Submit">
							</div><!-- /.input-group -->
						</form>
					</div>
					<div class="col-xs-4">
						<div class="form-group pull-right">
							<select name="num-per-page" class="selectpicker" data-width="75px">
								<option value="25"<?= (i('|', 'users', 'num-per-page') == '25') ? (' selected') : (''); ?>>25</option>
								<option value="50"<?= (i('|', 'users', 'num-per-page') == '50') ? (' selected') : (''); ?>>50</option>
								<option value="100"<?= (i('|', 'users', 'num-per-page') == '100') ? (' selected') : (''); ?>>100</option>
								<option value="250"<?= (i('|', 'users', 'num-per-page') == '250') ? (' selected') : (''); ?>>250</option>
								<option value="all"<?= (i('|', 'users', 'num-per-page') == 'all') ? (' selected') : (''); ?>>All</option>
							</select>
						</div>
					</div>
				</div><!-- /.row -->
				<hr />
				
				<div class="row">
					<div class="col-lg-12">
						<div class="table-responsive">
							<div class="h4">
								Total Results: <?= $totalresults; ?>
							</div>
							<table class="table table-hover">
								<thead>
									<tr class="sortable">
										<th><i class="fa fa-sort"></i> Email</th>
										<th><i class="fa fa-sort"></i> Username</th>
										<th><i class="fa fa-sort"></i> Group</th>
										<th><i class="fa fa-sort"></i> First</th>
										<th><i class="fa fa-sort"></i> Last</th>
										<th><i class="fa fa-sort"></i> Display</th>
										<th><i class="fa fa-sort"></i> Registered</th>
										<th><i class="fa fa-sort"></i> Last Login</th>
										<th><i class="fa fa-sort"></i> Status</th>
									</tr>
								</thead>
								<tbody>
<?php

$sql = '
	SELECT
		`u`.`id`,
		`u`.`email`,
		`u`.`username`,
		`u`.`firstname`,
		`u`.`lastname`,
		`u`.`displayname`,
		`u`.`created`,
		`u`.`last_login`,
		`u`.`active`,
		`g`.`name` AS group_name
	FROM
		`users` AS u
	INNER JOIN
		`user_groups` AS g
	ON
		`u`.`group_id` = `g`.`id`
';
$where = i('|', 'users', 'where');

$sort = ' ORDER BY ';
$sort .= (i('|', 'users', 'sort') == 'email') ? ('u.email') : ('');
$sort .= (i('|', 'users', 'sort') == 'username') ? ('u.username') : ('');
$sort .= (i('|', 'users', 'sort') == 'group') ? ('g.name') : ('');
$sort .= (i('|', 'users', 'sort') == 'first') ? ('u.firstname') : ('');
$sort .= (i('|', 'users', 'sort') == 'last') ? ('u.lastname') : ('');
$sort .= (i('|', 'users', 'sort') == 'display') ? ('u.displayname') : ('');
$sort .= (i('|', 'users', 'sort') == 'registered') ? ('u.created') : ('');
$sort .= (i('|', 'users', 'sort') == 'last_login') ? ('u.last_login') : ('');
$sort .= (i('|', 'users', 'sort') == 'status') ? ('u.active') : ('');
$sort .= ' '. i('|', 'users', 'sort_direction') .' ';

if (i('|', 'users', 'num-per-page') == 'all') {
	$limit = '';
}
else {
	$limit = ' LIMIT '. ($pagenum - 1) * i('|', 'users', 'num-per-page') .', '. i('|', 'users', 'num-per-page') .' ';
}

//print '<pre>'; print_r($sql . $where . $sort . $limit); print '</pre>';
//print '<pre>'; print_r(i('@', 'users')); print '</pre>';

$q = $conn->query($sql . $where);
$totalresults = $q->num_rows;

$result = $conn->query($sql . $where . $sort . $limit) OR die('Error in query on line '. __LINE__ .': '. mysqli_error($conn));
if ($numrows = $result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
?>
									<tr class="user" data-user-id="<?= $row['id']; ?>">
										<td><?= $row['email']; ?></td>
										<td><?= $row['username']; ?></td>
										<td>
<?php
switch($row['group_name']) {
	case 'Administrator':
		$usertype = 'primary';
		break;
	case 'Manager':
		$usertype = 'info';
		break;
	case 'Editor':
		$usertype = 'warning';
		break;
	case 'User':
		$usertype = 'default';
		break;
}
?>
											<span class="label label-<?= $usertype; ?> btn-xs">
												<?= $row['group_name']; ?>
											</span>
										</td>
										<td><?= $row['firstname']; ?></td>
										<td><?= $row['lastname']; ?></td>
										<td><?= $row['displayname']; ?></td>
										<td><?= date('M jS, Y', strtotime($row['created'])); ?></td>
										<td><?= ($row['last_login'] == '0000-00-00 00:00:00') ? ('<small><i>never</i></small>') : (date('M jS, Y', strtotime($row['last_login']))); ?></td>
										<td><?= ($row['active']) ? ('<i class="fa fa-check-circle-o user-active"></i>') : ('<i class="fa fa-ban user-inactive"></i>'); ?></td>
									</tr>
<?php } ?>
<?php } else { ?>

<tr>
	<td colspan="9">
		<div class="text-center">No results</div>
	</td>

<?php } ?>

								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- /.row -->

				<!-- Pagination -->
				<div class="row text-center">
					<div class="col-lg-12">
						<ul class="pagination">
							<li<?= ($pagenum == 1) ? (' class="disabled"') : (''); ?>>
								<a href="<?= ($pagenum > 1) ? ('users.php?page='. ($pagenum -1)) : ('javascript:;'); ?>">&laquo;</a>
							</li>
<?php
$totalpages = (i('|', 'users', 'num-per-page') == 'all') ? (1) : (ceil($totalresults / i('|', 'users', 'num-per-page')));
for($i = 1; $i <= $totalpages; $i++) { ?>
							<li<?= ($pagenum == $i) ? (' class="active"') : (''); ?>>
								<a href="users.php?page=<?= $i; ?>"><?= $i; ?></a>
							</li>
<?php } ?>
							<li<?= ($pagenum == $totalpages) ? (' class="disabled"') : (''); ?>>
								<a href="<?= ($pagenum < $totalpages) ? ('users.php?page='. ($pagenum +1)) : ('javascript:;'); ?>">&raquo;</a>
							</li>
						</ul>
					</div>
				</div><!-- /.row -->
								
			</div>
			<!-- /.container-fluid -->

		</div>
		<!-- /#page-wrapper -->
<?php n('-', 'users'); unset($pagenum); ?>
<?php } // end if users page ?>
<?php include('footer.php'); ?>