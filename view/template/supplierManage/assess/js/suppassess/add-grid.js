// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".suppassessGrid").yxgrid("reload");
};
$(function() {
	var assId = $("#assId").val();
	$(".suppassessGrid").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		url : '?model=supplierManage_assess_suppassess&action=sasPageJson&assId='+assId,
		model : 'supplierManage_assess_suppassess',
		isEditAction : false,
		showcheckbox : true,
		isViewAction:false,
		// action : 'pageJson',
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : 'suppId',
					name : '供应商Id',
					sortable : true,
					hide : true
				}, {
					display : '供应商名称',
					name : 'suppName',
					sortable : true,
					width : 500,
					// 特殊处理字段函数
					process : function(v, row) {
						return row.suppName;
					}
				}, {
					display : '简称',
					name : 'lshortName',
					sortable : true
				}, {
					display : '负责人',
					name : 'lmanageUserName',
					sortable : true
				}, {
					display : '公司性质',
					name : 'lcompanyNature',
					sortable : true
				}, {
					display : '公司规模',
					name : 'lcompanySize',
					sortable : true
				}],
		// title : '客户信息',
		// 业务对象名称
		boName : '供应商',
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序
		sortorder : "ASC",
		// 添加扩展信息
		toAddConfig : {
			text : '添加供应商',
			/**
			 * 新增表单调用的后台方法
			 */
			action : 'sasToAdd',
			/**
			 * 其他参数
			 */
			plusUrl : '&assId='+assId
			/**
			 * 新增表单默认宽度
			 */
			//formWidth : 800,
			/**
			 * 新增表单默认高度
			 */
			//formHeight : 600

		},
		menusEx : [{
					text : '查看供应商',
					icon : 'view',
					action : function(row,rows,grid) {
						if(row){
							showThickboxWin("?model=supplierManage_formal_flibrary&action=toRead&id="
								+ row.suppId+"&skey="+row['skey_']
								+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1000");
						}else{
						   alert("请选中一条数据");
						}
					}

				}]
	});
});