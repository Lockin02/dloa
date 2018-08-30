<?php
/**
 * @author Administrator
 * @Date 2011��11��16�� 14:35:53
 * @version 1.0
 * @description:�̶��ʲ���Ƭ Model��
 */
 class model_asset_assetcard_assetcard  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_card";
		$this->sql_map = "asset/assetcard/assetcardSql.php";
		parent::__construct ();
	}

	/**
	 * ��ȡ�������ݣ��ʲ����ࡢʹ��״̬���䶯��ʽ���۾ɷ�ʽ��
	 * @author zengzx
	 * @since 1.0 - 2011-11-19
	 */
	function getBaseDate_d(){
		//��ȡ�ʲ�������Ϣ
		$directoryDao = new model_asset_basic_directory();
		$dirFields = "id,name";
		$dirInfo = $directoryDao->findAll(null,null,$dirFields);
		$dirOption = $this->setSelectOption($dirInfo);
		//��ȡ�䶯��ʽ��Ϣ
		$changeDao = new model_asset_basic_change();
		$directoryDao->searchArr['isSysType'] = 0;
		$chnCondition = array('isDel'=>0,'isSysType'=>0);
		$chnFields = "code as id,name";
		$chnInfo = $changeDao->findAll($chnCondition,null,$chnFields);
		$chnOption = $this->setSelectOption($chnInfo);
//		//��ȡʹ��״̬��Ϣ
//		$useStatusDao = new model_asset_basic_useStatus();
//		$useFields = "code as id,name";
//		$useInfo = $useStatusDao->findAll(null,null,$useFields);
//		$useOption = $this->setSelectOption($useInfo);
		//��ȡ�۾ɷ�ʽ��Ϣ
		$deprMethodDao = new model_asset_basic_deprMethod();
		$deprFields = "code as id,name";
		$deprInfo = $deprMethodDao->findAll(null,null,$deprFields);
		$deprOption = $this->setSelectOption($deprInfo);
		return array(
			'dirOption'=>$dirOption,
			'chnOption'=>$chnOption,
//			'useOption'=>$useOption,
			'deprOption'=>$deprOption,
		);//�����ʲ����͡��䶯��ʽ���۾ɷ�ʽ������ѡ��
	}

	/**
	 * ��̬ƴװselect��
	 * @author zengzx
	 * @since 1.0 - 2011-11-21
	 */
	function setSelectOption($info){
		$str = '';
		foreach($info as $key=>$val){
			$str .= "<option value=".$val['id'].">".$val['name']."</option>";
		}
		return $str;
	}

	/**
	 * �ʲ��䶯
	 */
	function change_d($object){
		try{
			$this->start_d();
			
			//������������
			$rs = $this->getParentDept_d($object['orgId']);
			$object['parentOrgId'] = $rs[0]['parentId'];
			$object['parentOrgName'] = $rs[0]['parentName'];
			//ʹ�ö�������
			$rs = $this->getParentDept_d($object['useOrgId']);
			$object['parentUseOrgId'] = $rs[0]['parentId'];
			$object['parentUseOrgName'] = $rs[0]['parentName'];
			$cardOldObj = $this->get_d($object['oldId']);
			$cardObj = $cardOldObj;
			unset($cardOldObj['id']);
			//���ɾɵĿ�Ƭ��¼
			$cardOldObj['isTemp']=1;
			$newId = parent::add_d($cardOldObj,true);
			foreach ( $cardObj as $key=>$val ){
				if( isset($object[$key]) ){
					$cardObj[$key] = $object[$key];
				}
			}
			//���µ�ǰ��Ƭ��Ϣ
			$id = parent::edit_d($cardObj,true);
			//������Ƭ�䶯��¼
			$changeDao = new model_asset_change_assetchange();
			$changeInfo = array(
				'alterDate' => day_date,
				'assetId' => $object['oldId'],
				'oldAssetId' => $newId,
				'businessType' => $object['changeTypeCode'],
				'assetCode' => $object['assetCode'],
			);
			$changeDao->addRecord_d($changeInfo);
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �ʲ��䶯 -- ҵ��������±䶯
	 */
	function changeByObj_d($object,$paramArr){
		try{
			$this->start_d();
			$changeTypeDao = new model_asset_basic_change();
			//��ȡ�䶯��ʽ��Ӧ������
			$changeTypeObj = $changeTypeDao->get_d($paramArr['changeId']);
			$paramArr['changeName']=$changeTypeObj['name'];
			$object['changeTypeCode']=$paramArr['changeCode'];
			$object['changeTypeName']=$paramArr['changeName'];

			$cardOldObj = $this->get_d($object['oldId']);
			$cardObj = $cardOldObj;
			//���ɾɵĿ�Ƭ��¼
			$cardOldObj['isTemp']=1;
			unset($cardOldObj['id']);
			$newId = parent::add_d($cardOldObj,true);
			//���䶯�������滻ԭ�еĿ�Ƭ��¼����
			foreach ( $cardObj as $key=>$val ){
				if( isset($object[$key]) ){
					if( $key == 'origina' ){
						$cardObj[$key] += $object[$key];
					}else{
						$cardObj[$key] = $object[$key];
					}
				}
			}
			$cardObj['version'] = $cardObj['version']*1+1;
			//����ǩ��ʱ�����¿�Ƭ��ʼʹ������
			if($paramArr['changeCode'] == 'charge'){
				$cardObj['beginTime'] = day_date;
			}
			//���µ�ǰ��Ƭ��Ϣ
			$id = $this->updateById ( $cardObj );
			//������Ƭ�䶯��¼
			$changeDao = new model_asset_change_assetchange();
			$changeInfo = array(
				'alterDate' => day_date,
				'assetId' => $object['oldId'],
				'oldAssetId' => $newId,
				'businessType' => $paramArr['changeCode'],
				'businessId' => $paramArr['businessId'],
				'businessCode' => $paramArr['businessCode'],
				'assetCode' => $object['assetCode'],
			);
			$changeDao->addRecord_d($changeInfo);
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ����ID��������ȡ��Ƭ��Ϣ
	 */
	function getCardsByIdArr($idArr,$condition=false){
		$idStr = implode(',',$idArr);
		if(is_array($condition)&&count($condition)>0){
			$this->searchArr=$condition;
		}
		$this->searchArr['ids'] = $idStr;
		$rows = $this->list_d();
		return $rows;
	}

	/**
	 * ��Ӷ���
	 * @linzx
	 */
	function add_d($object, $isAddInfo = false) {
		if ($isAddInfo) {
			//����һ����Ƭʱ�Զ�����Ƭ���isSell��ֵ
			//$object['isSell']="0";
			//��ȡ��ҳ�����е�ֵ������$object������
			$object = $this->addCreateInfo ( $object );
			//�����ʲ�����id��ȡ�ʲ�����code
       		$direCode =new model_asset_basic_directory();
		    $code= $direCode-> getCodeById_d($object['assetTypeId']);
			//�Զ������ʲ�����
           	$codeDao = new model_common_codeRule ();
			$object ['assetCode'] = $codeDao->assetcardCode2 ( "oa_asset_assetcard", $object['property'] ,$object['orgName'],$object['assetabbrev'],$code['code'],$object['buyDate']);

		}
		//���������ֵ䴦��
		$newId = $this->create ( $object );
		//echo $newId;
		return $newId;

	}

	/**
	 * ��Ӷ���  --  ����
	 * @zengzx
	 * 2012��7��9�� 11:06:18
	 */
	function importAdd_d($object, $isAddInfo = false) {
		if ($isAddInfo) {
			//����һ����Ƭʱ�Զ�����Ƭ���isSell��ֵ
			//$object['isSell']="0";
			//��ȡ��ҳ�����е�ֵ������$object������
			$object = $this->addCreateInfo ( $object );
			//�����ʲ�����id��ȡ�ʲ�����code
			//�Զ������ʲ�����
		}
		//���������ֵ䴦��
		$newId = $this->create ( $object );
		//echo $newId;
		return $newId;

	}
	
	/**
	 * ��Ӷ���
	 * @zengzx
	 */
	function addBeach_d($object, $isAddInfo = false) {
		if ($isAddInfo) {
			$object = $this->addCreateInfo ( $object );
		}
		$cardArr = array();
		$assetCode = $object ['assetCode'];
		if(isset($object['machineCode']) && !empty($object['machineCode'])){//���ڻ�����
			//�������������
			$machineCodeArr = explode(",",$object['machineCode']);
			foreach ($machineCodeArr as $key => $val){
				$object['machineCode'] = $val;
				$i = $key+1;
				switch(strlen($i)){
					case 1:$number="00".$i;break;
					case 2:$number="0".$i;break;
					case 3:$number="".$i;break;
				}
				$object ['assetCode'] = $assetCode.$number;
				$cardArr[] = $object;
			}
		}else{
			for ( $i=1;$i<=$object['number'];$i++ ){
				switch(strlen($i)){
					case 1:$number="00".$i;break;
					case 2:$number="0".$i;break;
					case 3:$number="".$i;break;
				}
				$object ['assetCode'] = $assetCode.$number;
				$cardArr[] = $object;
			}
		}
		return $this->createBatch($cardArr);
	}
	/**
	 * �������¿�Ƭ��Ϣ
	 */
	 function updateBeach_d($obj){
		$condition = array ("templeId" => $obj ['tempId'] );
		return $this->update ( $condition, $obj );
	 }

	//���ݿ�Ƭ��id��ȡ�޸�ǰ��Ƭ������

	function getOldCardById_d($id){
		$OldCardObj = $this->get_d($id);
		return $OldCardObj;

	}

	/**
	 * ���������޸Ķ���
	 * �༭����
	 * @linzx
	 */
	function edit_d($object, $isEditInfo = false) {
		if ($isEditInfo) {
			$object = $this->addUpdateInfo ( $object );
		}
		//�����ʲ�����id��ȡ�ʲ�����code --ȡ�������ʲ��������ʲ���� By Bingo 2015.8.31
// 		$direCode =new model_asset_basic_directory();
// 		$code= $direCode-> getCodeById_d($object['assetTypeId']);

		$OldCardObj=$this->getOldCardById_d($object['id']);

//      $thisByDate = date('Ym',strtotime($object['buyDate']));
// 		$afterNum = substr( $object ['assetCode'],-6 );
// 		$object ['assetCode'] = $object['companyCode'].$thisByDate.$code['code'].$afterNum;
		//������������
		$rs = $this->getParentDept_d($object['orgId']);
		$object['parentOrgId'] = $rs[0]['parentId'];
		$object['parentOrgName'] = $rs[0]['parentName'];
		//ʹ�ö�������
		$rs = $this->getParentDept_d($object['useOrgId']);
		$object['parentUseOrgId'] = $rs[0]['parentId'];
		$object['parentUseOrgName'] = $rs[0]['parentName'];

		return $this->updateById ( $object );
	}

	function addCard_d($object){
		$this->addBeach_d($object, true);
		$receiveItem = new model_asset_purchase_receive_receiveItem();
		$receiveItem->changeIsCard($object['receiveItemId']);

	}

	function isRelated_d($assetId){
		$alterDao = new model_asset_change_assetchange();
		$alterDao->pageSize=1;
		$alterDao->asc=true;
		$alterDao->searchArr['assetId']=$assetId;
		$rows = $alterDao->page_d();
		if(is_array($rows)&&$rows[0]['businessId']!=''){
			echo 0;
		}else{
			echo 1;
		}
	}

	/**
	 * �����۾�����ʱ�������ʲ��ۼ��۾�ֵ���ʲ���ֵ
	 */
	function updateDepreciation($equId,$origina,$localNetValue,$period){
			$sql = " update ".$this->tbl_name." set depreciation=($origina - $localNetValue),netValue=$localNetValue,alreadyDay=$period where id=$equId ";
			//echo $sql;
			return $this->query($sql);
	}

	/**
	 * ɾ���۾�����ʱ�������ʲ��ۼ��۾�ֵ���ʲ���ֵ
	 */
	function updateDepreciationReturn($equId,$depreciation,$lastDepreciation=false){
		if(isset($lastDepreciation)&&$depreciation==$lastDepreciation){
			return true;
		}else{
			if($lastDepreciation){
				$sql = " update ".$this->tbl_name." set depreciation=(ifnull(depreciation,0) - $depreciation - $lastDepreciation),netValue=(ifnull(netValue,0) + $depreciation - $lastDepreciation) where id=$equId ";
			}else{
				$sql = " update ".$this->tbl_name." set depreciation=(ifnull(depreciation,0) - $depreciation),netValue=(ifnull(netValue,0) + $depreciation) where id=$equId ";
			}
			//echo $sql;
			return $this->query($sql);
		}
	}
	
	/**
	 * @author chenzb
	 *@param $isTemp �䶯
	 *@param $isDel �Ƿ����
	 *���� $isTemp $isDel ����ȡ��Ƭ��Ϣ
	 */
	function getChildren_d($isTemp,$isDel){
		$this->searchArr['isTemp'] = $isTemp;
		$this->searchArr['isDel'] =$isDel;
//			$this->asc = false;
		$rows=$this->listBySqlId('select_default');
		return $rows;
	}

	/**
	 * ��ȡ��Ƭ��Ϣ
	 */
	function getCards(){


		//��ȡָ�����������µĵ�һ��ͻ�ȡָ�������¸��µĵ�һ��
		$date=date('Y-m-d');
		$arr = getdate();
		if($arr['mon'] == 12){
			$year = $arr['year'] +1;
			$month = $arr['mon'] -11;
	  		$day = $arr['mday'];
	   		if($day < 10){
	    		$mday = '0'.$day;
	   		}else {
	   			 $mday = $day;
	   		}
	   		$nextfirstday = $year.'-0'.$month.'-01';
	  	}else{
	   		$time=strtotime($date);
	   		$nextfirstday=date('Y-m-01',strtotime(date('Y',$time).'-'.(date('m',$time)+1).'-01'));
	  	}
	  	$firstday = date("Y-m-01",strtotime($date));

	  	// �����ʲ�����ʱ��ĵ��£������۾ɣ�������ʲ������۾ɣ��۾�����ʲ������۾�;���˱䶯��¼
		$sql = "select * from oa_asset_card c where c.wirteDate>='$nextfirstday' or c.wirteDate<'$firstday' and c.isDel=0 and c.isTemp=0 and c.netValue>c.salvage";
        $rows = $this->_db->getArray($sql);
		return $rows;
	}
	
	/**
	 * ƥ��excel�ֶ�
	 */
	function formatArray_d($datas,$titleRow){
		// �Ѷ������
		$definedTitle = array(
				'���' => 'serial', '��Ƭ���' => 'assetCode', '�ʲ�����' => 'assetName', '�ʲ�����' => 'property',
				'Ʒ��' => 'brand', '�ʲ����' => 'assetTypeName', '�ֻ�Ƶ��' => 'mobileBand', '�ֻ�����' => 'mobileNetwork',
				'����ͺ�' => 'spec', '����' => 'deploy', '������' => 'machineCode','��λ' => 'unit', '����' => 'number', 
				'��������' => 'agencyName', '��Ӧ��' => 'supplierName', '������˾' => 'companyName', '��������' => 'orgName', '������' => 'belongMan',
				'ʹ�ò���' => 'useOrgName', 'ʹ����' => 'userName', '��ʼʹ������' => 'beginTime', '��ע' => 'remark', 
				'��������' => 'buyDate', '��������' => 'wirteDate','�䶯��ʽ' => 'changeTypeName', '�۾ɷ�ʽ' => 'deprName', 
				'����ԭֵ' => 'origina', 'ԭֵ����' => 'localCurrency', '�ۼ��۾�' => 'depreciation','��ֵ' => 'netValue', 
				'����' => 'netAmount', 'Ԥ�ƾ���ֵ' => 'salvage', '�����۾ɶ�' => 'periodDepr', 'Ԥ��ʹ���ڼ���' => 'estimateDay',
				'��ʹ���ڼ���' => 'alreadyDay', '�̶��ʲ���Ŀ' => 'subName', '�۾ɷ�����Ŀ' => 'expenseItems', 'ʹ��״̬' => 'useStatusName'
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
	 * ��Ƭ��Ϣ����
	 */
	 function import_d(){
	 	try{
	 		$this->start_d();

	 		set_time_limit(0);
			$filename = $_FILES ["inputExcel"] ["name"];
			$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
			$fileType = $_FILES ["inputExcel"] ["type"];
			$resultArr = array();//�������
			$excelData = array ();//excel��������
			$tempArr = array();
			
			//�жϵ��������Ƿ�Ϊexcel��
			if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
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
					$deprDao = new model_asset_basic_deprMethod();//�۾ɷ���
					$directoryDao = new model_asset_basic_directory();//�ʲ����
					$changeDao = new model_asset_basic_change();//�䶯��ʽ
					$agencyDao = new model_asset_basic_agency();//��������
					$companyDao = new model_deptuser_branch_branch();//��˾��Ϣ
					$deptDao = new model_deptuser_dept_dept();//������Ϣ
					$userDao = new model_deptuser_user_user();//��Ա��Ϣ
					$supplierDao = new model_supplierManage_formal_flibrary();//��Ӧ��
					$dataDao = new model_system_datadict_datadict();//�����ֵ�
					$cardArr = $this->list_d();//��Ƭ��Ϣ

					$inArr = array();
					foreach($excelData as $key => $val){
						// ��ʽ������
						$val = $this->formatArray_d($val,$titleRow);
						$actNum = $key + 1;
						
						//���ʹ��״̬Ϊ�գ���Ĭ��Ϊ����
						if($val['useStatusName'] == ""){
							$val['useStatusName'] = '����';
							$val['useStatusCode'] = 'SYZT-XZ';
						}else{
							$rs = $dataDao->find(array('dataName' => $val['useStatusName']),null,'dataCode');
							if(!empty($rs)){
								$val['useStatusCode'] = $rs['dataCode'];
							}
						}
						
						//��Ƭ���
						if($val['assetCode'] == ''){
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '��Ƭ���Ϊ�գ�����ʧ�ܣ�';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
							$assetCode = $val['assetCode'];
							foreach($cardArr as $cardVal){
								if(strtoupper($assetCode) == strtoupper($cardVal['assetCode'])){
									$tempArr['docCode'] = $assetCode;
									$tempArr['result'] = '��Ƭ����Ѵ��ڣ�����ʧ�ܣ�';
									array_push( $resultArr,$tempArr );
									continue 2;
								}
							}
						}
						//�ʲ�����
						if($val['assetName'] != ''){
							//ֻ�е��ʲ�����Ϊ�ֻ�ʱ��������ֻ�Ƶ�Σ��ֻ��������Ч
							if($val['assetName'] != "�ֻ�"){
								unset($val['mobileBand']);
								unset($val['mobileNetwork']);
							}
						}else{
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '�ʲ�����Ϊ�գ�����ʧ�ܣ�';
							array_push( $resultArr,$tempArr );
							continue;
						}
						//�ʲ�����
						if($val['property'] != ''){
							if($val['property'] != '0' && $val['property'] != '1' && $val['property'] != "�̶��ʲ�" && $val['property'] != "��ֵ����Ʒ"){
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '�ʲ����ԷǷ�������ʧ�ܣ�����0��̶��ʲ���1���ֵ����Ʒ';
								array_push( $resultArr,$tempArr );
								continue;
							}elseif($val['property'] == "�̶��ʲ�"){
								$val['property'] = '0';
							}elseif($val['property'] == "��ֵ����Ʒ"){
								$val['property'] = '1';
							}
						}else{
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '�ʲ�����Ϊ�գ�����ʧ�ܣ�';
							array_push( $resultArr,$tempArr );
							continue;
						}
						//�ʲ����
						if($val['assetTypeName'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '�ʲ����Ϊ�գ�����ʧ�ܣ�';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
							$rs = $directoryDao->find(array('name' => $val['assetTypeName']),null,'id');
							if(!empty($rs)){
								$val['assetTypeId'] = $rs['id'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '�ʲ���𲻴��ڣ�����ʧ�ܣ�';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//��������
						$chargeId = '';//������������id
						if($val['agencyName'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '��������Ϊ�գ�����ʧ�ܣ�';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
							$rs = $agencyDao->find(array('agencyName' => $val['agencyName']),null,'agencyCode,chargeId');
							if(!empty($rs)){
								$val['agencyCode'] = $rs['agencyCode'];
								$chargeId = $rs['chargeId'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '�������򲻴��ڣ�����ʧ�ܣ�';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//��Ӧ��
						if($val['supplierName'] != ''){
							$rs = $supplierDao->find(array('suppName' => $val['supplierName']),null,'id');
							if(!empty($rs)){
								$val['supplierId'] = $rs['id'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '��Ӧ�̲����ڣ�����ʧ�ܣ�';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//������˾
						if($val['companyName'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '������˾Ϊ�գ�����ʧ�ܣ�';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
							$rs = $companyDao->find(array('NameCN' => $val['companyName']),null,'NamePT');
							if(!empty($rs)){
								$val['companyCode'] = $rs['NamePT'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '������˾�����ڣ�����ʧ�ܣ�';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//��������
						if($val['orgName'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '��������Ϊ�գ�����ʧ�ܣ�';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
							$rs = $deptDao->find(array('DEPT_NAME' => $val['orgName'],'DelFlag' =>0),null,'DEPT_ID');
							if(!empty($rs)){
								$val['orgId'] = $rs['DEPT_ID'];
								//������������
								$rs = $this->getParentDept_d($val['orgId']);
								$val['parentOrgId'] = $rs[0]['parentId'];
								$val['parentOrgName'] = $rs[0]['parentName'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '�������Ų����ڣ�����ʧ�ܣ�';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//������
						if($val['belongMan'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '������Ϊ�գ�����ʧ�ܣ�';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
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
									$tempArr['docCode'] = $assetCode;
									$tempArr['result'] = '��������������˾����Ӧ������ʧ�ܣ�';
									array_push( $resultArr,$tempArr );
									continue;
								}
								if(!$deptFlag){
									$tempArr['docCode'] = $assetCode;
									$tempArr['result'] = '���������������Ų���Ӧ������ʧ�ܣ�';
									array_push( $resultArr,$tempArr );
									continue;								
								}
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '�����˲����ڣ�����ʧ�ܣ�';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//ʹ�ò���
						if($val['useOrgName'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = 'ʹ�ò���Ϊ�գ�����ʧ�ܣ�';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
							$rs = $deptDao->find(array('DEPT_NAME' => $val['useOrgName'],'DelFlag' =>0),null,'DEPT_ID');
							if(!empty($rs)){
								$val['useOrgId'] = $rs['DEPT_ID'];
								//ʹ�ö�������
								$rs = $this->getParentDept_d($val['useOrgId']);
								$val['parentUseOrgId'] = $rs[0]['parentId'];
								$val['parentUseOrgName'] = $rs[0]['parentName'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = 'ʹ�ò��Ų����ڣ�����ʧ�ܣ�';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//ʹ����
						if($val['userName'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = 'ʹ����Ϊ�գ�����ʧ�ܣ�';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
							$rs = $userDao->findAll(array('USER_NAME' => $val['userName']),null,'USER_ID,DEPT_ID');
							if(!empty($rs)){
								$deptFlag = false;
								foreach ($rs as $v){
									if($v['DEPT_ID'] == $val['useOrgId']){
										$deptFlag = true;
										$val['userId'] = $v['USER_ID'];
										//���ʹ�����������������˲�ͬ�����ʲ���ʹ��״̬��Ϊʹ����
										if($val['useStatusName'] == "����" && ($val['userId'] != $chargeId)){
											$val['useStatusName'] = 'ʹ����';
											$val['useStatusCode'] = 'SYZT-SYZ';
										}
										break;
									}
								}
								if(!$deptFlag){
									$tempArr['docCode'] = $assetCode;
									$tempArr['result'] = 'ʹ������ʹ�ò��Ų���Ӧ������ʧ�ܣ�';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = 'ʹ���˲����ڣ�����ʧ�ܣ�';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//��������
						if($val['buyDate'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '��������Ϊ�գ�����ʧ�ܣ�';
							array_push( $resultArr,$tempArr );
							continue;
						}
						//��������
						if($val['wirteDate'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '��������Ϊ�գ�����ʧ�ܣ�';
							array_push( $resultArr,$tempArr );
							continue;
						}
						//�䶯��ʽ
						if($val['changeTypeName'] != ''){
							$rs = $changeDao->find(array('name' => $val['changeTypeName']),null,'code');
							if(!empty($rs)){
								$val['changeTypeCode'] = $rs['code'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '�䶯��ʽ�����ڣ�����ʧ�ܣ�';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '�䶯��ʽΪ�գ�����ʧ�ܣ�';
							array_push( $resultArr,$tempArr );
							continue;
						}
						//�۾ɷ�ʽ
						if($val['deprName'] != ''){
							$rs = $deprDao->find(array('name' => $val['deprName']),null,'code');
							if(!empty($rs)){
								$val['deprCode'] = $rs['code'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '�۾ɷ�ʽ�����ڣ�����ʧ�ܣ�';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						array_push($inArr, $val);
					}

					if( count($resultArr)>0 ){
						$title = '�ʲ���Ƭ��Ϣ������';
						$thead = array( '�ʲ����','���' );
						echo "<script>alert('����ʧ��')</script>";
						echo util_excelUtil::showResult($resultArr,$title,$thead);
					}else{
						foreach ( $inArr as $key => $val ){
							//��ӿ�Ƭ��¼��Ϣ
							$assetTempDao = new model_asset_assetcard_assetTemp();
							$tempId = $assetTempDao->addByCardImport_d($val);
							$val['templeId'] = $tempId;
							$val['version'] = 1;
							parent::add_d($val,true);
						}
						echo "<script>alert('����ɹ�');self.parent.tb_remove();if(self.parent.show_page)self.parent.show_page(1);</script>";
					}
				} else {
					msg( "�ļ������ڿ�ʶ������!");
				}
			} else {
				msg( "�ϴ��ļ����Ͳ���EXCEL!");
			}
	 		$this->commit_d();
	 	}catch(Exception $e){
	 		$this->rollBack();
	 	}
	}

	/**
	 * ��Ƭ��Ϣ����
	 */
	 function oldImport_d($objKeyArr){
	 	try{
	 		$this->start_d();
	 		$returnFlag = true;
			$service = $this->service;
			$filename = $_FILES ["inputExcel"] ["name"];
			$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
			$fileType = $_FILES ["inputExcel"] ["type"];
			$resultArr = array();
			$objectArr = array();
			$excelData = array ();
			//�жϵ��������Ƿ�Ϊexcel��
			if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
				$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
				spl_autoload_register("__autoload");
				unset($excelData[0]);
				//�ж��Ƿ��������Ч����
				if ($excelData) {
					$branchDao = new model_deptuser_branch_branch();
					$deprDao = new model_asset_basic_deprMethod();
					$directoryDao = new model_asset_basic_directory();
					$changeDao = new model_asset_basic_change();
					$agencyDao = new model_asset_basic_agency();
					$dataDao = new model_system_datadict_datadict();
					$userDao = new model_deptuser_user_user();
					$deptDao = new model_deptuser_dept_dept();
//					$projectDao = new model_engineering_project_esmproject();
//					$projectDao->searchArr['ExaStatus'] = '���';
					$dataArr = $dataDao->getDatadictsByParentCodes('SYZT');
					$branchArr = $branchDao->list_d();
					$direArr = $directoryDao->list_d();
					$agencyArr = $agencyDao->list_d();
					$changeArr = $changeDao->list_d();
					$deprArr = $deprDao->list_d();
					$deptArr = $deptDao->list_d();
					$userArr = $userDao->list_d();
					$cardArr = $this->list_d();
//					$projArr = $projectDao->list_d();
//					echo "<pre>";
//					print_R($projArr);
					foreach ($excelData as $key=>$val){
						//��ʽ�����룬ɾ������Ŀո��������Ϊ�գ���������ݲ�����Ч��
						foreach( $val as $index => $value ){
							$excelData[$key][$index] = trim($value);
						}
						$excelData[$key][0] = str_replace( ' ','',$val[0]);
						$excelData[$key][2] = trim($val[2]);
						if( $excelData[$key][0] == '' ){
							unset( $excelData[$key] );
						}
					}
					foreach ( $excelData as $rNum => $row ) {
						foreach ( $objKeyArr as $index => $fieldName ) {
							//��ֵ������Ӧ���ֶ�
							$objectArr [$rNum] [$fieldName] = $row [$index];
						}
					}
					$rows = $this->list_d();
					$repeatCodeArr = array();
					foreach( $objectArr as $key=>$val ){
						$repeatCodeArr[$key]['docCode'] = $val['assetCode'];
						$repeatCodeArr[$key]['result'] = '';
						if($val['assetCode'] == ''){
							$repeatCodeArr[$key]['result'] .= '�ʲ�����Ϊ�գ�����ʧ�ܣ�';
						}else{
							foreach( $cardArr as $cardKey => $cardVal ){
								if( $val['assetCode'] == $cardVal['assetCode'] ){
									$repeatCodeArr[$key]['result'] .= '���ʲ������Ѵ��ڣ�����ʧ�ܣ�';
									break;
								}
							}
						}
						if($val['assetName'] == ''){
							$repeatCodeArr[$key]['result'] .= '�ʲ�����Ϊ�գ�����ʧ�ܣ�';
						}
						if($val['assetTypeName']==''){
							$repeatCodeArr[$key]['result'] .= '�ʲ����Ϊ�գ�����ʧ�ܣ�';
						}else{
							$isAssetType = false;
							foreach ( $direArr as $direKey=>$direVal ){
								if($val['assetTypeName'] == $direVal['name']){
									$objectArr[$key]['assetTypeId'] = $direVal['id'];
									$isAssetType = true;
								}
							}
							if(!$isAssetType){
								$repeatCodeArr[$key]['result'] .= '�ʲ����Ƿ������ʼ�����ٵ��룡';
							}
						}
						$objectArr[$key]['isDeprf']='1';
						$objectArr[$key]['useStatusName']='����';
						$isUseStatus = false;
						foreach ( $dataArr['SYZT'] as $dataKey=>$dataVal ){
							if($objectArr[$key]['useStatusName'] == $dataVal['dataName']){
								$objectArr[$key]['useStatusCode'] = $dataVal['dataCode'];
								$isUseStatus = true;
							}
						}
						if(!$isUseStatus){
							$repeatCodeArr[$key]['result'] .= 'ʹ��״̬�Ƿ������ʼ�����ٵ��룡';
						}
						$objectArr[$key]['changeTypeName']='����';
//						$isChangeType = false;
//						foreach ( $changeArr as $changeKey=>$changeVal ){
//							if($objectArr[$key]['changeTypeName'] == $changeVal['name']){
//								$objectArr[$key]['changeTypeCode'] = $changeVal['code'];
//								$isChangeType = true;
//							}
//						}
//						if(!$isChangeType){
//							$repeatCodeArr[$key]['result'] .= '�䶯��ʽ�Ƿ������ʼ�����ٵ��룡';
//						}
						$objectArr[$key]['deprName']='ƽ�����޷�';
						$isdeprType = false;
						foreach ( $deprArr as $deprKey=>$deprVal ){
							if($objectArr[$key]['deprName'] == $deprVal['name']){
								$objectArr[$key]['deprCode'] = $deprVal['code'];
								$isdeprType = true;
							}
						}
						if(!$isdeprType){
							$repeatCodeArr[$key]['result'] .= '�۾ɷ�ʽ�Ƿ���';
						}
						if($val['buyDate']==''){
							$repeatCodeArr[$key]['result'] .= '��������Ϊ�գ�����ʧ�ܣ�';
						}else{
							$buyDate = mktime(0, 0, 0, 1, $objectArr[$key]['buyDate'] - 1, 1900);
							$objectArr[$key]['buyDate'] = date("Y-m-d", $buyDate);
							$objectArr[$key]['wirteDate'] = $objectArr[$key]['buyDate'];
						}
						if($val['orgName']!=''){
							$isDept = false;
							foreach ( $deptArr as $deptKey=>$deptVal ){
								if($val['orgName'] == $deptVal['name']){
									$objectArr[$key]['orgId'] = $deptVal['id'];
									$isDept = true;
								}
							}
							if(!$isDept){
								$repeatCodeArr[$key]['result'] .= '�������Ų����ڣ�';
							}
						}else{
							$repeatCodeArr[$key]['result'] .= '��������Ϊ�գ�����ʧ�ܣ�';
						}

						if($val['agencyName']==''){
							$repeatCodeArr[$key]['result'] .= '��������Ϊ�գ�����ʧ�ܣ�';
						}else{
							$isAgency = false;
							foreach ( $agencyArr as $agencyKey=>$agencyVal ){
								if(strstr($agencyVal['agencyName'],$val['agencyName'])){
									$objectArr[$key]['agencyCode'] = $agencyVal['agencyCode'];
									$isAgency = true;
								}
							}
							if(!$isAgency){
								$repeatCodeArr[$key]['result'] .= '��������Ƿ������ʼ�����ٵ��룡';
							}
						}
						if($val['origina']==''){
							$objectArr[$key]['origina'] = 0;
						}
						if($val['buyDepr']==''){
							$objectArr[$key]['buyDepr'] = 0;
						}
						if($val['beginTime']==''){
							$objectArr[$key]['beginTime'] = $objectArr[$key]['buyDate'];
						}
						if($val['estimateDay']==''){
							$objectArr[$key]['estimateDay'] = 0;
						}
						if($val['alreadyDay']==''){
							$objectArr[$key]['alreadyDay'] = 0;
						}
						if($val['depreciation']==''){
							$objectArr[$key]['depreciation'] = 0;
						}
						if($val['salvage']==''){
							$objectArr[$key]['salvage'] = 0;
						}
						if($val['netValue']==''){
							$objectArr[$key]['netValue'] = 0;
						}
//						if( $val['useOrgName']!='' ){
//							$isDept = false;
//							foreach ( $deptArr as $deptKey=>$deptVal ){
//								if($val['useOrgName'] == $deptVal['name']){
//									$objectArr[$key]['useOrgId'] = $deptVal['id'];
//									$isDept = true;
//								}
//							}
//							if(!$isDept){
//								$repeatCodeArr[$key]['result'] .= 'ʹ�ò��Ų����ڣ�';
//							}
//						}
						if( $val['belongMan']!='' ){
							$isUser = false;
							foreach ( $userArr as $userKey=>$userVal ){
								if($val['belongMan'] == $userVal['USER_NAME'] && $userVal['DEPT_ID']==$objectArr[$key]['orgId']){
									$objectArr[$key]['belongManId'] = $userVal['USER_ID'];
									$objectArr[$key]['companyCode'] = $userVal['Company'];
									foreach( $branchArr as $branchKey=>$branchval ){
										if( $branchval['NamePT'] == $userVal['Company'] ){
											$objectArr[$key]['companyName'] = $branchval['NameCN'];
										}
									}
									$isUser = true;
								}
							}
							if(!$isUser){
								$repeatCodeArr[$key]['result'] .= '�����˲�����,�����������������Ų���Ӧ��';
							}
						}
					}
					foreach( $repeatCodeArr as $key=>$val ){
						if($val['result']==''){
							unset($repeatCodeArr[$key]);
						}
					}
					if( count($repeatCodeArr)>0 ){
				 		$returnFlag = false;
						$title = '�ʲ���Ƭ��Ϣ������';
						$thead = array( '�ʲ�����','���' );
						echo "<script>alert('����ʧ��')</script>";
						echo util_excelUtil::showResult($repeatCodeArr,$title,$thead);
					}else{
						foreach ( $objectArr as $key => $val ){
							$val['version']=1;
							$this->importAdd_d($val,true);
						}
						echo "<script>alert('����ɹ�');self.parent.tb_remove();if(self.parent.show_page)self.parent.show_page(1);</script>";
					}
				} else {
					msg( "�ļ������ڿ�ʶ������!");
				}
			} else {
				msg( "�ϴ��ļ����Ͳ���EXCEL!");
			}
	 		$this->commit_d();
	 		return $returnFlag;
	 	}catch(Exception $e){
	 		$this->rollBack();
	 		return 0;
	 	}
	}
	
	/**
	 * ��⿨Ƭ�Ƿ񱻽���/���õ��ݹ���
	 */
	function checkAsset_d($code){
		$sql = "SELECT count(*) as number from (
			SELECT bi.id,bi.borrowId AS mainId,bi.assetCode,bi.isReturn from oa_asset_borrowitem bi WHERE bi.isReturn=0
			UNION ALL SELECT ci.id,ci.allocateId AS mainId,ci.assetCode,ci.isReturn FROM oa_asset_chargeitem ci WHERE ci.isReturn=0
			)c WHERE c.assetCode='".$code."'";
		$number = $this->_db->getArray( $sql );
	 	return $number[0]['number'];
	}

    /**
     * �ʼ�����
     * 2012��12��26�� 17:07:59
     * zengzx
     */
    function toMail_d($emailArr,$object){
        $addMsg = '������Ա��ȷ���ˣ��ʲ����ƣ�'.$object['assetName'].')�Ŀ�Ƭ��¼�������������ʲ���Ƭ����ȷ�ϡ�';
        $emailDao = new model_common_mail();
        $emailInfo = $emailDao->mailClear('��Ƭȷ��',$emailArr,$addMsg);
    }

    function addBeachByProperty_d($id){
		$tempDao = new model_asset_assetcard_assetTemp();
		$tempInfo = $tempDao->getAssetInfo_d($id);
		$row['id']=$id;
		$tempInfo['templeId']=$id;
		//Ĭ��ʹ��״̬Ϊ����
		$tempInfo['useStatusCode']='SYZT-XZ';
		$tempInfo['useStatusName']='����';
		$tempInfo['version']=1;

		//��ȡ��������������Ϣ
		$agencyDao = new model_asset_basic_agency();
		$rs = $agencyDao->find(array('agencyCode' => $tempInfo['agencyCode']),null,'chargeId');
		//����������������ʹ���˲�ͬʱʹ��״̬��Ϊʹ����
		if($rs['chargeId'] != $tempInfo['userId']){
			$tempInfo['useStatusCode']='SYZT-SYZ';
			$tempInfo['useStatusName']='ʹ����';
		}
		$id = $this->addBeach_d($tempInfo,true);
		if($row['id']){
		 	$statusInfo = array(
		 		'id' => $row['id'],
		 		'isCreate' => 1
		 	);
		 	$tempDao->updateById( $statusInfo );
		}
    }
    
    /**
     * �ʲ���Ƭ����������
     */
    function updateBelongMan_d() {
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream"|| $fileType == "application/kset") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			//�ж��Ƿ��������Ч����
			if (is_array($excelData)) {
				$userDao = new model_deptuser_user_user();
				$deptDao = new model_deptuser_dept_dept();
				$userArr = $userDao->list_d();//������
				$deptArr = $deptDao->list_d();//��������
				$sql = "select NameCN,NamePT from branch_info";
				$compArr = $this->listBySql($sql);//������˾
				//��ʽ�����룬ɾ������Ŀո�
				foreach ($excelData as $key=>$val){
					foreach( $val as $index => $value ){
						$excelData[$key][$index] = trim($value);
					}
				}
				//������ѭ��
				foreach( $excelData as $key=>$val ){
					$actNum = $key + 2;
					$inArr = array();
					//��Ƭ���
					if($val[0] != ''){
						$inArr['assetCode'] = $val[0];
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����д��Ƭ���';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//������˾
					if($val[1]!=''){
						$inArr['companyName'] = $val[1];
						$isComp = false;
						foreach ($compArr as $compKey => $compVal){
							if($val[1] == trim($compVal['NameCN'])){
								$inArr['companyCode'] = trim($compVal['NamePT']);
								$isComp = true;
							}
						}
						if(!$isComp){
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!��������˾������';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}
					//��������
					if($val[2]!=''){
						$inArr['orgName'] = $val[2];
						$isDept = false;
						foreach ($deptArr as $deptKey => $deptVal){
							if($val[2] == trim($deptVal['DEPT_NAME'])){
								$inArr['orgId'] = trim($deptVal['id']);
								//������������
								$rs = $this->getParentDept_d($inArr['orgId']);
								$inArr['parentOrgId'] = $rs[0]['parentId'];
								$inArr['parentOrgName'] = $rs[0]['parentName'];
								$isDept = true;
							}
						}
						if(!$isDept){
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!���������Ų�����';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}
					//������
					if($val[3]!=''){
						$inArr['belongMan'] = $val[3];
						$isUser = false;
						$isInDept = true;
						$isInComp = true;
						if(isset($inArr['orgId'])){
							$isInDept = false;
						}elseif (isset($inArr['companyCode'])){
							$isInComp = false;
						}
						foreach ($userArr as $userKey=>$userVal){
							if($val[3] == trim($userVal['USER_NAME'])){
								$inArr['belongManId'] = $userVal['USER_ID'];
								if(trim($userVal['DEPT_ID']) == $inArr['orgId']){
									$isInDept = true;
								}else{
									$inArr['orgId'] = trim($userVal['DEPT_ID']);
									foreach ($deptArr as $deptKey => $deptVal){
										if($inArr['orgId'] == trim($deptVal['id'])){
											$inArr['orgName'] = trim($deptVal['DEPT_NAME']);
										}
									}
								}
								if(trim($userVal['Company']) == $inArr['companyCode']){
									$isInComp = true;
								}else{
									$inArr['companyCode'] = trim($userVal['Company']);
									foreach ($compArr as $compKey => $compVal){
										if($inArr['companyCode'] == trim($compVal['NamePT'])){
											$inArr['companyName'] = trim($compVal['NameCN']);
										}
									}
								}
								$isUser = true;
							}
						}
						if(!$isUser){
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!�������˲�����';
							array_push( $resultArr,$tempArr );
							continue;
						}
						if(!$isInDept){
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!�����������������Ų�ƥ��';
							array_push( $resultArr,$tempArr );
							continue;
						}
						if(!$isInComp){
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!����������������˾��ƥ��';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û����д������';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//���뿪ʼִ��
					try{
						$this->start_d();
						//���¿�Ƭ��������Ϣ
						$id = $this->update(array("assetCode" => $inArr['assetCode']),$inArr);
						if($id){
							$tempArr['result'] = '���³ɹ�';
						}
							
						$this->commit_d();
					}catch(Exception $e){
						$this->rollBack();
						$tempArr['result'] = '����ʧ��';
					}
					$tempArr['docCode'] = '��' . $actNum .'������';
					array_push( $resultArr,$tempArr );
				}
				return $resultArr;
			}else {
				msg( "�ļ������ڿ�ʶ������!");
			}
		}else {
			msg( "�ϴ��ļ����Ͳ���EXCEL!");
		}
    }
    
    /**
     * ����ȷ�ϱ������룬�����ʲ���Ƭ��ֵ����ֵ��Ϣ
     */
    function updateScrapcard($cardInfo){
    	$condition = array('id' => $cardInfo['assetId']);
    	$row = array('salvage' => $cardInfo['salvage'],'netValue' => $cardInfo['netValue']);
    	return $this->update($condition,$row);
    }
    
    /**
     * ��ȡ��Ҫ�������µĿ�Ƭ��Ϣ
     */
    function getUpdateData_d($obj){
    	$rowLength = 6;
    	$rowWidth = 100/$rowLength;
    	$rs = $this->getData_d($obj);
    	if($rs){
    		$rsLength = count($rs);
    		$str = '<table class="main_table" style="width:100%;" ><td colspan="99" class="form_header"><input type="checkbox" id="checkboxAll" onclick="checkAll();"/>'.
    		' ����<span id="allNum">'.$rsLength.'</span>����¼,ѡ��<span id="num">0</span>����'.
    		'</td>';
    		$i = 0;
    		foreach($rs as $v){
    			if($i == 0) $str .= "<tr>";
    			$str .= '<td style="width:'.$rowWidth.'%;"><input type="checkbox" id="check-'.$v['id'].'" name="assetcard[idArr][]" value="'.$v['id'].'" onclick="checkThis(\''.$v['id'].'\');"/>'.
    					'<a href="javascript:void(0);" onclick="viewForm(\''.$v['id'].'\');">' .$v['assetCode'].'</a></td>';
    			$i++;
    			if($i == $rowLength){
    				$str .= '</tr>';
    				$i = 0;
    			}
    		}
    		$leastNum = $rowLength - $i;
    		if($i != $rowLength) $str .= '<td colspan="'. $leastNum .'"></td></tr>';
    		$str .= '</table>';
    		return $str;
    	}else{
    		return '<table class="form_table" style="width:100%;"><tr><td colspan="5"> - û���ҵ���صĿ�Ƭ��Ϣ - </td></tr></table>';
    	}
    }
    
    /**
     * ��ȡ����
     */
    function getData_d($obj){
    	$condition='';
    	$userId = $obj['userId'];
    	$belongManId = $obj['belongManId'];
    	$agencyCode = $obj['agencyCode'];
    	
    	if($userId) $condition.=" and userId = '".$userId."'";
    	if($belongManId) $condition.=" and belongManId = '".$belongManId."'";
    	if($agencyCode) $condition.=" and agencyCode = '".$agencyCode."'";
    	
		$sql = "SELECT id,assetCode FROM oa_asset_card WHERE isDel=0 and isTemp = 0".$condition;
    	return $this->_db->getArray($sql);
    }
    
    /**
     * ���¿�Ƭ��Ϣ����
     */
    function updateCard_d($object){ 		
    	$updateSql = "UPDATE ". $this->tbl_name . " SET ";
    	$updateArr = array();
    		
    	//ת��ʹ������Ϣ
    	if(!empty($object['inUserId']))array_push($updateArr, "userId = '" . $object['inUserId'] . "'");
    	if(!empty($object['inUser']))array_push($updateArr, "userName = '" . $object['inUser'] . "'");
    	if(!empty($object['inUseOrgId'])){
    		$useOrgId = $object['inUseOrgId'];
    		//ʹ�ö�������
    		$rs = $this->getParentDept_d($useOrgId);
    		$parentUseOrgId = $rs[0]['parentId'];
    		$parentUseOrgName = $rs[0]['parentName'];
    		array_push($updateArr, "useOrgId = '" . $useOrgId . "'");
    		array_push($updateArr, "useOrgName = '" . $object['inUseOrg'] . "'");
    		array_push($updateArr, "parentUseOrgId = '" . $parentUseOrgId . "'");
    		array_push($updateArr, "parentUseOrgName = '" . $parentUseOrgName . "'");
    	}
    	//ת����������Ϣ
    	if(!empty($object['inBelongManId']))array_push($updateArr, "belongManId = '" . $object['inBelongManId'] . "'");
    	if(!empty($object['inBelongMan']))array_push($updateArr, "belongMan = '" . $object['inBelongMan'] . "'");
    	if(!empty($object['inOrgId'])){
    		$orgId = $object['inOrgId'];
    		//������������
    		$rs = $this->getParentDept_d($orgId);
    		$parentOrgId = $rs[0]['parentId'];
    		$parentOrgName = $rs[0]['parentName'];
    		array_push($updateArr, "orgId = '" . $orgId . "'");
    		array_push($updateArr, "orgName = '" . $object['inOrg'] . "'");
    		array_push($updateArr, "parentOrgId = '" . $parentOrgId . "'");
    		array_push($updateArr, "parentOrgName = '" . $parentOrgName . "'");
    	}
    	//����������Ϣ
    	if(!empty($object['inAgencyCode']))array_push($updateArr, "agencyCode = '" . $object['inAgencyCode'] . "'");
    	if(!empty($object['inAgencyName']))array_push($updateArr, "agencyName = '" . $object['inAgencyName'] . "'");
    	//id��Ϣ
    	$idStr = implode(',',$object['idArr']);
    		
    	//ִ�и���
    	if(!empty($updateArr)){
    		$updateSql .= implode(',',$updateArr) . 'WHERE id IN ('.$idStr.')';
    		return $this->_db->query($updateSql);
    	}else{
    		return false;
    	}
    }
    
    /**
     *��ȡ����Ȩ���ַ���
     */
    function getAgencyStr_d() {
    	$agencyDao = new model_asset_basic_agency();
    
    	//������������Ĭ���в鿴�������¿�Ƭ��Ȩ��
    	$rsArr = $agencyDao->findAll(array('chargeId'=>$_SESSION ['USER_ID']),null,'agencyCode');
    	//��ȡ����Ȩ��
    	$otherDataDao = new model_common_otherdatas();
    	$sysLimit = $otherDataDao->getUserPriv('asset_assetcard_assetcard', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
    	$agency = $sysLimit['����Ȩ��'];
    	//������û��������������˻��������Ȩ��
    	if((!empty($rsArr) || !empty($agency))){
    		//����Ȩ��Ϊȫ��
    		if(strstr($agency,';;') !== false){
    			return ';;';
    		}else{	//�������Ȩ�޲�Ϊȫ����������������
    			$agencyCodeArr = array();
    			if(!empty($agency)){
    				$agencyIdArr = explode(',',$agency);
    				foreach ($agencyIdArr as $key => $val){
    					$rs = $agencyDao->find(array('id'=>$val),null,agencyCode);
    					array_push($agencyCodeArr, "'".$rs['agencyCode']."'");
    				}
    			}
    			if(!empty($rsArr)){
    				foreach ($rsArr as $key => $val){
    					array_push($agencyCodeArr, "'".$val['agencyCode']."'");
    				}
    			}
    			//����ȥ��
    			$agencyCodeArr = array_unique($agencyCodeArr);
    			//���������ַ���
    			$agencyStr = implode(',', $agencyCodeArr);
    			return $agencyStr;
    		}
    	}
    	return null;
    }
    
    /****************************************���¿�Ƭ������Ϣ****************************************/
    /**
     * ͨ������id��ȡ�ϼ�������Ϣ
     */
    function getParentDept_d($deptId){
    	//����ò��Ų������ϼ����ţ����ϼ�������Ϊ�ò��ű���
    	$sql = "
	    	SELECT
	    	IF (
		    	d.PARENT_ID = 0,
		    	d.DEPT_ID,
		    	d1.DEPT_ID
	    	) AS parentId,
	    	IF (
		    	d.PARENT_ID = 0,
		    	d.DEPT_NAME,
		    	d1.DEPT_NAME
	    	) AS parentName
	    	FROM
	    		department d
	    	LEFT JOIN department d1 ON d.PARENT_ID = d1.DEPT_ID
	    	WHERE
	    		d.DEPT_ID = $deptId";
    	return $this->findSql($sql);
    }

    /**
     * ͨ����Աid��ȡ������Ϣ
     */
    function getDeptInfo_d($userId){
    	$sql = "
				SELECT
    				u.USER_NAME AS userName,
					u.DEPT_ID AS deptId,
					d.DEPT_NAME AS deptName,
				IF (
					d.PARENT_ID = 0,
					d.DEPT_ID,
					d1.DEPT_ID
				) AS parentId,				
				IF (
					d.PARENT_ID = 0,
					d.DEPT_NAME,
					d1.DEPT_NAME
				) AS parentName
				FROM
					USER u
				LEFT JOIN department d ON u.DEPT_ID = d.DEPT_ID
				LEFT JOIN department d1 ON d.PARENT_ID = d1.DEPT_ID
				WHERE
					u.USER_ID = '".$userId."'";
    	return $this->findSql($sql);
    }
    
    /**
     * ��Ա�䶯ʱ�����¿�Ƭʹ���˻������˵Ĳ�����Ϣ
     */
    function updateDeptInfo($userId){
    	//��ȡ������Ϣ
    	$deptInfo = $this->getDeptInfo_d($userId);

    	$deptId = $deptInfo[0]['deptId']; //����id
    	$deptName = $deptInfo[0]['deptName']; //��������
    	$parentDeptId = $deptInfo[0]['parentId']; //��������id
    	$parentDeptName = $deptInfo[0]['parentName']; //������������
    	
    	try {
    		$this->start_d();
    		 
    		//�����ʲ���Ƭʹ�ò�����Ϣ
    		$this->update(array('userId' => $userId), array(
    				'useOrgId' => $deptId,'useOrgName' => $deptName,
    				'parentUseOrgId' => $parentDeptId,'parentUseOrgName' => $parentDeptName));
    		//�����ʲ���Ƭ����������Ϣ
    		$this->update(array('belongManId' => $userId), array(
    				'orgId' => $deptId,'orgName' => $deptName,
    				'parentOrgId' => $parentDeptId,'parentOrgName' => $parentDeptName));
    		 
    		$this->commit_d();
    		return true;
    	}catch ( Exception $e ) {
    		$this->rollBack();
    		return false;
    	}
    }
    
    /**
     * �����豸���á�ת��ʱ�����¿�Ƭʹ���˼�ʹ�ò�����Ϣ��������Ƭ״̬��Ϊʹ����
     */
    function updateByEsmDevice($userId,$ids){
    	//��ȡ������Ϣ
    	$deptInfo = $this->getDeptInfo_d($userId);

    	$userName = $deptInfo[0]['userName']; //��Ա����
    	$deptId = $deptInfo[0]['deptId']; //����id
    	$deptName = $deptInfo[0]['deptName']; //��������
    	$parentDeptId = $deptInfo[0]['parentId']; //��������id
    	$parentDeptName = $deptInfo[0]['parentName']; //������������
    	
    	$sql = " UPDATE $this->tbl_name SET userId = '".$userId."' ,userName = '".$userName."' ,useOrgId = '".$deptId."' ,
				 	useOrgName = '".$deptName."' ,parentUseOrgId = '".$parentDeptId."' ,parentUseOrgName = '".$parentDeptName."' ,
				 	useStatusCode = 'SYZT-SYZ' ,useStatusName = 'ʹ����'
				 WHERE id IN ($ids)";
    	$this->_db->query($sql);
    }
    
    /**
     * �����豸�黹ʱ����ʹ���˼�ʹ�ò����޸�Ϊ��������������Ϣ��������Ƭ״̬��Ϊ����
     */
    function emptyByEsmDevice($ids){
    	$idArr = explode(',', $ids);
    	$agencyDao = new model_asset_basic_agency();//��������
    	foreach ($idArr as $id){
    		$agencyCode = $this->get_table_fields($this->tbl_name, 'id='.$id, 'agencyCode');
    		$rs = $agencyDao->find(array('agencyCode' => $agencyCode),null,'chargeId');//��ȡ��������������Ϣ
    		$userId = $rs['chargeId'];
    		//��ȡ������Ϣ
    		$deptInfo = $this->getDeptInfo_d($userId);
    		
    		$userName = $deptInfo[0]['userName']; //��Ա����
    		$deptId = $deptInfo[0]['deptId']; //����id
    		$deptName = $deptInfo[0]['deptName']; //��������
    		$parentDeptId = $deptInfo[0]['parentId']; //��������id
    		$parentDeptName = $deptInfo[0]['parentName']; //������������
    		
	    	$sql = " UPDATE $this->tbl_name SET userId = '".$userId."' ,userName = '".$userName."' ,useOrgId = '".$deptId."' ,
					 	useOrgName = '".$deptName."' ,parentUseOrgId = '".$parentDeptId."' ,parentUseOrgName = '".$parentDeptName."' ,
					 	useStatusCode = 'SYZT-XZ' ,useStatusName = '����'
					 WHERE id =".$id;
    		$this->_db->query($sql);
    	} 	 
    }
    
    /****************************************�����ֵ����Ʒҵ�񲿷�****************************************/
    /**
     * ��ȡ��ֵ����Ʒ
     */
    function searchCleanLowValueGoods_d($object) {
    	$assetCode = isset($object['assetCode']) ? $object['assetCode'] : "";  //�ʲ����
    	$condition = '';
    	if($assetCode){//����д���ʲ���ţ��򲻴���������������
    		$condition .= " and c.assetCode = '".$assetCode."'";
    	}else{
    		$assetName = isset($object['assetName']) ? $object['assetName'] : "";  //�ʲ�����
    		$year = isset($object['year']) ? $object['year'] : "";  //ʹ������(��)
    		$month = isset($object['month']) ? $object['month'] : "";  //ʹ������(��)
    		$day = isset($object['day']) ? $object['day'] : "";  //ʹ������(��)
    		if($assetName){
    			$condition .= " and c.assetName = '".util_jsonUtil::iconvUTF2GB($assetName)."'";
    		}
    		if ($year || $month || $day) {//ʹ������
    			$countDays = 0;
    			if($year){
    				$countDays += bcmul($year,365);
    			}
    			if($month){
    				$countDays += bcmul($month,30);
    			}
    			if($day){
    				$countDays += $day;
    			}
    			if($countDays != 0){
    				$condition .= " and DATEDIFF(CURDATE(),c.".$object['dateType'].") >= ".$countDays;
    			}
    		}
    	}
    	$sql = "
			SELECT
				c.id,c.assetCode,c.assetName,c.buyDate,c.wirteDate,FORMAT(c.salvage,2) as salvage,c.agencyName,c.useOrgName,c.userName,c.orgName,c.belongMan,c.remark
			FROM
				" . $this->tbl_name . " c
			WHERE
				c.isDel = 0 AND	c.isTemp = 0 AND c.useStatusCode = 'SYZT-XZ' AND c.property = 1" .$condition."
    		ORDER BY 
				c.assetName,c.".$object['dateType'];

    	return $this->_db->getArray($sql);
    }
    
    /**
     * ���html
     */
    function serachHtml_d($rows) {
    	if ($rows) {
    		$html = "<table class='main_table'><thead><tr class='main_tr_header'><th><input type='checkbox' id='checkAll' onclick='checkAll();' /></td><th>���</th>" .
    				"<th>�ʲ����</th><th>�ʲ�����</th><th>��������</th><th>��������</th><th>��ֵ</th><th>��������</th><th>��������</th><th>������</th><th>��ע</th></tr></thead><tbody>";
    		$i = 0;
    		foreach ($rows as $v) {
    			$i++;
    			$html .= "<tr class='tr_even'>";
    			$html .= "<td><input type='checkbox' value='$v[id]' /></td><td>$i</td><td><a href='javascript:void(0);' onclick='searchDetail(\"$v[id]\")'>$v[assetCode]</a></td>" .
    			"<td>$v[assetName]</td><td>$v[buyDate]</td><td>$v[wirteDate]</td><td>$v[salvage]</td><td>$v[agencyName]</td><td>$v[orgName]</td>" .
    			"<td>$v[belongMan]</td><td>$v[remark]</td></tr>";
    		}
    		return $html . '</tbody></table>';
    	} else {
    		return 'û�в�ѯ������';
    	}
    }
    
    /**
     * ���������ֵ����Ʒ
     */
    function cleanLowValueGoods_d($ids) {
    	$idStr = util_jsonUtil::strBuild($ids);
    	$updateSql = "UPDATE " . $this->tbl_name . " SET useStatusCode = 'SYZT-YQL',useStatusName = '������',isDel = 1 WHERE id IN(" . $idStr . ")";
    	return $this->query($updateSql);
    }
    /****************************************���¿�Ƭ״̬****************************************/
    /**
     * ����Id�޸��ʲ�����״̬
     * @linzx
     */
    function setCleanStatus($id){
    	$rows = array(
    			'id'=>$id,
    			'isDel'=>'1',
    			'useStatusCode'=>'SYZT-YQL',
    			'useStatusName'=>'������'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * �����ʲ�ID�޸��ʲ�����״̬
     * @linzx
     */
    function setIsSell($id){
    	$rows = array(
    			'id'=>$id,
    			'isSell'=>'1',
    			'useStatusCode'=>'SYZT-WCS',
    			'useStatusName'=>'δ����'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * �����ʲ�ID�޸��ʲ���ƬΪ����״̬
     * @linzx
     */
    function setNoScrap($id){
    	$rows = array(
    			'id'=>$id,
    			'isScrap'=>'0',
    			'useStatusCode'=>'SYZT-XZ',
    			'useStatusName'=>'����'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * �����ʲ�ID�޸��ʲ���ƬΪ������״̬
     * @linzx
     */
    function setToScrap($id){
    	$rows = array(
    			'id'=>$id,
    			'isScrap'=>'1',
    			'useStatusCode'=>'SYZT-DBF',
    			'useStatusName'=>'������'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * �����ʲ�ID�޸��ʲ���ƬΪ�ѱ���״̬
     */
    function setIsScrap($id){
    	$rows = array(
    			'id'=>$id,
    			'isScrap'=>'1',
    			'useStatusCode'=>'SYZT-YBF',
    			'useStatusName'=>'�ѱ���'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * �����ʲ�ID�޸��ʲ��۾���״̬
     * @fengxw
     */
    function setIsDepr($id){
    	$rows = array(
    			'id'=>$id,
    			'isDeprf'=>'1'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * �����ʲ�ID�޸��ʲ���ƬΪ������״̬
     */
    function setToStock($id){
    	$rows = array(
    			'id'=>$id,
    			'useStatusCode'=>'SYZT-DTK',
    			'useStatusName'=>'���˿�'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * �����ʲ�ID�޸��ʲ���ƬΪ���˿�״̬
     */
    function setIsStock($id){
    	$rows = array(
    			'id'=>$id,
    			'useStatusCode'=>'SYZT-YTK',
    			'useStatusName'=>'���˿�'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * ���¿�Ƭ�ġ��Ƿ���С�״̬
     */
    function setIdleStatus($idArr,$value){
    	$ids = implode(',',$idArr);
    	$sql = "update oa_asset_card set idle=".$value." where id in(".$ids.")";
    	$this->_db->query($sql);
    }
    
    /**
     * �黹�ʲ�ʱ,��֤��Ƭ�Ƿ����ύ���黹
     */
    function isReturning_d($assetId,$userId){
		$sql = "SELECT r.id FROM oa_asset_return r LEFT JOIN oa_asset_returnitem ri
				ON (r.id = ri.allocateID) WHERE r.isSign = 0 AND r.returnManId = '".$userId."' AND ri.assetId=".$assetId;
		$rs = $this->_db->getArray( $sql );
    	if(empty($rs)){//�����ڹ黹��¼
    		return false;
    	}else{
    		return true;
    	}
    }
 }