$(document).ready(function() {
	var itemTableObj = $("#checkacceptGrid");
	itemTableObj.yxeditgrid({
		objName : 'checkaccept',
		colModel : [{
			display : "��������",
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
			display : '���սڵ�',
			name : 'dateName'
		},{
			display : '���սڵ�ʱ��',
			name : 'checkDateR',
			type : 'date'
		},{
			display : '��������',
			name : 'days'
		},{
			display : '��ϸ��������',
			type: 'textarea',
			name : 'clauseInfo'
		}]
	});
});
