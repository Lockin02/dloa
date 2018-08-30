<?php
/**
 * @description: 供应商临时库评估
 * @date 2010-11-9 下午02:51:01
 */
class controller_supplierManage_temporary_stasse extends controller_base_action{
   /**
   * @desription 构造函数
   * @param tags
   * @date 2010-11-9 下午08:53:05
   */
  function __construct() {
    $this->objName = "stasse";
    $this->objPath = "supplierManage_temporary";
    parent::__construct();
  }

/*******************************普通Action方法**************************************/
  /*
   * @desription 跳转新增供应商评估
   * @param tags
   * @date 2010-11-8 下午02:18:04
   */
  function c_stsToAdd () {
    $parentId = isset( $_GET['parentId'] )?$_GET['parentId']:exit;
    $parentCode = isset( $_GET['parentCode'] )?$_GET['parentCode']:exit;
    $this->show->assign("parentId",$parentId);
    $this->show->assign("parentCode",$parentCode);
    $datadictDao = new model_system_datadict_datadict ();
    $datadictArr = $datadictDao->getDatadictsByParentCodes ( "lskpg" );
    $str = $this->service->add_s($datadictArr['lskpg']);
    $this->show->assign("str",$str);
    $this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
  }

/************************************ 以下是编辑供应商评价*********************************************/

  	/*
	 * @desription 编辑更改供应产品，并跳转到注册的下一步--添加供应商评价
	 * @param tags
	 * @date 2010-11-25 上午10:35:35
	 */
	function c_ededit () {
		$parentId = isset( $_GET['parentId'] )?$_GET['parentId']:null;
		$id = $this->service->edit_d( $_POST['stproduct'],true );
		if($id){
//			succ_show('?model=supplierManage_temporary_stasse&action=stsToAdd&parentId='. $parentId);
			$this->show->assign("parentId",$parentId);
		    $datadictDao = new model_system_datadict_datadict ();
		    $datadictArr = $datadictDao->getDatadictsByParentCodes ( "lskpg" );
		    $str = $this->service->add_s($datadictArr['lskpg']);
		    $this->show->assign("str",$str);
			$this->show->display ( $this->objPath . '_' . 'stasse-add' );

		}
	}

	/*
	 * @desription 编辑供应商初步评价
	 * @param tags
	 * @date 2010-11-24 下午02:19:38
	 */
	function c_toEditAsse () {
		$this->permCheck ($_GET['parentId'],'supplierManage_temporary_temporary');//安全校验
		//通过parentId获取相关的信息
		$parentId = isset( $_GET['parentId'] )?$_GET['parentId']:null;
		$service = $this->service;
		$service->asc = false;
		$service->searchArr = array( 'parentId'=>$parentId );
		$assArr = $service->stsList_d();
//		echo "<pre>";
//		print_r($assArr);
		$datadictDao = new model_system_datadict_datadict ();
	    $datadictArr = $datadictDao->getDatadictsByParentCodes ( "lskpg" );

	    if($assArr){
//	    	echo "*****";
	    	$str = $this->service->addAsse_s($assArr);

	    }else if($datadictArr){
//	    	echo "#########";
	    	$str = $this->service->add_s($datadictArr['lskpg']);
	    }

	    //模板替换
	    //分别替换上半部分的评估选择项及下半部分的评估意见
	    $this->show->assign("str",$str);
	    $this->show->assign('opinion',$assArr[0]['opinion']);

		$this->show->assign('parentId',$parentId);
		$this->show->display($this->objPath . '_' . $this->objName . '-edit');
	}


