<?php
include "include/base_includes.php";
$navLinks = GetNavLinks();

//Get the Contents of php file and use in the place of a variable in layout.php
//ob_start();
include "include/login_form.php"; // Include navigation.php and buffer its output
//$content = ob_get_clean(); // Assign the buffered content to $navigationContent


include("include/view/layout.php");

