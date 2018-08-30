/**
 * ���ݿͻ���Ϣ��ȡ�������ü���������
 *
 * @param {}
 *            customerId
 * @param {}
 *            customerName
 */
function getAreaMoneyByCustomer(customerId, customerName) {
//	return;
	$.ajax({
		type : "post",
		url : '?model=projectmanagent_borrow_borrow&action=getAreaMoneyByCustomerId',
		data : {
			"customerId" : customerId
		},
		// dataType : 'json',
		success : function(data) {
			var area = eval("(" + data + ")");
			if (!area.areaMoney) {
				area.areaMoney = 0.00;
			}
			if (!area.maxMoney) {
				area.maxMoney = 0.00;
			}
			var msg = "<font color='red'>������ʾ:����ѡ��Ŀͻ���" + customerName + "������"
					+ area.areaName + "</br>�������Ϊ"
					+ moneyFormat2(area.maxMoney) + ";�����ۼƽ���Ѵ�"
					+ moneyFormat2(area.areaMoney) + "</font>";
			$("#msg").html(msg);
		}
	});
}

/**
 * ����Ա����ȡԱ���������ü���������
 *
 * @param {}
 *            customerId
 * @param {}
 *            customerName
 */
function getUserDeptMoneyByUser(userId, userName) {
//	return;
	$.ajax({
		type : "post",
		url : '?model=projectmanagent_borrow_borrow&action=getUserDeptMoneyByUserId',
		data : {
			"userId" : userId
		},
		// dataType : 'json',
		success : function(data) {
			var config = eval("(" + data + ")");
			var userMoney = config['userMoney'];
			var deptMoney = config['deptMoney'];
			if (!userMoney.borrowMoney) {
				userMoney.borrowMoney = 0.00;
			}
			if (!userMoney.maxMoney) {
				userMoney.maxMoney = 0.00;
			}
			if (!deptMoney.borrowMoney) {
				deptMoney.borrowMoney = 0.00;
			}
			if (!deptMoney.maxMoney) {
				deptMoney.maxMoney = 0.00;
			}
			var msg = "<font color='red'>������ʾ:</br>��ĸ���" + "�������Ϊ"
					+ moneyFormat2(userMoney.maxMoney) + ";���Ž������Ϊ"
					+ moneyFormat2(deptMoney.maxMoney) + "</br>���������ۼƽ���Ѵ�"
					+ moneyFormat2(userMoney.borrowMoney) + ";���������ۼƽ���Ѵ�"
					+ moneyFormat2(deptMoney.borrowMoney) + "</font>";
			$("#msg").html(msg);
		}
	});
}
