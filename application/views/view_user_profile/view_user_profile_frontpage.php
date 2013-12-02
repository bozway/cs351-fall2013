<div id="user_profile_frontpage" class="hidden-phone">
	<div class="fms-user-coverphoto">
		<img src="<?php echo $cover_photo_path?>"></img>
	</div>
    <div id="user_profile_body" class="row"> 
		<div class="fms-page-sidebar span3">
			<div class="profile-picture-container">
				<img class="profile-picture" src="<?php echo $profile_img_path?>"></img>
			</div>
			<ul class="social-link">
				<?php if(isset($facebook) && !empty($facebook['link'])){?>
					<a href="<?php echo $facebook['link']?>"><li class="facebook"><img src="/img/facebook.png"> /<?php echo $facebook['name']?></li></a>
				<?php }?>
				<?php if(isset($twitter) && !empty($twitter['link'])) {?>
					<a href="<?php echo $twitter['link']?>"><li class="twitter"><img src="/img/twitter1.png"> @<?php echo $twitter['name']?></li></a>
				<?php }?>
				<?php if(isset($soundcloud) && !empty($soundcloud['link'])){?>
					<a href="<?php echo $soundcloud['link']?>"><li class="soundcloud"><img src="/img/sound-cloud.png"> /<?php echo $soundcloud['name']?></li></a>
                <?php } ?>
			</ul>
			<?php if (isset($management_title)) { ?>
			<p class="role">
				<?php echo $management_title; ?>
			</p>
			<?php } ?>
			<ul id="hotlink" class="hot-link">				
            	<?php if($agent['contentLength'] > 0){?>
				<li>
					<p>Agent<span class="fui-arrow-right"></span></p>
					<div>
                    	
                            <?php if(isset($agent['name'])){ ?>
								<span><?php echo $agent['name']?></span><br/>
                            <?php }	?>
                            <?php if(isset($agent['email'])){ ?>
                            	<span><?php echo $agent['email']?></span><br/>
                            <?php }	?>
							<?php if(isset($agent['phone'])){ ?>
                            	<span><?php echo $agent['phone']?></span>
                            <?php }	?>
					</div>
				</li>
                <?php } ?>
                <?php if($manager['contentLength'] > 0){?>
				<li>
					<p>Manager<span class="fui-arrow-right"></span></p>
					<div>
                    		<?php if(isset($manager['name'])){ ?>
                    	    	<span><?php echo $manager['name']?></span><br/>
                            <?php }	?>
                            <?php if(isset($manager['email'])){ ?>
                            	<span><?php echo $manager['email']?></span><br/>
                            <?php }	?>
                            <?php if(isset($manager['phone'])){ ?>
                            	<span><?php echo $manager['phone']?></span>
                            <?php }	?>
                        
					</div>
				</li>
                <?php } ?>
                <?php if($booking['contentLength'] > 0){?>
				<li>
					<p>Booking<span class="fui-arrow-right"></span></p>
					<div>
                            <?php if(isset($booking['name'])){ ?>
                            	<span><?php echo $booking['name']?></span><br/>
                            <?php }	?>
                            <?php if(isset($booking['name'])){ ?>
                            	<span><?php echo $booking['email']?></span><br/>
                            <?php }	?>
                            <?php if(isset($booking['name'])){ ?>
                            	<span><?php echo $booking['phone']?></span>
                            <?php }	?>
					</div>
				</li>
                <?php } ?>
                <?php if($publisher['contentLength'] > 0){?>
				<li>
					<p>Publisher<span class="fui-arrow-right"></span></p>
					<div>
                            <?php if(isset($publisher['name'])){ ?>
                            	<span><?php echo $publisher['name']?></span><br/>
                            <?php }	?>
                            <?php if(isset($publisher['email'])){ ?>
                            	<span><?php echo $publisher['email']?></span><br/>
                            <?php }	?>
                            <?php if(isset($publisher['phone'])){ ?>
                            	<span><?php echo $publisher['phone']?></span>
                            <?php }	?>
					</div>
				</li>
                <?php } ?>
                <?php if($recordlabel['contentLength'] > 0){?>
				<li>
					<p>Record Label<span class="fui-arrow-right"></span></p>
					<div>
                            <?php if(isset($recordlabel['name'])){?>
                            	<span><?php echo $recordlabel['name']?></span><br/>
                            <?php }	?>
                            <?php if(isset($recordlabel['website'])){?>
                            	<span><?php echo $recordlabel['website']?></span>
                            <?php }	?>
					</div>
				</li>
                <?php } ?>
			</ul>

		</div>

		<div class="fms-page-content span9">
		
			<div class="user_info">
				<div class="basic_info">
					<p class="fullname"><?php echo $namefirst?> <?php echo $namelast?></p>
					<p class="country"><i class="flag flag-<?php echo (isset($usercountrycode)) ? $usercountrycode : 'none'; ?>"> </i><span class="city"><?php echo ($city) ? $city . ', ' : ""; ?></span> <span><?php echo ($country) ? $country : "United States"; ?></span></p>
				</div>									
				<div id="followers" class="social-info <?php if($followers === 0) echo "hidden";?>">
					<p class="social_count"><?php echo $followers?></p>
					<p class="social_text">Followers</p>
				</div>
						
				<div id="likes" class="social-info <?php if($likes === 0) echo "hidden";?>">
					<p class="social_count"><?php echo $likes?></p>
					<p class="social_text">Likes</p>
				</div>

				
			</div>
			<ul class="options">
                                <?php $save = ''; 
                                    $save = ($ifInContact) ? 0 : 1;
                                    $action = ($save) ? 'Save' : 'Unsave';
                                    ?>
			<?php if($userid != $loggedinUser){ ?>
				<li><button id="favorite" data-toggle="<?= $save; ?>" data-btn="con" data-userid="<?= $userid ?>" class="btn btn-success btn-embossed" type="button"><?= $action; ?> Contact</button></li>
				<li><button id="message" class="btn btn-block btn-embossed" type="button" data-userid="<?php echo $userid ?>" data-btn="msg" data-msgrecipient="userid"><i class="fui-mail"></i>Message</button></li>
				<li><button id="Invite" class="btn btn-block inactive btn-embossed" type="button" data-userid="<?php echo $userid ?>" data-btn="inv" data-invrecipient="userid"><i class="fui-plus"></i>Invite</button></li>
			<?php } ?>
			</ul>
			
			<ul class="spotlight">
			<?php foreach($spotlight as $row) {?>
				<li>
					<span class="spotlight-play fui-play" data-url="<?php echo $row['link'] ?>"></span>
					<span class="spotlight-name"><?php echo $row['title']?></span>
					<span class="spotlight-time"><?php echo $row['length']?></span>
				</li>
			<?php }?>
			</ul>
			
			<div class="nav-wrap">
				<ul id="navigation" class="nav-bar">
					<input name="" type="hidden" value="<?php echo $userid ?>" id="id" />
					<li class="nav-tab active" id="projects">Project Portfolio</li>
					<li class="nav-tab" id="biography">Biography</li>
					<li class="nav-tab" id="workedwith">Worked With</li>
					<li class="nav-tab" id="skills">Skills</li>
				</ul>
			</div>

			<div id="detail_container">
				<?php echo $view_project_listing;?>				
			</div>


            Vince Fong


            What Does The Dog ...


            Everyone love what does the fox say, so I want to make a song what does the dog ...




            Thomas Honeyman


            The City feat. Dyl...


            The City feat. Dylan ByrdThe City is a song t...
		</div>

	</div>
        </div>
    </div>
