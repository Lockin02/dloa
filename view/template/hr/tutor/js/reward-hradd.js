$(document).ready(function() {
	/**
	 * ��֤��Ϣ
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
	$("#name").val(year + '��' + month + '�µ�ʦ��������');

	// ��ʦ������ϸ
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
					display : '��ʦ����ID',
					name : 'tutorId',
					type : 'hidden'
				}, {
					display : '��ʦ',
					name : 'userName',
					type : 'statictext',
					isSubmit : true
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
					isSubmit : true
				}, {
					display : '��ʦ���˷���',
					name : 'assessmentScore',
					type : 'statictext',
					isSubmit : true
				}, {
					display : '��Ա��',
					name : 'studentName',
					type : 'statictext',
					isSubmit : true
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
					type : 'statictext',
					isSubmit : true
				}, {
					display : '�� ��(Ԫ)',
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
							input.val("��");
						}
					}
				}]
	});
})