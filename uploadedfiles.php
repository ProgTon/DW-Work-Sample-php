<!DOCTYPE html>
<?php require'includes/allpages.php';

// EXIT FORM IF PAGE IS REFRESHED INSTEAD OF RESENDING DATA
if($_SESSION['stopForm'] > 0){
	$_SESSION['stopForm'] = 0;
	header('Location: uploadedfiles.php');
	exit;
}
?>
<html>
<head>
	<?php include'includes/head.php' ?>
	<title>Transactions</title>
	<script>

	</script>
</head>
<body>
<?php 
$message = '';
if (isset($_POST['process'])){
	$message = 'Processing files, please wait..';
}
else if(isset($_POST['deleteUnprocessed']) || isset($_POST['deleteProcessed'])){
	$message = 'Delete files, please wait..';
}
?>
	<div class="se-pre-con"><h1 class="loadMessage"><?= $message ?></h1></div>
	<?php
	/* ============== DEV TEST START =============== */
	// Write ?reset in the end of URL to reset all processed files to unprocessed
	$query = $_SERVER['QUERY_STRING'];
	echo $query;
	if($query == 'reset'){
		$visitor->reset('processed');
		$files = array_diff(scandir($folders['HistoryFolder']), array('.', '..'));
		for($i=2;$i<count($files)+2;$i++){
			rename($folders['HistoryFolder'] . $files[$i], $folders['UnprocessedFolder'] . $files[$i]);
		}
	}
	/* ============== DEV TEST END =============== */
	?>
