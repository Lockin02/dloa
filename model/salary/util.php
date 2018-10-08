<?php

include_once(WEB_TOR . "includes/classes/date_class.php");
include_once(WEB_TOR . "includes/classes/rsa_class.php");

/**
 * ���ʴ�����
 */
class model_salary_util extends model_base {

    public $monthDaysProvide;
    public $cesseProvideBase;
    public $gjjBase;//���������
    public $shbBase;//�����
    public $coGjjBase;
    public $coShbBase;
    public $prepareAm;
    public $handicapAm;
    public $manageAm;
    public $salaryRsa;
    public $rsaClass;
    public $rsaKey;
    public $prikey;

    function __construct() {
        parent::__construct();
        $this->monthDaysProvide = 21.75;
        $this->cesseProvideBase = 5000;
        $this->gjjBase = 2500;
        $this->shbBase = 0;
        $this->coGjjBase = 0;
        $this->coShbBase = 0;
        $this->prepareAm = 0;
        $this->handicapAm = 0;
        $this->manageAm = 0;
        $this->getRsaInfo();
        $this->rsaClass = new RSA();
        //�Զ�ʹ��$_SESSION['prikey']
        $this->set_prikey($_SESSION['prikey']);
        $this->set_rsakey();
    }
    /**
     *����prikey 
     */
    function set_prikey($val){
        $this->prikey=$val;
    }
    /**
     *��ȡ��Կ
     */
    function set_rsakey(){
        $this->rsaKey = $this->rsaClass->decrypt($this->salaryRsa['SalaryCiphertext']
                , $this->configCrypt($this->prikey, 'decode'), $this->salaryRsa['SalaryModulo']);
    }

