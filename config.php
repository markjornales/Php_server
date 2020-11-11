<?php 
require('fpdf181/fpdf.php');
$_POST = json_decode(file_get_contents('php://input'), true);
class sqlserver {
	protected static $consql = "";
	public static function sqlcon(){
		try { 
			sqlserver::$consql = new PDO("sqlsrv:server=CLSI\SQLEXPRESS","sa","clsi2016");
			sqlserver::$consql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
				echo "Connection failed: ".$e->getMessage();
		}
			return sqlserver::$consql;
	}

}
class PDF extends FPDF { 
	function Footer() { 
		$this->SetY(-15); 
		$this->SetFont('Helvetica','I',8); 
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	  
	function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true){
        $str_width=$this->GetStringWidth($txt);
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $ratio = ($w-$this->cMargin*2)/$str_width;

        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit){
            if ($scale){
                $horiz_scale=$ratio*100.0;
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            }
            else{
                $char_space=($w-$this->cMargin*2-$str_width)/max($this->MBGetStringLength($txt)-1,1)*$this->k;
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            }
            $align='';
        }
        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);
        if ($fit)
            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
    }

    function CellFitScale($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
    $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,false);}
    function CellFitScaleForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
    $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,true);}
    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
    $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);}
    function CellFitSpaceForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
    $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,true);}
    function MBGetStringLength($s){
    if($this->CurrentFont['type']=='Type0'){
     $len = 0;
     $nbbytes = strlen($s);
     for ($i = 0; $i < $nbbytes; $i++){
      if (ord($s[$i])<128)
      $len++;
      else {
      	$len++;
      	$i++;}
         }
         return $len;
      }else
      return strlen($s);
    }

}


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


?>