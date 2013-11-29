<div id="search_musicians_container"> 
	<div id="bannerWrapper">
	    <div id="searchBanner" class="container hidden-phone">
	       <p id="bannerText">I’m searching for a musician...</p> 
           <div id="searchBar" >
				<div id="searchBy" class="fms_dropdown_container" data-selected="Skills">
	                <p ><span>Skills</span> <span class="fms_caret"></span></p>  
					<ul  class="fms_dropdown_menu" >
	                    <li data-search-id="1">Skills</li>
	                    <li data-search-id="2">Name</li>
	                    <li data-search-id="3">Influences</li>
	                    <li data-search-id="4">Genres</li>
	                </ul>
	            </div>		         
		              <input  id="textextinput" type="text" placeholder="Type here and press enter...|">
		              <input  id="normalinput"  type="text" placeholder="Type here and press enter...|">
		              <button id="generalSearch" class="btn btn-huge" type="button"><i class="fui-arrow-right"></i></button>
		   </div>                      
	      </div>
		  <!--mobile version of banner-->
		  <div class="search visible-phone"  id="top" >
	  
			<input class="textbox" type="text" name="enter" placeholder="Type here...">
		
			<div class="btn-group dropDown">
			<button type="button" class="btn btn-info dropdown-toggle filter" data-toggle="dropdown">

				Filter <span class="caret"></span>
			</button>
			<ul class="dropdown-menu pull-right" role="menu">
	            <li data-search-id="1">Skills</li>
                <li data-search-id="2">Name</li>
	            <li data-search-id="3">Influences</li>
	            <li data-search-id="4">Genres</li>
				<li class="divider"></li>
				<li><a href="#">Separated link</a></li>
			</ul>
		    </div>
	        </div>
			<!-- mobile version of banner ends here-->
    </div>
   
    <div id="searchBody" class="container">
		<div class="row">
			<?php echo $sidebar;?>
		      <div id="i2" class="span9">   
		      	<div id="result_header" style="display:none">
		      		<span id="result_count"></span>
		      		<a name="top"></a>
					<div id="sortBy" class="fms_dropdown_container dropdown01">
		                <p ><span>Sort By </span> <span class="fms_caret"></span></p>  
						<ul  class="fms_dropdown_menu" >
		                    <li data-search-id="1">Last Active</li>
		                    <li data-search-id="2">Name</li>
		                    <li data-search-id="3">Experience</li>		                    
		                </ul>
		            </div>			      		
		      	</div>
		      	<p id="result_header_placeholder" class="hidden-phone">Featured Results</p>
				<!--mobile version of body-->
				<div class="row visible-phone">  
			<div class="span8">  
			<ul id="myTab" class="nav nav-pills nav-justified">  
			 <li><a href="#project" class="tabFont">&nbsp<br>Projects</a></li>     
			 <li class="active"><a href="#musicians" class="tabFont">&nbsp<br>Musicians</a></li>  
			</ul>  
			</div> 
				
			<div class="tab-content">
			<!--Project tab content-->
			<div class="tab-pane" id="project">
			<div id="slideProj">&nbsp<br>&nbsp&nbspSlide project to audition</div>
			<div class="project_div">
				<table class="projectTable">
					<tr>
						<td class="pTWidth">
						<a href="#"><img src="lib/img/p1.png" class="img-rounded"></img></a>
						</td>
						<td>
						<p id="projname">Paper Walls</p>
						<p id="place">Los Angeles, CA</p>
						<p id="members">2 Members / Completed</p>
						</td>
					</tr>
			
				</table>
			</div>
			<div class="project_div">
				<table class="projectTable">
					<tr>
						<td class="pTWidth">
						<a href="#"><img src="lib/img/p2.png" class="img-rounded"></img></a>
						</td>
						<td>
						<p id="projname">Paper Walls cover with Annie Hall</p>
						<p id="place">Los Angeles, CA</p>
						<p id="members">3 Members / <em> Auditioning</em></p>
						</td>
					</tr>
			
				</table>
			</div>
			<div class="project_div">
				<table class="projectTable">
					<tr>
						<td class="pTWidth">
						<a href="#"><img src="lib/img/p3.png" class="img-rounded"></img></a>
						</td>
						<td>
						<p id="projname">Paper Walls</p>
						<p id="place">Los Angeles, CA</p>
						<p id="members">2 Members / Completed</p>
						</td>
					</tr>
			
				</table>
			</div>
			<div class="project_div">
				<table class="projectTable">
					<tr>
						<td class="pTWidth">
						<a href="#"><img src="lib/img/p4.png" class="img-rounded"></img></a>
						</td>
						<td>
						<p id="projname">Paper Walls cover with Annie Hall</p>
						<p id="place">Los Angeles, CA</p>
						<p id="members">3 Members / <em> Auditioning</em></p>
						</td>
					</tr>
			
				</table>
			</div>
			</div>
			<!--Musicians tab content-->
			<div class="tab-pane active" id="musicians">
			<div id="slideMusic">&nbsp<br>&nbsp&nbspSlide musician to contact</div>
				<div class="musician_div">
				<table class="musicianTable">
					<tr>
						<td>
						<p id="musicname">Thomas Honeyman</p>
						<p id="place">Los Angeles, CA</p>
						<p id="members">4 projects/ Last active 10/01/2013</p>
						</td>
						<td class="mTRight">
						
						<a href="#"><img src="lib/img/m1.png" class="img-rounded"></img></a>
						</td>
					</tr>
			
				</table>
			</div>
			<div class="musician_div">
				<table class="musicianTable">
					<tr>
						<td>
						<p id="musicname">Thomas Honeyman</p>
						<p id="place">Los Angeles, CA</p>
						<p id="members">4 projects/ Last active 10/01/2013</p>
						</td>
						<td class="mTRight">
						
						<a href="#"><img src="lib/img/m1.png" class="img-rounded"></img></a>
						</td>
					</tr>
			
				</table>
			</div>
			<div class="musician_div">
				<table class="musicianTable">
					<tr>
						<td>
						<p id="musicname">Thomas Honeyman</p>
						<p id="place">Los Angeles, CA</p>
						<p id="members">4 projects/ Last active 10/01/2013</p>
						</td>
						<td class="mTRight">
						
						<a href="#"><img src="lib/img/m1.png" class="img-rounded"></img></a>
						</td>
					</tr>
			
				</table>
			</div>
			<div class="musician_div">
				<table class="musicianTable">
					<tr>
						<td>
						<p id="musicname">Thomas Honeyman</p>
						<p id="place">Los Angeles, CA</p>
						<p id="members">4 projects/ Last active 10/01/2013</p>
						</td>
						<td  class="mTRight">
						
						<a href="#"><img src="lib/img/m1.png" class="img-rounded"></img></a>
						</td>
					</tr>
			
				</table>
			</div>
			<div class="musician_div">
				<table class="musicianTable">
					<tr>
						<td>
						<p id="musicname">Thomas Honeyman</p>
						<p id="place">Los Angeles, CA</p>
						<p id="members">4 projects/ Last active 10/01/2013</p>
						</td>
						<td class="mTRight">
						
						<a href="#"><img src="lib/img/m1.png" class="img-rounded"></img></a>
						</td>
					</tr>
			
				</table>
			</div>
			</div>
			</div>
		</div>
				<!--mobile version of body ends here-->
		      	<div id="musician_search_result">
		      		
		      	</div>   
		      	<a href="#top"><button class="back_to_top">BACK TO TOP</button></a>	
		      </div>
		</div>
    </div>
	  
</div>
