var show_page = function(page) {
	$("#interviewDetailGrid").yxgrid("reload");
};
$(function() {
			$("#interviewDetailGrid").yxgrid({
						model : 'hr_leave_interviewDetail',
						title : '��ְ--��̸��¼����ϸ',
						isOpButton : false,
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'interviewer',
									display : '��̸��',
									sortable : true
								}, {
									name : 'interviewerId',
									display : '��̸��ID',
									sortable : true
								}, {
									name : 'interviewContent',
									display : '��̸����',
									sortable : true
								}, {
									name : 'interviewDate',
									display : '��̸����',
									sortable : true
								}, {
									name : 'createName',
									display : '������',
									sortable : true
								}, {
									name : 'createId',
									display : '������ID',
									sortable : true
								}, {
									name : 'createTime',
									display : '����ʱ��',
									sortable : true
								}, {
									name : 'updateName',
									display : '�޸���',
									sortable : true
								}, {
									name : 'updateId',
									display : '�޸���ID',
									sortable : true
								}, {
									name : 'updateTime',
									display : '�޸�ʱ��',
									sortable : true
								}],
						// ���ӱ������
						subGridOptions : {
							url : '?model=hr_leave_NULL&action=pageItemJson',
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