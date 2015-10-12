<?php
@include("config.php");
@include("class/Advertisement.Class.php");
$Advertisement = new Advertisement();
?>
<html>
	<meta charset="UTF-8">
	<head>
		<title>Example Script : Advertisement Clicks History</title>
	</head>
	<body>
		<h3> Advertisement Clicks History</h3>
		<?php $Advertisement->DisplayBanner("120x60","2");?>
		<h3>Advertisement History</h3>
		<div>Total Clicks : <?php echo count($Advertisement->data);?></div>
		<table width="50%" border="1" cellpadding="3" cellspacing="3">
			<tr>
				<th>Sr No.</th>
				<th>IP Address</th>
				<th>Click Time</th>
			</tr>
			<?php if(!empty($Advertisement->data)) { $i=0;?>
				<?php foreach ($Advertisement->data as $record) { ?>
					<tr align="center">
						<td><?php echo ++$i;?></td>
						<td><?php echo $record['ip_address'];?></td>
						<td><?php echo date("d-m-Y H:i:s",strtotime($record['click_time']));?></td>
					</tr>
				<?php } ?>
			<?php } else { ?>
				<tr><td colspan="3" align="center">Records not available.</a></tr>
			<?php } ?>
		</table>
	</body>
</html>

