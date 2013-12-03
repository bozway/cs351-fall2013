<div id="about_container" class="hidden-phone" >
	<div class="about_header_bg">
        <div class="about_header">
            <div class="about_header_top container">
                <p>Find My Song</p>
                <p>is changing how people</p>
                <p>around the world connect with music</p>
                <a type="button" class="btn btn-large btn-block btn-danger" data-gated="1" >
                    Get Started Now!
                    <i class="fui-arrow-right pull-right"></i>
                </a>
            </div>
            <div class="about_header_bottom">
            	<h3 class="title container">Our mission is to bring creative music to life wherever YOU are.</h3>
            </div>
        </div>
    </div>
	<div class="about_content">
    	<div class="row">
        	<div class="span12">
                <div>                    
                    <p class="subtitle">FindMySong brings musicians from all over the world closer together. Guided by the belief that musicians deserve better technology to help them achieve success, we focus on clarity, simplicity, and quality to provide the best online collaborative platform for music in the world.  FindMySong is hand-crafted by musicians in Los Angeles to help musicians network, collaborate on projects, and work safely with simple contracts to reach greater success in the music business. It’s a strong, supportive community online built by musicians, for musicians.</p>
                </div>
                
                <div>
                    <h3 class="first">1. FMS is a new way to network with other talented creators</h3>
                    <p>We’re a home for musicians, songwriters, producers, engineers, fans, and people passionate about creating music from all over the world.  Singers in Nashville can collaborate with guitarists in London.  Producers in New York can discover songwriters from Atlanta or Minneapolis. Our search engines are crafted specifically for musicians to find their best creative matches, whether at home or anywhere in the world.</p>
                </div>
                
                <div>
                    <h3>2. FMS is a place to showcase your musical work</h3>
                    <p>We believe that every artist deserves a well-designed portfolio to show the world who they are and what they’ve accomplished.  That’s why every person on FindMySong gets an expertly-crafted personal profile, and that’s why every project gets its own showcase to be displayed in your personal portfolio.  Music is meant to be shared, and we built FindMySong to help people around the world discover you and your music.</p>
                </div>
                
                <div>
                    <h3>3. FMS helps remove location as a barrier to making music</h3>
                    <p>We put talented creators together, wherever you are.  But that’s not enough to break down the walls that stop creative work online.  That’s why we created FindMySong projects.  A project is a new home for your work - a place to keep everything you need organized while you work. Save your friends as contacts, and keep your professional connections looped in on your new projects.  Audition new people to make sure they’re the perfect fit. Keep a permanent archive of old projects so you never lose them. And above all, have fun!</p>
                </div>
                
                <div>
                    <h3>4. FMS keeps you safe with simple, personal contracts - no 360º deals here</h3>
                    <p>The music industry is notoriously complex, frustrating, and prone to taking advantage of artists.  Bypass the gatekeepers and the profiteers by managing your own contracts in FindMySong. Keep 100% ownership of your works, or decide how much you want to give to your fellow artists.</p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- CSCI 351 code -->
