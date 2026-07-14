<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hindi Karyashala Portal</title>

    <link rel="stylesheet" href="css/login.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="overlay">

    <div class="login-box">

        <div class="logo-section">

            <i class="fa-solid fa-book-open logo-icon"></i>

            <h1>Hindi Karyashala Portal</h1>

            <p>Official Workshop Management System</p>

        </div>

        <form action="login.php" method="POST">

            <div class="input-group">

                <label>IC Number</label>

                <input
                    type="number"
                    name="ic_no"
                    placeholder="Enter IC Number"
                    required>

            </div>

            <div class="input-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter Password"
                    required>

            </div>

            <button
                type="submit"
                class="btn-login">

                <i class="fa-solid fa-right-to-bracket"></i>

                Login

            </button>

        </form>

        <div class="footer-text">

            © 2026 Hindi Karyashala Portal

        </div>

    </div>

</div>

</body>

</html>