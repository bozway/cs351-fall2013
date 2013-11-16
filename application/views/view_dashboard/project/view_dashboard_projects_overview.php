<div class="dashboard-header">
	<div class="container">
		<div class="row">
			<p class="project-title span12"><span><?= $project->getName() ?></span> Overview</p>
			<p class="project-description span6">In your project overview, you can see 
			the most important details of your project.</p>
            
            <?php if($logged_in_role === 'owner' && $project->getStatus() === Fms_project_model::UNPUBLISHED) { ?>
                <button id="dashboard-action-project" class="fms-button">Start Project !</button>
            <?php } ?>
            
            <?php if($logged_in_role === 'owner' && $project->getStatus() == Fms_project_model::ACTIVE) {?>
            	<a id="dashboard-action-project" 
                	class="btn btn-large btn-block btn-success dashboard-header-btn"
                >Complete Project</a>
            <?php } else { ?>
	            <a class="btn btn-large btn-block btn-success dashboard-header-btn"
	            	href="<?php echo base_url('projects/profile/'.$project->getId());
	            ?>">View Public Profile</a>
	        <?php }?>
		</div>
	</div>
</div>




<div class="container" id ="<?php
if (isset($logged_in_userid)) {
    echo $logged_in_userid;
}
?>">
    <span id="project_id" data-project-id="<?php
    if ($project_id) {
        echo $project_id;
    }
    ?>"></span>
    
