<form id="projectBasicForm" data-projectId="<?php echo $projectId;?>" data-create='0'>


<div class="default-header">
	<div class="container">
		<div class="row">
			<p class="project-title span12">Edit <span><?php echo $content_title?></span></p>
			<p class="project-description span7">Creating a project lets you
				talk, collaborate, and finish your track with other musicians! The
				more information you fill out about your profile, the more likely
				other musicians are to work with you.</p>
		<?php if(isset($create_flag)) {?>
			<p class="project-create-navlink">
				<span>Step 1 of 2</span>
			</p>
		<?php }?>
		<button id="basic_setting_save" type="submit"
				class="btn btn-large btn-block btn-success"><?php if(isset($create_flag)) echo "Save & Next"; else echo "Save Changes"?></button>
		</div>
	</div>
</div>
	
<div class="container" id="project_edit_basic" data-create="1">
	<div class="row">
		<?=$vertical_nav?>
		<div class="span10">
			<div class="row">
				<p class="dashboard-subtitle span10">
					<span>Project Name</span>
				</p>
				<div class="control-group large span10">
					<input class="span10" name="name" type="text"
						placeholder="e.g. My First Project"
						<?php if($project->getName()) echo "value='".$project->getName()."'";?> />
				</div>
				
				<p class="dashboard-subtitle span10">Tags</p>
				<div id="projectTags" class="span10">
					<input name="tagsinput" class="tagsinput" value="<?php 
						$tags = $project->getTags();
						$iter = 0;
						if($tags) {
							foreach($tags as $row) {
								echo $row;
								$iter++;
								if($iter != count($tags)) echo ',';
							}
						}
					?>" />
				</div>
				
				<div class="dashboard-separator span10"></div>
			</div>
			
			<div class="row">
				<p class="dashboard-subtitle span10">
					<span>Location</span>
					<span id="showLocation" 
					<?php $showFlag = $project->getShowCountry();
					if($showFlag == 0) {?>
						class="visibility-toggle not-show" 
						data-show="0"
					<?php } else {?>
						class="visibility-toggle"
						data-show="1"
					<?php }?>
					></span>
				</p>
				
				<div id="usstate_dropdown" class="fms_dropdown_container dropdown02 span3" 
					data-slimscroll='1' 
					data-slimscrollsize="200"
				>
			        <p ><span id="usstate"><?php 
			        		$projectState = $project->getState();
							$projectStateId = "INVALID";
			        		if($projectState) {
			        			$projectStateId = $projectState->getAbbreviatedName();
								echo $projectState->getFullName();
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
				        	<?php $iter = 0 ?>
				            <?php foreach($stateList as $row) {?>
				            	<li <?php if($row->getAbbreviatedName() == $projectStateId) {echo 'selected="selected"';}?>
				  					data-search-index="<?php echo substr($row->getFullName(), 0, 1)?>"
		  							data-scrollto-index="<?php echo $iter++;?>"
				  				><?php echo $row->getFullName(); ?></li>
				            <?php } ?>
				        </ul>
				    </div>
			    </div>	
				
				<!-- Flat-ui
				<div id="language_dropdown" class="btn-grey span3">
					<select id="language" name="large" class="select-block mbl">
						<option value="0">Language</option>
						<?php 
			        		$projectLanguage = $project->getLanguage();
							$projectLanguageId = "INVALID";
			        		if($projectLanguage) {
			        			$projectLanguageId = $projectLanguage->getIsoCode();
			        			echo $projectLanguage->getLanguageName();
			        		} else {
			        			echo "";
			        		}
			        		$iter = 1;
			        		foreach($languageList as $row) {?>
			            	<option value="<?php $iter++?>"
			            		<?php if($row->getIsoCode() == $projectLanguageId) {echo 'selected="selected"';}?>
			  				><?php echo $row->getLanguageName(); ?></option>
			            <?php } ?>
					</select>
				</div>
				-->
				<div id="language_dropdown" 
					class="fms_dropdown_container dropdown02 span3" 
					data-slimscroll='1' 
					data-slimscrollsize="200"
				>
			        <p ><span id="language"><?php 
			        		$projectLanguage = $project->getLanguage();
							$projectLanguageId = "INVALID";
			        		if($projectLanguage) {
			        			$projectLanguageId = $projectLanguage->getIsoCode();
			        			echo $projectLanguage->getLanguageName();
			        		} else {
			        			echo "Language";
			        		}
			        	?></span> <span class="fms_caret"></span></p>
			        <div class="fms_dropdown_menu_container">
						<div class="fms_dropdown_arrow_container">
							<div class="fms_dropdown_arrow after"></div>
							<div class="fms_dropdown_arrow before"></div>
						</div>
				        <ul  class="fms_dropdown_menu" id="language_list">
				        	<?php $iter = 0 ?>
				            <?php foreach($languageList as $row) {?>
				            	<li <?php if($row->getIsoCode() == $projectLanguageId) {echo 'selected="selected"';}?>
				  					data-search-index="<?php echo substr($row->getLanguageName(), 0, 1)?>"
		  							data-scrollto-index="<?php echo $iter++;?>"
				  				><?php echo $row->getLanguageName(); ?></li>
				            <?php } ?>
				        </ul>
				    </div>
			    </div>
				
				<div class="control-group large span3">
					<input class="span3" name="city" type="text"
						placeholder="Enter City or Zip"
						<?php if($project->getCity()) echo "value='".$project->getCity()."'"?> />
				</div>
				
				<div class="dashboard-separator span10"></div>
			</div>
			
			<div class="row">
				<div class="span10">
					<p class="dashboard-subtitle">
						<span>Audio Preview</span>
						<span id="showAudioPreview" 
						<?php $showFlag = $project->getShowAudioPreview();
						if($showFlag == 0) {?>
							class="visibility-toggle not-show" 
							data-show="0"
						<?php } else {?>
							class="visibility-toggle"
							data-show="1"
						<?php }?>
						></span>
					</p>
					<?php echo $audio_preview_module;?>
				</div>
				
				<div class="dashboard-separator span10"></div>
			</div>
			
			<div class="row">
				<div class="span4">
					<p class="dashboard-subtitle">
						<span>Project Photo</span>
						<span class="visibility-toggle"></span>
					</p>
					<p>Use a photo 300x300 px.  Choose a nice photo.</p>
					<?php echo $profile_picture_module; ?>
				</div>
				
				<div class="span6">
					<p class="dashboard-subtitle">
						<span>Video Preview</span>
						<span id="showVideoPreview" 
						<?php $showFlag = $project->getShowVideoPreview();
						if($showFlag == 0) {?>
							class="visibility-toggle not-show" 
							data-show="0"
						<?php } else {?>
							class="visibility-toggle"
							data-show="1"
						<?php }?>
						></span>
					</p>
					<p>You can embed a video from YouTube to give your project profile an extra kick.</p>
					<div class="video-preview">
						<?php if($project->getVideoPreview()) {?>
							<iframe title="YouTube video player" width="460" height="220"
							src="<?php echo $project->getVideoPreview()?>" frameborder="0"
							allowfullscreen=""></iframe>
						<?php } else {?>
							<span class="fui-video"></span>
						<?php }?>
					</div>
					
					<div class="row">
						<p class="video-url span1">URL</p>
						<div class="control-group small span5">
							<input name="videoPreview" type="text" class="span5" value="<?php echo $project->getVideoPreview()?>" />
						</div>
					</div>
				</div>
				
				<div class="dashboard-separator span10"></div>
			</div>
			
			<div class="row">
				<div id="description" class="span10">
					<p class="dashboard-subtitle">
						<span>Description</span>
						<span id="showDescription" 
						<?php $showFlag = $project->getShowDescription();
						if($showFlag == 0) {?>
							class="visibility-toggle not-show" 
							data-show="0"
						<?php } else {?>
							class="visibility-toggle"
							data-show="1"
						<?php }?>
						></span>
					</p>
					<textarea id="project-description" style="width: <?php if($edit_basic === 1){echo 764;} else{echo 948;}?>px; height: 500px;"><?php if($project->getDescription()) echo $project->getDescription();else echo "Enter something ...";?></textarea>
				</div>
			</div>

		</div>
	</div>
</div>