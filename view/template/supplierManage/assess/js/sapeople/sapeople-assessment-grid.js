// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".assessGrid").yxgrid("reload");
};
$(function() {
	var assId = $("#assId").val();
	$(".assessGrid").yxgrid({
		url : '?model=supplierManage_assess_sapeople&action=assessment&pj=1&assesId='+$("#assId").val(),
		model : 'supplierManage_assess_sapeople',
		action : 'assessment',
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		// 列信息
		colModel : [{
					display : '供应商Id',
					name : 'ssuppId',
					sortable : true,
					hide : true
				}, {
					display : '供应商对象Id',
					name : 'suppPjId',
					sortable : true,
					hide : true
				}, {
					display : '方案Id',
					name : 'assesId',
					sortable : true,
					hide : true
				}, {
					display : '供应商名称',
					name : 'ssuppName',
					sortable : true
				}, {
					display : '当前级别',
					name : 'ssuppLevelC',
					sortable : true,
					width : 200
				}],
		// 扩展按钮
		buttonsEx : [{
					name : 'saaStart',
					text : "评估",
					icon : 'edit',
					action : function(row,rows,grid) {
						if( row ){
								var url="?model=supplierManage_assess_sapeople&action=assessmentToWork&suppId="+row.suppPjId+"&assesId="+row.assesId;
								showThickboxWin(url + "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
						}else{
							alert("请选中一条数据");
						}
					}
				}, {
					separator : true
				}],
		menusEx : [{
					text : '评估',
					icon : 'edit',
					action : function(row,rows,grid) {
						if( row ){
								var url="?model=supplierManage_assess_sapeople&action=assessmentToWork&suppId="+row.suppPjId+"&assesId="+row.assesId;
								showThickboxWin(url + "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
						}else{
							alert("请选中一条数据");
						}
					}
				}],
		// title : '客户信息',
		// 业务对象名称
		boName : '评估方案-供应商',
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序
		sortorder : "ASC",
		toViewConfig : {
				text : '查看供应商',
				toViewFn : function(p, g) {
					var rowObj = g.getSelectedRow();
					if (rowObj) {
						showThickboxWin("?model=supplierManage_formal_flibrary&action=toRead&id="
							+ rowObj.data('data').ssuppId
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=950");
					} else {
						alert('请选择一行记录！');
					}
				}
			}
	});
});