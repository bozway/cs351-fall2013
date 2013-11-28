
<!-- CSCI 351 code -->
<div id="signup_page" class="visible-phone">


    <div id="top" class="row-fluid">
        <div class="span6 text-left">
            <a href="">Signup</a>
        </div>

        <div class="span6 text-right">
            <a href="">Cancel</a>
        </div>

    </div>


    <div id="method" class="row-fluid">
        <div id="facebook">
            <button type="button" class="btn btn-primary active login" id="login_facebook">Signup with Facebook
</button>
        </div>
        <div id="twitter">
            <button type="button" class="btn btn-info active login" id="signup_twitter">Signup with Twitter
</button>
        </div>

    </div>

    <div id="form">
        <form id="fms_signup_form">
        <div id="name">

            <div class="text-center">
                <input class="user" name="user_namefirst" id="user_namefirst" title="First Name" type="text" placeholder="First Name">
                <input class="user" name="user_namelast" id="user_namelast" type="text" title="Last Name" placeholder="Last Name">
            </div>

        </div>

        <div id="email">
            <div class="text-center">
                <input class="user" type="email" name="user_email" value="" id="user_email" placeholder="Email">
                <input class="user" type="email" name="user_email_repeat" value="" id="user_email_repeat" title="Confirm Email" placeholder="Confirm Email">
            </div>
        </div>

        <div id="password">
            <div class="text-center">
                <input class="user" type="password" value="" id="user_password" title="Password" placeholder="Password">
                <input class="user" type="password" id="user_password_repeat" name="user_password_repeat" placeholder="Confirm Password">
            </div>
        </div>

        <div id="policy">

            <label class="checkbox">

                <input id="checkbox" type="checkbox" class="fms_checkbox unchecked">
                <p>By Checking the box and clicking "Join", I understand that I am joining FindmySong, and I have read and accept the new and consent to the new <a href="<?= base_url('privacy') ?>">Privacy Policy</a></p>


            </label>

            <div id="signup_button">
                <button id="fms_join" class="btn btn-primary btn-large btn-block signup_submit_btn btn-embossed" type="submit" name="submit">Let's Rock!</button>
            </div>
        </div>
        </form>
    </div>

</div>

<!-- End of CSCI 351 code -->