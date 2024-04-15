<?php
function AddGenericHead($filePath = "") : void
{
    ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=$filePath?>include/view/styles.css">
    <link rel="stylesheet" href="<?=$filePath?>include/view/column.css">
    <link rel="stylesheet" href="<?=$filePath?>include/view/input_style.css">
    <title>CPEFS Holiday Units</title>
</head>
<?php
}
function AddHeader_StartMain($navLinks,$filePath = "") : void
{
    ?>
<body>
<header>
    <div class="container">
        <div class="banner">
            <img src="<?=$filePath?>include/view/images/logo.png" alt="CPEFS Holiday Units">
            <nav>
                <div class="nav-links">
                <?php
                foreach($navLinks as $link)
                {
                    echo "<a href='".$link['href']."' class='nav-link'>".$link['text']."</a>";
                }
                ?>
                </div>
                <button class="nav-button" onclick="toggleNavLinks()">Menu</button>
            </nav>
        </div>
    </div>
</header>
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
        <p>&copy; 2024 CPEFS. All rights reserved.</p>
    </div>
</footer>
</body>
    <script>
        function toggleNavLinks() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('show');
        }
    </script>
<?php
}
