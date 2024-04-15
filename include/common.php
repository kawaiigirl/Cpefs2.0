<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
ini_set('post_max_size', '100M');
ini_set('upload_max_filesize', '100M');
include_once "dbc/class_DBC.php";
include_once "dbconnect.php";

include_once "commonFunction.php";

include "functions_booking.php";
include "functions_units.php";

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

//todo::fix this mess


function pagination($targetpage,$query_string,$total_pages,$page)
{

   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;

	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	//$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lastpage = $total_pages;
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
   $pagination = "";
    $counter = 1;
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage?page=$prev$query_string\"><< Previous</a>";
		else
			$pagination.= "<span class=\"disabled\"><< Previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage?page=$counter$query_string\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter$query_string\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1$query_string\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage$query_string\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage?page=1$query_string\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2$query_string\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter$query_string\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1$query_string\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage$query_string\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage?page=1$query_string\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2$query_string\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class='current'>$counter</span>";
					else
						$pagination.= "<a href='$targetpage?page=$counter$query_string'>$counter</a>";
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href='$targetpage?page=$next$query_string'>Next >></a>";
		else
			$pagination.= "<span class='disabled'>Next >></span>";
		$pagination.= "</div>\n";		
	}
   echo $pagination;
}
