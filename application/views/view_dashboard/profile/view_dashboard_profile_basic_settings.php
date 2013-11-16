<form id="dashboard_profile_basic_settings">

	<div class="container define-header">
		<div class="container">
			<p class="span10">Basic Settings</p>
			<p class="span7">Your basic settings establish the main
				information on your profile page. Display your name, location, and
				other relevant details. Go ahead and upload or change your profile
				picture and cover photo, too!</p>
		</div>
		<div class="save-btn-container span3">
			<button id="basic_setting_save" type="submit" class="btn btn-large btn-block btn-success btn-embossed">Save
			Changes</button>
		</div>
	</div>
	
	<div class="container">
		<div class="row">
			<div id="basic-setting-left-column" class="span3">
				<p class="user-profile-title firstsp  span3">Your Real Name</p>
				<input type="text" value="<?php echo $user->getFirstName() ?>"
					class="firstsp span3" placeholder="First Name" name="namefirst">
				<input type="text" value="<?php echo $user->getLastName() ?>"
					class="firstsp span3" placeholder="Last Name" name="namelast">
				<label class="error" generated="true" for="namefirst"></label>
				<label class="validation-err-msg" generated="true" for="namelast"></label>
			</div>
		
			<div id="basic-setting-right-column" class="span8 offset1">
				<p class="user-profile-title">Spotlight</p>
				<p class="firstsp span5">This will be your musical resume on the
					web. You can upload up to 3 files.</p>
				<div class="cf"></div>
				<?php echo $spotlight_module?>
			</div>
		</div>
	</div>
	
	<div class="clear container"></div>

	<div id="LanguageLocation " class="container">
		<div id="basic-setting-left-Language" class="firstsp span3">
			<p class="user-profile-title">Language & Location</p>
			<p class="user-profile-description">Displaying this information
				allows you to find other musicians based on your language and
				location.</p>
		</div>
				
		<div id="language_dropdown" 
			class="fms_dropdown_container dropdown02 span3" 
			data-slimscroll='1' 
			data-slimscrollsize="200"
		>
	        <p ><span id="language"><?php 
	        		$userLanguage = $user->getLanguage();
					$userLanguageId = "INVALID";
	        		if($userLanguage) {
	        			$userLanguageId = $userLanguage->getIsoCode();
						echo $userLanguage->getLanguageName();
	        		} else {
	        			echo "Language";
	        		}
	        	?></span><span class="fms_caret"></span></p>
	        <div class="fms_dropdown_menu_container">
				<div class="fms_dropdown_arrow_container">
					<div class="fms_dropdown_arrow after"></div>
					<div class="fms_dropdown_arrow before"></div>
				</div>
		        <ul  class="fms_dropdown_menu" id="language_list">
		        	<?php $iter = 0?>
		            <?php foreach($languageDropdown as $row) {?>
		            	<li <?php if($row->getIsoCode() == $userLanguageId) {echo 'selected="selected"';}?>
		  					data-search-index="<?php echo substr($row->getLanguageName(), 0, 1)?>"
		  					data-scrollto-index="<?php echo $iter++;?>"
		  				><?php echo $row->getLanguageName(); ?></li>
		            <?php } ?>
		        </ul>
		    </div>
	    </div>	
		
		<div id="usstate_dropdown" class="fms_dropdown_container dropdown02 span3" 
			data-slimscroll='1' 
			data-slimscrollsize="200"
		>
	        <p ><span id="usstate"><?php 
	        		$userState = $user->getState();
					$userStateId = "INVALID";
	        		if($userState) {
	        			$userStateId = $userState->getAbbreviatedName();
						echo $userState->getFullName();
	        		} else {
	        			echo "State";
	        		}
	        	?></span><span class="fms_caret"></span></p>
	        <div class="fms_dropdown_menu_container">
				<div class="fms_dropdown_arrow_container">
					<div class="fms_dropdown_arrow after"></div>
					<div class="fms_dropdown_arrow before"></div>
				</div>
		        <ul  class="fms_dropdown_menu" id="state_list">
		        	<?php $iter = 0; ?>
		            <?php foreach($stateList as $row) {?>
		            	<li <?php if($row->getAbbreviatedName() == $userLanguageId) {echo 'selected="selected"';}?>
		  					data-search-index="<?php echo substr($row->getFullName(), 0, 1)?>"
		  					data-scrollto-index="<?php echo $iter++;?>"
		  				><?php echo $row->getFullName(); ?></li>
		            <?php } ?>
		        </ul>
		    </div>
	    </div>	
		
		<div class="span3">
			<input name="city" type="text" class="searchcity span3"
				placeholder="City or Zip code"
				value="<?php 
					$city = $user->getCity();
					if($city) echo $city;
					?>" />
			<label class="validation-err-msg" for="namelast"></label>
		</div>

	</div>


	<div class="clear container"></div>
	<!-- presently unsupported
	<div id="WebAddress" class="container">
		<div id="basic-setting-left-WebAddress" class="firstsp span3">
			<p class="user-profile-title">Web Address</p>
			<p class="user-profile-description">You can personalize your URL
				with a permanent link.</p>
		</div>
		<p class="span8 offset1">
			http://www.findmysong.com/ <input name="webaddr"
				id="your_web_address" placeholder="Web Address"
				value="<?php echo $user->getWebAddress(); ?>" class="yourname span3"
				type="text" <?php if($user->getWebAddress() !== null){ echo "readonly"; }?> />
			<span id="webaddrresult"></span>
		</p>

	</div>
	
	<div class="clear container"></div>
	-->
	<div id="basic_setting_photo" class="container">
		<p class="user-profile-title">Cover Photo & Profile Picture</p>
		<p class="user-profile-description">These photos help your profile
			look beautiful!</p>
		<?php echo $profile_picture_module ?>
		<?php echo $cover_photo_module ?>
		<div style="clear: both"></div>
	</div>
	<div style="clear: both"></div>
</form>