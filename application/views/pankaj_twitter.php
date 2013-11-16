<!DOCTYPE html>
<html>
    <head>
        <title>Twitter Authorization</title>
        <script language="javascript" type="text/javascript">
            function authorize(){
                var url = 'http://www.findmysong.dev/pankaj_test/testfirst';
  
                
                newwindow=window.open(url,'Twitter Authorize','height=400,width=400');
                if (window.focus) {newwindow.focus()}
                return false;
                }
         </script>
        
    </head>
    <body>
        <form method="POST" onsubmit="authorize();">
            <input type="image" src ="img/twitter.png" name="twitter"/>
        </form>
        <//?php echo phpinfo() ?> 
    </body>
</html>
