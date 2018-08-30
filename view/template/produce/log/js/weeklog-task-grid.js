var show_page = function(page) {
	$("#weeklogGrid").yxgrid("reload");
};
$(function() {
	$("#weeklogGrid")
			.yxgrid(
					{
						model : 'produce_log_weeklog',
						title : '�����ܱ�',
						showcheckbox : false,
						isAddAction : false,
						isEditAction : false,
						isDelAction : false,
						// ����Ϣ
						param : {
							createId : $('#hidden')[0].value
						},
						colModel : [
								{
									display : '���',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									name : 'weekTitle',
									display : '����',
									sortable : true,
									process : function(v, row) {
										return "<a href='?model=produce_log_worklog&action=page&id='"
												+ v.id
												+ ">"
												+ v
												+ "( "
												+ row.weekBeginDate
												+ "~"
												+ row.weekEndDate + " )</>";
									},
									width : 350

								}, {
									name : 'depName',
									display : '��������',
									sortable : true,
									width : 250

								}, {
									name : 'updateTime',
									display : '�������ʱ��',
									sortable : true,
									width : 250
								} ],

						isAddAction : false,
						isDelAction : false,
						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [ {
							display : "����",
							name : 'depName'
						} ]
					});
});