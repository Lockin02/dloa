<?php
/*
 * Created on 2012-4-12
 * Created by kuangzw
 * 工作流审批完成配置文件
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 *
 * 注,参数2为数据字典,如果有用到数据字典处理的必须有这个参数
 *
 */

//付款申请详细配置
function payablesapplyFun($rows, $datadictArr) {
	//主表信息带入
	$mainInfo = $rows[0];

	$mainStr = '单据id : ' . $mainInfo['id'] . ' , 申请单号: ' . $mainInfo['formNo'] . ' , 单据日期 : ' . $mainInfo['formDate'] .
		' , 付款单位 : ' . $mainInfo['supplierName'] . ' , 申请金额 ：' . $mainInfo['payMoney'];

	// 源单编号缓存
	$sourceNoCache = array();

	//从表信息带入
//	$detailStr = '<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef style="font-size:14"><tr><td>序号</td><td>业务类型</td><td>业务编号</td><td>申请金额</td><td>物料编号</td><td>物料名称</td></tr>';
	foreach ($rows as $key => $val) {
		if (!in_array($val['objCode'], $sourceNoCache)) {
			$sourceNoCache[] = $val['objCode'];
		}
//		$i = $key + 1;
//		$objTypeCN = getDataNameByCode_d($val['objType'], $datadictArr);
//		$detailStr .= <<<EOT
//			<tr><td>$i</td><td>$objTypeCN</td><td>$val[objCode]</td><td>$val[money]</td><td>$val[productNo]</td><td>$val[productName]</td></tr>
//EOT;
	}
//	$detailStr .= '</table>';
	$mainStr .= "，源单编号：" . implode(',', $sourceNoCache);

	return $mainStr;
}

//采购询价详细配置
function inquiryFun($rows, $datadictArr) {
	$str = '';
	//主表信息带入
	$mainInfo = $rows[0];
	$str .= '询价单号:<font color=red> ' . $mainInfo['inquiryCode'] . '</font>';

	if (is_array($rows)) {
		//从表信息带入
		$str .= '<table border=1 cellspacing=0  width=80% bordercolorlight=#333333 bordercolordark=#efefef style="font-size:14"><tr><td>序号</td><td>物料编号</td><td>物料名称</td><td>询价数量</td></tr>';

		foreach ($rows as $key => $val) {
			$i = $key + 1;
			$str .= <<<EOT
				<tr><td>$i</td><td>$val[productNumb]</td><td>$val[productName]</td><td>$val[amount]</td></tr>
EOT;
		}
		$str .= '</table>';
	}

	//    print_r($mainInfo);
	return $str;
}

//采购订单详细配置
function purchaseontractFun($rows, $datadictArr) {
	$str = '';
	//主表信息带入
	$mainInfo = $rows[0];
	$str .= '订单编号:<font color=blue> ' . $mainInfo['hwapplyNumb'] . '</font>';

	if (is_array($rows)) {
		//从表信息带入
		$str .= '<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>单位</b></td><td><b>订单数量</b></td><td><b>期望到货时间</b></td><td><b>到货时间</b></td><td><b>申请部门</b></td><td><b>源单编号</b></td></tr>';
		$j = 0;
		foreach ($rows as $key => $val) {
			$j++;
			$productName = $val['productName'];
			$pattem = $val ['pattem'];
			$unitName = $val ['units'];
			$amountAll = $val ['amountAll'];
			$dateIssued = $val ['dateIssued'];
			$dateHope = $val ['dateHope'];
			//						$purchTypeCn=$interfObj->typeKToC ( $val['purchType'] ); //类型名称
			$applyDeptName = $val['applyDeptName'];
			$sourceNumb = $val['sourceNumb'];
			$str .= <<<EOT
						<tr align="center" >
									<td>$j</td>
									<td>$productName</td>
									<td>$pattem</td>
									<td>$unitName</td>
									<td>$amountAll</td>
									<td>$dateIssued</td>
									<td>$dateHope</td>
									<td>$applyDeptName</td>
									<td>$sourceNumb</td>
								</tr>
EOT;
		}
		$str .= '</table>';
	}
	return $str;
}

