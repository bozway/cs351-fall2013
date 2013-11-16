<div id="password_container">
	<div class="password_header">
		<div class="password_banner">
	    	<p class="title">Forgot your password?</p>
	        <p>Enter the email address you used when you joined and we’ll send you instructions to reset your password.</p>
	    </div>
	</div>	
    <div class="forgot_password_content">
    	<label id="error_message" for="email"><?php if(isset($error_message)){echo $error_message;}?></label>
    	<ul>
			<li><p>Your Registered Email</p><input id="email" type="text" value="" class="span3"></li>
            <li><a id="retrieve_password" class="btn btn-small btn-block btn-primary">Retrieve Password</a></li>
        </ul>
    </div>
</div>