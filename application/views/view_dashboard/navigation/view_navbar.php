	<?php if(isset($arrow_class_name)){?>
		<div class="arrow-container">
		    <div id="header_arrow" class="<?= $arrow_class_name ?>"></div>
		</div>
	<?php } ?>
	<?php
	/**
	 * 		Example for passing data 
	 *
	 * 		$data['links'] = array(
	 * 							array('value'=>'Preview Profile', 'id'=>'profile_default'),
	 * 							array('value'=>'Basic Setting', 'id'=>'profile_basic_settings')
	 * 							);
	 * 
	 * 		OR
	 * 
	 * 		$data['links'] = array(
	 * 							array('value'=>'Preview Profile', 'url'=>site_url()),
	 * 							array('value'=>'Basic Setting', 'url'=>site_url())
	 * 							);
	 */
	?>
	
	<div class="fms-navigation">
		<div class="container align">
			<?php 
				foreach ($links as $link): ?>
				<div class="nav-link <?php if(isset($link['is_active'])){echo 'active-nav-link';}?>">
					<a class="navigation-link"
						<?php if (isset($link['id'])): ?> 
							data-id="<?= $link['id'] ?>" 
						<?php endif; ?>
						<?php if (isset($link['url'])): ?>
							href="<?= $link['url'] ?>" 
						<?php endif; ?>>
					<?= $link['value'] ?>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php if(isset($show_navigation)){  ?>
		</div><!--end of class="fms-header" from view_header.php--> 
	<?php } ?>