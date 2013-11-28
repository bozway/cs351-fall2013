<div id="search_project_container"> 
	<div id="bannerWrapper">
	    <div id="searchBanner" class="container">
	       <p id="bannerText">I’m searching for a project...</p> 
           <div id="searchBar" >
           	 
				<div id="searchBy" class="fms_dropdown_container" data-selected="Skills">
	                <p ><span>Skills</span> <span class="fms_caret"></span></p>  

	                <ul  class="fms_dropdown_menu" >
	                    <li data-search-id="1">Skills</li>
	                    <li data-search-id="2">Project Name</li>
	                    <li data-search-id="3">Influences</li>
	                    <li data-search-id="4">Genres</li>
	                    <li data-search-id="4">Project Tags</li>
	                </ul>
	            </div>		       
		              <input  id="textextinput" type="text" placeholder="Type here and press enter...|">
		              <input  id="normalinput"  type="text" placeholder="Type here and press enter...|">
		              <button id="generalSearch" class="btn btn-huge" type="button"><i class="fui-arrow-right"></i></button>
		   </div>                      
	      </div>
    </div>
   
    <div id="searchBody" class="container">
		<div class="row">
			<?php echo $view_search_filters;?>
		      <div id="i2" class="span9">
		      	<div id="result_header">
		      		<span id="result_count"></span>
		      		<a name="top"></a>
					<div id="sortBy" class="fms_dropdown_container dropdown01">
		                <p><span>Sort By </span> <span class="fms_caret"></span></p>  
		                <ul  class="fms_dropdown_menu">
		                    <li data-search-id="1">Last Active</li>
		                    <li data-search-id="2">Name</li>
		                    <li data-search-id="3">Create Date</li>
		                    <li data-search-id="4">State</li>
		                </ul>
		            </div>			      		
		      	</div>
		      	<p id="result_header_placeholder">Featured Results</p>
		      	<div id="project_search_result">                
                </div>
		      	<a href="#top"><button class="back_to_top">BACK TO TOP</button></a>                      	
		      </div>
		</div>
    </div>
	  
</div>