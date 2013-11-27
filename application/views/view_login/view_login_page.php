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
        <div id="userpass">
            <div class="text-center">
                <input id="user_email_login" class="user" type="text" placeholder="Username">
                <input id="user_password_login" class="user" type="password" placeholder="Password">
            </div>
        </div>
        <div id="remember">
            <label class="checkbox">
                <input type="checkbox" id="rememberme" value="rem">
                <p> Remember <a href="http://www.cs351.gurtem.com/forgot_password">Forgot?</a></p>
            </label>

            <div id="login_button">
                <button class="btn btn-primary btn-large btn-block active" type="submit">Let's Rock!</button>
            </div>
        </div>
    </div>

    <div id="bottom" class="row-fluid">
        <div class="span12">
            <p>Don't have an account yet? <a id="anchor_signup" href="#">Click here</a></p>
        </div>
    </div>

    <div id="method" class="row-fluid">
        <div id="facebook">
            <button type="button" class="btn btn-primary active">Login with Facebook
            </button>
        </div>
        <div id="twitter">
            <button type="button" class="btn btn-info active">Login using Twitter
            </button>
        </div>
    </div>

</div>

<!-- End of CSCI 351 code -->