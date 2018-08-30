$(document).ready(function() {

	var itemsObj = $("#items");
	itemsObj.yxeditgrid({
		objName : 'produceapply[items]',
		isFristRowDenyDel : true,

		colModel : [{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '物料类型Id',
			name : 'proTypeId',
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
					hiddenId : 'items_cmp_productId' + rowNum,
					width : 500,
					height : 300,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : function(e ,row ,data) {
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val(data.productCode);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productName").val(data.productName);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"pattern").val(data.pattern);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"unitName").val(data.unitName);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"proType").val(data.proType);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"proTypeId").val(data.proTypeId);
							}
						}
					}
				});
			}
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		},{
			display : '规格型号',
			name : 'productModel',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '单位名称',
			name : 'unitName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '申请数量',
			name : 'produceNum',
			tclass : 'txtshort',
			validation : {
				custom : ['onlyNumber']
			}
		},{
			display : '期望交货时间',
			name : 'planEndDate',
			tclass : 'txtshort',
			type : 'date',
			readonly : true
		},{
			display : '备注',
			name : 'remark',
			type : 'textarea',
			width : '20%',
			rows : 2
		}]
	});

	validate({
		"relDocTypeCode" : {
			required : true
		},
		"relDocCode" : {
			required : true
		}
	});
});

//直接提交
function toSubmit(){
	document.getElementById('form1').action = "?model=produce_apply_produceapply&action=addDepartment&actType=actType";
}