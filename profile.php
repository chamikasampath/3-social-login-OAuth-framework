<!doctype html>
<html >
    <head>
        <title>OAuth Framework!</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
      

        <link rel="stylesheet" href="public/css/bootstrap.min.css">
        <script src="public/js/jquery.min.js"></script>
        <script src="public/js/bootstrap.min.js"></script>

    </head>
    <body style="background-image: url('/3-social-login-OAuth-framework/facebook.jpg');color: white;">

    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="index.php">CSRF Protection!</a>
          </div>
          <ul class="nav navbar-nav">

            <!-- check whether session cookie is set, to enable logout  -->
            <?php 
                if(isset($_COOKIE['session_cookie'])) 
                {
                    echo "<li class='nav-item'>
                            <a class='nav-link active' href='logout.php'>Logout</a>
                        </li>";
                }
            ?>
        </ul>
    </nav>
    <div class="container">
        <div class="row" align="center" style="padding-top: 100px;">
            <div class="col-12">
                        
                <div class="card">
                    <!-- display user details derived from facebook or google account -->
                   <div class="card">
              <h3 class="card-header">- Your Profile -</h3>
              <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                <?php
                                    // check wether session cookie is set at the login time
                                    if(isset($_COOKIE['session_cookie'])) 
                                    {    
                                        //get user details from profile cookie
                                        $string2= $_COOKIE['profile'];
                                        $string2=explode('#', $string2);
                                        echo"
                                        <div class='row'>
                                            <div class='col-md-2'>
                                                <img src='".$string2[4]."' alt='cover' style='width:100px;height:100px;border-radius: 50%;'>
                                            </div>
                                            <div class='col-md-10' style='padding-top: 20px;'>
                                                <div class='row'>
                                                    <div class='col-md-4'>
                                                        <b>Name</b>
                                                    </div>
                                                    <div class='col-md-8'>
                                                        ".$string2[1]." ".$string2[2]."
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                        <div class='col-md-4'>
                                                            <b>Gender</b>
                                                        </div>
                                                        <div class='col-md-8'>
                                                            ".$string2[3]."
                                                        </div>
                                                </div>
                                                <div class='row'>
                                                    <div class='col-md-4'>
                                                        <b>E-mail</b>
                                                    </div>
                                                    <div class='col-md-8'>
                                                        ".$string2[0]."
                                                    </div>
                                                </div>
                                            </div>
                                        </div>";
                                    }
                                    else
                                    {
                                        //check whether access token is derived from the url (only in facebook )
                                        if(isset($_POST["at"]))
                                        {
                                            //check whether access token is empty
                                            if ($_POST["at"] != '' && $_POST["at"] != null)
                                            {
                                                //get access token and save it to new variable
                                                $new=$_POST["at"];
                                                //endpoint to send get request and derived user details
                                                //first name, last name,email,gender, profile picture and latest 3 post posted by the user will be derived from the facebook
                                                $user_details = "https://graph.facebook.com/me?fields=first_name,last_name,email,gender,picture.type(large)&access_token=".$new;
                                                $response = file_get_contents($user_details);
                                                $response = json_decode($response);
                                                //check whether details are in the respond from the facebook
                                                if($response->email != null || $response->email != '')
                                                {
                                                    //start cookie
                                                    session_set_cookie_params(300);
                                                    session_start();
                                                    session_regenerate_id();
                                                        
                                                    setcookie('session_cookie', session_id(), time() + 300, '/');
                                                    $_SESSION['access'] = $new;
                                                    //set CSRF cookie
                                                    $token = generate_token();
                                                    setcookie('csrf_token', $token, time() + 300, '/');
                                                    //set profile cookie which contain basic user details derived from facebook
                                                    $res=$response->picture;
                                                    $res2=$res->data;
                                                    $string=$response->email."#".$response->first_name."#".$response->last_name."#".$response->gender."#".$res2->url;
                                                    setcookie('profile', $string, time() + 300, '/');  
                                                    
                                                    //var_dump($response);
                                                    header("Location:profile.php");
                                                    exit;

                                                }  
                                        

                                            }
                                        }
                                        
                                        else
                                        {
                                            //when logged in using facebook facebook send access token in the URL 
                                            //below javascript will derived the access token from the URL
                                            //and make a post request which containing the access token in the body
                                            echo "
                                            <form action='profile.php'  method='post' id='form'>
                                                <input type='hidden' name='at' id='at'>     
                                            </form>

                                            <script >
                                                var ur=location.hash.replace('#access_token=', '');
                                                var ur=ur.split('&');
                                                var u=ur[0];

                                                document.getElementById('at').value = u;
                                                document.getElementById('form').submit();
                                            </script>";
                                        }


                                    }
                                    //function to generate CSRF token
                                    function generate_token()
                                    {
                                        return sha1(base64_encode(openssl_random_pseudo_bytes(30)));    
                                    }
                                ?>
                             </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <script src="/public/js/bootstrap.min.js"></script>
    <script src="/public/js/popper.min.js"></script>

</body>
</html>
