var show_page = function(page) {
	$("#handoverSchemeGrid").yxgrid("reload");
};
$(function() {
			$("#handoverSchemeGrid").yxgrid({
						model : 'hr_leave_handoverScheme',
						title : '��ְ�嵥ģ�巽��',
						isOpButton : false,
						bodyAlign:'center',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'schemeName',
									display : '��������',
									width:'200',
									sortable : true
								}, {
									name : 'jobName',
									display : 'ְλ����',
									sortable : true
								}, {
									name : 'companyName',
									display : '���ƣ���˾��',
									sortable : true
								}, {
									name : 'leaveTypeName',
									display : '��ְ����',
									width:'100',
									sortable : true
								}, {
									name : 'remark',
									display : '��ע',
									width : 250,
									sortable : true
								}],

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : "��������",
									name : 'schemeName'
								}]
					});
		});