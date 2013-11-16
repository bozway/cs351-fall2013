<div id="biography_container" class="biography">
    <div id="biography_bio" class="biography-content">
    	<?php if ($userbiography) {  ?>
			<div class="text">
					<?php echo $userbiography; ?>
		        </div>
		<?php } else {
			  if ($cur_userId != $loggedinUser) { ?>
					<div class="nodata">
				        	<p>This user has no biography！<br>
				        		Why not try <a class="sending_message">sending a message</a>?</p>
				    </div>
		       	<?php	} else { 	?> 
		       		<div class="nodata">
			        	<p>You haven't created a biography yet!<br>Why not <a href="<?php echo base_url('/dashboard/profile/biography')?>">write one</a>?</p>
        			</div>
		       		
		<?php } }?>
    </div>
</div>
