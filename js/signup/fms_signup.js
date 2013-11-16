////////// OBSOLETE - noted for cleanup 06-19-2013 :: ww

$(document).ready(function(){
	
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '333159133434737', // App ID
      channelUrl : '//WWW.findmysong.dev/channel.html', // Channel File
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });

   	FB.getLoginStatus(function(response) {
  if (response.status === 'connected') {
    // the user is logged in and has authenticated your
    // app, and response.authResponse supplies
    // the user's ID, a valid access token, a signed
    // request, and the time the access token 
    // and signed request each expire
    var uid = response.authResponse.userID;
    //alert(uid);
    var accessToken = response.authResponse.accessToken;
    //alert("login+ ap");
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
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
	
	
	// $("#fbsign").click(function(){
		// FB.login(function(response){
			    // if (response.status === 'connected') {
    			// alert("already login and approved our app");
      // // The response object is returned with a status field that lets the app know the current
      // // login status of the person. In this case, we're handling the situation where they 
      // // have logged in to the app.
      // testAPI();
    // } else if (response.status === 'not_authorized') {
//     	
    	// alert("already login but not approved our app");
      // // In this case, the person is logged into Facebook, but not into the app, so we call
      // // FB.login() to prompt them to do so. 
      // // In real-life usage, you wouldn't want to immediately prompt someone to login 
      // // like this, for two reasons:
      // // (1) JavaScript created popup windows are blocked by most browsers unless they 
      // // result from direct interaction from people using the app (such as a mouse click)
      // // (2) it is a bad experience to be continually prompted to login upon page load.
      // FB.login();
    // } else {
//     	
    	// alert("not login");
      // // In this case, the person is not logged into Facebook, so we call the login() 
      // // function to prompt them to do so. Note that at this stage there is no indication
      // // of whether they are logged into the app. If they aren't then they'll see the Login
      // // dialog right after they log in to Facebook. 
      // // The same caveats as above apply to the FB.login() call here.
      // FB.login();
    // }
// 			
		// });
	// });
	
	
	    function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Good to see you, ' + response.name + '.');
    });
  };
	
});
