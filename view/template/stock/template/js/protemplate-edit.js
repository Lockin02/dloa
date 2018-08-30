$(document).ready(function() {

    //��ʼ����ϸ
    initDetail();

    validate({
        "formDate": {
            required: true
        }
    });
});

//��ʼ���ʼ���ϸ
function initDetail() {
    //�����ʼ����ݱ�
    var itemTableObj = $("#itemTable");

    itemTableObj.yxeditgrid({
        url: "?model=stock_template_protemplateitem&action=listJson",
        param: {
            "mainId": $("#id").val()
        },
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
            type: 'hidden',
        },
        {
            name: 'productCode',
            display: '���ϱ��',
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
			readonly : true,
			tclass : 'readOnlyTxtNormal',
        },
        {
            name: 'pattern',
            display: '����',
			readonly : true,
			tclass : 'readOnlyTxtNormal',
        },
        {
            name: 'unitName',
            display: '��λ',
			readonly : true,
			tclass : 'readOnlyTxtNormal',
        },
        {
            name: 'actNum',
            display: '����',
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