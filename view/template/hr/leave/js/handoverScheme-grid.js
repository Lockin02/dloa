var show_page = function(page) {
	$("#handoverSchemeGrid").yxgrid("reload");
};
$(function() {
			$("#handoverSchemeGrid").yxgrid({
						model : 'hr_leave_handoverScheme',
						title : '离职清单模板方案',
						isOpButton : false,
						bodyAlign:'center',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'schemeName',
									display : '方案名称',
									width:'200',
									sortable : true
								}, {
									name : 'jobName',
									display : '职位名称',
									sortable : true
								}, {
									name : 'companyName',
									display : '编制（公司）',
									sortable : true
								}, {
									name : 'leaveTypeName',
									display : '离职类型',
									width:'100',
									sortable : true
								}, {
									name : 'remark',
									display : '备注',
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
									display : "方案名称",
									name : 'schemeName'
								}]
					});
		});