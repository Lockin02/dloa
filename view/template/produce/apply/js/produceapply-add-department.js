$(document).ready(function() {

	var itemsObj = $("#items");
	itemsObj.yxeditgrid({
		objName : 'produceapply[items]',
		isFristRowDenyDel : true,

		colModel : [{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '��������Id',
			name : 'proTypeId',
			type : 'hidden'
		},{
			display : '��������',
			name : 'proType',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '���ϱ���',
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
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		},{
			display : '����ͺ�',
			name : 'productModel',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '��λ����',
			name : 'unitName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '��������',
			name : 'produceNum',
			tclass : 'txtshort',
			validation : {
				custom : ['onlyNumber']
			}
		},{
			display : '��������ʱ��',
			name : 'planEndDate',
			tclass : 'txtshort',
			type : 'date',
			readonly : true
		},{
			display : '��ע',
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

//ֱ���ύ
function toSubmit(){
	document.getElementById('form1').action = "?model=produce_apply_produceapply&action=addDepartment&actType=actType";
}