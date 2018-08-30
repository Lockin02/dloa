/**
 * 根据客户信息获取区域设置及已申请金额
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
			var msg = "<font color='red'>友情提示:你所选择的客户【" + customerName + "】属于"
					+ area.areaName + "</br>金额上限为"
					+ moneyFormat2(area.maxMoney) + ";申请累计金额已达"
					+ moneyFormat2(area.areaMoney) + "</font>";
			$("#msg").html(msg);
		}
	});
}

/**
 * 根据员工获取员工部门设置及已申请金额
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
			var msg = "<font color='red'>友情提示:</br>你的个人" + "金额上限为"
					+ moneyFormat2(userMoney.maxMoney) + ";部门金额上限为"
					+ moneyFormat2(deptMoney.maxMoney) + "</br>个人申请累计金额已达"
					+ moneyFormat2(userMoney.borrowMoney) + ";部门申请累计金额已达"
					+ moneyFormat2(deptMoney.borrowMoney) + "</font>";
			$("#msg").html(msg);
		}
	});
}
