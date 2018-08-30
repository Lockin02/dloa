$(document).ready(function() {
	if ($("#projectName").val() != '') {
		$("#department0").hide();
	}
	
    /**
     * 验证信息
     */
    validate({
    	"taskId": {
            required: true
        }
    });
    
	var templateObj = $('#templateData');
	templateObj.yxeditgrid({
		url:'?model=produce_plan_produceplan&action=classify',
		param : {
			id : $("#taskId").val(),
			productCode : $("#productCode").val(),
			dir : 'ASC'
		},
		objName : 'produceplan[items]',
		isFristRowDenyDel : true,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '物料类型',
			name : 'proType',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '物料编码',
			name : 'productCode',
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_product({
					hiddenId : 'templateData_cmp_productId' + rowNum,
					width : 500,
					height : 300,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : function(e ,row ,data) {
								templateObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val(data.productCode);
								templateObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productName").val(data.productName);
								templateObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"pattern").val(data.pattern);
								templateObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"unitName").val(data.unitName);
								templateObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"proType").val(data.proType);
								templateObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"proTypeId").val(data.proTypeId);
							}
						}
					}
				});
			},
			validation : {
			}
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		},{
			display : '规格型号',
			name : 'pattern',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '单位名称',
			name : 'unitName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '数量',
			name : 'num',
			tclass : 'txtshort',
			validation : {
				custom : ['onlyNumber']
			}
		}]
	});
});