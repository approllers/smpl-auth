<?php include('init.php'); if (!$userid) { header('Location: login.php'); } ?>

<?php
// form processing
if ($_POST) {
	i('+', 'settings', $_POST);
	if ($_POST['action'] == 'site') {
		if (!valid('text', $_POST['name'])) {
			n('+', 'settings', 'error', 'You must input a site name.');
		}
		if (!valid('url', $_POST['main_url'])) {
			n('+', 'settings', 'error', 'Main URL must be a valid URL.');
		}
		if (!valid('url', $_POST['admin_url'])) {
			n('+', 'settings', 'error', 'Admin URL must be a valid URL.');
		}
		if (!valid('email', $_POST['email'])) {
			n('+', 'settings', 'error', 'Site email must be a valid email address.');
		}
		if (isset($_POST['send_mail_method'])) {
			if (!valid('text', $_POST['smtp_username'])) {
				n('+', 'settings', 'error', 'Please provide a valid SMTP Username.');
			}
			if (!valid('text', $_POST['smtp_password'])) {
				n('+', 'settings', 'error', 'Please provide a valid SMTP Password.');
			}
			if (!valid('url', $_POST['smtp_host'])) {
				n('+', 'settings', 'error', 'Please provide a valid SMTP Host.');
			}
			if (!valid('int', $_POST['smtp_port'], 0)) {
				n('+', 'settings', 'error', 'Please provide a valid SMTP Port.');
			}

		} 
		
		if (!n('?', 'settings', 'error')) {
			$send_mail_method = (isset($_POST['send_mail_method'])) ? ('smtp') : ('php');
			$new_settings = array(
				'email' => $_POST['email'],
				'main_url' => $_POST['main_url'],
				'admin_url' => $_POST['admin_url'],
				'name' => htmlspecialchars($_POST['name']),
				'description' => htmlspecialchars($_POST['description']),
				'send_mail_method' => $send_mail_method,
				'smtp_username' => $_POST['smtp_username'],
				'smtp_password' => $_POST['smtp_password'],
				'smtp_host' => $_POST['smtp_host'],
				'smtp_port' => $_POST['smtp_port']
			);
			settings('^', $new_settings, $settings['id']);
			$settings = settings('@');
			n('+', 'settings', 'success', 'Website settings have been updated.');
		}
	}
	
	if ($_POST['action'] == 'user') {
		if (isset($_POST['send_welcome_email'])) {
			$send_welcome_email = 1;
		}
		else { $send_welcome_email = 0; }
		if (!n('?', 'settings', 'error')) {
			$new_settings = array(
				'default_new_user_group_id' => $_POST['default_new_user_group_id'],
				'send_welcome_email' => $send_welcome_email,
				'send_welcome_email_subject' => htmlspecialchars($_POST['send_welcome_email_subject']),
				'send_welcome_email_body' => htmlspecialchars($_POST['send_welcome_email_body']),
				'send_reset_link_email_subject' => htmlspecialchars($_POST['send_reset_link_email_subject']),
				'send_reset_link_email_body' => htmlspecialchars($_POST['send_reset_link_email_body']),
				'send_random_password_subject' => htmlspecialchars($_POST['send_random_password_subject']),
				'send_random_password_body' => htmlspecialchars($_POST['send_random_password_body']),
				'send_new_user_info_subject' => htmlspecialchars($_POST['send_new_user_info_subject']),
				'send_new_user_info_body' => htmlspecialchars($_POST['send_new_user_info_body'])
			);
			settings('^', $new_settings, $settings['id']);
			$settings = settings('@');
			n('+', 'settings', 'success', 'User settings have been updated.');
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
<?php foreach (n('|', 'settings', 'error') as $k => $message) { ?>
<div>
	<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<strong>Error:</strong> <?= $message; ?>
	</div>
</div>
<?php } ?>
<?php foreach (n('|', 'settings', 'success') as $k => $message) { ?>
<div>
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<strong>Success:</strong> <?= $message; ?>
	</div>
</div>
<?php } ?>
		
					</div>
				</div>
				<!-- /.row -->
				<div class="row">
					<div class="col-lg-12">
						<div id="header-tabs" class="">
							<ul class="nav nav-tabs navbar-right">
								<li<?= (!$_GET || $_GET['section'] == 'site') ? (' class="active"') : (''); ?>><a href="settings.php?section=site">
									<i class="fa fa-desktop"></i> Website</a>
								</li>
								<li<?= ($_GET && $_GET['section'] == 'user') ? (' class="active"') : (''); ?>><a href="settings.php?section=user">
									<i class="fa fa-users"></i> User</a>
								</li>
							</ul>
						</div>
						<h1 class="page-header">
							Settings
						</h1>
						
					</div>
				</div>
				<!-- /.row -->
				
				
				
<?php if (!$_GET || $_GET['section'] == 'site') { ?>
				<div class="row top-buffer">
					<div class="col-lg-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Website Settings</h3>
							</div>
							<div class="panel-body">
								<form role="form" action="" method="post">
									<input type="hidden" name="action" value="site">
									<div class="form-group">
										<label>Site Name</label>
										<input name="name" value="<?= $settings['name']; ?>" class="form-control">
										<p class="help-block">Example block-level help text here.</p>
									</div>
									<div class="form-group">
										<label>Site Description</label>
										<textarea class="form-control" rows="3" name="description"><?= $settings['description']; ?></textarea>
										<p class="help-block">Example block-level help text here.</p>
									</div>
									<div class="form-group">
										<label>Main URL</label>
										<input name="main_url" value="<?= $settings['main_url']; ?>" class="form-control">
										<p class="help-block">Example block-level help text here.</p>
									</div>
									<div class="form-group">
										<label>Administration URL</label>
										<input name="admin_url" value="<?= $settings['admin_url']; ?>" class="form-control">
										<p class="help-block">Example block-level help text here.</p>
									</div>
									<div class="form-group">
										<label>Email</label>
										<input name="email" value="<?= $settings['email']; ?>" class="form-control">
										<p class="help-block">Example block-level help text here.</p>
									</div>
									<div class="form-group">
										<label>Send Mail Method</label>
										<input type="checkbox" name="send_mail_method" value="1" class="send-mail-method-checkbox"<?= ($settings['send_mail_method'] == 'smtp' || isset($_POST['send_mail_method'])) ? (' checked="checked"') : (''); ?>>
									</div>
									
									<div class="smtp-section well well-sm">
									
										<div class="form-group top-buffer">
											<div class="form-group col-lg-6 col-md-6 col-sm-6">
												<label>SMTP Username</label>
												<input type="text" class="form-control" name="smtp_username" value="<?= $settings['smtp_username']; ?>">
												<!--<p class="help-block">Example block-level help text here.</p>-->
											</div>
				
											<div class="form-group col-lg-6 col-md-6 col-sm-6">
												<label>SMTP Password</label>
												<input type="text" class="form-control" name="smtp_password" value="<?= $settings['smtp_password']; ?>">
												<!--<p class="help-block">Example block-level help text here.</p>-->
											</div>
										</div>
										<div class="form-group bottom-buffer">
											<div class="col-lg-10 col-md-10 col-sm-10 col-xs-9">
												<label>SMTP Host</label>
												<input name="smtp_host" value="<?= $settings['smtp_host']; ?>" type="text" class="form-control">
											</div>
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
												<label>&nbsp;</label>
												<input name="smtp_port" value="<?= $settings['smtp_port']; ?>" type="text" class="form-control" placeholder="Port">
											</div>
										</div>
										<div class="clearfix"></div>
										
									</div><!-- /.smtp-section -->
									<div class="clearfix"></div>
									<hr />
									
									<div class="form-group col-lg-12">
										<input type="submit" class="btn btn-primary" value="Save Settings" />
									</div>
								</form>
							</div>
						</div>


					</div>
				</div>
				<!-- /.row -->
<?php } ?>
<?php if ($_GET && $_GET['section'] == 'user') { ?>
				<div class="row top-buffer">
					<div class="col-lg-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">User Settings</h3>
							</div>
							<div class="panel-body">
								<form role="form" action="" method="post">
									<input type="hidden" name="action" value="user">
									<div class="form-group">
										<label>Default New User Group</label>
										<select name="default_new_user_group_id" class="selectpicker" data-width="125px">
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
											<option value="<?= $group['id']; ?>"<?= ($settings['default_new_user_group_id'] == $group['id']) ? (' selected') : (''); ?>><?= $group['name']; ?></option>
		<?php
			}
		}
		?>
										</select>
										<!--<p class="help-block">Example block-level help text here.</p>-->
									</div>
									<hr />
									<div class="form-group">
										<label>New Registered Users</label>
										<input type="checkbox" name="send_welcome_email" value="1" class="send-welcome-email-checkbox"<?= (!empty($settings['send_welcome_email'])) ? (' checked="checked"') : (''); ?>>
									</div>
									
									<div class="welcome-email-section well well-sm">
										<div class="form-group bottom-buffer">
											<div class="col-lg-12">
												<label>Welcome Email Subject</label>
												<input name="send_welcome_email_subject" value="<?= $settings['send_welcome_email_subject']; ?>" type="text" class="form-control">
											</div>
											
											<div class="col-lg-12 top-buffer">
												<label>Welcome Email Body</label>
												<textarea class="form-control" rows="5" name="send_welcome_email_body"><?= $settings['send_welcome_email_body']; ?></textarea>
												<p class="help-block">Example block-level help text here.</p>
											</div>
										</div>
										<div class="clearfix"></div>	
									</div><!-- /.welcome-email-section -->
									<hr />
									<div class="form-group">
										<label>Reset Link Subject</label>
										<input name="send_reset_link_email_subject" value="<?= $settings['send_reset_link_email_subject']; ?>" type="text" class="form-control">
									</div>
									<div class="form-group">
										<label>Reset Link Body</label>
										<textarea class="form-control" rows="5" name="send_reset_link_email_body"><?= $settings['send_reset_link_email_body']; ?></textarea>
										<p class="help-block">Example block-level help text here.</p>
									</div>
									<div class="clearfix"></div>
									<hr />
									
									<div class="form-group">
										<label>Random Password Subject</label>
										<input name="send_random_password_subject" value="<?= $settings['send_random_password_subject']; ?>" type="text" class="form-control">
									</div>
									<div class="form-group">
										<label>Random Password Body</label>
										<textarea class="form-control" rows="5" name="send_random_password_body"><?= $settings['send_random_password_body']; ?></textarea>
										<p class="help-block">Example block-level help text here.</p>
									</div>
									<div class="clearfix"></div>
									<hr />
									
									
									<div class="form-group">
										<label>New User Info Subject</label>
										<input name="send_new_user_info_subject" value="<?= $settings['send_new_user_info_subject']; ?>" type="text" class="form-control">
									</div>
									<div class="form-group">
										<label>New User Info Body</label>
										<textarea class="form-control" rows="5" name="send_new_user_info_body"><?= $settings['send_new_user_info_body']; ?></textarea>
										<p class="help-block">Example block-level help text here.</p>
									</div>
									<div class="clearfix"></div>
									<hr />
									
									<div class="form-group col-lg-12">
										<input type="submit" class="btn btn-primary" value="Save Settings" />
									</div>
								</form>
							</div>
						</div>

					</div>
				</div>
				<!-- /.row -->
<?php } ?>
			</div>
			<!-- /.container-fluid -->

		</div>
		<!-- /#page-wrapper -->
		
<?php include('footer.php'); ?>
<?php n('-', 'settings'); ?>
<?php i('-', 'users', 'where'); ?>
<?php i('-', 'users', 'search'); ?>
<?php i('-', 'users', 'search_by'); ?>
<?php i('-', 'users', 'sort'); ?>
<?php i('-', 'users', 'sort_direction'); ?>