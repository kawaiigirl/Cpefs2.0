<?php
class Validator
{
	private array $errors = []; // A variable to store a list of error messages

	// Validate blank only
	function General($theinput,$description =''): bool
    {
		if (trim($theinput) != "" )
			return true;
		else
		{
			$this->errors[] = $description;
			return false;
		}
	}
	// Validate email address
	function Email($themail,$description = ''): bool
    {
        $clean_email = filter_var($themail,FILTER_SANITIZE_EMAIL);

        if ($themail == $clean_email && filter_var($themail,FILTER_VALIDATE_EMAIL)){
            return true;
        }
        else
        {
            if($description=="")
                $this->errors[] = "Invalid email address specified";
            else
                $this->errors[] = $description;
            return false;
        }
	}
	// Validate numbers only
	function Number($theinput,$description = ''): bool
    {
		if (is_numeric($theinput)) 
			return true;
		else
		{ 
			$this->errors[] = $description; 
			return false; 
		}
	}

	// Check whether any errors have been found (i.e. validation has returned false)
	// since the object was created
	function foundErrors(): bool
    {
		if (count($this->errors) > 0)
			return true;
		else
			return false;
	}

	// Return a string containing a list of errors found,
	// Seperated by a given deliminator
	function listErrors()
	{
	?>
		<table width="100%" cellspacing="4" cellpadding="0" border="0" align="center" class="error">
		  <tr>
			<td align="left"> 
			  <?php
			   if($this->foundErrors())
				   foreach($this->errors as $key)
					    echo "<li>$key</li>";
			  ?>
			  <br>
			</td>
	      </tr>
		  </table><br />
	<?php
	}
	// Manually add something to the list of errors
	function addError($description)
	{
		$this->errors[] = $description;
	}	
}