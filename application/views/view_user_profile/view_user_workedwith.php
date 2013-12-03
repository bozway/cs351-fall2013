<div id="workedwith_container" class="worked_with hidden-phone">
		<ul class="user_profile">
			<?php if (count($worked_with)>0) {  ?>
				<?php for($i=0;$i<count($worked_with);$i++){?> 
					<?php if($worked_with[$i]['user_id'] != $cur_userId) { ?>
				<li>
					<img src="<?php
                            if(isset($worked_with) && isset($worked_with[$i]['profile_image'])){
                                echo $worked_with[$i]['profile_image'];
                            }
                        ?>"/>
					<a class="user-img-hover" href="<?php 
						if(isset($worked_with) && isset($worked_with[$i]['user_fullname'])){
							echo base_url().'users/profile/'.$worked_with[$i]['user_id'];
                        } ?>" ></a>
					<p>
						<span class="member_fullname">
							<?php
                                if(isset($worked_with) && isset($worked_with[$i]['user_fullname'])){
                                    echo $worked_with[$i]['user_fullname'];
                                } ?>

						</span>
						<span id="view_projects" class="user_projects"><a href="<?php 
						if(isset($worked_with) && isset($worked_with[$i]['user_fullname'])){
							echo base_url().'projects/profile/'.$worked_with[$i]['project_id'];
                        } ?>
                        ">View Projects</a></span>
					</p>
				</li>
					<?php }?>
			<?php } 
			} else { 
				if ($cur_userId != $loggedinUser) { ?>
					<div class="nodata">
			        	<p>This user hasn't worked with anyone yet!<br>
			        		Why not <a class="invite_workwith" >invite them</a> to one of your projects?</p>
        			</div>
					
			<?php	} else { 	?> 
					<div class="nodata">
			        	<p>You haven't worked with anyone yet!<br>Why not <a href="<?php echo base_url('projects/search')?>">join a project</a>?</p>
        			</div>
					
				
				
		<?php	} } ?>
			
			
		</ul>
</div>

<div class="visible-phone">
    <?php if (count($worked_with)>0) {  ?>
        <?php for($i=0;$i<count($worked_with);$i++){?>
            <?php if($worked_with[$i]['user_id'] != $cur_userId) { ?>
                    <a href="<?php echo base_url().'users/profile/'.$worked_with[$i]['user_id']; ?>">
                    <img class="working_with_btn" src="<?php
                    if(isset($worked_with) && isset($worked_with[$i]['profile_image'])){
                        echo $worked_with[$i]['profile_image'];
                    }
                    ?>"/> </a>
            <?php }?>
        <?php }
    } else {
        if ($cur_userId != $loggedinUser) { ?>
            <div class="nodata">
                <p>This user hasn't worked with anyone yet!<br>
                    Why not <a class="invite_workwith" >invite them</a> to one of your projects?</p>
            </div>

        <?php	} else { 	?>
            <div class="nodata">
                <p>You haven't worked with anyone yet!<br>Why not <a href="<?php echo base_url('projects/search')?>">join a project</a>?</p>
            </div>
        <?php	} } ?>

</div>