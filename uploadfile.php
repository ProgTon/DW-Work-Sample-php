<!DOCTYPE html>
<?php require'includes/allpages.php' ?>
<html>
<head>
	<?php include'includes/head.php' ?>
	<title>Transactions</title>
</head>
<body>
<?php include'includes/header.php' ?>
	<main>
		<h2>Uploading files</h2>
		<section>

		<?php
		if ( isset($_FILES["file"])) {
			$files = $_FILES['file'];

			$tableColumns = array('Filename', 'Type', 'Size', 'Rows', 'Status', 'Message');
			?>
			<table class="fileDetails">
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
					echo 'Number of files: '.count($files['name']).'<br>';
						for($i=0;$i<count($files['name']);$i++){
						$file = $files['name'][$i];
						$error = $files['error'][$i];
						$fileWPath = $folders['UnprocessedFolder'] . $file;
						$type = $files['type'][$i];
						$size = number_format($files['size'][$i] / 1024, 2).' kB';
						$tmp_name = $files['tmp_name'][$i];
						$rowsCount = 0;
						
						if($tmp_name){
							$fileOpened = fopen($tmp_name, 'r');
							$rowsCount = 0; //Row-counter
							while(! feof($fileOpened))
							{
								$fileLine = fgetcsv($fileOpened);
								$rowsCount++;
							}
							fclose($fileOpened);
						}
					
			
							
						$message = get_file_error($file, $error, $folders);
						if($message || $error > 0){
							$status = '<span class="red">Fail</span>';
						}
						else{
							move_uploaded_file($tmp_name, $fileWPath);
							$insertCSV = $visitor->insertCSV($file, $rowsCount, '', '', '');
							$status = '<span class="green">Success</span>';
						}
						$tableColumns = array($file, $type, $size, $rowsCount, $status, $message);
					?>
					<tr>
						<?php
							foreach($tableColumns as $column) 
								echo '<td>'.$column.'</td>';
						?>
					</tr>
					<?php 
					} ?>
				</tbody>
			</table>
			<?php
		}
		else{
			echo 'No files selected';
		}
?>
		</section>
	</main>

<?php include'includes/foot.php' ?>
</body>
</html>
