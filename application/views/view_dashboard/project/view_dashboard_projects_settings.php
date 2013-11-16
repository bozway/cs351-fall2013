<div class="dashboard-header">
	<div class="container">
		<div class="row">
			<p class="project-title span12"><?php echo $project->getName(); ?></p>
			<p class="project-description span9">It's normally a good idea to keep your project here for safety. 
			But if you'd like to download all your files, delete the project, 
			or otherwise make permanent changes, this is the place!</p>
		</div>
    </div>
</div>

<div class="body-container">
    <?= $vertical_nav ?>

    <div class="dashboard-project-settings-container span10" id='<?= $project->getId(); ?>' 
         data-project-name="<?= $project->getName(); ?>">
        <ul>
        	<li>
        		<p>Project Status: <span id="project-status"><?= strtoupper($currentStatus); ?></span></p>
        		<p>Once you've finished working on your project, you can complete it to save a permanent
        			profile on the web! Completing your project also frees up space for you to create new projects.</p>
    			<div class="demo-col" id="dashboard-project-action">
					<a href="#" class="btn btn-large btn-block">Complete Project</a>
				</div>
				<div class="complete-message"></div>
        	</li>
        	<li>
        		<p>Delete Project</p>
        		<p>Deleting your project completely removes it from the FindMySong system. There's no going back!
        			Since you're not the only member on this project, please contact our customer service to delete
        			your project</p>
    			<div class="demo-col">
					<a href="<?= base_url('contact') ?>" class="btn btn-large btn-block">Contact Us</a>
				</div>
        	</li>
        </ul>
        <!-- presently unsupported 
        <div class="project-url">
        	<p>Permanent URL</p>
        	<input type="text" value="<?= base_url("projects/profile/" . $project->getId()) ?>" readonly>
        </div>
        -->
    </div>
</div>
