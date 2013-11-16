<?php
	$form_props = array('id' => 'fms_signup_skill_form', 'autocomplete' => 'off'); 
	echo form_open('/', $form_props);						
?>		
<div id="signupSkillsModal" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="loginlabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="fms_close" data-dismiss="modal" aria-hidden="true"></button>
		<h3 id="skilllabel"></h3>
	</div>
	<div class="modal-body">
		<div class="modal-body-title">
			<span class="modal-title font-light">Welcome <span class="user_name"><?php if (isset($name)){echo ucfirst($name);}?></span></span>
			<span class="modal-signup-stage">3</span>
			<span class="modal-signup-stage active">2</span>
			<span class="modal-signup-stage">1</span>
		</div>
		<div class="modal-body-content">
			<div id="skill_column1">
				<p class="user-profile-title">What your musical skills?</p>
				<ul id="extra_skills" class="skill-select-area">
					<li><p data-id="34" data-name="General Vocals">General Vocals</p></li>
					<li><p data-id="14" data-name="Acoustic Guitar">Acoustic Guitar</p></li>
					<li><p data-id="19" data-name="Bass Drum">Bass Drum</p></li>
					<li><p data-id="30" data-name="Electric Guitar">Electric Guitar</p></li>
					<li><p data-id="20" data-name="Bass Guitar">Bass Guitar</p></li>
					<li><p data-id="47" data-name="Piano">Piano</p></li>
					<li><p data-id="56" data-name="Songwriter">Songwriter</p></li>
					<li><p data-id="39" data-name="Lyricist">Lyricist</p></li>
					<li><p data-id="50" data-name="Producer">Producer</p></li>
				</ul>
				<div class="user-profile-description">
					<div class="demo-icons">
						<span class="fui-alert"></span>
					</div>
					<div class="search-skill-db">Don't see your skill? Try searching our database!</div> 
				</div>
				<input type="text" id="skillsearchbox" name="skillsearchbox" autocomplete="off" placeholder="Search all skills..."></input>
			</div>
			<div id="skill_column2">
				<p class="user-profile-title">Your Skills</p>
				<a id="help_skill_selection" class="helper"></a>
				<ul id="skill_selected" class="skill-select-area">
				 <?php
				   if (isset($skills)){
				   		foreach ($skills as $skill){
				   			$id 	= $skill->getSkill()->getId();
							$name   = $skill->getSkill()->getName();
							echo "<li><p data-id=".'"'.$id.'"'."data-name=".'"'.$name.'"'.">$name</p><img src='/img/fms_user_portal/icon_handle.png'></li>";
				   		}
				   }
				?>	
				</ul>
			</div>
			<div id="skill_column3">
				<label id="help_skill_popover" class="popover-right-light">
					<a id="help_skill_popover_trgger"
							rel="popover"
							data-content="You must add at least 1 and up to 10 skills so other musicians can find you based on your talent. Drag to reorder your skills!"
							data-placement="right"
					></a>
				</label>
				<p id="error_message" class="error">You can select at most 10 skills</p>
				<button id="skills-submit" type="submit" class="fms-button">Continue
                <i class="fui-arrow-right pull-right" style="float:none; margin-left:10px;"></i>
                </button>
			</div>
		</div>
	</div>
</div>
</form>
