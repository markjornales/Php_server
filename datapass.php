<?php  
	require('config.php');
	$database = new sqlserver();
	if(isset($_GET['passdata'])){
		$query='insert into GoldtechCashAdvance.dbo.company_names 
					(listnames,datecreated,lastupdate) values (?,?,?)';
		$stmt_mssql=$database->sqlcon()->prepare($query);
		$stmt_mssql->bindParam(1,$_POST['company_name'],PDO::PARAM_STR);
		$stmt_mssql->bindParam(2,$_POST['dateCreated'],PDO::PARAM_STR);
		$stmt_mssql->bindParam(3,$_POST['dateCreated'],PDO::PARAM_STR);
		$stmt_mssql->execute();
		$data =array('mssqlStatus'=>true,'message'=>'Successful Add Company');
		echo json_encode($data);
	}
	if(isset($_GET['editData'])=='true'){
		$query = 'update GoldtechCashAdvance.dbo.company_names set listnames=?,lastupdate=? where id=?';
		$stmt = $database->sqlcon()->prepare($query);
		$stmt->bindParam(1, $_POST['company_name'],PDO::PARAM_STR);
		$stmt->bindParam(2, $_POST['dateCreated'],PDO::PARAM_STR);
		$stmt->bindParam(3, $_POST['dataTarget'],PDO::PARAM_STR);
		$stmt->execute();
		$data =array('mssqlStatus'=>true,'message'=>'Success Updated Company name');
		echo json_encode($data);
	}
	if(isset($_GET['companynames'])=='get'){
		$query = 'select id,listnames, datecreated, lastupdate, format(totalca , \'#,##0\') totalca
						from GoldtechCashAdvance.dbo.company_names';
		$stmt_mssql=$database->sqlcon()->prepare($query);
		$stmt_mssql->execute();
		$data = array();
		$datacollect = [];
		while($rows=$stmt_mssql->fetch(PDO::FETCH_ASSOC)){
			$data['id'] = $rows['id'];
			$data['listnames'] = $rows['listnames'];
			$data['datecreated'] = $rows['datecreated'];
			$data['lastupdate'] = $rows['lastupdate'];
			$data['totalca'] = $rows['totalca'];
			$datacollect[] = $data;
		}
		echo json_encode($datacollect);
	}
	if(isset($_GET['cashadvance'])=='getData'){
		$dataPass = $_POST['get_id'];
		$query = 'select listnames from GoldtechCashAdvance.dbo.company_names where id=?';
		$query2 = 'select ca_number,date_of_ca,date_needed,ca_status from GoldtechCashAdvance.dbo.cash_advances where record_id=?'; 
		$stmt_mssql=$database->sqlcon()->prepare($query);
		$stmt_mssql2 = $database->sqlcon()->prepare($query2);
		$stmt_mssql->bindParam(1, $dataPass,PDO::PARAM_STR);
		$stmt_mssql2->bindParam(1, $dataPass, PDO::PARAM_STR);
		$stmt_mssql->execute();
		$stmt_mssql2->execute();
		$datacollect = [];
		while ($rows=$stmt_mssql->fetch(PDO::FETCH_ASSOC)) {
			$datacollect['listnames'] = $rows['listnames']; 
			$datacollect['cash_advances']=[];
			while ($rows2 = $stmt_mssql2->fetch(PDO::FETCH_ASSOC)) {
				array_push($datacollect['cash_advances'],
					array('ca_number'=>$rows2['ca_number'],
					'date_of_ca'=>$rows2['date_of_ca'],
					'date_needed'=>$rows2['date_needed'],
					'ca_status'=>$rows2['ca_status'])
				); 
			}
		}
		echo json_encode($datacollect);
	}
   if(isset($_GET['liqdate'])=='getliq'){
   		$dataPass= $_POST['ca_number'];
   		$query = 'select max(id) liqref from GoldtechCashAdvance.dbo.liquidation where ca_number = ?';
   		$query2 = 'select liqd_refno,liqd_description,liqd_date,liqd_amount from GoldtechCashAdvance.dbo.liquidation where ca_number = ?';
   		$stmt_data1 = $database->sqlcon()->prepare($query);
   		$stmt_data2 = $database->sqlcon()->prepare($query2);
   		$stmt_data1->bindParam(1, $dataPass, PDO::PARAM_STR);
   		$stmt_data2->bindParam(1, $dataPass, PDO::PARAM_STR);
   		$stmt_data1->execute();
   		$stmt_data2->execute();
   		$datacollect=[];
   		while ($rows = $stmt_data1->fetch(PDO::FETCH_ASSOC)) {
   			$datacollect['liqref']=intval($rows['liqref']);
   			$datacollect['liquidation']=[];
   			while ($rows2=$stmt_data2->fetch(PDO::FETCH_ASSOC)) {
   				array_push($datacollect['liquidation'], 
   					array('liqrefno'=>$rows2['liqd_refno'],
   						'liqd_description'=>$rows2['liqd_description'],
   						'liqd_date'=>$rows2['liqd_date'],
   						'liqd_amount'=>$rows2['liqd_amount']
   					)
   				);
   			}
   		}
   		echo json_encode($datacollect);
   }
 // localhost:8080/PHP_server/datapass?liqdate=getliq
 ?>

