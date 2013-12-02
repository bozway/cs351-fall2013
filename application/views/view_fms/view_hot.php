<div id="whatshot" class="hidden-phone">
	<div class="default-header" id="whatshot-header">
		<div class="container">Welcome back, <?php echo $userfirstname; ?>!</div>
	</div>
	<div id="whatshot">
		<!-- Quick Links -->
		<div id="whatshot-content-quicklinks" class="whatshot-row">
			<div class="triangle"></div>
			<div id="quicklinks-colorbg"></div>
			<div id="quicklinks-content" class="container">
				<span class="whatshot-content-title">Quick Links</span>
				<ul id="quicklinks-list">
					<li	class="quicklinks-list-item">
						<a href="<?php echo base_url("dashboard/profile/basicsettings");?>" class="quicklinks-link">
							<?php echo img('img/home_page/icon_complete_profile.png')?>
							<span class="quicklinks-link-title">Complete Your Profile</span>
						</a>
					</li>					
					<li	class="quicklinks-list-item">
						<a href="<?php echo base_url("dashboard/project/create_basic");?>" class="quicklinks-link">
							<?php echo img('img/home_page/icon_create_project.png')?>
							<span class="quicklinks-link-title">Create a Project</span>
						</a>
					</li>					
					<li	class="quicklinks-list-item">
						<a href="<?php echo base_url("dashboard/project/manage");?>" class="quicklinks-link">
							<?php echo img('img/home_page/icon_manage_project.png')?>
							<span class="quicklinks-link-title">Manage Your Projects</span>
						</a>
					</li>					
					<li	class="quicklinks-list-item">
						<a href="<?php echo base_url("users/search");?>" class="quicklinks-link">
							<?php echo img('img/home_page/icon_discover_people.png')?>
							<span class="quicklinks-link-title">Discover People</span>
						</a>
					</li>
					<li	class="quicklinks-list-item">
						<a href="<?php echo base_url("projects/search");?>" class="quicklinks-link">
							<?php echo img('img/home_page/icon_discover_projects.png')?>
							<span class="quicklinks-link-title">Discover Projects</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<!-- Popular People -->
		<div id="whatshot-content-pop-people" class="whatshot-row">
			<div id="pop-people-colorbg"></div>
			<div id="pop-people-content" class="container">
				<span class="whatshot-content-title">Popular People</span>
				<ul id="pop-people-list">
					<?php foreach($musicians as $musician) { ?>
					<li class="pop-people-list-item">
						<img class="profile-pic" src="<?php echo $musician['imgpath']; ?>"/>
						<a href="<?php echo base_url().'users/profile/'.$musician['id']; ?>"></a>
					</li>
					<?php } ?>	
				</ul>
			</div>
		</div>
		<!--  Popular Projects -->
		<div id="whatshot-content-pop-projects" class="whatshot-row">
			<div id="pop-projects-colorbg"></div>
			<div id="pop-projects-content" class="container">
				<span class="whatshot-content-title">Popular Projects</span>
				<ul id="pop-projects-list">
					<?php foreach($projects as $project) { ?>
					<li class="pop-project-list-item span3">						
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
		<!-- Trending Tags -->
		<!--  Popular Projects -->
		<div id="whatshot-content-trending" class="whatshot-row">
			<div id="trending-colorbg"></div>
			<div id="trending-content" class="container">
				<span class="whatshot-content-supertitle">Trending</span>
				<div class="tagblock span4">
					<span class="whatshot-content-title">Tags</span>
					<ul id="tags-list">
						<?php foreach($tags as $tag) { ?>
						<li class="tag"><?php echo $tag; ?></li>
						<?php } ?>	
					</ul>
				</div>
				<div class="tagblock span4">
					<span class="whatshot-content-title">Genres</span>
					<ul id="tags-list">
						<?php foreach($genres as $genre) { ?>
						<li class="tag"><?php echo $genre; ?></li>
						<?php } ?>	
					</ul>
				</div>
				<div class="tagblock span4">
					<span class="whatshot-content-title">Influences</span>
					<ul id="tags-list">
						<?php foreach($influences as $influence) { ?>
						<li class="tag"><?php echo $influence; ?></li>
						<?php } ?>	
					</ul>
				</div>
			</div>
		</div>
	</div>

</div>

<!-- CSCI 351 code -->
<div  id="welcomeBar" class="container visible-phone">
    <span id="welcomeText">Welcome back.</span>
    <span id="magGlass" data-toggle="collapse" data-target="#search-collapse-form"><img src="<?php echo base_url('img/whatshot/searchicon.png');?>"/></span>
</div>

<div id="search-collapse-div" class="visible-phone" >
    <form id="search-collapse-form" class="collapse">
        <input id="search-collapse-text" type="text" placeholder="Search FindMySong"/>
    </form>
</div>

<div id="popMusicians" class="visible-phone">
    <div id="musicianContent">
        <p class="home_contact_top_title">Popular Musicians <a href="http://www.gurtem.com/fms_351/projects/search">(See All)</a></p>
        <ul id="pop-musicians-list" class="carousel">

            <li class="pop-project-list-item" id="card1" >
                <img class="musimg img-rounded" src="<?php echo base_url('img/whatshot/c1.png');?>"/>

            </li>
            <li class="pop-project-list-item" id="card1" >
                <img class="musimg img-rounded" src="<?php echo base_url('img/whatshot/c2.png');?>"/>

            </li>
            <li class="pop-project-list-item" id="card1" >
                <img class="musimg img-rounded" src="<?php echo base_url('img/whatshot/c3.png');?>"/>

            </li>
            <li class="pop-project-list-item" id="card1" >
                <img class="musimg img-rounded" src="<?php echo base_url('img/whatshot/c4.png');?>"/>

            </li>
            <li class="pop-project-list-item" id="card1" >
                <img class="musimg img-rounded" src="<?php echo base_url('img/whatshot/c1.png');?>"/>

            </li>
        </ul>
    </div>
</div>

<div id="featured-projects" class="span6 visible-phone" >
    <div id="background">
        <div id="projectContent">
            <p class="home_contact_top_title">Most liked Projects <a href="http://www.gurtem.com/fms_351/projects/search">(See All)</a></p>

            <ul id="pop-projects-list" class="carousel">

                <li class="pop-project-list-item span3" id="card" >
                    <img class="projimg span3" src="<?php echo base_url('img/whatshot/project1.png');?>"/>
                    <a class="projlink" href="http://www.gurtem.com/fms_351/projects/profile/109"></a>
                    <div class="projinfo">
						<p class="gap">&nbsp;<br></p>
                        <span class="projowner">Vince Fong</span>
						<p class="gap">&nbsp;<br></p>
                        <span class="projtitle">What Does The Dog ...</span>
						<p class="gap">&nbsp;<br></p>
                        <p class="projdesc">Everyone love what does the fox say, so I want to make a song what does the dog ...</p>
                    </div>

                </li>
                <li class="pop-project-list-item span3" id="card">
                    <img class="projimg span3" src="<?php echo base_url('img/whatshot/project1.png');?>"/>
                    <a class="projlink" href="http://www.gurtem.com/fms_351/projects/profile/110"></a>
                    <div class="projinfo">
						<p class="gap">&nbsp;<br></p>
                        <span class="projowner">Thomas Honeyman</span>
						<p class="gap">&nbsp;<br></p>
                        <span class="projtitle">The City feat. Dyl...</span>
						<p class="gap">&nbsp;<br></p>
                        <p class="projdesc">The City feat. Dylan ByrdThe City is a song t...</p>
                    </div>

                </li>
                <li class="pop-project-list-item span3" id="card">
                    <img class="projimg span3" src="<?php echo base_url('img/whatshot/project1.png');?>"/>
                    <a class="projlink" href="http://www.gurtem.com/fms_351/projects/profile/118"></a>
                    <a class="projlink" href="http://www.gurtem.com/fms_351/projects/profile/118"></a>
                    <div class="projinfo">
						<p class="gap">&nbsp;<br></p>
                        <span class="projowner">Thomas Honeyman</span>
						<p class="gap">&nbsp;<br></p>
                        <span class="projtitle">Open by Rhye</span>
						<p class="gap">&nbsp;<br></p>
                        <p class="projdesc">Rhye - Open             I've been working on ...</p>
                    </div>

                </li>
                <li class="pop-project-list-item span3" id="card">
                    <img class="projimg span3" src="<?php echo base_url('img/whatshot/project1.png');?>"/>
                    <a class="projlink" href="http://www.gurtem.com/fms_351/projects/profile/121"></a>
                    <div class="projinfo">
						<p class="gap">&nbsp;<br></p>
                        <span class="projowner">Thomas Honeyman</span>
						<p class="gap">&nbsp;<br></p>
                        <span class="projtitle">Sunday Morning</span>
						<p class="gap">&nbsp;<br></p>
                        <p class="projdesc">Sunday Morning - Maroon 5Maroon 5's Songs Abo...</p>
                    </div>

                </li>


            </ul>
        </div>
    </div>
</div>

<div id="influences" class="visible-phone">
    <div id="infContent">
        <p class="home_contact_top_title">Influences</p>
        <div class="infDiv1">&nbsp;</div><div class="infDiv2">&nbsp;</div><div class="infDiv3">&nbsp;</div>
        <div class="infDiv2">&nbsp;</div><div class="infDiv3">&nbsp;</div><div class="infDiv1">&nbsp;</div>
        <div class="infDiv2">&nbsp;</div><div class="infDiv1">&nbsp;</div><div class="infDiv3">&nbsp;</div>
    </div>
</div>
<div id="genres" class="visible-phone">
    <div id="genContent">
        <p class="home_contact_top_title">Genres</p>
        <div class="infDiv1">&nbsp;</div><div class="infDiv2">&nbsp;</div><div class="infDiv3">&nbsp;</div>
        <div class="infDiv2">&nbsp;</div><div class="infDiv3">&nbsp;</div><div class="infDiv1">&nbsp;</div>
        <div class="infDiv2">&nbsp;</div><div class="infDiv1">&nbsp;</div><div class="infDiv3">&nbsp;</div>
    </div>
</div>
<!-- END OF 351 -->