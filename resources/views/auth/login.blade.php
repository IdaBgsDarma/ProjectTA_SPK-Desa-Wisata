<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="/assets/css/style1.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

    <div class="wrapper">

        <div class="vh-100 d-flex justify-content-center align-items-center flex-column">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h1>Login</h1>
            <div class="input-box">
                    <input type="email" class="form-control" name="email">
                    <i class='bx bxs-user'></i>
                    <x-input-error :messages="$errors->get('email')" style="color:red;font-weight: bold;" />
            </div>
            <div class="input-box">
                <input type="password" class="form-control" name="password">
                <i class='bx bx-lock-alt'></i>
                <x-input-error :messages="$errors->get('password')" style="color:red;font-weight: bold;" />
            </div>
            <button class="btn btn-primary" type="submit">Login</button>
        </form>
    </div>
</div>

</body>
</html>
