<div id="project_portfolio" class="container <?php if($active_panel == Profile::PORTFOLIO){echo 'active-panel';}?>">

	    <div class="define-header">
      <div class="container">
      <p class="span10">Your FMS Portfolio</p>
      <p class="span7">You can choose up to 12 projects to display on your personal profile.</p></div>
      <div class="save-btn-container span3">
      	<button id="save-project-portfolio" class="btn btn-large btn-block btn-success btn-embossed">Save Changes</button>
      </div>
    </div>
	
    	 <p class="first-of-type">These are your projects. Select your top 12, and drag to rearrange!</p>
         
         <div class="arrow-container">
			<div class="projects-profile-arrow" id="projects_header_arrow"></div>
		</div>
     <div class="black_bg" >   
     <div id="projects_container_after" >
		<ul id="project_list_after" class="row">
        	
        	 <?php
	        	 $visible_projects = array ();
				 $other_projects = array ();
				
	        	 foreach ( $user_project_ranking as $row ) {
					if($row[1]->getRanking()>0 && $row[1]->getRanking()<13 && $row[1]->getVisibility()==1){
						 $visible_projects [] = $row[1];
					}else{
						$other_projects[]= $row[1];
					}
	        	 }
	        	 usort ( $visible_projects, function ($a, $b) {
	        	 	if ($a->getRanking () == $b->getRanking ())
	        	 		return 0;
	        	 	return ($a->getRanking () < $b->getRanking ()) ? - 1 : 1;
	        	 } );
		        $num = 0;
		        foreach ($visible_projects as $row){ 
					$project = $row->getProject();
			?>
	            	<li class="span3" data-project-id="<?php echo $project->getId(); ?>"  data-name="<?php echo $project->getName(); ?>"data-times="<?php if($project->getCreationTime()){
						echo strtotime($project->getCreationTime()->format('Y/m/d'));
						}?>">
            
					<img src="<?php  
						$photo = $project->getPhoto();
						if($photo) echo base_url($photo->getPath() . $photo->getName());
		            ?>" width="220" height="230">
					<a class="project-img-hover" target="_blank" href="<?php echo base_url('projects/profile/'.$project->getId()); ?>"></a>
					<p>
						<span class="project-title"><?php echo $project->getName(); ?></span>
						<a class="project-link" href="<?php echo base_url('projects/profile/'.$project->getId()); ?>"></a>
						<span class="project-length"></span>
						<span class="fui-play project_play" data-url="<?php 
							$projectAudio = $project->getFiles();
							if($projectAudio) {
								foreach($projectAudio as $audio) {
									if($audio->getType() == 0 && $audio->getSubtype() == 1) {
										echo base_url($audio->getPath() . $audio->getName());
									}
								}
							}
						?>"></span>
					</p>
		            <audio >
						<source src="">
					</audio>
            <?php }?>
            
            <?php if(count($user_project_ranking) == 0) {?>
	            <div class="span12 ul_none_list">
	        		<p>You have no projects! <a href="<?php echo base_url('dashboard/project/create_basic')?>">Create One?</a></p>
	        	</div>
        	<?php } else {?>
	            <li id="add_project" class="span3" style="height:224px; background-color:transparent;">
	                <a class="add_first" href="<?php echo base_url('dashboard/project/create_basic');?>">Add Another</a>
	                <a href="<?php echo base_url('dashboard/project/create_basic');?>">Project</a>
	            </li>
            <?php } ?>
			</ul>
	     </div>
	</div>
	<div id="projects" class="container" data-user-id="">
		<div class="middle row">
        <div class="mostrecent span3">
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
           <div class="span9">
           <p>Drag a project to add it to your top 12. You can filter by most recent or alphabetically</p>
           </div>
         </div>
        
        
         <div id="projects_container_before">   
         	<?php if(count($user_project_ranking) == 0) {?>
	            <div class="ul_none_list lightbox">
	        		<p>Do you need <a href="<?php echo base_url('help')?>">help?</a> Visit our Help Center to find out more.</p>
	        	</div>
	    	<?php } ?>
	    	
	    	<ul id="project_list_before" class="row">
         	<?php  foreach ($other_projects as $row){ 
					$project = $row->getProject();
			?>
        	<li class="span3" data-project-id="<?php echo $project->getId(); ?>"  data-name="<?php echo $project->getName(); ?>"data-times="<?php if($project->getCreationTime()){
				echo strtotime($project->getCreationTime()->format('Y/m/d'));
				}?>" >
					<img src="<?php  
						$photo = $project->getPhoto();
						if($photo) echo base_url($photo->getPath() . $photo->getName());
		            ?>" width="220" height="230">
					<a class="project-img-hover" target="_blank" href="<?php echo base_url('projects/profile/'.$project->getId()); ?>"></a>
					<p>
						<span class="project-title"><?php echo $project->getName(); ?></span>
						<a class="project-link" href="<?php echo base_url('projects/profile/'.$project->getId()); ?>"></a>
						<span class="project-length"></span>
						<span class="fui-play project_play" data-url="<?php 
							$projectAudio = $project->getFiles();
							if($projectAudio) {
								foreach($projectAudio as $audio) {
									if($audio->getType() == 0 && $audio->getSubtype() == 1) {
										echo base_url($audio->getPath() . $audio->getName());
									}
								}
							}
						?>"></span>
					</p>
		            <audio>
						<source src="">
					</audio>
            <?php }?>
			</li>
          
        </ul>
        </div>
         	
   </div>     
   
   
</div>