<div class="wrapper visible-phone">
    <a id="pageTop">&nbsp;</a>

    <div id="myCarousel" class="carousel slide visible-phone">
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
            <li data-target="#myCarousel" data-slide-to="3"></li>
        </ol>
        <!-- Carousel items -->
        <div class="carousel-inner">
            <div class="active item"><img src="<?php echo base_url('img/about/aboutUs.png'); ?>" alt="" /></div>
            <div class="item"><img src="<?php echo base_url('img/about/aboutUs4.png'); ?>" alt="" /></div>
            <div class="item"><img src="<?php echo base_url('img/about/aboutUs2.png'); ?>" alt="" /></div>
            <div class="item"><img src="<?php echo base_url('img/about/aboutUs3.png'); ?>" alt="" /></div>
        </div>
    </div>
    <!-- Carousel nav -->
    <div  id="loginBar" class="visible-phone">
        <div id="searchImg" data-toggle="collapse" data-target="#search-collapse-form"><img src="<?php echo base_url('img/about/search.png'); ?>"/></div>
        <a href="<?php echo base_url('/login'); ?>"><span id="loginText">LOG IN</span></a>
        <a href="<?php echo base_url('/mobile_signup'); ?>">
        <button class="btn btn-success btn-large
            " type="button" id="signUpButton">Sign up</button>
            </a>
    </div>

    <div id="search-collapse-div" class="visible-phone">
        <form id="search-collapse-form" class="collapse">
            <input id="search-collapse-text" type="text" placeholder="Search FindMySong"/>
        </form>
    </div>
    <div id='mission' class="visible-phone">
        <h3><em>FindMySong</em></h3>
        <h3> Its purpose, its life, its mission!</h3>
        <p> Gaining a foothold in the music industry is hard. Making it and having a music career is even harder.
            And getting the big break that we all <em>(secretly)</em> hope for, seems nearly impossible.</p>
        <p> Maybe you cannot get access to the right means to produce your song; maybe you're missing that one
            connection to your success or maybe you want to advertise your music, but you can't figure out how.
        </p>
        <p> Being musicians ourselves, we have run into many challenges that every now and then left us feeling
            discouraged, questioning our talent and wishing someone to be there to understand what we were going
            through.
        </p>
        <p> Because of that we created FindMySong, an online platform that allows musicians from all over the
            world to connect and network with other musicians.
        </p>


        <!-- Play Video Modal -->
        <div id="play_video_modal" class="modal hide fade visible-phone" tabindex="-1" role="dialog">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <div class="modal-body">
                <object width="100%" height="350">
                    <param name="movie" value="//www.youtube.com/v/dQw4w9WgXcQ?version=3&amp;hl=en_US&amp;rel=0" />
                    <param name="allowFullScreen" value="true"/><param name="allowscriptaccess" value="always" />
                    <embed src="//www.youtube.com/v/dQw4w9WgXcQ?version=3&amp;hl=en_US&amp;rel=0" type="application/x-shockwave-flash" width="100%" height="350" allowscriptaccess="always" allowfullscreen="true">
                    </embed>
                </object>
            </div>
        </div>

        <div class="visible-phone" style="position: relative; left: 0; top: 0;">
            <a href="#play_video_modal" role="button" data-toggle="modal">
                <img src="<?php echo base_url('img/about/play_video.png'); ?>" height="auto" alt="..." id="play"/>
            </a>
        </div>



    </div>
    <div id='mission2' class="visible-phone">
        <p>FindMySong made it its mission to give its users and opportunity to form <em>mutually beneficial relationships
                and to collaborate on projects,</em> while providing that often times much needed support-system.
        </p>
        <div><img src="<?php echo base_url('img/about/frame.png'); ?>" width="100%"/></div>
        <p>
            <br>
            FindMySong's community understands the struggles you face and <em>supports you on your way to success.</em> Join Findmysong now,
            create your profile, upload your songs and make new ones with others, work on projects and network with like-minded people and watch
            friends from all over the world listen to your music, rate it, buy it and love it.
        </p>
    </div>

    <div id="benefit" class="visible-phone">
        <table>
            <tr>
                <td>
                    <button class="btn btn-success" type="button">Sign up</button>
                    <br>
                    <br>
                    <button class="btn btn-inverse" type="button">Get in touch</button>
                    <br>
                    <br>
                    <br>
                    <em class="followUs">Follow us:</em>
                    <br>
                    <br>
                    <button class="btn btn-primary ftbutton" type="button"><img class="bImg" src="<?php echo base_url('img/about/fbwhite.png'); ?>"/>Like us on Facebook</button>
                    <br>
                    <br>
                    <button class="btn btn-info ftbutton" type="button"><img class="bImg" src="<?php echo base_url('img/about/twwhite.png'); ?>"/>Follow us on Twitter</button>
                </td>
                <td id='benefit_desc'>
                    <h4>FMS - its benefits</h4>
                    <h6>Using <em>FindMySong,</em> you can:</h6>
                    <p>Create and customize your professional profile. <br><br> Upload your own songs. <br><br>
                        Contact and connect with other musicians worldwide. <br><br>
                        Rate and comment on songs.<br><br> Receive and give feedback.<br><br> Work on your as well as other
                        people's projects.<br><br> Ask questions, get answers and communicate in a professional forum.<br><br>
                        Showcase your talent.<br><br>Manage your own copyright.<br><br>Establish a name for yourself in the
                        industry!
                    </p>

                </td>

            </tr>
        </table>
    </div>

    <div id="footer1" class="visible-phone">
        &nbsp;<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Sign up &nbsp;</em>Don't have an account yet? Sign up to get started!
        <button class="btn signInFt" type="button">Sign up!</button>
    </div>
    <div id="footer2" class="visible-phone">
        <table class="footerTable">
            <tr>
                <th>FindmySong.com</th>
                <th> FMS</th>
                <th> Legal</th>
                <th>Follow us</th>
            </tr>
            <tr>
                <td> Copyright &copy 2013</td>
                <td> About<br> Contact<br> Help Center</td>
                <td> Terms<br> Privacy</td>
                <td> <img class="fb2" src="<?php echo base_url('img/about/fbIcon.png'); ?>"/><img class="tw2" src="<?php echo base_url('img/about/twIcon.png'); ?>"/></td>
            </tr>
        </table>
    </div>
    <div class="push visible-phone"></div>
</div>
<div id="footer3" class="visible-phone">
    &nbsp;<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#pageTop">Top
        <img class="topIcon" src="<?php echo base_url('img/about/arrow.png'); ?>"/></a>
</div>
<!-- End of 351 -->