<div id="message_contacts_container" data-user-id="<?php echo $loggedin_userid;?>" class="<?php if($active_panel == Message::CONTACTS) echo 'active-panel';?>">
	<div class="default-header">
		<div class="container">
			<div class="row">
				<p class="dashboard-title">My Contacts</p>
			</div>
		</div>
	</div>
	
	<div class="page-content container">
		<div class="row">
			<div class="span6 pull-right tool-bar">
				<div class="row">
					<div class="span4">
						<form id="contact_search_form" class="form-search" disabled="true">
							<div class="input-append">
								<input id="contact_search" class="span3 search-query search-query-rounded" type="text" placeholder="Search">
								<button id="contact_search_btn" class="btn">
									<span class="fui-search"></span>
								</button>
							</div>
						</form>
					</div>
					<div class="span2">
						<div class="btn-grey">
							<div id="sort_by_dropdown" class="btn-group pull-right">
								<i class="dropdown-arrow dropdown-arrow-inverse"></i>
								<button class="btn btn-info selected-sort">Sort By</button>
								<button class="btn btn-info dropdown-toggle" data-toggle="dropdown">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-inverse">
									<li data-type="0"><a>Last Name</a></li>
									<li data-type="1"><a>First Name</a></li>
									<li data-type="2"><a>Most Recent</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
		<div id="contacts_container" class="row">
			<?php foreach($contacts as $contact):?>
				<div class="contact_container span6" data-id="<?=$contact['id']?>">
					<button class="btn-delete close fui-cross" data-contactid="<?=$contact['id']?>"></button>
					<div class="contact-photo"> 
						<img src="<?=$contact['photo']?>"></img>
						<a href="<?php echo base_url('users/profile/'.$contact['id']);?>"></a>
					</div>
					<div class="contact_name_container">
						<p class="contact-name">
							<span class="first_name"><?=$contact['first_name']?></span>
							<span class="last_name"><?=$contact['last_name']?></span>
							<span class="hide last_login_time"><?=$contact['last_login_time']?></span>
						</p>
						<div class="contact-skills">
		      				<?php foreach($contact['skills'] as $skill): ?>
		      					<span><?=$skill?>,</span>
		      				<?php  endforeach;?>
		      				<?php foreach($contact['more_skills'] as $skill): ?>
		      					<span class="more-skills hidden"><?=$skill?>,</span>
		      				<?php  endforeach;?>
		      				<?php if(count($contact['more_skills']) > 0):?>
		      					<label class="more-link">more...</label>
		      				<?php endif;?>                                   
						</div>
						<button class="btn btn-success btn-small inv" data-btn="inv" data-userid="<?=$contact['id']?>">Invite</button>
						<button class="btn btn-small msg" data-btn="msg" data-userid="<?=$contact['id']?>">Message</button>
					</div>
				</div>
			<?php endforeach;?>
		</div>
	</div>
</div>
