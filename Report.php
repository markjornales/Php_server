<?php 
	require_once('datapass.php');
	class Ca_form extends PDF { 
		function headerPage_Ca($totalca,$border=0) {
			$this->SetTitle("Statement of account",false);
			$this->AliasNbPages();
			$this->AddPage('P', 'Letter', 0);
			$this->SetFont('Arial', '', 16);
			$this->Cell(150,10, 'Liquidation report', $border, 0, 'L');
			$this->SetFont('Arial', '', 11);
			$this->Cell(20, 10, 'Total ca :', $border, 0, 'R');
			$this->Cell(20, 10, number_format($totalca['totalca'],2), $border, 1, 'L');
			$this->Cell(20,5, 'Date from:',  $border, 0, 'L');
			$this->Cell(22,5, $totalca['datefrom'],  $border, 0, ':');
			$this->Cell(20,5, 'Date To:',  $border, 0, 'C');
			$this->Cell(22,5, $totalca['dateto'],  $border, 0, 'L');
			$this->Cell(54,5, '',  $border, 0, 'L');
			$this->Cell(32,5, 'Total Liquidation :',  $border, 0, 'L');
			$this->Cell(20,5, number_format(floatval($totalca['total_liq']),2),  $border, 1, 'L');
			$this->Ln();
		}
		 
		function form_letter($datasets, $borderCA = 0){  
			$this->SetFont('Arial', 'B', 11);
			$this->Cell(25, 8, 'CA number :', $borderCA, 0 , 'L'); 
			$this->SetFont('Arial', '', 11);
			$this->Cell(25, 8, $datasets['ca_number'],  $borderCA, 0 , 'L');
			$this->Cell(50, 8, '', $borderCA, 0, 'L');
			$this->Cell(30, 8, '', $borderCA, 1, 'L');
			$this->Cell(50, 5, 'Car parts', $borderCA, 0 , 'L');
			$this->Cell(50, 5, $datasets['car_parts'], $borderCA, 0, 'L');
			$this->Cell(30, 5, '', $borderCA, 1, 'L');
			$this->Cell(50, 5, 'Freight', $borderCA, 0 , 'L');
			$this->Cell(50, 5, $datasets['freight'], $borderCA, 0, 'L');
			$this->Cell(30, 5, '', $borderCA, 1, 'L');
			$this->Cell(50, 5, 'Description', $borderCA, 0 , 'L');
			$this->MultiCell(50, 5, $datasets['description_ca'], $borderCA, 'J', false);
			$this->Cell(50, 5, 'Plate number', $borderCA, 0 , 'L');
			$this->Cell(50, 5, $datasets['plateno'], $borderCA, 0, 'L');
			$this->Cell(30, 5, ' ', $borderCA, 1, 'L');
			$this->Cell(50, 5, 'Quantity', $borderCA, 0 , 'L');
			$this->Cell(50, 5,intval($datasets['quantity'])<=0?'':intval($datasets['quantity']),$borderCA, 0, 'L');
			$this->Cell(30, 5, ' ', $borderCA, 1, 'L');
			$this->Cell(50, 5, 'Unit Price', $borderCA, 0 , 'L');
			$this->Cell(50, 5,floatval($datasets['unitPrice'])<=0?'':number_format(floatval($datasets['unitPrice']),2),$borderCA, 0, 'L');
			$this->Cell(30, 5, '', $borderCA, 1, 'L');
			$this->Cell(50, 5, 'Total Amount', $borderCA, 0 , 'L');
			$this->Cell(50, 5, ' ', $borderCA, 0, 'R');
			$this->Cell(30, 5,floatval($datasets['totalAmount'])<=0?'':number_format(floatval($datasets['totalAmount']),2),$borderCA, 1, 'R');
			$this->Cell(50, 5, 'Request by Dept', $borderCA, 0 , 'L');
			$this->Cell(50, 5, $datasets['requestByDep'], $borderCA, 0, 'L');
			$this->Cell(30, 5, ' ', $borderCA, 1, 'L');
			$this->Cell(50, 5, 'Name', $borderCA, 0 , 'L');
			$this->Cell(50, 5, $datasets['name'], $borderCA, 0, 'L');
			$this->Cell(30, 5, ' ', $borderCA, 1, 'L');
			$this->Cell(50, 5, 'Purpose', $borderCA, 0 , 'L');
			$this->Cell(50, 5, $datasets['purpose'], $borderCA, 0, 'L');
			$this->Cell(30, 5, ' ', $borderCA, 1, 'L');
			$this->Cell(50, 5, 'Date of Ca', $borderCA, 0 , 'L');
			$this->Cell(50, 5, $datasets['date_needed'], $borderCA, 0, 'L');
			$this->Cell(30, 5, ' ', $borderCA, 1, 'L');
			$this->Cell(50, 5, 'Date needed', $borderCA, 0 , 'L');
			$this->Cell(50, 5, $datasets['date_of_ca'], $borderCA, 0, 'L');
			$this->Cell(30, 5, ' ', $borderCA, 1, 'L');
			$this->Cell(50, 5, 'CA Amount', $borderCA, 0 , 'L');
			$this->Cell(50, 5, ' ', $borderCA, 0, 'L');
			$this->Cell(30, 5,floatval($datasets['ca_amount'])<=0?'':number_format(floatval($datasets['ca_amount']),2),$borderCA, 1, 'R');
			$this->MultiCell(130, 0, '',0, 'L' , true); 
		}
		function headerLiquidation($border=0){
			$this->SetFont('Arial', 'B', 11);
			$this->CellFitScale(50, 10, 'Liquidation', $border, 1, 'L'); 
			$this->SetFont('Arial', '', 11);
		}
		function liquidation($dataset,$border=0){
			$this->cell(50, 5, 'Description', $border, 0, 'L');
			$this->MultiCell(50, 5, $dataset['liqd_description'],$border, 'J' , false);
			$this->CellFitScale(50, 5, 'Liquidate', $border, 0, 'L');
			$this->Cell(50, 5, $dataset['liqd_date'], $border, 1, 'L');
			$this->Cell(50, 5, 'Amount', $border, 0, 'L');
			$this->CellFitScale(50, 5, ' ', $border, 0, 'L');
			$this->Cell(30, 5, floatval($dataset['liqd_amount'])<=0?'':number_format(floatval($dataset['liqd_amount']),2), $border, 1, 'R');
		}
		function Liq_underLine($dataset,$border=0){
			$this->MultiCell(130, 0, '',0, 'L' , true);
			$this->CellFitScale(100, 1, ' ', $border, 1 , 'L'); 
			$this->CellFitScale(100, 4, 'Total CA:', $border, 0, 'R');
			$this->Cell(30, 4, floatval($dataset['totalca'])<=0?'':number_format(floatval($dataset['totalca']),2), $border, 1, 'R');
			$this->CellFitScale(100, 4, 'Total Liquidation:', $border, 0, 'R');
			$this->Cell(30, 4, floatval($dataset['total_liqui'])<=0?'':number_format(floatval($dataset['total_liqui']),2), $border, 1, 'R');	
			$this->CellFitScale(100, 4, 'Outstanding CA:', $border, 0, 'R');
			$this->Cell(30, 4, floatval($dataset['outca'])<=0?'-':number_format(floatval($dataset['outca']),2), $border, 1, 'R');	
		}
	}
