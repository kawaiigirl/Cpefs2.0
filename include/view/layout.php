<?php
global $navLinks,$content;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="include/view/styles.css">
    <title>CPEFS Holiday Units</title>
</head>
<body>
<header>
    <div class="container">
        <h1>CPEFS Holiday Units</h1>
    </div>
</header>
<div class="container">
    <div class="banner">
        <img src="your-banner-image.jpg" alt="Banner Image">
    </div>
    <nav>
        <?php
        foreach($navLinks as $link)
        {
            echo "<a href='".$link['href']."' class='nav-link'>".$link['text']."</a>";
        }
        ?>
    </nav>
    <main>
        <?php
        echo $content;
        ?>
    </main>
</div>
<footer>
    <div class="container">
        <p>&copy; 2024 CPEFS. All rights reserved. | <a href="#" class="nav-link">Privacy Policy</a></p>
    </div>
</footer>
</body>
</html>