    /**
     * ������ְ���ʴ���
     * @param date $comed	//��ְ����
     * @param double $baseAm //���ʽ��
     */
    function salaryDeal($comed, $baseAm) {
        $res = 0;
        if ($baseAm == 0) {
            return 0;
        }
        $ck=date('N',  strtotime($comed));
        $dateClass = new DateUtil();
        $c1 = $dateClass->dayInMonthWorkdays($comed, false);//��ְ��
        //�ж��Ƿ���������ְ
        if($ck==6||$ck==7){
            $c2 = $dateClass->dayInMonthWorkdays($comed);//��ְǰ
        }else{
            $c2 = $dateClass->dayInMonthWorkdays($comed)-1;//��ְǰ
        }
        $c=$c1+$c2;
        $res=$this->cfv(($baseAm*$c1)/$c);
        /*
        if ($c2 > $this->monthDaysProvide) {
            $ret = round(($baseAm / $this->monthDaysProvide) * $c1, 2);
        } elseif ($c2 < $this->monthDaysProvide) {
            $ret = round($baseAm - (($baseAm / $this->monthDaysProvide) * $c2), 2);
        }
         * 
         */
        return $res >= 0 ? $res : 0;
    }
    /**
     * ������ְ���ʴ���
     * @param date $comed	//��ְ����
     * @param date $leaved	//��ְ����
     * @param double $baseAm //���ʽ��
     */
    function salaryDealLeave($comed, $leaved , $baseAm) {
        $comed=date("Ymd",  strtotime($comed));
        $leaved=date("Ymd",strtotime($leaved));
        $res = 0;
        if ($baseAm == 0) {
            return 0;
        }
        $ck=date('N',  strtotime($leaved));
        $dateClass = new DateUtil();
        $ckc=substr($comed,0,6);
        $ckl=substr($leaved,0,6);
        if($ckc==$ckl){//������ְ����ְ
            $c1 = $dateClass->dayInMonthWorkdays($comed)-1;//��ְǰ
            $c2 = $dateClass->dayInMonthWorkdays($comed, false);//��ְ��
            $b1 = $dateClass->dayInMonthWorkdays($leaved);//��ְǰ
            $b2 = $dateClass->dayInMonthWorkdays($leaved, false)-1;//��ְ��
            $c=$c1+$c2;
            $c1=round($c2-$b2);
        }else{
            $c1 = $dateClass->dayInMonthWorkdays($leaved);//��ְǰ
            if($ck==6||$ck==7){
                $c2 = $dateClass->dayInMonthWorkdays($leaved, false);//��ְ��
            }else{
                $c2 = $dateClass->dayInMonthWorkdays($leaved, false)-1;//��ְ��
            }
            $c=$c1+$c2;
        }
        $res=$this->cfv(($baseAm*$c1)/$c);
        return $res >= 0 ? $res : 0;
    }
    /**
     * ������ְ������
     * @param date $comed	//��ְ����
     * @param date $leaved	//��ְ����
     */
    function getLeaveWorkDays($comed , $leaved ) {
        
        $comed=date("Ymd",  strtotime($comed));
        $leaved=date("Ymd",strtotime($leaved));
        $dateClass = new DateUtil();
        $ckc=substr($comed,0,6);
        $ckl=substr($leaved,0,6);
        if($ckc==$ckl){//������ְ����ְ
            $c2 = $dateClass->dayInMonthWorkdays($comed, false);//��ְ��
            $b2 = $dateClass->dayInMonthWorkdays($leaved, false)-1;//��ְ��
            $c1=round($c2-$b2);
        }else{
            $c1 = $dateClass->dayInMonthWorkdays($leaved);//��ְǰ
        }
        return $c1;
        
    }
    /**
     * ���¹�����
     * @param date $dt	����
     */
    function getWorkDays($dt) {
        
        $dt = date("Ymd",  strtotime($dt));
        $dateClass = new DateUtil();
        $wd = $dateClass->monthWorkDays($dt);
        return $wd;
        
    }
    /**
     *ͨ�������ռ��㹤��
     * @param type $am ����
     * @param type $wd ���ڹ�����
     * @param type $pd ���ڵ�������
     */
    function getSalaryByWorkDays($am,$wd,$pdt){
        if(empty($am)){
            $res=0;
        }else{
            $dateClass = new DateUtil();
            $pwd=$dateClass->monthWorkDays($pdt);
            if($wd>$pwd){
                $pwd=$wd;
            }
            $res=$this->cfv(($am*$wd)/$pwd);
        }
        return $res;
    }
    /**
     *ͨ�������ռ��㹤��
     * @param type $am ���� 
     * @param type $pdt ����
     * @param type $flag true �������칤�� false ��������
     */
    function getSalaryByDateToWorkDays($am,$pdt,$flag=true){
        if(empty($am)){
            $res=0;
        }else{
            $dateClass = new DateUtil();
            $jswd=0;
            if($flag){//�������칤��
                $jswd=$dateClass->dayInMonthWorkdays($pdt);
            }else{//����
                $jswd=$dateClass->dayInMonthWorkdays($pdt)-1;
            }
            if($jswd<0){
                $res=0;
            }else{
                $pwd=$dateClass->monthWorkDays($pdt);
                $res=$this->cfv(($am*$jswd)/$pwd);
            }
        }
        return $res;
    }
    /**
     *ת�����ʼ��㷽��
     * @param <type> $bam �����ڹ���
     * @param <type> $pam ת������
     * @param <type> $pdt ת������
     * @return <type>  ת�����¹���
     */
    function salaryPass($bam,$pam,$pdt){
        $res=0;
        if($pam==''||$pdt==''){
            throw new Exception('data error');
        }else{
            $ck=date('N',  strtotime($pdt));
            $dateClass = new DateUtil();
            if($ck==6||$ck==7){
                $d1=$dateClass->dayInMonthWorkdays($pdt);//������ǰ������
            }else{
                $d1=$dateClass->dayInMonthWorkdays($pdt)-1;//������ǰ������
            }
            $d2=$dateClass->dayInMonthWorkdays($pdt,false);//�����ں�����
            $d=$d1+$d2;
            /*
            $p1=round( ($bam/$this->monthDaysProvide) ,6);
            $p2=round( ($pam/$this->monthDaysProvide) ,6);
             * 
             */
            $res=$this->cfv(($bam*$d1+$pam*$d2)/$d);
            /*
            if($d1>$this->monthDaysProvide){
               $res=$this->cfv($bam-($p1*$d2)+($p2*$d2));
            }elseif($d2>$this->monthDaysProvide){
                $res=$this->cfv($pam-($p2*$d1)+($p1*$d1));
            }else{
                $res=$this->cfv(($p1*$d1)+($p2*$d2));
            }
             */
        }
        return $res;
    }
    /**
     * ��������˰����
     * @param double $cesseAm
     */
    function cesseDeal($cesseAm, $cpb='0', $year='2018', $mon='09', $com='dl') {
        if ($cpb == '0' || $cpb == ''|| !is_numeric($cpb) ) {
            $cpb = 3500;
        }

        if($cpb==2000){
            $cpb=3500;
        }

        $cesseProvide = array(
            "1" => array("cess" => "3", "del" => "0", "min" => "1", "max" => "1500"),
            "2" => array("cess" => "10", "del" => "105", "min" => "1501", "max" => "4500"),
            "3" => array("cess" => "20", "del" => "555", "min" => "4501", "max" => "9000"),
            "4" => array("cess" => "25", "del" => "1005", "min" => "9001", "max" => "35000"),
            "5" => array("cess" => "30", "del" => "2755", "min" => "35001", "max" => "55000"),
            "6" => array("cess" => "35", "del" => "5505", "min" => "55001", "max" => "80000"),
            "7" => array("cess" => "45", "del" => "13505", "min" => "80001", "max" => "0")
        );

        //�¸�˰(5000��׼) ������
        if($this->compare_year_mon_income($year, $mon, $com)){
            $cpb = 5000;

            $cesseProvide = array(
                "1" => array("cess" => "3", "del" => "0", "min" => "1", "max" => "3000"),
                "2" => array("cess" => "10", "del" => "210", "min" => "3001", "max" => "12000"),
                "3" => array("cess" => "20", "del" => "1410", "min" => "12001", "max" => "25000"),
                "4" => array("cess" => "25", "del" => "2660", "min" => "25001", "max" => "35000"),
                "5" => array("cess" => "30", "del" => "4410", "min" => "35001", "max" => "55000"),
                "6" => array("cess" => "35", "del" => "7160", "min" => "55001", "max" => "80000"),
                "7" => array("cess" => "45", "del" => "15160", "min" => "80001", "max" => "0")
            );
        }


        $ret = 0;
        $leaveCesse = round($cesseAm - $cpb, 2);
        $cesseck = ceil($leaveCesse);
        foreach ($cesseProvide as $cval) {
            if ($cesseck >= $cval["min"] && ($cesseck <= $cval["max"] || $cval["max"] == 0)) {
                $ret=($leaveCesse * $cval["cess"]) / 100 - $cval["del"]+0.0000000001;
                $ret = round($ret, 2);
                break;
            }
        }

        return $ret >= 0 ? $ret : 0;
    }
    