</div>

<!-- CSCI code -->

<!-- CS 351 CODE -->
<div class="visible-phone" id="loginBar">
    <span id="backImg" data-toggle="collapse" data-target="#search-collapse-form"><img src="<?php echo base_url('img/musician_profile/arrow.png'); ?>"></span>
    <span id="backToResultsText">Back to results</span>
    <button class="btn btn-success btn-large
            " type="button" id="contactButton">Contact</button>
</div>



<div id="mobile_name" class="visible-phone">
    <div id="mobile_name_text">
        <?php echo $namefirst?> <?php echo $namelast?> <br>
        <?php echo ($city) ? $city . ', ' : ""; ?><?php echo ($country) ? $country : "United States"; ?>
    </div>
    <img id="mobile_profile_pic" src="<?php echo $profile_img_path?>" />
</div>



<div id="mobile_user_social" class="visible-phone">
    <a id="fb_btn" href="http://www.facebook.com">
        <img src="<?php echo base_url('img/musician_profile/fb_button.png'); ?>" />
    </a>



    <a id="twt_btn" href="http://www.twitter.com">
        <img src="<?php echo base_url('img/musician_profile/twitter_button.png'); ?>" />
    </a>


    <a id="yt_btn" href="http://www.youtube.com">
        <img src="<?php echo base_url('img/musician_profile/youtube_button.png'); ?>" />
    </a>


