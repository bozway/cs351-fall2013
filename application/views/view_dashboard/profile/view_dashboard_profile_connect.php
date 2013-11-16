<form id="connect_form">
<div id="profile_connect" class="container <?php if($active_panel == Profile::CONNECT){echo 'active-panel';}?>">
	<div class="define-header">
       <div class="container">
        <p class="span10">Connect</p>
        <p class="span7"> Show off your other locations around the web by linking to your social media accounts and personal website. Get your management in on the action by listing their contact information on your profile.</p></div>
        <div class="save-btn-container span3">
        	<button id="save_connect" type="submit" class="btn btn-large btn-block btn-success btn-embossed">Save Changes</button>
      	</div>
      </div>
     <div class="connect-management-container">
        <p class="sub-title">Add Your Management</p>
        <div> <span class="firstsp span3"> 
  
          Agent: </span>
          <input type="text" class="span3" placeholder="Name" name="agent_name" value="<?php echo $an ?>">
          <input type="text" class="span3" placeholder="Email" name="agent_email" value="<?php echo $ae ?>">
          <input type="text" class="span3" placeholder="Phone" name="agent_phone" value="<?php echo $ap ?>">
        </div>
        <div> <span class=" firstsp span3">Manager:</span>
          <input type="text" class="span3" placeholder="Name" name="manager_name" value="<?php echo $mn ?>" >
          <input type="text" class="span3" placeholder="Email" name="manager_email"  value="<?php echo $me ?>">
          <input type="text" class="span3" placeholder="Phone" name="manager_phone" value="<?php echo $mp ?>">
        </div>
        <div> <span class=" firstsp span3">Booking:</span>
          <input type="text" class="span3" placeholder="Name" name="booking_name" value="<?php echo $bn ?>">
          <input type="text" class="span3" placeholder="Email" name="booking_email" value="<?php echo $be ?>">
          <input type="text" class="span3" placeholder="Phone" name="booking_phone" value="<?php echo $bp ?>">
        </div>
        <div> <span  class=" firstsp span3">Publisher:</span>
          <input type="text" class="span3" placeholder="Name" name="publisher_name" value="<?php echo $pn ?>">
          <input type="text" class="span3" placeholder="Email" name="publisher_email" value="<?php echo $pe ?>">
          <input type="text" class="span3" placeholder="Phone" name="publisher_phone" value="<?php echo $pp ?>">
        </div>
        <div> <span class=" firstsp span3">Record Label:</span>
          <input  type="text" value="<?php echo $rn ?>" class="span3" placeholder="Name" name="record_name">
          <input type="text"  value="<?php echo $rw ?>" class="span3" placeholder="Website" name="record_website">
        </div>
      </div>
    
    
      <div id="linkandmedia" class="container">
        <div class="connect-links-container firstsp span5">
          <p class="sub-title">Add Your Links</p>

          
          	<div class="control-group">
				<div class="input-prepend">
					<span class="add-on">http://</span>
					<input class="span3" type="text" value="<?php echo $pwl ?>" placeholder="Your personal page" name="website">
				</div>
				<span class="fui-link"></span>

			</div>
          
          	<div class="control-group">
				<div class="input-prepend">
					<span class="add-on">facebook.com/</span>
					<input class="span3" type="text" value="<?php echo $fbl ?>" placeholder="yourbandname" name="facebook">
				</div>
				<span><img align="middle" src="<?php echo base_url('img/f_icon.png') ?>"></span>

			</div>
			
			<div class="control-group">
				<div class="input-prepend">
					<span class="add-on">twitter.com/</span>
					<input class="span3" type="text" value="<?php echo $twl ?>" placeholder="yourbandname" name="twitter">
				</div>
				<span class="fui-twitter"></span>

			</div>
			
			<div class="control-group" id="connect-sound-cloud">
				<div class="input-prepend">
					<span class="add-on">soundcloud.com/</span>
					<input class="span3" type="text" value="<?php echo $scl ?>" placeholder="yourbandname" name="soundcloud">
				</div>
			</div>
        </div>
    
    
   
	
	<div class="connect-social-media-container">
		<p class="sub-title">Connect Your Social Media</p>
        <?php if($linkFB==0){?> 
         <a href="javascript:void(0)" class="firstsp span3" id="linkFB">
		<img src="<?php echo base_url('img/fms_user_portal/icon_facebook.png') ?>">
		Link with Facebook 
	</a> <?php }else {?>
    	  <a href="" class="firstsp span3" id="unlinkFB">
		<img src="<?php echo base_url('img/fms_user_portal/icon_facebook.png') ?>">
		Unlink with Facebook 
	</a> 
    <?php } ?>
    
    <?php if($linkTW==0){?> 
    
    <a href="" class="span3 twitter" id="linkTW">
    	<img src="<?php echo base_url('img/fms_user_portal/icon_twitter.png') ?>">
		Link with Twitter
	</a><?php  }else{?>
    	 <a href="" class="span3 twitter" id="unlinkTW">
    	<img src="<?php echo base_url('img/fms_user_portal/icon_twitter.png') ?>">
		Unlink with Twitter
	</a>
    <?php } ?>
      </div>
		
	</div>
</div>
</form>