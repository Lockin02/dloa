var show_page = function(page) {
	$("#changeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
	var logObj = $('#logObj').val();
	var originalId = $("#originalId").val();

	$("#changeLogGrid").yxgrid_changeLog({
		param : {
			logObj : logObj,
			objId : $('#objId').val()
		},
		title : '变更信息 (注：红色代表当前变更版本记录)',
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
					if (row['tempId'] == originalId) {
						return "<font color='red'>" + v + "</font>";
					}
					return v;
				}
			}, {
				display : '变更时间',
				name : 'changeTime',
				width : 200,
				process : function(v, row) {
					if (row['tempId'] == originalId) {
						return "<font color='red'>" + v + "</font>";
					}
					return v;
				}
			}, {
				display : '变更原因',
				name : 'changeReason',
				width : 300
			}, {
				display : '审批状态',
				name : 'ExaStatus'
			}, {
				display : '审批时间',
				name : 'ExaDT'
			}, {
				display : 'tempId',
				name : 'tempId',
				hide : true
			}
		],
		subGridOptions : {
			param : [{
				logObj : logObj// 固定值放一起，而且要放前面
			}, {
				paramId : 'parentId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称
			}]
		}
	});
});