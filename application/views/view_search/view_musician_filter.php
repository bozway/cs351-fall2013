<div id="i1" class="span3">
 <?php if ($bool_showMyProjects) : ?>
  	<div id ="my_project" class="title">
  		My Project Needs
  	</div>
    <hr class="shortLine">
    <div id="select_project" class="fms_dropdown_container dropdown01" data-select-inactive="0">
    	<p><span>Select project</span><span class="fms_caret"></span></p>  
        <ul class="fms_dropdown_menu">
            <?php foreach ($projects as $project) { ?>
                <li data-projectId="<?= $project['projectId'] ?>"><?= $project['projectName'] ?></li>
            <?php } ?>
        </ul>
    </div>
    
    <div id="select_skill" class="fms_dropdown_container dropdown01 inactive" data-select-inactive="1">
        <p><span>Please Select Project </span><span class="fms_caret"></span></p>
        <ul id = "skillSelect" class="fms_dropdown_menu">
        </ul>		
    </div>
    <div id = "hiddenSkills">	
        <?php foreach ($skills as $projectId => $projectSkills) { ?>
            <div id = "projectId<?= $projectId ?>">
                <?php foreach ($projectSkills as $skill) { ?>	
                    <li data-skillId="<?= $skill['skillId'] ?>" data-projectSkillId="<?= $skill['id'] ?>" data-categoryId="<?= $skill['categoryId'] ?>" ><?= $skill['skillName'] ?></li>
                <?php } ?>
            </div>
        <?php } ?>
    </div>	
	<div id="skill_style" class="hide">
		<div id="filter_genre">
	            <p class="filter_title"> <span>Genres</span><span id="clear_genre" class="clear">Clear All</span></p>
	            <ul id="genreUL" class="filter_tag_container">
	
	            </ul>
	    </div>        
	    <div id = "hiddenGenres">
	        <?php foreach ($skillGenres as $id => $genres) { ?>
	            <div id = "genreSkillId<?= $id ?>">
	                <?php foreach ($genres as $genre) { ?>
	                    <p><span class="first-icon fui-checkbox-checked selected" data-genreId="<?= $genre['id'] ?>"></span><span class="style_text"><?= $genre['name'] ?></span></p>
	                <?php } ?>					
	            </div>
	        <?php } ?>		
	    </div>
	    <div id="filter_influence">
	        <p class="filter_title"> <span>Influences</span><span id="clear_influence" class="clear">Clear All</span></p>
	        <ul id = "influenceUL" class="filter_tag_container">
	
	        </ul>
	    </div>
	    <div id = "hiddenInfluences">
	        <?php foreach ($skillInfluences as $id => $influences) { ?>
	            <div id = "influenceSkillId<?= $id ?>">
	                <?php foreach ($influences as $influence) { ?>
	                    <p><span class="first-icon fui-checkbox-checked selected" data-influenceId="<?= $influence['id'] ?>"></span><span class="style_text"><?= $influence['name'] ?></span></p>
	                <?php } ?>					
	            </div>
	        <?php } ?>		
	    </div>
	</div>        	 	    
    <a  id="searchByProjectNeed" class="btn btn-block btn-primary btn-embossed mlm">Search</a>
 <?php endif; ?>
 
   	<div id ="advanced_filter" class="title">
  		Advanced Filters
  	</div>
    <hr class="shortLine">
    

  	<div class="project_exp_title">Project Experience:</div>    
      
	  <div id="project_experience" class="ui-slider">
	    <span class="ui-slider-value first">0</span>
	    <span class="ui-slider-value last">45</span>
	  </div> 

	<div id="dropdown_language" class="fms_dropdown_container dropdown01">
        <p ><span id="language">Language</span> <span class="fms_caret"></span></p>  

        <ul  class="fms_dropdown_menu" >
            <li data-search-id="1">English</li>
            <li data-search-id="2">Spanish</li>
            <li data-search-id="3">Chinese</li>
        </ul>
    </div>		  

    <div id="dropdown_state" class="fms_dropdown_container dropdown01" data-select-inactive="0">
    	<p><span id="country">States</span><span class="fms_caret"></span></p>  
        <ul id="slimScroll" class="fms_dropdown_menu">
            <?php foreach ($states as $state) { ?>
                <li data-id="<?= $state->getAbbreviatedName() ?>"><?= $state->getFullName() ?></li>
            <?php } ?>
        </ul>
    </div>	  
    
    <input id="zipcode" type="text" value="" placeholder="Enter city or zip code" class="span3">

  	<div class="project_exp_title">Gender :</div>
  	<div id="genderdiv">
     <label class="radio" data-value="0">
        <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" data-toggle="radio">
        Both
      </label> 		
      <label class="radio" data-value="1">
        <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2" data-toggle="radio">
        Male
      </label>
      <label class="radio" data-value="2">
        <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3" data-toggle="radio" >
        Female
      </label>  	
    </div>  

      <a  id="apply_filter" class="btn btn-block btn-primary btn-embossed mlm">Apply Filters</a>      
  	
</div>


