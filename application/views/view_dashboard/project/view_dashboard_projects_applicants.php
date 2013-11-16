<div class="dashboard-header">
	<div class="container">
		<div class="row">
			<p class="project-title span12"><span><?= $project->getName() ?></span> Applicants</p>
			<p class="project-description span12">In your project overview, 
			you can see the most important details of your project.</p>
		</div>
	</div>
</div>

<div id="project-applicants" class="container">
	<span id="project_id" data-id="<?php echo $project->getId();?>"></span>
	<div class="row">
		<?=$vertical_nav?>
		<div class="span10">
			<div id="sortby" class="applicants-sorting-bar">
				<div class="btn-grey">
					<div class="btn-group">
						<i class="dropdown-arrow dropdown-arrow-inverse"></i>
						<button class="btn btn-info selected-sort">Sort By</button>
						<button class="btn btn-info dropdown-toggle" data-toggle="dropdown">
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu dropdown-inverse sortby-list">
							<li data-type="0"><a>Skill</a></li>
							<li data-type="1"><a>Name</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div id="applicant-list">
				<?php if(count($auditions) == 0):?>
					<p>You have no applicants yet!</p>
				<?php endif;?>
					
				<?php foreach ($auditions as $audition):?>
					<div class="row">
						<div class="applicant-list-item span10">
							<div class="row">
								<div class="applicant-photo-wrap span2">
									<img class="applicant-photo" src="<?php 
										$applicantUser = $audition->getApplicant();
										$applicantPhoto = $applicantUser->getProfilePicture();
										if($applicantPhoto) {
											echo base_url ( $applicantPhoto->getPath() . $applicantPhoto->getName() );
										} else {
											echo "http://www.findmysong.dev/img/fms_user_portal/demo_photo.png";
										}
									?>"></img>
									<a class="profile-photo-hover" href="<?php echo base_url('users/profile/'.$applicantUser->getId())?>"></a>
								</div>
								<div class="span8">
									<p class="applicant-name"><?php echo $applicantUser->getFirstName() . ' '. $applicantUser->getLastName(); ?></p>
									<p class="applicant-skill">Audition For: <?php echo $audition->getSkill()->getSkill()->getName(); ?>
									<div class="applicant-button">
										<a class="btn btn-small btn-block message-contact" 
											data-btn="msg" 
											data-userid="<?php echo $applicantUser->getId();?>"
										><i class="fui-mail"></i>Message</a>
										<?php 
											$ifInContact = $this->fms_user_model->checkUserContact($project->getOwner()->getId(), (int) $applicantUser->getId());
											$save = ($ifInContact) ? 0 : 1;
											$action = ($save) ? 'Save' : 'Unsave';
										?>
										<a class="btn btn-small btn-block <?php echo $save? 'btn-success' : ''?> save-contact" 
											data-btn="con" 
											data-userid="<?php echo $applicantUser->getId();?>"
											data-toggle="<?php echo $save; ?>"
										><?php echo $action?> contact</a>
									</div>
								</div>
							</div>
                           <?php  if($freeze_header=="applicants"){?>
							<span class="fui-check applicant-accept" 
								data-user-id="<?= $applicantUser->getId(); ?>"
								data-skill-id="<?= $audition->getSkill()->getSkill()->getId(); ?>"></span>
							<span class="fui-cross applicant-hide" 
	                            data-user-id="<?= $applicantUser->getId(); ?>" 
								data-skill-id="<?= $audition->getSkill()->getSkill()->getId(); ?>"></span>
                                <?php }else{?>
                                <span class="applicant-re-shown" 
                                	data-user-id="<?= $applicantUser->getId(); ?>" 
                                    data-skill-id="<?= $audition->getSkill()->getSkill()->getId(); ?>">
                                <a href="#fakelink" class="btn btn-small btn-block btn-primary">Unhide</a>
                                </span>
                                <?php }?>
							<p class="applicant-experience"><span>Experience:</span><span class="content-data"><?php echo "30"?> Projects</span></p>
						</div>
					</div>
				<?php endforeach;?>
			</div>
		</div>

	</div>
</div>