    /**
     * ��������˰����
     * @param double $cesseAm
     */
    function getCesseDeal($cesseAm, $cpb='0', $year='2018', $mon='09', $com='dl') {
        if ($cpb == '0' || $cpb == ''|| !is_numeric($cpb) ) {
            $cpb = 3500;
        }
        if($cpb==2000){
            $cpb=3500;
        }
        $cesseProvide = array(
            "1" => array("cess" => "3", "del" => "0", "min" => "1", "max" => "1500"),
            "2" => array("cess" => "10", "del" => "105", "min" => "1501", "max" => "4500"),
            "3" => array("cess" => "20", "del" => "555", "min" => "4501", "max" => "9000"),
            "4" => array("cess" => "25", "del" => "1005", "min" => "9001", "max" => "35000"),
            "5" => array("cess" => "30", "del" => "2755", "min" => "35001", "max" => "55000"),
            "6" => array("cess" => "35", "del" => "5505", "min" => "55001", "max" => "80000"),
            "7" => array("cess" => "45", "del" => "13505", "min" => "80001", "max" => "0")
        );

        //�¸�˰(5000��׼) ������
        if($this->compare_year_mon_income($year, $mon, $com)){
            $cpb = 5000;

            $cesseProvide = array(
                "1" => array("cess" => "3", "del" => "0", "min" => "1", "max" => "3000"),
                "2" => array("cess" => "10", "del" => "210", "min" => "3001", "max" => "12000"),
                "3" => array("cess" => "20", "del" => "1410", "min" => "12001", "max" => "25000"),
                "4" => array("cess" => "25", "del" => "2660", "min" => "25001", "max" => "35000"),
                "5" => array("cess" => "30", "del" => "4410", "min" => "35001", "max" => "55000"),
                "6" => array("cess" => "35", "del" => "7160", "min" => "55001", "max" => "80000"),
                "7" => array("cess" => "45", "del" => "15160", "min" => "80001", "max" => "0")
            );
        }


        $ret = 0;
        $leaveCesse = round($cesseAm - $cpb, 2);
        $cesseck = ceil($leaveCesse);
        foreach ($cesseProvide as $cval) {
            if ($cesseck >= $cval["min"] && ($cesseck <= $cval["max"] || $cval["max"] == 0)) {
                $ret = $cval["cess"];
                break;
            }
        }
        return $ret >= 0 ? $ret : 0;
    }

