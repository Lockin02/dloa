var show_page = function(page) {
	$("#purchaseSignLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#purchaseSignLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'purchasesign',
							objId : $('#objId').val()
						},
						isRightMenu:false,
					    title : '签收记录',
					colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '签收人',
								name : 'changeManName',
								width : 200,
								process : function(v, row) {
									if (row['tempId'] == $("#originalId").val()) {
										return "<font color='red'>" + v
												+ "</font>";
									}
									return v;
								}
							}, {
								display : '签收时间',
								name : 'changeTime',
								width : 200,
								process : function(v, row) {
									if (row['tempId'] == $("#originalId").val()) {
										return "<font color='red'>" + v
												+ "</font>";
									}
									return v;
								}
							}, {
								display : '变更原因',
								name : 'changeReason',
								hide : true
							}, {
								display : '审批时间',
								name : 'ExaDT',
								hide : true
							}, {
								display : 'tempId',
								name : 'tempId',
								hide : true
							}],
						subGridOptions : {
							param : [{
										logObj : 'purchasesign'// 固定值放一起，而且要放前面
									}, {
										paramId : 'parentId',// 传递给后台的参数名称
										colId : 'id'// 获取主表行数据的列名称
									}]
						}
					});


		});