//推荐奖励审批后回调函数
function recommendbonusFun($rows, $datadictArr) {
	$str = '';
	//主表信息带入
	$rows = $rows[0];

	$str .= '内部推荐奖励:<font color=blue> ' . $rows['formCode'] . '</font>';
	//从表信息带入
	$str .= '<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center >
		<td><b>填表人</b></td>
		<td><b>被荐人</b></td>
		<td><b>简历编号</b></td>
		<td><b>职位</b></td>
		<td><b>入职日期</b></td>
		<td><b>转正日期</b>
		</td><td><b>第一次单发时间</b></td>
		<td><b>第一次待发奖金</b></td>
		<td><b>第二次待发时间</b></td>
		<td><b>第二次待发奖金</b></td>
		</tr>';
	$formManName = $rows['formManName'];
	$isRecommendName = $rows['isRecommendName'];
	$resumeCode = $rows['resumeCode'];
	$positionName = $rows['jobName'];
	$becomeDate = $rows['becomeDate'];
	$entryDate = $rows['entryDate'];
	$firstGrantDate = $rows['firstGrantDate'];
	$firstGrantBonus = $rows['firstGrantBonus'];
	$secondGrantDate = $rows['secondGrantDate'];
	$secondGrantBonus = $rows['secondGrantBonus'];
	$str .= <<<EOT
		<tr align="center" >
					<td>$formManName</td>
					<td>$isRecommendName</td>
					<td>$resumeCode</td>
					<td>$positionName</td>
					<td>$entryDate</td>
					<td>$becomeDate</td>
					<td>$firstGrantDate</td>
					<td>$firstGrantBonus</td>
					<td>$secondGrantDate</td>
					<td>$secondGrantBonus</td>
				</tr>
EOT;
	$str .= '</table>';
	//echo $str;
	return $str;
}

//付款申请详细配置
function stockUpProductsFun($rows, $datadictArr) {
	$str = '';
	//主表信息带入
	$mainInfo = $rows[0];
	$str .= '单据id : ' . $mainInfo['id'] . ' , 申请编号: ' . $mainInfo['listNo'] . ' , 项目名称 : ' . $mainInfo['projectName'] . ' , 申请人 : ' . $mainInfo['appUserName'] . ' , 说明 ：' . $mainInfo['description'];

	//从表信息带入
	$str .= '<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef style="font-size:14"><tr><td>序号</td><td>物料名称（产品）</td><td>数量</td><td>产品配置</td><td>期望交付日期</td><td>备注</td></tr>';
	foreach ($rows as $key => $val) {
		$i = $key + 1;
		$str .= <<<EOT
			<tr><td>$i</td><td>$val[productName]</td><td>$val[productNum]</td><td>$val[productConfig]</td><td>$val[exDeliveryDate]</td><td>$val[remark]</td></tr>
EOT;
	}
	$str .= '</table>';

	//    print_r($rows);
	return $str;
}

//调岗申请详细配置
function transferJobFun($rows, $datadictArr) {
	$str = '';
	//主表信息带入
	$mainInfo = $rows[0];

	$str .= '调岗人员姓名 : ' . $mainInfo['userName'] . ' , 申请单号: ' . $mainInfo['formCode'] . ' , 现所在岗位 : ' . $mainInfo['preJobName'] . ' , 调动后岗位 : ' . $mainInfo['afterJobName'] . ' , 申请日期 ：' . $mainInfo['applyDate'];

	//	echo "<pre>";
	//    print_r($rows);
	return $str;
}

//机票申请详细配置
function flightsRequireFun($rows, $datadictArr) {
	$str = '';
	//主表信息带入
	$mainInfo = $rows[0];

	$str .= '申请人 : ' . $mainInfo['requireName'] . ' , 申请日期 : ' . $mainInfo['requireTime'] . ' , 乘机人 : ' . $mainInfo['airName'] . ' , 出发日期 : ' . $mainInfo['startDate'] . ' , 出发城市 ：' . $mainInfo['startPlace'] . ' , 到达城市 ：' . $mainInfo['endPlace'];

	return $str;
}

/*************** 共用部分 ----------- 不要改 ******************/
function getDataNameByCode_d($key, $datadictArr) {

	if (isset($datadictArr[$key])) {
		return $datadictArr[$key];
	} else {
		return $key;
	}
}