class datacollect extends sqlserver {
	public $caStatemt;
	public $caResult;
	public $liqStatement;
	public $liqResult;
	function ca_query($datafrom, $dateto, $unique_id){
		$sql = 'select * from goldtechcashadvance.dbo.cash_advanceForms where 
		date_of_ca between :datefrom and :dateto and unique_id=:unique_id order by date_of_ca desc';
		$this->caStatemt = $this->sqlcon()->prepare($sql);
		$this->caStatemt->bindParam(':datefrom', $datafrom);
		$this->caStatemt->bindParam(':dateto',$dateto);
		$this->caStatemt->bindParam(':unique_id', $unique_id);
		$this->caStatemt->execute();
		$this->caResult = $this->caStatemt->fetchAll();
	}
 	function liq_query($unique_id, $value){
 		$queryString = 'select * from goldtechcashadvance.dbo.liquidation where unique_id=:unique_id and ca_number=:ca_number';
  	    $this->liqStatement = $this->sqlcon()->prepare($queryString);
	    $this->liqStatement->bindParam(':unique_id', $unique_id);
	    $this->liqStatement->bindParam(':ca_number', $value['ca_number']);
	    $this->liqStatement->execute();
	    $this->liqResult =  $this->liqStatement->fetchAll();
 	}

}

if(isset($_GET['genreport'])){
   if(isset($_POST['datefrom'])&&isset($_POST['dateto'])&&isset($_POST['unique_id'])){

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
		echo json_encode($message);
   } else {
   		$message['error']=true;
   		$message['message'] = 'Please make sure properties and names are valid';
   		echo json_encode($message);
   }
}
	



?>