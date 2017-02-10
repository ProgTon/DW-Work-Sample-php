<!DOCTYPE html>
<?php require'includes/allpages.php' ?>
<html>
<head>
	<?php include'includes/head.php' ?>

	<title>Transactions</title>
</head>
<body>
	<div class="se-pre-con"><h1 class="loadMessage">Retrieving  transactions, please wait..</h1></div>
<?php include'includes/header.php' ?>
	<main>
		<section>
			<?php
				$result = $visitor->fetch('All', '');
				if($result['Status'] == 'error'){
					echo $result['Result'];
				}
				else{
					$result = $result['Result'];
					$tableColumns = array();
					array_push($tableColumns, 't.TransactionID');
					array_push($tableColumns, 't.TransactionDate');
					array_push($tableColumns, 't.Amount');
					array_push($tableColumns, 'td.Description');
					array_push($tableColumns, 'f.StartDate');
					array_push($tableColumns, 'f.EndDate');
					array_push($tableColumns, 'f.Filename');

				?>
				<table>
					<thead>
						<tr>
							<?php
								foreach($tableColumns as $column){
								echo '<th>'.$column.'</th>';
								}
							?>
						</tr>
					</thead>
					<tbody class="list">
						<?php
							foreach($result as $row){
								$description = $row['Description'];
								$description = str_replace('<', '&lt;', $description);
								$description = str_replace('>', '&gt;', $description);
							?>
								<tr class="list-item">
									<td class="idCol"><?= $row['TransactionID'] ?></td>
									<td class="dateCol"><?= $row['TransactionDate'] ?></td>
									<td class="amountCol"><?= $row['Amount'] ?></td>
									<td class="descCol"><?= $description ?></td>
									<td class="sDateCol"><?= $row['StartDate'] ?></td>
									<td class="eDateCol"><?= $row['EndDate'] ?></td>
									<td class="nameCol"><?= $row['Filename'] ?></td>
								</tr>
							<?php } ?>
					</tbody>
				</table>


	<div class="show_more_main">
		<span class="show_more" title="Load more transactions">Show more</span>
		<span class="loding" style="display: none;">
			<span class="loding_txt">Loading....</span>
		</span>
	</div>
	<?php } ?>
		</section>
	</main>

<?php include'includes/foot.php' ?>
</body>
</html>
