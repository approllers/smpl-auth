<?php include('init.php'); if (!$userid) { header('Location: login.php'); } ?>
<?php

if ($_POST) {
	clean($_POST);
	if ($_POST['action'] == 'account') {
		if (!valid('email', $_POST['email'])) {
			n('+', 'account', 'error', 'Invalid email address.');
		}
		if ($_POST['username'] != '' && !valid('username', $_POST['username'])) {
			n('+', 'account', 'error', 'Invalid username. Please use only letters, numbers, hyphens, and underscores.');
		}
		
		if (!n('?', 'account', 'error')) {
			$userinfo = array(
				'firstname' => $_POST['firstname'],
				'lastname' => $_POST['lastname'],
				'displayname' => htmlspecialchars($_POST['displayname']),
				'username' => $_POST['username'],
				'email' => $_POST['email'],
			);
			auth('^', $userinfo, $user['id']);
			$user = auth('@', array('id' => $user['id']));
			n('+', 'account', 'success', 'Your account information has been updated.');
		}
			
	}
	if ($_POST['action'] == 'password') {
		//print '<pre>'; print_r($_POST); print '</pre>';
		if ($user['password'] != md5($_POST['currentpassword'])) {
			n('+', 'account', 'error', 'Incorrect current password.');
		}
		if (!valid('text', $_POST['password'], 5)) {
			if (!n('?', 'account', 'error')) {
				n('+', 'account', 'error', 'Your new password must be at least 5 characters in length.');
			}
		}
		if ($_POST['password'] !== $_POST['confirmpassword']) {
			if (!n('?', 'account', 'error')) {
				n('+', 'account', 'error', 'Passwords do not match.');
			}
		}
		
		if (!n('?', 'account', 'error')) {
			auth('^', array('password' => md5($_POST['password'])), $user['id']);
			$user = auth('@', array('id' => $user['id']));
			n('+', 'account', 'success', 'Your account information has been updated.');
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
<?php foreach (n('|', 'account', 'error') as $k => $message) { ?>
<div>
	<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<strong>Error:</strong> <?= $message; ?>
	</div>
</div>
<?php } ?>
<?php foreach (n('|', 'account', 'success') as $k => $message) { ?>
<div>
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<strong>Success:</strong> <?= $message; ?>
	</div>
</div>
<?php } ?>
						<h1 class="page-header">
							Account
						</h1>
						
					</div>
				</div>
				<!-- /.row -->

				<div class="row">
					<div class="col-lg-6">
						<div class="panel panel-default">
							<div class="panel-heading clearfix">
								<h3 class="panel-title pull-left">Account Information</h3>
								<div class="pull-right">
									<span class="badge"><?= $user['group_name']; ?></span>
								</div>
							</div>
							<div class="panel-body">
								<form role="form" action="" method="post">
									<input type="hidden" name="action" value="account">
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<label>First Name</label>
										<input type="text" class="form-control" name="firstname" value="<?= $user['firstname']; ?>">
										<!--<p class="help-block">Example block-level help text here.</p>-->
									</div>
		
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<label>Last Name</label>
										<input type="text" class="form-control" name="lastname" value="<?= $user['lastname']; ?>">
										<!--<p class="help-block">Example block-level help text here.</p>-->
									</div>
									
									<div class="form-group col-lg-12 col-md-12 col-sm-12">
										<label>Email Address</label>
										<input type="text" class="form-control" name="email" value="<?= $user['email']; ?>">
										<!--<p class="help-block">Example block-level help text here.</p>-->
									</div>
									
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<label>Username</label>
										<input type="text" class="form-control" name="username" value="<?= $user['username']; ?>">
										<!--<p class="help-block">Example block-level help text here.</p>-->
									</div>
									
									<div class="form-group col-lg-6 col-md-6 col-sm-6">
										<label>Display Name</label>
										<input type="text" class="form-control" name="displayname" value="<?= $user['displayname']; ?>">
										<!--<p class="help-block">Example block-level help text here.</p>-->
									</div>
									
									<input type="submit" class="btn btn-primary" value="Update Account" />
		
								</form>
							</div>
						</div>
					</div>
					
					<div class="col-lg-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Change Password</h3>
							</div>
							<div class="panel-body">
								<form role="form" action="" method="post">
									<input type="hidden" name="action" value="password">
									<div class="form-group col-lg-6">
										<label>Current Password</label>
										<input type="password" class="form-control" name="currentpassword">
										<!--<p class="help-block">Example block-level help text here.</p>-->
									</div>
									<div class="clearfix"></div>
									<div class="form-group col-lg-6">
										<label>New Password</label>
										<input type="password" class="form-control" name="password">
										<!--<p class="help-block">Example block-level help text here.</p>-->
									</div>
		
									<div class="form-group col-lg-6">
										<label>Confirm New Password</label>
										<input type="password" class="form-control" name="confirmpassword">
										<!--<p class="help-block">Example block-level help text here.</p>-->
									</div>
									
									<input type="submit" class="btn btn-primary" value="Update Password" />
		
								</form>
							</div>
						</div>
					</div>
					
					
					<div class="col-lg-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Access Management</h3>
							</div>
							<div class="panel-body">
																
								<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">
									<i class="fa fa-trash-o"></i>
									Deactivate Your Account
								</button>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Confirm to Deactivate Your Account</h4>
			</div>
			<div class="modal-body">
				Type in your password to deactivate your account.
				<br /><br />
				<div class="form-group form-inline">
					<label>Password</label>
					<input type="password" class="form-control" name="password">
				</div>
			</div>
			<div class="modal-footer">	
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-danger deactivate-user" data-user-id="<?= $user['id']; ?>">Deactivate</button>
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

<?php include('footer.php'); ?>
<?php n('-', 'account'); ?>