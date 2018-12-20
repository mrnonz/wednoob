<?
	require_once 'application/_config.php';
	include_once 'template/header.php';
	
	if(isset($_SESSION["member_uid"]))
	{
		$query_last = $connect->query("SELECT * FROM `order` WHERE `order_uid` = '".$_SESSION['member_uid']."' ORDER BY order_id DESC");
		$num_last = $query_last->num_rows;

		if($num_last == '0')
		{
			header('location:'.$config['site_url']."/member.php?page=historyorder");
		}
		else
		{
			$fetch_last = $query_last->fetch_assoc();
			$query_last_product = $connect->query("SELECT * FROM `product` WHERE `product_id` = '".$fetch_last['order_product_id']."'");
			$num_last_product = $query_last_product->num_rows;
			if($num_last_product == '0')
			{
				header('location:'.$config['site_url']."/member.php?page=historyorder");
			}
			else
			{
				$fetch_last_product = $query_last_product->fetch_assoc();
				$platform_id = $fetch_last_product['product_platform'];
				$query_event_go = $connect->query("SELECT * FROM `product_event` WHERE `event_type` = '".$platform_id."'");
				$num_event_go = $query_event_go->num_rows;
				if($num_event_go == '0')
				{
					header('location:'.$config['site_url']."/member.php?page=historyorder");
				}
				else
				{
					$fetch_last_go = $query_event_go->fetch_assoc();
					$event_go_link = $fetch_last_go['event_go'];
					header('location:'.$event_go_link);
				}
				
			}
		}
	}

	include_once 'template/footer.php';
?>