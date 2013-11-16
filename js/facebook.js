$(document).ready(
		function() {
			window.fbAsyncInit = function() {
				FB.init({
					appId : '333159133434737', // App ID
					channelUrl : location.protocol + '//' + location.hostname
							+ '/channel.html', // Channel File
					status : true, // check login status
					cookie : true, // enable cookies to allow the server to access the session
					xfbml : true
				// parse XFBML
				});
				FB.getLoginStatus(function(response) {
					if (response.status === 'connected') {

						var uid = response.authResponse.userID;
						var accessToken = response.authResponse.accessToken;
						//alert(uid);
						console.log(uid);
						login(uid);

					} else if (response.status === 'not_authorized') {
						//alert("login+ not ap");
						// the user is logged in to Facebook, 
						// but has not authenticated your app
					} else {
						//alert("not login");
						// the user isn't logged in to Facebook.
					}
				});
			};
			// Load the SDK asynchronously
			(function(d) {
				var js, id = 'facebook-jssdk', ref = d
						.getElementsByTagName('script')[0];
				if (d.getElementById(id)) {
					return;
				}
				js = d.createElement('script');
				js.id = id;
				js.async = true;
				js.src = "//connect.facebook.net/en_US/all.js";
				ref.parentNode.insertBefore(js, ref);
			}(document));

		});

function login(uid) {

	// console.log('login function called');
	// $.post("facebookSignin", {
		// facebookUserid : uid
	// }).done(function(data) {
		// //alert("facebook signup success!");
	// });

};