	/*
	 * @desription 在注册库里，选择供应商进入编辑tab页，进行编辑评价的保存方法
	 * @param tags
	 * @date 2010-11-26 上午10:11:57
	 */
	function c_editAsse () {
		$service = $this->service;
		$parentId = isset( $_GET['parentId'] )?$_GET['parentId']:null;

		for($i = 0;$i<3;$i++){
			$_POST['typeCode'][$i]['opinion'] = $_POST['stasse']['opinion'];
		}

		//根据获取得到的parentId判断数据表是否有这条数据
		//利用bindBy方法，只要搜索到有一条，则为true
		//有的话则对其进行编辑，没有则新增
		$isExistAsse = $service->findBy(parentId,$parentId);
		if($isExistAsse){
			foreach($_POST['typeCode'] as $key=>$val){
				$id = $this->service->edit_d( $val,true );
			}
		}else{
			//把POST到的数据转存到另外一个数组，方便遍历数组
			$arrTemp = array(
				'hpzl' => $_POST['typeCode']['hpzl'],
				'fhsd' => $_POST['typeCode']['fhsd'],
				'fwtd' => $_POST['typeCode']['fwtd'],
				'opinion' => $_POST['typeCode'][0]['opinion']
			);
			//这个变量$i是关于评估选项的控制的。
			//根据评估选项的数量，确定循环的次数。
			//在这个地方是三个评估选项，所以i是从0到2，共3次
			$i = 0;
			foreach($arrTemp as $key => $val){
				$arr[$i] = array(
					"objCode"=>generatorSerial(),
					"systemCode" => generatorSerial(),
					"parentId"=> $parentId,
					"typeCode"=> $val,
					"typeName"=> $key,
					"opinion"=>$_POST['stasse']['opinion']
				);
				$i++;
			}
			for($i = 0;$i<3;$i++){
				$id = $this->service->add_d($arr[$i],true);
			}
		}

		if($id){
			msgGo("编辑成功","?model=supplierManage_temporary_stasse&action=toEditAsse&parentId=".$parentId);
//			msg();
		}

	}


/************************************ 以上是编辑供应商评价*********************************************/


 	/*
	 * @description 临时库跳转到正式库--录入正式库
	 * @2010-11-19 下午21:34:40
	 */
	function c_toFormal () {
		$parentId = isset( $_GET['parentId'] )?$_GET['parentId']:null;
    	$parentCode = isset( $_GET['parentCode'] )?$_GET['parentCode']:null;

    	$this->show->assign('parentId',$parentId);
    	$this->show->assign('parentCode',$parentCode);

		$this->show->display($this->objPath .'_' . 'temptoformal');
	}

  /*
   * @description 注册供应商信息评估
   * @date 2010-9-20 下午02:06:22
   */
  function c_stsAdd() {
    $productNames = explode("," ,substr( $_POST['stasse']['productNames'],0,-1) );
    $productIds = explode("," ,substr( $_POST['stasse']['productIds'],1,-1 ) );
    $parentId = isset($_POST[$this->objName]['parentId'])?$_POST[$this->objName]['parentId']:null;
    $parentCode = isset($_POST[$this->objName]['parentCode'])?$_POST[$this->objName]['parentCode']:null ;

    $this->show->assign('parentId',$parentId);
    $this->show->assign('parentCode',$parentCode);

	$this->show->assign('productNames',$productNames);
	$this->show->assign('productIds',$productIds);

	foreach($_POST['typeCode'] as $key => $val){
		$arr = array(
			"objCode"=>generatorSerial(),
			"systemCode" => generatorSerial(),
			"parentCode" => $parentCode,
			"parentId"=> $parentId,
			"typeCode"=> $val,
			"typeName"=> $key,
			"opinion"=>$_POST['opinion']
		);
	$id = $this->service->add_d($arr,true);
	}


        //判断是‘保存’还是‘提交审批’
		  if($id && $_GET['act'] == 'save'){
		       msgGo( '保存成功！','?model=supplierManage_temporary_temporary&action=myloglist');
		  }else if($id){
		       succ_show('controller/supplierManage/temporary/ewf_index.php?actTo=ewfSelect&billId=' . $parentId . '&examCode=oa_supp_lib_temp&formName=供应商审核');
		  }else{
		       msgGo( '添加失败！','?model=supplierManage_temporary_temporary&action=myloglist' );
		  }
  }


/*
 * @desription 查看供应商初步评价
 * @param tags
 * @date 2010-11-22 下午09:35:50
 */
	function c_toViewAsse () {
		$this->permCheck ($_GET['parentId'],'supplierManage_temporary_temporary');//安全校验
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:null;
		$searchTemp = array('parentId'=>$parentId);
		$asseInfoArr = $this->service->findAll($searchTemp);
		$this->show->assign('list',$this->service->showAsseList($asseInfoArr));
		$this->show->display($this->objPath . '_' . $this->objName . '-view');
	}




}
?>
