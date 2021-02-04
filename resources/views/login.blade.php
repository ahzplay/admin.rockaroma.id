<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url({{asset('img/bg-home.png')}});
            background-size: auto;
            font-family: sans-serif;
        }

        .loginBox {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            height: 350px;
            padding: 70px 40px;
            box-sizing: border-box;
            background: rgba(0, 0, 0, 0.7);
        }

        .logo {
            width: 200px;
            overflow: hidden;
            position: absolute;
            top: calc(-30px/2);
            left: calc(37% - 50px);
        }

        h2 {
            margin: 0;
            padding: 0 0 26px;
            color: #FFC108;
            text-align: center;
        }

        .loginBox p {
            margin: 0;
            padding: 0;
            font-weight: bold;
            color: #fff;
        }

        .loginBox input {
            width: 100%;
            margin-bottom: 20px;
        }

        .loginBox input[type="email"],
        .loginBox input[type="password"] {
            border: none;
            border-bottom: 1px solid #fff;
            background: transparent;
            outline: #d39e00;
            padding-left: 5px;
            height: 35px;
            color: #fff;
            font-size: 16px;
        }

        .loginBox input[type="button"] {
            border: none;
            outline: none;
            height: 40px;
            color: black;
            font-size: 16px;
            background-color: #FFC108;
            cursor: pointer;
            border-radius: 10px;
            margin: 12px 0 18px;
        }

        .loginBox input[type="button"]:hover {
            background-color: #ff9720;
            color: #fff;
        }

        .loginBox a {
            color: #fff;
            font-size: 14px;
            font-weight: normal;
            text-decoration: none;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <link rel="stylesheet" href="{{ url('css/overlay-loader.css') }}">
</head>
<body>
<div class="loading" id="loading-div" style="display:none;"></div>
<div class="loginBox">
    <img class="logo" src="{{asset('img/logo-rockaroma.png')}}">
    <form method="POST" id="login-form" action="">
        {{csrf_field()}}
        <label style="color: white;">Username</label>
        <input type="email" name="email" required>
        <label style="color: white;">Password</label>
        <input type="password" name="password" {{--pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"--}} required>
        <input type="button" onclick="doLogin()" name="sign-in" value="Sign In">
    </form>
    <center>
        <label style="color: white; font-size: 10px;">
            ROCKAROMA.ID<br>
            Content Management System
        </label>
    </center>
    <br>
    @if(session('message'))
        <center><h4 style="color: red">{{ session('message') }}</h4></center>
    @endif
    {{--<center><h4 style="color: red">{{ $message ?? '' }}</h4></center>--}}

</div>

<script>
    function doLogin() {
        $('#loading-div').show();
        $.ajax({
            type: "POST",
            url: "{{url('api/login-action')}}",
            data: $('#login-form').serialize(),
            processData:false,
            timeout: 60000,
            beforeSend: function(){
                //$('#upload-demo-submit-btn').attr("disabled","disabled");
            },
            success: function(response){
                $('#loading-div').hide();
                if(response.status == 'success') {
                    window.location.replace("{{url('/dashboard-page')}}");
                } else {
                    alert(response.message);
                }

            },
            error: function(){
                $('#loading-div').hide();
                alert('Something wrong !');
            },
        });
    }
</script>

</body>
</html>

