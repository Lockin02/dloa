$(document).ready(function() {

	$("#protocolTypeCode").trigger('change');
	$("#itemTable").yxeditgrid({
		objName : 'material[materialequ]',
		bodyAlign:'center',
		url : '?model=purchase_material_materialequ&action=listJson',
		title : 'Э�����ϸ',
		param : {
			parentId :$("#id").val()
		},
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
			width : 70,
			type : 'date',
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
				var itemTableObj = $("#itemTable");
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
			process : function (e) {
				if(e == "on"){
				   return true;
				}else{
				   return false;
				}
			},
			value : 'on'
		}, {
			name : 'giveCondition',
			display : '��������',
			width : 150,
			type : 'textarea'
		}, {
			name : 'remark',
			display : '��ע',
			width : 150,
			type : 'textarea'
		}]
	});

   validate({
				"productCode" : {
					required : true
				},
				"protocolTypeCode" :{
					required : true
				}
			});
   });