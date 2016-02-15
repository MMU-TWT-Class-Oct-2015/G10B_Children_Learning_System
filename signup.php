<!DOCTYPE html>
<html>
<head>
	<title>Sign Up</title>
	
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
	
	<?php
		function checkInput($data){
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
		$userErr = '';
		$pwdErr = '';
		$emlErr = '';
		
		function checkValidity(){
			global $username, $password, $email;
			global $userErr, $pwdErr, $emlErr;
			$result = true;
			if (!preg_match("/^[0-9a-zA-Z]*$/",$username)){
				$userErr = "*Only letters and numbers are allowed.";
				$result = false;
			}
			if (!preg_match("/^[0-9a-zA-Z]*$/",$password)){
				$pwdErr = "*Only letters and numbers are allowed.";
				$result = false;
			}
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$emlErr = "*Invalid email format.";
				$result = false;
			}
	
			return $result;
		}
		
		if(isset($_POST['submitForm'])){
			$username = checkInput($_POST["username"]);
			$password = checkInput($_POST["password"]);
			$email = checkInput($_POST["email"]);
			$valid = checkValidity();
			
			if($valid){
				$encryptpwd = sha1($password);
				
				if(!$connect = mysql_connect("localhost", "root", ""))
					die(mysql_error());
				
				if (!mysql_select_db("cls", $connect))
					die(mysql_error());
				
				
				$userquery = "SELECT * FROM `profile` WHERE `user_name` = '$username';";
				$emailquery = "SELECT * FROM `profile` WHERE `email` = '$email';";
				
				$usercheck = mysql_query($userquery, $connect) or die(mysql_error());
				$emailcheck = mysql_query($emailquery, $connect) or die(mysql_error());
				
				if(mysql_num_rows($usercheck) == 0){
					if(mysql_num_rows($emailcheck) == 0){
						$command = "INSERT INTO `profile` (`user_name`, `password`, `email`) VALUES ('$username', '$encryptpwd', '$email');";
						if(mysql_query($command, $connect)){
							$temp = "INSERT INTO `temp` (`user_name`, `score`) VALUES ('$username', '0');";
							mysql_query($temp, $connect) or die(mysql_error());
							echo "<script type='text/javascript'>alert('You have registered successfully! Please continue to log in.');</script>";
							echo '<meta http-equiv="refresh" content= "0;url=http://localhost/cls/login.php" />';
						}else{ die(mysql_error()); }
					}else{$emlErr = "*The email you entered: '$email' was registered. Please proceed to login.";}
				}else{$userErr = "*The username you entered: '$username' already exists. Please enter another username.";}
				mysql_close($connect);
			}
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
			margin-top: 100px;
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
			width: 90%;
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
			padding: 5px;
			box-sizing: border-box;
		}
	
		input[type=text]:focus, input[type=password]:focus{
			background-color: #66FFFF;
		}
		
		input[type=submit]{
			background-color: #8B0000;
			color: white;
			cursor: pointer;
			padding: 15px;
		}
		
		input[type=submit]:focus{
			background-color: #FFFFFF;
			color: black;
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
			<div id="menucontainer">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<table>
					<tr>
						<td>Username <i style="color: red;">(alphanumeric)</i>: </td>
						<td><input type="text" name="username" required></input></td>
					</tr>
					<tr><td colspan="2" class="error"><?php echo $userErr; ?></td></tr>
					<tr>
						<td>Password <i style="color: red;">(alphanumeric)</i>: </td>
						<td><input type="password" name="password" required></input></td>
					</tr>
					<tr><td colspan="2" class="error"><?php echo $pwdErr; ?><td></tr>
					<tr><td colspan="2">Email Address: </td></tr>
					<tr><td colspan="2"><input type="text" name="email" required></input></td></tr>
					<tr><td colspan="2" class="error"><?php echo $emlErr; ?></td></tr>
					<tr><td colspan="2"><input type="submit" name="submitForm" value="Sign Up"></input></td></tr>
				</table>
				</form>
			</div>
		</div>		
</body>
</html>
