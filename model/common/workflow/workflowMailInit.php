<?php
/*
 * Created on 2012-4-12
 * Created by kuangzw
 * ������������������ļ�
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 *
 * ע,����2Ϊ�����ֵ�,������õ������ֵ䴦��ı������������
 *
 */

//����������ϸ����
function payablesapplyFun($rows, $datadictArr) {
	//������Ϣ����
	$mainInfo = $rows[0];

	$mainStr = '����id : ' . $mainInfo['id'] . ' , ���뵥��: ' . $mainInfo['formNo'] . ' , �������� : ' . $mainInfo['formDate'] .
		' , ���λ : ' . $mainInfo['supplierName'] . ' , ������ ��' . $mainInfo['payMoney'];

	// Դ����Ż���
	$sourceNoCache = array();

	//�ӱ���Ϣ����
//	$detailStr = '<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef style="font-size:14"><tr><td>���</td><td>ҵ������</td><td>ҵ����</td><td>������</td><td>���ϱ��</td><td>��������</td></tr>';
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
	$mainStr .= "��Դ����ţ�" . implode(',', $sourceNoCache);

	return $mainStr;
}

//�ɹ�ѯ����ϸ����
function inquiryFun($rows, $datadictArr) {
	$str = '';
	//������Ϣ����
	$mainInfo = $rows[0];
	$str .= 'ѯ�۵���:<font color=red> ' . $mainInfo['inquiryCode'] . '</font>';

	if (is_array($rows)) {
		//�ӱ���Ϣ����
		$str .= '<table border=1 cellspacing=0  width=80% bordercolorlight=#333333 bordercolordark=#efefef style="font-size:14"><tr><td>���</td><td>���ϱ��</td><td>��������</td><td>ѯ������</td></tr>';

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

//�ɹ�������ϸ����
function purchaseontractFun($rows, $datadictArr) {
	$str = '';
	//������Ϣ����
	$mainInfo = $rows[0];
	$str .= '�������:<font color=blue> ' . $mainInfo['hwapplyNumb'] . '</font>';

	if (is_array($rows)) {
		//�ӱ���Ϣ����
		$str .= '<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>��������</b></td><td><b>����ͺ�</b></td><td><b>��λ</b></td><td><b>��������</b></td><td><b>��������ʱ��</b></td><td><b>����ʱ��</b></td><td><b>���벿��</b></td><td><b>Դ�����</b></td></tr>';
		$j = 0;
		foreach ($rows as $key => $val) {
			$j++;
			$productName = $val['productName'];
			$pattem = $val ['pattem'];
			$unitName = $val ['units'];
			$amountAll = $val ['amountAll'];
			$dateIssued = $val ['dateIssued'];
			$dateHope = $val ['dateHope'];
			//						$purchTypeCn=$interfObj->typeKToC ( $val['purchType'] ); //��������
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

//�Ƽ�����������ص�����
function recommendbonusFun($rows, $datadictArr) {
	$str = '';
	//������Ϣ����
	$rows = $rows[0];

	$str .= '�ڲ��Ƽ�����:<font color=blue> ' . $rows['formCode'] . '</font>';
	//�ӱ���Ϣ����
	$str .= '<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center >
		<td><b>�����</b></td>
		<td><b>������</b></td>
		<td><b>�������</b></td>
		<td><b>ְλ</b></td>
		<td><b>��ְ����</b></td>
		<td><b>ת������</b>
		</td><td><b>��һ�ε���ʱ��</b></td>
		<td><b>��һ�δ�������</b></td>
		<td><b>�ڶ��δ���ʱ��</b></td>
		<td><b>�ڶ��δ�������</b></td>
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

//����������ϸ����
function stockUpProductsFun($rows, $datadictArr) {
	$str = '';
	//������Ϣ����
	$mainInfo = $rows[0];
	$str .= '����id : ' . $mainInfo['id'] . ' , ������: ' . $mainInfo['listNo'] . ' , ��Ŀ���� : ' . $mainInfo['projectName'] . ' , ������ : ' . $mainInfo['appUserName'] . ' , ˵�� ��' . $mainInfo['description'];

	//�ӱ���Ϣ����
	$str .= '<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef style="font-size:14"><tr><td>���</td><td>�������ƣ���Ʒ��</td><td>����</td><td>��Ʒ����</td><td>������������</td><td>��ע</td></tr>';
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

//����������ϸ����
function transferJobFun($rows, $datadictArr) {
	$str = '';
	//������Ϣ����
	$mainInfo = $rows[0];

	$str .= '������Ա���� : ' . $mainInfo['userName'] . ' , ���뵥��: ' . $mainInfo['formCode'] . ' , �����ڸ�λ : ' . $mainInfo['preJobName'] . ' , �������λ : ' . $mainInfo['afterJobName'] . ' , �������� ��' . $mainInfo['applyDate'];

	//	echo "<pre>";
	//    print_r($rows);
	return $str;
}

//��Ʊ������ϸ����
function flightsRequireFun($rows, $datadictArr) {
	$str = '';
	//������Ϣ����
	$mainInfo = $rows[0];

	$str .= '������ : ' . $mainInfo['requireName'] . ' , �������� : ' . $mainInfo['requireTime'] . ' , �˻��� : ' . $mainInfo['airName'] . ' , �������� : ' . $mainInfo['startDate'] . ' , �������� ��' . $mainInfo['startPlace'] . ' , ������� ��' . $mainInfo['endPlace'];

	return $str;
}

/*************** ���ò��� ----------- ��Ҫ�� ******************/
function getDataNameByCode_d($key, $datadictArr) {

	if (isset($datadictArr[$key])) {
		return $datadictArr[$key];
	} else {
		return $key;
	}
}