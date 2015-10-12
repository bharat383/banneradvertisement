<?php
@include("config.php");
@include("class/Advertisement.Class.php");
$Advertisement = new Advertisement();
?>
<html>
	<meta charset="UTF-8">
	<head>
		<title>Example Script : Manage Advertisement</title>
	</head>
	<body>
		<h3>Add New Addvertisement</h3>
		<?php $Advertisement->DisplayMessage();?>
		<form method="post" enctype="multipart/form-data">
			<div class="row">
				<div>Title : </div>	
				<input type="text" name="title" value="<?php echo @$_POST['title'];?>" placeholder="Enter Advertisement Title" maxlength="50" size="50">
			</div>

			<div class="row">
				<div>Description : </div>	
				<textarea name="description" maxlength="150" placehoder="Enter Advertisement Description Here" cols="39"><?php echo @$_POST['description'];?></textarea>
			</div>

			<div class="row">
				<div>Banner Size : </div>	
				<select name="banner_size">
					<option value="120x60">120x60</option>
					<option value="120x90">120x90</option>
					<option value="125x125">125x125</option>
					<option value="180x150">180x150</option>
					<option value="120x400">120x400</option>
					<option value="250x250">250x250</option>
					<option value="468x60">468x60</option>
					<option value="728x90">728x90</option>
				</select>
			</div>
			<div class="row">
				<div>Banner Image : </div>	
				<input type="file" name="banner_image" accepty="image/*">
			</div>
			<div class="row">
				<div>Destination URL : </div>	
				<input type="text" name="destination_url" value="<?php echo @$_POST['destination_url'];?>" placeholder="Enter Destination URL. Example :http://www.google.com" size="50">
			</div>
			<div class="row">
				<div>Advertisement Impression : </div>	
				<input type="text" name="impression" value="<?php echo @$_POST['impression'];?>" placeholder="Enter Advertisement Impression in Numeric Value. Example : 100" size="50">
			</div>
			<div class="row">
				<input type="submit" name="submit_add" value="Add Advertisement">
			</div>	
		</form>
		<hr>
		<?php $Advertisement->DisplayBanner("120x60","1");?>
		<?php $Advertisement->DisplayBanner("125x125","2");?>
		<?php $Advertisement->DisplayBanner("468x60","1");?>
		<h3>Manage Advertisement</h3>
		<table width="100%" border="1" cellpadding="3" cellspacing="3">
			<tr>
				<th>#ID</th>
				<th>Title</th>
				<th>Description</th>
				<th>Image</th>
				<th>Impression</th>
				<th>Display Counter</th>
				<th>Click Counter</th>
				<th>Status</th>
			</tr>
			<?php if(!empty($Advertisement->data)) {  $total_clicks = $Advertisement->DisplayClickHistory();?>
				<?php foreach ($Advertisement->data as $record) { ?>
					<tr align="center">
						<td><?php echo $record['id'];?></td>
						<td><?php echo stripslashes($record['title']);?></td>
						<td><?php echo stripslashes($record['description']);?></td>
						<td><img src="banner/<?php echo stripslashes($record['banner_image']);?>" height="50%" width="50%"></td>
						<td><?php echo $record['impression'];?></td>
						<td><?php echo $record['display_counter'];?></td>
						<td><?php echo $total_clicks[$record['id']];?></td>
						<td>
							<?php if($record['active_status']==1) { ?>
							<a href="advertisement.php?status=0&id=<?php echo $record['id'];?>">Active</a>
							<?php } else { ?>
							<a href="advertisement.php?status=1&id=<?php echo $record['id'];?>">Inactive</a>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
			<?php } else { ?>
				<tr><td colspan="7" align="center">Records not available.</a></tr>
			<?php } ?>
		</table>
	</body>
</html>

