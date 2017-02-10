<?php


/*$IDStartArr = array(180, 200, 220, 240, 260, 280, 300);
$mArr = array('05', '06', '07', '08', '09', '10', '11');
$FileNumber = 2;*/

$list = array
(
	"ID,Date,Description,Amount"
);

$idStart = 1;


for($i=$idStart; $i<= 100000; $i++){
	$c = mt_rand(1, 100);
	$d = mt_rand(1, 31);
	$m = mt_rand(1, 12);


	$date = '15/' . $m . '/' . $d;
	$description = 'Test'. $c;
	$amount = mt_rand(-1000, 1000);
	if(mt_rand(1, 100) < 20){
		$amount = $amount.'.99';
	}
	else{
		$amount = $amount.'.00';
	}

	array_push($list, $i . ',' . $date . ',' . $description . ',' . $amount );
}

foreach($list as $line){
	echo $line.'<br>';
}

$file = fopen("SampleTransactions HUGE2.csv","w");

foreach ($list as $line)
	{
		fputcsv($file,explode(',',$line));
	}

  fclose($file);

 ?>
