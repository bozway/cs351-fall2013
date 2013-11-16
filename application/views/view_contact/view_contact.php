<div id="contact_container">
	<div class="contact_header">
    	<p>Get in touch</p>
    </div>
    <div class="contact_content">
    	<div class="row">
        
        	<div class="span6">
            	<div class="row">
                	<div class="span5">
                    	<div class="content_img"><i class="fui-heart"></i></div>
                        <p class="first-p">Thanks for stopping by! We love getting mail, so we read every message and respond personally.</p>
                        <div class="or">OR</div>
                        <div class="contact_way">
                        	<p>Just want to talk? Drop a line!<br /><a href="">info@findmysong.com</a></p>
                            <p>2222 S. Figueroa St. #215<br />Los Angeles, Ca 90007</p>
                        </div>
                    </div>
                    <div class="span1">
                    	<div class="middle_border"></div>
                    </div>
                </div>
            </div>
            <div class="span6">
            	<div class="row">
                	<div class="span1"></div>
                	<div class="span5">
                    	<div class="content_img"><i class="fui-mail"></i></div>
	                        <form id="contact-fms">                        
	                        <?php if(!$userid){?>
	                            <div class="control-group small">
	                                <input name="customer_name" class="span5" type="text" placeholder="Name" value="" />
	                            </div>
	                        <?php }?>	                        
	                        <div id="subject-selector-wrapper">
	                        	<div class="btn-grey">
	                               <select id="customer_subject" name="large" class="select-block mbl span3" >
	                                      <option value="What are you inquiring about?">What are you inquiring about?</option>
	                                      <option value="Feature Requests">Feature Requests</option>
	                                      <option value="Report a Problem/Bug">Report a Problem/Bug</option>
	                                      <option value="Customer Support">Customer Support</option>
	                                      <option value="General Comments">General Comments</option>
	                               </select>
	                         	</div>
	                        </div>
	                        <div>
	                        	<textarea id="customer_message" name="customer_message" class="span5" placeholder="Message" rows="6"></textarea>
	                        </div>
	                        <div class="control-group small">
	                        	<?php if(!$userid){?>
	                        	<input class="span3" type="text" placeholder="Email address" value="" name="customer_email"/>
	                            <?php } ?>
	                            <button id="beam-me-up-scotty" type="submit" class="btn btn-mini btn-block btn-primary btn-embossed contact_btn">Send!</button>
	                        </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>