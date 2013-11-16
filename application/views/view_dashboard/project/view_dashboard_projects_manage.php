<div id="project_manage">
    <div class="default-header">
    	<div class="container">
    		<div class="row">
		        <p class="span12">Manage Projects</p>
		        <p class="span7">The Manage Projects screen lets you see in broad strokes the projects you have
		        	been involved in. Select a project to manage team members, edit the project
		        	profile , and complete finished pojects.</p>
        	</div>
    	</div>
    </div>
	<div class="page-content container">
        <div class="row">
	        <div class="span6 nav-container">
				<ul class="nav nav-tabs nav-append-content">
					<li data-category="0" class="<?php if($active_tab == Project::UNPUBLISHED){echo 'active';}?>">
						<a href="#projects_unpublished_container">Unpublished</a>
					</li>
					<li data-category="1"  class="<?php if($active_tab == Project::ACTIVE || $active_tab === FALSE){echo 'active';}?>">
						<a href="#projects_active_container">Active</a>
					</li>
					<li data-category="2"  class="<?php if($active_tab == Project::COMPLETED){echo 'active';}?>">
						<a href="#projects_completed_container">Completed</a>
					</li>
					<li data-category="3"  class="<?php if($active_tab == Project::MYAPPLICATIONS){echo 'active';}?>">
						<a href="#projects_applied_container">My Applications</a>
					</li>
				</ul>
			</div>
			<div class="span6 pull-right tool-bar">
				<div class="row">
					<div class="span4">
						<form id="project_search_form" class="form-search">
							<div class="input-append">
								<input id="project_search" class="span3 search-query search-query-rounded" type="text" placeholder="Search">
								<button id="project_search_btn" class="btn">
									<span class="fui-search"></span>
								</button>
							</div>
						</form>
					</div>
					<div class="span2">
						<div class="btn-grey">
							<div id="sort_by_dropdown" class="btn-group pull-right">
								<i class="dropdown-arrow dropdown-arrow-inverse"></i>
								<button class="btn btn-info selected-sort">Sort By</button>
								<button class="btn btn-info dropdown-toggle" data-toggle="dropdown">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-inverse">
									<li class="sort-option" data-type="0"><a>Alphabetical</a></li>
									<li class="sort-option" data-type="1"><a>Most Recent</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>	
			</div>
		</div>
	
		<div class="row project-filter">
			<div class="span6">
			    <label class="radio project_filter_option"><input type="radio" name="project_filter_radios"  value="<?=Fms_project_member_model::ALL?>" data-toggle="radio" checked>
			    	All Projects
			    </label>
	            <label class="radio project_filter_option"><input type="radio" name="project_filter_radios"  value="<?=Fms_project_member_model::OWNER?>" data-toggle="radio">
	            	Owner
	            </label>
	            <label class="radio project_filter_option"><input type="radio" name="project_filter_radios"  value="<?=Fms_project_member_model::MEMBER?>" data-toggle="radio">
	            	Participant
	            </label>
			</div>
		</div>

		<div class="row tab-content">
	        <div id="projects_unpublished_container" class="tab-pane span12 <?php if($active_tab == Project::UNPUBLISHED){echo 'active';}?>">
	        	<div class="row">
	            	<?php if($noUnpublishedFlag) { ?>
		        		<p class="noproject">
		        		<?php if($noProjectFlag) {?>
		        			You have no projects! You can <a href= <?php echo $createUrl ?>>create</a> a project or <a href= <?php echo $searchUrl ?>>join</a> an existing one.
		        		<?php } else {?>
		        			You have no unpublished projects. Why not <a href= <?php echo $createUrl ?>>create one?</a> 
		        		<?php }?>	        
		        		</p>
		        	<?php }?>
	        			        		
		            <?php foreach ($project_members['unpublished'] as $project_member): ?>
		                <div class="span6 project" 
		                	data-id="<?=$project_member->getProject()->getId()?>" 
		                	data-time="<?php if($project_member->getProject()->getCreationTime()){echo $project_member->getProject()->getCreationTime()->format('Y-m-d');}?>">
		                    <div class="row">
			                    <div class="project-img span2">
				                    <img src="<?php if ($project_member->getProject()->getPhoto()){
				                    	echo base_url($project_member->getProject()->getPhoto()->getPath() . $project_member->getProject()->getPhoto()->getName());}?>"/>
				                    <a href="<?php echo base_url('dashboard/project/edit_basic/'.$project_member->getProject()->getId());?>"></a>
			                    </div>
			                    <div class="project-info span4">
				                    <a class="project-name unpublished" href="<?php echo base_url('dashboard/project/edit_basic/'.$project_member->getProject()->getId());?>"><?=$project_member->getProject()->getName()?></a>
									<a class="btn btn-small btn-block btn-success btn-edit" href="<?php echo base_url('dashboard/project/edit_basic/'.$project_member->getProject()->getId());?>">Edit</a>
									<a class="btn btn-small btn-block btn-delete">Delete</a>
								</div>
							</div>
		                </div>
		            <?php endforeach; ?>
	            </div>
	        </div>
	
	        <div id="projects_active_container" class="tab-pane span12 <?php if($active_tab == Project::ACTIVE || $active_tab === FALSE){echo 'active';}?>">
	        	<div class="row">
	            	<?php if($noActiveFlag) { ?>
		        		<p class="noproject">
		        		<?php if($noProjectFlag) {?>
		        			You have no projects! You can <a href= <?php echo $createUrl ?>>create</a> a project or <a href= <?php echo $searchUrl ?>>join</a> an existing one.    		
		        		<?php } else {?>
		        			You have no active projects. Why not <a href= <?php echo $createUrl ?>>create one?</a>
		        		<?php }?>	        
		        		</p>
		        	<?php }?>
	        				
	                <?php foreach ($project_members['active'] as $project_member): ?>
		                <div class="span6 project <?php if($project_member->getRole() == Fms_project_member_model::OWNER){echo 'owner';}?>" 
		                	data-id="<?=$project_member->getProject()->getId()?>" 
		                	data-role="<?=$project_member->getRole()?>" 
		                	data-time="<?php if($project_member->getProject()->getLastEditTime()){echo $project_member->getProject()->getLastEditTime()->format('Y-m-d');}?>">
		                    <div class="row">
			                    <div class="project-img span2">
				                    <img src="<?php if ($project_member->getProject()->getPhoto()){
				                    	echo base_url($project_member->getProject()->getPhoto()->getPath() . $project_member->getProject()->getPhoto()->getName());}?>"/>
				                    <a href="<?php echo base_url('dashboard/project/overview/'.$project_member->getProject()->getId());?>"></a>
			                    </div>
			                    <div class="project-info span3">
				                    <a class="project-name" href="<?php echo base_url('dashboard/project/overview/'.$project_member->getProject()->getId());?>"><?=$project_member->getProject()->getName()?></a>
									<div class="project-member-info">
										<p>My Skill: <span><?php foreach($project_member->getSkillForProject() as $projectSkill){
											echo $projectSkill->getSkill()->getName().' ';
										}?></span></p>
										<p><?php if($project_member->getRole() == Fms_project_member_model::OWNER){
													echo 'Created: ';
												}
												else{
													echo 'Joined: ';
												}?>
											<span><?php if($project_member->getCreationTime()){echo $project_member->getCreationTime()->format('F j, Y');}?></span>
										</p>
									</div>
								</div>
							</div>
		                </div>
	                <?php endforeach;?>
	             </div>
	        </div>
	
	        <div id="projects_completed_container"  class="tab-pane span12 <?php if($active_tab == Project::COMPLETED){echo 'active';}?>">
	            <div class="row">
	            	<?php if($noCompletedFlag) { ?>
		        		<p class="noproject">
		        		<?php if($noProjectFlag) {?>
		        			You have no projects! You can <a href= <?php echo $createUrl ?>>create</a> a project or <a href= <?php echo $searchUrl ?>>join</a> an existing one.		        		
		        		<?php } else {?>
		        			You have no completed projects yet.
		        		<?php }?>	        
		        		</p>
		        	<?php }?>
	        			            		            	
	                <?php foreach ($project_members['completed'] as $project_member):?>
		                <div class="span6 project <?php if($project_member->getRole() == Fms_project_member_model::OWNER){echo 'owner';}?>" 
	                		 data-id="<?=$project_member->getProject()->getId()?>" 
	                		 data-role="<?=$project_member->getRole()?>" 
	                		 data-time="<?php if($project_member->getProject()->getCompleteTime()){echo $project_member->getProject()->getCompleteTime()->format('Y-m-d');}?>"
                		 >
		                    <div class="row">
			                    <div class="project-img span2">
				                    <img src="<?php if ($project_member->getProject()->getPhoto()){
				                    	echo base_url($project_member->getProject()->getPhoto()->getPath() . $project_member->getProject()->getPhoto()->getName());}?>"/>
				                    <a href="<?php echo base_url('dashboard/project/overview/'.$project_member->getProject()->getId());?>"></a>
			                    </div>
			                    <div class="project-info span3">
				                    <a class="project-name" href="<?php echo base_url('dashboard/project/overview/'.$project_member->getProject()->getId());?>"><?=$project_member->getProject()->getName()?></a>
									<div class="project-member-info">
										<p>My Skill: <span><?php foreach($project_member->getSkillForProject() as $projectSkill){
											echo $projectSkill->getSkill()->getName().'  ';
										}?></span></p>
										<p><?php if($project_member->getRole() == Fms_project_member_model::OWNER){
													echo 'Created: ';
												}
												else{
													echo 'Joined: ';
												}?>
											<span><?php if($project_member->getCreationTime()){echo $project_member->getCreationTime()->format('F j, Y');}?></span>
										</p>
									</div>
								</div>
							</div>
		                </div>
	            	<?php endforeach;?>
	            </div>
	        </div>
	
	        <div id="projects_applied_container" class="tab-pane span12 <?php if($active_tab == Project::MYAPPLICATIONS){echo 'active';}?>">
	        	<div class="row">
	        		<?php if( $noApplicationFlag ) {?>
	        		<p class="noproject">	        		
	        			You haven't applied to any projects yet! Find and <a href= <?php echo $searchUrl ?>>join one now!</a>	        		
	        		</p>
	        		<?php }?>

			        <?php foreach ($applied_applications as $application) {
			        	if($application->getStatus() !== Fms_audition_model:: ACCEPTED){ 
			        		?>               
		                <div class="span6 project" 
		                	data-id="<?=$application->getProject()->getId()?>" 
		                	data-time="<?php if($application->getCreationTime()){echo $application->getCreationTime()->format('Y-m-d');}?>">
		                    <div class="row">
			                    <div class="project-img span2">
			                    	<img src="<?php if ($application->getProject()->getPhoto()) {
		                        	echo base_url($application->getProject()->getPhoto()->getPath() . $application->getProject()->getPhoto()->getName());}?>"/>
		                    		<a href="<?php echo base_url('projects/profile/'.$application->getProject()->getId());?>"></a>
		                    	</div>
			                    <div class="project-info span4">
				                    <a class="project-name unpublished" href="<?php echo base_url('projects/profile/'.$application->getProject()->getId());?>"><?=$application->getProject()->getName()?></a>
									<a class="btn btn-small btn-block btn-message" data-btn="msg" data-userid="<?=$application->getProject()->getOwner()->getId()?>">Message Owner</a>
									<a class="btn btn-small btn-block btn-primary btn-view" href="<?php echo base_url('projects/profile/'.$application->getProject()->getId());?>">View</a>
									<button class="btn-withdraw close fui-cross" data-auditionid="<?=$application->getId()?>"></button>
								</div>
			                </div>
	              		</div>
	        		<?php }} ?>
	        	</div>
	        </div>
	    </div>
    </div>
</div>