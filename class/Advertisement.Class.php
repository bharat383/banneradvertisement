<?php

/* 
FILE NAME : Advertisement.Class.php
FILE LOCATION : class/Advertisement.Class.php
*/

class Advertisement
{
	public function __construct()
	{
		//ADD NEW ADVERTISEMENT
		if(isset($_POST['submit_add']) && !empty($_POST))
		{
			$this->AddAdvertisement();	
		}

		//CHANGE ACTIVE/INACTIVE STATUS OF THE ADVERTISEMENT
		if(isset($_GET['status']) && isset($_GET['id']) && is_numeric($_GET['id']) && is_numeric($_GET['status']) && $_GET['status']<=1)
		{
			$this->ChangeStatus($_GET['id'],$_GET['status']);
		}

		//CLICK COUNTER FOR THE ADVERTISEMENT
		if(basename($_SERVER['PHP_SELF'])=="advcounter.php" && isset($_GET['id']) && is_numeric($_GET['id']))
		{
			$this->ClickCount($_GET['id']);
		}

		//CLICK COUNTER FOR THE ADVERTISEMENT
		if(basename($_SERVER['PHP_SELF'])=="clickhistory.php" && isset($_GET['id']) && is_numeric($_GET['id']))
		{
			$this->data = $this->DisplayClickHistory($_GET['id']);	
		}
		
		//ADVERTISEMENT PAGE
		if(basename($_SERVER['PHP_SELF'])=="advertisement.php")
		{
			$array = array();
			$this->data = $this->DisplayAdvertisement($array);	
		}
		

	}

