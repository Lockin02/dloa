$(document).ready(function() {
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"name" : {
			required : true
		}
	});


// ��ʦ������ϸ
$("#rewardList").yxeditgrid({
	objName : 'reward[rewardinfo]',
	tableClass : 'form_in_table',
	url : '?model=hr_tutor_rewardinfo&action=listJson',
	param : {'rewardId' : $("#id").val()},
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
		isSubmit : true,
		process : function(v,row){
		   return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=hr_tutor_scheme&action=toView&id='
									+ row.tutorId
									+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
									+ "<font color = '#4169E1'>"
									+ v + "</font>" + '</a>';
		}
	}, {
		display : '��ʦ�˺�',
		name : 'userAccount',
		type : 'hidden'
	}, {
		display : '��ʦ���',
		name : 'userNo',
		type : 'hidden'
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
	}, {
		display : '�����������',
		name : 'situation'
	}]
});
})