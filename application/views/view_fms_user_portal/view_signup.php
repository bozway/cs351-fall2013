
<div id="signupModal" class="login modal hide fade in hidden-phone" tabindex="-1" role="dialog" aria-labelledby="loginlabel" aria-hidden="true">
	<!-- Signup modal body -->
	<div id="signup_modal_body" class="modal-body">
		<form id='fms_signup_form'>
		<div class="modal-body-title">
			<span class="modal-title font-light">WELCOME TO FINDMYSONG!</span>
			<label class="close-signup-modal" data-dismiss="modal"></label>
		</div>
		<div class="modal-body-content">
			<div class="options-column">
				<span class="option-block">Already have an account? <a id="anchor_login" href="#">Click Here</a></span>
			</div>
			<div class="options-column">
				<a class="option-block signup_facebook_button" id="signup_facebook" href="">Sign up with Facebook</a>
			</div>				
			<div class="form-wrapper popover-top-dark">
				<ul class="form-column">
					<li class="input-block"> 
						<div>
						<input type="text" name="user_namelast" value="" id="user_namelast" placeholder="Last Name" title="Last Name" tabindex="2">						</div>
						<label id="help_name_wrap">
							<a id="help_name" rel="popover" data-content="Please use your real name. You can enter your aritst or band name later!" data-placement="top" data-original-title="" title=""></a>
						</label>
						<label for="user_namelast" class="error" style="width:0;height:0;overflow:hidden"></label>
					</li>
					<li class="input-block">
						<input type="email" name="user_email_repeat" value="" id="user_email_repeat" title="Confirm Email" placeholder="Confirm Email" tabindex="4">						
						<label id="help_email_wrap">
							<a id="help_email" rel="popover" data-content="Whoops! Make user your email addresses match up. Try again" data-placement="top" data-original-title="" title=""></a>
						</label>
						<label for="user_email_repeat" class="error" style="width:0;height:0;overflow:hidden"></label>
					</li>					
					<li class="input-block">
						<input type="password" name="user_password_repeat" value="" id="user_password_repeat" placeholder="Confirm Password" title="Confirm Password" tabindex="6">						<label id="help_psw_wrap">
							<a id="help_psw" rel="popover" data-content="Whoops! Make sure your passwords match up. Try again" data-placement="top" data-original-title="" title=""></a>
						</label>
						<label for="user_password_repeat" class="error" style="width:0;height:0;overflow:hidden"></label>
					</li>
				</ul>
				<ul class="form-column">
					<li class="input-block">
						<input type="text" name="user_namefirst" value="" id="user_namefirst" placeholder="First Name" title="First Name" autofocus="true" tabindex="1">						<label for="user_namefirst" class="error" style="width:0;height:0;overflow:hidden"></label>				
					</li>
					<li class="input-block">
						<input type="email" name="user_email" value="" id="user_email" placeholder="Email" title="Email" tabindex="3">						<label id="signup_email_error" for="user_email" class="error"></label>
					</li>					
					<li class="input-block">
						<input type="password" name="user_password" value="" id="user_password" placeholder="Password" title="Password" tabindex="5">						<label for="user_password" class="error"></label>
					</li>										
				</ul>
			</div>
		</div>
		<div class="fms-terms-signup">
			<p id="checkbox" class="fms_checkbox unchecked"></p>
			<p id="fms-terms-signup-message">By checking the box and clicking “Let’s Rock”, I understand that I am joining FindMySong, and I have read and accepted the 
				<a href="<?= base_url('terms') ?>">Terms</a> and <a href="<?= base_url('privacy') ?>">Privacy Policy</a>.</p>
			
			<button id="fms_join" class="btn btn-large btn-primary btn-block signup_submit_btn btn-embossed" type="submit" name="submit">Let's Rock!</button>
		</div>
		</form>
	</div>
	
	
	
<!-- THE SIGN IN MODAL BODY -->	
	
	<div id="signin_modal_body" class="modal-body hidden-phone">
	<form id='fms_signin_form'>
		<div class="modal-body-title login">
			<span class="modal-title font-light">WELCOME BACK!</span>
			<label class="close-signup-modal login" data-dismiss="modal"></label>
		</div>
		
		<ul class="form-column popover-right-dark login">
			<li class="input-block input-prepend login">
				<span class="add-on">Email</span><input class="login" type="email" name="user_email_login" id="user_email_login" title="Email" tabindex="3">
			</li>			
			<li class="input-block input-prepend login">
				<span class="add-on">Password</span><input class="login" type="password" name="user_password_login" id="user_password_login" title="Password" tabindex="6">
			</li>
			<label id="help_login_wrap"><a id="help_login" rel="popover"></a></label> 				
			<ul class="fms-login-actions">				
				<li><a id="forgotlink" href="<?php echo base_url("forgot_password"); ?>">Forgot Password?</a></li>
				<button id="fms_login" class="btn btn-large btn-primary btn-block signin_submit_btn btn-embossed" type="submit" name="submit">Let's Rock!</button>
			</ul>			
		</ul>
	</form>
	<div class="fms-user-signup">Don’t have an account yet? <a id="anchor_signup" href="#">Click here</a></div>
	<ul class="fms-login-social-conn">
		<li><span class="social-media-tile facebook">
				<a id="login_facebook" href="" class="login">
					Login using Facebook
				</a>
			</span>
		</li>
		<li><span class="social-media-tile twitter">
				<a id="signup_twitter" href="" class="login">
					Login using Twitter
				</a>
			</span>
		</li>
	</ul>
	</div>
</div>

