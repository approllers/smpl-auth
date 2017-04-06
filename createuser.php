<?php include('init.php'); if (!$userid) { header('Location: login.php'); } ?>
<?php

if ($_POST) {
	clean($_POST);
	if ($_POST['action'] == 'create-user') {
		i('+', 'create-user', $_POST);
		if (!valid('email', $_POST['email'])) {
			n('+', 'create-user', 'error', 'Invalid email address.');
		}
		if ($existinguser = auth('?', array('email' => $_POST['email'], 'active' => array(0, 1)))) {
			n('+', 'create-user', 'error', 'The email address <strong>'. $_POST['email'] .'</strong> is already associated with another account (<a href="'. $settings['admin_url'] .'user.php?id='. $existinguser .'">#'. $existinguser .'</a>).');
		}
		
		if (strlen($_POST['username']) > 0 && !valid('text', $_POST['username'], 5, 25)) {
			if (!n('?', 'create-user', 'error')) {
				n('+', 'create-user', 'error', 'Invalid username. Please use at least 5 characters of only letters, numbers, hyphens, and underscores.');
			}
		}
		
		if (strlen($_POST['username']) > 0 && $existinguser = auth('?', array('username' => $_POST['username'], 'active' => array(0, 1)))) {
			if (!n('?', 'create-user', 'error')) {
				n('+', 'create-user', 'error', 'The username <strong>'. $_POST['username'] .'</strong> is already associated with another account (<a href="'. $settings['admin_url'] .'user.php?id='. $existinguser .'">#'. $existinguser .'</a>).');
			}
		}
		
		if (!valid('text', $_POST['password'], 5)) {
			if (!n('?', 'create-user', 'error')) {
				n('+', 'create-user', 'error', 'Password must be at least 5 characters.');
			}
		}
		
		if (!n('?', 'create-user', 'error')) {
			$newuser = array(
				'firstname' => htmlspecialchars($_POST['firstname']),
				'lastname' => htmlspecialchars($_POST['lastname']),
				'displayname' => htmlspecialchars($_POST['displayname']),
				'username' => strtolower($_POST['username']),
				'email' => strtolower($_POST['email']),
				'password' => md5($_POST['password']),
				'group_id' => $_POST['group_id'],
				'created' => date('Y-m-d H:i:s')
			);
			auth('+', $newuser);
			$newuser['password'] = $_POST['password'];
			if ($_POST['notify_user']) {
				if ($settings['send_mail_method'] == 'php') {
					$password = chaos('alphanum', 8);
					$to = $_POST['email'];
					$replace = array('user' => $newuser, 'settings' => $settings);
					$subject = parse_message($settings['send_new_user_info_subject'], $replace);
					$body = parse_message($settings['send_new_user_info_body'], $replace);
					$headers = 'From: '. $settings['email'] ."\r\n"
						.'Reply-To: '. $settings['email'] ."\r\n"
						.'X-Mailer: PHP/'. phpversion();
					mail($to, $subject, $body, $headers);
				}
				elseif ($settings['send_mail_method'] == 'smtp') {
					// send mail using SMTP
				}
			}
			
			n('+', 'create-user', 'success', 'New user <strong>'. $_POST['email'] .'</strong> has been created.');
			i('-', 'create-user');
			i('+', 'create-user', array('active' => 1, 'notify_user' => 1));
			$newuser = array();
		}
		
		
	}
	
}



?>
<?php include('header.php'); include('sidebar.php'); ?>
		<div id="page-wrapper">

			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
<?php foreach (n('|', 'create-user', 'error') as $k => $message) { ?>
<div>
	<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<strong>Error:</strong> <?= $message; ?>
	</div>
</div>
<?php } ?>
<?php foreach (n('|', 'create-user', 'success') as $k => $message) { ?>
<div>
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<strong>Success:</strong> <?= $message; ?>
	</div>
</div>
<?php } ?>
						<h1 class="page-header">
							Create a User
						</h1>
						
					</div>
				</div>
				<!-- /.row -->

				<div class="row">
					<div class="col-lg-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">New User Information</h3>
							</div>
							<div class="panel-body">
								<form role="form" action="" method="post">
									<input type="hidden" name="action" value="create-user">
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<label>First Name</label>
										<input type="text" class="form-control" name="firstname" value="<?= i('|', 'create-user', 'firstname'); ?>">
										<!--<p class="help-block">Example block-level help text here.</p>-->
									</div>
		
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<label>Last Name</label>
										<input type="text" class="form-control" name="lastname" value="<?= i('|', 'create-user', 'lastname'); ?>">
										<!--<p class="help-block">Example block-level help text here.</p>-->
									</div>
									
									<div class="form-group col-lg-12 col-md-12 col-sm-12">
										<label>Email Address</label>
										<input type="text" class="form-control" name="email" value="<?= i('|', 'create-user', 'email'); ?>">
										<!--<p class="help-block">Example block-level help text here.</p>-->
									</div>
									
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<label>Username</label>
										<input type="text" class="form-control" name="username" value="<?= i('|', 'create-user', 'username'); ?>">
										<!--<p class="help-block">Example block-level help text here.</p>-->
									</div>
									
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<label>Display Name</label>
										<input type="text" class="form-control" name="displayname" value="<?= i('|', 'create-user', 'displayname'); ?>">
										<!--<p class="help-block">Example block-level help text here.</p>-->
									</div>
									
									
									<div class="form-group col-xs-12">
										<hr>
									</div>
									
									<div class="form-group form-inline col-lg-12 col-md-12 col-sm-12">
										<label>Password</label><br />
										<input type="password" class="form-control random-password-input" name="password">
										<button type="button" class="btn btn-default random-password-button">Insert random password</button>
									</div>
									<div class="form-group col-xs-12">
										<hr />
									</div>
									
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
			DESC
';
$group_result = $conn->query($groups) OR die('Error in query: '. mysqli_error($conn));
if ($group_result->num_rows > 0) {
	while ($group = $group_result->fetch_assoc()) {
?>
											<option value="<?= $group['id']; ?>"<?= (i('|', 'create-user', 'group_id') == $group['id']) ? (' selected') : (''); ?><?= (!$_POST && $settings['default_new_user_group_id'] == $group['id']) ? (' selected') : (''); ?>><?= $group['name']; ?></option>
<?php
	}
}
?>
										</select>
										<!--<p class="help-block">Example block-level help text here.</p>-->
									</div>
									
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<label>Status</label><br />
										<input type="checkbox" name="active" value="1" class="edit-user-active-checkbox"<?= (i('?', 'create-user', 'active') || !$_POST) ? (' checked="checked"') : (''); ?>>
									</div>
									<div class="clearfix"></div>
									<div class="form-group col-xs-12">
										<hr />
									</div>
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<input type="submit" class="btn btn-primary" value="Create User Account" />
									</div>
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<input type="checkbox" name="notify_user" value="1" class="notify-new-user-checkbox"<?= (i('?', 'create-user', 'notify_user') || !$_POST) ? (' checked="checked"') : (''); ?>>
									</div>
								</form>
								
							</div>
						</div>
					</div>
					
				</div>
				<!-- /.row -->

			</div>
			<!-- /.container-fluid -->

		</div>
		<!-- /#page-wrapper -->

<?php include('footer.php'); ?>
<?php i('-', 'create-user'); n('-', 'create-user'); ?>