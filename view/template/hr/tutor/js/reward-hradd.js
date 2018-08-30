$(document).ready(function() {
	/**
	 * 验证信息
	 */
	validate({
				"name" : {
					required : true
				}
			});

	var myDate = new Date();
	var year = myDate.getFullYear();
	var month = myDate.getMonth() + 1;
	if (month < 10)
		month = "0" + month;
	$("#name").val(year + '年' + month + '月导师奖励方案');

	// 导师奖励明细
	$("#rewardList").yxeditgrid({
		objName : 'reward[rewardinfo]',
		tableClass : 'form_in_table',
		url : '?model=hr_tutor_reward&action=rewardInfo',
		isAdd : false,
		isAddOneRow : false,
		colModel : [{
					display : 'id',
					name : 'id',
					type : 'hidden'
				}, {
					display : '导师管理ID',
					name : 'tutorId',
					type : 'hidden'
				}, {
					display : '导师',
					name : 'userName',
					type : 'statictext',
					isSubmit : true
				}, {
					display : '导师账号',
					name : 'userAccount',
					type : 'hidden'
				}, {
					display : '导师编号',
					name : 'userNo',
					type : 'hidden'
				}, {
					display : '导师部门id',
					name : 'tutorDeptId',
					type : 'hidden'
				}, {
					display : '导师部门',
					name : 'tutorDeptName',
					type : 'statictext',
					isSubmit : true
				}, {
					display : '导师考核分数',
					name : 'assessmentScore',
					type : 'statictext',
					isSubmit : true
				}, {
					display : '新员工',
					name : 'studentName',
					type : 'statictext',
					isSubmit : true
				}, {
					display : '新员工账号',
					name : 'studentAccount',
					type : 'hidden'
				}, {
					display : '新员工编号',
					name : 'studentNo',
					type : 'hidden'
				}, {
					display : '转正日期',
					name : 'tryEndDate',
					type : 'statictext',
					isSubmit : true
				}, {
					display : '奖 励(元)',
					name : 'rewardPrice',
					tclass : 'txtshort',
					process : function(input, row) {
						if (row.assessmentScore >= 80
								&& row.assessmentScore < 85) {
							input.val("100");
						} else if (row.assessmentScore >= 85
								&& row.assessmentScore < 90) {
							input.val("200");
						} else if (row.assessmentScore >= 90) {
							input.val("400");
						} else {
							input.val("无");
						}
					}
				}]
	});
})