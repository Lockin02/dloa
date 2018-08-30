// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".assessGrid").yxgrid("reload");
};
$(function() {
	var assId = $("#assId").val();
	$(".assessGrid").yxgrid({
		model : 'supplierManage_assess_assessment',
		action : 'saaPJMyManage',
		title:'我负责的评估方案',
		isViewAction : false,
		//isEditAction : false,
//		isToolBar : false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '状态Key',
					name : 'status',
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
					sortable : true,
					width : 200
				}, {
					display : '评估类型',
					name : 'assesTypeName',
					sortname : 'c.assesType',
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
					sortname : 'c.status',
					sortable : true,
					width : 100
				}],
		// 扩展按钮
//		buttonsEx : [{
//					name : 'saaStart',
//					text : "启动",
//					icon : 'edit',
//					action : function(row,rows,grid) {
//						if(row && isStruts(rows, ["1"] )  ){
//							if( confirm("确定启动评估方案【"+ row.assesName +"】？") ){
//								parent.location="?model=supplierManage_assess_assessment&action=saaStart&assId="+row.id;
//							}
//						}else{
//							alert("请选中一条数据并且选中的数据状态只能是保存状态");
//						}
//					}
//				}, {
//					separator : true
//				},{
//					name : 'close',
//					text : "完成评估",
//					icon : 'edit',
//					action : function(row,rows,grid) {
//						//$.showDump(row);
//						if(row && isStruts(rows, ["2"] ) ){
//							//if( confirm("确定完成此评估方案【"+ row.assesName +"】？") ){
//								parent.location="?model=supplierManage_assess_assessment&action=assToClose&assId="+row.id;
//							//}
//						}else{
//							alert("请选中一条数据,并且选中的数据状态只能是执行中！");
//						}
//					}
//				}],
		// 扩展右键菜单
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

				},{
					name : 'saaStart',
					text : "启动",
					icon : 'edit',
					action : function(row,rows,grid) {
						if(row && isStruts(rows, ["1"] )  ){
							if( confirm("确定启动评估方案【"+ row.assesName +"】？") ){
								parent.location="?model=supplierManage_assess_assessment&action=saaStart&assId="+row.id;
							}
						}else{
							alert("请选中一条数据并且选中的数据状态只能是保存状态");
						}
					}
				}, {
					name : 'close',
					text : "完成评估",
					icon : 'edit',
					action : function(row,rows,grid) {
						//$.showDump(row);
						if(row && isStruts(rows, ["2"] ) ){
							//if( confirm("确定完成此评估方案【"+ row.assesName +"】？") ){
								parent.location="?model=supplierManage_assess_assessment&action=assToClose&assId="+row.id;
							//}
						}else{
							alert("请选中一条数据,并且选中的数据状态只能是执行中！");
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
		// 添加扩展信息
		toAddConfig : {
			text : '添加评估',
			toAddFn : function(p,g) {
					parent.location="?model=supplierManage_assess_assessment&action=saaToAdd";
				}
		},
		toViewConfig : {
				text : '查看评估',
				toViewFn : function(p, g) {
					var rowObj = g.getSelectedRow();
					if( rowObj ){
						showOpenWin("?model=supplierManage_assess_assessment&action=saaViewTab&assId="+rowObj.data('data').id );
					}else{
						alert("请至少选中一条数据");
					}
				}
		},
		toEditConfig : {
				text : '编辑',
				toEditFn : function(p, g) {
					var rowObj = g.getSelectedRow();
					if( rowObj && rowObj.data('data').status=="1" ){
						parent.location="?model=supplierManage_assess_assessment&action=saaToEdit&assId="+rowObj.data('data').id;
					}else{
						alert("只有状态为保存的数据可以编辑，操作失败");
					}
				}
		},
		toDelConfig : {
				text : '删除',
				toDelFn : function(p, g) {
					var rowIds = g.getCheckedRowIds();
					var rows = g.getCheckedRows();
					if (rowIds[0]) {
						if( isStruts(rows, ["1"] ) ){
							if (window.confirm("确认要删除?")) {
								$.ajax({
											type : "POST",
											url : "?model=" + p.model + "&action=ajaxdeletes",
											data : {
												id : g.getCheckedRowIds()
														.toString()
												// 转换成以,隔开方式
											},
											success : function(msg) {
												if (msg == 1) {
													g.reload();
													alert('删除成功！');
												}
											}
										});
							}
						}else{
							alert("只有状态为保存的数据可以删除，操作失败");
						}
					} else {
						alert('请选择一行记录！');
					}
				}
			}
	});

	/**
	 * 判断状态
	 */
	function isStruts(rows,arr){
		for( row in rows ){
			if( rows[row]['status'] && !arr.in_array(rows[row]['status']) ){
				return false;
			}
		}
		return true;
	}
});