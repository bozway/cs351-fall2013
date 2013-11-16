<div id="<?php echo $uploader_id?>" class="img-uploader" data-enable-crop="1">
	<p class="img-uploaded-wrap">
	<?php
	   if($picUrl){
	   	$image_properties = array(
	          'src' => $picUrl,
	          'class' => 'img-uploaded',
			  'data-default-img' => '0',
	   		  'data-filename'	=> $picName,
		);
		echo img($image_properties);
	}else{
		echo '<img data-default-img="1" class="img-uploaded" src="/img/fms_user_portal/demo_photo.png"></img>';
	}
	?>
	</p>
	<div class="img-uploader-link-wrap">
		<p class="img-uploader-link local">Upload image</p>
		<p class="img-uploader-link url">Add from URL</p>
		<div class="img-url-enter">
			<input type="text">
			<button class="fms-button" type="button">Upload</button>
			<a>Cancel</a>
		</div>
		<div style="clear:both"></div>
	</div>
	
	<div id="<?php echo $uploader_id."_crop"?>" class="img-crop-container" style="display:none">
		<p class="img-crop-wrap">
			<img class="img-crop" src=""></img>
		</p>
		<div>
			<p class="img-crop-info">Shift Box over to the position desired</p>
			<div class="img-crop-btn-container">
				<button type="button" class="btn btn-mini btn-block btn-primary btn-embossed crop">Crop!</button>
			</div>
			<div class="img-crop-btn-container">
				<p class="btn btn-mini btn-block btn-embossed close-crop">close</p>
			</div>
		</div>
	</div>
	
	<input id="<?= $uploader_id.'_file'?>" class="img-file" type="file" name="files[]" multiple style="display:none"/>
</div>