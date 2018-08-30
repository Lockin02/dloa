$(document).ready(function() {
	validate({
		"recognizeAmount" : {
			required : true
		}
	});
	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireitem&action=listByRequireJson',
		objName : 'requirement[items]',
		isAddAndDel : false,
		param : {
			mainId : $("#id").val()
		},
		isAddOneRow : true,
		colModel : [{
			display : 'id',
			name : 'id',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '物料编号',
			name : 'productCode',
			type : 'statictext'
		}, {
			display : '物料名称',
			name : 'productName',
			type : 'statictext'
		}, {
			display : '设备名称',
			name : 'name',
			type : 'statictext'
		}, {
			display : '设备描述',
			name : 'description',
			validation : {
				required : true
			},
			type : 'statictext'
		}, {
			display : '数量',
			name : 'number',
			validation : {
				required : true ,
				custom : ['onlyNumber']
			},
			type : 'statictext'
		}, {
			display : '预计金额',
			name : 'expectAmount',
			process : function(v){
				return moneyFormat2(v);
			},
			type : 'statictext'
		}, {
			display : '预计交货日期',
			name : 'dateHope',
			type:'date',
			validation : {
				required : true
			},
			type : 'statictext'
		}, {
			display : '备注',
			name : 'remark',
			type : 'statictext'
		}, {
			display : '询价金额',
			name : 'inquiryAmount',
			validation : {
				required : true
			},
			type : 'money',
			tclass : 'txtshort',
			event : {
				blur : function() {
					caculate();
				}
			}
		}, {
			display : '行政意见',
			name : 'suggestion',
			validation : {
				required : true
			},
			type : 'textarea',
			cols : '40',
			rows : '3'
		}]
	})
})

/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_require_requirement&action=recognize&actType=audit");
		$("#form1").submit();
	} else {
		return false;
	}
}
function caculate() {
	var rowAmountVa = 0;
	var inquiryAmount = $("#itemTable").yxeditgrid("getCmpByCol", "inquiryAmount");
//	var portions = $("#itemTable").yxeditgrid("getCmpByCol", "standardProportion");
//	for(var i=0;i<inquiryAmount.length;i++){
//		alert(inquiryAmount[i].value)
//		return false;
//	}
	inquiryAmount.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
//		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
//	if(rowAmountVa>100){alert("总和不能超过100！");return false;}
	$("#recognizeAmount").val(rowAmountVa);
	$("#recognizeAmount_v").val(moneyFormat2(rowAmountVa));
	return true;
}