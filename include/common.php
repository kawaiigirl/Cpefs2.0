<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
ini_set('post_max_size', '100M');
ini_set('upload_max_filesize', '100M');
include_once "classes/class_DBC.php";
include_once "dbconnect.php";

include_once "commonFunction.php";

include "functions_booking.php";
include "functions_units.php";

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

//todo::fix this mess


function pagination($targetPage, $query_string, $total_pages, $page): void
{

   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;

	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	//$lastPage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lastPage = $total_pages;
	$lpm1 = $lastPage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
   $pagination = "";
    $counter = 1;
	if($lastPage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetPage?page=$prev$query_string\"><< Previous</a>";
		else
			$pagination.= "<span class=\"disabled\"><< Previous</span>";	
		
		//pages	
		if ($lastPage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastPage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetPage?page=$counter$query_string\">$counter</a>";
			}
		}
		elseif($lastPage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetPage?page=$counter$query_string\">$counter</a>";
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetPage?page=$lpm1$query_string\">$lpm1</a>";
				$pagination.= "<a href=\"$targetPage?page=$lastPage$query_string\">$lastpage</a>";
			}
			//in middle; hide some front and some back
			elseif($lastPage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetPage?page=1$query_string\">1</a>";
				$pagination.= "<a href=\"$targetPage?page=2$query_string\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetPage?page=$counter$query_string\">$counter</a>";
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetPage?page=$lpm1$query_string\">$lpm1</a>";
				$pagination.= "<a href=\"$targetPage?page=$lastPage$query_string\">$lastPage</a>";
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetPage?page=1$query_string\">1</a>";
				$pagination.= "<a href=\"$targetPage?page=2$query_string\">2</a>";
				$pagination.= "...";
				for ($counter = $lastPage - (2 + ($adjacents * 2)); $counter <= $lastPage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class='current'>$counter</span>";
					else
						$pagination.= "<a href='$targetPage?page=$counter$query_string'>$counter</a>";
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href='$targetPage?page=$next$query_string'>Next >></a>";
		else
			$pagination.= "<span class='disabled'>Next >></span>";
		$pagination.= "</div>\n";		
	}
   echo $pagination;
}
