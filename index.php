<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Hindi Karyashala Portal</title>

<link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="login-container">

    <div class="login-box">

        <h1>हिंदी कार्यशाला पोर्टल</h1>

        <form action="login.php" method="POST" onsubmit="return validateForm();">

            <div class="form-group">

                <label>IC Number</label>

                <input
                    type="number"
                    name="ic_no"
                    id="ic_no"
                    placeholder="Enter IC Number"
                    required>

            </div>

            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Enter Password"
                    required>

            </div>

            <div class="form-group">

                <label>Login As</label>

                <select name="role" id="role" required>

                    <option value="">Select Role</option>

                    <option value="karyashala">
                        Karyashala Admin
                    </option>

                    <option value="admin">
                        Admin
                    </option>

                </select>

            </div>

            <button type="submit">
                Login
            </button>

            <p id="error"></p>

        </form>

    </div>

</div>

<script src="js/validation.js"></script>

</body>

</html>