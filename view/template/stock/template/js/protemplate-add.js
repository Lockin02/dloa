$(document).ready(function() {

    //初始化明细
    initDetail();
    
	$("#productModel").click(function() {
		document.location = "?model=contract_report_contractreport&action=contractReport&reportType="
				+ reportType;
	});
});

//初始化质检明细
function initDetail() {
    //缓存质检内容表
    var itemTableObj = $("#itemTable");

    itemTableObj.yxeditgrid({
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
            width: 130,
            type: 'hidden',
        },
        {
            name: 'productCode',
            display: '物料编号',
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
            width : 130,
            tclass : 'readOnlyTxtNormal',
			readonly : true,
        },
        {
            name: 'pattern',
            display: '规格号',
            width : 130,
            tclass : 'readOnlyTxtNormal',
			readonly : true,
        },
        {
            name: 'unitName',
            display: '单位',
            width : 130,
            tclass : 'readOnlyTxtNormal',
			readonly : true,
        },
        {
            name: 'actNum',
            display: '数量',
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