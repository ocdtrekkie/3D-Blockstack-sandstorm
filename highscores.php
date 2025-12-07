<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <title>Stacking Game</title>
  <!-- css -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/themes.css">
  <!-- favicon -->
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="shortcut icon" href="favicon.ico" />
</head>
<body>
  <div id="highscores">
    <div class="content">
      <h1>High Scores</h1>
	  <p>Even among friends, some are better than others.</p>
	  <table align="center">

	  <?php
		$db = new SQLite3('/var/db3d.sqlite');
		
		$scorelist = $db->query("SELECT handle, bestScore FROM backend ORDER BY bestScore DESC LIMIT 10");
		$i = 0;
		while ($res = $scorelist->fetchArray(SQLITE3_ASSOC)) {
			$ordinal = $i + 1;
			echo "<tr><td><strong>$ordinal. " . $res['handle'] . "</strong></td><td><strong>" . $res['bestScore'] . "</strong></td></tr>";
			$i++;
		}
	  ?>
	  </table>
	  <p><a href="index.php">Go Back</a></p>
    </div>
  </div>
</body>
</html>
