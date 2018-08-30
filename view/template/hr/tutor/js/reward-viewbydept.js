$(document).ready(function() {

	// ��ʦ������ϸ
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
					display : '��ʦ����ID',
					name : 'tutorId',
					type : 'hidden'
				}, {
					display : '��ʦ',
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
					display : '��ʦ�˺�',
					name : 'userAccount',
					type : 'hidden'
				}, {
					display : '��ʦ���',
					name : 'userNo',
					type : 'hidden'
				}, {
					display : '��ʦ����id',
					name : 'tutorDeptId',
					type : 'hidden'
				}, {
					display : '��ʦ����',
					name : 'tutorDeptName',
					type : 'statictext',
						width : '20%',
					isSubmit : true
				}, {
					display : '��ʦ���˷���',
					name : 'assessmentScore',
					width : '10%',
					process:function(v){
						if($('#isPublish').val==1){
							return v;
						}
					}
				}, {
					display : '��Ա��',
					name : 'studentName',
					type : 'statictext',
					width : '15%'
				}, {
					display : '��Ա���˺�',
					name : 'studentAccount',
					type : 'hidden'
				}, {
					display : '��Ա�����',
					name : 'studentNo',
					type : 'hidden'
				}, {
					display : 'ת������',
					name : 'tryEndDate',
					width : '10%'
				}, {
					display : '�� ��(Ԫ)',
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