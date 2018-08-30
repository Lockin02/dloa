var show_page = function(page) {
	$("#attrGrid").yxgrid("reload");
};
$(function() {

			$("#attrGrid").yxgrid({

						model : 'hr_inventory_attr&action=page',

						title : '盘点表属性',
						toViewConfig : {
							action : "toView"
						},toEditConfig : {
							action : "toEdit"
						},
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'attrName',
									display : '属性名称',
									sortable : true
								}, {
									name : 'attrType',
									display : '属性类型',
									process : function(row, v) {
										v = v == 0 ? "文本框" : "下拉框";
										return v;
									},
									sortable : true
								}, {
									name : 'remark',
									display : '备注',
									sortable : true
								}],
						searchitems : [{
									display : "属性名称",
									name : 'attrName'
								}],
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"

					});
		});