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



?>