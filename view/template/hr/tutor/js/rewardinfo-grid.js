var show_page = function(page) {
	$("#rewardinfoGrid").yxgrid("reload");
};
$(function() {
			$("#rewardinfoGrid").yxgrid({
						model : 'hr_tutor_rewardinfo',
						title : '��ʦ��������--��ϸ',
						isOpButton : false,
						bodyAlign:'center',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'userNo',
									display : '��ʦԱ�����',
									sortable : true
								}, {
									name : 'userAccount',
									display : '��ʦԱ���˺�',
									sortable : true
								}, {
									name : 'userName',
									display : '��ʦ����',
									sortable : true
								}, {
									name : 'assessmentScore',
									display : '���˷���',
									sortable : true
								}, {
									name : 'studentNo',
									display : 'ѧԱԱ�����',
									sortable : true
								}, {
									name : 'studentAccount',
									display : 'ѧԱԱ���˺�',
									sortable : true
								}, {
									name : 'studentName',
									display : 'ѧԱ����',
									sortable : true
								}, {
									name : 'tryEndDate',
									display : 'ת������',
									sortable : true
								}, {
									name : 'rewardPrice',
									display : '��Ʒ�۸�',
									sortable : true
								}, {
									name : 'situation',
									display : '�����������',
									sortable : true
								}],
						// ���ӱ������
						subGridOptions : {
							url : '?model=hr_tutor_NULL&action=pageItemJson',
							param : [{
										paramId : 'mainId',
										colId : 'id'
									}],
							colModel : [{
										name : 'XXX',
										display : '�ӱ��ֶ�'
									}]
						},

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : "�����ֶ�",
									name : 'XXX'
								}]
					});
		});