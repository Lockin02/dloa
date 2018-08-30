<?php


/**
 *
 * 余额model
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
	 * 新建折旧信息
	 */
	function add_d($object) {

		if ($object['assetId'] == "") {
			try {
				$this->start_d();
				//获取卡片所有信息，计算出每个资产的折旧信息，赋给$addArr
				$assetDao = new model_asset_assetcard_assetcard();
				$assetArr = $assetDao->getCards();
				if (!$assetArr) {
					msgGo('本期没有符合折旧条件的资产，请确认后再重试！');
					throw new Exception('保存失败！');
				}
				//年份
				$localYear = date("Y");
				$localDate = date("Y-m-d");
				$addArr = array (); //正确信息数组
				foreach ($assetArr as $key => $obj) {
					$addArr[$key]['assetId'] = $obj['id'];
					//期初累计折旧
					$addArr[$key]['initDepr'] = $obj['depreciation'];
//					//当期折旧率
//					$addArr[$key]['deprRate'] = ($obj['origina'] - $obj['salvage']) / $obj['estimateDay'] / $obj['origina'] * 100;
					//判断剩余折旧额为负数时，把剩余折旧额赋值为0，本期计提折旧为能让剩余折旧额为0的值
					$addArr[$key]['deprRemain'] = $obj['origina'] - $obj['salvage'] - $obj['monthlyDepreciation'] - $obj['depreciation'];
					if ($addArr[$key]['deprRemain'] < 0) {
						//剩余折旧额
						$addArr[$key]['deprRemain'] = '0';
						//本期计提折旧额
						$addArr[$key]['depr'] = $obj['origina'] - $obj['salvage'] - $obj['depreciation'];
					} else {
						//本期计提折旧额
						$addArr[$key]['depr'] = $obj['monthlyDepreciation'];
					}
					//本期应提折旧额
					$addArr[$key]['deprShould'] = $obj['monthlyDepreciation'];
					//期间
					$addArr[$key]['period'] = $obj['alreadyDay'] + 1;
					//年度
					$addArr[$key]['years'] = $localYear;
					//折旧日期
					$addArr[$key]['deprTime'] = $localDate;
					//资产原值
					$addArr[$key]['origina'] = $obj['origina'];
					//预计使用期间
					$addArr[$key]['estimateDay'] = $obj['estimateDay'];
					//预计净残值
					$addArr[$key]['salvage'] = $obj['salvage'];
				}
				//判断数据库是否存在本期的折旧，如果存在就先删除本数据，在新增新的折旧信息
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
						// 更新资产卡片的累计折旧和净值
						$assetDao->updateDepreciationReturn($arr[$key]['assetId'], $arr[$key]['depr']);
						$this->deletes($arr[$key]['id']);
					}
					//获取上一期的折旧数据
					$oldArray = $this->findLastMonBid($arr[$key]['deprTime'], $arr[$key]['assetId']);
					$arr[$key]['olddepr'] = $oldArray[0]['depr'];
					$arr[$key]['oldinitDepr'] = $oldArray[0]['initDepr'];
					$arr[$key]['olddeprRemain'] = $oldArray[0]['deprRemain'];
					$arr[$key]['oldperiod'] = $oldArray[0]['period'];
				}
				//重新获取卡片的信息
				foreach ($assetArr as $key => $obj) {
					//期初累计折旧值是不变的
					if ($arr[$key]['initDepr'] != "") {
						$addArr[$key]['initDepr'] = $arr[$key]['initDepr'];
						//								$addArr[$key]['initDepr'] = $addArr[$key]['initDepr']-$arr[$key]['deprShould'];
					} else
						if ($arr[$key]['oldinitDepr'] != "") {
							$addArr[$key]['initDepr'] = $arr[$key]['oldinitDepr'] + $arr[$key]['olddepr'];
						}
					//剩余折旧额是不变的
					if ($arr[$key]['deprRemain'] != "" || $arr[$key]['olddeprRemain'] != "") {
						$addArr[$key]['deprRemain'] = $addArr[$key]['origina'] - $addArr[$key]['salvage'] - $addArr[$key]['initDepr'] - $addArr[$key]['depr'];
					}
					//计算目前净值
					$addArr[$key]['localNetValue'] = $addArr[$key]['deprRemain'] + $addArr[$key]['salvage'];
					//期间
					if ($arr[$key]['period'] != "") {
						$addArr[$key]['period'] = $arr[$key]['period'];
					} else
						if ($arr[$key]['oldperiod'] != "") {
							$addArr[$key]['period'] = $arr[$key]['oldperiod'] + 1;
						}
				}
				//批量新增折旧信息
				$newArr = array ();
				foreach ($addArr as $key => $Obj) {
					$newArr[$key]['assetId'] = $addArr[$key]['assetId'];
					$newArr[$key]['origina'] = $addArr[$key]['origina'];
					$newArr[$key]['localNetValue'] = $addArr[$key]['localNetValue'];
					$assetDao = new model_asset_assetcard_assetcard();
					// 更新资产卡片的累计折旧和净值
					$assetDao->updateDepreciation($newArr[$key]['assetId'], $newArr[$key]['origina'], $newArr[$key]['localNetValue'], $addArr[$key]['period']);
					// 判断折旧完的资产，更新资产卡片的折旧状态
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
			// 先删除本期折旧的数据
			$oldArr = $this->findBid($object['deprTime'], $object['assetId']);
			//获取上一期的折旧数据
			$oldArray = $this->findLastMonBid($object['deprTime'], $object['assetId']);
			if ($oldArr != "") {
				$assetDao = new model_asset_assetcard_assetcard();
				// 更新资产卡片的累计折旧和净值
				$assetDao->updateDepreciationReturn($object['assetId'], $oldArr[0]['depr']);
				$this->deletes($oldArr[0]['id']);
			}
			//期初累计折旧
			if ($oldArr[0]['initDepr'] != "") {
				$object['initDepr'] = $oldArr[0]['initDepr'];
			} else
				if ($oldArray[0]['initDepr'] != "") {
					$object['initDepr'] = $oldArray[0]['initDepr'] + $oldArray[0]['depr'];
				}
			//剩余折旧额
			if ($oldArr[0]['deprRemain'] != "" || $oldArray[0]['deprRemain'] != "") {
				$object['deprRemain'] = $object['origina'] - $object['salvage'] - $object['initDepr'] - $object['depr'];
			}

			//计算目前净值
			$object['localNetValue'] = $object['deprRemain'] + $object['salvage'];
			//期间
			if ($oldArr[0]['period'] != "") {
				$object['period'] = $oldArr[0]['period'];
			} else
				if ($oldArray[0]['period'] != "") {
					$object['period'] = $oldArray[0]['period'] + 1;
				}
			// 再新增本期新的折旧信息
			try {
				$this->start_d();
				$assetDao = new model_asset_assetcard_assetcard();
				// 更新资产卡片的累计折旧和净值
				$assetDao->updateDepreciation($object['assetId'], $object['origina'], $object['localNetValue'], $object['period']);
				// 判断折旧完的资产，更新资产卡片的折旧状态
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
	 * 根据折旧时间deprTime，查找本期折旧信息
	 */
	function findBid($date, $assetId) {
		//获取指定日期所在月的第一天和获取指定日期下个月的第一天
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
	 * 根据折旧时间deprTime，查找上个月折旧信息
	 */
	function findLastMonBid($date, $assetId) {
		//获取指定日期的上个月的第一天和获取指定日期这个月的第一天
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
	* 当修改计提折旧额时，同时改变资产卡片的本期累积折旧和净值
	*/
	function edit_d($object, $isEditInfo = false) {
		$assetDao = new model_asset_assetcard_assetcard();
		// 更新资产卡片的累计折旧和净值
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