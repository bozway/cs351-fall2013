<div class="default-header">
	<div class="container">
		<div class="row">
			<p class="project-title span12">General Settings</p>
			<p class="project-description span7">Your general settings contain the most basic information about your account</p>
		</div>
    </div>
</div>

<div class="container">
	<div class="account_container row">
        <div class="email-setting span4 pull-left">
        	<form id="email_reset_form">
	            <p>Update Email Address</p>
	            <input type="text" id="new_email" name="new_email" placeholder="New email">
	            <input type="text" id="confirm_new_email" name="confirm_new_email" placeholder="New email Confirm">
	            <input type="password" id="password" name="password" placeholder="Enter Your Password">
	            Original Email: <label id="original_email">
	                <?= $user_email; ?>
	            </label>
	            <div class="dashboard-account-save-btn">
	            	<a id="save_email_btn" class="btn btn-large btn-block">Save Changes</a>
	            </div>
        	</form>
        </div>
	     
        <div class="password-setting span4 pull-right">
        	<form id="password_reset_form">
	            <p>Update Password</p>
	            <input type="password" id="new_password" name="new_password" placeholder="New Password">
	            <input type="password" id="confirm_new_password" name="confirm_new_password" placeholder="New Password Confirm">
	            <input type="password" id="confirm_password" name="confirm_password" placeholder="Enter Your Current Password">
	            <div class="dashboard-account-save-btn">
	            	<a id="save_psw_btn" class="btn btn-large btn-block">Save Changes</a>
	            </div>
        	</form>
        </div>
	    
	    <div class="deactivate-container">
	        <p>Deactivate Account</p>
	        <p>If you'd like to deactivate your account, use the button below.  If you'd like to delete your account, please contact Customer 
	            Service and we'll take care of it right away!</p>
	        <button class="btn btn-mini btn-block btn-primary" id="deactive">Deactivate</button>
	    </div>
	</div>
</div>