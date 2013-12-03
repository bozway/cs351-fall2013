<?php ?>
<div id="login-container" class="container hidden-phone">
	<button id="loginhere" type="button" class="btn btn-large" data-gated="1">Click here to login!</button>
</div>
<script>window.onload = function(){$('#loginhere').trigger('click');};</script>

<!-- CSCI 351 code -->
<div id="login_page" class="visible-phone">
    <div id="top" class="row-fluid">
        <div class="span6 text-left">
            <a href="">Login</a>
        </div>

        <div class="span6 text-right">
            <a href="">Cancel</a>
        </div>
    </div>

    <div id="form">
        <form id="fms_signin_form">
        <div id="userpass">
            <div class="text-center">
                <input id="user_email_login" class="login" type="email" name="user_email_login" title="Email"
                       placeholder="Email">
                <input id="user_password_login" class="login" type="password" name="user_password_login" title="Password" placeholder="Password">
            </div>
        </div>
        <div id="remember">
            <label class="checkbox">
                <input type="checkbox" id="rememberme" value="rem">
                <p> Remember <a href="http://www.cs351.gurtem.com/forgot_password">Forgot?</a></p>
            </label>

            <div id="login_button">
                <button id="fms_login" class="btn btn-primary btn-large btn-block active" type="submit" name="submit">Let's Rock!</button>
            </div>
        </div>

        </form>
    </div>

    <div id="bottom" class="row-fluid">
        <div class="span12">
            <p>Don't have an account yet? <a id="anchor_signup" href="http://www.cs351.gurtem.com/mobile_signup">Click here</a></p>
        </div>
    </div>

    <div id="method" class="row-fluid">
        <div id="facebook">
            <button type="button" class="btn btn-primary active" id="login_facebook" href class="login">Login with Facebook
            </button>
        </div>
        <div id="twitter">
            <button type="button" class="btn btn-info active" id="signup_twitter" href class="login">Login using Twitter
            </button>
        </div>
    </div>

</div>

<!-- End of CSCI 351 code -->