<?php include'includes/header.php' ?>
	<main>
		<section>
			<ul class="tab">
			  <li><a href="javascript:void(0)" id="UnprocessedTab" class="tablinks active" data-tabName="Unprocessed">Unprocessed</a></li>
				<li><a href="javascript:void(0)" id="HistoryTab" class="tablinks" data-tabName="History">History</a></li>
			  <li><a href="javascript:void(0)" id="ProcessTab" class="tablinks" data-tabName="Process">Under process</a></li>

			</ul>
			<div id="Process" class="tabcontent align-left">
				<h3>Files under process in <?= $folders['ProcessFolder'] ?></h3>
				<div id="details" class="clearfix">
						<h5>Details will remain until page is refreshed. <br>A refresh is needed to change tab</h5>
						<button onclick="location.reload();">REFRESH</button>
				</div>

				<?php
				//If form has been submitted with unprocessed files
				$filesExist = '';
				if (isset($_POST['process'])){
					$processFile = $_POST['unprocessedFilesP'];

					if($processFile){
						foreach($processFile as $filename){
							if(!file_exists($folders['UnprocessedFolder'] . $filename)){
								continue;
							}
							
							$result = $visitor->fetch('fileRows', $filename);
							if($result['Status'] == 'success')
								$rowsCountDB = $result['Result']['TotalNumberOfRows'];
							else
								echo 'Fail';

							$filesExist = 1;
							echo span('Process: ' . $filename, 'process-row');
							echo '<div class="processedContent">';
							$startDate = date('Y-m-d H:i:s');
							rename($folders['UnprocessedFolder'].$filename, $folders['ProcessFolder'].$filename);
							$file = fopen($folders['ProcessFolder'] . $filename, 'r');
							$rowC = 0; //Row-counter
							$rowErrors = 0; //Row-errors counter
							while(! feof($file))
							{
								$rowC++;
								$fileLine = fgetcsv($file);
								
								if(!$fileLine[0]){
									echo span(span('(ROW: '.$rowC.') Error: ', 'red') . ' Row is empty', 'process-row');
									$rowErrors++;
									continue;
								}

								//CREATING VARIABLES FROM THE CSV-FILE AND REMOVES WHITE-SPACE
								$transactionID = preg_replace('/\s+/', '', $fileLine['0']);
								$transactionDate = preg_replace('/\s+/', '', $fileLine['1']);
								$transactionDescription = trim($fileLine['2'], ' ');
								$amount = preg_replace('/\s+/', '', $fileLine['3']);
								
								//CONVERTS STRING TO UTF-8
								$transactionDescription = iconv(mb_detect_encoding($transactionDescription, mb_detect_order(), true), "UTF-8", $transactionDescription);
								//TRIMS TEXT TO MAX-LENGTH 33. (30 because three periods are added if text is trimmed)
								$transactionDescription = trim_text($transactionDescription, '30');
								
								//CHECKS IF YEAR SHOULD BE 19* OR 20*
								$transactionDateUpdated = str_replace("/", "-", $transactionDate);
								$transactionDateArr = explode('-', $transactionDateUpdated);
								if($transactionDateArr[0] > date('y-m-d'))
									$transactionDateUpdated = '19'.$transactionDateUpdated;
								else
									$transactionDateUpdated = '20'.$transactionDateUpdated;
								
								//LOCAL VALIDATION START
								//VALIDATE ROWS IN THE FILE BEFORE TRYING TO SEND THEM TO DATABASE
								$fileRowErrors = validateRows($transactionID, $transactionDate, $transactionDescription, $amount);
								if($fileRowErrors){
									echo '<span class="process-row">'.span('(ROW: '.$rowC.') Error:', 'red');
									foreach($fileRowErrors as $fileRowError){
										echo '<br>&emsp;&emsp;'.$fileRowError;
									}
									echo '</span>';
									$rowErrors++;
									continue;
								}
								//LOCAL VALIDATION END
								
								$insertRow = $visitor->insertRow($transactionID, $transactionDateUpdated, $amount, '', $filename);
								if($insertRow['Status'] == 'error'){
									echo span(span('(ROW: '.$rowC.') Error: ', 'red') . $insertRow['Result'], 'process-row');
									$rowErrors++;
								}
								else{
									$visitor->insertDescription($transactionDescription);
									$result = $visitor->fetch('transactions', $transactionDescription);
									if($result['Status'] == 'success'){
										$transactionDescriptionPK = $result['Result']['TransactionDescriptionPK'];
									}
									else{
										echo '<b>' . $result['Result'] . '</b>';
									}
									
									$visitor->updateTransactions($transactionDescriptionPK, $transactionID);
								}
							}
							
							if(!$rowErrors){
								echo span('No errors occurred', 'process-row green');
							}
							$succededRows = $rowsCountDB - $rowErrors;
							if($succededRows == 0) {
								$rowsStatusColor = 'red';
							}
							else if($rowErrors == 0){
								$rowsStatusColor = 'green';
							}
							else{
								$rowsStatusColor = 'orange';
							}
							echo span($succededRows . '/'.$rowsCountDB.' row(s) was successfully sent to database.', 'process-row '.$rowsStatusColor);

							fclose($file);
							rename($folders['ProcessFolder'].$filename, $folders['HistoryFolder'].$filename);
							$endDate = date('Y-m-d H:i:s');
							$visitor->updateCSV($filename, $rowErrors, $startDate, $endDate);
							echo '</span><hr></div>';
						}
					}
					if(!$processFile || !$filesExist){
						echo span('No files selected for processing.', 'process-row').'<hr>';
					}

					
					$_SESSION['stopForm'] = 1;
				}
				else if(isset($_POST['deleteUnprocessed'])){
					$deleteFile = $_POST['unprocessedFilesD'];
					if($deleteFile){
						foreach($deleteFile as $filename){
							if(!file_exists($folders['UnprocessedFolder'] . $filename)){
								continue;
							}
							$filesExist = 1;
							echo '<span class="process-row">';
							echo 'Delete: '.$filename;
							echo '<div class="processedContent">';
							$visitor->delete($filename, 'unprocessed');
							unlink($folders['UnprocessedFolder'].$filename);
							echo span('No errors occurred', 'green');
							echo '</span><hr></div>';
						}
					}
					
					if(!$deleteFile || !$filesExist){
						echo span('No files selected for deleting.', 'process-row');
					}
				}
				//If form has been submitted with processed files
				else if (isset($_POST['deleteProcessed'])){
					$deleteFile = $_POST['processedFilesD'];
					if($deleteFile){
						foreach($deleteFile as $filename){
							if(!file_exists($folders['HistoryFolder'] . $filename)){
								continue;
							}
							$filesExist = 1;
							echo '<span class="process-row">';
							echo 'Delete: '.$filename;
							echo '<div class="processedContent">';
							$visitor->delete($filename, 'processed');
							unlink($folders['HistoryFolder'].$filename);
							echo span('No errors occurred', 'green');
							echo '</span><hr></div>';
						}
					}
					if(!$deleteFile || !$filesExist){
						echo span("No files selected for deleting.", 'process-row');
					}
					$_SESSION['stopForm'] = 1;
				}
				else{
					echo span('No files selected for processing.', 'process-row');
					echo '<hr>';
					echo span('No files selected for deleting.', 'process-row');
				}
				?>
			</div>
			<div id="Unprocessed" class="tabcontent clearfix" style="display: block;">

			  <h3>Files in <?= $folders['UnprocessedFolder'] ?></h3>
				<form method="post" class="processForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<?php
					$filesUnprocessed = $visitor->fetch('files', 'unprocessed');
					if($filesUnprocessed['Status'] == 'error'){
						echo $filesUnprocessed['Result'];
					}
					else if($filesUnprocessed['Status'] == 'null'){
						echo $filesUnprocessed['Result'];
					}
					else{
						$filesUnprocessed = $filesUnprocessed['Result'];
						$tableColumns = array('Delete', 'Filename', 'TotalNumberOfRows', 'CreationDate');
					?>
						<table>
							<thead>
								<tr>
									<?php
										foreach($tableColumns as $column)
											echo '<th>'.$column.'</th>';
									?>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($filesUnprocessed as $file){
									echo '<tr>';
										echo '<td class="withCheckbox"><input class="checkbox" name="unprocessedFilesD[]" type="checkbox" value="'.$file['Filename'].'"></td>';
										echo '<input type="hidden" value="'.$file['Filename'].'" name="unprocessedFilesP[]">';
										echo '<td class="Filename">' . $file['Filename'].'</td>';
										echo '<td>' . $file['TotalNumberOfRows'].'</td>';
										echo '<td class="date">' . $file['CreationDate'].'</td>';
									echo '</tr>';
								}
								?>
							</tbody>
						</table>
						<input type="submit" value="Delete selected files" class="deleteButton" name="deleteUnprocessed">
						<input type="submit" value="Process all files" class="processButton" name="process">
					<?php
					}
				?>

				</form>
			</div>
			<div id="History" class="tabcontent clearfix">
				<form method="post" class="processForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			  <h3>Files in <?= $folders['HistoryFolder'] ?></h3>
				<?php
					$filesProcessed = $visitor->fetch('files', 'processed');
					if($filesProcessed['Status'] == 'error'){
						echo $filesProcessed['Result'];
					}
					else if($filesProcessed['Status'] == 'null'){
						echo $filesProcessed['Result'];
					}
					else{

						$filesProcessed = $filesProcessed['Result'];
						$tableColumns = array('Delete', 'Filename', 'TotalNumberOfRows', 'RowsWithErrors', 'StartDate', 'EndDate', 'CreationDate');
						?>
						<h5>Deleting a processed file will delete all its content in the database</h5>
						<table>
							<thead>
								<tr>
									<?php
										foreach($tableColumns as $column)
											echo '<th>'.$column.'</th>';
									?>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($filesProcessed as $file){
									echo '<tr>';
									echo '<td class="withCheckbox"><input class="checkbox" name="processedFilesD[]" type="checkbox" value="'.$file['Filename'].'"></td>';
										echo '<td class="Filename">' . $file['Filename'].'</td>';
										echo '<td>' . $file['TotalNumberOfRows'].'</td>';
										echo '<td>' . $file['RowsWithErrors'].'</td>';
										echo '<td class="date">' . $file['StartDate'].'</td>';
										echo '<td class="date">' . $file['EndDate'].'</td>';
										echo '<td class="date">' . $file['CreationDate'].'</td>';
									echo '</tr>';
								}
								?>
							</tbody>
						</table>
						<input type="submit" value="Delete selected files" class="deleteButton" name="deleteProcessed">
					<?php
					}
				?>
			</div>
				</form>

		</section>
	</main>

<?php include'includes/foot.php' ?>
</body>
</html>
