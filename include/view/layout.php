<?php
function AddGenericHead($filePath = "",$links="") : void
{
    ?>
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=$filePath?>include/view/column.css">
    <link rel="stylesheet" href="<?=$filePath?>include/view/tables.css">
    <link rel="stylesheet" href="<?=$filePath?>include/view/styles.css">
    <link rel="stylesheet" href="<?=$filePath?>include/view/input_style.css">
    <?=$links?>
    <title>CPEFS Holiday Units</title>
</head>
<?php
}
function GetNavLinks(): array
{
    $loggedIn = isset($_SESSION['member_id']);
    $navLinks = array();
    if($loggedIn)
    {
        $navLinks[] = array("href" => "units.php", "text" => "Units");
        $navLinks[] = array("href" => "unit_availability.php", "text" => "Unit Availability");
        $navLinks[] = array("href" => "my_account.php", "text" => "My Account");
        $navLinks[] = array("href" => "my_bookings.php", "text" => "My Bookings");
        $navLinks[] = array("href" => "make_booking.php", "text" => "Make Booking");
    }

    $navLinks[] = array("href" => "contact.php", "text" => "Contact Us");
    $navLinks[] =  array("href" => "legals.php", "text" => "Legals");
    if($loggedIn)
    {
        $navLinks[] = array("href" => "logout.php", "text" => "Logout");
    }
    else
    {
        $navLinks[] = array("href" => "login.php", "text" => "Login");
    }
    return $navLinks;
}

function AddHeader_StartMain($navLinks,$filePath = "") : void
{
    ?>
<body>
<header>
    <div class="container">
        <div class="banner">
            <a href="<?=SITE_URL?>" ><img src="<?=$filePath?>include/view/images/logo.png" alt="CPEFS Holiday Units"></a>
        </div>
    </div>
</header>
<nav>
    <div class="container">
        <button class="nav-button navButtonWidth" onclick="toggleNavLinks()"><span class="menuClose">Close </span>Menu</button>
        <div class="nav-links">
            <?php
            foreach($navLinks as $link)
            {
                echo "<a href='".$link['href']."' class='nav-link'>".$link['text']."</a>";
            }
            ?>
        </div>
    </div>
</nav>
<main>
    <div class="container">
 <?php
}
function AddFooter_CloseMain() : void
{
    ?>
    </div>
</main>

<footer>
    <div class="container">
        <p>&copy; <?=getCurrentYear();?> CPEFS. All rights reserved.</p>
    </div>
</footer>
</body>
    <script>
        function toggleNavLinks() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('show');
            const navButton = document.querySelector('.nav-button');
            navButton.classList.toggle('navButtonWidth');
            const menuClose = document.querySelector('.menuClose');
            menuClose.classList.toggle('show');
        }
    </script>
<?php
}
