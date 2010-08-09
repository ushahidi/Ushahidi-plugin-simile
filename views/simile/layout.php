<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $site_name; ?></title>
<?php 
echo $simile_js;
?>
<?php
plugin::render('stylesheet');
plugin::render('javascript');
?>
<script type="text/javascript">
<?php echo $js; ?>
</script>
</head>

<body>
<table>
	<tr style="height:40px;">
		<td class="header">
			<div class="title">
				<h1><?php echo $site_name; ?></h1>
				<span><?php echo $site_tagline; ?></span>
			</div>
			<div class="underlinemenu">
				<ul>
					<li><a href="<?php echo url::base(); ?>">Home</a></li>
					<li><a href="<?php echo url::site()."simile/"; ?>" <?php if ($this_page == "timemap" OR ! $this_page) {
						echo " class=\"selected\" ";
					} ?>>Time Map</a></li>
					<li><a href="<?php echo url::site()."simile/timeplot"; ?>" <?php if ($this_page == "timeplot" OR ! $this_page) {
						echo " class=\"selected\" ";
					} ?>>Time Plot</a></li>
				</ul>
			</div>
		</td>
	</tr>
	<tr style="height:100%;" valign="top">
		<td>
			<?php echo $content;?>
		</td>
	</tr>
</table>
<?php echo $google_analytics; ?>
</body>
</html>