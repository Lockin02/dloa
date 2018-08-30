$(document).ready(function() {


	var itemTableObj = $("#checkacceptGrid");
	itemTableObj.yxeditgrid({
//		url : "?model=contract_checkaccept_checkaccept&action=editlistJson",
//		param : {
//			'contractId' : $("#contractId").val()
//		},
		objName : 'checkaccept',
		initAddRowNum : 0,
		colModel : [{
			display : "id",
			name : "id",
			type :　"hidden"
		},{
			display : "验收条款",
			name : 'clause',
			process : function($input, rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_checkset({
						isFocusoutCheck : false,
						gridOptions : {
							event : {
								row_dblclick : function(e, row, data) {
									itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"dateName").val(data.dateName);
									itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"days").val(data.days);
								}
							}
						}
					});
			}
		},{
			display : '验收时间节点',
			name : 'dateName'
		},{
			display : '验收节点(日期)',
			name : 'checkDateR',
			type : 'date'
		},{
			display : '缓冲天数',
			name : 'days'
		},{
			display : '详细验收条款',
			type: 'textarea',
			name : 'clauseInfo'
		}],
	});

	$.ajax({
		type : "POST",
		url : '?model=contract_checkaccept_checkaccept&action=editlistJson',
		data : {
			'contractId' : $("#contractId").val()
		},
		success : function(data) {
			if (data) {
				data = eval("(" + data + ")");

				var $tab = $("#checkacceptGrid > table > tbody");
				for (var i = 0; i < data.length; i++) {
					//通过调用表格组件里的增加删除来处理行号
					itemTableObj.yxeditgrid("addRow" ,i ,data[i]); //默认为可编辑
					if (data[i].checkStatus == '已验收') { //如果登陆人不为创建人的话则不能编辑
						itemTableObj.yxeditgrid("removeRow" ,i); //进行假删除
						htmlArr = '<tr class="tr_even" rownum="' + i
								+ '"><td>' + '<input type="hidden" name="checkaccept[' + (i) + '][rowNum_]" value="' + i
								+ '"></td><td type="rowNum">' + (i+1)
								+ '</td><td>' + data[i].clause
								+ '</td><td>' + data[i].dateName
								+ '</td><td>' + data[i].days + '</td></tr>';
						$tab.append(htmlArr);
					}
				}
			}
		}
	});

})