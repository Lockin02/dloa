<?php
include_once(WEB_TOR."includes/classes/date_class.php");
include_once(WEB_TOR."includes/classes/rsa_class.php");
/**
 * 工资处理类
 */
class SalaryUtil{
	
	public $monthDaysProvide;//平均日
	public $cesseProvideBase;
	public $gjjBase;
	public $shbBase;
	public $coGjjBase;
	public $coShbBase;
	public $prepareAm;
	public $handicapAm;
	public $manageAm;
	public $salaryRsa;
	public $rsaClass;
	public $rsaKey;

	function __construct() {
       $this->monthDaysProvide=21;
       $this->cesseProvideBase=2000;
       $this->gjjBase=2500;
       $this->shbBase=0;
       $this->coGjjBase=0;
       $this->coShbBase=0;
       $this->prepareAm=0;
       $this->handicapAm=0;
       $this->manageAm=0;
       $this->getRsaInfo();
       $this->rsaClass=new RSA();
       $this->rsaKey=$this->rsaClass->decrypt($this->salaryRsa['SalaryCiphertext'],$this->configCrypt($_SESSION['prikey'],'decode'),$this->salaryRsa['SalaryModulo']);
   }
	/**
	 *当月入职工资处理 
	 * @param date $comed	//入职日期
	 * @param string $checkdt 格式:2010-09
	 * @param double $baseAm //工资金额
         * @param string $flag //判断点是否新入职
	 */
	function salaryDeal($comed,$checkdt,$baseAm,$flag='0'){
		$ret=0;
		if($baseAm==0){
			return 0;
		}
		$ctime=strtotime($comed);
                if($flag=='1'){
                    $dateClass=new DateUtil();
                    $lastmonth =date("Y-m",mktime(0, 0, 0, date("m",strtotime($checkdt."-01"))-1, 1,   date("Y",strtotime($checkdt."-01"))));
                    if(date("Y-m",$ctime)==date("Y-m",strtotime($checkdt."-01"))){
                        $monthd=date("Y-m",$ctime)."-01";
                        $mworkdays=$dateClass->dayInMonthWorkdays($monthd,false);
                        $cworkdays=$dateClass->dayInMonthWorkdays($comed,false);
                        if($cworkdays>=$this->monthDaysProvide||$cworkdays==$mworkdays){
                                $ret=$baseAm;
                        }else{
                                $ret=round($cworkdays*$baseAm/$this->monthDaysProvide,2);
                        }
                    }elseif($lastmonth==date("Y-m",$ctime)){
                        $cworkdays=$dateClass->dayInMonthWorkdays($comed,false);
                        $ret=round(($cworkdays*$baseAm/$this->monthDaysProvide)+$baseAm,2);
                    }
                }
		return $ret>=0?$ret:0;
	}
	/**
	 * 个人所得税缴纳
	 * @param double $cesseAm	
	 */
	function cesseDeal($cesseAm,$cpb='0'){
		if($cpb=='0'||$cpb==''){
			$cpb=$this->cesseProvideBase;
		}
		$cesseProvide=array(
		"1"=>array("cess"=>"5","del"=>"0","min"=>"1","max"=>"500"),
		"2"=>array("cess"=>"10","del"=>"25","min"=>"501","max"=>"2000"),
		"3"=>array("cess"=>"15","del"=>"125","min"=>"2001","max"=>"5000"),
		"4"=>array("cess"=>"20","del"=>"375","min"=>"5001","max"=>"20000"),
		"5"=>array("cess"=>"25","del"=>"1375","min"=>"20001","max"=>"40000"),
		"6"=>array("cess"=>"30","del"=>"3375","min"=>"40001","max"=>"60000"),
		"7"=>array("cess"=>"35","del"=>"6375","min"=>"60001","max"=>"80000"),
		"8"=>array("cess"=>"40","del"=>"10375","min"=>"80001","max"=>"100000"),
		"9"=>array("cess"=>"45","del"=>"15375","min"=>"100001","max"=>"0")
		);
		$ret=0;
		$leaveCesse=round($cesseAm-$cpb,2);
		foreach ($cesseProvide as $cval){
			if($leaveCesse>=$cval["min"]&&($leaveCesse<=$cval["max"]||$cval["max"]==0)){
				$ret=round(($leaveCesse*$cval["cess"])/100-$cval["del"],2);
				break;
			}
		}
		return $ret>=0?$ret:0;
	}
	/**
	 * 休假处理扣除额
	 * @param $perHoles	事假天数
	 * @param $sickHoles 病假
	 * @param $baseAm	工资
	 */
	function holsDeal($perHoles,$sickHoles,$baseAm){
                if(!is_numeric($perHoles)){
                    $perHoles=0;
                }
                if(!is_numeric($sickHoles)){
                    $sickHoles=0;
                }
		$ret=0;
		$ret=round(($perHoles+$sickHoles/2)*$baseAm/$this->monthDaysProvide,2);
		return $ret>$baseAm?$baseAm:$ret;
	}
	/**
	 * 加密处理
	 * @param double $base 加密数据
	 */
	function encryptDeal($base){
		if(isset($_SESSION['prikey'])==false||$_SESSION['prikey']==""){
			throw new Exception('私钥不存在！');
		}
		$base=$this->salaryCrypt(trim($base),'encode',$this->rsaKey);
		return $base;
	}
	/**
	 * 解密处理
	 * @param double $base 解密处理
	 */
	function decryptDeal($base){
		if(isset($_SESSION['prikey'])==false||$_SESSION['prikey']==""){
			throw new Exception('私钥不存在！');
		}
		if($base!="0")
			$base=$this->salaryCrypt($base,"decode",$this->rsaKey);
		return $base;
	}
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $date
	 * @param unknown_type $mode
	 * @param unknown_type $key
	 * @return unknown
	 */
	function salaryCrypt($date,$mode='encode',$key="dinglicom"){
		$key=$this->rsaKey;
		$key = md5($key);//用MD5哈希生成一个密钥
		if ($mode == 'decode'){
		    $date = base64_decode($date);
		}
		if (function_exists('mcrypt_create_iv')){
		      $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
		      $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		}
		if (isset($iv) && $mode == 'encode'){
		    $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $date, MCRYPT_MODE_ECB, $iv);
		}elseif (isset($iv) && $mode == 'decode'){
		    $passcrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $date, MCRYPT_MODE_ECB, $iv);
		}
		if ($mode == 'encode'){
		   $passcrypt = base64_encode($passcrypt);
		}
		if($mode == 'decode'){
			$passcrypt=trim($passcrypt);
		}
		return $passcrypt;
	}
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $base
	 * @return unknown
	 */
	function salaryGjj($base){
		$res=0;
		if($base>=$this->gjjBase){
			$res=round($this->gjjBase*0.05,2);
		}else 
			$res=round($base*0.05,2);
		return $res;
	}
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $base
	 * @return unknown
	 */
	function salaryShb($base){
		$res=$this->shbBase;
		return $res;
	}
	/**
	 * 获取财务最终数据，数据最少为零。
	 *
	 * @param unknown_type $base
	 */
	function getFinanceValue($base){
		if(is_numeric($base)&&$base>0){
			$base=round($base,2);
		}else {
			$base=0;
		}
		return $base;
	}
	/**
	 * Enter description here...
	 *
	 */
	function loadingDiv(){
		echo "
<div id='divback'>
    <div style='position:absolute;left:0px; top:0px; width:100%; height:100%;background-color:#000;filter:alpha(Opacity=5);display:black' ></div>
    <div align='center'style='position:absolute;left:0px; top:0px;' id='divloading'></div>
</div>";
		ob_flush();       
		flush(); 
	}
	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function getCoGjj(){
		return $this->coGjjBase;
	}
	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function getCoShb(){
		return $this->coShbBase;
	}
	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function getPreparAm(){
		return $this->prepareAm;
	}
	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function getHandicapAm(){
		return $this->handicapAm;
	}
	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function getManageAm(){
		return $this->manageAm;
	}
	function getRsaInfo(){
		global $msql;
		$sql="select name , value from config where name in ('SalaryModulo','SalaryPublicKey','SalaryCiphertext','SalaryZero') ";
		$msql->query($sql);
		while ($msql->next_record()) {
			$this->salaryRsa[$msql->f('name')]=$msql->f('value');
		}
	}
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $date
	 * @param unknown_type $mode
	 * @param unknown_type $key
	 * @return unknown
	 */
	function configCrypt($date,$mode='encode',$key="dinglicom"){
		$key = md5($key);//用MD5哈希生成一个密钥
		if ($mode == 'decode'){
		    $date = base64_decode($date);
		}
		if (function_exists('mcrypt_create_iv')){
		      $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
		      $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		}
		if (isset($iv) && $mode == 'encode'){
		    $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $date, MCRYPT_MODE_ECB, $iv);
		}elseif (isset($iv) && $mode == 'decode'){
		    $passcrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $date, MCRYPT_MODE_ECB, $iv);
		}
		if ($mode == 'encode'){
		   $passcrypt = base64_encode($passcrypt);
		}
		if($mode == 'decode'){
			$passcrypt=trim($passcrypt);
		}
		return $passcrypt;
	}
}
?>