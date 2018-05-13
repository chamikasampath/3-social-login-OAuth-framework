<!DOCTYPE html>
<html lang="en>">
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

  
    <div class="container">
        <h2> User Login </h2>

        <div class=col-sm-8>

          <form action ='login.php' method='POST' enctype='multipart/form-data'>

            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="email" name="email" Placeholder="User Email">
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="password" name="password" Placeholder="Password">
                </div>
            </div>

                    <button type="submit" class="btn btn-primary" id="submit" name="submit"> Login </button>
                  </form>
                  
                </div>
                <div class="col-sm-2"></div>
              </div>
              
              <div class="row" align="center">
                <div class="col-sm-4"></div>
                <div class="col-sm-1">
                  
                  
                  <a href="https://graph.facebook.com/oauth/authorize?response_type=token&client_id=243344276212072&redirect_uri=https://localhost:4433/3-social-login-OAuth-framework/profile.php&scope=email%20public_profile" class="btn btn-primary" class="fb btn">
                  <img src="/3-social-login-OAuth-framework/fb.png" width="210px" height="40">

                  </a>
                  
                  
                </div>
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


<?php
  
	if(isset($_POST['submit']))
  {
    
		login();
	}
?>

<?php
	
	function login()
	{

		$email='admin@gmail.com';
		$password='admin';

	
		$input_email = $_POST['email'];
		$input_pwd = $_POST['password'];

		
		if(($input_email == $email)&&($input_pwd == $password))
		{
			session_set_cookie_params(300);
			session_start();
			session_regenerate_id();
			
			
			setcookie('session_cookie', session_id(), time() + 300, '/');

			$token = generate_token();

      setcookie('csrf_token', $token, time() + 300, '/','www.assignment03.com',true);
			
			header("Location:profile.php");
   		exit;
			
		}
		else
		{
			echo "<script>alert('Invalid login! Please try again!!')</script>";
		}


	}
	
  function generate_token()
	{
	  return sha1(base64_encode(openssl_random_pseudo_bytes(30))); 
	}


?>
