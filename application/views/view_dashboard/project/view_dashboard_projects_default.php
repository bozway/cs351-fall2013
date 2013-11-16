<div class="default-header">
	<div class="container">
		<div class="row">
			<p class="project-title span12">Your Project Dashboard</p>
			<p class="project-description span7">Your project dashboard holds all of your current and completed projects, 
			as well as any projects you have applied to. You can create a new project, 
			or you can manage your current projects and applications!</p>
			<div id="create_project_button" class="span3">
				<a class="btn btn-large btn-block btn-primary btn-embossed dashboard-header-btn"
					href="<?php echo base_url('dashboard/project/create_basic')?>">Create a Project</a>
			</div>
		</div>
    </div>
</div>

<div class="container dashboard-default-container">	
		<ul class="container define-default">
			<li>
				<p>Start a new project</p>
				<p>Create a new project to get started creating new music with other talents!  
					Projects let you manage your group and stay organized while you make music.</p>
				<button class="btn btn-embossed btn-large">
					<a href="<?= base_url('dashboard/project/create_basic')?>">New Project</a>
				</button>
			</li>
			<li>
				<p>Edit your applications</p>
				<p>View and update your current applications.  You can message the owner, 
					view the project one more time, or withdraw your application.</p>
				<button class="btn btn-embossed btn-large">
					<a href="<?= base_url('dashboard/project/manage/myapplications') ?>">My Applications</a>
				</button>
			</li>
			<li>
				<p>Manage your projects</p>
				<p>See every important detail of your project in one click.  Manage your team, 
					audition new musicians, and complete your projects.</p>
				<button class="btn btn-embossed btn-large">
					<a href="<?= base_url('dashboard/project/manage') ?>">Manage Projects</a>
				</button>
			</li>
		</ul>
	
	    <div class="container default-helpful">
	    	<p>
			<span class="helpful_links">Helpful Links </span> (for more information, <a href="<?= base_url('help') ?>">visit the Help Center</a>)</p>
	        <p><a href="<?= base_url('FAQ#create_project') ?>">How do I create a new project?</a></p>
	        <p><a href="<?= base_url('FAQ#join_projects') ?>">How do I find projects to join?</a></p>
	        <p><a href="<?= base_url('FAQ#finding_musicians') ?>">I’ve made my new project. How do I find musicians?</a></p>
	        <p><a href="<?= base_url('FAQ#intro_skill') ?>">What are skills?</a></p>
	        <p><a href="<?= base_url('contact') ?>">I want to delete my project.</a></p>
	    </div>   
</div>