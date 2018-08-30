<?php
/**
 *
 * �ʼ��������к�̨��Model��
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
	 * ���к�����¼��
	 */
	function deal_d($object){
		try{
			$this->start_d();

			//�����кŷ���
			$snoArr = $this->trimArr_d(explode("\n",$object['sequences']));
			unset($object['sequences']);

			//��ȡ�������к�
			$nowSnoInfo = $this->getSequence_d($object['relDocId'],$object['relDocType']);
			$nowSnoArr = empty($nowSnoInfo['sequences']) ? array() : $this->trimArr_d(explode("\n",$nowSnoInfo['sequences']));

			//�������齻�� - ��ͬӵ�еĲ������
			$intersectionArr = array_intersect($snoArr,$nowSnoArr);

			//��������
			$diffAddArr = array_diff($snoArr,$intersectionArr);
			$diffDelArr = array_diff($nowSnoArr,$intersectionArr);

			//������Ҫ���������� - ������������
			if(count($diffAddArr) > 0){
				foreach ($diffAddArr as $val){
					if($val){
						$object['sequence'] = $val;
						parent::add_d($object);
					}
				}
			}

			//������Ҫɾ�������� - ����ɾ������
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
	 * ������
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
	 * ��дadd_d����
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
	 * excel����
	 */
	function importExcel($object){
		set_time_limit(0);
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			$resultArr=array();//ִ�н��
			$actNum=1;
			$productNum=$object['productNum'];   //���ϸ�����
			if(is_array($excelData)){
				$existsRow=$this->getCount($object['relDocId']);//���ݿ���ڵĲ��ϸ�����
				$productNum-=$existsRow;

				foreach ($excelData as $val){
					if($actNum>$productNum)  //�������������ڵ��ڲ�������ʱ������ѭ��
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
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ɹ�';
							array_push( $resultArr,$tempArr );
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ�ܣ�����δ֪����';
							array_push( $resultArr,$tempArr );
						}
						unset($tempVal);

					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ�ܣ����к�Ϊ��';
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
	 * ����ĳһ���ϲ��ϸ�����к�����
	 *
	 */
	function getCount($relDocId,$relDocType){
		$count=$this->findCount(array('relDocType'=>$relDocType,'relDocId'=>$relDocId));//���ݿ���ڵĲ��ϸ�����
		return $count;
	}

	/**
	 * ��ȡ��ӦԴ�������к�
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