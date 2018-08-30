var show_page = function(page) {
	$("#outplanChangeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#outplanChangeLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'outplan',
							objId : $('#objId').val()
						},
					// 表单
					colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '变更人',
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
								display : '变更时间',
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
								width : 300
							}, {
								display : '审批状态',
								name : 'ExaStatus',
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
										logObj : 'outplan'// 固定值放一起，而且要放前面
									}, {
										paramId : 'parentId',// 传递给后台的参数名称
										colId : 'id'// 获取主表行数据的列名称
									}]
						}
					});


		});