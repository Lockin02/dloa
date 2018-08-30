$(document).ready(function() {

	if(mark == 1){
		var itemsObj = $('#items',parent.document);
		itemsObj.empty();
		$('#templateName',parent.document).val('');
		$('#classify',parent.document).val('');
		$('#remark',parent.document).val('');
		itemsObj.yxeditgrid({
			objName : 'product[items]',
			isFristRowDenyDel : true,
			data:datas,
			colModel : [{
				display : 'id',
				name : 'id',
				type : 'hidden'
			},{
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
	}
});