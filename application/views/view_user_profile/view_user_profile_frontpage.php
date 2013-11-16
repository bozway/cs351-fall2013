<div id="user_profile_frontpage">
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
		</div>

	</div>
        </div>
    </div>
</div>