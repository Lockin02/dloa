<?php
/**
 * @author show
 * @Date 2013��12��27�� 11:18:01
 * @version 1.0
 * @description:������ Model��
 */
class model_engineering_officeinfo_manager extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_office_managerinfo";
		$this->sql_map = "engineering/officeinfo/managerSql.php";
		parent :: __construct();
	}

	//�����ֵ��ֶδ���
	public $datadictFieldArr = array('productLine');

	/**
	 * ��д��������
	 */
	function add_d($object){
		//�����ֵ�
		$object = $this->processDatadict($object);
		return parent::add_d($object,true);
	}

	/**
	 * ���������޸Ķ���
	 */
	function edit_d($object) {
		$rangeDao=new model_engineering_officeinfo_range();
		$officeinfoDao = new model_engineering_officeinfo_officeinfo();
		try{
			$this->start_d();
			//�����ֵ�
			$object = $this->processDatadict($object);
			//�Լ��༭
			parent::edit_d($object);
			//����޸���ʡ�ݺ͹�����˾,��ô����Ҫ���¾ɵ���Ϣ
			if($object['provinceId'] != $object['oldProvinceId'] || $object['businessBelong'] != $object['oldBusinessBelong']){
				$rangeDao->updateEsmManagerPro_d($object['oldProvinceId'],$object['oldBusinessBelong'],$object['oldProductLine']);

				//��ѯ��ɾ�����б�ɾ��ʡ�ݵİ��´�
				$rangeDao->update(array('proId' => $object['oldProvinceId'],'businessBelong' => $object['oldBusinessBelong']),array('managerId' => '','managerName' => ''));
				$officeArr = $officeinfoDao->getNeedUpdateManager_d($object['oldProvinceId'],$object['oldBusinessBelong'],$object['oldProductLine']);
				if($officeArr){
					foreach($officeArr as $val){
						//��ȡ���´�����ʡ�ݸ�����
						$newManagerArr = $rangeDao->getManager_d($val['id']);
						$officeinfoDao->update(
							array('id' => $val['id']),
							array('managerName' => $newManagerArr['managerName'],'managerCode' => $newManagerArr['managerId'])
						);
					}
				}
			}

			//ֱ�Ӹ���ʡ����Ϣ
			$rangeDao->updateEsmManagerPro_d($object['provinceId'],$object['businessBelong'],$object['productLine']);

			//���°��´��ķ�������
			$officeArr = $officeinfoDao->getNeedUpdateManager_d($object['provinceId'],$object['businessBelong'],$object['productLine']);
			if($officeArr){
				foreach($officeArr as $val){
					//��ȡ���´�����ʡ�ݸ�����
					$newManagerArr = $rangeDao->getManager_d($val['id']);
					$officeinfoDao->update(
						array('id' => $val['id']),
						array('managerName' => $newManagerArr['managerName'],'managerCode' => $newManagerArr['managerId'])
					);
				}
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ����ɾ������
	 */
	function deletes_d($ids) {
		try {
			$this->start_d();

			//���Ȼ�ȡҪɾ��������
			$this->searchArr['ids'] = $ids;
			$rows = $this->list_d();

			//ɾ������
			$this->deletes ( $ids );

			//����ɾ�������ݸ��°��´���Ϣ
			$rangeDao=new model_engineering_officeinfo_range();
			$officeinfoDao = new model_engineering_officeinfo_officeinfo();
			foreach($rows as $v){
				//ɾ����ط�Χ��Ϣ
				$rangeDao->update(array('proId' => $v['provinceId'],'businessBelong' => $v['businessBelong']),array('managerId' => '','managerName' => ''));

				//��ѯ��ɾ�����б�ɾ��ʡ�ݵİ��´�
				$officeArr = $officeinfoDao->getNeedUpdateManager_d($v['provinceId'],$v['businessBelong'],$v['productLine']);
				if($officeArr){
					foreach($officeArr as $val){
						//��ȡ���´�����ʡ�ݸ�����
						$newManagerArr = $rangeDao->getManager_d($val['id']);
						$officeinfoDao->update(
							array('id' => $val['id']),
							array('managerName' => $newManagerArr['managerName'],'managerCode' => $newManagerArr['managerId'])
						);
					}
				}
			}

			$this->commit_d();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ʡ��Ȩ�޻�ȡ -- �ɵĲ��֣��Ѿ���������
	 */
    function getProvinces_d($userId = null,$businessBelong = null){
		$userId = empty($userId) ? $_SESSION['USER_ID'] : $userId;
		$businessBelong = empty($businessBelong) ? $_SESSION['USER_COM'] : $businessBelong;
		$this->searchArr = array('findManagerId' => $userId,'businessBelong' => $businessBelong);
		$rs = $this->list_d();
		if(is_array($rs)){
			$provinceArr = array();
			foreach($rs as $val){
				array_push($provinceArr,$val['province']);
			}
			return implode($provinceArr,',');
		}else{
			return '';
		}
    }

	/**
	 * ���ݸ�����id����ʡ��
	 * @param $userId
	 * @return string
	 */
	function getProvinceByUser_d($userId) {
		$this->searchArr = array('findManagerId' => $userId);
		$row = $this->listBySqlId();
		$provinceStr = '';
		$provinceArr = array();
		if (is_array($row)) {
			foreach ($row as $key => $val) {
				$provinceArr[$key] = $val['province'];
			}
			$provinceStr = implode(',', $provinceArr);
		}
		return $provinceStr;
	}

    /**
     * ��ȡʡ�ݺͲ�Ʒ��
     */
    function getProvincesAndLines_d($userId = null){
    	$userId = empty($userId) ? $_SESSION['USER_ID'] : $userId;
    	$this->searchArr = array('findManagerId' => $userId);
    	$rs = $this->list_d();
    	if(is_array($rs)){
    		$plArr = array();
    		foreach($rs as $val){
    			$pl = array('province'=>$val['province'],'productLine' =>$val['productLine']);
    			array_push($plArr,$pl);
    		}
    		return $plArr;
    	}else{
    		return '';
    	}
    }

    /**
     * ��ȡ������
     */
    function getManager_d($province,$businessBelong = null){
		$businessBelong = empty($businessBelong) ? $_SESSION['USER_COM'] : $businessBelong;
		$obj = $this->find(array('province' => $province,'businessBelong' => $businessBelong),null,'managerId,managerName');
		$rtObj = array(
			'areaManagerId' => '',
			'areaManager' => ''
		);
		if(strpos($obj['managerId'],',') === FALSE){
			$rtObj['areaManagerId'] = $obj['managerId'];
			$rtObj['areaManager'] = $obj['managerName'];
		}else{
			$rtObj['areaManagerId'] = array_pop(implode(',',$obj['managerId']));
			$rtObj['areaManager'] = array_pop(implode(',',$obj['managerName']));
		}
		return $rtObj;
    }

    /**
     * ��ȡ������ created by huanghaojin
     */
    function getManagerForMail_d($province,$businessBelong = null){
        $businessBelong = empty($businessBelong) ? $_SESSION['USER_COM'] : $businessBelong;
        $obj = $this->find(array('province' => $province,'businessBelong' => $businessBelong),null,'managerId,managerName');
        $rtObj = array(
            'areaManagerId' => '',
            'areaManager' => ''
        );
        $rtObj['areaManagerId'] = ($obj['managerId'] == '')? '' : $obj['managerId'];
        $rtObj['areaManager'] = ($obj['managerName'] == '')? '' : $obj['managerName'];
        return $rtObj;
    }
}