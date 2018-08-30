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
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
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
					width : 200,
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
		// 扩展按钮
		buttonsEx : [{
					name : 'vv',
					text : "查看评估结果",
					icon : 'view',
					action : function(row,rows,grid) {
						var viewPerm = parent.document.getElementById("viewPerm").value;
						if(viewPerm=="1"){
							if( row  ){
								showThickboxWin("?model=supplierManage_assess_norm&action=sanViewNormPeo&suppPjId="
									+ row.id+"&assId="+assId
									+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
							}else{
								alert("请先选中一条数据");
							}
						}else{
							alert("你没有权限查看本页面");
						}
					}
				}],
		menusEx : [{
					text : '查看评估结果',
					icon : 'view',
					action : function(row,rows,grid) {
						var viewPerm = parent.document.getElementById("viewPerm").value;
						if(viewPerm=="1"){
							if( row  ){
								showThickboxWin("?model=supplierManage_assess_norm&action=sanViewNormPeo&suppPjId="
									+ row.id+"&assId="+assId
									+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
							}else{
								alert("请先选中一条数据");
							}
						}else{
							alert("你没有权限查看本页面");
						}
					}
				},{
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

		}
//		toViewConfig : {
//			text : '查看供应商',
//			toViewFn : function(p, g) {
//				var rowObj = g.getSelectedRow();
//				if (rowObj) {
//					showThickboxWin("?model=supplierManage_formal_flibrary&action=toRead&id="
//							+ rowObj.data('data').suppId
//							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
//				} else {
//					alert('请选择一行记录！');
//				}
//			}
//		},
	});
});