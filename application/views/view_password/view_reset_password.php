<div id="password_container">
	<div class="password_header">
		<div class="password_banner">
			<p class="title">Reset password</p>
        	<p>In order to reset your password, please enter a new password for FindMySong</p>	
		</div>    	
    </div>
    <div class="reset_password_content">
    	<p class="username"><span>Username</span><?=$user_name?></p>
        <p class="email"><span>Email</span><?=$email?></p>
        <input id="userid" type="hidden" value="<?=$user_id?>">
        <input id="token" type="hidden" value="<?=$token?>">
    	<form id="password_reset_form">
	    	<ul>
	        	<li>
	        		<p>Enter New Password</p>
	        		<div><input id="password" name="password" type="password" value="" class="span3"></div>
	        	</li>
	            <li>
	            	<p>Confirm New Password</p>
	            	<div><input id="repassword" name="repassword" type="password" value="" class="span3"></div>
	            </li>
	            <li><a id="reset_password" class="btn btn-small btn-block btn-primary">Reset Password</a></li>
	        </ul>
        </form>
    </div>
</div>