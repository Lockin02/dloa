var show_page = function(page) {
	$("#logsettingGrid").yxgrid("reload");
};
$(function() {
			$("#logsettingGrid").yxgrid({
						model : 'syslog_setting_logsetting',
						title : 'ϵͳ��־����',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'tableName',
									display : '����',
									sortable : true,
									width : 200
								}, {
									name : 'businessName',
									display : 'ҵ������',
									sortable : true,
									width : 200
								}, {
									name : 'pkName',
									display : 'ҵ�������ֶ���',
									sortable : true
								}, {
									name : 'createName',
									display : '������',
									sortable : true
								}, {
									name : 'createTime',
									display : '��������',
									sortable : true
								}, {
									name : 'updateName',
									display : '�޸���',
									sortable : true
								}, {
									name : 'updateTime',
									display : '�޸�����',
									sortable : true
								}],
						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : "����",
									name : 'tableName'
								}, {
									display : "ҵ������",
									name : 'businessName'
								}]
					});
		});