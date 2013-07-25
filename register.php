<!DOCTYPE html public "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<style>
body{
background-color:#d2d2d2;
font-family:"Georgia", serif;
font-size:12px;
color:#094209;
}

p, h1, form, button{border:0; margin:0; padding:0;}
.spacer{clear:both; height:1px;}
.myform{
margin:0 auto;
width: 400px;
padding:14px;
}

#stylized{
border:solid 2px #094209;
background:#51B351;
}

#stylized h1 {
font-size:14px;
font-weight:bold;
margin-bottom:8px;
color:#094209;
}

#stylized p {
font-size:11px;
color:#234D23;
margin-bottom:20px;
border-bottom:solid 1px #b7ddf2;
padding-bottom:10px;
}

#stylized label{
display:block;
font-weight:bold;
text-align:right;
width:140px;
float:left;
padding-right:5px;
color:#094209;
}

#stylized .small{
color:#234D23;
display:block;
font-size:11px;
font-weight:normal;
text-align:right;
width:140px;
}

#stylized input{
float:left;
font-size:12px;
padding: 4px 2px;
border:solid 1px #aacfe4;
width:200px;
margin 2px 0 20px 10px;
}

#stylized button{
margin-top:5px;
clear:both;
margin-left:145px;
width:125px;
height:31px;
background:#0F5F0F;
text-align:center;
line-height:31px;
color:#5FC75F;
font-size:11px;
font-weight:bold;
}


</style>
</head>
<body>

<h1><center>Mobile App Registration</center></h1>

<div id="stylized" class="myform">
<form id="form" name="form" method="post" action="complete.php">

<h1>Sign up form</h1>
<p>Register for the mobile app here.</p>

<label>Name
<span class="small">Add your name</span>
</label>
<input type="text" name="name" id="name" />
<br><br>
<label>Password
<span class="small">Enter in your password</span>
</label>
<input type="text" name="password" id="password" />
<button type="submit">Sign-up</button>
<div class ="spacer"></div>

</form>
</div>

</body>
</html>