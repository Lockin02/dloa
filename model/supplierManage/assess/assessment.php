<?php
/**
 * @description: 供应商临时库评估信息
 * @date 2010-11-10 下午02:07:59
 * @author oyzx
 * @version V1.0
 */
class model_supplierManage_assess_assessment extends model_base{

	public $statusDao; //状态类
	public $datadictDao; //数据字典dao

	function __construct(){
		$this->tbl_name = "oa_supp_assessment";
		$this->sql_map = "supplierManage/assess/assessmentSql.php";
		parent::__construct ();

		$this->datadictDao = new model_system_datadict_datadict();
		$this->statusDao = new model_common_status();
		$this->statusDao->status = array(
			array(
				"statusEName" => "save",
				"statusCName" => "保存",
				"key" => "1"
			),
			array(
				"statusEName" => "ongoing",
				"statusCName" => "进行中",
				"key" => "2"
			),
			array(
				"statusEName" => "close",
				"statusCName" => "关闭",
				"key" => "3"
			),
			array(
				"statusEName" => "end",
				"statusCName" => "完成",
				"key" => "4"
			)
		);
	}

	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------------------*
	 **************************************************************************************************/



	/***************************************************************************************************
	 * ------------------------------以下接口方法,可供其他模块调用---------------------------------------*
	 **************************************************************************************************/

	 /**
	 * @desription 通过Id获取数据
	 * @param tags
	 * @date 2010-11-12 下午04:56:52
	 */
	function saaArrById_d ($id) {
		$this->resetParam();
		$this->searchArr = array( "id"=>$id );
		$arr = $this->list_d();
		$arr['0']['assesTypeName'] = $this->datadictDao->dataDictArr[ $arr['0']['assesType'] ];
		$arr['0']['statusC'] = $this->statusDao->statusKtoC( $arr['0']['status']  );
		return $arr;
	}

	/**
	 * @desription 获取分页列表
	 * @param tags
	 * @date 2010-11-13 下午02:27:20
	 */
	function saaPage_d ( $sql="" ) {
		$arr = $this->page_d( $sql );
		if( is_array($arr) ){
			foreach( $arr as $key => $val ){
				$arr[$key]['assesTypeName'] = $this->datadictDao->dataDictArr[ $arr[$key]['assesType'] ];
				$arr[$key]['statusC'] = $this->statusDao->statusKtoC( $arr[$key]['status']  );
			}
		}
		return $arr;
	}

	/**
	 * @desription 提交评估
	 * @param tags
	 * @date 2010-11-16 下午02:47:24
	 */
	function submit_d ( $id ) {
		$normDao = new model_supplierManage_assess_norm();
		$normArr = $normDao->getAllByAssesUId_d($id);
		$peoDao = new model_supplierManage_assess_sapeople();
		$suppDao = new model_supplierManage_assess_suppassess();
		$suppArr = $suppDao->getAllByAssesId($id);
		$perArr = $this->findSql(" select * from oa_supp_asses_per where assesId='".$id."' and userId='".$_SESSION['USER_ID']."' ");
		$val = true;
		foreach($normArr as $nKey =>$nVal){
			$nval=true;
			foreach( $suppArr as $sKey=>$sVal ){
				$sval = false;
				foreach( $perArr as $pKey=>$pVal ){
					if( $pVal['normId']==$nVal['id'] && $pVal['suppId']==$sVal['id']&&isset( $pVal['score'] ) ){
						$sval = true;
					}
				}
				if($sval==false) {
					$nval=false;
				}
			}
			if($nval == false) $val=false;
		}

		if($val){
			$peoDao->peoSubmit_d($id);
			return 1;
		}else{
			return 0;
		}
	}

