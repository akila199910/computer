<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Welcome</title>
</head>
<body>

    Hi {{ $user->name }},<br>
    Welcome to  Suhas Computers.<br>
    We are happy to have you with us.<br>
    Your account has been created successfully.<br>
    Your email is {{ $user->email }}.<br>
    Your password is {{ $user->password }}.<br>
    <br>
    Best Regards,<br>
</body>
</html>
