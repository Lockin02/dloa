$(document).ready(function () {
	// �����ƻ�����
	$('#planCode').yxcombogrid_produceplan({
		hiddenId: 'planId',
		nameCol: 'planCode',
		gridOptions: {
			event: {
				row_dblclick: function (e, row ,data) {
					$('#planId').val(data.id);
					$('#planCode').val(data.docCode);
					$('#taskId').val(data.taskId);
					$('#taskCode').val(data.taskCode);
					$('#relDocCode').val(data.relDocCode);
					$('#relDocId').val(data.relDocId);
					$('#relDocName').val(data.relDocName);
					$('#relDocType').val(data.relDocType);
					$('#relDocTypeCode').val(data.relDocTypeCode);
					$('#objCode').val(data.objCode);
					$('#applyDocCode').val(data.applyDocCode);
					$('#applyDocId').val(data.applyDocId);
					$('#applyDocItemId').val(data.applyDocItemId);
					$('#customerId').val(data.customerId);
					$('#customerName').val(data.customerName);
				}
			}
		}
	});

	var productObj = $("#productItem");
	productObj.yxeditgrid({
		objName: 'picking[item]',
		isFristRowDenyDel: true,
		colModel: [{
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
			display: '�ƻ���������',
			name: 'planDate',
			width: '10%',
			type: 'date',
			readonly: true,
			validation: {
				required: true
			}
		}, {
			display: '��ע',
			name: 'remark',
			type: 'textarea',
			rows: 2,
			width: '20%'
		}]
	});

	validate({
		"docTypeCode": {
			required: true
		},
		"module" : {
			required : true
		}
	});
});

//���ôӱ��ƻ���������
function setPlanDate(e) {
	if (e.value != '') {
		var planDateObjs = $("#productItem").yxeditgrid('getCmpByCol', 'planDate');
		planDateObjs.each(function (k, v) {
			if (this.value == '') {
				$(this).val(e.value);
			}
		});
	}
}

//ֱ���ύ
function toSubmit() {
	document.getElementById('form1').action = "?model=produce_plan_picking&action=add&actType=audit";
}