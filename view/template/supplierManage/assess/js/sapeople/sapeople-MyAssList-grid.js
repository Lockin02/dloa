// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".assessGrid").yxgrid("reload");
};
$(function() {
	var assId = $("#assId").val();
	$(".assessGrid").yxgrid({
		url : '?model=supplierManage_assess_sapeople&action=sapMyAssList&pj=1',
		model : 'supplierManage_assess_sapeople',
		action : 'sapMyAssList',
		title :'我参与的评估方案',
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		isToolBar : false,
		showcheckbox : false,
		// 列信息
		colModel : [{
					display : '评估方案Id',
					name : 'aid',
					sortable : true,
					hide : true
				}, {
					display : '人员Id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					display : '评估方案名称',
					name : 'aassesName',
					sortable : true,
					width : 200
				}, {
					display : '发起人',
					name : 'acreateName',
					sortable : true,
					width : 200
				}, {
					display : '评估类型',
					name : 'aassesTypeName',
					sortname : 'a.assesType',
					sortable : true,
					width : 150
				}, {
					display : '评估预计开始日期',
					name : 'abeginDate',
					sortable : true,
					width : 150
				}, {
					display : '评估预计结束日期',
					name : 'aendDate',
					sortable : true,
					width : 150
				}, {
					display : '评估创建时间',
					name : 'acreateTime',
					sortable : true,
					width : 170
				}, {
					display : '状态',
					name : 'astatusC',
					sortname : 'c.status',
					sortable : true,
					width : 120
				}],
		// 扩展按钮
		buttonsEx : [{
					name : 'saaStart',
					text : "评估",
					icon : 'edit',
					action : function(row,rows,grid) {
						if( row ){
								parent.location="?model=supplierManage_assess_sapeople&action=assessment&assId="+row.aid ;
						}else{
							alert("请选中一条数据");
						}
					}
				}, {
					separator : true
				},{
					name : 'close',
					text : "提交",
					icon : 'edit',
					action : function(row,rows,grid) {
						//$.showDump(row);
						if(row ){
							if( confirm("确定提交评估方案【"+ row.aassesName +"】？") ){
								location="?model=supplierManage_assess_assessment&action=submit&assId="+row.aid;
							}
						}else{
							alert("请选中一条数据");
						}
					}
				}],
		menusEx : [{
					name : 'saaStart',
					text : "评估",
					icon : 'edit',
					action : function(row,rows,grid) {
						if( row ){
								parent.location="?model=supplierManage_assess_sapeople&action=assessment&assId="+row.aid ;
						}else{
							alert("请选中一条数据");
						}
					}
				}, {
					name : 'close',
					text : "提交",
					icon : 'edit',
					action : function(row,rows,grid) {
						//$.showDump(row);
						if(row ){
							if( confirm("确定提交评估方案【"+ row.aassesName +"】？") ){
								location="?model=supplierManage_assess_assessment&action=submit&assId="+row.aid;
							}
						}else{
							alert("请选中一条数据");
						}
					}
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
		sortname : "id",
		// 默认搜索顺序
		sortorder : "ASC",
		toViewConfig : {
				text : '查看评估',
				toViewFn : function(p, g) {
					var rowObj = g.getSelectedRow();
					if( rowObj ){
						showOpenWin("?model=supplierManage_assess_assessment&action=saaViewTab&assId="+rowObj.data('data').aid );
					}else{
						alert("请至少选中一条数据");
					}
				}
		}
	});
});