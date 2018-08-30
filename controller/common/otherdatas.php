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
	 * 获取审批选择提交人员
	 */
	function c_getWorkflowEnterUser(){
		if(!isset($_POST['task'])){
			return null;
		}
		echo util_jsonUtil::encode ( $this->service->getWorkflowEnterUser_d($_POST['task']) );
	}

	/**
	 * 根据人员账号 获取 员工编号
	 */
	function c_getUserNo(){
		if(!isset($_POST['userAccount'])){
			return null;
		}
		echo $this->service->getUserCard($_POST['userAccount']);
	}

	/**
	 * 根据职位id获取职位名称
	 */
	function c_getJobName(){
		if(!isset($_POST['jobId'])){
			return null;
		}
		echo util_jsonUtil::iconvGB2UTF ( $this->service->getJobName_d($_POST['jobId']));
	}

	/**
	 * 获取费用项目
	 */
	function c_getFeeType(){
		$rs = $this->service->getFeeType();
		echo util_jsonUtil::encode( $rs );
	}

    /**
     * 获取表单类型
     */
    function c_getBillType(){
        $rs = $this->service->getBillType();
        echo util_jsonUtil::encode( $rs );
    }
        /**
     * 获取表单类型
     */
    function c_getCompanyAndAreaInfo(){
        $rs = $this->service->getCompanyAndAreaInfo();
        echo util_jsonUtil::encode( $rs );
    }

    /**
     * 获取员工信息 - 用户身份证号，联系方式
     */
    function c_getPersonnelInfo(){
		if(!isset($_POST['userAccount'])){
			return null;
		}
        echo util_jsonUtil::encode( $this->service->getPersonnelInfo_d($_POST['userAccount']) );
    }

    /**
     * 写一个界面，可以同步报表系统数据
     */
    function c_toLink(){
		$this->view('link');
    }

    /**
     * 写一个输出组织机构XML文件的方法
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
     * 调用webservice的功能
     */
    function c_linkWebService(){
		try {
			$soap = new SoapClient(REPORTURL.'services/syncOrg?wsdl',
				array('login' => 'trp','password' => 'xxxxxxxx')
			); # wsdl方式
//	    	$soap = new SoapClient(null, # uri方式
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
	 * 单点登录报表功能
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

	/*********************** 报销数据更新***********************/
    /**
     * 费用报销更新
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
     * 获取配置
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
     * 更新配置
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
	 * 单点登录AWS功能-跳转到首页
	 */
	function c_toSignInAws(){
		$userId = $_SESSION['USER_ID'];
		$ip = $_SESSION['IP'];
		$skey = md5($userId.$ip.day_date);
		// 从aws获取sid
		$result = util_curlUtil::getDataForSignInAWS ( array (
			"cmd"  => "API_BENCHMARK_SSO_CREATESID",
			"uid"  => $userId,
			"ip"   => $ip,
			"skey" => $skey
		) );
		if(empty($result)){
			msgRf ( "跳转新OA失败，请联系系统管理员！" );
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
	 * 单点登录AWS功能-跳转到具体栏目
	 */
	function c_toSignInAwsMenu(){
		if(!isset($_GET['reUrl']) || empty($_GET['reUrl'])){
			msgRf ( "栏目路径配置有误，请联系系统管理员！" );
			exit ();
		}else{
			$userId = $_SESSION['USER_ID'];
			$ip = $_SESSION['IP'];
			$skey = md5($userId.$ip.day_date);
			// 从aws获取sid
			$result = util_curlUtil::getDataForSignInAWS ( array (
				"cmd"  => "API_BENCHMARK_SSO_CREATESID",
				"uid"  => $userId,
				"ip"   => $ip,
				"skey" => $skey
			) );
			if(empty($result)){
				msgRf ( "跳转新OA失败，请联系系统管理员！" );
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