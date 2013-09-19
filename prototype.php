<?php
	include_once ("settings.php");
	include_once (SERVER_ROOT . "/include/header.php");
?>


<div id="top">
	<div class="center">
		<div>
			<a href="/"><img id="confused_smiley" src="<?= WEB_ROOT ?>/image/site/smiley_blue_confused.png" alt="confused" /></a>
			<div id="front">
				<img alt="bubble" src="<?= WEB_ROOT ?>/image/site/bubble.png" /><br/>
				<h2>Get a suggestion on where to eat lunch today</h2>
				<h3>Where are you ?</h3>
				<form action="<?= WEB_ROOT ?>/gen2.php" method="get">
					<table>
						<tr>
							<td><input id="input_query" type="text" name="location" /></td>
							<td><input id="btn_getLunchPlace" type="submit" value="" /></td>
						</tr>
					</table>

				</form>
				<em>For example: Street name 66</em>
			</div>
		</div>
	</div>
</div>

<script>
	getLocation();
</script>
<?php
	include_once (SERVER_ROOT . "/include/bottom.php");
?>
