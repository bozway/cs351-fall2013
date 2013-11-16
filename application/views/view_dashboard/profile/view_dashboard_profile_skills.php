<div id="profile_skills" class="container <?php if($active_panel == Profile::SKILLS){echo 'active-panel';}?>">

	<div class="define-header">
		<div class="container">
			<p class="span10">
				Edit Your Skills
			</p>
			<p class="span7">
				Add skills to your profile so other musicians know what you're
				good at. You can add up to 10 skills.  Providing extra details for each skill 					
				including video proof, up to 5 genres, and up to 5 influences helps  other musicians find you!
			</p>
		</div>
		<div class="save-btn-container span3">
			<button id="save_skills" class="btn btn-large btn-block btn-success btn-embossed">Save Changes</button>
		</div>
	</div>

	<div id="DragandDrop" class="container">
		<p class="firstsp span9">
			Drag and Drop to Rearrange
		</p>
		<div class="span3">
			
            <form class="form-search">
            <div class="input-append">
              <input type="text" class="span2 search-query" placeholder="Search Skills..." id="skillSpecifier">
              <button type="submit" class="btn"><span class="fui-search"></span></button>
            </div>
          </form>
		</div>
	</div>
	<div id="skill_column"></div>
</div>
