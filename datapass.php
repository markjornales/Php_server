<?php  
	require('config.php');
	$database = new sqlserver();
	if(isset($_GET['passdata'])){
		$query='insert into GoldtechCashAdvance.dbo.company_names 
					(listnames,datecreated,lastupdate,unique_id) values (?,?,?,?)';
		$stmt_mssql=$database->sqlcon()->prepare($query);
		$stmt_mssql->bindParam(1,$_POST['company_name'],PDO::PARAM_STR);
		$stmt_mssql->bindParam(2,$_POST['dateCreated'],PDO::PARAM_STR);
		$stmt_mssql->bindParam(3,$_POST['dateCreated'],PDO::PARAM_STR);
		$stmt_mssql->bindParam(4, $_POST['unique_id'],PDO::PARAM_STR);
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
		$query = 'select id,unique_id,listnames, datecreated, lastupdate, format(totalca , \'#,##0\') totalca
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
			$data['unique_id'] = $rows['unique_id'];
			$datacollect[] = $data;
		}
		echo json_encode($datacollect);
	}
	if(isset($_GET['cashadvance'])=='getData'){
		$dataPass = $_POST['get_id'];
		$query = 'select unique_id,listnames from GoldtechCashAdvance.dbo.company_names where unique_id=?';
		$query2 = 'select ca_number,convert(varchar, date_of_ca, 110) date_of_ca,convert(varchar, date_needed, 110) date_needed,ca_status from GoldtechCashAdvance.dbo.cash_advances where unique_id=?'; 
		$stmt_mssql=$database->sqlcon()->prepare($query);
		$stmt_mssql2 = $database->sqlcon()->prepare($query2);
		$stmt_mssql->bindParam(1, $dataPass,PDO::PARAM_STR);
		$stmt_mssql2->bindParam(1, $dataPass, PDO::PARAM_STR);
		$stmt_mssql->execute();
		$stmt_mssql2->execute();
		$datacollect = [];
		while ($rows=$stmt_mssql->fetch(PDO::FETCH_ASSOC)) {
			$datacollect['listnames'] = $rows['listnames'];
			$datacollect['unique_id'] = $rows['unique_id'];
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
   		$query = 'select count(id) liqref from GoldtechCashAdvance.dbo.liquidation where ca_number = ? and unique_id=?';
   		$query2 = 'select id,liqd_refno,liqd_description,convert(varchar, liqd_date, 110) liqd_date,liqd_amount 
   		from GoldtechCashAdvance.dbo.liquidation where ca_number = ? and unique_id=?';
   		$stmt_data1 = $database->sqlcon()->prepare($query);
   		$stmt_data2 = $database->sqlcon()->prepare($query2);
   		$stmt_data1->bindParam(1,  $_POST['ca_number'], PDO::PARAM_STR);
   		$stmt_data1->bindParam(2, $_POST['unique_id'],PDO::PARAM_STR);
   		$stmt_data2->bindParam(1, $_POST['ca_number'], PDO::PARAM_STR);
   		$stmt_data2->bindParam(2, $_POST['unique_id'],PDO::PARAM_STR);
   		$stmt_data1->execute();
   		$stmt_data2->execute();
   		$datacollect=[];
   		while ($rows = $stmt_data1->fetch(PDO::FETCH_ASSOC)) {
   			$datacollect['liqref']=intval($rows['liqref']);
   			$datacollect['liquidation']=[];
   			while ($rows2=$stmt_data2->fetch(PDO::FETCH_ASSOC)) {
   				array_push($datacollect['liquidation'], 
   					array('refid'=>$rows2['id'],'liqrefno'=>$rows2['liqd_refno'],
   						'liqd_description'=>$rows2['liqd_description'],
   						'liqd_date'=>$rows2['liqd_date'],
   						'liqd_amount'=>$rows2['liqd_amount']
   					)
   				);
   			}
   		}
   		echo json_encode($datacollect);
   }
   if(isset($_GET['liq_add'])=='setliq'){
   		$query = 'insert into goldtechcashadvance.dbo.liquidation (liqd_refno,liqd_description,liqd_date,liqd_amount,ca_number,unique_id) 
   		values (?,?,?,?,?,?)';
   		$statement = $database->sqlcon()->prepare($query);
   		$statement->bindParam(1, $_POST['refno'],PDO::PARAM_STR);
   		$statement->bindParam(2, $_POST['descript'],PDO::PARAM_STR);
   		$statement->bindParam(3, $_POST['date'],PDO::PARAM_STR);
   		$statement->bindParam(4, $_POST['amount'],PDO::PARAM_STR);
   		$statement->bindParam(5, $_POST['ca_number'],PDO::PARAM_STR);
   		$statement->bindParam(6, $_POST['unique_id'],PDO::PARAM_STR);
   		$statement->execute();
   		if($statement->rowCount() > 0){
   			 $data = array('mssql_error'=>false , 'message'=>'Success Add new liquidations');
   		}
   		echo json_encode($data);
   }
   if(isset($_GET['liq_update'])=='setupdate'){
   		$query = 'update goldtechcashadvance.dbo.liquidation set liqd_description=?,liqd_date=?,liqd_amount=? where id=?';
   		$statement = $database->sqlcon()->prepare($query);
   		$statement->bindParam(1, $_POST['descript'],PDO::PARAM_STR);
   		$statement->bindParam(2, $_POST['date'],PDO::PARAM_STR);
   		$statement->bindParam(3, $_POST['amount'],PDO::PARAM_STR);
   		$statement->bindParam(4, $_POST['refid'],PDO::PARAM_STR);
   		$statement->execute();
   		if($statement->rowCount() > 0){
   			$data = array('mssql_error'=>false, 'message'=>'Success Update input Liquidations');
   		}
   		echo json_encode($data);
   }
   if(isset($_GET['liq_delete'])=='deleted'){
   		$query = 'delete from goldtechcashadvance.dbo.liquidation where id = ?';
   		$statement = $database->sqlcon()->prepare($query);
   		$statement->bindParam(1, $_POST['refid'], PDO::PARAM_STR);
   		$statement->execute();
   		if($statement->rowCount() > 0){
   			$data = array('mssql_error'=>false, 'message'=>'Success deleted liquidations Refno: '.$_POST['refno']);
   		}
   		echo json_encode($data);
   }
   if(isset($_GET['getca'])=='info'){
   		$query = 'select count(ca_number)+1 ca_number from goldtechcashadvance.dbo.cash_advanceForms where unique_id=?';
   		$query_info = 'select id,unique_id,ca_number,car_parts,freight,quantity,description_ca,plateno,unitPrice,totalAmount,requestByDep,
   						name,purpose,ca_amount,date_needed,date_of_ca from GoldtechCashAdvance.dbo.cash_advanceForms where unique_id=?';
   		$statement = $database->sqlcon()->prepare($query);
   		$statement_info = $database->sqlcon()->prepare($query_info);
   		$statement->bindParam(1, $_POST['unique_id'],PDO::PARAM_STR);
   		$statement_info->bindParam(1, $_POST['unique_id'],PDO::PARAM_STR);
   		$statement->execute();
   		$statement_info->execute();
   		$results = $statement->fetchAll();
   		if($statement->rowCount()>0){
   			foreach ($results as $rows) {
   				$data['ca_number']=$rows['ca_number'];
   				$data['ca_info'] = [];
   				while($rows_info=$statement_info->fetch(PDO::FETCH_ASSOC)){
   					array_push($data['ca_info'], 
   						array('ca_number'=>$rows_info['ca_number'],
   						'car_parts' => $rows_info['car_parts'],
   						'freight' => $rows_info['freight'],
   						'quantity' => $rows_info['quantity'],
   						'description_ca' => $rows_info['description_ca'],
   						'plateno' => $rows_info['plateno'],
   						'unitPrice' => $rows_info['unitPrice'],
   						'totalAmount' => $rows_info['totalAmount'],
   						'requestByDep' => $rows_info['requestByDep'],
   						'name' => $rows_info['name'],
   						'purpose' => $rows_info['purpose'],
   						'ca_amount' => $rows_info['ca_amount'],
   						'date_needed' => $rows_info['date_needed'],
   						'date_of_ca' => $rows_info['date_of_ca'])
   					);
   				}
   			}
   		} else {
   			$data['ca_number'] = 1;
   			$data['ca_info']=[];
   		}
   		echo json_encode($data);
   }
 ?>

