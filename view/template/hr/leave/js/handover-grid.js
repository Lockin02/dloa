var show_page = function(page) {
	$("#handoverGrid").yxgrid("reload");
};
$(function() {
			$("#handoverGrid").yxgrid({
						model : 'hr_leave_handover',
						title : '��ְ�����嵥',
						isOpButton : false,
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'userNo',
									display : 'Ա�����',
									sortable : true
								}, {
									name : 'userAccount',
									display : 'Ա���˺�',
									sortable : true
								}, {
									name : 'userName',
									display : 'Ա������',
									sortable : true
								}, {
									name : 'deptName',
									display : '��������',
									sortable : true
								}, {
									name : 'deptId',
									display : '����Id',
									sortable : true
								}, {
									name : 'jobName',
									display : 'ְλ����',
									sortable : true
								}, {
									name : 'entryDate',
									display : '��ְ����',
									sortable : true
								}, {
									name : 'quitDate',
									display : '��ְ����',
									sortable : true
								}, {
									name : 'quitReson',
									display : '��ְԭ��',
									sortable : true
								}, {
									name : 'quitTypeCode',
									display : '��ְ����',
									sortable : true
								}, {
									name : 'quitTypeName',
									display : '��ְ��������',
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
								}, {
									name : 'Column_21',
									display : 'Column_21',
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