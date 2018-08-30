<?php
/*
 * Created on 2012-4-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_common_otherdatas extends controller_base_action{
	function __construct() {
		$this->objName = "otherdatas";
		$this->objPath = "common";
		parent::__construct ();
	}

	/**
	 * ��ȡ����ѡ���ύ��Ա
	 */
	function c_getWorkflowEnterUser(){
		if(!isset($_POST['task'])){
			return null;
		}
		echo util_jsonUtil::encode ( $this->service->getWorkflowEnterUser_d($_POST['task']) );
	}

	/**
	 * ������Ա�˺� ��ȡ Ա�����
	 */
	function c_getUserNo(){
		if(!isset($_POST['userAccount'])){
			return null;
		}
		echo $this->service->getUserCard($_POST['userAccount']);
	}

	/**
	 * ����ְλid��ȡְλ����
	 */
	function c_getJobName(){
		if(!isset($_POST['jobId'])){
			return null;
		}
		echo util_jsonUtil::iconvGB2UTF ( $this->service->getJobName_d($_POST['jobId']));
	}

	/**
	 * ��ȡ������Ŀ
	 */
	function c_getFeeType(){
		$rs = $this->service->getFeeType();
		echo util_jsonUtil::encode( $rs );
	}

    /**
     * ��ȡ������
     */
    function c_getBillType(){
        $rs = $this->service->getBillType();
        echo util_jsonUtil::encode( $rs );
    }
        /**
     * ��ȡ������
     */
    function c_getCompanyAndAreaInfo(){
        $rs = $this->service->getCompanyAndAreaInfo();
        echo util_jsonUtil::encode( $rs );
    }

    /**
     * ��ȡԱ����Ϣ - �û����֤�ţ���ϵ��ʽ
     */
    function c_getPersonnelInfo(){
		if(!isset($_POST['userAccount'])){
			return null;
		}
        echo util_jsonUtil::encode( $this->service->getPersonnelInfo_d($_POST['userAccount']) );
    }

    /**
     * дһ�����棬����ͬ������ϵͳ����
     */
    function c_toLink(){
		$this->view('link');
    }

    /**
     * дһ�������֯����XML�ļ��ķ���
     */
    function c_getUnitXML($outputType = 'str'){
		set_time_limit(0);
		$arr = array(
			'unitInfo' => $this->service->getUnitUser(),
			'roleInfoList' => $this->service->getRoleInfo(),
			'userRelaRoleList' => $this->service->getUserRelaRole()
		);
		$xmlUtilDao = new util_xmlUtil();
		return base64_encode(trim($xmlUtilDao->outXml($arr,$outputType)));
    }

    /**
     * ����webservice�Ĺ���
     */
    function c_linkWebService(){
		try {
			$soap = new SoapClient(REPORTURL.'services/syncOrg?wsdl',
				array('login' => 'trp','password' => 'xxxxxxxx')
			); # wsdl��ʽ
//	    	$soap = new SoapClient(null, # uri��ʽ
//	    		array(
//					'location' =>"http://localhost/soap/soapserver.php",
//					'uri' => 'myuri'
//				)
//			);
			$soap->soap_defencoding = 'utf-8';
			$soap->xml_encoding = 'utf-8';
			$xml = $this->c_getUnitXML();
			$soap->sync($xml);
//			print_r($soap->__getFunctions());
			exit('success');
		}catch(Exception $e){
			exit($e->getMessage());
		}
    }

	/**
	 * �����¼������
	 */
	function c_toReport(){
		$reUrl = $_GET['reUrl'];
		$thisUrl = REPORTURL.'identify!login?userAccont='.$_SESSION['USER_ID'].'&model=sso&ssoUrl='.$reUrl;
		$reUrlCode = MD5($reUrl);
		echo $str =<<<E
			<script type="text/javascript" src="js/jquery/jquery-1.4.2.js"></script>
			<script>
				var scrWidth = screen.availWidth;
				var scrHeight = screen.availHeight;
				window.open("$thisUrl","$reUrlCode","resizable=yes,toolbar=no, menubar=no,scrollbars=yes,location=no,status=no,top=0,left=0,width=" + scrWidth + ",height=" + scrHeight);
			</script>
			<script>window.parent.$(".tabs-selected>.tabs-close").trigger('click')</script>
E;
//		succ_show(REPORTURL.'identify!login?userAccont='.$_SESSION['USER_ID'].'&model=sso&ssoUrl='.$reUrl);
	}

	/*********************** �������ݸ���***********************/
    /**
     * ���ñ�������
     */
	function c_updateEsmproject(){
		try {
			$this->service->updateEsmproject_d($_POST['thisVal']);
			exit('success');
		}catch(Exception $e){
			exit($e->getMessage());
		}
	}

    /**
     * ��ȡ����
     */
    function c_getConfig() {
        try {
            echo util_jsonUtil::iconvGB2UTF($this->service->getConfig(util_jsonUtil::iconvUTF2GB($_POST['name']),
                util_jsonUtil::iconvUTF2GB($_POST['type'])));
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     * ��������
     */
    function c_updateConfig() {
        try {
            $this->service->updateConfig(util_jsonUtil::iconvUTF2GB($_POST['name']),
                util_jsonUtil::iconvUTF2GB($_POST['type']), util_jsonUtil::iconvUTF2GB($_POST['value']));
            echo 'success';
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	
	/**
	 * �����¼AWS����-��ת����ҳ
	 */
	function c_toSignInAws(){
		$userId = $_SESSION['USER_ID'];
		$ip = $_SESSION['IP'];
		$skey = md5($userId.$ip.day_date);
		// ��aws��ȡsid
		$result = util_curlUtil::getDataForSignInAWS ( array (
			"cmd"  => "API_BENCHMARK_SSO_CREATESID",
			"uid"  => $userId,
			"ip"   => $ip,
			"skey" => $skey
		) );
		if(empty($result)){
			msgRf ( "��ת��OAʧ�ܣ�����ϵϵͳ����Ա��" );
			exit ();
		}
		$data = util_jsonUtil::decode ( $result ['data'], true );
		if(!isset($data['data']['sid'])){
			msgRf ( $data['msg'] );
			exit ();
		}else{
			$url = substr(SSOURL,0,strlen(SSOURL)-2)."w";
			succ_show($url.'?cmd=CLIENT_USER_HOME&sid='.$data['data']['sid']);
		}	
	}
	
	/**
	 * �����¼AWS����-��ת��������Ŀ
	 */
	function c_toSignInAwsMenu(){
		if(!isset($_GET['reUrl']) || empty($_GET['reUrl'])){
			msgRf ( "��Ŀ·��������������ϵϵͳ����Ա��" );
			exit ();
		}else{
			$userId = $_SESSION['USER_ID'];
			$ip = $_SESSION['IP'];
			$skey = md5($userId.$ip.day_date);
			// ��aws��ȡsid
			$result = util_curlUtil::getDataForSignInAWS ( array (
				"cmd"  => "API_BENCHMARK_SSO_CREATESID",
				"uid"  => $userId,
				"ip"   => $ip,
				"skey" => $skey
			) );
			if(empty($result)){
				msgRf ( "��ת��OAʧ�ܣ�����ϵϵͳ����Ա��" );
				exit ();
			}
			$data = util_jsonUtil::decode ( $result ['data'], true );
			if(!isset($data['data']['sid'])){
				msgRf ( $data['msg'] );
				exit;
			}else{
				$url = substr(SSOURL,0,strlen(SSOURL)-2)."w";
				header('Location: ' . $url.'?sid='.$data['data']['sid'].str_replace("&quot;", "\"", urldecode($_GET['reUrl'])));
				exit;
			}
		}
	}
}