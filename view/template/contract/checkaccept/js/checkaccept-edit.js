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
			type :��"hidden"
		},{
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
			display : '����ʱ��ڵ�',
			name : 'dateName'
		},{
			display : '���սڵ�(����)',
			name : 'checkDateR',
			type : 'date'
		},{
			display : '��������',
			name : 'days'
		},{
			display : '��ϸ��������',
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
					//ͨ�����ñ������������ɾ���������к�
					itemTableObj.yxeditgrid("addRow" ,i ,data[i]); //Ĭ��Ϊ�ɱ༭
					if (data[i].checkStatus == '������') { //�����½�˲�Ϊ�����˵Ļ����ܱ༭
						itemTableObj.yxeditgrid("removeRow" ,i); //���м�ɾ��
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