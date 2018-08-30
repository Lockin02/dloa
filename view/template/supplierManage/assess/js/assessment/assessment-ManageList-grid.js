// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".assessGrid").yxgrid("reload");
};
$(function() {
	var assId = $("#assId").val();
	$(".assessGrid").yxgrid({
		model : 'supplierManage_assess_assessment',
		action : 'saaPJManage',
		//isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		isViewAction:false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '评估方案名称',
					name : 'assesName',
					sortable : true,
					width : 200
				}, {
					display : '发起人',
					name : 'createName',
					sortable : true
				}, {
					display : '评估类型',
					name : 'assesTypeName',
					sortable : true,
					width : 150
				}, {
					display : '评估预计开始日期',
					name : 'beginDate',
					sortable : true,
					width : 150
				}, {
					display : '评估预计结束日期',
					name : 'endDate',
					sortable : true,
					width : 150
				}, {
					display : '评估创建时间',
					name : 'createTime',
					sortable : true,
					width : 170
				}, {
					display : '状态',
					name : 'statusC',
					sortable : true,
					width : 120
				}],
		// 快速搜索
		searchitems : [{
					display : '评估方案名称',
					name : 'assesName',
					isdefault : true
				}, {
					display : '发起人',
					name : 'createName'
				}],
		// title : '客户信息',
		// 业务对象名称
		boName : '评估方案',
		// 默认搜索字段名
		sortname : "updateTime",
		// 默认搜索顺序
		sortorder : "DESC",
		//扩展右键菜单
		menusEx : [{
					text : '查看',
					icon : 'view',
					action : function(row,rows,grid) {
						if(row){
						showOpenWin("?model=supplierManage_assess_assessment&action=saaViewTab&assId="+row.id+"&skey="+row['skey_'] );
						}else{
						   alert("请选中一条数据");
						}
					}

				}
		]
	});
});