<?php

/**
 * trims text to a space then adds ellipses if desired
 * @param string $input text to trim
 * @param int $length in characters to trim to
 * @param bool $ellipses if ellipses (...) are to be added
 * @param bool $strip_html if html tags are to be stripped
 * @return string 
 */
function trim_text($input, $length, $ellipses = true, $strip_html = true) {
    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags($input);
    }
  
    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
        return $input;
    }
  
    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    $trimmed_text = substr($input, 0, $last_space);
  
    //add ellipses (...)
    if ($ellipses) {
        $trimmed_text .= '...';
    }
  
    return $trimmed_text;
}
		
function get_file_error($file, $error, $folders){
	
  if($error > 0){
		$phpFileUploadErrors = array(
			0 => 'There is no error, the file uploaded with success',
			1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
			2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
			3 => 'The uploaded file was only partially uploaded',
			4 => 'No file was uploaded',
			6 => 'Missing a temporary folder',
			7 => 'Failed to write file to disk.',
			8 => 'A PHP extension stopped the file upload.'
		);
	return $phpFileUploadErrors[$error];
		}
  else if (file_exists($folders['UnprocessedFolder'] . $file)) {
    return 'A file with this name already exists in '.$folders['UnprocessedFolder'];
  }
  else if (file_exists($folders['ProcessFolder'] . $file)) {
    return 'A file with this name already exists in '.$folders['ProcessFolder'];
  }
  else if (file_exists($folders['HistoryFolder'] . $file)) {
    return 'A file with this name already exists in '.$folders['HistoryFolder'];
  }

}

function validateRows($transactionID, $transactionDate, $transactionDescription, $amount){
	
  $fileRowErrors = array();
  $res_ID = '';
  $res_Date = '';
  $res_Amount = '';
	
	//CHECK COLUMNCOUNT
	if(!$transactionID || !$transactionDate || !$transactionDescription || !$amount){
		$res = 'Row must have 4 values (ID, Date, Description, Amount)';
		array_push($fileRowErrors, $res);
		return $fileRowErrors;
	}
	
  /* CHECK ID */
  if(!is_numeric($transactionID)){
    $res_ID = '<span class="red">'.$transactionID.'</span>: ID can only contain numbers.';
  }
  else{
    if((int)$transactionID != $transactionID){
      $res_ID = '<span class="red">'.$transactionID.'</span>: ID can only be integer (whole numbers).';
    }
  }

  /* CHECK DATE */
  $transactionDateNew = str_replace("/", "-", $transactionDate);
  $date = explode('-', $transactionDateNew);
  $year4 = DateTime::createFromFormat('Y', $date[0]);
  $res_year4 = $year4 && $year4->format('Y') === $date[0];
  $year2 = DateTime::createFromFormat('y', $date[0]);
  $res_year2 = $year2 && $year2->format('y') === $date[0];
	
	if(count($date) == 3){
		// checkdate(m, d, y) date[1] = m. date[2] = d. date[0] = y.
		if(!checkdate(@$date[1], @$date[2], @$date[0]) || @$res_year4 == 0 && @$res_year2 == 0){
			$res_Date = '<span class="red">'.$transactionDate.'</span>: Date must be of type YYYY-MM-DD or YY-MM-DD (with - or /) with valid values.';
		}
	}
	else{
		$res_Date = '<span class="red">'.$transactionDate.'</span>: Date must be of type YYYY-MM-DD or YY-MM-DD (with - or /) with valid values.';
	}

  /* CHECK DESCRIPTION */

  /* CHECK AMOUNT */
  if(!is_numeric(@$amount)) {
    @$res_Amount = '<span class="red">'.@$amount.'</span>: Amount must be numeric. Only periods is allowed for decimals.';
  }

    //ADD ERRORS TO ERROR-ARRAY
    if($res_ID) array_push($fileRowErrors, $res_ID);
    if($res_Date) array_push($fileRowErrors, $res_Date);
    if($res_Amount) array_push($fileRowErrors, $res_Amount);

    return $fileRowErrors;
}



function span($text, $class){
	return '<span class="'.$class.'">'.$text.'</span>';
}


?>
