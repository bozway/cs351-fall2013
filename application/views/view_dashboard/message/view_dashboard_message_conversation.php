<div id="message_conversation_container" class="<?php if($active_panel === FALSE || $active_panel == Message::MESSAGES) echo 'active-panel';?>">
	<div class="default-header" id="dashboard-message-default-page">
    	<div class="container">
    		<div class="row">
		        <p class="span12">Current Message</p>
        	</div>
        	<a id="new_thread" class="btn btn-success span2">New Message</a>
    	</div>
    </div>
	
	<div class="page-content">
		<div class="background left-bg"></div>
		<div class="background right-bg"></div>
		<div class="container">
			<div class="row">
				<div class="span4 thread-container">
					<ul class="nav nav-tabs nav-append-content">
						<li class="active">
							<a href="#all_tab">View All<span id="all_message_num">(0)</span></a>
						</li>
						<li class="">
							<a href="#unread_tab">Unread<span id="unread_message_num">(0)</span></a>
						</li>
						<li class="">
							<a href="#invitation_tab">Invitation<span id="invitation_num">(0)</span></a>
						</li>
					</ul>
					<div class="border blue"></div>
					<div class="tab-content">
						<div class="tab-pane active" id="all_tab"></div>
						<div class="tab-pane" id="unread_tab"></div>
						<div class="tab-pane" id="invitation_tab"></div>
						<div id="thread_template" class="thread">
							<img src=""/>
							<div class="thread-content">
								<p class="thread-title"></p>
								<p class="thread-preview"></p>
								<p class="thread-time"></p>
							</div>
						</div>
					</div>
				</div>
	
				<div class="span8 message-container">
					<div class="row">
						<p class="span5 conv-contact-list">
							No Conversation Selected
						</p>
						<form class="span3 form-search">
				            <div class="input-append">
				              <input id="conv_contact_input" type="text" class="small search-query search-query-rounded" placeholder="Add people">
				              <button id="conv_contact_btn" class="btn btn-small"><span class="fui-search"></span></button>
				            </div>
						</form>
					</div>
	
					<div class="border"></div>
					<div class="row message-container-row">
						<div class="chat-history-container span8">
							<div class="empty-chat-history row">
								<div class="span8">
									<img src="/img/empty_message_icon.png"/>
									<p></p>
								</div>
							</div>
						</div>
					</div>
					<div id="message_date_template" class="message-seperator row">
						<div class="span3"></div>
						<div class="span2 message-date"></div>
						<div class="span3"></div>
					</div>
					
					<div id="message_template" class="message row">
						<img src="" class="span1"/>
						<div class="span6">
							<p class="message-sender"></p>
							<p class="message-content"></p>
						</div>
						<p class="span1 message-time"></p>
					</div>
	
					<div class="new_message_container">
						<div class="row">
							<form id="new_message" class="span7">
								<textarea id="new_message_input" name="new_message_input" rows="2" placeholder="Write your message..."></textarea>
				          		<label class="checkbox" for="press_enter">
				          			<input type="checkbox" checked="checked" id="press_enter_checkbox" data-toggle="checkbox">
	        						Press enter to send a message
	      						</label>
							</form>
							<div class="span1">
								<a id="new_message_btn" class="pull-right btn btn-success">Send</a>
							</div>
						</div>
					</div>
				</div>
			</div> 
		</div>
	</div>
</div>