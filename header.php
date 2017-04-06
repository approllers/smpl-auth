<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title><?= $settings['name']; ?> - SMPL Aggregator</title>

	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Bootstrap Plugin CSS -->
	<link href="css/bootstrap-switch.css" rel="stylesheet">
	<link href="css/bootstrap-tagsinput.css" rel="stylesheet">
	<link href="css/bootstrap-select.css" rel="stylesheet">

	
	<!-- Custom CSS -->
	<link href="css/sb-admin.css" rel="stylesheet">

	<!-- Morris Charts CSS -->
	<link href="css/plugins/morris.css" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>

<body>

	<div id="wrapper">

		<!-- Navigation -->
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="dashboard.php"><?= $settings['name']; ?></a>
			</div>
			<!-- Top Menu Items -->
			<ul class="nav navbar-right top-nav">
				<li class="dropdown<?= ($page == 'account.php') ? (' active') : (''); ?>">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><?= $user['displayname']; ?><b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li<?= ($page == 'account.php') ? (' class="active"') : (''); ?>>
							<a href="account.php"><i class="fa fa-fw fa-user"></i> My Account</a>
						</li><?php /*
						<li<?= ($page == 'favorites.php') ? (' class="active"') : (''); ?>>
							<a href="favorites.php"><i class="fa fa-fw fa-heart"></i> Favorites</a>
						</li>*/ ?>
						<li class="divider"></li>
						<li>
							<a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
						</li>
					</ul>
				</li>
			</ul>
