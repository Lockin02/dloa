<?php
/**
 *
 * 质检物料序列号台账Model层
 * @author chenrf
 *
 */
class  model_produce_quality_serialno extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_quality_serialno";
		$this->sql_map = "produce/quality/serialnoSql.php";
		parent::__construct ();
	}

	/**
	 * 序列号批量录入
	 */
	function deal_d($object){
		try{
			$this->start_d();

			//将序列号分组
			$snoArr = $this->trimArr_d(explode("\n",$object['sequences']));
			unset($object['sequences']);

			//获取现有序列号
			$nowSnoInfo = $this->getSequence_d($object['relDocId'],$object['relDocType']);
			$nowSnoArr = empty($nowSnoInfo['sequences']) ? array() : $this->trimArr_d(explode("\n",$nowSnoInfo['sequences']));

			//处理数组交集 - 共同拥有的不需调整
			$intersectionArr = array_intersect($snoArr,$nowSnoArr);

			//处理数组差集
			$diffAddArr = array_diff($snoArr,$intersectionArr);
			$diffDelArr = array_diff($nowSnoArr,$intersectionArr);

			//存在需要新增的数组 - 则做新增操作
			if(count($diffAddArr) > 0){
				foreach ($diffAddArr as $val){
					if($val){
						$object['sequence'] = $val;
						parent::add_d($object);
					}
				}
			}

			//存在需要删除的数组 - 则做删除操作
			if(count($diffDelArr) > 0){
				foreach ($diffDelArr as $val){
					$sql = "delete from ".$this->tbl_name." where relDocId = '".$object['relDocId']."' and relDocType='".$object['relDocType']."' and sequence = '$val' limit 1";
					$this->_db->query($sql);
				}
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollback();
			return false;
		}
	}

	/**
	 * 处理换行
	 */
	function trimArr_d($rows){
		if($rows){
			foreach($rows as $key => $val){
				$rows[$key] = trim($val);
			}
		}
		return $rows;
	}

	/**
	 * 重写add_d方法
	 */
	function add_d($object){
//		echo '<pre>';
//		print_r($object);
//		die();
		try{
			$this->start_d();
			if(is_array($object['items']))
			foreach ($object['items'] as &$val){
				if(isset($val['id'])&&!empty($val['id']))
				$id.=$val['id'].',';
				if($val['isDelTag']==1){
					$val=null;
				}
				else{
					$val['productCode']=$object['productCode'];
					$val['productName']=$object['productName'];
					$val['productId']=$object['productId'];
					$val['pattern']=$object['pattern'];
					$val['relDocId']=$object['relDocId'];
					$val['relDocType']=$this->tbl_name;
				}
			}
			unset($val);
			$items=array_filter($object['items']);
			if(!empty($id))
			$this->delete(' id in ('.rtrim($id,',').')');
			$this->createBatch($items);
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollback();
			return false;
		}

	}

	/**
	 * excel导入
	 */
	function importExcel($object){
		set_time_limit(0);
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			$resultArr=array();//执行结果
			$actNum=1;
			$productNum=$object['productNum'];   //不合格数量
			if(is_array($excelData)){
				$existsRow=$this->getCount($object['relDocId']);//数据库存在的不合格数量
				$productNum-=$existsRow;

				foreach ($excelData as $val){
					if($actNum>$productNum)  //当导入条数大于等于不及格数时。跳出循环
						break;
					if(isset($val[0])&&!empty($val[0])){
						$tempVal['productCode']=$object['productCode'];
						$tempVal['productName']=$object['productName'];
						$tempVal['productId']=$object['productId'];
						$tempVal['pattern']=$object['pattern'];
						$tempVal['relDocId']=$object['relDocId'];
						$tempVal['relDocType']=$this->tbl_name;
						$tempVal['sequence']=$val[0];
						$tempVal['remark']=$val[1];
						if(parent::add_d($tempVal)){
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入成功';
							array_push( $resultArr,$tempArr );
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败，发生未知错误';
							array_push( $resultArr,$tempArr );
						}
						unset($tempVal);

					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败，序列号为空';
						array_push( $resultArr,$tempArr );
					}
					$actNum++;
				}
			}
		}
		return $resultArr;
	}
	/**
	 *
	 * 返回某一物料不合格的序列号总数
	 *
	 */
	function getCount($relDocId,$relDocType){
		$count=$this->findCount(array('relDocType'=>$relDocType,'relDocId'=>$relDocId));//数据库存在的不合格数量
		return $count;
	}

	/**
	 * 获取对应源单的序列号
	 */
	function getSequence_d($relDocId,$relDocType){
		$this->searchArr = array(
			'relDocIds' => $relDocId,
			'relDocType' => $relDocType
		);
		$rs = $this->list_d('select_count');
		if($rs[0]['num']){
			return $rs[0];
		}else{
			return array(
				'sequences' => '',
				'num' => 0
			);
		}
	}
}