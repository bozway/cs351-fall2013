<div id="projects_container">
	<ul class="project-listing">

		<?php if (count($projects)>0) {  ?>
			<?php foreach($projects as $row) {
				if($row['project_id'] !== "") {
			 ?>
				<li data-id="<?php echo $row['rank']?>">
					<img src="<?php echo $row['img']?>" />
					<a class="project-img-hover" href="<?php echo base_url().'projects/profile/'.$row['project_id'] ?>" ></a>
					<p>
						<span class="project-title"><?php echo $row['title']?></span>
						<a class="project-link" href="<?php echo $row['link']?>"></a>
						<span class="project-length"><?php echo $row['length']?></span>
						<?php if($row['audio_link']) {?>
					<span class="fui-play project_play" data-url="<?php echo $row['audio_link']?>"></span>
				<?php } ?>
					</p>
				</li>
			<?php } } } else { 
					if($userid != $loggedinUser){ ?>
				<div class="nodata">
        			<p>This user has no projects yet!<br>
        				You can <a class="invite_project" >invite them</a> to one of your projects.</p>
        		</div>
        			<?php	} else { 	?> 
        				<div class="nodata">
			        	<p>You have no projects yet!<br>Why not <a href="<?php echo base_url('dashboard/project/create_basic')?>">create one</a>?</p>
        			</div>
        				
		<?php	}  }?>

	</ul>


	<div style="clear:both"></div>
</div>