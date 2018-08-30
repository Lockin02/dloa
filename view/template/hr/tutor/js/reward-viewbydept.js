$(document).ready(function() {

	// 导师奖励明细
	$("#rewardList").yxeditgrid({
		objName : 'reward[rewardinfo]',
		tableClass : 'form_in_table',
		url : '?model=hr_tutor_rewardinfo&action=listJson',
		param : {
			'rewardId' : $("#id").val(),
			'tutorDeptId' : $("#deptId").val()
		},
		type : 'view',
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
					width : '10%',
					process:function(v){
						if($('#isPublish').val==1){
							return v;
						}
					}
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
					width : '10%'
				}, {
					display : '奖 励(元)',
					name : 'rewardPrice',
					width : '10%',
					process:function(v){
						if($('#isPublish').val==1){
							return v;
						}
					}
				}]
	});
})