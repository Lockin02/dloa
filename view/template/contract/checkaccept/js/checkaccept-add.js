$(document).ready(function() {
	var itemTableObj = $("#checkacceptGrid");
	itemTableObj.yxeditgrid({
		objName : 'checkaccept',
		colModel : [{
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
			display : '验收节点',
			name : 'dateName'
		},{
			display : '验收节点时间',
			name : 'checkDateR',
			type : 'date'
		},{
			display : '缓冲天数',
			name : 'days'
		},{
			display : '详细验收条款',
			type: 'textarea',
			name : 'clauseInfo'
		}]
	});
});
