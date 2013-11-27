<div id="modal_container" data-authservice="<?php echo (isset($protectedPage)) ? $protectedPage : 0; ?>"></div>
<div id="site_alert_modal_container">
	<div id="inviteModal-container">				
		<div id="inviteModal" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
			<div class="modal-header">Send Invitation
				<button type="button" class="fms_close" data-dismiss="modal" aria-hidden="true"><i class="fui-cross"></i></button>				
			</div>
			<div class="modal-body">
				<div id ="sideTitles" >
					<p id="p1" class="subTitle">Musician</p>
					<p id="p2" class="subTitle">Project</p>
					<p id="p3" class="subTitle">Message</p>
				</div>	
				<div id = "main">
					<div id = "musician">	
						<img src="" id="profilePicture"/>				
						<span id = "musicianName"></span>
					</div>
					<div id = "project">
						<div class="btn-grey btn-embossed">
							<select name="large" class="select-block mbl span3" id="project_invitation">
			
							</select>
						</div>
						<p>Don't have a project yet? <a href="<?php echo site_url('/dashboard/project/manage');?>">Create one</a> to start sending invitations!</p>
					</div>
					<div id="message">
						<div>
							<textarea class="span6" placeholder="Write a message..."></textarea>
						</div>		
					</div>
					<button id="sendInvitation" class="btn btn-mini btn-block btn-primary">Send</button>
				</div>
			</div>
	  </div>								
	</div>
	
	<div id="auditionModal-container">				
		<div id="auditionModal" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
			<div class="modal-header"> Audition for <span>Project</span>
				<button type="button" class="fms_close" data-dismiss="modal" aria-hidden="true"><i class="fui-cross"></i></button>				
			</div>
			<div class="modal-body">
				<p class > Choose a Skill to Audition</p>
				<p> To audition for a project, you have to select a specific skill to audition for.<br />Choose a skill from the dropdown and select "Audition" to get started! </p>
                <div id="audition_skill_selection" class="btn-grey">
                    <select name="info" id="skill_audition" class="select-block mbl span3">
    
                    </select>
                </div>
				<div class="clear"></div>
				<button id="sendAudition" class="btn btn-block btn-primary">Audition</button>
			</div>
		</div>								
	</div>
	
	<div id="messageModal-container">				
		<div id="messageModal" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
			<div class="modal-header"> Send Message
				<button type="button" class="fms_close" data-dismiss="modal" aria-hidden="true"><i class="fui-cross"></i></button>				
			</div>
			<div class="modal-body">
				<div id ="sideTitles" >
					<p id="p1" class="subTitle">Musician</p>
					<p id="p3" class="subTitle">Message</p>
				</div>	
			<div id = "main">
				<div id = "musician">	
					<img id="profilePicture" src="<?php echo base_url("/img/default_avatar_photo.jpg"); ?>"/>				
                    <span id = "musicianName"></span>
				</div>
				<div id="message">
					<div>
						<textarea class="span6" placeholder="Write a message..."></textarea>
					</div>				
				</div>
				<button id="sendMessage" class="btn btn-mini btn-block btn-primary">Send</button>
			</div>
		</div>								
	</div>
  </div>	
	
