<!DOCTYPE html>
<html>
<head>
	<title>Forgot Password</title>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	
	<?php
		$emlErr = '';
		
		if(isset($_POST['pwdresetForm'])){
			$pwdresetUsername = $_POST['pwdresetUsername'];
			$pwdresetEmail = $_POST['pwdresetEmail'];
			
			if(!$connect = mysql_connect("localhost", "root", ""))
				die(mysql_error());
			
			if (!mysql_select_db("cls", $connect))
				die(mysql_error());
			
			$match = mysql_query("SELECT * FROM profile WHERE user_name='$pwdresetUsername' AND email='$pwdresetEmail'", $connect) or die(mysql_error());
			
			if(mysql_num_rows($match) == 1){
				$p = substr(md5(uniqid(rand(),1)), 3, 10);
				$shaP = sha1($p);
				
				$result = mysql_query("UPDATE profile SET password = '$shaP' WHERE user_name = '$pwdresetUsername';", $connect) or die(mysql_error());
				
				if(mysql_affected_rows() == 1){
					require_once('PHPMailer-master\class.phpmailer.php');
					include("PHPMailer-master\class.smtp.php");
					
					$mail = new PHPMailer();
					
					$body = "Your password to log into The Leopard: Children Learning System has been temporarily changed to '$p'. Please log in using this password and your username. You may change your password afterward. The Leopard login page url: http://localhost/cls/login.php";
					
					$mail->IsSMTP();
					$mail->Host = "ssl://smtp.gmail.com";
					$mail->SMTPDebug = 1;
					$mail->SMTPAuth = true;
					$mail->SMTPSecure = "ssl";
					$mail->Host = "smtp.gmail.com";
					$mail->Port = 465;
					$mail->Username = "theleopardchildrenlearningsys@gmail.com";
					$mail->Password = "theleopard";
					$mail->SetFrom('theleopardchildrenlearningsys@gmail.com', 'The Leopard');
					$mail->Subject = "Password Reset: The Leopard";
					$mail->MsgHTML($body);
					$address = $pwdresetEmail;
					$mail->AddAddress($address, $pwdresetUsername);
					
					if($mail->Send()){ echo "<div id='msg'><i class=\"fa fa-info-circle\"></i> Your password has been changed. You will receive the new, temporary password at the email address with which you registered. Once you have logged in with this password, you may change it at the settings.</div>";
					}else{echo "<script>alert(\"Your message is not sent. Please try again later.\");</script>";}
					mysql_close($connect);
				}
			}else{
				$emlErr = '*The submitted email address does not match those on file. Please enter a correct email address.';
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
			margin-top: 80px;
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
			border: 5px inset grey;
			border-radius: 5px;
			padding: 10px;
			font-size: 17px;
			font-family: cursive;
		}
		
		input[type=text], input[type=submit]{
			background-color: #F8F8F8;
			border-radius: 5px;
			width: 100%;
			font-size: 17px;
			font-family: cursive;
			padding: 5px;
			box-sizing: border-box;
		}
		
		td {
			padding: 5px;
		}
	
		input[type=text]:focus:focus{
			background-color: #66FFFF;
		}
		
		input[type=submit]{
			background-color: black;
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
		
		#msg{
			background-color: white;
			border: 3px inset red;
			border-radius: 5px;
			font-style: italic;
			font-family: Arial;
			color: red;
			padding: 10px;
		}
	</style>
</head>
<body>
	<div id="box">
			<div id="imgcontainer">
				<a href="http://localhost/cls/homepage.html" title="Back to Home">
					<img id="logo" src="theleopard.png"/>
				</a>
			</div>
			<br>
			<div id="menucontainer">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<table>
						<tr>
							<td colspan="2" style="font-size: 1.4em; font-weight: bold;">Password Reset</td>		
						</tr>
						<tr>
							<td>Username: </td>
							<td><input type="text" name="pwdresetUsername" required></input></td>
						</tr>
						<tr>
							<td colspan="2">Please enter your email address: </td>
						</tr>
						<tr>
							<td colspan="2"><input type="text" name="pwdresetEmail" required></input></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" name="pwdresetForm" value="Reset Password"></input></td>
						</tr>
						<tr><td colspan="2" class="error"><?php echo $emlErr; ?></td></tr>
					</table>
				</form>
			</div>
		</div>	
</body>
</html>
