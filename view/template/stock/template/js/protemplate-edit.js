$(document).ready(function() {

    //初始化明细
    initDetail();

    validate({
        "formDate": {
            required: true
        }
    });
});

//初始化质检明细
function initDetail() {
    //缓存质检内容表
    var itemTableObj = $("#itemTable");

    itemTableObj.yxeditgrid({
        url: "?model=stock_template_protemplateitem&action=listJson",
        param: {
            "mainId": $("#id").val()
        },
        objName: 'protemplate[items]',
        title: '物料清单',
        colModel: [{
            name: 'id',
            display: 'id',
            type: 'hidden'
        },
        {
            name: 'productId',
            display: '物料id',
            type: 'hidden',
        },
        {
            name: 'productCode',
            display: '物料编号',
			readonly : true,
            process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
					hiddenId : 'itemTable_cmp_productId' + rowNum,
					gridOptions : {
						event : {
							row_dblclick : function(e, row, data) {
								//本行报检数量
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
            display: '物料名称',
			readonly : true,
			tclass : 'readOnlyTxtNormal',
        },
        {
            name: 'pattern',
            display: '规格号',
			readonly : true,
			tclass : 'readOnlyTxtNormal',
        },
        {
            name: 'unitName',
            display: '单位',
			readonly : true,
			tclass : 'readOnlyTxtNormal',
        },
        {
            name: 'actNum',
            display: '数量',
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