    /**
     * ��������˰������
     * @param double $cesseAm
     */
    function cesseDealNew($cesseAm, $cpb='0') {
        if ($cpb == '0' || $cpb == ''|| !is_numeric($cpb) ) {
            $cpb = 3500;
        }
        if($cpb==2000){
            $cpb=3000;
        }
        $cesseProvide = array(
            "1" => array("cess" => "5", "del" => "0", "min" => "1", "max" => "1500"),
            "2" => array("cess" => "10", "del" => "75", "min" => "1501", "max" => "4500"),
            "3" => array("cess" => "20", "del" => "525", "min" => "4501", "max" => "9000"),
            "4" => array("cess" => "25", "del" => "975", "min" => "9001", "max" => "35000"),
            "5" => array("cess" => "30", "del" => "2725", "min" => "35001", "max" => "55000"),
            "6" => array("cess" => "35", "del" => "5475", "min" => "55001", "max" => "80000"),
            "7" => array("cess" => "45", "del" => "13475", "min" => "80001", "max" => "0")
        );
        $ret = 0;
        $leaveCesse = round($cesseAm - $cpb, 2);
        $cesseck = ceil($leaveCesse);
        foreach ($cesseProvide as $cval) {
            if ($cesseck >= $cval["min"] && ($cesseck <= $cval["max"] || $cval["max"] == 0)) {
                $ret=($leaveCesse * $cval["cess"]) / 100 - $cval["del"]+0.0000000001;
                $ret = round($ret, 2);
                break;
            }
        }
        return $ret >= 0 ? $ret : 0;
    }

    /**
     * ���ս���������˰����
     * @param double $cesseAm
     */
    function cesseDealYeb($yearAm ,$laseMonAm ,$cpb,$flag=false) {
        if ($cpb == '0' || $cpb == ''|| !is_numeric($cpb) ) {
            $cpb = 3500;
        }
        if($cpb==2000){
            $cpb=3500;
        }

//        $cesseProvide = array(
//            "1" => array("cess" => "3", "del" => "0", "min" => "1", "max" => "1500"),
//            "2" => array("cess" => "10", "del" => "105", "min" => "1501", "max" => "4500"),
//            "3" => array("cess" => "20", "del" => "555", "min" => "4501", "max" => "9000"),
//            "4" => array("cess" => "25", "del" => "1005", "min" => "9001", "max" => "35000"),
//            "5" => array("cess" => "30", "del" => "2755", "min" => "35001", "max" => "55000"),
//            "6" => array("cess" => "35", "del" => "5505", "min" => "55001", "max" => "80000"),
//            "7" => array("cess" => "45", "del" => "13505", "min" => "80001", "max" => "0")
//        );

        $cpb = 5000;
        $cesseProvide = array(
            "1" => array("cess" => "3", "del" => "0", "min" => "1", "max" => "3000"),
            "2" => array("cess" => "10", "del" => "210", "min" => "3001", "max" => "12000"),
            "3" => array("cess" => "20", "del" => "1410", "min" => "12001", "max" => "25000"),
            "4" => array("cess" => "25", "del" => "2660", "min" => "25001", "max" => "35000"),
            "5" => array("cess" => "30", "del" => "4410", "min" => "35001", "max" => "55000"),
            "6" => array("cess" => "35", "del" => "7160", "min" => "55001", "max" => "80000"),
            "7" => array("cess" => "45", "del" => "15160", "min" => "80001", "max" => "0")
        );

        $ret = 0;

        if($laseMonAm < $cpb){//����-������-�籣�� < ��˰����
            $leaveCesse = round( ($yearAm-($cpb-$laseMonAm))/12 , 2);
            $tmpAm = round( $yearAm-($cpb-$laseMonAm) , 2 );
        }else{
            $leaveCesse = round( $yearAm/12 , 2);
            $tmpAm = round($yearAm,2);
        }
        $cesseck = ceil($leaveCesse);
        foreach ($cesseProvide as $cval) {
            if ($cesseck >= $cval["min"] && ($cesseck <= $cval["max"] || $cval["max"] == 0)) {
                $ret = ($tmpAm * $cval["cess"]) / 100 - $cval["del"]+0.0000000001;
                $ret = round($ret, 2);
                break;
            }
        }
        return $ret >= 0 ? $ret : 0;
    }
    /**
     * �ݼٴ���۳���
     * @param $perHoles	�¼�����
     * @param $sickHoles ����
     * @param $baseAm	����
     * $holeDate ��������
     */
    function holsDeal($perHoles, $sickHoles, $baseAm,$holeDate='') {
        if (!is_numeric($perHoles)) {
            $perHoles = 0;
        }
        if (!is_numeric($sickHoles)) {
            $sickHoles = 0;
        }
        $ret = 0;
        if(!empty($holeDate)){
            $dateClass = new DateUtil();
            $mwd=$dateClass->monthWorkDays($holeDate);
        }else{
            $mwd=$this->monthDaysProvide;
        }
        $ret = round(($perHoles + $sickHoles / 2) * $baseAm /$mwd , 2);
        return $ret > $baseAm ? $baseAm : $ret;
    }

