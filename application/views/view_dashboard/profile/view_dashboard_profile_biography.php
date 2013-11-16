<form>
		<div class="container define-header">
  <div class="container">
      <p class="span10">Biography</p>
      <p class="span7">Your biography is your chance to tell the world your story.  What inspires you to make music? Who are your biggest influences? Let other musicians know what you’re all about.</p>
      
    </div>
    <div class="save-btn-container span3">
    	<button id="save_biography" type="button"  class="btn btn-large btn-block btn-success btn-embossed">Save Changes</button>
	</div>
	</div>
	<div id ="hr" style="width: 940px; height: 20px;"> </div>
	
	<div id="biography">
	    <textarea id="user-biography" style="width: 940px; height: 500px;"><?php $biography = $user->getBiography(); if($biography) echo $biography;else echo "Write your biography here..."; ?></textarea>
	</div>
</form>
