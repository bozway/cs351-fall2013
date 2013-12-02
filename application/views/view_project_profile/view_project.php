<div class="profile-container hidden-phone">
	
  <div class="profile-header">
 	<div class="row">
 		<div class="span4">
	    	<img class="cover-container" src="<?php
			if(isset($project_cover)){
				echo base_url($project_cover->getPath().$project_cover->getName());
			}else
			{
				echo  base_url('/img/default_project_photo.jpg');
			}
			?>"/>
    	</div>
 	
       <div class="span8" id="project-basic-info">
   		 <div class="span8">
       		<p class="project-title"><?=$project->getName()?></p>
	  	    <p class="project-creator">by <a href="<?php if(isset($project_creator)){echo base_url('/users/profile/'.$project_creator->getId());}?>" target="_blank">
		<?php  if(isset($project_creator)){
			echo $project_creator->getFirstName(). ' ' .$project_creator->getLastName();			
		}?></a></p>
        </div>
        
        	<div class="span4">
            <p class="info"><span class="fui-location icon"></span>Location:<span><?=(strlen($project_location) > 0) ? $project_location : "Unspecified"; ?></span></p>
            <p class="info"><span class="fui-user icon"></span>Language:<span><?php if($project->getLanguage()){
				echo $project->getLanguage()->getLanguageName();
			}else{
				echo "Unknown";
			}?></span></p>
         </div>
            <div class="span3">
            	<div class="span4">
		            <p class="info"><span class="fui-time icon"></span>Duration:<span><?php if($project->getDuration()){
						echo $project->getDuration();
					}else{
						echo "Unspecified";
					}?> </span></p>
		           <?php if($project_start != ""):?>
		            <p class="info"><span class="fui-calendar icon"></span>Project Starts:<span><?=$project_start?></span></p>
		            <?php endif;?>
            	</div>
            </div>
             <div class="span7">
             	<!--Share on Twitter-->
             	<a id="linkTW" href="http://twitter.com/share?text=Check out my FindMySong project at &amp;url=<?php echo base_url("/projects/profile/".$project->getId());?>" onclick="javascript:window.open(this.href,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" title="Share on Twitter!">
             	<img src="<?php echo base_url('/img/fms_user_portal/icon_twitter.png') ?>">
              </a>
				<!--Share on Facebook-->
				<?php echo $fb_share_link; ?>
					<img src="<?php echo base_url('img/fms_user_portal/icon_facebook.png') ?>">		
			  	<?php echo '</a>'; ?>
     
        	<div>
	            <div class="popover right">
	              <div class="arrow"></div>            
	              <div class="popover-content">
	                <p>Share</p>
	              </div>
	            </div>
          	</div>
	         <div class="video_preview">
	         	<?php if($project_spotlight_audio) { ?>
	         		<div id="audio_player">
	         			<div class="demo-play-button"></div>	         			
	         			<div class="demo-progress-bar">
                        	<div class="demo-buffer-bar">
                        		<div class="demo-progress"></div>
                             </div>   
                        </div>
	         			<audio class="preview_audio"  src="<?=$project_spotlight_audio?>"></audio>
	         		</div>
	         	<?php } ?>
	         </div>
	         <?php if(!$is_projectMember):?>
		         	<a href="#fakelink" class="btn btn-large btn-block btn-success audition" data-projectid="<?=$project->getId()?>" data-btn="aud">AUDITION</a>
	       	 <?php endif;?>
          </div>
       </div>
        
 	</div>
  </div>
 
  <div id="clear"></div>

   <div class="project_public_content row">
   		<div class="span4">
	   		<div>
		        <?php if(count($project_needs)>0):?>
			        <div class="project_need">
			        	<span class="btn btn-large btn-block btn-inverse">Project Needs</span>
			        		<ul class="hot-link" id="hotlink">
			            	<?php foreach($project_needs as $need):?>
							<li><span><i class="skill-icon" data-categoryid="<?=$need['kill_categoryid']?>"> </i></span>
								<p class="skill_name"><?=$need['skill']?><span class="fui-arrow-right"></span></p>
								<div class="show" style="display:none;">
									 <p><span>Genres: </span><?=$need['genres']?></p>
			                        
									 <p><span>Influences: </span><?=$need['influence']?></p>
			                         
			                          <p><span>Details: </span><?=$need['skill_description']?></p>
								</div>
							</li>
			                <?php endforeach;?>
						</ul>
			        </div>
		 		<?php endif;?>
		 	</div>
		 	<div class="project_member">
	       		<span class="btn btn-large btn-block btn-inverse">Members</span>
	     		<ul>
	          	 	<?php foreach($project_members as $member):?>
	          		<li>
	          			<a class="member_cover" href="<?php echo base_url('/users/profile/'.$member['userid']);?>"><img src="<?=$member['photo']?>"/></a>
		            	<p class="member_name"><?=$member['name']?></p>
		                <p class="joined_skill">Joined:<span><?=$member['joined_date']?></span></p>
		                <p class="joined_skill">Skills:<span><?=$member['skills']?></span></p>
		      			<span data-userid="<?=$member['userid']?>" data-btn="msg" class="msg-icon fui-mail"></span>      
	            	</li>
	            	<?php endforeach;?>        
	     		</ul>
	         </div>
 		</div>
		
		<div class="span8">
	    	<div class="project_description">
           <?php if($project->getDescription()){ ?>
			   <p><?=$project->getDescription()?></p>
		   <?php }else{ 
		   		if($project_creator->getId() == $loggedInUser){
		   		?>
			   <div class="nodata"><p>You haven't created a project description yet!<br /> 
               Why not <a href="<?php echo base_url('dashboard/project/edit_basic/'.$project->getId())?>">
               add one</a> now?</p></div>
		   <?php }else{ ?>
		   	<div class="nodata"><p>The owner of this project hasn't updated the project description!<br/>
		   		You can still <?php if(!$is_projectMember) {?><a data-projectid="<?=$project->getId()?>" data-btn="aud">audition</a> 
		   		or <?php }?> <a data-userid="<?= $project_creator->getId() ?>" data-btn="msg">send them a message</a>.
		   		</p></div>
		  <?php }}?>
	 			
	 			<?php if($VideoPreview != ""):?>
		 			<div class="project-preview-video">
		 				<iframe title="Project Proview" width="620" height="365" src="<?=$VideoPreview?>" frameborder="0" allowfullscreen></iframe>
		 			</div>
	 			<?php endif;?>
	 		</div>
			<div class="profile-content">
		 		<?php if(isset($project_tags)){?>	
			    	<div class="project_tag">
				     	<?php foreach($project_tags as $tag):?>
					    	<div class="tag"><?=$tag?></div>
				    	<?php endforeach;?>
			        </div> 
		        <?php }?>
			</div>
		</div>
     </div>
     
   
</div>

