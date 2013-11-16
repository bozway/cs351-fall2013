<?php 
	require_once('application/controllers/dashboard/message.php');
?>
<div id="help_center_box">
	<div class="faq_header">
    	<p>Help Center</p>
    </div>
    <div class="faq_content">
    	<div class="row">
            <div class="faq_content_left span8">
                <div>
                    <h2 id="FMS_Essentials">FMS Essentials</h2>
                    <p>Thanks for joining the community at FindMySong!  As the fastest-growing music collaboration site on the Internet, there’s a lot of opportunity to get involved.  We want you to get the most out of it, so here’s some helpful information.  If you have trouble and your question isn’t answered here, please don’t hesitate to <a href="<?php echo base_url().'contact';?>">contact us</a>.</p>
                    <h3 id="changing_your_email">Changing Your Email</h3>
                    <p>To change your email, first go to your <a href="<?= base_url('dashboard/account')?>">Settings</a>.  Then, you can input your new email and your password to confirm.  Hit Save Changes to make the change official!  You’ll receive a confirmation email within a few minutes to make sure everything went well.</p>
                    <h3 id="changing_your_password">Changing Your Password</h3>
                    <p>Your password settings are located in your account’s <a href="<?= base_url('dashboard/account')?>">Settings</a>.  From your settings, you can input your new password and your old password to confirm.  Hit Save Changes to make the change official! You’ll receive a confirmation email within a few minutes to make sure everything went well.</p>
                    <h3 id="deactivating_your_account">Deactivating Your Account</h3>
                    <p>If you’ve decided you want to leave FindMySong, you can do that from your account’s <a href="<?= base_url('dashboard/account')?>">Settings</a>. Simply click on the large “Deactivate” button to proceed.</p>
                    <p>You can only deactivate your account if you have no active projects you are involved in.  If you have active projects, you must either leave the project or complete it.  Any unpublished projects you have will be deleted.</p>
                    <p>Your account will become completely private.  Your profile will not display in any search results. Any links to your profile will automatically redirect.  If you’d like to reactivate your account, simply login to your account!  If you’d like to delete your account, please <a href="<?php echo base_url().'contact';?>">contact us</a>.</p>
                    <h3 id="messaging">Messaging</h3>
                    <p>To send a message to another FindMySong user, simply click on the “Message” button anywhere you see them on the site.  You can message them from their profile, from your Contacts, and from within any project you share with them.</p>
                    <p>Your messages are all located in your <a href="<?php echo base_url().'dashboard/message';?>">Messaging</a>.  To send a new message to a FindMySong user, you can either click the “Message” button or you can search your contacts to message them.  All of your conversations, including both normal messages and invitations, are located in your <a href="<?php echo base_url().'dashboard/message';?>">Messaging</a>.</p>
                    <h3 id="contactd">Contacts</h3>
                    <p>Your <a href="<?=base_url('dashboard/message/'.Message::CONTACTS)?>">Contacts</a> are a list of the people you want to stay in touch with on FindMySong. You can add anyone as a contact for easy access to their profile, recent projects, and messaging.  You can save someone as a contact either from their profile or from within any project you share with them.</p>
                    <p>All of your contacts are saved together in your <a href="<?= base_url('dashboard/message/'.Message::CONTACTS)?>">Contacts</a>.  From there, you can search your contacts, message them, view their profiles, and invite them to your projects.  Alternately, you can also remove contacts.</p>
                </div>

                <div>
                    <h2 id="intro_skill">What are Skills?</h2>                    
                    <h3 id="skills_overview">Overview</h3>
                    <p>Skills are, in essence, what you are able to do.  They can be talents on instruments like drums, electric guitar, or marimba.  They can also be talents in music production like songwriting, recording engineering, or producing.</p>
                    <p>Skills help everyone on the site know at a glance what talents you have.  However, there’s a lot more to being an electric guitarist than the instrument.  You can describe each skill more in-depth by using Genres and Influences tags.</p>
                    <p><span>Genres</span>: What genres do you focus on?<br /><span>Influences</span>: What artists do you play like?</p>                    
                    <h3 id="on_your_profile">On your Profile</h3>
                    <p>Your personal profile displays what skills you have selected for yourself.  In your <a href="<?= base_url('dashboard/profile')?>">profile dashboard</a>, you can choose skills, decide whether they are public or private, and describe them using genres and influences.  </p>
                    <p>There are two more pieces of information on your profile.  First, you can embed a video of yourself playing to prove you really are good at what you do.  Next, your project experience will be shown.  This is simply a record of how many projects you have worked on as an acoustic guitarist, drummer, or other skill.</p>
                    <p>Your skills are displayed under the “Skills” tab on your profile. </p>
                  
                    <h3 id="in_your_projects">In your Projects</h3>
                    <p>Skills in your projects are a great way to make sure the right people find you.</p>
                    <p>When you create a FindMySong project, you are asked to include at least 1 skill for yourself and at least 1 skill that you are looking for.  This is where you can describe what you are doing on the project (your skill) and describe who you would like to join you.</p>
                    <p>When other users use the “Search Projects” feature, they’ll find your project based on what skills you need.  If they are a songwriter and you have listed that you need a songwriter, then you will show up in their search!</p>
                </div>
                
                <div>
                	<h2 id="">The Musician Profile</h2>	
                	<p>Your profile is your showcase on the web. It serves as a professional resume, showcasing your work and experience as well as providing information about your background, your management, and who you have worked with. You set up part of your profile in the signup process, but to finish it up you need to go to your <a href="<?= base_url('dashboard/profile')?>">Profile Dashboard</a></p>
                	<h3 id="musician_profile_basic">Basic Settings</h3>
                	<p>Your basic setting define the essential information from your profile. These include the following: </p>
                	<h3 id="musician_profile_spotlight">Spotlight</h3>
                	<p>Your spotlight lets you upload up to 3 files. These are often your best tracks, the ones that truly display your artistic talent. If you have already uploaded 3 files and would like to add another, you need to first remove one of your current files in order to replace it.</p>
                	<h3 id="musician_profile_webaddr">Web Address</h3>
                	<p>Your web address is a permanent link that allows you to share your FindMySong profile around the web and in life. Be sure of your selection - the only way to change this link is to contact customer service!</p>
                	<h3 id="musician_profile_projects">Project Portfolio</h3>
                	<p>Your portfolio displays to the world what projects you have worked on in FindMySong. You can rearrange the order of the projects by dragging them, or select the eye icon to change them from private to public or public to private</p>
                	<p>Alternatively, if you have a large number of projects, you can change to the List View. In List View, projects are displayed in a list form. You can still drag to rearrange and select which projects are visible on your profile</p>
                	<h3 id="musician_profile_biography">Biography</h3>
                	<p>You can add a descriptive biography describing yourself and your work on your profile. Use the built-in text editor to bold, align, and otherwise style your text. When you're finished, simply click "Save Changes" to make your biography live!</p>
                	<h3 id="musician_profile_skills">Skills</h3>
                	<p>To learn more about what Skills are, <a href="#intro_skill" class="faq_a">click here</a></p>
                	<h3 id="musician_profile_connect">Connect</h3>
                	<p>FindMySong lets you list your management on your profile in case other users or music industry members want to get in contact with your team. You can fill in this information in the Connect section. You are also able to list your social media links, and connect with Facebook or Twitter.</p>
                </div>
                
                <div>
                    <h2 id="create_project">Creating a Project</h2>
                    <p id="creating_projects_overview">Creating a new project is how you can get started working with other musicians from anywhere in the world.  Invite your friends from around town, or search for new talents!  While the project is active and once its completed, your project will have a profile to showcase the work.</p>
                    <h3 id="creating_projects_basic_settings">Basic Settings</h3>
                    <p>In your basic settings, you describe the essentials of your new project.  Give your project a name, and upload a project photo at least 300x300 px.  If you’re looking for local musicians, make sure to specify your location.  If you prefer only musicians who speak your language, make sure to indicate a preferred language as well!</p>
                    <p>1) Project Tags<br  />Using project tags helps people find your project.  Describe your project in tags like genres, influences, mood, and subject matter.  Then, when people enter these terms into the Search Projects page, your project will be included in the results!</p>
                    <p>2) Duration<br  />The duration selection lets you estimate how long the project will last.  Is this a quick jingle that needs to be done in a week?  Is this a songwriting project that you think will last a month?  Or is this an ongoing project to stay organized with your friends, that will last indefinitely?</p>
                    <p>3) Audio & Video Preview<br  />You can show what you’ve been working on by uploading an audio file.  As your project progresses, you can update this audio file with the newest version!  If you’ve been working on a music video, or you’d like to extend a personal appeal to people to join your project, you can include a video as well.</p>
                    <p>4) Project Description<br  />This section is the main content on your Project Profile.  Describing your project effectively makes it much more likely musicians will pay attention to what you’re up to.  It also serves as a place to keep your lyrics, explain why you wrote the piece, and include any other information about the song.</p>
                    <h3 id="adding_skills">Adding Skills</h3>
                    <p>If you’d like to learn more about what skills are, please <a href="#skills_overview" class="faq_a">click here</a>.</p>
                    <p>Adding skills to your project is a straightforward process.  You need to add at least 1 skill to yourself and at least 1 skill to your team.  You can decide whether you would like to add skills to yourself, or if you’d like to add them to “I’m looking for...” using this toggle located above the skill icons.</p>
                    <p>Each of the skill icons is a category - vocals, keyboards, percussion, bass, guitars, strings, electronic instruments, wind instruments, and music production.  When you select an icon, you are able to see what skills are in that category. Select one and it will be added to your roster!</p>
                    <h3 id="describing_skills">Describing Skills</h3>
                    <p>Describing skills is outlined in the “What Are Skills?” section!  To read more, <a href="#skills_overview" class="faq_a">click here</a>.</p>
                    <h3 id="skills_and_team">My Skills vs I’m Looking For...</h3>
                    <p>Every project has two sections for skills: the owner’s skills, and the participants’ skills. </p>
                    <p>1) Owner Skills<br  />Before a project can be published, there must be at least 1 skill assigned to the owner.  This helps other artists on the site see what talents are already being applied to the project.  It also helps them verify that the owner is proficient in their chosen skills, by taking a look at the owner’s profile.</p>
                    <p>2) I’m Looking For...<br  />Most of the time, when musicians search for projects they are searching for skills, genres, and influences to narrow the results.  In the I’m Looking For... section, you aren’t just listing what skills you need help with on your new project - you are also making your project searchable.  It’s a great idea to include as much information as you can here, because the more information you have the more likely you are to show up in search results!</p>
                    <h3 id="publishing_your_project">Publishing Your Project</h3>
                    <p>Publishing your project is the last step in getting your work out to the world!  When you publish a new project, a few things happen:</p>
                    <p>1) Your project is visible and searchable<br  />Now that your project has been published, you will receive a permanent link to the project’s public profile.  You can share that link to social media, friends, and to musicians you’d like to join your project.  Additionally, your project will now be displayed in the Search Projects results and on your personal profile under the “Project Portfolio” tab.  So long as you have at least 1 skill open, your project will be searchable!  When all of your positions are filled, your project will no longer be displayed in the Search Project results.</p>
                    <p>It’s a good idea to share your project and get active searching for musicians right after you create your new project.</p>
                    <p>2) You can now manage your project<br  />Once you have created a project, it will show up in the <a href="<?= base_url('dashboard/project/manage')?>">Manage Projects</a> view in your <a href="<?= base_url('dashboard/project')?>">Project Dashboard</a>.  From here you can edit all the settings you initially described when creating your project.  You can also manage your applicants and contracts.</p>
                </div>
                
                <div>
                    <h2 id="join_projects">Joining Projects</h2>
                    <p>When you’re ready to get started creating music on FindMySong, you can either create new projects you lead, or you can jam with other musicians on their projects.</p>
                    <p>1) Finding Projects<br  />The best way to find new projects is using the Search Projects page.  You can find this page in the header section. The Search Projects page can be used in a number of ways to find the best project for you.</p>
                    <h3 id="search_for_project">I’m Searching for a Project...</h3>
                    <p>Using the general search bar, you can search by skills, genres, influences, tags, or project names.  The dropdown on the left side of the search bar allows you to select which of these items you are searching for.</p>
                    <p>1) Searching Skills<br  />Selecting the “Skills” search restricts results to the specific skills you input.  If you search for “electric guitar”, only projects that need electric guitarists will be listed in the results.  However, you can also search for categories.  For instance, if you search for “guitar”, then not only will “electric guitar” results show up, but “acoustic guitar” and “12-string guitar” will also be included.</p>
                    <p>2) Searching Genres<br  />Selecting the “Genres” search restricts results to the specific genres you input. If you search for “rock”, then projects that have listed any open skills in the “rock” genre will be returned.  You can search for one genre or for multiple genres.</p>
                    <p>3) Searching Influences<br  />Selecting the “Influences” search restricts results to the specific influences you input.  If you search for “Stevie Wonder”, then only projects that have one of their influences listed as Stevie Wonder will be returned.  You can search for one influence or for multiple influences.</p>
                    <p>4) Searching Tags<br  />Selecting the “Tags” search allows you to search for any tags that a project has listed.  These are most often genres, influences, subject matter, and mood, but can include any search term.</p>
                    <p>5) Searching Project Names<br  />If you know the name of the project you’re looking for, simply search the name of the project using this selection!</p>
                    <h3 id="getting_invited">Getting Invited</h3>
                    <p>Project owners are able to invite more musicians to join their project.  When you are invited to join a project, you will receive a new message that includes a personal note from the project owner and a link to check out the project and audition.  To accept the invitation, simply click the project link. To ignore an invitation, simply delete the message!</p>
                    <h3 id="audition">Auditioning</h3>
                    <p>The audition process allows a project owner to spend some time with a potential project participant to be sure he or she matches perfectly.  When you audition for a new project, the project owner will see you in their “Applicants” list.  From there, they can check out your profile, see what projects you’ve worked on recently, and contact you directly to have a conversation.</p>
                    <p>While your application is pending, you can review it by going to your <a href="<?= base_url('dashboard/project')?>">Project Dashboard</a> and selecting “Manage Projects” from the main menu.  In the <a href="<?= base_url('dashboard/project/manage')?>">Manage Projects</a> page, you can view your current applications to send a message to the project owner, view the project again, or withdraw your application. </p>
                    <p>Once you have been accepted into the project, it will still be located in the Manage Projects page, but moved from Applications to Active Projects.  You can now select the project to see the other members involved, have conversations, see detailed information about the project, and (if you really want to) leave the project.</p>
                </div>
                
                <div>
                    <h2 id="finding_musicians">Finding Musicians</h2>
                    <p>When you’re ready to get started creating music on FindMySong, you can either create new projects you lead, and you can jam with other musicians on their projects.</p>
                    <p>1) Finding Musicians<br  />The best way to find new musicians is using the <a href="<?= base_url('users/search')?>">Search Musicians</a> page.  You can find this page in the header section  The <a href="<?= base_url('users/search')?>">Search Musicians</a> page can be used in a number of ways to find the best project for you.