    /**
     * ���ܴ���
     * @param double $base ��������
     */
    function encryptDeal($base) {
        if (isset($this->prikey) == false || $this->prikey == "") {
            throw new Exception('˽Կ�����ڣ�');
        }
        $base = $this->salaryCrypt(trim($base), 'encode', $this->rsaKey);
        return $base;
    }

    /**
     * ���ܴ���
     * @param double $base ���ܴ���
     */
    function decryptDeal($base) {
        if (isset($this->prikey) == false || $this->prikey == "") {
            throw new Exception('˽Կ�����ڣ�');
        }
        if ($base != "0"&&$base!=''){
            $base = $this->salaryCrypt($base, "decode", $this->rsaKey);
        }else{
            $base=0;
        }
        return round($base,2);
    }
    /**
     * Enter description here...
     *
     * @param unknown_type $date
     * @param unknown_type $mode
     * @param unknown_type $key
     * @return unknown
     */
    function salaryCrypt($date, $mode='encode', $key="dinglicom") {
        $key = $this->rsaKey;
        $key = md5($key); //��MD5��ϣ����һ����Կ
        if ($mode == 'decode') {
            $date = base64_decode($date);
        }
        if (function_exists('mcrypt_create_iv')) {
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        }
        if (isset($iv) && $mode == 'encode') {
            $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $date, MCRYPT_MODE_ECB, $iv);
        } elseif (isset($iv) && $mode == 'decode') {
            $passcrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $date, MCRYPT_MODE_ECB, $iv);
        }
        if ($mode == 'encode') {
            $passcrypt = base64_encode($passcrypt);
        }
        if ($mode == 'decode') {
            $passcrypt = trim($passcrypt);
        }
        return $passcrypt;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $base
     * @return unknown
     */
    function salaryGjj($am,$com='dl') {
        $res = array('p'=>0,'c'=>0);
        if($com=='dl'){
            /*
            if ($base >= $this->gjjBase) {
                $res = round($this->gjjBase * 0.05, 2);
            }else
                $res=round($base * 0.05, 2);
                */
        }elseif($com=='sy'){
            $base=1320;//��ͻ���
            $cess=0.12;//������ٷְ�
            //�����ж�
            if(round($am)>=8000){
                $base=round($am*0.34,2);
            }elseif(round($am)>=4000){
                $base=round($am*0.42,2);
            }elseif(round($am)>=2640){
                $base=round($am*0.50,2);
            }
            $res['p']=round($base*$cess,2);
            $res['c']=round($base*$cess,2);
        }elseif($com=='br'){
            
        }
        return $res;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $base
     * @return unknown
     */
    function salaryShb($base,$com='dl',$usercard='') {
        $res = array('p'=>0,'c'=>0);
        //$res = $this->shbBase;
        if($com=='dl'){
            
        }elseif($com=='sy'){
            $base_1=1680;//���ϣ�ʧҵ�����˻���
            $base_2=2521;//������ҽ�ƻ���
            if($usercard=='01001864'){//������
                $base_1=2000;
            }
            $res['p']=round(($base_1*0.08)+($base_1*0.002)+($base_2*0.02+3),2);
            $res['c']=round(($base_1*0.2)+($base_1*0.01)+($base_1*0.008)+($base_2*0.008)+($base_2*0.1),2);
        }elseif($com=='br'){
            
        }
        return $res;
    }

    /**
     * ��ȡ�����������ݣ���������Ϊ�㡣
     *
     * @param unknown_type $base
     */
    function getFinanceValue($base) {
        if (is_numeric($base) && $base > 0) {
            $base = round($base, 2);
        } else {
            $base = 0;
        }
        return $base;
    }
    /**
     *���ݴ���������ݣ���������ֵ
     * @param <type> $d
     * @return int 
     */
    function cfv($d){
        if (is_numeric($d) && $d > 0) {
            $d = round($d+0.00000001, 2);
        } else {
            $d = 0;
        }
        return $d;
    }

    /**
     *
     */
    function finiView($d){
        $res=$this->cfv($d);
        return empty($res)?'-':$res;
    }
    /**
     * Enter description here...
     *
     */
    function loadingDiv() {
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
    function getCoGjj() {
        return $this->coGjjBase;
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function getCoShb() {
        return $this->coShbBase;
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function getPreparAm() {
        return $this->prepareAm;
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function getHandicapAm() {
        return $this->handicapAm;
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function getManageAm() {
        return $this->manageAm;
    }
    

    function getRsaInfo() {
        $sql = "select name , value from config where name in ('SalaryModulo','SalaryPublicKey','SalaryCiphertext','SalaryZero') ";
        $query = $this->_db->query($sql);
        while ($row = $this->_db->fetch_array($query)) {
            $this->salaryRsa[$row['name']] = $row['value'];
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
    function configCrypt($date, $mode='encode', $key="dinglicom") {
        $key = md5($key); //��MD5��ϣ����һ����Կ
        if ($mode == 'decode') {
            $date = base64_decode($date);
        }
        if (function_exists('mcrypt_create_iv')) {
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        }
        if (isset($iv) && $mode == 'encode') {
            $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $date, MCRYPT_MODE_ECB, $iv);
        } elseif (isset($iv) && $mode == 'decode') {
            $passcrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $date, MCRYPT_MODE_ECB, $iv);
        }
        if ($mode == 'encode') {
            $passcrypt = base64_encode($passcrypt);
        }
        if ($mode == 'decode') {
            $passcrypt = trim($passcrypt);
        }
        return $passcrypt;
    }

    /**
     * ��������Ƿ�Ϊ������Ϣ
     */
    function numberCheck($obj,$ck=array()) {
        if (is_array($obj)) {
            $res = true;
            $i=0;
            foreach ($obj as $key => $val) {
                if (!is_numeric($val)&&in_array($i, $ck)) {
                    $res = false;
                }
                $i++;
            }
            return $res;
        } else {
            return is_numeric($obj);
        }
    }

    /**
     * ���鴦��ӽ���
     * @param <type> $data
     * @param <type> $type
     * @param <type> $exp
     * @return <type>
     */
    function dataCript($data, $type='encode', $exp=array()) {
        if (is_array($data)) {
            $res = array();
            $i = 0;
            foreach ($data as $key => $val) {
                if (in_array($i, (array)$exp)) {
                    $res[$key] = $val;
                } else {
                    if ($type == 'decode') {
                        $res[$key] = $this->decryptDeal($val);
                    } else {
                        $res[$key] = $this->encryptDeal($val);
                    }
                }
                $i++;
            }
        } else {
            if ($type == 'decode') {
                $res = $this->decryptDeal($data);
            } else {
                $res = $this->encryptDeal($data);
            }
        }
        return $res;
    }


    /**
     * ��˰�Ƚ�,�����¸�˰ʱ�䷵��true,���þɸ�˰ʱ�䷵��false
     * @param $year
     * @param $mon
     * @return bool
     */
    function compare_year_mon_income($year, $mon, $com)
    {
        $year = intval($year);
        if($mon != '10'){
            $mon = intval(str_replace('0', '', $mon));
        }

        if($year >2018){
            return true;
        }elseif($year == 2018){
            if ($com!='xs' && $com!='jk'){
                if($mon >= 9){
                    return true;
                }else{
                    return false;
                }
            }else{
                if($mon >= 10){
                    return true;
                }else{
                    return false;
                }
            }

        }else{
            return false;
        }
    }

}
?>