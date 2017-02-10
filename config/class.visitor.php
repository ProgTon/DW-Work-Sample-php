<?php
class VISITOR
{


	private $db;

	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}



	public function insertCSV($name, $rows, $errors, $startDate, $endDate){
		try
		{

			$stmt = $this->db->prepare("INSERT INTO file
										(Filename,
										TotalNumberOfRows,
										RowsWithErrors,
										StartDate,
										EndDate)
										VALUES
										(:Name,
										:Rows,
										:Errors,
										:StartDate,
										:EndDate)");
			$stmt->bindparam(':Name', $name);
			$stmt->bindparam(':Rows', $rows);
			$stmt->bindparam(':Errors', $errors);
			$stmt->bindparam(':StartDate', $startDate);
			$stmt->bindparam(':EndDate', $endDate);
			$stmt->execute();

		}
		catch(PDOException $e)
		 {
				 return $e->getMessage();
		 }
	}
	public function updateCSV($name, $errors, $startDate, $endDate){

		 try{
			$stmt = $this->db->prepare("UPDATE file
										SET RowsWithErrors = :Errors,
										StartDate = :StartDate,
										EndDate = :EndDate
										WHERE Filename = :Name");
			$stmt->bindparam(':Name', $name);
			$stmt->bindparam(':Errors', $errors);
			$stmt->bindparam(':StartDate', $startDate);
			$stmt->bindparam(':EndDate', $endDate);
			$stmt->execute();
		}
	 catch(PDOException $e)
	 {
		 return $e->getMessage();
	 }
 }

 	public function updateTransactions($transactionDescriptionPK, $transactionID){

		 try{
			$stmt = $this->db->prepare("UPDATE transactions
										SET TransactionDescriptionPK = :TransactionDescriptionPK
										WHERE TransactionID = :TransactionID");
			$stmt->bindparam(':TransactionDescriptionPK', $transactionDescriptionPK);
			$stmt->bindparam(':TransactionID', $transactionID);
			$stmt->execute();
		}
	 catch(PDOException $e)
	 {
		 return $e->getMessage();
	 }
 }

	public function insertRow($transactionID, $transactionDate, $amount, $transactionDescriptionPK, $file){
		try
		{

			$stmt = $this->db->prepare("INSERT INTO transactions
										(TransactionID,
										TransactionDate,
										Amount,
										TransactionDescriptionPK,
										FilePK)
										VALUES
										(:TransactionID,
										:TransactionDate,
										:Amount,
										:TransactionDescriptionPK,
										:FilePK)");
			$stmt->bindparam(':TransactionID', $transactionID);
			$stmt->bindparam(':TransactionDate', $transactionDate);
			$stmt->bindparam(':Amount', $amount);
			$stmt->bindparam(':TransactionDescriptionPK', $transactionDescriptionPK);
			$stmt->bindparam(':FilePK', $file);
			$result = $stmt->execute();
			$return = array('Status'=>'success', 'Result'=>'');

			set_time_limit(1800); //30 minutes

			return $return;

		}
		catch(PDOException $e)
		 {
				  $result = $e->getMessage();
			 		$result_arr = explode(':', $result);
			 		if($result_arr[0] == 'SQLSTATE[23000]'){
						$result = 'ID: '.$transactionID.' is already in use in the database';
					}
			 		else{
						$result = $e->getMessage();
					}
					$return = array('Status'=>'error', 'Result'=>$result);
					return $return;
		 }
	}

	public function insertDescription($transactionDescription){
		try
		{

			$stmt = $this->db->prepare("INSERT INTO transactiondescription
										(Description)
										VALUES
										(:TransactionDescription)");
			$stmt->bindparam(':TransactionDescription', $transactionDescription);
			$stmt->execute();

		}
		catch(PDOException $e)
		 {
				  return $e->getMessage();
		 }
	}

	public function fetch($table, $data){
		if($table == 'transactions'){
			try{
				$stmt = $this->db->prepare("SELECT TransactionDescriptionPK
																		FROM transactiondescription
																		WHERE Description = :Description");
				$stmt->bindparam(':Description', $data);
				$stmt->execute();
				$result=$stmt->fetch(PDO::FETCH_ASSOC);
				$return = array('Status'=>'success', 'Result'=>$result);
				return $return;

			}
			catch(PDOException $e)
			 {
					$result = $e->getMessage();
 					$return = array('Status'=>'error', 'Result'=>$result);
 					return $return;
			 }
		}
		else if($table == 'fileRows'){
			try{
				$stmt = $this->db->prepare("SELECT TotalNumberOfRows
																		FROM file
																		WHERE Filename = :Filename");
				$stmt->bindparam(':Filename', $data);
				$stmt->execute();
				$result=$stmt->fetch(PDO::FETCH_ASSOC);
				if(!$result){
					$result = 'No file found.';
					$return = array('Status'=>'error', 'Result'=>$result);
					return $return;
				}
				$return = array('Status'=>'success', 'Result'=>$result);
				return $return;
			}
			catch(PDOException $e)
			 {
					  $result = $e->getMessage();
						$return = array('Status'=>'error', 'Result'=>$result);
						return $return;
			 } 
		}
		else if($table == 'All'){
			try{
				$stmt = $this->db->prepare("SELECT
				t.TransactionID,
				t.TransactionDate,
				t.Amount,
				td.Description,
				f.StartDate,
				f.EndDate,
				f.Filename
				FROM
				transactions as t,
				transactiondescription as td,
				file as f
				WHERE t.FilePK = f.Filename AND
				t.TransactionDescriptionPK = td.TransactionDescriptionPK
				ORDER BY t.TransactionDate DESC, t.Amount ASC
				LIMIT 100");
				$stmt->execute();
				$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
				if(!$result){
					$result = 'No transactions found.';
					$return = array('Status'=>'error', 'Result'=>$result);
					return $return;
				}
				$return = array('Status'=>'success', 'Result'=>$result);
				return $return;
			}
			catch(PDOException $e)
			 {
					  $result = $e->getMessage();
						$return = array('Status'=>'error', 'Result'=>$result);
						return $return;
			 }
		}
		else if($table == 'files'){
			if($data == 'unprocessed'){
				try{
					$stmt = $this->db->prepare("SELECT
					Filename,
					TotalNumberOfRows,
					CreationDate
					FROM
					file
					WHERE StartDate = '0000-00-00 00:00:00'
					ORDER BY CreationDate ASC");
					$stmt->execute();
					$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
					if(!$result){
						$result = 'No unprocessed files found';
						$return = array('Status'=>'null','Result'=>$result);
					}
					else{
						$return = array('Status'=>'success','Result'=>$result);
					}

					return $return;
				}
				catch(PDOException $e)
				 {
						$result = $e->getMessage();
						$return = array('Status'=>'error', 'Result'=>$result);
						return $return;
				 }
			}
			else if($data == 'processed'){
				try{
					$stmt = $this->db->prepare("SELECT *
					FROM
					file
					WHERE StartDate > 0
					ORDER BY CreationDate DESC");
					$stmt->execute();
					$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
					if(!$result){
						$result = 'No processed files found';
						$return = array('Status'=>'null','Result'=>$result);
					}
					else{
						$return = array('Status'=>'success','Result'=>$result);
					}

					return $return;
				}
				catch(PDOException $e)
				 {
						$result = $e->getMessage();
						$return = array('Status'=>'error', 'Result'=>$result);
						return $return;
				 }
			}

		}


	}

	public function delete($data, $status){
		if($status == 'unprocessed'){
			try{
				$stmt = $this->db->prepare("DELETE FROM file WHERE Filename = :Filename");
				$stmt->bindparam(':Filename', $data);
				$stmt->execute();

			}
			catch(PDOException $e)
			 {
					$result =  $e->getMessage();
					$return = array('Status'=>'error', 'Result'=>$result);
					return $return;
			 }
		 }
		 else if($status == 'processed'){
			 try{
 				$stmt = $this->db->prepare("DELETE FROM file WHERE Filename = :Filename");
				$stmt->bindparam(':Filename', $data);
 				$stmt->execute();

				$stmt = $this->db->prepare("DELETE FROM transactions WHERE FilePK = :Filename");
				$stmt->bindparam(':Filename', $data);
 				$stmt->execute();

 			}
 			catch(PDOException $e)
 			 {
 					$result = $e->getMessage();
 					$return = array('Status'=>'error', 'Result'=>$result);
 					return $return;
 			 }
		 }
	 }
	 
	 public function queryALL(){
			try{
				$stmt = $this->db->prepare("SELECT COUNT(*) as num_rows 
				FROM transactions");
				$stmt->execute();
				$result=$stmt->fetch();
				return $result;

			}
			catch(PDOException $e)
			 {
					$result =  $e->getMessage();
					$return = array('Status'=>'error', 'Result'=>$result);
					return $return;
			 }
	 }
	 
	 public function query($start, $showLimit){
			try{
				$stmt = $this->db->prepare("SELECT
				t.TransactionID,
				t.TransactionDate,
				t.Amount,
				td.Description,
				f.StartDate,
				f.EndDate,
				f.Filename
				FROM
				transactions as t,
				transactiondescription as td,
				file as f
				WHERE
				t.FilePK = f.Filename AND 
				t.TransactionDescriptionPK = td.TransactionDescriptionPK
				ORDER BY t.TransactionDate DESC, t.Amount ASC
				LIMIT " . $start . "," . $showLimit );
				$stmt->execute();
				$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
				return $result;
			}
			catch(PDOException $e)
			 {
					$result =  $e->getMessage();
					$return = array('Status'=>'error', 'Result'=>$result);
					return $return;
			 }
	 }

	 /* ============== DEV TEST START =============== */
	public function reset($content){
		if($content == 'processed'){
			try{
				$stmt = $this->db->prepare("UPDATE file
																		SET RowsWithErrors = 0,
																		StartDate = '0000-00-00 00:00:00',
																	 	EndDate= '0000-00-00 00:00:00'");
				$stmt->execute();

				$stmt = $this->db->prepare("TRUNCATE TABLE transactions");
				$stmt->execute();

				$stmt = $this->db->prepare("TRUNCATE TABLE transactiondescription");
				$stmt->execute();

			}
			catch(PDOException $e)
			 {
					$result = $e->getMessage();
					$return = array('Status'=>'error', 'Result'=>$result);
					return $return;
			 }
		 }
		 else if($content == 'everything'){
			 try{
 				$stmt = $this->db->prepare("TRUNCATE TABLE file");
 				$stmt->execute();

 				$stmt = $this->db->prepare("TRUNCATE TABLE transactions");
 				$stmt->execute();

 				$stmt = $this->db->prepare("TRUNCATE TABLE transactiondescription");
 				$stmt->execute();

 			}
 			catch(PDOException $e)
 			 {
 					$result = $e->getMessage();
 					$return = array('Status'=>'error', 'Result'=>$result);
 					return $return;
 			 }
		 }
	 }
	 /* ============== DEV TEST END =============== */

}

?>
