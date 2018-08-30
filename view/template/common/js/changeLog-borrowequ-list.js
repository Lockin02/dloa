var show_page = function(page) {
	$("#borrowequChangeLogGrid").yxgrid_changeLog("reload");
};

$(function() {
	$("#borrowequChangeLogGrid").yxgrid_changeLog({
		param : {
			logObj : 'borrowequ',
			objId : $('#objId').val()
			,objType : 'borrowequ'
		},
		subGridOptions : {
			param : [{// 固定值放一起，而且要放前面
				logObj : 'borrowequ'
			}, {
				paramId : 'parentId',
				// 传递给后台的参数名称
				colId : 'id' // 获取主表行数据的列名称
			}],
			colModel : [{
						name : 'detailTypeCn',
						display : '对象类型'
					},
					/**
					 * { name : 'changeField', width : 150, display : '变更字段' },
					 */
					{
						name : 'detailId',
						width : 30,
						display : '标志',
						process : function(v) {
							if (v != 0) {
								return v;
							}
							return "";
						}
					},  {
						name : 'objField',
						width : 150,
						display : '对象名称'
					},{
						name : 'changeFieldCn',
						width : 150,
						display : '变更属性',
						process : function(v, row) {
							if (v == '加密配置') {
								return v
										+ " <img src='images/icon/search.gif' title='查看配置差异' onclick='clickFun("
										+ row.oldValue + " , " + row.newValue
										+ ");'/>";
							} else {
								return v;
							}
						}
					}, {
						name : 'oldValue',
						width : 150,
						display : '变更前值'
					}, {
						name : 'newValue',
						width : 150,
						display : '变更后值'
					}]
		}
	});

});