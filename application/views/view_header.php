<!DOCTYPE html>
<html >
<head>
	<?php if ($title === 'Home') :?>
	<title>Find My Song</title>
	<?php else :?>
	<title><?php echo $title; ?> | Find My Song</title>	
	<?php endif; ?>
		<?php echo '<script>var pageTitle = ' . json_encode($title) . ';</script>'; ?>
		<link rel="shortcut icon" href="<?php echo site_url();?>img/favicon.ico" />
	<?php 
		echo link_tag(array('href'=>'css/bootstrap.min.css', 'rel'=>'stylesheet', 'media'=>'screen'))."\r\n\t";		
		echo link_tag(array('href'=>'css/flat-ui.css', 'rel'=>'stylesheet', 'media'=>'screen'))."\r\n\t";
		echo '<link href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900" rel="stylesheet" type="text/css">';		
		if (isset($font_ref)) {
			foreach ($font_ref as $font) {
				echo link_tag(array('href'=>'http://fonts.googleapis.com/css?family='.$font,'rel'=>'stylesheet', 'type'=>'text/css'))."\r\n\t";
			}		
		}		 
		if (isset($css_ref)) {
			foreach ($css_ref as $css) {
				echo link_tag(array('href'=>$css, 'rel'=>'stylesheet', 'media'=>'screen'))."\r\n\t"; 			}
			
		} else {
			echo link_tag(array('href'=>'css/main.css', 'rel'=>'stylesheet', 'media'=>'screen'))."\r\n\t";			
		}	
		echo link_tag(array('href'=>'css/popups/fms_modals.css', 'rel'=>'stylesheet', 'media'=>'screen'))."\r\n\t";
		echo link_tag(array('href'=>'css/fms_icons.css', 'rel'=>'stylesheet', 'media'=>'screen'))."\r\n\t";
		?><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><?php
		if (isset($metadata)) {
			echo meta($metadata);
		}
		//echo meta('viewport', 'width=device-width, initial-scale=1.0');
		
	?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>

