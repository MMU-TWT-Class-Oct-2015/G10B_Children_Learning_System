<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
	
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
	
	<?php
		session_start();  
		if(empty($_SESSION['user_name']) OR empty($_SESSION['user_password'])){  
			echo '<script>Please continue to log in.</script>';
			echo '<meta http-equiv="refresh" content= "0;url=http://localhost/cls/login.php" />';
		}else{$username = $_SESSION['user_name'];}
		
		if(isset($_POST['logoutBtn'])){
			session_unset();
			session_destroy();
			echo '<meta http-equiv="refresh" content= "0;url=http://localhost/cls/homepage.html" />';
		}
		
		if(!$connect = mysql_connect("localhost", "root", ""))
			die(mysql_error());
			
		if (!mysql_select_db("cls", $connect))
			die(mysql_error());
			
		$query = mysql_query("SELECT * FROM profile WHERE user_name='$username';", $connect) or die(mysql_error());
		
		$result = mysql_fetch_array($query, MYSQL_NUM);
		$email = $result[3];
		$col_hs = $result[4];
		$fru_hs = $result[5];
		$ani_hs = $result[6];
		$add_hs = $result[7];
		$sub_hs = $result[8];
		$mul_hs = $result[9];
		$div_hs = $result[10];
		$totalsum= $col_hs + $fru_hs + $ani_hs + $add_hs + $sub_hs + $mul_hs + $div_hs;
		$total_hs = $totalsum . '/700';
		
		$change = '';
		if(isset($_POST['chgPwdMenu'])){
			$change = '
				<table id="change">
					<form action="profile.php" method="post">
						<tr>
							<td>Old Password:</td>
							<td><input type="password" name="oldPwd" required></input></td>
						</tr>
						<tr>
							<td>New Password:</td>
							<td><input type="password" name="Pwd" required></input></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" name="chgPwd" id="chgPwd" value="Change" ></input></td>
						</tr>
					</form>
				</table>
			';
		}

		if(isset($_POST['chgEmlMenu'])){
			$change = '
				<table id="change">
					<form action="profile.php" method="post">
						<tr>
							<td>New Email:</td>
							<td><input type="text" name="newEml" required></input></td>
						</tr>
						<tr>
							<td>Confirm Password:</td>
							<td><input type="password" name="Pwd" required></input></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" name="chgEml" id="chgEml" value="Change" ></input></td>
						</tr>
					</form>
				</table>
			';
		}
		
		function checkInput($data){
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
		function checkValidity(){
			global $Pwd, $newEml;
			$result = true;
		
			if(!empty($Pwd)){
				if (!preg_match("/^[0-9a-zA-Z]*$/",$Pwd)){
					echo '<script> alert("*Only letters and numbers are allowed."); </script>';
					$result = false;
				}
			}
			
			if(!empty($newEml)){
				if (!filter_var($newEml, FILTER_VALIDATE_EMAIL)){
					echo '<script> alert("*Invalid email format."); </script>';
					$result = false;
				}
			}
			return $result;
		}
		
		if(isset($_POST['chgPwd'])){
			$oldPwd = checkInput($_POST['oldPwd']);
			$Pwd = checkInput($_POST['Pwd']);
			$valid = checkValidity();
			
			if($valid){
				if(sha1($oldPwd) == $_SESSION['user_password']){
					$query = mysql_query("UPDATE profile SET password = SHA1('$Pwd') WHERE user_name='$username';", $connect) or die(mysql_error());
					
					if($query){
						echo '<script>
							alert("You has successfully changed your password. \nPlease login to continue."); 
						</script>';
						session_unset();
						session_destroy();
						echo '<meta http-equiv="refresh" content= "0;url=http://localhost/cls/login.php" />';
					}
				}else{ echo '<script>alert("The password you entered does not match with those in files.");</script>'; }
			}
			
		}
		
		if(isset($_POST['chgEml'])){
			$newEml = checkInput($_POST['newEml']);
			$Pwd = checkInput($_POST['Pwd']);
			$valid = checkValidity();
			
			if($valid){
				if(sha1($Pwd) == $_SESSION['user_password']){
					$query = mysql_query("UPDATE profile SET email = '$newEml' WHERE user_name='$username';", $connect) or die(mysql_error());
					
					if($query){
						echo '<script>
							alert("You has successfully changed your email. \nPlease login to continue."); 
						</script>';
						session_unset();
						session_destroy();
						echo '<meta http-equiv="refresh" content= "0;url=http://localhost/cls/login.php" />';
					}
				}else{ echo '<script>alert("The password you entered does not match with those in files.");</script>'; }
			}
		}
		
		mysql_close($connect);
	?>
	
	<style>
		body{
			background-color: lightcyan;
		}
		
		#header{
			background-color: white;
			border: 4px outset aquamarine;
			padding: 10px;
			margin-bottom: 3px;
		}
		
		form{
			display: inline;
			float: right;
		}
		
		#logo{
			display: inline;
			width: 30%;
		}
		
		#logout{
			padding: 10px;
			background-color: aquamarine;
			border: 4px outset aquamarine;
			color: black;
			width: 100px;
			height: 100px;
			font-family: cursive;
			font-size: 20px;
			cursor: pointer;
		}
		
		#score{
			background-color: white;
			width: 70%;
			font-family: cursive;
			font-size: 20px;
			vertical-align: middle;
			float: left;
			border: 10px outset gold;
		}
		
		thead td{
			background-color: blueViolet;
			color: white;
			text-align: center;
			height: 40px;
		}
		
		tfoot td{
			background-color: black;
			color: white;
			font-weight: bold;
			height: 75px;
			text-align: right;
			padding-right: 20px;
		}
		
		#score tbody td{
			height: 40px;
			text-align: center;
		}
		
		a:link, a:visited{
			display: block;
			color: white;
			height: 100%;
			width: 95%;
			background-color: crimson;
			border: 4px outset crimson;
			text-decoration: none;
			text-transform: uppercase;
			text-align: center;
			padding: 2px 0;
		}

		a:hover, a:active{
			background-color: cyan;
			border: 4px outset cyan;
			color: black;
		}
		
		#user{
			background-color: white;
			width: 29.5%;
			font-family: cursive;
			font-size: 20px;
			vertical-align: middle;
			float: right;
			border: 10px outset blueviolet;
		}
		
		#user td{
			height: 40px;
			text-align: left;
			padding: 5px;
		}
		
		#chgPwd{
			height: 50px;
			width: 100%;
			font-family: cursive;
			font-size: 20px;
			background-color: orangered;
			color: white;
			border: 5px outset orangered;
			cursor: pointer;
		}
		
		#chgEml{
			height: 50px;
			width: 100%;
			font-family: cursive;
			font-size: 20px;
			background-color: lawngreen;
			border: 5px outset lawngreen;
			cursor: pointer;
		}
		
		#change{
			background-color: white;
			width: 29.5%;
			font-family: cursive;
			font-size: 17px;
			vertical-align: middle;
			float: right;
			border: 10px outset indianred;
			margin-top: 10px;
		}
		
		#change td{
			height: 40px;
			text-align: left;
			padding: 5px;
		}
		
		input[type=text], input[type=password]{
			height: 30px;
			padding: 5px;
		}
		
		.error{
			color: red;
			font-size: 15px;
			font-style: italic;
		}
	</style>
