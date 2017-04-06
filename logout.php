<?php include('init.php'); ?>
<?php
if ($user_id = auth('?', array('session_id' => session_id()))) {
	auth('^', array('session_id' => ''), $user_id);
}
i('-');
header('Location: login.php');
?>