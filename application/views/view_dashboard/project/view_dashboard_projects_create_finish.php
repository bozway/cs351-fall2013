<div class="dashboard-header">
	<div class="container">
		<p class="dashboard-header-title">Congratulations! 
			<span class="dashboard-header-subtitle">You've published your new project.  
			Here are some guidelines for what to do next!</span>
		</p>
	</div>
</div>

<div id="project_create_finish" class="container">
	<div class="row">
		<div class="span4">
			<p class="dashboard-subtitle">
				<span>Share your Project</span>
			</p>
			<p>Sharing your project helps your friends and followers get involved!</p>
			<div class="share-buttons row">
				<div class="span3">					
					<?php echo $fb_share_link; ?>					
					<a class="btn btn-large btn-block btn-info" href="http://twitter.com/share?text=Check out my FindMySong project at &amp;url=<?php echo base_url("/projects/profile/".$project_id);?>" onclick="javascript:window.open(this.href,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" title="Share on Twitter!">
             			Share with Twitter
             		</a>
             		
             		             		
				</div>
			</div>
		</div>
		<div class="span8" id="project_create_finish_content">
			<div class="row">
				<p class="dashboard-subtitle span8">
					<span>Find & Invite Musicians</span>
				</p>
				<p class="span6">Now that you've created a new project, you can invite people to join!  
				Use the <a href="<?php echo base_url('users/search')?>">musician search</a> to find people who fit the skills you need, 
				or invite your friends from your <a href="<?php echo base_url('dashboard/message');?>">contacts</a>.</p>
						
				<div class="span2">
					<a href="<?php echo base_url('users/search')?>" class="btn btn-large btn-block btn-success">Search</a>
				</div>
			</div>
			
			<div class="row">
				<p class="dashboard-subtitle span8">
					<span>Polish Up the Project Profile</span>
				</p>
				<p class="span6">Your project profile is the permanent home for your music online!  
				Add an audio preview track, a video, the lyrics, and the story behind the music.  </p>
			
				<p class="span2">
					<a href="<?php echo base_url('dashboard/project/edit_basic/'.$project_id)?>" class="btn btn-large btn-block">Go</a>
				</p>
			</div>
			
			<div class="row">
				<p class="dashboard-subtitle span8">
					<span>Share your Project</span>
				</p>
				<p class="span6">Share your project to your Facebook friends and Twitter followers so your friends and fans can follow along.  
				You can also use your project's permanent link to share anywhere and everywhere else.</p>
				
			</div>
			
			<div class="row">
				<p class="dashboard-subtitle span8">
					<span>Update your Personal Profile</span>
				</p>
				<p class="span6">Most people who view your new project will go on to view your personal profile. 
				A completed personal profile makes other musicians much more likely to work with you!  
				You can also add your new project to your project portfolio.</p>
			
				<p class="span2">
					<a href="<?php echo base_url('dashboard/profile/'.$user_id)?>" class="btn btn-large btn-block">Go</a>
				</p>
			</div>
		</div>
	</div>
</div>