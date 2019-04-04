<html>
<head>
    <title> G C</title>

    <style type="text/css">

        #logo {
            text-align: center;
            width: 200px;
            display: block;
            margin: 100px auto;
            border: 2px solid #2980b9;
            padding: 10px;
            background: none;
            color: #2980b9;
            cursor: pointer;
            text-decoration: none;
        }

    </style>

    <?php
    $login_url = 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode('https://www.googleapis.com/auth/calendar') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';

    ?>
</head>
<body>
<a id="logo" href="<?= $login_url ?>">Login with Google</a>
</body>
</html>