<div>
	<button id="fbsignup">signup facebook</button>
	<button id="twsignup">signup twitter</button>
</div>

<form enctype="multipart/form-data" action="http://www.findmysong.dev/profile/uploadPicture" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="800000" />
    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />
</form>






<div id="fb-root"></div>




<script>
  // Additional JS functions here

   
    // function testAPI() {
    // console.log('Welcome!  Fetching your information.... ');
    // FB.api('/me', function(response) {
      // console.log('Good to see you, ' + response.name + '.');
    // });
  // };
   
</script>



<script src="<?php echo base_url();?>js/jquery-1.9.1.min.js" type="text/javascript"></script>

<?php if (isset($extrascripts)) {
	foreach ($extrascripts as $script) {
		echo '<script src="'.base_url().$script.'" type="text/javascript"></script>'."\r\n";
	}	
} ?>
</body>
</html>