$(document).ready(function() {

    //��ʼ����ϸ
    initDetail();
    
	$("#productModel").click(function() {
		document.location = "?model=contract_report_contractreport&action=contractReport&reportType="
				+ reportType;
	});
});

//��ʼ���ʼ���ϸ
function initDetail() {
    //�����ʼ����ݱ�
    var itemTableObj = $("#itemTable");

    itemTableObj.yxeditgrid({
        objName: 'protemplate[items]',
        title: '�����嵥',
        colModel: [{
            name: 'id',
            display: 'id',
            type: 'hidden'
        },
        {
            name: 'productId',
            display: '����id',
            width: 130,
            type: 'hidden',
        },
        {
            name: 'productCode',
            display: '���ϱ��',
            width : 130,
			readonly : true,
            process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
					hiddenId : 'itemTable_cmp_productId' + rowNum,
					gridOptions : {
						event : {
							row_dblclick : function(e, row, data) {
								//���б�������
								itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"productCode").val(data.productCode);
								itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"pattern").val(data.pattern);
								itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"productName").val(data.productName);
								itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"unitName").val(data.unitName);
							}
						}
					}
				});
			},
			validation : {
				required : true
			}
        },
        {
            name: 'productName',
            display: '��������',
            width : 130,
            tclass : 'readOnlyTxtNormal',
			readonly : true,
        },
        {
            name: 'pattern',
            display: '����',
            width : 130,
            tclass : 'readOnlyTxtNormal',
			readonly : true,
        },
        {
            name: 'unitName',
            display: '��λ',
            width : 130,
            tclass : 'readOnlyTxtNormal',
			readonly : true,
        },
        {
            name: 'actNum',
            display: '����',
            width : 130,
            validation : {
				required : true
			}
        }]
    });
    validate({
        "templateName": {
            required: true
        }
    });
}