</div>


<div id="spotlight_container" class="container visible-phone">
    <div class="mp_bar visible-phone">
        <div class="mp_text_div">
                <span class="mp_text">
                    Spotlight
                </span>
        </div>
    </div>

    <?php foreach($spotlight as $row) {?>
        <div class="spotlight_showcase visible-phone">
            <img class="spotlight-play" src="<?php echo base_url('img/musician_profile/play_spotlight.png'); ?>" data-url="<?php echo $row['link'] ?>" />
            <span class="showcase_name"><?php echo $row['title']?></span>
        </div>

        <div class="spotlight_break visible-phone"></div>
    <?php }?>

    <div id="profile_info" class="visible-phone">
        <div id="mobile_bio">
                <span class="mobile_bio_header">
                   </br> BIOGRAPHY </br>
                </span>
                <span class="mobile_bio_body">
                    </br>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Curabitur a cursus lorem, nec adipiscing est. Suspendisse adipiscing ac
                    urna non facilisis. Sed ligula sapien, viverra gravida aliquet et,
                    vehicula et diam. Nullam placerat condimentum volutpat. Maecenas
                    fermentum commodo imperdiet. Pellentesque habitant morbi tristique senectus
                    et netus et malesuada fames ac turpis...
                    </br>
                </span>
                <span class="mobile_bio_header">
                    </br>
                    INFLUENCES </br>
                </span>
                <span class="mobile_bio_body">
                    </br>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Curabitur a cursus lorem, nec adipsicing est. </br>
                    </br>
                    <a href="">Show more...</a>
                </span>
        </div>


    </div>

    <div id="show_portfolio" class="visible-phone">
        <div id="show_portfolio_div">
            <a id="show_portfolio_text" href="www.findmysong.com"> Show Portfolio </a>
        </div>
    </div>
    <div class="span6 visible-phone">
        <img class="show_portfolio_img" src="http://placehold.it/280x280" />
        <img class="show_portfolio_img" src="http://placehold.it/280x280" />
        <img class="show_portfolio_img" src="http://placehold.it/280x280" />
        <img class="show_portfolio_img" src="http://placehold.it/280x280" />
    </div>
</div>

<div id="skills_container" class="visible-phone">
    <div class="mp_bar">
        <div class="mp_text_div">
                <span class="mp_text">
                    Skills
                </span>
        </div>
    </div>

    <div id="skills_bar">
        <img class="skills_btn" src="<?php echo base_url('img/musician_profile/guitar.png'); ?>" />
        <img class="skills_btn" src="<?php echo base_url('img/musician_profile/keyboard.png'); ?>" />
        <img class="skills_btn" src="<?php echo base_url('img/musician_profile/microphone.png'); ?>" />
        <img class="skills_btn" src="<?php echo base_url('img/musician_profile/sax.png'); ?>" />
    </div>
</div>

<div id="working_with_container" class="visible-phone">
    <div class="mp_bar">
        <div class="mp_text_div">
                <span class="mp_text">
                    Working with
                </span>
        </div>
    </div>

    <div id="working_with_bar">
        <a href="<?php echo base_url().'users/profile/16'; ?>">
            <img class="working_with_btn" src="http://www.cs351.gurtem.com/img/default_avatar_photo.jpg" />
        </a>
        <a href="<?php echo base_url().'users/profile/18'; ?>">
            <img class="working_with_btn" src="http://www.cs351.gurtem.com/img/default_avatar_photo.jpg" />
        </a>
        <a href="<?php echo base_url().'users/profile/24'; ?>">
            <img class="working_with_btn" src="http://www.cs351.gurtem.com/img/default_avatar_photo.jpg" />
        </a>
        <a href="<?php echo base_url().'users/profile/26'; ?>">
            <img class="working_with_btn" src="http://www.cs351.gurtem.com/img/default_avatar_photo.jpg" />
        </a>
    </div>

    <div id="back_to_top_bar">
        <div id="back_to_top_div">
            <span id="back_to_top_text">Back to top</span>
        </div>
    </div>

</div>

<!-- End of CSCI 351 code -->