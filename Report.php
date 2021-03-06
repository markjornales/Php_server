<?php 
	require_once('datapass.php');
	if(isset($_GET['perca_report'])){
		if(isset($_POST['ca_number'])&&isset($_POST['unique_id'])){
			try {
				$ca_number = $_POST['ca_number'];
				$unique_id = $_POST['unique_id'];
				$pdfreport = new Ca_form();
				$perca = new datacollect();
				$perca->ca_query_noOnly($ca_number, $unique_id);
				$totalcas=array(
						'totalca'=> 0,
						'total_liq'=>0,
						'datefrom'=>'-',
						'dateto'=>'-'
					);
				if($perca->caStatemt->rowCount()>0){
					foreach ($perca->caResult as $value) {
						$totalcas['totalca']+=$value['totalAmount']+$value['ca_amount'];
						$perca->liq_query($unique_id, $value);
						if($perca->liqStatement->rowCount()>0){
							  foreach ($perca->liqResult as $data) {
								$totalcas['total_liq']+=$data['liqd_amount'];
							}
						}
					}
					$pdfreport->headerPage_Ca($totalcas);
				}
				$datacol = new datacollect();
					$datacol->ca_query_noOnly($ca_number, $unique_id);
					if($datacol->caStatemt->rowCount() > 0){
						foreach ($datacol->caResult as $value) {
							$arrayTotal = array(
								'totalca' => $value['totalAmount']+$value['ca_amount'],
								'total_liqui'=> ' ',
								'outca' => ' '
							);
							$pdfreport->form_letter($value);
							$pdfreport->headerLiquidation();
							    $datacol->liq_query($unique_id, $value);
							    if($datacol->liqStatement->rowCount()>0){
							    	foreach ($datacol->liqResult as $data) {
										$pdfreport->liquidation($data);
										$arrayTotal['total_liqui']+=$data['liqd_amount'];
							    	}
							    }
							$arrayTotal['outca']= $arrayTotal['totalca']-$arrayTotal['total_liqui'];
							$pdfreport->Liq_underLine($arrayTotal);
						}
					}
				$pdfreport->Output('F','Liq_perca_Report.pdf');
				$message['error']= false;
				$message['url_report']='http://localhost:8080/php_server/Liq_perca_Report.pdf';
				header('Content-Type: application/json');
				echo json_encode($message);

			} catch (PDOException $e) {
				$message['error']=true;
		        $message['message']=$error;
		        header('Content-Type: application/json');
		        echo json_encode($message);
			}
		}else {
	   		$message['error']=true;
	   		$message['message'] = 'Please make sure properties and names are valid';
	   		header('Content-Type: application/json');
	   		echo json_encode($message);
	   }
	}
	if(isset($_GET['genreport'])){
	   if(isset($_POST['datefrom'])&&isset($_POST['dateto'])&&isset($_POST['unique_id'])){
	   		try{
		   			$datefrom = $_POST['datefrom'];
					$dateto =  $_POST['dateto'];
					$unique_id = $_POST['unique_id'];
					$pdfreport = new Ca_form();
					$totals = new datacollect();
					$totals->ca_query($datefrom, $dateto, $unique_id);
					$totalcas=array(
						'totalca'=> 0,
						'total_liq'=>0,
						'datefrom'=>$datefrom,
						'dateto'=>$dateto
					);
					if($totals->caStatemt->rowCount()>0){
						foreach ($totals->caResult as $value) {
							$totalcas['totalca']+=($value['totalAmount']+$value['ca_amount']);
							$totals->liq_query($unique_id, $value);
							if($totals->liqStatement->rowCount()>0){
							    	foreach ($totals->liqResult as $data) {
									 	$totalcas['total_liq']+=$data['liqd_amount'];
							    	}
							    }
						}
						$pdfreport->headerPage_Ca($totalcas);
					}
					$datacol = new datacollect();
					$datacol->ca_query($datefrom, $dateto, $unique_id);
					if($datacol->caStatemt->rowCount() > 0){
						foreach ($datacol->caResult as $value) {
							$arrayTotal = array(
								'totalca' => $value['totalAmount']+$value['ca_amount'],
								'total_liqui'=> ' ',
								'outca' => ' '
							);
							$pdfreport->form_letter($value);
							$pdfreport->headerLiquidation();
							    $datacol->liq_query($unique_id, $value);
							    if($datacol->liqStatement->rowCount()>0){
							    	foreach ($datacol->liqResult as $data) {
										$pdfreport->liquidation($data);
										$arrayTotal['total_liqui']+=$data['liqd_amount'];
							    	}
							    }
							$arrayTotal['outca']= $arrayTotal['totalca']-$arrayTotal['total_liqui'];
							$pdfreport->Liq_underLine($arrayTotal);
						}
					}
					$pdfreport->Output('F','LiquidationReport.pdf');
					$message['error']= false;
					$message['url_report']='http://localhost:8080/php_server/LiquidationReport.pdf';
					header('Content-Type: application/json');
					echo json_encode($message);
				} catch(PDOException $error){
					$message['error']=true;
		             $message['message']=$error;
		             header('Content-Type: application/json');
		             echo json_encode($message);
				}

	   } else {
	   		$message['error']=true;
	   		$message['message'] = 'Please make sure properties and names are valid';
	   		header('Content-Type: application/json');
	   		echo json_encode($message);
	   }
	}
//backup



?>