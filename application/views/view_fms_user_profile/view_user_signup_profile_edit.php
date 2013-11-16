<?php
	$form_props = array('id' => 'fms_profile_form', 'autocomplete' => 'off'); 
	echo form_open('/', $form_props);						
?>		
<div id="profileModal" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="loginlabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="fms_close" data-dismiss="modal" aria-hidden="true"></button>
		<h3 id="loginlabel"></h3>
	</div>
	<div class="modal-body popover-top-light">
		<div class="modal-body-title">
			<span class="modal-title font-light">Welcome <span class="user_name"><?php if (isset($name)){echo $name;}?></span></span>
			<span class="modal-signup-stage active">3</span>
			<span class="modal-signup-stage">2</span>
			<span class="modal-signup-stage">1</span>
		</div>
		<div class="modal-body-content">
			<div id="column1">
				<p class="user-profile-title">Create your profile</p>
				<?php echo $imageModule?>
				
				<!--  
				<p id="country" class="fms-select-container">
					<select id="country" name="country">
						<option>United States</option>
						<option>China</option>
						<option>India</option>
					</select>
				</p>
				-->
				<!--<input id="country" type="text" name="country" placeholder="Country" style="height:40px;width:160px;" autocomplete="off"/>
				<input id="city" type="text" name="city" placeholder="City or Zip Code" />-->
			</div>
			<!--
			<div id="column2">
				<p class="user-profile-title">
					Privacy
					<a id="help_privacy_button" class="helper float"></a>
					<label id="help_privacy_wrap">
						<a id="help_privacy"
							rel="popover"
							data-content="Anyone is allowed to view your profile on Public. Only followers are allowed to see your profile on Private."
							data-placement="top"
						></a>
					</label>
				</p>
				<ul class="privacy">
					<li><p id="public_profile" class="radio checked"></p><p>Public profile</p></li>
					<li><p id="private_profile" class="radio"></p><p>Private profile</p></li>
				</ul>
				
				<p class="user-profile-title">Date of Birth</p>
				<ul class="date-of-birth">
					<li><input id="dob_month" type="text" name="month" placeholder="mm" /></li>
					<li><input id="dob_day" type="text" name="day" placeholder="dd" /></li>
					<li><input id="dob_year" type="text" name="year" placeholder="yyyy" /></li>
				</ul>-->
				
				<!--<div class="fms-select-container">
					<p id="gender">Male</p>
					<ul id="gender_options" class="fms-select-options">
						<li>Male</li>
						<li>Female</li>
						<li>Unspecified</li>
					</ul>
				</div>
			</div>-->
			<div id="column3">
				<p class="user-profile-title">
					Upload your Spotlight
					<a id="help_upload_button" class="helper float"></a>
					<label id="help_upload_wrap">
						<a id="help_upload"
							rel="popover"
							data-content="Upload your music, the more content, the more likely you are to be contacted by musicians."
							data-placement="top"
						></a>
					</label>
				</p>
				<?php echo $audioModule;?>
					
				</p>
			</div>
            
            <div class="user_info" style=" clear:both;height: 120px;">
            	<p class="user-profile-title">
					Where in the world are you?
                    </p>
            
          
        <div id="usstate_dropdown" class="fms_dropdown_container dropdown02" 
			data-slimscroll='1' 
			data-slimscrollsize="200"
		>
	        <p ><span id="usstate">State</span><span class="fms_caret"></span></p>
	        <div class="fms_dropdown_menu_container">
				<div class="fms_dropdown_arrow_container">
					<div class="fms_dropdown_arrow after"></div>
					<div class="fms_dropdown_arrow before"></div>
				</div>
		        <ul  class="fms_dropdown_menu" id="language_list">
		        	<?php $iter = 0 ?>
		            <?php foreach($stateList as $row) {?>
		            	<li
		            		data-search-index="<?php echo substr($row->getFullName(), 0, 1)?>"
		  					data-scrollto-index="<?php echo $iter++;?>"
		            	><?php echo $row->getFullName(); ?></li>
		            <?php } ?>
		        </ul>
		    </div>
	    </div>	

            <input id="city" type="text" name="city" placeholder="City or Zip Code" />
            
            
         <div id="gender_dropdown" class="fms_dropdown_container dropdown02" 
			data-slimscroll='1' 
			data-slimscrollsize="120"
		>
	        <p ><span id="gender">Gender</span><span class="fms_caret"></span></p>
	        <div class="fms_dropdown_menu_container">
				<div class="fms_dropdown_arrow_container">
					<div class="fms_dropdown_arrow after"></div>
					<div class="fms_dropdown_arrow before"></div>
				</div>
		        <ul  class="fms_dropdown_menu" id="language_list">
		            <li>Male</li>
		            <li>Female</li>
		            <li>Unspecified</li>
		        </ul>
		    </div>
	    </div>	
            
            
            
            </div>
            
            
            
			<div id="submit-area">
				<button id="back" class="fms-button inactive" type="button">Back</button>
				<!--<p id="skip">Skip step</p>--> <a href="#fakelink" id="skip" class="btn btn-mini btn-block btn-primary">Skip step</a>
				<button id="submit" class="fms-button" type="submit">I'm finished</button>
			</div>
            
            
            
            
		</div>
	</div>
</div>
</form>
