var show_page = function(page) {
	$("#weeklogGrid").yxgrid("reload");
};
$(function() {
	$("#weeklogGrid")
			.yxgrid(
					{
						model : 'produce_log_weeklog',
						title : '工作周报',
						showcheckbox : false,
						isAddAction : false,
						isEditAction : false,
						isDelAction : false,
						// 列信息
						param : {
							createId : $('#hidden')[0].value
						},
						colModel : [
								{
									display : '序号',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									name : 'weekTitle',
									display : '名称',
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
									display : '所属部门',
									sortable : true,
									width : 250

								}, {
									name : 'updateTime',
									display : '最近更新时间',
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
							display : "部门",
							name : 'depName'
						} ]
					});
});