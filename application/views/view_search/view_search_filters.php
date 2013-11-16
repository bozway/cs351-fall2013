<div id="i1" class="span3">
 <?php if ($bool_showMySkills) : ?>
  	<div id ="my_project" class="title">
  		Search My Skills
  	</div>
    <hr class="shortLine">
    <div id="select_skill" class="fms_dropdown_container dropdown01" data-select-inactive="0">
    	<p><span>Select Skill</span><span class="fms_caret"></span></p>  
        <ul class="fms_dropdown_menu">
            <?php foreach ($userSkills as $userSkill) { ?>
                <li data-userskillid="<?= $userSkill['id']?>" data-skillid="<?= $userSkill['skillid']?>" data-categoryid="<?= $userSkill['categoryid']?>"><?= $userSkill['name'] ?></li>
            <?php } ?>
        </ul>
    </div>
	<div id="skill_style" class="hide">
		<div id="filter_genre">
	            <p class="filter_title"> <span>Genres</span><span id="clear_genre" class="clear">Clear All</span></p>
	            <ul id="genreUL" class="filter_tag_container">
	
	            </ul>
	    </div>        
	    <div id = "hiddenGenres">
	        <?php foreach ($userGenres as $id => $genres) { ?>
	            <div id = "genreSkillId<?= $id ?>">
	                <?php foreach ($genres as $genre) { ?>
	                    <p><span class="second-icon fui-checkbox-checked selected" data-genreId="<?= $genre['id'] ?>"></span><span class="style_text"><?= $genre['name'] ?></span></p>
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
	        <?php foreach ($userInfluences as $id => $influences) { ?>
	            <div id = "influenceSkillId<?= $id ?>">
	                <?php foreach ($influences as $influence) { ?>
	                    <p><span class="second-icon fui-checkbox-checked selected" data-influenceId="<?= $influence['id'] ?>"></span><span class="style_text"><?= $influence['name'] ?></span></p>
	                <?php } ?>					
	            </div>
	        <?php } ?>		
	    </div>
	</div>        	 	    
    <a  id="searchByMySkill" class="btn btn-block btn-primary btn-embossed mlm">Search</a>
 <?php endif; ?>
 
   	<div id ="advanced_filter" class="title">
  		Advanced Filters
  	</div>
    <hr class="shortLine">
  	<!--<div class="project_exp_title">Project Experience:</div>  -->  
	  
	<div id="dropdown_language" class="fms_dropdown_container dropdown01">
        <p ><span id="language">Language</span> <span class="fms_caret"></span></p>  

        <ul  class="fms_dropdown_menu" >
            <li data-id="1">English</li>
            <li data-id="2">Spanish</li>
            <li data-id="3">Chinese</li>
        </ul>
    </div>	
  
	<div id="dropdown_duration" class="fms_dropdown_container dropdown01">
        <p ><span>Duration </span> <span class="fms_caret"></span></p>  

        <ul  class="fms_dropdown_menu" >
            <li data-id="1">1 month</li>
            <li data-id="2">2 month</li>
            <li data-id="3">3 month</li>
            <li data-id="4">4 month</li>
            <li data-id="5">5 month</li>
            <li data-id="6">6 month</li>            
        </ul>
    </div>	    	  

    <div id="state_dropdown" class="fms_dropdown_container dropdown01" data-select-inactive="0">
    	<p><span id='state' >State</span><span class="fms_caret"></span></p>  
        <ul id="slimScroll" class="fms_dropdown_menu">
            <?php foreach ($states as $state) { ?>
                <li data-id="<?= $state->getAbbreviatedName() ?>"><?= $state->getFullName() ?></li>
            <?php } ?>
        </ul>
    </div>	  
    
    <input id="zipcode" type="text" value="" placeholder="Enter city or zip code" class="span3">
    
  	<div class="project_exp_title">Audio Preview:</div>   
	
 
	<p><span id="audio_preview_check_box" class="second-icon fui-checkbox-checked"></span><span id="audio_previe_text" class="style_text">Audio Preview Required</span></p>  	

  	
      <a  id="apply_filter" class="btn btn-block btn-primary btn-embossed mlm">Apply Filters</a>      
  	
</div>


