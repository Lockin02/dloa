$(document).ready(function() {

	// 导师奖励明细
	$("#rewardList").yxeditgrid({
		objName : 'reward[rewardinfo]',
		tableClass : 'form_in_table',
		url : '?model=hr_tutor_rewardinfo&action=listJson',
		param : {
			'rewardId' : $("#id").val()
		},
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
					process : function(v, row) {
						return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=hr_tutor_scheme&action=toView&id='
								+ row.tutorId
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
					},
					width : '15%'
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
					width : '20%',
					isSubmit : true
				}, {
					display : '导师考核分数',
					name : 'assessmentScore',
					type : 'statictext',
					width : '10%'
				}, {
					display : '新员工',
					name : 'studentName',
					type : 'statictext',
					width : '15%'
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
					width : '10%'
				}, {
					display : '奖 励(元)',
					name : 'rewardPrice',
					type : 'statictext',
					width : '10%'
				}, {
					display : '是否已发放',
					name : 'isGrant',
					type:'checkbox',
					checkVal:'1'
				}, {
					display : '进度备注',
					name : 'scheduleRemark',
					type : 'text',
					width : '10%'
				}]
	});
})