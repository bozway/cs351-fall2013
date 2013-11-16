<form id="projectBasicForm" data-projectId="<?php echo $projectId;?>">
<div class="default-header">
	<div class="container">
		<div class="row">
			<p class="project-title span12"><?php echo $content_title?></p>
			<p class="project-description span7">Creating a project lets you
				talk, collaborate, and finish your track with other musicians! The
				more information you fill out about your profile, the more likely
				other musicians are to work with you.</p>
			<p class="project-create-navlink">
				<span>Step 1 of 2</span>
			</p>
		<button id="basic_setting_save" type="submit"
				class="btn btn-large btn-block btn-success">Save & Next</button>
		</div>
	</div>
</div>

<div class="container" id="project_create_basic" data-create="1">

	<div class="row">
		<div class="span6">
			<div class="row">
				<p class="dashboard-subtitle span6">
					<span>Project Name</span>
				</p>
				<div class="control-group large span6">
					<input class="span6" name="name" type="text"
						placeholder="e.g. My First Project"
						<?php if($project->getName()) {  echo "value='".$project->getName()."'";}?> />
				</div>
				<p class="tags-title span6">Tags</p>
				<div id="projectTags" class="span6">
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
			</div>
		</div>
		<div class="span6">
			<div class="row">
				<p class="dashboard-subtitle span6">
					<span>Photo Cover</span>
				</p>
			</div>
			<div class="row">
				<div class="span3">
					<?php echo $profile_picture_module; ?>
				</div>
				<p class="span3">Use a photo 300x300 px. A beautiful photo makes
					other musicians much more likely to click on your project, so
					choose wisely!</p>
			</div>
		</div>
		<div class="dashboard-separator span12"></div>
	</div>

	<div class="row">
		<div class="span3">
			<p class="dashboard-subtitle">
				<span>Location</span>
				<span id="showLocation" class="visibility-toggle" data-show='1'></span>
			</p>

			<div id="usstate_dropdown" class="fms_dropdown_container dropdown02" 
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

			<div class="control-group large">
				<input class="span3" name="city" type="text"
					placeholder="Enter City or Zip"
					<?php if($project->getCity()) echo "value='".$project->getCity()."'"?> />
			</div>


		</div>

		
		<div class="span3">
			<p class="dashboard-subtitle"><span>&nbsp;</span></p>
			<!-- Flat-ui dropdown
			<div id="language_dropdown" class="btn-grey">
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
				class="fms_dropdown_container dropdown02" 
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
		    
		</div>
		
		<div class="span3">
			<p class="dashboard-subtitle">
				<span>Duration</span>
				<span id="showDuration" class="visibility-toggle" data-show='1'></span>
			</p>
			<div class="btn-grey">
				<select id="estimatedDuration" name="large" class="select-block mbl span3">
					<?php if($project->getDuration() ) {?>
					<option value="0"><?php echo $project->getDuration()?></option>
					<?php } else { ?>
					<option value="0">Estimate duration</option>
					<?php }?>
					<option value="1">1 week</option>
					<option value="2">1 month</option>
					<option value="3">3 month</option>
					<option value="4">6 month</option>
					<option value="5">6+ month</option>
				</select>
			</div>
		</div>

		<div class="span3">
			<p class="dashboard-subtitle">
				<span>Start Date</span>
				<span class="visibility-toggle" data-show='1'></span>
			</p>
			<div class="control-group">
            <div class="input-prepend input-datepicker">
              <button type="button" class="btn"><span class="fui-calendar"></span></button>
              <input type="text" class="span2" value="<?= $startDate ?>" id="datepicker-01">
            </div>
          </div>
		</div>
		<div class="dashboard-separator span12" id="row2_separator"></div>
	</div>

	<div class="row">
		<div class="span6">
			<p class="dashboard-subtitle">
				<span>Audio Preview</span>
				<span id="showAudioPreview" class="visibility-toggle" data-show='1'></span>
			</p>
			<p>Upload a file to show the world what you've been working on!</p>
			<?php echo $audio_preview_module;?>
		</div>
		
		
		
		<div class="span6">
			<p class="dashboard-subtitle">
				<span>Video Preview</span>
				<span id="showVideoPreview" class="visibility-toggle" data-show='1'></span>
			</p>
			<p>You can embed a video from YouTube to give your project profile an extra kick.</p>
			<div>
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
						<input name="video_preview" type="text" class="span5" value="" />
					</div>
				</div>
			</div>
		</div>
		
		<div class="dashboard-separator span12" id="row3_separator"></div>
	</div>
	
	<div class="row">
		<div class="span12">
			<p class="dashboard-subtitle">
				<span>Description</span>
				<span id="showDescription" class="visibility-toggle" data-show='1'></span>
			</p>
			<textarea id="project-description" style="width: <?php if($edit_basic === 1){echo 764;} else{echo 948;}?>px; height: 500px;">
	            <?php if($project->getDescription()) 
					echo $project->getDescription();
	                    else echo "Enter something ...";
	            ?>
			</textarea>
		</div>
	</div>
</div>
</form>