<?php include('config.php'); include('smpl.php'); ?>
<?php
$settings = settings('@');
$uri = explode('/', $_SERVER['REQUEST_URI']);
$page = array_pop($uri);
$page = explode('?', $page);
$page = $page[0];
if ($userid = auth('?', array('session_id' => session_id()))) {
	$user = auth('@', array('id' => $userid));
}
?>