<?php if(isset($show_navigation)){  ?>
	<div class="freeze-space">
		<div class="dashboard-header-bg"></div>
	</div>
	<div class="fms-header">
<?php } ?>	
	<header>
		<div class="colorbg grey header hidden-phone"></div>
		<div class="container hidden-phone">
			<div class="fms-home">
				<a id="header-logo"	href="<?php 
				if($authenticated){
					echo base_url().'hot';
				}else{
					echo base_url();
				}?>" class="home-link">
					<?php echo img('img/logo/fms_HeaderLogo_beta.png')?>
				</a>
			</div>
			<div class="seperator hidden-phone"></div>
			<div class="fms-explore hidden-phone <?php if(!$authenticated){ echo "guest"; }?>">
				<?php if($authenticated) {?>
					<div class="member">
						<a href="<?php echo base_url('dashboard/profile')?>">
							<label class="dashboard-icon fmsicon-profile"></label>
						</a>
					</div>
					<div class="member">
						<a href="<?php echo base_url('dashboard/project')?>">
							<label class="dashboard-icon fmsicon-project"></label>
						</a>
					</div>
					<div class="member">
						<a href="<?php echo base_url('dashboard/message')?>">
							<label class="dashboard-icon fmsicon-message"></label>
							<label id="unread_thread_num"></label>
						</a>
					</div>
				<?php }else{?>
					<div class="about guest"><a href="<?php echo base_url('about')?>" data-id="about_fms">About FMS</a></div>
					<div class="guest"><a href="<?php echo base_url('users/search')?>" data-id="musician_search">Search Musicians</a></div>
					<div class="guest"><a href="<?php echo base_url('projects/search')?>" data-id="project_search">Search Projects</a></div>
				<?php } ?>
			</div>
			
			<div class="fms-member hidden-phone">
				<div class="seperator last"></div>
				<div class="fms-user-portal<?php if($authenticated) {echo " member";}else {echo " guest";}?>">
					<?php if($authenticated) {?>
						<div class="show-fms-user-menu-header">
							<label class="fms-user-settings">Me <?php echo img('img/trigon_white_bottom.png')?></label>
							<div id= "fms-user-settings-menu" class="tooltip fade bottom in tooltip-light">
								<div class="tooltip-arrow"></div>
								<div class="tooltip-inner">
									<ul class="user-settings-options">
										<li class="bottom-border"><a href="<?= base_url('users/profile/' . $userId)?>">View My Profile</a></li>
										<li class="bottom-border"><a href="#">Visit the Blog</a></li>
										<li class="bottom-border"><a href="<?= base_url('help')?>">Help Center</a></li>
										<li><a href="<?= base_url('dashboard/account')?>">My Settings</a></li>
									</ul>
									<div class="fms-user-logout"><a href="<?= base_url('logout')?>"><?php echo img('img/logout.png')?> Log Out</a></div>
								</div>
							</div>
						</div>
						<!-- </label> -->
						<a href="<?= base_url('users/profile/' . $userId)?>">
							<img class="member-profile-img" src="<?php if (isset($profile_img_path)) { echo base_url($profile_img_path);} ?>"></img>
						</a>
					<?php }else{ ?>
						<a href="<?= base_url('login')?>" data-gated="1" data-linktype="1">Log in / 
						</a><span data-gated="1"> Signup</span>
					<?php } ?>
				</div>	
			</div>
			
			<div class="show-fms-search-header hidden-phone <?php if($authenticated) { echo " member";}?>">
				<label class="fms-search<?php if($authenticated) { echo " member";}?>"></label>
				<div id="init-search" class="tooltip fade bottom in tooltip-light">
					<div class="tooltip-arrow"></div>
					<div class="tooltip-inner">
						<span class="fms-header-search"><a href="<?php echo base_url('users/search')?>">Musicians</a></span> | 
						<span class="fms-header-search"><a href="<?php echo base_url('projects/search')?>">Projects</a></span>
					</div>
				</div>
			</div>
			
		</div>

        <!-- CSCI 351 -->
        <div class="navbar navbar-inverse navbar-fixed-top visible-phone">
            <div class="navbar-inner visible-phone">
                <div class="container visible-phone">

                    <div>
                        <?php
                        if($authenticated){
                            ?>
                            <a href="<?= base_url('users/profile/' . $userId)?>">
                                <img class="mobile-member-profile-img" src="<?php if (isset($profile_img_path)) { echo base_url($profile_img_path);} ?>"></img>
                            </a>
                        <?php
                        }
                        ?>
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    </button>

                    </div>


                    <a class="brand" href="<?php echo base_url(); ?>">
                        <img src="<?php echo base_url('img/mobile_fms_icon.png'); ?>">
                        <span class="icon-bar"></span>
                        <strong>FindmySong</strong>

                    </a>



                    <div class="nav-collapse collapse visible-phone">
                        <ul class="nav">
                            <li><a href="<?php
                                if($authenticated){
                                    echo base_url().'hot';
                                }else{
                                    echo base_url();
                                }?>">Home</a></li>
                            <li><a href="<?php echo base_url('about'); ?>">About Us</a></li>
                            <li><a href="<?php echo base_url('users/search'); ?>">Search Musicians</a></li>
                            <li><a href="<?php echo base_url('projects/search'); ?>">Search Projects</a></li>
                            <li><a href="<?php
                                if($authenticated){
                                    echo base_url().'logout';
                                }else{
                                    echo base_url().'login';
                                }?>">
                                <?php
                                    if($authenticated){
                                        echo 'Sign out';
                                    }else{
                                        echo 'Sign in';
                                    }?>
                            </a>
                            </li>
                        </ul>


                    </div><!--/.nav-collapse -->


                </div>

            </div>
        </div>

        <!-- END OF 351 -->
	</header>