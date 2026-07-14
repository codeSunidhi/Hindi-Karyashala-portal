<?php

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<div class="navbar">

    <div class="navbar-left">

        <i class="fa-solid fa-book-open"></i>

        <span class="portal-title">

            Hindi Karyashala Portal

        </span>

    </div>

    <div class="navbar-right">

        <div class="user-info">

            <div class="user-name">

                <?php echo $_SESSION['name']; ?>

            </div>

            <div class="user-role">

                <?php echo $_SESSION['designation']; ?>

            </div>

            <div class="user-ic">

                IC No :
                <?php echo $_SESSION['ic_no']; ?>

            </div>

        </div>

        <a

        href="../logout.php"

        class="logout-btn"

        onclick="return confirm('Are you sure you want to logout?');"

        >

            <i class="fa-solid fa-right-from-bracket"></i>

            Logout

        </a>

    </div>

</div>