	/**
	 * @desription 显示关闭列表
	 * @param tags
	 * @date 2010-11-17 下午04:56:51
	 */
	function showClose ($id) {
		//echo "<pre>";
		$normDao = new model_supplierManage_assess_norm();
		$normArr = $normDao->getAllByAssesUId_d($id);
		$peoDao = new model_supplierManage_assess_sapeople();
		$suppDao = new model_supplierManage_assess_suppassess();
		$suppArr = $suppDao->getAllByAssesId($id);
		$perArr = $this->findSql(" select * from  oa_supp_asses_per where assesId='".$id."' ");
		$datadictArr = $this->getDatadicts("GYSJB");
		$datadictStr = $this->getDatadictsStr( $datadictArr['GYSJB'] );
		//print_R($suppArr);
		$arr = array();
		$arr['0']['0'] = array("val"=>"供应商\指标" );
		foreach( $suppArr as $suppKey => $suppVal ){
			$scVal2 = 0;
			foreach( $normArr as $normKey => $normVal ){
				$arr[$suppKey+1]['0']['val']=$suppVal['suppName'];
				$arr[$suppKey+1][$normKey+1]['val']="";
				$arr['0'][$normKey+1]['val'] = $normVal['normName'];

				$scVal = 0;
				$i = 0;
				foreach( $perArr as $perKey => $perVal ){
					if( isset( $perVal['score'] ) && $perVal['score']!="" && $perVal['normId']==$normVal['id']  &&  $perVal['suppId']==$suppVal['id'] ){
						$perVal['score'] += 0;
						$scVal += $perVal['score'];
						++$i;
					}
				}
				if( $i>0 ) $arr[$suppKey+1][$normKey+1]['val'] = $scVal/$i;
					else $arr[$suppKey+1][$normKey+1]['val'] = 0;

				$weight = $normVal['weight'];
				$weight += 0;
				$scVal2 += ( $arr[$suppKey+1][$normKey+1]['val'])*$weight/100;
			}
			$arr['0'][$normKey+2]['val'] = "总分/百分制";
			$arr[$suppKey+1][$normKey+2]['val'] = $scVal2;
			$arr['0'][$normKey+3]['val'] = "评估级别";
			$arr[$suppKey+1][$normKey+3]['val'] =<<<EOT
			<select name="obj[$suppVal[suppId]]">
				$datadictStr
			</select>
EOT;
		}
		return $arr;

	}

	/**
	 * @desription 显示列表
	 * @param tags
	 * @date 2010-11-17 下午05:31:11
	 */
	function showClose_s ( $arr ) {
		$top="";
		$list = "";
		foreach( $arr as $key => $val ){
			if( $key==0 ){
				foreach( $val as $keyc => $valc ){
					if( $keyc ==0 ) $width="width='20%'";
					else $width="";
					$top .= "<th $width> ".$valc['val']." </th>";
				}
			}else{
				$trV ="";
				foreach( $val as $keyv => $valv ){
					$trV .= "<td> $valv[val] </td>";
				}
				$list .= "<tr>$trV</tr>";
			}
		}

		$str =<<<EOT
                    <thead>
                        <tr class="main_tr_header">
                            $top
                        </tr>
                    </thead>
                    <tbody>
						$list
                    </tbody>
EOT;
		return $str;
	}

	/**
	 * @desription 关闭评估
	 * @param tags
	 * @date 2010-11-17 下午06:41:03
	 */
	function assClose_d ( $arr ) {
		try {
			$this->start_d ();
			$object = array(
				"id" => $arr['assId'],
				"status" => $this->statusDao->statusEtoK("close")
			);
			$val = $this->edit_d($object, true);
			$peoDao = new model_supplierManage_assess_sapeople();
			$peoObj = array(
				"status" => $peoDao->statusDao->statusEtoK("close")
			);
			$peoDao->update(array( "assesId"=>$arr['assId'] ), $peoObj);
			$flibDao = new model_supplierManage_formal_flibrary();
			foreach( $arr['obj'] as $key => $val  ){
				$flibDao->changeLevel_d( $key,$val );
			}
			//TODO:等待变更数据。
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * @desription 判断是否可以启动评估
	 * @param tags
	 * @date 2010-11-18 下午05:03:04
	 */
	function saaStartIs_d ( $id ) {
		$normDao = new model_supplierManage_assess_norm();
		$peoDao = new model_supplierManage_assess_sapeople();
		$suppDao = new model_supplierManage_assess_suppassess();
		$normDao->pk = "id";
		$normCount = $normDao->findCount( array( "assesId"=>$id ) );
		$peoDao->pk = "id";
		$peoCount = $peoDao->findCount( array( "assesId"=>$id ) );
		$suppDao->pk = "id";
		$suppCount = $suppDao->findCount( array( "assesId"=>$id ) );
		if( !isset($normCount) || !isset($peoCount) || !isset($suppCount) || $normCount<=0 || $peoCount<=0 || $suppCount<=0 ){
			return false;
		}else{
			return true;
		}

	}
}
?>
