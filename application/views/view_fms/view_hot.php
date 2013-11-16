<div id="whatshot">
	<div class="default-header" id="whatshot-header">
		<div class="container">Welcome back, <?php echo $userfirstname; ?>!</div>
	</div>
	<div id="whatshot">
		<!-- Quick Links -->
		<div id="whatshot-content-quicklinks" class="whatshot-row">
			<div class="triangle"></div>
			<div id="quicklinks-colorbg"></div>
			<div id="quicklinks-content" class="container">
				<span class="whatshot-content-title">Quick Links</span>
				<ul id="quicklinks-list">
					<li	class="quicklinks-list-item">
						<a href="<?php echo base_url("dashboard/profile/basicsettings");?>" class="quicklinks-link">
							<?php echo img('img/home_page/icon_complete_profile.png')?>
							<span class="quicklinks-link-title">Complete Your Profile</span>
						</a>
					</li>					
					<li	class="quicklinks-list-item">
						<a href="<?php echo base_url("dashboard/project/create_basic");?>" class="quicklinks-link">
							<?php echo img('img/home_page/icon_create_project.png')?>
							<span class="quicklinks-link-title">Create a Project</span>
						</a>
					</li>					
					<li	class="quicklinks-list-item">
						<a href="<?php echo base_url("dashboard/project/manage");?>" class="quicklinks-link">
							<?php echo img('img/home_page/icon_manage_project.png')?>
							<span class="quicklinks-link-title">Manage Your Projects</span>
						</a>
					</li>					
					<li	class="quicklinks-list-item">
						<a href="<?php echo base_url("users/search");?>" class="quicklinks-link">
							<?php echo img('img/home_page/icon_discover_people.png')?>
							<span class="quicklinks-link-title">Discover People</span>
						</a>
					</li>
					<li	class="quicklinks-list-item">
						<a href="<?php echo base_url("projects/search");?>" class="quicklinks-link">
							<?php echo img('img/home_page/icon_discover_projects.png')?>
							<span class="quicklinks-link-title">Discover Projects</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<!-- Popular People -->
		<div id="whatshot-content-pop-people" class="whatshot-row">
			<div id="pop-people-colorbg"></div>
			<div id="pop-people-content" class="container">
				<span class="whatshot-content-title">Popular People</span>
				<ul id="pop-people-list">
					<?php foreach($musicians as $musician) { ?>
					<li class="pop-people-list-item">
						<img class="profile-pic" src="<?php echo $musician['imgpath']; ?>"/>
						<a href="<?php echo base_url().'users/profile/'.$musician['id']; ?>"></a>
					</li>
					<?php } ?>	
				</ul>
			</div>
		</div>
		<!--  Popular Projects -->
		<div id="whatshot-content-pop-projects" class="whatshot-row">
			<div id="pop-projects-colorbg"></div>
			<div id="pop-projects-content" class="container">
				<span class="whatshot-content-title">Popular Projects</span>
				<ul id="pop-projects-list">
					<?php foreach($projects as $project) { ?>
					<li class="pop-project-list-item span3">						
						<img class="projimg span3" src="<?php echo $project['imgpath']; ?>"/>
						<a class="projlink" href="<?php echo base_url().'projects/profile/'.$project['id']; ?>"></a>
						<div class="projinfo">
							<span class="projowner"><?php echo $project['projowner']; ?></span>
							<span class="projtitle"><?php echo $project['projtitle']; ?></span>
							<p class="projdesc"><?php echo $project['projdesc']; ?></p>
						</div>					
					
					</li>
					<?php } ?>	
				</ul>
			</div>
		</div>
		<!-- Trending Tags -->
		<!--  Popular Projects -->
		<div id="whatshot-content-trending" class="whatshot-row">
			<div id="trending-colorbg"></div>
			<div id="trending-content" class="container">
				<span class="whatshot-content-supertitle">Trending</span>
				<div class="tagblock span4">
					<span class="whatshot-content-title">Tags</span>
					<ul id="tags-list">
						<?php foreach($tags as $tag) { ?>
						<li class="tag"><?php echo $tag; ?></li>
						<?php } ?>	
					</ul>
				</div>
				<div class="tagblock span4">
					<span class="whatshot-content-title">Genres</span>
					<ul id="tags-list">
						<?php foreach($genres as $genre) { ?>
						<li class="tag"><?php echo $genre; ?></li>
						<?php } ?>	
					</ul>
				</div>
				<div class="tagblock span4">
					<span class="whatshot-content-title">Influences</span>
					<ul id="tags-list">
						<?php foreach($influences as $influence) { ?>
						<li class="tag"><?php echo $influence; ?></li>
						<?php } ?>	
					</ul>
				</div>
			</div>
		</div>
	</div>

</div>