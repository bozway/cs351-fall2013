<div id="skills_container" class="skills">
	<?php foreach($skills as $skill):?>
		<div class="skill-bar"><img src="<?php echo base_url().$skill['video_cover'] ?>" /><?=$skill['name']?><p>Experience (<?=$skill['experience']?>)</p></div>
		<div class="skill-body <?php if(empty($skill['video_src'])){echo 'not_has_video';}?>">
			<div class="skill-video-container" data-src="<?=$skill['video_src']?>" style="display:<?php if(empty($skill['video_src'])){echo 'none';}else{echo 'block';}?>">
				<div class="skill-video-hover" >
								<iframe title="YouTube video player" width="220" height="160" 
									src="<?=$skill['video_src']?>" frameborder="0" allowfullscreen>
								</iframe>
				</div>
			</div>
			<div class="skill-genres"><p>Genres</p>
				<?php foreach($skill['genres'] as $genre):?>
					<div class="skill_tag"><?=$genre?></div>
				<?php endforeach;?>
			</div>
			<div class="skill-influence"><p>Influences</p>
				<?php foreach($skill['influences'] as $influence):?>
					<div class="skill_tag"><?=$influence?></div>
				<?php endforeach;?>
			</div>
		</div>
		<div style="clear:both"></div>
	<?php endforeach;?>
</div>