</div>
<?php $enableSlimFooter = (isset($enableSlimFooter)) ? $enableSlimFooter : FALSE; ?>
<footer class="hidden-phone" <?php if ($enableSlimFooter) { echo 'class="slim"'; }?>>
	<div class="colorbg grey footer"></div>
		<div class="fms-info-footer">
			<div class="fms-info-footer-container">
            	<ul class="fms-info-list">
            		<li>
	            		<ul>
		            		<?php if (!$authenticated): ?>
		                	<li class="prompt-signup">
		                    	<p class="title">Sign up.</p>
		                        <p>(Don't have an account yet?<br/>Sign up to get started!)</p>
		                        <p><button data-gated="1" class="btn btn-small btn-block btn-success">Sign up!</button></p>
		                    </li>
		                    <?php endif; ?>
		                    <li class="social-media">
		                		<p class="title">Follow us</p>
		                    	<p class="social-media-options">
		                        	<a href="http://www.facebook.com/FindMySong"><?php echo img('img/footer-facebook.png')?></a>
		                            <a href="http://twitter.com/FindMySong"><?php echo img('img/footer-twitter.png')?></a>
		                       	</p>
		                    </li>
	                    </ul>
	                </li>
                    <li>
                    	<p class="title">FMS</p>
                        <p><a href="<?php echo base_url().'about'?>">About</a></p>
                        <p><a href="<?php echo base_url().'contact'?>">Contact</a></p>
                        <p><a href="<?php echo base_url().'help'?>">Help Center</a></p>
                    </li>
                    <li>
                    	<p class="title">Legal</p>
                        <p><a href="<?php echo base_url().'terms'?>">Terms</a></p>
                        <p><a href="<?php echo base_url().'privacy'?>">Privacy</a></p>
                    </li>                  	
                </ul>
                <div>
                	<p class="title">Last from the Blog</p>
                    <p>
                    	<a href="">
                        	<img src="<?php echo base_url() ?>/img/home_page/blog.png"  />
                            This Scandinavian pop trio and romantic single with their ...
                        </a>
                    </p>
                    <p>
                    	<a href="">
                        	<img src="<?php echo base_url() ?>/img/home_page/blog.png"  />
                            This Scandinavian pop trio and romantic single with their ...
                        </a>
                    </p>
                    <p>
                    	<a href="">
                        	<img src="<?php echo base_url() ?>/img/home_page/blog.png"  />
                            This Scandinavian pop trio and romantic single with their ...
                        </a>
                    </p>
                </div>
            </div>
        </div>	
	<div class="container hidden-phone">
	  <ul id="slimlinks">
	    <li>Copyright &copy <?php echo date('Y'); ?> FindMySong</li>
	    <?php if ($enableSlimFooter) { ?>
	    <li><a href="<?php echo base_url("about"); ?>">About</a></li>
	    <li><a href="<?php echo base_url("contact"); ?>">Contact</a></li>
	    <li><a href="<?php echo base_url("help"); ?>">Help Center</a></li>
	    <?php } ?>	    
	  </ul>	  
	</div>
</footer>

<script src="<?php echo base_url();?>js/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/fms_auth.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/facebook.js" type="text/javascript"></script>

<!--  Scripts added for FLAT UI -->
<script src="<?php echo base_url();?>js/bootstrap-select.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/bootstrap-switch.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/flatui-radio.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/jquery.tagsinput.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/jquery.placeholder.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/jquery.stacktable.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/application.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/application_bootstrap.js" type="text/javascript"></script>


<script type="text/javascript">
	setTimeout(function(){var a=document.createElement("script");
	var b=document.getElementsByTagName("script")[0];
	a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0016/9661.js?"+Math.floor(new Date().getTime()/3600000);
	a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
</script>
<script type="text/javascript">
	(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"===e.location.protocol?"https:":"http:")+'//cdn.mxpnl.com/libs/mixpanel-2.2.min.js';f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f);b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==
	typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");for(g=0;g<i.length;g++)f(c,i[g]);
	b._i.push([a,e,d])};b.__SV=1.2}})(document,window.mixpanel||[]);
	<?php
		$host = $_SERVER['HTTP_HOST']; 
		if ($host === 'www.findmysong.dev' 
				|| $host === 'www.findmysong.info'
				|| $host === 'www.findmysong.mobi') {
			// development Mixpanel project ID
			$mixpanelID = '6d390b5176f6b7146580bcea7e15ec78';
		} else {
			$mixpanelID = 'a00113da8970d4306e46a049d80cfc9d';
		}
	?>
	mixpanel.init("<?php echo $mixpanelID; ?>");
</script>
<script src="<?php echo base_url();?>js/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/jquery-queryParser.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/fms_main.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/popups/fms_modal.js" type="text/javascript"></script>
<?php if (isset($extrascripts)) {
	foreach ($extrascripts as $script) {
		echo '<script src="'.base_url().$script.'" type="text/javascript"></script>'."\r\n";
	}	
} ?>

<?php if(isset($current_page)){?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('[data-id="<?php echo $current_page ?>"]').parent().toggleClass("active-nav-link");
		});
	</script> 
<?php } ?>

<?php if(isset($current_nav)){?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('[data-id="<?php echo $current_nav ?>"]').parent().toggleClass("active"); 
		});
	</script> 
<?php } ?>

<?php if(isset($freeze_header)){?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.fms-header').toggleClass("freeze"); 
			$('.freeze-space').toggleClass("freeze"); 
		});
	</script> 
<?php } ?>

<script src="https://www.youtube.com/iframe_api" type="text/javascript"></script>
<script src="<?= base_url('js/dashboard/jquery.poly.js') ?>" type="text/javascript"></script>

</body>
</html>