<div class="row">
	<?php if (isset($vertical_nav)) echo $vertical_nav; ?> 
    <div class="<?php if(isset($vertical_nav)) echo "span7"; else echo "span9"?>">
    	<p class="dashboard-subtitle">
			<span>Members</span>
		</p>
		<?php foreach ($members as $member) { ?>
		<?php if($member->getRole() != Fms_project_member_model::PAST_MEMBER) { ?>
			<div class="row">
				<div class="member-list-item <?php if(isset($vertical_nav)) echo "span7"; else echo "span9"?>">
					<div class="row">
						<div class="span2">
							<img class="member-photo" src="<?php 
								$memberUser = $member->getUser();
								$memberPhoto = $memberUser->getProfilePicture();
								if($memberPhoto) {
									echo base_url ( $memberPhoto->getPath() . $memberPhoto->getName() );
								} else {
									echo "http://www.findmysong.dev/img/fms_user_portal/demo_photo.png";
								}
							?>"></img>
							<a class="profile-photo-hover" href="<?php echo base_url('users/profile/'.$memberUser->getId())?>"></a>
						</div>
						<div class="<?php if(isset($vertical_nav)) echo "span5"; else echo "span7"?>">
							<p class="member-name"><?php 
								echo $memberUser->getFirstName() . ' '. $memberUser->getLastName();
								if($memberUser->getId() == $logged_in_userid) {
									echo " (Me)";
								}
							?></p>
							<p class="member-skill">Skills: <?php 
								$memberSkills =  $member->getSkillForProject();
								$memberSkillArray = array();
								foreach($memberSkills as $row) {
									$memberSkillArray[] = $row->getSkill()->getName();
								}
								echo implode(', ', $memberSkillArray);
							?></p>
							<?php if($memberUser->getId() != $logged_in_userid) {?>
								<a class="btn btn-small btn-block message-contact" 
									data-btn="msg" 
									data-userid="<?php echo $memberUser->getId();?>"
								><i class="fui-mail"></i>Message</a>
								<?php 
									$ifInContact = $this->fms_user_model->checkUserContact($logged_in_userid, (int) $member->getUser()->getId());
									$save = ($ifInContact) ? 0 : 1;
									$action = ($save) ? 'Save' : 'Unsave';
								?>
								<a class="btn btn-small btn-block <?php echo $save? 'btn-success' : ''?> save-contact" 
									data-btn="con" 
									data-userid="<?php echo $memberUser->getId();?>"
									data-toggle="<?php echo $save; ?>"
								><?php echo $action?> contact</a>
							<?php } else {?>
								<?php if($logged_in_role != 'owner' && $project->getStatus() != Fms_project_model::COMPLETED) {?>
									<a class="btn btn-small btn-block btn-warning leave-project"
										id="dashboard-leave-project" data-user-id="<?= $user_member->getId() ?>" 
									>Leave Project</a>
								<?php }?>
							<?php }?>
							<?php if($member->getRole() != Fms_project_member_model::PAST_MEMBER && $member->getRole() != Fms_project_member_model::OWNER && $logged_in_role=='owner') {?>
								<span class="fui-cross dashboard-project-member-kickout"
									data-member-id="<?php echo $member->getId();?>"
								></span>
							<?php }?>
							<?php if($member->getRole() == Fms_project_member_model::OWNER) {?>
								<span class="owner-ribbon"></span>
							<?php }?>
						</div>
					</div>
				</div>
			</div>
		<?php }?>
		<?php }?>
		
		<div id="show-past-member"><p><a>Show Past Members</a></p></div>
		<div id="past-member">
		<?php foreach ($members as $member) { ?>
		<?php if($member->getRole() == Fms_project_member_model::PAST_MEMBER) { ?>
			<div class="row">
				<div class="member-list-item <?php if(isset($vertical_nav)) echo "span7"; else echo "span9"?>">
					<div class="row">
						<div class="span2">
							<img class="member-photo" src="<?php 
								$memberUser = $member->getUser();
								$memberPhoto = $memberUser->getProfilePicture();
								if($memberPhoto) {
									echo base_url ( $memberPhoto->getPath() . $memberPhoto->getName() );
								} else {
									echo "http://www.findmysong.dev/img/fms_user_portal/demo_photo.png";
								}
							?>"></img>
							<a class="profile-photo-hover" href="<?php echo base_url('users/profile/'.$memberUser->getId())?>"></a>
						</div>
						<div class="<?php if(isset($vertical_nav)) echo "span5"; else echo "span7"?>">
							<p class="member-name"><?php 
								echo $memberUser->getFirstName() . ' '. $memberUser->getLastName();
								if($memberUser->getId() == $logged_in_userid) {
									echo " (Me)";
								}
							?></p>
							<p class="member-skill">Skills: <?php 
								$memberSkills =  $member->getSkillForProject();
								$memberSkillArray = array();
								foreach($memberSkills as $row) {
									$memberSkillArray[] = $row->getSkill()->getName();
								}
								echo implode(', ', $memberSkillArray);
							?></p>
							<?php if($memberUser->getId() != $logged_in_userid) {?>
								<a class="btn btn-small btn-block message-contact" 
									data-btn="msg" 
									data-userid="<?php echo $memberUser->getId();?>"
								><i class="fui-mail"></i>Message</a>
								<?php 
									$ifInContact = $this->fms_user_model->checkUserContact($logged_in_userid, (int) $member->getUser()->getId());
									$save = ($ifInContact) ? 0 : 1;
									$action = ($save) ? 'Save' : 'Unsave';
								?>
								<a class="btn btn-small btn-block <?php echo $save? 'btn-success' : ''?> save-contact" 
									data-btn="con" 
									data-userid="<?php echo $memberUser->getId();?>"
									data-toggle="<?php echo $save; ?>"
								><?php echo $action?> contact</a>
							<?php } else {?>
								<?php if($logged_in_role != 'owner' && $member->getRole() == Fms_project_member_model::MEMBER) {?>
									<a class="btn btn-small btn-block btn-warning leave-project"
										id="dashboard-leave-project" data-user-id="<?= $user_member->getId() ?>" 
									>Leave Project</a>
								<?php }?>
							<?php }?>
						</div>
					</div>
				</div>
			</div>
		<?php }?>
		<?php }?>
		</div>
    </div>
    <div class="span3">
    	<p class="dashboard-subtitle">
			<span>Information</span>
		</p>
		<div class="info-block">
			<p class="info-subtitle1">My Role:</p>
			<p class="info-content-highlighted"><?php 
				if($logged_in_role == 'owner') {
					echo "Owner";
				}
				if($logged_in_role == 'participant') {
					echo "Participant";
				}
				if($logged_in_role == 'past-participant') {
					echo "Past Participant";
				}
			?></p>
			<p class="info-subtitle1">My Skills:</p>
			<p class="info-content-highlighted"><?php 
				$memberSkills =  $user_member->getSkillForProject();
				$memberSkillArray = array();
				foreach($memberSkills as $row) {
					$memberSkillArray[] = $row->getSkill()->getName();
				}
				echo implode(', ', $memberSkillArray);
			?></p>
		</div>
		<div class="info-block">
			<p class="info-subtitle2">Created</p>
			<p class="info-content-faded"><?php 
				$creationDate = $project->getCreationTime();
				$timezone = new DateTimeZone('America/Los_Angeles');
				$creationDate->setTimeZone($timezone);
				echo $creationDate->format('F j, Y');
			?></p>
			<span class="fui-user info-icon"></span>
		</div>
		<div class="info-block">
			<p class="info-subtitle2">Start Date</p>
			<p class="info-content-faded"><?php 
				$startDate = $project->getStartDate();
				if($startDate) {
					$startDate->setTimeZone($timezone);
					echo $startDate->format('F j, Y');
				} else {
					echo "Unknown";
				}
			?></p>
			<span class="fui-calendar info-icon"></span>
		</div>
		<div class="info-block">
			<p class="info-subtitle2">Location</p>
			<p class="info-content-faded"><?php 
				$location = array();
				if($project->getCity())
					$location[] = $project->getCity();
				if($project->getState())
					$location[] = $project->getState()->getAbbreviatedName();
				if($project->getCountry())
					$location[] = $project->getCountry()->getIsoCode();
				if(count($location)) {
					echo implode(', ', $location);
				} else {
					echo "Unknown";
				}
			?></p>
			<span class="fui-location info-icon"></span>
		</div>
		<div class="info-block">
			<p class="info-subtitle2">Duration</p>
			<p class="info-content-faded"><?php 
				$duration = $project->getDuration();
				if($duration) {
					echo $duration;
				} else {
					echo "Unknown";
				}
			?></p>
			<span class="fui-time info-icon"></span>
		</div>
		<?php if($project->getStatus() != Fms_project_model::COMPLETED) { ?>
			<p class="info-subtitle3">Skills Open:</p>
			<?php $iter = 0;?>
			<?php foreach($project->getSkills() as $projectSkill) {
				if($projectSkill->getIsOpen() == 1) {?>
					<p class="info-content-tabbed"><?php 
						echo $projectSkill->getSkill()->getName();
						$iter++;
					?></p>
			<?php }}?>
			<?php if($iter == 0) {
					echo "<p>No Open Skills</p>";
			}?>
		<?php } ?>
		
		<div class="social-btn-wrap">
			<div class="facebook-btn-wrap">		
				<?php echo $fb_share_link; ?>		
					<span class="fui-facebook"></span>
				<?php echo '</a>'; ?>
				<!-- 
				<a id="linkFB" class="btn btn-small btn-block btn-primary share-facebook" href="http://www.facebook.com/sharer.php?u=<?php echo base_url("/projects/profile/".$project->getId());?>" onclick="javascript:window.open(this.href,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" title="Share on Facebook!">
					Share<span class="fui-facebook"></span>
				</a>-->
			</div>
			<div class="twitter-btn-wrap">				
				<!--Share on Twitter-->
             	<a id="linkTW" class="btn btn-small btn-block btn-info share-twitter" href="http://twitter.com/share?text=Check out my FindMySong project at &amp;url=<?php echo base_url("/projects/profile/".$project->getId());?>" onclick="javascript:window.open(this.href,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" title="Share on Twitter!">
             		Share<span class="fui-twitter"></span>
             	</a>
			</div>
		</div>
    </div>
</div>

</div>
