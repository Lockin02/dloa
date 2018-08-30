$(document).ready(function() {
	var productObj = $("#productItem");
	productObj.yxeditgrid({
		objName : 'back[items]',
		title : '�������뵥��ϸ',
		isFristRowDenyDel: true,
		url : '?model=produce_plan_backitem&action=listJson',
		param : {
			backId : $("#id").val(),
			dir : 'ASC'
		},
		colModel : [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '����Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '���ϱ���',
			name: 'productCode',
			process: function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_product({
					hiddenId: 'productItem_cmp_productId' + rowNum,
					gridOptions: {
						showcheckbox: false,
						event: {
							row_dblclick: function (e, row, data) {
								productObj.yxeditgrid("getCmpByRowAndCol", rowNum, "productCode").val(data.productCode);
								productObj.yxeditgrid("getCmpByRowAndCol", rowNum, "productName").val(data.productName);
								productObj.yxeditgrid("getCmpByRowAndCol", rowNum, "pattern").val(data.pattern);
								productObj.yxeditgrid("getCmpByRowAndCol", rowNum, "unitName").val(data.unitName);

								//�������������Ϊ�˷�ֹ�����첽��ȡǰ����Ŀ��ʾ����
								productObj.yxeditgrid("getCmpByRowAndCol", rowNum, "JSBC").html('');
								productObj.yxeditgrid("getCmpByRowAndCol", rowNum, "KCSP").html('');
								productObj.yxeditgrid("getCmpByRowAndCol", rowNum, "SCC").html('');
								//��ȡ���豸�֡������Ʒ�ֺ�����������
								$.ajax({
									type: 'POST',
									url: "?model=produce_plan_picking&action=getProductNum",
									data: {
										productCode: data.productCode
									},
									success: function (result) {
										var obj = eval("(" + result + ")");
										productObj.yxeditgrid("getCmpByRowAndCol", rowNum, "JSBC").html(obj.JSBC);
										productObj.yxeditgrid("getCmpByRowAndCol", rowNum, "KCSP").html(obj.KCSP);
										productObj.yxeditgrid("getCmpByRowAndCol", rowNum, "SCC").html(obj.SCC);
									}
								});
							}
						}
					}
				});
			}
		}, {
			display: '��������',
			name: 'productName',
			tclass: 'readOnlyText',
			width: '25%',
			readonly: true
		}, {
			display: '����ͺ�',
			name: 'pattern',
			tclass: 'readOnlyTxtMiddle',
			width: '10%',
			readonly: true
		}, {
			display: '��λ',
			name: 'unitName',
			tclass: 'readOnlyTxtShort',
			width: '8%',
			readonly: true
		}, {
			display: '���豸��',
			name: 'JSBC',
			type: 'statictext',
			width: '5%'
		}, {
			display: '�����Ʒ',
			name: 'KCSP',
			type: 'statictext',
			width: '5%'
		}, {
			display: '������',
			name: 'SCC',
			type: 'statictext',
			width: '5%'
		}, {
			display: '��������',
			name: 'applyNum',
			validation: {
				custom: ['onlyNumber']
			},
			width: '5%'
		}, {
			display: '��ע',
			name: 'remark',
			type: 'textarea',
			rows: 2,
			width: '20%'
		}]
	});
	// ������������һ��������֤������ᵼ���ӱ���֤����
	validate({
		"createName": {
			required: true
		}
	});
});

//����
function save(){
	$('#form1').attr("action","?model=produce_plan_back&action=edit");
}

// �ύ
function sub(){
	$('#form1').attr("action","?model=produce_plan_back&action=edit&act=sub");
}