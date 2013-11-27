<div id="home_page">
	<div class="home_header hidden-phone">
    	<div id="home_header_top">
        	<div class="home_bg_images_bg">
                <div id="home-bg-images">
                    <img data-id="1" class="home-bg-color" src="/img/home_page/fms_home_slide_1.jpg">
                    <img data-id="2" class="home-bg-color" src="/img/home_page/fms_home_slide_2.jpg">
                    <img data-id="3" class="home-bg-color" src="/img/home_page/fms_home_slide_3.jpg">
                    <img data-id="4" class="home-bg-color" src="/img/home_page/fms_home_slide_4.jpg">
                    
                </div>
             
			</div>
            <div class="home_header_text_container">
                <div class="home_header_links">
                    <ul>
                        <li ><a data-id="1" class="slider-link">Network</a></li>
                        <li ><a data-id="2" class="slider-link">Portfolio</a></li>
                        <li ><a data-id="3" class="slider-link">Projects</a></li>
                        <li ><a data-id="4" class="slider-link">Contracts</a></li>
                    </ul>
                </div>
                <div data-id="1" class="home_header_text network_text text_select">
                    <p class="header_title">FMS is a new way to network with musicians</p>
                    <p>We're a home to musicians, songwriters, producers, engineers, fans, and people passionate about creating music all over the world.</p>
                </div>
                <div data-id="2" class="home_header_text portfolio_text">
                    <p class="header_title">FMS is a place to showcase your music</p>
                    <p>We believe music is meant to be shared. That's why every artist on FMS gets an expertly-designed portfolio that shows the world who you are.</p>
                </div>
                <div data-id="3" class="home_header_text projects_text">
                    <p class="header_title">FMS helps creators make music anywhere</p>
                    <p>We created Projects to help you create music efficiently. Save your professional network as contacts, audition new musicians, and share your music with fans.</p>
                </div>
                <div data-id="4" class="home_header_text contracts_text">
                    <p class="header_title">FMS keeps your work safe with simple splits</p>
                    <p>The music industry's legal system is notoriously complex. Keep your work safe and avoid legal disputes with FindMySong contracts.</p>
                </div>
              </div>
        </div>
        
        <a href="#fms-home-video-container" role="button" data-toggle="modal">
        	<div class="home_header_play_btn">
	        	<div class="home_header_play_btn_bg">
	            	<i class="fui-triangle-right-large"></i>
	            </div>
            </div>
        </a>

        <!-- related to youtube video - disabled for now 
        	<div id="fms-home-video-container" class="modal hide fade" tabindex="-1" 
        	role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		</div> -->
		
        <div class="home_header_bottom">
        	<p><span>FindMySong</span> brings musicians together all over the world to network, collaborate and create.</p>
            <i class="fui-triangle-down-small"></i>
        </div>
    </div>

    <!-- CS 351 CODE -->
    <div id="video_container" class="container visible-phone">
        <div  id="loginBar">
            <span id="searchImg" data-toggle="collapse" data-target="#search-collapse-form"><img src="<?php echo base_url('img/home_page/search.jpg'); ?>"></span>
            <span id="loginText">LOG IN</span>
            <button class="btn btn-success btn-large
            " type="button" id="signUpButton">Sign up</button>
        </div>

        <div id="search-collapse-div" >
            <form id="search-collapse-form" class="collapse">
                <input id="search-collapse-text" type="text" placeholder="Search FindMySong"/>
            </form>
        </div>

        <!-- Play Video Modal -->
        <div id="play_video_modal" class="modal hide fade" tabindex="-1" role="dialog">
            <button type="button" class="close" data-dismiss="modal">X</button>
            <div class="modal-body">
                <object width="100%" height="350">
                    <param name="movie" value="//www.youtube.com/v/dQw4w9WgXcQ?version=3&amp;hl=en_US&amp;rel=0"></param>
                    <param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param>
                    <embed src="//www.youtube.com/v/dQw4w9WgXcQ?version=3&amp;hl=en_US&amp;rel=0" type="application/x-shockwave-flash" width="100%" height="350" allowscriptaccess="always" allowfullscreen="true">
                    </embed>
                </object>
            </div>
        </div>

        <p id="vidText" class="lead">
            FMS<br>
            HELPS<br>
            CREATORS<br>
            MAKE MUSIC<br>
            FROM<br>
            <span id="anywhere">ANYWHERE.</span>
        </p>

        <div id="playVidBar" class="row-fluid">
            <span>
                Play the video!
            </span>
            <div class="playIconDiv"><a href="#play_video_modal" role="button" data-toggle="modal">
                    <img id="playIcon" src="<?php echo base_url('img/home_page/play_vid_icon.png'); ?>"/></div>
            </a>




        </div>

    </div>
    <!-- END OF CS 351 CODE -->
    
    <div class="home_contact">
    	<div class="home_contact_top">
			<div class="row">
            	<div id="featured-musicians" class="span6">
                	<p class="home_contact_top_title">Featured Musicians <a href="<?php echo base_url().'users/search' ?>">(See All)</a></p>
                    <?php foreach($featured_musicians as $musician){ ?>
                    <div class="featured_musicians">

                    	<a href="<?php echo base_url().'users/profile/'.$musician['id']; ?>">

                    	</a>
                        <img src="<?php echo $musician['profile_img_path']; ?>"/>
                    </div>
					<?php } ?>
                </div>
                <div id="featured-projects" class="span6">
                    <p class="home_contact_top_title">Featured Projects <a href="<?php echo base_url().'projects/search' ?>">(See All)</a></p>
                        
                        <ul id="pop-projects-list" class="carousel">
							<?php foreach($featured_projects as $project) { ?>
							<li class="pop-project-list-item span3" id="card">
								<img class="projimg span3" src="<?php echo $project['imgpath']; ?>"/>
								<a class="projlink" href="<?php echo base_url().'projects/profile/'.$project['id']; ?>"></a>
								<div class="projinfo">
									<span class="projowner"><?php echo $project['projowner']; ?></span>
									<span class="projtitle"><?php echo $project['projtitle']; ?></span>
									<p class="projdesc"><?php echo $project['projdesc']; ?></p>
								</div>					
							
							</li>
							<?php } ?>	
						</ul>
                </div>
            </div>
        </div>
        <div class="home_contact_middle_bg">
            <div class="home_contact_middle">
            	<div id="home_contact_container">
                    <p class="visible-phone"><img  src="<?php echo base_url().'img/home_page/footer2.png' ?>" width="100%" height="auto"/></p>
	                <div class="home_contact_middle_left">
	                    <p class="title">Music is better with friends.</p>
	                    <p class="hidden-phone">Get social on FindMySong. Help your friends keep updated on your new music by connecting with Facebook, and follow along with your favorite artists as they work on new projects.</p>
                        <p class="visible-phone">Help your friends keep updated on your new music by connecting with Facebook.</p>
                        <button id="fb_btn" data-gated="1" class="btn btn-small btn-block sign-in-fb">Connect with Facebook</button>
	                </div>
	                <div class="home_contact_middle_right hidden-phone">
	                    <p><span>Bring</span><br />your music to life</p>
	                    <p>Every project on FindMySong is an independent musical creation by someone like you. </p>
	                    <p><a href="<?= base_url('login') ?>" data-gated="1" >Ready to get started?</a></p>
	                </div>
            	</div>
            </div>
        </div>
    </div>
</div>