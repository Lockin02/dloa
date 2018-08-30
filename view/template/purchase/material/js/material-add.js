$(document).ready(function() {
	initDetail();

	validate({
				"productCode" : {
					required : true
				},
				"protocolTypeCode" :{
					required : true
				}
			});
    })

//��ʼ���ʼ���ϸ
function initDetail(){
	//�����ʼ����ݱ�
	var itemTableObj = $("#itemTable");//��ȡ����

	itemTableObj.yxeditgrid({//������ñ༭��񷽷�
		objName : 'material[materialequ]',
		event : {
			'reloadData' : function(e){
				$("#protocolTypeCode").trigger("change");
			},
			'removeRow' : function(){
			}
		},
		title : 'Э�����ϸ',
		colModel : [{
			name : 'lowerNum',
			display : '��������',
			width : 70,
			validation : {
				required : true,
				custom : ['onlyNumber']
			}
		}, {
			name : 'ceilingNum',
			display : '��������',
			width : 70,
			validation : {
				required : true,
				custom : ['onlyNumber']
			}
		}, {
			name : 'taxPrice',
			display : '����',
			width : 70,
			validation : {
				required : true,
				custom : ['percentageNum']
			}
		}, {
			name : 'startValidDate',
			display : '��ʼ��Ч��',
			type : 'date',
			width : 70,
			readonly : true,
			validation : {
				required : true
			}
		}, {
			name : 'validDate',
			display : '������Ч��',
			type : 'date',
			width : 70,
			readonly : true,
			validation : {
				required : true
			}
		}, {
			name : 'suppName',
			display : '��Ӧ������',
			width : 180,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_supplier({
					hiddenId : 'itemTable_cmp_suppName' + rowNum,
					isShowButton : false,
					width : 615,
					isFocusoutCheck : false,
					gridOptions : {
						event : {
							row_dblclick : function(e, row, data) {
								//���б�������
								itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"suppId").val(data.id);
								itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"suppName").val(data.suppName);
								itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"suppCode").val(data.busiCode);
							}
						}
					}
				});
			},
			validation : {
				required : true
			}
		}, {
			name : 'suppId',
			display : '��Ӧ��id',
			type : 'hidden'
		}, {
			name : 'suppCode',
			display : '��Ӧ�̱���',
			type : 'hidden'
		}, {
			name : 'isEffective',
			display : '�Ƿ���Ч',
			width : 35,
			type : 'checkbox',
			value : 'on'
		}, {
			name : 'giveCondition',
			display : '��������',
			type : 'textarea',
			width : 150
		}, {
			name : 'remark',
			display : '��ע',
			type : 'textarea',
			width : 150
		}]
	});
}