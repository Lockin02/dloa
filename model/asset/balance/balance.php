<?php


/**
 *
 * ���model
 * @author fengxw
 *
 */
class model_asset_balance_balance extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_balance";
		$this->sql_map = "asset/balance/balanceSql.php";
		parent :: __construct();
	}

	/**
	 * �½��۾���Ϣ
	 */
	function add_d($object) {

		if ($object['assetId'] == "") {
			try {
				$this->start_d();
				//��ȡ��Ƭ������Ϣ�������ÿ���ʲ����۾���Ϣ������$addArr
				$assetDao = new model_asset_assetcard_assetcard();
				$assetArr = $assetDao->getCards();
				if (!$assetArr) {
					msgGo('����û�з����۾��������ʲ�����ȷ�Ϻ������ԣ�');
					throw new Exception('����ʧ�ܣ�');
				}
				//���
				$localYear = date("Y");
				$localDate = date("Y-m-d");
				$addArr = array (); //��ȷ��Ϣ����
				foreach ($assetArr as $key => $obj) {
					$addArr[$key]['assetId'] = $obj['id'];
					//�ڳ��ۼ��۾�
					$addArr[$key]['initDepr'] = $obj['depreciation'];
//					//�����۾���
//					$addArr[$key]['deprRate'] = ($obj['origina'] - $obj['salvage']) / $obj['estimateDay'] / $obj['origina'] * 100;
					//�ж�ʣ���۾ɶ�Ϊ����ʱ����ʣ���۾ɶֵΪ0�����ڼ����۾�Ϊ����ʣ���۾ɶ�Ϊ0��ֵ
					$addArr[$key]['deprRemain'] = $obj['origina'] - $obj['salvage'] - $obj['monthlyDepreciation'] - $obj['depreciation'];
					if ($addArr[$key]['deprRemain'] < 0) {
						//ʣ���۾ɶ�
						$addArr[$key]['deprRemain'] = '0';
						//���ڼ����۾ɶ�
						$addArr[$key]['depr'] = $obj['origina'] - $obj['salvage'] - $obj['depreciation'];
					} else {
						//���ڼ����۾ɶ�
						$addArr[$key]['depr'] = $obj['monthlyDepreciation'];
					}
					//����Ӧ���۾ɶ�
					$addArr[$key]['deprShould'] = $obj['monthlyDepreciation'];
					//�ڼ�
					$addArr[$key]['period'] = $obj['alreadyDay'] + 1;
					//���
					$addArr[$key]['years'] = $localYear;
					//�۾�����
					$addArr[$key]['deprTime'] = $localDate;
					//�ʲ�ԭֵ
					$addArr[$key]['origina'] = $obj['origina'];
					//Ԥ��ʹ���ڼ�
					$addArr[$key]['estimateDay'] = $obj['estimateDay'];
					//Ԥ�ƾ���ֵ
					$addArr[$key]['salvage'] = $obj['salvage'];
				}
				//�ж����ݿ��Ƿ���ڱ��ڵ��۾ɣ�������ھ���ɾ�������ݣ��������µ��۾���Ϣ
				$arr = array ();
				foreach ($addArr as $key => $Obj) {
					$arr[$key]['assetId'] = $addArr[$key]['assetId'];
					$arr[$key]['deprTime'] = $addArr[$key]['deprTime'];

					$oldArr = $this->findBid($arr[$key]['deprTime'], $arr[$key]['assetId']);
					$arr[$key]['id'] = $oldArr[0]['id'];
					$arr[$key]['deprShould'] = $oldArr[0]['deprShould'];
					$arr[$key]['depr'] = $oldArr[0]['depr'];
					$arr[$key]['initDepr'] = $oldArr[0]['initDepr'];
					$arr[$key]['deprRemain'] = $oldArr[0]['deprRemain'];
					$arr[$key]['origina'] = $oldArr[0]['origina'];
					$arr[$key]['salvage'] = $oldArr[0]['salvage'];
					$arr[$key]['period'] = $oldArr[0]['period'];
					if ($oldArr != "") {
						$assetDao = new model_asset_assetcard_assetcard();
						// �����ʲ���Ƭ���ۼ��۾ɺ;�ֵ
						$assetDao->updateDepreciationReturn($arr[$key]['assetId'], $arr[$key]['depr']);
						$this->deletes($arr[$key]['id']);
					}
					//��ȡ��һ�ڵ��۾�����
					$oldArray = $this->findLastMonBid($arr[$key]['deprTime'], $arr[$key]['assetId']);
					$arr[$key]['olddepr'] = $oldArray[0]['depr'];
					$arr[$key]['oldinitDepr'] = $oldArray[0]['initDepr'];
					$arr[$key]['olddeprRemain'] = $oldArray[0]['deprRemain'];
					$arr[$key]['oldperiod'] = $oldArray[0]['period'];
				}
				//���»�ȡ��Ƭ����Ϣ
				foreach ($assetArr as $key => $obj) {
					//�ڳ��ۼ��۾�ֵ�ǲ����
					if ($arr[$key]['initDepr'] != "") {
						$addArr[$key]['initDepr'] = $arr[$key]['initDepr'];
						//								$addArr[$key]['initDepr'] = $addArr[$key]['initDepr']-$arr[$key]['deprShould'];
					} else
						if ($arr[$key]['oldinitDepr'] != "") {
							$addArr[$key]['initDepr'] = $arr[$key]['oldinitDepr'] + $arr[$key]['olddepr'];
						}
					//ʣ���۾ɶ��ǲ����
					if ($arr[$key]['deprRemain'] != "" || $arr[$key]['olddeprRemain'] != "") {
						$addArr[$key]['deprRemain'] = $addArr[$key]['origina'] - $addArr[$key]['salvage'] - $addArr[$key]['initDepr'] - $addArr[$key]['depr'];
					}
					//����Ŀǰ��ֵ
					$addArr[$key]['localNetValue'] = $addArr[$key]['deprRemain'] + $addArr[$key]['salvage'];
					//�ڼ�
					if ($arr[$key]['period'] != "") {
						$addArr[$key]['period'] = $arr[$key]['period'];
					} else
						if ($arr[$key]['oldperiod'] != "") {
							$addArr[$key]['period'] = $arr[$key]['oldperiod'] + 1;
						}
				}
				//���������۾���Ϣ
				$newArr = array ();
				foreach ($addArr as $key => $Obj) {
					$newArr[$key]['assetId'] = $addArr[$key]['assetId'];
					$newArr[$key]['origina'] = $addArr[$key]['origina'];
					$newArr[$key]['localNetValue'] = $addArr[$key]['localNetValue'];
					$assetDao = new model_asset_assetcard_assetcard();
					// �����ʲ���Ƭ���ۼ��۾ɺ;�ֵ
					$assetDao->updateDepreciation($newArr[$key]['assetId'], $newArr[$key]['origina'], $newArr[$key]['localNetValue'], $addArr[$key]['period']);
					// �ж��۾�����ʲ��������ʲ���Ƭ���۾�״̬
					if ($addArr[$key]['localNetValue'] <= $addArr[$key]['salvage']) {
						$assetDao->setIsDepr($addArr[$key]['assetId']);
					}
				}
				$this->addBatch_d($addArr, true);
				$this->commit_d();
				return true;
			} catch (Exception $e) {
				$this->rollBack();
				return null;
			}
		} else {
			// ��ɾ�������۾ɵ�����
			$oldArr = $this->findBid($object['deprTime'], $object['assetId']);
			//��ȡ��һ�ڵ��۾�����
			$oldArray = $this->findLastMonBid($object['deprTime'], $object['assetId']);
			if ($oldArr != "") {
				$assetDao = new model_asset_assetcard_assetcard();
				// �����ʲ���Ƭ���ۼ��۾ɺ;�ֵ
				$assetDao->updateDepreciationReturn($object['assetId'], $oldArr[0]['depr']);
				$this->deletes($oldArr[0]['id']);
			}
			//�ڳ��ۼ��۾�
			if ($oldArr[0]['initDepr'] != "") {
				$object['initDepr'] = $oldArr[0]['initDepr'];
			} else
				if ($oldArray[0]['initDepr'] != "") {
					$object['initDepr'] = $oldArray[0]['initDepr'] + $oldArray[0]['depr'];
				}
			//ʣ���۾ɶ�
			if ($oldArr[0]['deprRemain'] != "" || $oldArray[0]['deprRemain'] != "") {
				$object['deprRemain'] = $object['origina'] - $object['salvage'] - $object['initDepr'] - $object['depr'];
			}

			//����Ŀǰ��ֵ
			$object['localNetValue'] = $object['deprRemain'] + $object['salvage'];
			//�ڼ�
			if ($oldArr[0]['period'] != "") {
				$object['period'] = $oldArr[0]['period'];
			} else
				if ($oldArray[0]['period'] != "") {
					$object['period'] = $oldArray[0]['period'] + 1;
				}
			// �����������µ��۾���Ϣ
			try {
				$this->start_d();
				$assetDao = new model_asset_assetcard_assetcard();
				// �����ʲ���Ƭ���ۼ��۾ɺ;�ֵ
				$assetDao->updateDepreciation($object['assetId'], $object['origina'], $object['localNetValue'], $object['period']);
				// �ж��۾�����ʲ��������ʲ���Ƭ���۾�״̬
				if ($object['localNetValue'] <= $object['salvage']) {
					$assetDao->setIsDepr($object['assetId']);
				}
				$id = parent :: add_d($object, true);
				$this->commit_d();
				return $id;
			} catch (Exception $e) {
				$this->rollBack();
				return $id;
			}
		}
	}

	/**
	 * �����۾�ʱ��deprTime�����ұ����۾���Ϣ
	 */
	function findBid($date, $assetId) {
		//��ȡָ�����������µĵ�һ��ͻ�ȡָ�������¸��µĵ�һ��
		$arr = getdate();
		if ($arr['mon'] == 12) {
			$year = $arr['year'] + 1;
			$month = $arr['mon'] - 11;
			$day = $arr['mday'];
			if ($day < 10) {
				$mday = '0' . $day;
			} else {
				$mday = $day;
			}
			$nextfirstday = $year . '-0' . $month . '-01';
		} else {
			$time = strtotime($date);
			$nextfirstday = date('Y-m-01', strtotime(date('Y', $time) . '-' . (date('m', $time) + 1) . '-01'));
		}
		$firstday = date("Y-m-01", strtotime($date));
		//		print_r($firstday);
		//		print_r($nextfirstday);
		$sql = "select id,depr,deprShould,initDepr,deprRemain,origina,estimateDay,salvage,localNetValue,period from oa_asset_balance b where b.deprTime<'$nextfirstday' and b.deprTime>='$firstday' and b.assetId='$assetId' order by b.deprTime desc limit 1";
		$arr = $this->_db->getArray($sql);
		return $arr;
	}

	/**
	 * �����۾�ʱ��deprTime�������ϸ����۾���Ϣ
	 */
	function findLastMonBid($date, $assetId) {
		//��ȡָ�����ڵ��ϸ��µĵ�һ��ͻ�ȡָ����������µĵ�һ��
		$time = strtotime($date);
		$lastfirstday = date('Y-m-01', strtotime(date('Y', $time) . '-' . (date('m', $time) - 1) . '-01'));
		$firstday = date("Y-m-01", strtotime($date));
		//		print_r($firstday);
		//		print_r($nextfirstday);
		$sql = "select id,depr,deprShould,initDepr,deprRemain,origina,estimateDay,salvage,localNetValue,period from oa_asset_balance b where b.deprTime<'$firstday' and b.deprTime>='$lastfirstday' and b.assetId='$assetId' order by b.deprTime desc limit 1";
		$arr = $this->_db->getArray($sql);
		return $arr;
	}

	/**
	* ���޸ļ����۾ɶ�ʱ��ͬʱ�ı��ʲ���Ƭ�ı����ۻ��۾ɺ;�ֵ
	*/
	function edit_d($object, $isEditInfo = false) {
		$assetDao = new model_asset_assetcard_assetcard();
		// �����ʲ���Ƭ���ۼ��۾ɺ;�ֵ
		$assetDao->updateDepreciationReturn($object['assetId'], $object['olddepr']);
		$assetDao->updateDepreciation($object['assetId'], $object['origina'], $object['localNetValue'], $object['period']);
		if ($isEditInfo) {
			$object = $this->addUpdateInfo($object);
		}
		//		echo "<pre>";
		//		print_r($object);
		return $this->updateById($object);
	}

}
?>