</p>
                    <h3 id="searching_for_musicians">I’m Searching for a Musician...</h3>
                    <p>Using the general search bar, you can search by skills, genres, influences or musician names.  The dropdown on the left side of the search bar allows you to select which of these items you are searching for.</p>
                    <p>Searching Skills<br  />Selecting the “Skills” search restricts results to the specific skills you input.  If you search for “electric guitar”, only musicians who play electric guitarist will be listed in the results.  However, you can also search for categories.  For instance, if you search for “guitar”, then not only will “electric guitar” results show up, but “acoustic guitar” and “12-string guitar” will also be included.</p>
                    <p>Searching Genres<br  />Selecting the “Genres” search restricts results to the specific genres you input. If you search for “rock”, then musicians that have listed skills in the “rock” genre will be returned.  You can search for one genre or for multiple genres.</p>
                    <p>Searching Influences<br  />Selecting the “Influences” search restricts results to the specific influences you input.  If you search for “Stevie Wonder”, then only musicians that have one of their influences listed as Stevie Wonder will be returned.  You can search for one influence or for multiple influences.</p>
                    <p>Searching Musician Names<br  />If you know the name of the person you’re looking for, simply search their name using this selection!</p>
                    
                    <h3 id="invite_musician">Inviting Musicians</h3>
                    <p>Project owners are able to invite musicians to join their project.  This can be done either directly from the search results by clicking the “Invite” icon, or by visiting the musician’s profile and selecting Invite from their profile.</p>
                    
                    <h3 id="the_audition_system">Audition Process</h3>
                    <p>The audition process allows a project owner to spend some time with a potential project participant to be sure he or she matches perfectly.  When a musician auditions for your project, you will see them in your “Applicants” list.  From there, you can check out their profile, see what projects they’ve worked on recently, and contact them directly to have a conversation.</p>
                    
                </div>            
            </div>
            <div class="span1"></div>
            <div class="faq_content_right span3" style="top:2px;">
            	<h2>FAQ</h2>
                <div>
                    <h3>FMS Essentials</h3>
                    <p><a href="#changing_your_email">Changing Your Email</a></p>
                    <p><a href="#changing_your_password">Changing Your Password</a></p>
                    <p><a href="#deactivating_your_account">Deactivating Your Account</a></p>
                    <p><a href="#messaging">Messaging</a></p>
                    <p><a href="#contactd">Contacts</a></p>
                </div>
                <div>
                    <h3>What Are Skills?</h3>
                    <p><a href="#skills_overview">Overview</a></p>
                    <p><a href="#on_your_profile">On your Profile</a></p>
                    <p><a href="#in_your_projects">In your Projects</a></p>
                </div>
                <div>
                    <h3>The Musician Profile</h3>
                    <p><a href="#musician_profile_basic">Basic Settings</a></p>
					<p><a href="#musician_profile_spotlight">Spotlight</a></p>
                    <p><a href="#musician_profile_webaddr">Web Address</a></p>
                    <p><a href="#musician_profile_projects">Project Portfolio</a></p>
                    <p><a href="#musician_profile_biography">Biography</a></p>
                    <p><a href="#musician_profile_skills">Skills</a></p>
                    <p><a href="#musician_profile_connect">Connect</a></p>
                </div>
                <div>
                    <h3>Creating Projects</h3>
                    <p><a href="#creating_projects_overview">Overview</a></p>
                    <p><a href="#creating_projects_basic_settings">Basic Settings</a></p>
                    <p><a href="#adding_skills">Adding Skills</a></p>
                    <p><a href="#describing_skills">Describing Skills</a></p>
                    <p><a href="#skills_and_team">“My Skills” vs “My Team”</a></p>
                    <p><a href="#publishing_your_project">Publishing Projects</a></p>
                </div>
                <div>
                    <h3>Joining Projects</h3>
                    <p><a href="#search_for_project">Using the Project Search</a></p>
                    <p><a href="#getting_invited">Getting Invited</a></p>
                    <p><a href="#audition">Audition</a></p>
                </div>
                <div>
                    <h3>Finding Musicians</h3>
                    <p><a href="#searching_for_musicians">Searching for Musicians</a></p>
                    <p><a href="#invite_musician">Inviting Contacts</a></p>
                    <p><a href="#the_audition_system">The Audition System</a></p>
                </div>
            </div>
            <a href="#FMS_Essentials" class="top" style="top:1050px;"><i class="fui-triangle-up-small"></i>Go back top</a>
        </div>
        <div class="clear"></div>
        <div class="row">
        	<div class="span12">
                <div class="help_center_footer">
                    <p>Haven't found an answer in our resources and still need help?&nbsp;<a href="<?php echo base_url().'contact'?>">Contact us</a></p>
                </div>
            </div>
        </div>
    </div>
</div>