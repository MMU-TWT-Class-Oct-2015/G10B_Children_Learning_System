<?php
  session_start();
  $username = $_SESSION['user_name'];
  if(!$connect=mysql_connect("localhost","root","")){
    die(mysql_error());
  }

  if(!mysql_select_db("cls",$connect)){
    $query = "CREATE DATABASE cls;";
    mysql_query($query) or die(mysql_error());
    mysql_select_db("cls",$connect);
    mysql_query($query) or die(mysql_error());
  }
  extract($_POST);
  if(isset($_POST['submit'])){
    $query = "UPDATE `profile`SET DIV_HS= '$score' WHERE USER_NAME='$username';";
    mysql_query($query) or die(mysql_error());
  }
?>

<DOCTYPE! HTML>
<html>
<head>
  <meta charset="utf-8">
  <title>Calculation</title>
  <style>
   span{
       float:left;
       padding: 50px 50px 0px 50px;
       margin-right: 20px;
       border-color: black;
       border-style: solid;
       background-color:#9999FF;
       color: black;
       font-size: 40pt;
       height:100px;

     }
    span1{
       float:right;
       padding: 50px 50px 0px 50px;
       margin-right: 20px;
       border-color: black;
       border-style: solid;
       background-color:#9999FF;
       color: black;
       font-size: 40pt;
       height:100px;

       }
  body{

    background-repeat:no-repeat;
    background-size:cover;
  }
  p1{
     font-size: 70pt;
  }
  .d1{background-color:#9999FF;
     font-style: sans-serif,monospace;
     font-size: 40pt;
     color:white;
     border:3px black solid;
     padding: 10px 0px 0px 0px;
     text-align: center;
    }
  .d2{
     font-style: sans-serif,monospace;
     font-size: 20pt;
     color:white;
     border:6px black double;
     background-color: black;
     background-repeat:no-repeat;
     background-size: cover;
    }
  .d3{background-color:black;
      font-style: sans-serif,monospace;
      font-size: 40pt;
      color:red;
      border:3px black solid;
      padding: 10px 0px 0px 0px;
      text-align: center;
    }
   hide{display: none;
        visibility: hidden;
       }
  </style>
  <script type="text/javascript">
    var counter=1;
    var hs= 0 ;
    document.mul1.check.disabled=true;
    function division(){
          if(counter<11)
          {
            var num2 = Math.floor(Math.random()*12)+1;
            var num1 = Math.floor(Math.random()*12) * num2;
            var sum  = 0;
            document.getElementById("divnum1").innerHTML = num1;
            document.getElementById("divnum2").innerHTML = num2;
            sum = num1 / num2;
            document.getElementById("sum1").innerHTML = sum;
            document.div1.start1.value="Next Question";
            document.div1.start1.disabled=true;
            document.div1.divall.disabled=false;
            document.div1.divall.value="";
            document.div1.check.disabled=false;
            document.getElementById("qnum").innerHTML= "Q"+counter;
            counter++;
          }
          else {
            alert("You Have Finished THIS QUIZ!!");
            document.div1.score.disabled=false;
            document.div1.submit.disabled=false;
          }
    }

   function division2(sum){
    var x = parseInt(document.div1.divall.value);
    if(x==document.getElementById("sum1").innerHTML){
      alert("You are correct!! ");
      document.div1.divall.disabled=true;
      document.div1.check.disabled=true;
      hs=hs+10;
      document.div1.score.value=hs;
    }
    else if (document.div1.divall.value=="") {
      alert("Please fill in your answer!!");
    }
    else{
      alert("You are wrong!!");
      document.div1.divall.disabled=true;
    }
    document.div1.start1.disabled=false;
  }

  </script>

</head>
<body>
  <form name="div1" action="division.php" method="post">
    <p1>Division Quiz</p1>
    <br><br><br><br>
    <span id="qnum"></span>
    <span><label id="divnum1"></label></span>
    <span>÷</span>
    <span><label id="divnum2"></span>
    <span>=</span>
    <input type="text" class="d1" value="" name=divall size=3 style="width:150px;height:155px;"><br><br><br><br>
    <input type="button" class="d2" value="Start question!" name="start1" onclick="division()">
    <input type="button" class="d2" value="Check answer!" name="check" onclick="division2()" disabled>
    <input type="reset" class="d2" value="Clear" name="reset">
    <button type="button" class="d2" onclick="location.href='profile.php'">Back</button>
    <p style="float:right;font-size:26pt;padding-right:50px;">Your Score :</p><br><br><br><br><br>
    <input type="submit" class="d2" value="submit" name=submit disabled style="width:120px;height:125px;float:right;">
    <input type="text"  class="d3" value="" name=score disabled style="width:120px;height:125px;float:right;background-color:cyan">
    <hide><label id="sum1"></label></hide>
  </form>

</body>
</html>