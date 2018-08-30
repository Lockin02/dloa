<?php
/*
 * 评估人员model层方法
 */
class model_supplierManage_assess_sapeople extends model_base {

	public $statusDao; //状态类

/**
 * @desription 构造函数
 * @param tags
 * @date 2010-11-10 下午04:39:47
 */
	function __construct () {
		$this->tbl_name = "oa_supp_asses_people";
		$this->sql_map = "supplierManage/assess/sapeopleSql.php";
		parent :: __construct();

		$this->statusDao = new model_common_status();
		$this->statusDao->status = array(
			array(
				"statusEName" => "save",
				"statusCName" => "保存",
				"key" => "0"
			),
			array(
				"statusEName" => "submit",
				"statusCName" => "提交",
				"key" => "1"
			),
			array(
				"statusEName" => "close",
				"statusCName" => "关闭",
				"key" => "2"
			)
		);
	}

	/**
	 * @desription 添加人员
	 * @param tags
	 * @date 2010-11-15 上午11:48:28
	 */
	function sapAdd($arr,$normId) {
		try {
			$assesId = $arr['assesId'];
			$this->resetParam();
			$this->searchArr = array( "assesId"=>$assesId );
			$peoArr = $this->list_d();
			$suppDao = new model_supplierManage_assess_suppassess();
			$suppArr = $suppDao->getAllByAssesId($assesId);

			$userSql = "'". str_replace( "," , "','" , substr( $arr['peo']['asseserId'] ,0,-1)  ) ."'";
			$userArr = $this->findSql( " select user.USER_ID,user.USER_NAME,d.DEPT_ID,d.DEPT_NAME from user left join department d on( user.DEPT_ID=d.DEPT_ID )  where user.USER_ID in($userSql) " );

			if( is_array($userArr) ){
				$normpeoSql = " insert into oa_supp_asses_normpeo(normId,normName,asseserId,asseserName,assDept,assDeptId,assesId,status,peopleId) values  ";

				foreach( $userArr as $userKey => $userVal ){
					$peoplePjId = "";
					$supppeoSql = " insert into oa_supp_asses_supppeo(suppPjId,suppId,suppName,peopleId,asseserId,asseserName,assesId)values ";
					if( is_array($peoArr) ){
						foreach( $peoArr as $peoKey => $peoVal ){
							if( $peoVal['asseserId'] == $userVal['USER_ID'] ){
								$peoplePjId = $peoVal['id'];
							}
						}
					}
					if( $peoplePjId=="" ){
						$addPeoArr = array(
							"assesId" => $assesId,
							"asseserId" => $userVal['USER_ID'],
							"asseserName" => $userVal['USER_NAME'],
							"assDept" => $userVal['DEPT_NAME'],
							"assDeptId" => $userVal['DEPT_ID'],
							"status" => $this->statusDao->statusEtoK( "save"  )
						);
						$peoplePjId = $this->add_d($addPeoArr);

						foreach( $suppArr as $suppKey => $suppVal ){
							$supppeoSql .= "('".$suppVal['id']."','".$suppVal['suppId']."','".$suppVal['suppName']."','".$peoplePjId."','".$userVal['USER_ID']."','".$userVal['USER_NAME']."','".$assesId."'  ),";
						}
						$this->query( substr( $supppeoSql,0,-1 ) );
					}
					$normpeoSql .= "('".$normId."','".$arr['normName']."','".$userVal['USER_ID']."','".$userVal['USER_NAME'] ."','".$userVal['DEPT_NAME']."','".$userVal['DEPT_ID']."','".$assesId."',0,$peoplePjId),";
				}
				$this->query( substr( $normpeoSql,0,-1 ) );
			}
		} catch ( Exception $e ) {
			throw $e;
			return $e;
		}
	}

	/**
	 * @desription 获取数据
	 * @param tags
	 * @date 2010-11-15 下午02:00:49
	 */
	function sapPage_d ($sql="") {
		$arr = $this->page_d( $sql );
		$assDao = new model_supplierManage_assess_assessment();
		if( is_array($arr) ){
			foreach( $arr as $key => $val ){
				$arr[$key]['aassesTypeName'] = $assDao->datadictDao->dataDictArr[ $arr[$key]['aassesType'] ];
				$arr[$key]['astatusC'] = $assDao->statusDao->statusKtoC( $arr[$key]['astatus']  );
				$arr[$key]['statusC'] = $this->statusDao->statusKtoC( $arr[$key]['status']  );
				$arr[$key]['ssuppLevelC'] = $this->statusDao->statusKtoC( $arr[$key]['ssuppLevel']  );

			}
		}
		return $arr;
	}

	/**
	 * @desription 添加
	 * @param tags
	 * @date 2010-11-16 上午10:11:07
	 */
	function saveAssessmentWork_d ( $obj ) {
		$sqlAdd = " insert into oa_supp_asses_per(normId,suppId,assesId,peopleId,userId," .
				"peoperName,score,createId,createName,createTime," .
				"updateId,updateName,updateTime  ) values ";
		$sqlUpadate = "";
		$up = $add = false;
		foreach( $obj['normId'] as $key => $val ){
			if( isset( $val['perId'] )&&$val['perId']!="" ){
				$up = true;
				$sqlUpadate = "update oa_supp_asses_per set score='".$val['score']."',updateId='".$_SESSION['USER_ID']."',updateName='".$_SESSION['USERNAME']."',updateTime='".date("Y-m-d H:i:s")."' where id='".$val['perId']."';";
				$this->query($sqlUpadate);
			}else{
				$add = true;
				$sqlAdd .="( '".$key."','".$obj['suppId']."','".$obj['assesId']."','".$obj['peopleId']."','".$_SESSION['USER_ID'].
						"','".$_SESSION['USERNAME']."','".$val['score']."','".$_SESSION['USER_ID']."','".$_SESSION['USERNAME']."','".date("Y-m-d H:i:s").
						"','".$_SESSION['USER_ID']."','".$_SESSION['USERNAME']."','".date("Y-m-d H:i:s")."' ),";
			}
		}
		if( $add==true ){
			$sql = substr( $sqlAdd,0,-1 );
			return $this->query($sql);
		}else{
			return true;
		}
	}

	/**
	 * @desription 提交
	 * @param tags
	 * @date 2010-11-16 下午03:10:08
	 */
	function peoSubmit_d ($assesId) {
		$sql = " update oa_supp_asses_people set status='".$this->statusDao->statusEtoK( 'submit' )."' where assesId='$assesId' and asseserId='".$_SESSION['USER_ID']."' ";
		return $this->query($sql);
	}

	/**
	 * @desription 根据方案Id 获取数据
	 * @param tags
	 * @date 2010-11-16 下午03:46:16
	 */
	function peoGetAllByAssesId_d ( $assesId ) {
		$this->resetParam();
		$this->searchArr = array( "assesId"=>$assesId );
		$arr = $this->list_d();
		//$datDao = new model_system_datadict_datadict();
		//$arr['0']['normCodeName'] = $datDao->dataDictArr[ $arr['0']['normCode'] ];
		return $arr;
	}

}
?>
