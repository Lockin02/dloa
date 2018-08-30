<?php
/**
 * @author show
 * @Date 2013年12月27日 11:18:01
 * @version 1.0
 * @description:服务经理 Model层
 */
class model_engineering_officeinfo_manager extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_office_managerinfo";
		$this->sql_map = "engineering/officeinfo/managerSql.php";
		parent :: __construct();
	}

	//数据字典字段处理
	public $datadictFieldArr = array('productLine');

	/**
	 * 重写新增方法
	 */
	function add_d($object){
		//数据字典
		$object = $this->processDatadict($object);
		return parent::add_d($object,true);
	}

	/**
	 * 根据主键修改对象
	 */
	function edit_d($object) {
		$rangeDao=new model_engineering_officeinfo_range();
		$officeinfoDao = new model_engineering_officeinfo_officeinfo();
		try{
			$this->start_d();
			//数据字典
			$object = $this->processDatadict($object);
			//自己编辑
			parent::edit_d($object);
			//如果修改了省份和归属公司,那么就需要更新旧的信息
			if($object['provinceId'] != $object['oldProvinceId'] || $object['businessBelong'] != $object['oldBusinessBelong']){
				$rangeDao->updateEsmManagerPro_d($object['oldProvinceId'],$object['oldBusinessBelong'],$object['oldProductLine']);

				//查询被删除含有被删除省份的办事处
				$rangeDao->update(array('proId' => $object['oldProvinceId'],'businessBelong' => $object['oldBusinessBelong']),array('managerId' => '','managerName' => ''));
				$officeArr = $officeinfoDao->getNeedUpdateManager_d($object['oldProvinceId'],$object['oldBusinessBelong'],$object['oldProductLine']);
				if($officeArr){
					foreach($officeArr as $val){
						//获取办事处责任省份负责人
						$newManagerArr = $rangeDao->getManager_d($val['id']);
						$officeinfoDao->update(
							array('id' => $val['id']),
							array('managerName' => $newManagerArr['managerName'],'managerCode' => $newManagerArr['managerId'])
						);
					}
				}
			}

			//直接更新省份信息
			$rangeDao->updateEsmManagerPro_d($object['provinceId'],$object['businessBelong'],$object['productLine']);

			//更新办事处的服务经理部分
			$officeArr = $officeinfoDao->getNeedUpdateManager_d($object['provinceId'],$object['businessBelong'],$object['productLine']);
			if($officeArr){
				foreach($officeArr as $val){
					//获取办事处责任省份负责人
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
	 * 批量删除对象
	 */
	function deletes_d($ids) {
		try {
			$this->start_d();

			//首先获取要删除的内容
			$this->searchArr['ids'] = $ids;
			$rows = $this->list_d();

			//删除数据
			$this->deletes ( $ids );

			//根据删除的内容更新办事处信息
			$rangeDao=new model_engineering_officeinfo_range();
			$officeinfoDao = new model_engineering_officeinfo_officeinfo();
			foreach($rows as $v){
				//删除相关范围信息
				$rangeDao->update(array('proId' => $v['provinceId'],'businessBelong' => $v['businessBelong']),array('managerId' => '','managerName' => ''));

				//查询被删除含有被删除省份的办事处
				$officeArr = $officeinfoDao->getNeedUpdateManager_d($v['provinceId'],$v['businessBelong'],$v['productLine']);
				if($officeArr){
					foreach($officeArr as $val){
						//获取办事处责任省份负责人
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
	 * 省份权限获取 -- 旧的部分，已经不适用了
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
	 * 根据负责人id查找省份
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
     * 获取省份和产品线
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
     * 获取服务经理
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
     * 获取服务经理 created by huanghaojin
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