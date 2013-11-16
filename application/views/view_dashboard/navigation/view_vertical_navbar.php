<div class="vertical-nav span2">
    <?php foreach ($vertical_links as $link): ?>
        <div>
        	<a class="btn btn-small btn-block <?php 
        			if(isset($active)) {
						echo "active";
					}
        		?>"
	            <?php if (isset($link['id'])): ?>
	                data-id = "<?= $link['id'] ?>"
	            <?php endif; ?>
	            <?php if (isset($link['url'])): ?>
	                href = "<?= $link['url'] ?>"
	            <?php endif; ?>
	            <?php if (isset($link['status'])): ?>
	                id = "<?= $link['status'] ?>"
	            <?php endif; ?>
	            <?php if(isset($link['group'])) { echo 'data-group="1"';}?>       
	        ><?= $link['value'] ?>
                <i class="fui-arrow-right pull-right<?php 
                	if(isset($active)) echo "unfolded";
                ?>"></i>
        	</a>
        </div>
        
		<?php if(isset($link['child_links'])) {?>
		<div class="vertical-nav-subtab">
			<?php foreach($link['child_links'] as $row) {?>
				<a
					href = "<?= $row['url'] ?>"
				><?= $row['value']?>
					<i class="fui-arrow-right pull-right"></i>
				</a>
			<?php }?>
		</div>
		<?php }?>
    <?php endforeach; ?>
</div>