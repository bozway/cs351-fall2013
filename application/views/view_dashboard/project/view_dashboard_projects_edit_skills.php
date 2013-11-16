<div class="default-header">
	<div class="container">
		<div class="row">
			<p class="project-title span12">Edit Your Skills</p>
			<p class="project-description span6">In your basic settings, 
			you can set your main project elements to make your project profile work!</p>
			
			<div class="save-btn-wrap">
				<a id="editProjectSkill" class="btn btn-large btn-block btn-success">Save Change</a>
			</div>
		</div>
	</div>
</div>


<div class="container">
<div class="row edit" id="project-skills" data-projectid="<?= $projectId ?>">
	<?= $vertical_nav ?>
	<div class="span10">
		<p class="dashboard-subtitle">1. Select Skills</p>
		<div id="skill-basket-toggle-container" class="cf">
			<div id="skillSpecifier-wrapper">
				<form class="form-search">
					<div class="input-append">
						<input type="text" name="skillSpecifier" id="skillSpecifier" autocomplete="off" class="span4 search-query search-query-rounded" placeholder="Search all skills...">
						<button type="submit" class="btn"><span class="fui-search"></span></button>
		        	</div>
				</form>
			</div>
			<div id="skill-basket-toggle-inner-container">
				<label id="owner-skills" class="skill-basket-toggle active"
					data-mode="1">Add to Me</label> <label id="team-skills"
					class="skill-basket-toggle" data-mode="2">Add to My Team</label>
			</div>
		</div>
		<div id="skill-sidebar">
			<div class="skill-tile-wrapper">
				<?php foreach ($skilldata as $category): ?>
				
				<label class="skill-tile" data-type="skillcat" data-categoryid="<?= $category['category_id'] ?>"> 
					<img src="<?= base_url($category['iconPath']); ?>"> 
					<i class="skill-icon-faded"	data-categoryid="<?= $category['category_id']?>"> </i>
				</label>
				<?php endforeach; ?>
				<div class="cf"></div>
			</div>
			<?php foreach ($skilldata as $category): ?>
			<div id="<?= $category['category_id'] ?>" class="skill-list-wrapper">
				<div class="selected-skill-header">
					<i class="skill-icon" data-categoryid="<?php echo $category['category_id'];?>"></i>
					<span class="skill-name"><?php echo $category['category_name']?></span>
				</div>
				<ul>
					<?php foreach ($category['skills'] as $skill): ?>
					<li class="skill-list-item" data-type="skilltile"
						data-skillid="<?= $skill['skillid'] ?>"
						data-name="<?= $skill['skill_name'] ?>">
						<label class="radio">
							<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" data-toggle="radio">
							<?= $skill['skill_name'] ?>
						</label>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endforeach; ?>
		</div>
		
		
	
	
	
	
		<p class="dashboard-subtitle">2. Describe Skills</p>
		
		


		<div id="project-skills" data-projectid="<?= $projectId ?>">
			<div id="selected-skill-container" class="">
				<div class="selected-skill-list-wrapper">
					<div class="skill-owner">
						<div class="myskill-title">My Skills</div>
						<span id="alert_myskills" class="fui-alert"></span>
						<label id="help_myskills_wrap">
							<a id="help_myskills"
								rel="popover"
								data-content="You should choose at least one skill for yourself."
								data-placement="top"
							></a>
						</label>
						
						<div class="myskill-select-wrap">
							<div class="btn-group" id="savedskills">
								<i class="dropdown-arrow dropdown-arrow-inverse"></i>
								<button id="saved-skills-toggler" class="btn btn-small btn-embossed">My Saved Skills</button>
								<button id="saved-skills-toggle-btn" class="btn btn-small dropdown-toggle btn-embossed" data-toggle="dropdown">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-inverse">
									<?php foreach($savedskills as $aSkill): ?>
									<li>
										<a href="#" data-skillid="<?= $aSkill['id'] ?>" data-name="<?= $aSkill['name'] ?>" 
											data-type="skilltile" data-internal="1" 
											data-storedgenres="<?php echo json_encode($aSkill['genres']); ?>"
											data-storedinfluences='<?php echo json_encode($aSkill['influences']); ?>'>
											<img class="saved-skill-tile" src="<?php echo base_url($aSkill['iconPath']); ?>"> </img><?= $aSkill['name'] ?>
										</a>
									</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					</div>
					<p class="search-skill-hint">What are you doing on this project?</p>
					
					<ul id="selected-skill-list-owner">
						<div class="cf"></div>
					</ul>
				</div>
				<div class="selected-skill-list-wrapper">
					<div class="skill-owner">
						<div class="myskill-title">Team Skills</div>
						<span id="alert_teamskills" class="fui-alert"></span>
						<label id="help_teamskills_wrap">
							<a id="help_teamskills"
								rel="popover"
								data-content="You should choose at least one skill for your team."
								data-placement="top"
							></a>
						</label>
						
					</div>
					<p class="search-skill-hint">What skills do you need on this project?</p>
					<ul id="selected-skill-list-team">
						<div class="cf"></div>
					</ul>
				</div>
			</div>
	
			<div class="cf"></div>
			<div id="inputContainer">	
				<div id="skillGenreSpecifier-container" class="tag-input" data-row="#-1">
					<form class="form-search">
						<div class="input-append">
							<input tabindex="-1" type="text" name="skillGenreSpecifier" id="skillGenreSpecifier" 
								autocomplete="off" placeholder="Genres: e.g. Jazz"
								class="span4 search-query search-query-rounded"></input>
							<button type="submit" class="btn"><span class="fui-search"></span></button>
						</div>
					</form>
				</div>
				<div id="skillInfluenceSpecifier-container" class="tag-input" data-row="#-1">
					<form class="form-search">
						<div class="input-append">
							<input tabindex="-1" type="text" name="skillInfluenceSpecifier" id="skillInfluenceSpecifier" 
								autocomplete="off" placeholder="Influences: e.g. Frank Sinatra"
								class="span4 search-query search-query-rounded"></input>								
							<button type="submit" class="btn"><span class="fui-search"></span></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
