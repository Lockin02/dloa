$(document).ready(function() {
	if ($("#projectName").val() != '') {
		$("#department0").hide();
	}
	
    /**
     * ��֤��Ϣ
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
			display : '����Id',
			name : 'productId',
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
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		},{
			display : '����ͺ�',
			name : 'pattern',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '��λ����',
			name : 'unitName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '����',
			name : 'num',
			tclass : 'txtshort',
			validation : {
				custom : ['onlyNumber']
			}
		}]
	});
});