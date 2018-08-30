$(document).ready(function() {

	if ($("#relDocTypeCode").val() == 'SCYDLX-01') {
		$("#projectName").removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly' ,true);
		//研发项目下拉
		$("#relDocCode").attr('readonly' ,true).yxcombogrid_rdprojectfordl({
			hiddenId : 'relDocId',
			nameCol : 'projectCode',
			isFocusoutCheck : false,
			gridOptions : {
				isTitle : true,
				showcheckbox : false,
				param : {
					"is_delete" : "0"
				},
				event : {
					row_dblclick : function(e ,row ,data) {
						$("#projectName").val(data.projectName);
						$("#relDocId").val(data.id);
					}
				}
			}
		});
	} else {
		$("#relDocCode").attr('readonly' ,'');
		$("#projectName").removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly' ,'');
		$("#relDocCode").yxcombogrid_rdprojectfordl('remove');
	}

	var itemsObj = $("#items");
	itemsObj.yxeditgrid({
		objName : 'produceapply[items]',
		url : '?model=produce_apply_produceapplyitem&action=listJson',
		param : {
			mainId : $("#id").val(),
			isTemp : 0
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '物料类型Id',
			name : 'proTypeId',
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
	document.getElementById('form1').action = "?model=produce_apply_produceapply&action=editDepartment&actType=actType";
}