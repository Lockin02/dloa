var show_page = function(page) {
	$("#orderChangeLogGrid").yxgrid_changeLog("reload");
};

function clickFun(oldVal, newVal) {
	alert('功能建设中');
}

$(function() {
	var objId = $("#objId").val();
	var logObj = ($("#logObj").val() != '')? $("#logObj").val() : 'contract';
	/*
	 * 获取变更属性
	 */
	function getData() {
		var responseText = $.ajax({
			url : 'index1.php?model=common_changeLog&action=getChangeFieldCn',
			type : "POST",
			data : {
				objId : objId,
				logObj : logObj
			},
			async : false
		}).responseText;
		var d = eval("(" + responseText + ")");
		var obj = [];
		for (var i = 0; i < d.length; i++) {
			obj.push({'text':d[i].changeFieldCn,'value':d[i].changeFieldCn});
		}
		return obj;
	}
	var selectArr = getData();
	
	$("#orderChangeLogGrid").yxgrid_changeLog({
		param : {
			logObj : logObj,
			objType : logObj,
			objId : $('#objId').val()
		},
		subGridOptions : {
			param : [{// 固定值放一起，而且要放前面
				logObj : logObj,
				parentType : logObj
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
					}, {
						name : 'objField',
						width : 150,
						display : '对象名称'
					}, {
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
		},
		searchitems : [{
			display : '变更人',
			name : 'changeManNameSearch'
		}],
		comboEx : [{
			text: "变更属性",
			key: 'changeFieldCn',
			data : selectArr
		}]
	});

});