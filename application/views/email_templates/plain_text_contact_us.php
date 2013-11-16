<html>
<body style="font-family: helvetica; font-size: 14px; width: 500px; line-height: 20px;">
	<div>
		<div>
			<ul style="list-style-type:none;line-height: 25px;">
				<li style="font-size: 18px; font-weight: bold;">
					<?php echo $customer_name; ?>
				</li>
				<li><?php echo $customer_email; ?></li>
				<li style="font-size: 10px;"><?php echo $customer_ip; ?></li>
			</ul>
		</div>
		<p style="background: rgba(30, 147, 201, 0.65); padding: 20px; color: white; border: 2px solid #1E93C9;">
			<?php echo $customer_message; ?>
		</p>
		
	</div>
</body>
</html>