</head>
<body>
	
	<div id="header">
		<img id="logo" src="theleopard.png"/>
		<form name="logoutForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<input type="submit" id="logout" value="Log Out" name="logoutBtn"></input>
		</form>
	</div>
	
	<table id="score">
		<thead>
			<tr>
				<td>Matching Games</td>
				<td>Highscore</td>
				<td></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Animal</td>
				<td><?php echo $ani_hs; ?></td>
				<td><a href="" target="_self">Play!</a></td>
			</tr>
			<tr>
				<td>Colour</td>
				<td><?php echo $col_hs; ?></td>
				<td><a href="" target="_self">Play!</a></td>
			</tr>
			<tr>
				<td>Fruit</td>
				<td><?php echo $fru_hs; ?></td>
				<td><a href="" target="_self">Play!</a></td>
			</tr>
		</tbody>
		<thead>
			<tr>
				<td>Numbers Games</td>
				<td>Highscore</td>
				<td></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Addition</td>
				<td><?php echo $add_hs; ?></td>
				<td><a href="" target="_self">Play!</a></td>
			</tr>
			<tr>
				<td>Subtraction</td>
				<td><?php echo $sub_hs; ?></td>
				<td><a href="" target="_self">Play!</a></td>
			</tr>
			<tr>
				<td>Multiplication</td>
				<td><?php echo $mul_hs; ?></td>
				<td><a href="" target="_self">Play!</a></td>
			</tr>
			<tr>
				<td>Division</td>
				<td><?php echo $div_hs; ?></td>
				<td><a href="" target="_self">Play!</a></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3">Total Highscore:&nbsp;<?php echo $total_hs; ?></td>
			</tr>
		</tfoot>
	</table>
	
	
	<table id="user">
		<tr>
			<td><i class="fa fa-user"></i> Username:</td>
			<td><?php echo $_SESSION['user_name']; ?></td>
		</tr>
		<tr>
			<td><i class="fa fa-envelope"></i> Email:</td>
			<td><?php echo $email; ?></td>
		</tr>
		<tr>
			<td colspan="2">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<input type="submit" id="chgPwd" name="chgPwdMenu" value="Change Password">
				</form>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<input type="submit" id="chgEml" name="chgEmlMenu" value="Change Email">
				</form>
			</td>
		</tr>
	</table>
	
	<?php echo $change; ?>
	

</body>
</html>
