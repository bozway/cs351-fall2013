<div id="<?php echo $uploader_id?>" class="audio_uploader">
	<div class="audio-preview-container">
		<div class="audio-player-template loading">
			<div class="audio-player-control"><span class="fui-play"></span></div>
			<div class="audio-player-info"><span>--</span></div>
			<div class="audio-player-delete"><span class="fui-trash"></span></div>
			<div class="audio-player-time"><span>--:--</span></div>
			<audio>
				<source src=""></source>
			</audio>
		</div>
	</div>
	<input id="<?php echo $uploader_id?>_file" name="files[]" type="file" style="display: none"  />
	<div class="audio-files-preload"><?php
			$spotlight = array ();
			$spotlightCollection = $files;
			foreach ( $spotlightCollection as $row ) {
				$spotlight [] = $row;
			}
			usort ( $spotlight, function ($a, $b) {
				if ($a->getSubtype () == $b->getSubtype ())
					return 0;
				return ($a->getSubtype () < $b->getSubtype ()) ? - 1 : 1;
			} );
		?>
		<?php foreach($spotlight as $row) { if($row->getType() === 0) {?>
			<div data-id="<?php echo $row->getId()?>"
				data-name="<?php echo $row->getName()?>"
				data-url="<?php echo base_url().$row->getPath().$row->getName();?>">
			</div>
		<?php }}?>
	</div>
	<div class="audio-upload-btn-container">
		<button type="button" class="btn btn-large btn-block btn-success btn-embossed">Upload File</button>
	</div>
	<p class="audio-upload-error"></p>
	<input class="audio-file-input" type="file" name="files[]" style="display: none" />
</div>