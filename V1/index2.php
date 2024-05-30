<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMS Landing Page</title>
    <link rel="stylesheet" href="styleslandingpage.css">
    <script>
        function redirectToLogin() {
            window.location.href = "admin.php";
        }
    </script>
</head>

<body onclick="redirectToLogin()">
    <header class="main-header"></header>
    <div class = "banner">
        <div class = "LPContainer"></div>
    </div>
</body>
</html>
