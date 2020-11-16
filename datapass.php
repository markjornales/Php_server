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
      header('Content-Type: application/json');
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
      header('Content-Type: application/json');
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
      header('Content-Type: application/json');
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
      $count_id = 0;
		while ($rows=$stmt_mssql->fetch(PDO::FETCH_ASSOC)) {
			$datacollect['listnames'] = $rows['listnames'];
			$datacollect['unique_id'] = $rows['unique_id'];
			$datacollect['cash_advances']=[];
			while ($rows2 = $stmt_mssql2->fetch(PDO::FETCH_ASSOC)) {
            $count_id++;
				array_push($datacollect['cash_advances'],
					array(
               'no'=> $count_id,
               'ca_number'=>$rows2['ca_number'],
					'date_of_ca'=>$rows2['date_of_ca'],
					'date_needed'=>$rows2['date_needed'],
					'ca_status'=>$rows2['ca_status'])
				); 
			}
		}
      header('Content-Type: application/json');
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
         header('Content-Type: application/json');
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
         header('Content-Type: application/json');
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
         header('Content-Type: application/json');
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
         header('Content-Type: application/json');
   		echo json_encode($data);
   }
   if(isset($_GET['getca'])=='info'){
   		$query = 'select count(ca_number)+1 ca_number from goldtechcashadvance.dbo.cash_advanceForms where unique_id=?';
   		$query_info = 'select id,unique_id,ca_number,car_parts,freight,quantity,description_ca,plateno,unitPrice,totalAmount,requestByDep,
   						name,purpose,ca_amount,convert(varchar,date_needed,110) date_needed,convert(varchar,date_of_ca,110) date_of_ca from GoldtechCashAdvance.dbo.cash_advanceForms where unique_id=?';
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
   						array('refid'=>$rows_info['id'],
   						'ca_number'=>$rows_info['ca_number'],
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
         header('Content-Type: application/json');
   		echo json_encode($data);
   }
   if(isset($_GET['addform_ca'])=='addca'){
   		$query_table_1 = 'insert into goldtechcashadvance.dbo.cash_advances (unique_id,ca_number,date_of_ca,date_needed,ca_status) 
   			values (?,?,?,?,?)';
   		$query_table_2 = 'insert into goldtechcashadvance.dbo.cash_advanceForms (unique_id,ca_number,
	   		car_parts, freight, quantity, description_ca, plateno, unitPrice, totalAmount, requestByDep,
	   		name, purpose, ca_amount, date_needed,date_of_ca) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
	   	$setstatus = 'pending...';
		   	$statement_table_1 = $database->sqlcon()->prepare($query_table_1);
		   	$statement_table_2 = $database->sqlcon()->prepare($query_table_2);
		   	$statement_table_1->bindParam(1,$_POST['unique_id'],PDO::PARAM_STR);
		   	$statement_table_1->bindParam(2,$_POST['ca_number'],PDO::PARAM_STR);
		   	$statement_table_1->bindParam(3,$_POST['dateofca'],PDO::PARAM_STR);
		   	$statement_table_1->bindParam(4,$_POST['dateneeded'],PDO::PARAM_STR);
		   	$statement_table_1->bindParam(5,$setstatus, PDO::PARAM_STR);
		   	$statement_table_2->bindParam(1, $_POST['unique_id'],PDO::PARAM_STR);
		   	$statement_table_2->bindParam(2, $_POST['ca_number'],PDO::PARAM_STR);
		   	$statement_table_2->bindParam(3, $_POST['car_parts'],PDO::PARAM_STR);
		   	$statement_table_2->bindParam(4, $_POST['freight'],PDO::PARAM_STR);
		   	$statement_table_2->bindParam(5, $_POST['quantity'],PDO::PARAM_STR);
		   	$statement_table_2->bindParam(6, $_POST['descriptions'],PDO::PARAM_STR);
		   	$statement_table_2->bindParam(7, $_POST['plate_no'],PDO::PARAM_STR);
		   	$statement_table_2->bindParam(8, $_POST['unit_price'],PDO::PARAM_STR);
		   	$statement_table_2->bindParam(9, $_POST['total_mount'],PDO::PARAM_STR);
		   	$statement_table_2->bindParam(10, $_POST['requestByDept'],PDO::PARAM_STR);
		   	$statement_table_2->bindParam(11, $_POST['name'],PDO::PARAM_STR);
		   	$statement_table_2->bindParam(12, $_POST['purpose'],PDO::PARAM_STR);
		   	$statement_table_2->bindParam(13, $_POST['ca_amount'],PDO::PARAM_STR);
		   	$statement_table_2->bindParam(14, $_POST['dateneeded'],PDO::PARAM_STR);
		   	$statement_table_2->bindParam(15, $_POST['dateofca'],PDO::PARAM_STR);
		$statement_table_1->execute();
		$statement_table_2->execute();
		$data = array('mssql_error'=> false, 'message'=>'success new added ca');
      header('Content-Type: application/json');
		echo json_encode($data);
   }

   if(isset($_GET['updateform_ca'])=='updateca'){
   		$query_table1 = 'update goldtechcashadvance.dbo.cash_advances set date_of_ca=?, date_needed=? where id=?';
   		$query_table2 = 'update goldtechcashadvance.dbo.cash_advanceForms set car_parts=?, freight=?, quantity=?, description_ca=?, plateno=?, unitPrice=?, totalAmount=?, requestByDep=?,name=?, purpose=?, ca_amount=?, date_needed=?,date_of_ca=? where id=?';
   		$stmt_table1 = $database->sqlcon()->prepare($query_table1);
   		$stmt_table2 = $database->sqlcon()->prepare($query_table2);
	   		$stmt_table1->bindParam(1,$_POST['dateofca'],PDO::PARAM_STR);
	   		$stmt_table1->bindParam(2,$_POST['dateneeded'],PDO::PARAM_STR);
	   		$stmt_table1->bindParam(3,$_POST['refid'],PDO::PARAM_STR); 
	   		$stmt_table2->bindParam(1,$_POST['car_parts'],PDO::PARAM_STR);
	   		$stmt_table2->bindParam(2,$_POST['freight'],PDO::PARAM_STR);
	   		$stmt_table2->bindParam(3,$_POST['quantity'],PDO::PARAM_STR);
	   		$stmt_table2->bindParam(4,$_POST['descriptions'],PDO::PARAM_STR);
	   		$stmt_table2->bindParam(5,$_POST['plate_no'],PDO::PARAM_STR);
	   		$stmt_table2->bindParam(6,$_POST['unit_price'],PDO::PARAM_STR);
	   		$stmt_table2->bindParam(7,$_POST['total_mount'],PDO::PARAM_STR);
	   		$stmt_table2->bindParam(8,$_POST['requestByDept'],PDO::PARAM_STR);
	   		$stmt_table2->bindParam(9,$_POST['name'],PDO::PARAM_STR);
	   		$stmt_table2->bindParam(10,$_POST['purpose'],PDO::PARAM_STR);
	   		$stmt_table2->bindParam(11,$_POST['ca_amount'],PDO::PARAM_STR);
	   		$stmt_table2->bindParam(12,$_POST['dateneeded'],PDO::PARAM_STR);
	   		$stmt_table2->bindParam(13,$_POST['dateofca'],PDO::PARAM_STR);
	   		$stmt_table2->bindParam(14,$_POST['refid'],PDO::PARAM_STR); 
   		$stmt_table1->execute();
   		$stmt_table2->execute();
   		$data = array('mssql_error'=>false, 'message'=>'Successful updated CA#'.$_POST['ca_number']);
         header('Content-Type: application/json');
   		echo json_encode($data);
   }

   if(isset($_GET['lastupdate'])=='lastupdate'){
    if(isset($_POST['datenow'])&&isset($_POST['unique_id'])){
         try {
               $totalItems = checkTotal_items($_POST['unique_id']);
               $query = 'update GoldtechCashAdvance.dbo.company_names set totalca=?, lastupdate=? where unique_id=?';
               $stmt = $database->sqlcon()->prepare($query);
               $stmt->bindParam(1, $totalItems ,PDO::PARAM_STR);
               $stmt->bindParam(2, $_POST['datenow'],PDO::PARAM_STR);
               $stmt->bindParam(3, $_POST['unique_id'],PDO::PARAM_STR);
            $stmt->execute();
            $data = [];
            if($stmt){
               $data['mssql_error']= false;
               $data['message']='Successful updated';
            }  
         }
         catch(PDOException $error){
             $data['mssql_error']=true;
               $data['message']=$error;
         }
         header('Content-Type: application/json');
         echo json_encode($data);
      }
      else {
         $data['error']=true;
         $data['message']='please check valid properties';
         header('Content-Type: application/json');
         echo json_encode($data);
      }
    }
   function checkTotal_items($unique_id){
         $database = new sqlserver();
         $query = 'select count(id) items from GoldtechCashAdvance.dbo.cash_advances where unique_id=?';
         $stmt = $database->sqlcon()->prepare($query);
         $stmt->bindParam(1, $unique_id, PDO::PARAM_STR);
         $stmt->execute();
         while($rows = $stmt->fetch(PDO::FETCH_ASSOC)){ 
            return $rows['items'];
         }
    }

    if(isset($_GET['datesearch'])){
      if(isset($_POST['datefrom_liq'])&&isset($_POST['dateTo_liq'])&&isset($_POST['unique_id'])){
         try{
            $querySelect = 'select id,ca_number,date_of_ca,date_needed,ca_status from GoldtechCashAdvance.dbo.cash_advances 
            where date_of_ca between :datefrom and :dateto and unique_id=:unique_id order by date_of_ca asc';
            $prepareStatement = $database->sqlcon()->prepare($querySelect);
            $prepareStatement->bindParam(':datefrom',$_POST['datefrom_liq']);
            $prepareStatement->bindParam(':dateto', $_POST['dateTo_liq']);
            $prepareStatement->bindParam(':unique_id',$_POST['unique_id']);
            $prepareStatement->execute();
            $datacollect = [];
               while ($rows = $prepareStatement->fetch(PDO::FETCH_ASSOC)) {
                     $data['id']=$rows['id'];
                     $data['ca_number']=$rows['ca_number'];
                     $data['date_of_ca']=$rows['date_of_ca'];
                     $data['date_needed']=$rows['date_needed'];
                     $data['ca_status']=$rows['ca_status'];
                     $datacollect[]=$data;
               }
            header('Content-Type: application/json');
            echo json_encode($datacollect);
         }
         catch(PDOException $error){
            $response['message']= $error;
            $response['error'] = true;
            header('Content-Type: application/json');
            echo json_encode($response);
         }
      }
      else{
         $response['message'] = 'Please make sure, your provided Properties and Names are valid';
         $response['error'] = true;
         header('Content-Type: application/json');
         echo json_encode($response);
      }
    }

    if(isset($_GET['updatestat'])){
      if(isset($_POST['ca_number'])&&isset($_POST['unique_id'])){
         try{
            $updateStat = new datacollect();
            $dataset = array(
               'unique_id'=>$_POST['unique_id'],
               'ca_number'=>$_POST['ca_number'],
               'totalca'=>0,
               'total_liq'=>0
            );
            $updateStat->caNoQuery($_POST['ca_number'], $_POST['unique_id']);
            if($updateStat->caStatemt->rowCount()>0){
               foreach ($updateStat->caResult as $value) {
                  $dataset['totalca']+=($value['totalAmount']+$value['ca_amount']);
                  $updateStat->liqQueryca($_POST['ca_number'], $_POST['unique_id']);
                  if($updateStat->liqStatement->rowCount()>0){
                     foreach ($updateStat->liqResult as $data) {
                        $dataset['total_liq']+=$data['liqd_amount'];
                     }
                  }
               }
            }
            header('Content-Type: application/json');
            echo json_encode($updateStat->caUpdateStat($dataset));
         } catch(PDOException $error){
             $message['error']=true;
             $message['message']=$error;
             header('Content-Type: application/json');
             echo json_encode($message);
         }
         
      }else{
         $message['error']=true;
         $message['message']='Make sure, your provided Properties and names are valid';
         header('Content-Type: application/json');
         echo json_encode($message);
      }
    }
    if(isset($_GET['logingtc'])){
      if(isset($_POST['username'])&&isset($_POST['password'])){
         try {
               $query= 'select * from goldtechcashadvance.dbo.loginform_tbl';
               $stmt = $database->sqlcon()->prepare($query); 
               $stmt->execute();
               $result = $stmt->fetchAll();
               $data = [];
               if($stmt->rowCount() > 0 ){
                  foreach ($result as $value) {
                     $data['id']= $value['id'];
                     $data['username']= $value['username'];
                     $data['password']= $value['password'];
                     $data['date_expire']= $value['date_expire'];
                     $data['valid_hash']= $value['valid_hash'];
                  }
                  header('Content-Type: application/json');
                  echo json_encode($data);
               }
         } catch (PDOException $err) {
            $message['error']=false;
            $message['message']=$err;
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode($message);
         }
      }
      else{
         $message['error']=true;
         $message['message']='Make sure, your input valid properties and names.';
         http_response_code(401);
         header('Content-Type: application/json');
         echo json_encode($message);
      }
    }
 ?>

