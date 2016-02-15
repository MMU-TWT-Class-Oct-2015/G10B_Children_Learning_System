<!DOCTYPE html>
<html>
<head>
	<title>Log In</title>
	
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">

	<?php
		$loginErr = '';
		if(isset($_POST['loginForm'])){
			extract($_POST);
			$encryptpwd = sha1($password);
			
			if(!$connect = mysql_connect("localhost", "root", ""))
				die(mysql_error());
			
			if (!mysql_select_db("cls", $connect))
				die(mysql_error());
			
			$valid = mysql_query("SELECT * FROM profile WHERE user_name='$username' AND password='$encryptpwd'", $connect) or die(mysql_error());
			
			$invalid = mysql_query("SELECT * FROM profile WHERE user_name='$username' OR password='$encryptpwd'", $connect) or die(mysql_error());
			
			if(mysql_num_rows($valid) == 1){
				$temp = "UPDATE `temp` SET score = '0' WHERE user_name = '$username'; ";
				mysql_query($temp, $connect) or die(mysql_error());
				session_start();
				$_SESSION['user_name'] = $username;
				$_SESSION['user_password'] = $encryptpwd;
				header('Location: http://localhost/cls/profile.php');
				exit;
			}elseif(mysql_num_rows($invalid) == 1){
				$loginErr = "*Invalid username or password. Please try again.";
			}else{$loginErr = "*You are not registered. Please sign up first.";}
			mysql_close($connect);
		}
	?>
	
	<style>
		body{
			background: url("junglebg.jpg") no-repeat center fixed;
			background-size: cover;
		}
		
		
		#box{
			background-color: rgba(255, 255, 255, 0.5);
			width: 500px;
			border-radius: 25px;
			margin: auto;
			margin-top: 120px;
			padding-top: 15px;
			padding-bottom: 25px;
		}
		
		#logo{
			display: block;
			margin-left: auto;
			margin-right: auto;
			width: 90%;
		}
		
		table{
			width: 80%;
			margin: auto;
			border: 5px inset orange;
			border-radius: 5px;
			padding: 10px;
			font-size: 17px;
			font-family: cursive;
		}
		
		input[type=text], input[type=password], input[type=submit]{
			background-color: #F8F8F8;
			border-radius: 5px;
			width: 100%;
			font-size: 17px;
			font-family: cursive;
			padding: 8px;
			box-sizing: border-box;
		}
		
		td {
			padding: 5px;
		}
	
		input[type=text]:focus, input[type=password]:focus{
			background-color: #66FFFF;
		}
		
		input[type=submit]{
			background-color: #9900FF;
			color: white;
			cursor: pointer;
		}
		
		input[type=submit]:focus{
			background-color: #FFFFFF;
			color: black;
		}
		
		#forgotpwd{
			font-size: 15px;
			text-align: right;
			padding: 0px;
		}
		
		.error{
			color: red;
			font-size: 15px;
			font-style: italic;
		}
	</style>
</head>
<body>
	<div id="box">
			<div>
				<a href="http://localhost/cls/homepage.html" title="Back to Home">
					<img id="logo" src="theleopard.png"/>
				</a>
			</div>
			<br>
			<div id="menucontainer">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<table>
						<tr>
							<td>Username: </td>
							<td><input type="text" name="username" required></input></td>
						</tr>
						<tr>
							<td>Password: </td>
							<td><input type="password" name="password" required></input></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" name="loginForm" value="Log In"></input></td>
						</tr>
						<tr><td colspan="2" class="error"><?php echo $loginErr; ?></td></tr>
						<tr>
							<td colspan="2" id="forgotpwd"><a href="http://localhost/cls/forgotpwd.php">Forgot password?</a></td>
						</tr>
					</table>
				</form>
			</div>
		</div>		
</body>
</html>
