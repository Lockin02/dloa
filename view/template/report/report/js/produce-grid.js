var show_page = function(page) {
	$("#produceGrid").yxgrid("reload");
};
$(function() {
			$("#produceGrid").yxgrid({
						model : 'report_report_produce',
						title : '生产能力表',
						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'name',
									display : '产品名',
									sortable : true,
									width : 180
								}, {
									name : 'remark',
									display : '备注',
									sortable : true,
									width : 220
								}, {
									name : 'createName',
									display : '创建人',
									sortable : true
								}, {
									name : 'createTime',
									display : '创建日期',
									sortable : true
								}],

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : "产品名",
									name : 'name'
								}]
					});
		});