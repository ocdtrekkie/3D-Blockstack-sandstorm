<?php

$db = new SQLite3('/var/db3d.sqlite');
$db->exec("CREATE TABLE IF NOT EXISTS backend (sandstormid TEXT PRIMARY KEY, handle TEXT, bestScore INTEGER)");

$sandstorm_userid = $_SERVER['HTTP_X_SANDSTORM_USER_ID'];
$sandstorm_handle = $_SERVER['HTTP_X_SANDSTORM_PREFERRED_HANDLE'];

if ($sandstorm_userid == "" && $sandstorm_handle == "" && $_SERVER['HTTP_X_SANDSTORM_USERNAME'] == "Anonymous%20User") {
	$sandstorm_userid = "0"; $sandstorm_handle = "Anonymous";
}

$userexists = $db->querySingle('SELECT * FROM backend WHERE sandstormid = "' . $sandstorm_userid . '"');
if ($userexists == null || $userexists == false) {
	$useradd = $db->prepare('INSERT INTO backend (sandstormid, handle) VALUES (:sandstormid, :handle)');
	$useradd->bindValue(':sandstormid', $sandstorm_userid);
	$useradd->bindValue(':handle', $sandstorm_handle);
	$useraddresult = $useradd->execute();
}

$_POST = json_decode(file_get_contents('php://input'), true);

$action = "";
$score = "";
if (isset($_POST['bestScore'])) { $score = $_POST['bestScore']; }
if (isset($_GET['action'])) { $action = $_GET['action']; }

if ($score != "") {
	$getbestscoreresult = $db->querySingle('SELECT bestScore FROM backend WHERE sandstormid = "' . $sandstorm_userid . '"');
	if ($getbestscoreresult == null || $getbestscoreresult < $score) {
		$updatescore = $db->prepare('UPDATE backend SET handle = :handle, bestScore = :bestScore WHERE sandstormid = :sandstormid LIMIT 1');
		$updatescore->bindValue(':sandstormid', $sandstorm_userid);
		$updatescore->bindValue(':handle', $sandstorm_handle);
		$updatescore->bindValue(':bestScore', $score);
		$updatescoreresult = $updatescore->execute();
	}
}

if ($action == "getbestscore") {
	$bestscore = $db->querySingle('SELECT bestScore FROM backend WHERE sandstormid = "' . $sandstorm_userid . '"');
	if ($bestscore != "") {	echo '{"value":"' . $bestscore . '"}'; } else { echo '{"value":"0"}'; }
}

?>