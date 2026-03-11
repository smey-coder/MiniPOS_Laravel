<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .container {
            display: center;
            width: 60vh;
            height: 60vh;
            justify-content: center;
            align-items: center;
            background-color: white;
            margin-left: 80vh;
            margin-right: 80vh;
            margin-top: 10vh;
            padding: 20px;
            border-radius: 10px;
        }
        body {
            background-color: rgb(152, 154, 178);
        }
        .container .login-form {
            display: flex;
            flex-direction: column;

        }
        .container .login-form .login {
            font-size: 50px;
            font-weight: bold;
            margin-bottom: 20px;
            align-items: center;
            color: rgb(21, 0, 255);
            display: flex;
            justify-content: center;
        }
        .container .login-form .label {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .container .login-form input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .container .login-form .button {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            font-size: 16px;
            color: white;
            padding: 8px;
            border-radius: 8px;
            background-color: rgb(105, 175, 180);
        }
        .container .login-form .button:hover {
            background-color: rgb(125, 183, 152);
        }
        .container .login-form a {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            color: rgb(21, 0, 255);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h1 class="login">Login</h1>
            <br>
            <label class="label">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter Username:">
            <br><br>
            <label class="label">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter Password:">
            <br><br>
            <button class="button">Login</button><br>
            <a href="{route('signup')}">Don't have an account? Sign up</a>
        </div>
        </div>
    </div>
</body>
</html>