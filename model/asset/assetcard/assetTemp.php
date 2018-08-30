<?php
/**
 * @author Administrator
 * @Date 2012��10��7�� 10:36:31
 * @version 1.0
 * @description:��Ƭ������ʱ�� Model��
 */
 class model_asset_assetcard_assetTemp  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_card_temp";
		$this->sql_map = "asset/assetcard/assetTempSql.php";
		parent::__construct ();
	}

	/**
	 * ��Ӷ���
	 */
	function add_d($object, $isAddInfo = false) {
		try{
			$this->start_d();
			
			if ($isAddInfo) {
				$object = $this->addCreateInfo ( $object );
			}
			//�ʲ�����ȥ�ո�
			$object['assetName'] = trim($object['assetName']);
	        //��ȡ�ʼ���¼
		    $emailArr = $object['email'];
		    unset($object['email']);
		    
		    //�����ʲ�����id��ȡ�ʲ�����code
		    $direCode =new model_asset_basic_directory();
		    $code= $direCode-> getCodeById_d($object['assetTypeId']);
		    //�Զ������ʲ�����
		    $codeDao = new model_common_codeRule ();
		    $sql = "SELECT MAX(buyDate) as buyDate from oa_asset_card";
		    $buyDateArr = $this->_db->get_one($sql);
		    $buyDate = $buyDateArr['buyDate'];
		    $maxBuyDate = date('Ym',strtotime($buyDate));
		    $thisByDate = date('Ym',strtotime($object['buyDate']));
		    if( $maxBuyDate!=$thisByDate ){
		    	$mainCode = $codeDao->assetcardCode ( "oa_asset_assetcard",$object['property'],true );
		    }else{
		    	$mainCode = $codeDao->assetcardCode ( "oa_asset_assetcard",$object['property'],false );
		    }
		    $object['assetCode'] = $object['companyCode'].$thisByDate.$code['code'].$mainCode;
		    $assetcardDao = new model_asset_assetcard_assetcard();//�ʲ���Ƭ
		    //������������
		    $rs = $assetcardDao->getParentDept_d($object['orgId']);
		    $object['parentOrgId'] = $rs[0]['parentId'];
		    $object['parentOrgName'] = $rs[0]['parentName'];
		    //ʹ�ö�������
		    $rs = $assetcardDao->getParentDept_d($object['useOrgId']);
		    $object['parentUseOrgId'] = $rs[0]['parentId'];
		    $object['parentUseOrgName'] = $rs[0]['parentName'];
		    
		    //�������յ�id��ȡ�ʲ�����������Ϣ
		    if(isset($object['receiveItemId']) && !empty($object['receiveItemId']) && empty($object['requireinId'])){
		    	$receiveItemDao = new model_asset_purchase_receive_receiveItem();
		    	$requirementInfo = $receiveItemDao->getRequirementInfo($object['receiveItemId']);
		    	$object['requireId'] = $requirementInfo['relDocId'];
		    	$object['requireCode'] = $requirementInfo['relDocCode'];
		    }
		    //��������ת�ʲ�id��ȡ�ʲ�����������Ϣ
		    if(isset($object['requireinId']) && !empty($object['requireinId'])){
		    	$requireinDao = new model_asset_require_requirein();
		    	$rs = $requireinDao->find(array('id'=>$object['requireinId']),null,'requireId,requireCode');
		    	$object['requireId'] = $rs['requireId'];
		    	$object['requireCode'] = $rs['requireCode'];		    	
		    }
		    //���������ֵ䴦�� add by chengl 2011-05-15
			$newId = $this->create ( $object );
			if( $_GET['actType']=='submit' ){
				$assetcardDao->addBeachByProperty_d($newId);
			}
			//�ı��ʲ�����״̬
			if($object['isPurch']=='1'){
				$receiveItemId = $object['receiveItemId'];
				//����������ϸ��Ƭ����������״̬
				$receiveItemDao->updateCardNum($receiveItemId,$object['number']);
				$receiveItemDao->changeCardStatus($receiveItemId);
				//�����ʲ���������״̬
				$requirementDao = new model_asset_require_requirement();
				$requirementDao->updateRecognize($object['requireId']);
			}
			//�ı�����ת�ʲ�������ص���״̬
			if(isset($object['requireinId']) && !empty($object['requireinId'])){
				//ͳһʵ����
				$receiveItemDao = new model_asset_purchase_receive_receiveItem();
				$requireinDao = new model_asset_require_requirein();
				$requireinitemDao = new model_asset_require_requireinitem();
				
				$number = $object['number'];
				$receiveItemId = $object['receiveItemId'];
				//����������ϸ��Ƭ����������״̬
				$receiveItemDao->updateCardNum($receiveItemId,$number);
				$receiveItemDao->changeCardStatus($receiveItemId);
				//�������ɿ�Ƭ����
				$requireinitemDao->updateCardNum($object['requireinItemId'],$number);
				$requireinId = $object['requireinId'];
				//���µ���״̬
				$requireinDao->updateStatus($requireinId);
				//����������������ת�ʲ�״̬
				$requireinDao->updateRequireInStatus($requireinId);
			}
			//�����ʼ� ,������Ϊ�ύʱ�ŷ���
			if( $object['ismail'] == '1'&& !empty($emailArr['TO_ID'])){
				$this->mailDeal_d('assetCard',$emailArr['TO_ID'],array('id' =>$newId ,'mailUser' =>$emailArr['TO_NAME'],'assetName'=>$object['assetName']));
			}
			
			$this->commit_d();
			return $newId;
		}catch( Exception $e ){
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ����������ȡ����
	 */
	function getAssetInfo_d($id) {
		//return $this->getObject($id);
		$condition = array ("id" => $id );
		$this->searchArr['id']=$id;
		$rows = $this->listBySqlId ('select_card');
		return $rows[0];
	}
	
	/**
	 * ���������޸Ķ���
	 */
	function edit_d($object, $isEditInfo = false) {
		if ($isEditInfo) {
			$object = $this->addUpdateInfo ( $object );
		}
		if( $_GET['actType']=='submit' ){
			$cardDao = new model_asset_assetcard_assetcard();
			$cardDao->addBeachByProperty_d($object['id']);
		}
		return $this->updateById ( $object );
	}
	
	/**
	 * ���¿�Ƭ���
	 */
	function updateAssetCode_d (){
		$rows=$this->list_d();
		$assetCoderow=array();
		$assetcardDao = new model_asset_assetcard_assetcard();
		foreach ($rows as $key => $val){
			$assetCoderow = $assetcardDao->find(array('templeId' => $val['id']),null,'assetCode');
// 			print_r($assetCoderow);die();
			if(!empty($assetCoderow)){
				$assetCode = $assetCoderow['assetCode'];
				$sql = "update oa_asset_card_temp set assetCode = substring('{$assetCode}',1,length('{$assetCode}')-3) where id = ".$val['id'];
				$this->query($sql);
			}
		}
	}
	
	/**
	 * ƥ��excel�ֶ�
	 */
	function formatArray_d($datas,$titleRow){
		// �Ѷ������
		$definedTitle = array(
				'�ʲ�����' => 'assetName', 'Ӣ������' => 'englishName', '�ʲ�����' => 'property', '�ʲ����' => 'assetTypeName',
				'�ֻ�Ƶ��' => 'mobileBand', '�ֻ�����' => 'mobileNetwork', '�ʲ���Դ' => 'assetSourceName', '�ʲ���;' => 'useType', 
				'Ʒ��' => 'brand', '����ͺ�' => 'spec', '����' => 'deploy', '������' => 'machineCode', '����' => 'number','��λ' => 'unit', 
				'��Ӧ��' => 'supplierName', '��������' => 'productName', '��ŵص�' => 'place', '��������' => 'agencyName', '������˾' => 'companyName', 
				'��������' => 'orgName', '������' => 'belongMan', 'ʹ�ò���' => 'useOrgName', 'ʹ����' => 'userName', '��ע' => 'remark',
				'��������' => 'buyDate', '��������' => 'wirteDate', '�䶯��ʽ' => 'changeTypeName', '�۾ɷ�ʽ' => 'deprName',
				'����ԭֵ' => 'origina', 'ԭֵ����' => 'localCurrency', '�ۼ��۾�' => 'depreciation', '��ֵ' => 'netValue',
				'����' => 'netAmount', 'Ԥ�ƾ���ֵ' => 'salvage', '�����۾ɶ�' => 'periodDepr', '��ʼʹ������' => 'beginTime', 
				'Ԥ��ʹ���ڼ���' => 'estimateDay', '��ʹ���ڼ���' => 'alreadyDay', '�̶��ʲ���Ŀ' => 'subName', '�۾ɷ�����Ŀ' => 'expenseItems'
		);
		// ������֤�ı���
		$dateTitle = array(
				'��ʼʹ������' => 'beginTime', '��������' => 'buyDate', '��������' => 'wirteDate'
		);
	
		// �����µ�����
		foreach($titleRow as $k => $v){
			// �������Ϊ�գ���ɾ��
			if(trim($datas[$k]) === ''){
				unset($datas[$k]);
				continue;
			}
			// ��������Ѷ������ݣ�����м�ֵ�滻
			if(isset($definedTitle[$v])){
				// ʱ�����ݴ���
				if(isset($dateTitle[$v]) && is_numeric(trim($datas[$k]))){
					$datas[$k] = date('Y-m-d',(mktime(0,0,0,1, $datas[$k] - 1 , 1900)));
				}
	
				// ��ʽ����������
				$datas[$definedTitle[$v]] = trim($datas[$k]);
			}
			// ������ɺ�ɾ������
			unset($datas[$k]);
		}
		return $datas;
	}
	
	/**
	 * ��Ƭ��¼����
	 */
	function import_d(){
		set_time_limit(0);
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();

		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream"|| $fileType == "application/kset") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name, 1);
			spl_autoload_register("__autoload");
			
			if($excelData[0][0] == "��������д"){//��2��Ϊ��������
				$titleRow = $excelData[1];
				unset($excelData[0]);
				unset($excelData[1]);
			}else{//��1��Ϊ��������
				$titleRow = $excelData[0];
				unset($excelData[0]);
			}
			
			//ɾ������Ŀո��Լ��հ�����
			foreach ($excelData as $key => $val){
				$delete = true;
				foreach( $val as $index => $value ){
					$excelData[$key][$index] = trim($value);
					if($value != ''){
						$delete = false;
					}
				}
				if($delete){
					unset( $excelData[$key] );
				}
			}

			//�жϴ�����Ƿ�Ϊ��Ч����
			if (count($excelData)>0) {
				$assetcardDao = new model_asset_assetcard_assetcard();//�ʲ���Ƭ
				$directoryDao = new model_asset_basic_directory();//�ʲ����
				$agencyDao = new model_asset_basic_agency();//��������
				$companyDao = new model_deptuser_branch_branch();//��˾��Ϣ
				$deptDao = new model_deptuser_dept_dept();//������Ϣ
				$userDao = new model_deptuser_user_user();//��Ա��Ϣ
				$supplierDao = new model_supplierManage_formal_flibrary();//��Ӧ��
				$productDao = new model_stock_productinfo_productinfo();//������Ϣ
				$changeDao = new model_asset_basic_change();//�䶯��ʽ
				$deprDao = new model_asset_basic_deprMethod();//�۾ɷ���
				$dataDao = new model_system_datadict_datadict();//�����ֵ�
				//������ѭ��
				foreach( $excelData as $key => $val ){
					// ��ʽ������
					$val = $this->formatArray_d($val,$titleRow);
					
					$actNum = $key + 1;
					$machineCodeArr = array();
					
					//��ƬĬ��ʹ��״̬Ϊ����
					$val['useStatusName'] = '����';
					$val['useStatusCode'] = 'SYZT-XZ';
					
					/******************************��������д����******************************/
					//�ʲ�����
					if($val['assetName'] != ''){
						//ֻ�е��ʲ�����Ϊ�ֻ�ʱ��������ֻ�Ƶ�Σ��ֻ��������Ч
						if($val['assetName'] != "�ֻ�"){
							unset($val['mobileBand']);
							unset($val['mobileNetwork']);
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����д�ʲ�����';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//�ʲ�����
					if($val['property'] != ''){
						if($val['property'] != '0' && $val['property'] != '1' && $val['property'] != "�̶��ʲ�" && $val['property'] != "��ֵ����Ʒ"){
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!�ʲ���������0��̶��ʲ���1���ֵ����Ʒ';
							array_push( $resultArr,$tempArr );
							continue;
						}
						if($val['property'] == "�̶��ʲ�"){
							$val['property'] = '0';
						}elseif($val['property'] == "��ֵ����Ʒ"){
							$val['property'] = '1';
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!�ʲ����Բ���Ϊ��';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//�ʲ����
					if($val['assetTypeName'] != ''){
						$rs = $directoryDao->find(array('name' => $val['assetTypeName']),null,'id');
						if(!empty($rs)){
							$val['assetTypeId'] = $rs['id'];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!���ʲ���𲻴���';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����д�ʲ����';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//�ʲ���Դ
					if($val['assetSourceName'] != ''){
						$rs = $dataDao->find(array('dataName' => $val['assetSourceName']),null,'dataCode');
						if(!empty($rs)){
							$val['assetSource'] = $rs['dataCode'];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!���ʲ���Դ������';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����д�ʲ���Դ';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//�ʲ���;
					if($val['useType'] != ''){
						$rs = $dataDao->find(array('dataName' => $val['useType']),null,'dataCode');
						if(empty($rs)){
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!���ʲ���;������';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}
					//������				
					if($val['machineCode'] == ''){
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����д������';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//����
					if($val['number'] != ''){
						//�������������
						$machineCodeArr = explode(",",$val['machineCode']);
						$num = count($machineCodeArr); 
						
						foreach ($machineCodeArr as $machineCode){
							if($machineCode == ""){
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '����ʧ��!���ڿյĻ�����';
								array_push( $resultArr,$tempArr );
								continue 2;
							}
						}
						if($num != count(array_unique($machineCodeArr))){
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!�����ظ��Ļ�����';
							array_push( $resultArr,$tempArr );
							continue;
						}
						if($num != $val['number']){
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!��������������������һ��';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����д����';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//��Ӧ��
					if($val['supplierName'] != ''){
						$rs = $supplierDao->find(array('suppName' => $val['supplierName']),null,'id');
						if(!empty($rs)){
							$val['supplierId'] = $rs['id'];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!�ù�Ӧ�̲�����';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}
					//��������
					if($val['productName'] != ''){
						$rs = $productDao->find(array('productName' => $val['productName']),null,'id,productCode');
						if(!empty($rs)){
							$val['productId'] = $rs['id'];
							$val['productCode'] = $rs['productCode'];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!���������Ʋ�����';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}
					//��������
					$chargeId = '';//������������id
					if($val['agencyName'] != ''){
						$rs = $agencyDao->find(array('agencyName' => $val['agencyName']),null,'agencyCode,chargeId');
						if(!empty($rs)){
							$val['agencyCode'] = $rs['agencyCode'];
							$chargeId = $rs['chargeId'];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!���������򲻴���';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����д��������';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//������˾
					if($val['companyName'] != ''){
						$rs = $companyDao->find(array('NameCN' => $val['companyName']),null,'NamePT');
						if(!empty($rs)){
							$val['companyCode'] = $rs['NamePT'];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!��������˾������';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����д������˾';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//��������
					if($val['orgName'] != ''){
						$rs = $deptDao->find(array('DEPT_NAME' => $val['orgName']),null,'DEPT_ID');
						if(!empty($rs)){
							$val['orgId'] = $rs['DEPT_ID'];
						    //������������
						    $rs = $assetcardDao->getParentDept_d($val['orgId']);
						    $val['parentOrgId'] = $rs[0]['parentId'];
						    $val['parentOrgName'] = $rs[0]['parentName'];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!���������Ų�����';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����д��������';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//������
					if($val['belongMan'] != ''){
						//�������ܳ����ظ���������findAll
						$rs = $userDao->findAll(array('USER_NAME' => $val['belongMan']),null,'USER_ID,DEPT_ID,Company');
						if(!empty($rs)){
							$companyFlag = false;
							$deptFlag = false;
							foreach ($rs as $v){
								if($v['Company'] == $val['companyCode']){
									$companyFlag = true;
								}else{
									$companyFlag = false;
								}
								if($v['DEPT_ID'] == $val['orgId']){
									$deptFlag = true;
								}else{
									$deptFlag = false;
								}
								if($companyFlag && $deptFlag){
									$val['belongManId'] = $v['USER_ID'];
									break;
								}
							}
							if(!$companyFlag){
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '����ʧ��!����������������˾��ƥ��';
								array_push( $resultArr,$tempArr );
								continue;
							}
							if(!$deptFlag){
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '����ʧ��!�����������������Ų�ƥ��';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!�������˲�����';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����д������';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//ʹ�ò���
					if($val['useOrgName'] != ''){
						$rs = $deptDao->find(array('DEPT_NAME' => $val['useOrgName']),null,'DEPT_ID');
						if(!empty($rs)){
							$val['useOrgId'] = $rs['DEPT_ID'];
							//ʹ�ö�������
							$rs = $assetcardDao->getParentDept_d($val['useOrgId']);
							$val['parentUseOrgId'] = $rs[0]['parentId'];
							$val['parentUseOrgName'] = $rs[0]['parentName'];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!��ʹ�ò��Ų�����';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����дʹ�ò���';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//ʹ����
					if($val['userName'] != ''){
						$rs = $userDao->findAll(array('USER_NAME' => $val['userName']),null,'USER_ID,DEPT_ID');
						if(!empty($rs)){
							$deptFlag = false;
							foreach ($rs as $v){
								if($v['DEPT_ID'] == $val['useOrgId']){
									$deptFlag = true;
									$val['userId'] = $v['USER_ID'];
									//���ʹ�����������������˲�ͬ�����ʲ���ʹ��״̬��Ϊʹ����
									if($val['userId'] != $chargeId){
										$val['useStatusName'] = 'ʹ����';
										$val['useStatusCode'] = 'SYZT-SYZ';
									}
									break;
								}
							}
							if(!$deptFlag){
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '����ʧ��!��ʹ������ʹ�ò��Ų�ƥ��';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!��ʹ���˲�����';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����дʹ����';
						array_push( $resultArr,$tempArr );
						continue;
					}
					/******************************������д����******************************/
					//��������
					if($val['buyDate'] == ''){
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����д��������';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//��������
					if($val['wirteDate'] == ''){
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����д��������';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//�䶯��ʽ
					if($val['changeTypeName'] != ''){
						$rs = $changeDao->find(array('name' => $val['changeTypeName']),null,'code');
						if(!empty($rs)){
							$val['changeTypeCode'] = $rs['code'];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!�ñ䶯��ʽ������';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����д�䶯��ʽ';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//�۾ɷ�ʽ
					if($val['deprName'] != ''){
						$rs = $deprDao->find(array('name' => $val['deprName']),null,'code');
						if(!empty($rs)){
							$val['deprCode'] = $rs['code'];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!���۾ɷ�ʽ������';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}
					//�Ƿ����ɿ�Ƭ��Ĭ��Ϊ��
					$val['isCreate'] = '0';
					//�汾��Ĭ��Ϊ1
					$val['version'] = '1';

					//������ʱ��Ƭ���ʲ���Ƭ
					$tempId = $this->importAdd_d($val);
					if($tempId){
						$tempArr['result'] = '�����ɹ�';
					}else{
						$tempArr['result'] = '����ʧ��';
					}
					
					$tempArr['docCode'] = '��' . $actNum .'������';
					array_push( $resultArr,$tempArr );
				}
				return $resultArr;
			} else {
				msg( "�ļ������ڿ�ʶ������!");
			}
		} else {
			msg( "�ϴ��ļ����Ͳ���EXCEL!");
		}
	}
	
	function importAdd_d($object){
		try{
			$this->start_d();
		    
		    //�Զ������ʲ�����
		    $codeDao = new model_common_codeRule ();
		    $sql = "SELECT MAX(buyDate) as buyDate from oa_asset_card";
		    $buyDateArr = $this->_db->get_one($sql);
		    $buyDate = $buyDateArr['buyDate'];
		    $maxBuyDate = date('Ym',strtotime($buyDate));
		    $thisByDate = date('Ym',strtotime($object['buyDate']));
		    if( $maxBuyDate!=$thisByDate ){
		    	$mainCode = $codeDao->assetcardCode ( "oa_asset_assetcard",$object['property'],true );
		    }else{
		    	$mainCode = $codeDao->assetcardCode ( "oa_asset_assetcard",$object['property'],false );
		    }
		    $object['assetCode'] = $object['companyCode'].$thisByDate.$object['assetTypeCode'].$mainCode;
		    $newId = $this->create($object);
		    
		    //�����ʲ���Ƭ
		    $assetcardDao = new model_asset_assetcard_assetcard();
		    $object['templeId'] = $newId;
		    if($assetcardDao->addBeach_d($object,true)){
		    	//���¿�Ƭ����״̬Ϊ��
		    	$this->update(array('id' => $newId), array('isCreate' => '1'));
		    }
					
			$this->commit_d();
			return $newId;
		}catch( Exception $e ){
			$this->rollBack();
			return null;
		}
	}
	
	/**
	 * ���뿨Ƭ��Ϣʱ���ɿ�Ƭ��¼
	 */
	function addByCardImport_d($cardInfo){
		$assetCode = substr($cardInfo['assetCode'],0,strlen($cardInfo['assetCode'])-3);//��Ƭ���ȥ����3λ,��Ϊ��Ƭ��¼���
		$rs = $this->find(array('assetCode' => $assetCode),null,'id,machineCode,number');
		if(!empty($rs)){//������صĿ�Ƭ��¼��Ϣ,���и���
			if(isset($cardInfo['machineCode'])){//���������
				if(!empty($rs['machineCode'])){//ԭ������,��ϲ�
					$rs['machineCode'] = $rs['machineCode'].','.$cardInfo['machineCode'];
				}else{
					$rs['machineCode'] = $cardInfo['machineCode'];
				}
			}
			$rs['number'] = $rs['number'] + 1;//�ۼӿ�Ƭ����
			parent::edit_d($rs, true);
			return $rs['id'];
		}else{//������Ƭ��¼
			$cardInfo['assetCode'] = $assetCode;
			$cardInfo['number'] = 1;
			$cardInfo['isCreate'] = 1;//�����ɿ�Ƭ
			return parent::add_d($cardInfo, true);//����id
		}
	}
 }