	protected function AddAdvertisement()
	{
		if(empty($_POST['title']))
		{
			$_SESSION['message'][] = "Please Enter Advertisement Title.";
		}

		if(empty($_POST['description']))
		{
			$_SESSION['message'][] = "Please Enter Advertisement Description.";
		}

		if(empty($_FILES['banner_image']))
		{
			$_SESSION['message'][] = "Please Select Advertisement Image File.";
		}
		if(!empty($_FILES['banner_image']))
		{
			$image_data =  getimagesize($_FILES['banner_image']['tmp_name']);
			$width = $image_data[0];
			$height = $image_data[1];

			$banner_size = explode("x",$_POST['banner_size']);
			if($width>$banner_size[0] || $height>$banner_size[1])
			{
				$_SESSION['message'][] = "Image size is not match with Banner Size.";	
			}
		}

		if(empty($_POST['destination_url']))
		{
			$_SESSION['message'][] = "Please Enter Destination URL.";
		}

		if(empty($_POST['impression']) || $_POST['impression']<=0 || !is_numeric($_POST['impression']))
		{
			$_SESSION['message'][] = "Please Enter Advertisement Impression in Numeric Format only.";
		}

		if(empty($_SESSION['message']))
		{
			$fileuploaded=0;
			$allowedfiletypes = array("png","jpg","jpeg","gif");
			$currentfile_extension = end(@explode(".",$_FILES['banner_image']['name']));
			if(in_array(strtolower($currentfile_extension),$allowedfiletypes))
			{
				$filename = date("YmdHis").rand(1000,9999).".".$currentfile_extension;
				if(!file_exists("banner/")) 
				{
					mkdir("banner/");
					chmod("banner/", 0755);
				}
				
				if(@move_uploaded_file($_FILES['banner_image']['tmp_name'], "banner/".$filename))
				{
					$fileuploaded=1;
					//CHANGIN FILE PERMISSION
					@chmod("banner/".$filename, 0755);
				}
				else
				{
					$_SESSION['message'][] = "File not uploaded..";
				}
			}
			else
			{
				$_SESSION['message'][] = "Please Select jpg, pgn, jpeg or gif file only for banner.";
			}

			if($fileuploaded==1)
			{
				mysql_query("insert into advertisement_master set 
							title = '".addslashes($_POST['title'])."',
							description = '".addslashes($_POST['description'])."',
							banner_size = '".addslashes($_POST['banner_size'])."',
							banner_image = '".addslashes($filename)."',
							destination_url = '".addslashes($_POST['destination_url'])."',
							impression = '".addslashes($_POST['impression'])."',
							display_counter = '0',
							active_status = '1'
						");
				if(mysql_affected_rows()>0)
				{
					$_SESSION['message'][] = "Please Select jpg, pgn, jpeg or gif file only for banner.";
					@header("location:advertisement.php");
					exit;
				}
			}	
		}
	}

	protected function ChangeStatus($id,$status)
	{
		mysql_query("update advertisement_master set active_status='".$status."' where id = '".$id."'");
		@header("location:advertisement.php");
		exit;
	}

	protected function DisplayAdvertisement(array $parameter)
	{
		$query_string = "select * from advertisement_master";

		//GET RECORD AS PER BANNER SIZE
		if(@$parameter['banner_size']!="")
		{
			$query_string.=" where banner_size = '".$parameter['banner_size']."' and active_status='1' and display_counter<impression order by rand()";
		}

		if(@$parameter['limit']!="")
		{
			$query_string.=" limit ".$parameter['limit'];
		}

		//GET RECORD AS PER ADVERTISEMENT ID
		if(@$parameter['id']!="")
		{
			$query_string.=" where id = '".$parameter['id']."' and active_status='1' and display_counter<impression";
		}

		$array = array();
		$query = mysql_query($query_string) or die(mysql_error());
		if(mysql_num_rows($query)>0)
		{
			while($data = mysql_fetch_assoc($query))
			{
				$array[] = $data;
			}
		}
		return $array;
	}

	public function DisplayBanner($banner_size,$limit)
	{
		$data = $this->DisplayAdvertisement(array("banner_size"=>$banner_size,"limit"=>$limit));
		if(!empty($data))
		{
			foreach ($data as $advertisement) 
			{
				mysql_query("update advertisement_master set display_counter=display_counter+1 where id = '".$advertisement['id']."'");
				echo "<div style='border:dotted red;display:inline-block;padding:5px;margin:5px;'>";
					echo "<a href='advcounter.php?id=".$advertisement['id']."' target='_new'>";
						echo "<img src='banner/".stripslashes($advertisement['banner_image'])."' alt='".stripslashes($advertisement['title'])."'>";
					echo "</a>";
					echo "<p><b>".stripslashes($advertisement['title'])."</b></p>";
					echo "<p>".stripslashes($advertisement['description'])."</p>";
					echo "<p><a href='advcounter.php?id=".$advertisement['id']."' target='_new'>".stripslashes($advertisement['destination_url'])."</a></p>";
				echo "</div>";
			}
		}
	}

	protected function ClickCount($id)
	{
		$data = $this->DisplayAdvertisement(array("id"=>$id));
		$data = $data[0];
		if(!empty($data))
		{
			mysql_query("insert into advertisement_click_counter set 
							advertisement_id = '".$id."',
							ip_address = '".$_SERVER['REMOTE_ADDR']."',
							click_time = '".date("Y-m-d H:i:s")."'
						") or die(mysql_error());
			@header("location:".stripslashes($data['destination_url']));
			exit;
		}
	}

	public function DisplayClickHistory($id=0)
	{
		$array = array();
		if($id==0)
		{
			$query = mysql_query("select advertisement_id, count(id) as total from advertisement_click_counter group by advertisement_id");
			if(mysql_num_rows($query))
			{
				while($data = mysql_fetch_assoc($query))
				{
					$array[$data['advertisement_id']]=$data['total'];
				}
			}
		}
		else
		{
			$query = mysql_query("select * from advertisement_click_counter where advertisement_id ='".$id."'");	
			if(mysql_num_rows($query))
			{
				while($data = mysql_fetch_assoc($query))
				{
					$array[]=$data;
				}
			}
		}
		return $array;
	}

	public function DisplayMessage()
	{
		if(isset($_SESSION['message']) && !empty($_SESSION['message']))
		{
			foreach ($_SESSION['message'] as $key => $value) {
				echo '<div>'.stripslashes($value).'</div>';
			}	
		}
		unset($_SESSION['message']);
	}


}
?>