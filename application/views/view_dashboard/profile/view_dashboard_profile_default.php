<div id="dashboardprofile" class="profile-container">
	<div class="default-header <?php if($active_panel !== FALSE){echo 'hide';}?>" id="dashboard-profile-default-page">
		<div class="container">
			<div class="row">
				<p class="span12">Your FMS Profile</p>
				<p class="span7">Your profile is where you can show off yourself to the world. A beautiful profile is a major
					first step to working with new musicians, finding gigs, getting to know management and exposing
					your music to the world.</p>
				<div id="view_profile_container" class="save-btn-container span3">
					<button class="view-profile btn btn-large btn-block btn-primary btn-embossed" data-userid="<?= $loggedin_user?> ">View Profile</button>
				</div>
			</div>
		</div>
	</div>
	<div id="profile_default" class="container dashboard-default-container <?php if($active_panel === FALSE){echo 'active-panel';}?>">
        <ul class="container define-default">
            <li>
                <p>Complete your profile</p>
                <p>Finishing your profile helps other musicians find you on FMS and greatly increases your 
                    chances of being invited to projects.  Completing your profile also lets you use your 
                    FMS profile as your musical resume on the web.</p>
                <button class="btn btn-embossed btn-large">
                    <a id="show_basic_settings" >Edit Profile</a>
                </button>
            </li>
            <li>
                <p>Find People & Projects</p>
                <p>Once your profile is ready, get involved on FMS by<a href="<?= base_url('users/search')?>"> searching musicians</a> 
                    to work with or <a href="<?= base_url('projects/search')?>">searching projects</a> to audition for. Projects are the best 
                    way to meet and collaborate with other musicians on FMS.</p>
                <button class="btn btn-embossed btn-large">
                    <a href="<?= base_url('projects/search')?>">Find Projects</a>
                </button>
            </li>
            <li>
                <p>Create a Project</p>
                <p>If you already have material or an idea and want to work with other peope on your music, 
                    then you’re ready to create a project of your own!  Create a project, describe who you need, 
                    and get started on your music right away.</p>
                <button class="btn btn-embossed btn-large">
                    <a href="<?= base_url('dashboard/project/create_basic')?>" >Let's go!</a>
                </button>
            </li>
        </ul>
		<div class="container default-helpful">
			<p>
				<span class="helpful_links">Helpful Links</span> (for more information,<a href="<?= base_url('help') ?>">visit the Help Center)</a>
			</p>
			<p>
				<a href="<?= base_url('FAQ#intro_skill') ?>">What are skills?</a>
			</p>
			<p>
				<a href="<?= base_url('FAQ#search_for_project') ?>">How do I find projects?</a>
			</p>
			<p>
				<a href="<?= base_url('FAQ#audition') ?>">How do I audition for a project?</a>
			</p>
			<p>
				<a href="<?= base_url('FAQ#invite_musician') ?>">How do I invite friends to my project?</a>
			</p>
			<p>
				<a href="<?= base_url('contact') ?>">I want to deactivate my account</a>
			</p>
		</div>
	</div>
	
	<?=$profile_connect?>
	<div id="profile_basic_settings" class="<?php if($active_panel == Profile::BASIC_SETTINGS){echo 'active-panel';}?>"><?= $profile_basic_settings ?></div>
	<?=$profile_portfolio ?>
	<div id="profile_biography" class="container <?php if($active_panel == Profile::BIOGRAPHY){echo 'active-panel';}?>"></div>
	<?=$profile_skill?>
</div>