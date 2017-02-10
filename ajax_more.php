<?php
if(isset($_POST["shown"]) && !empty($_POST["shown"])){
//SPECIFIES WHERE TO START GETING VALUES
$start = $_POST['shown'];

//include database configuration file
include('config/dbconfig.php');

//count all rows except already displayed

/* $queryAll = mysqli_query($con,"SELECT COUNT(*) as num_rows FROM tutorials WHERE id < ".$_POST['id']." ORDER BY id DESC");
$row = mysqli_fetch_assoc($queryAll);
$allRows = $row['num_rows']; */

$queryALL = $visitor->queryALL();
$allRows = $queryALL['num_rows'];
$rowsLeft = $start - $allRows;

//SPECIFIES HOW MANY RESULTS TO GET EACH CLICK
$showLimit = 50;

//get rows query
//$query = mysqli_query($con, "SELECT * FROM tutorials WHERE id < ".$_POST['id']." ORDER BY id DESC LIMIT ".$showLimit);

$queries = $visitor->query($start, $showLimit);
//print_r($queries);


//number of rows
$rowCount = count($queries);
//echo 'rowCount: '.$rowCount;

	if($rowCount > 0){
    foreach($queries as $row){
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
<?php 
		} 
	}	
}
?>