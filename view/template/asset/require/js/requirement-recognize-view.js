$(document).ready(function() {
	validate({
		"recognizeAmount" : {
			required : true
		}
	});
	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireitem&action=listByRequireJson',
		objName : 'requirement[items]',
		type : 'view',
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
			tclass : 'txt'
		}, {
			display : '数量',
			name : 'number',
			validation : {
				required : true ,
				custom : ['onlyNumber']
			},
			tclass : 'txtshort'
		}, {
			display : '预计金额',
			name : 'expectAmount',
			tclass : 'txtshort',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '预计交货日期',
			name : 'dateHope',
			type:'date',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : '询价金额',
			name : 'inquiryAmount',
			type : 'money',
			process : function(v){
				return '<font color="red">' + moneyFormat2(v) + "</font>";
			}
		}, {
			display : '行政意见',
			name : 'suggestion',
			validation : {
				required : true
			},
			type : 'textarea',
			cols : '40',
			rows : '3',
			process : function(v){
				return '<font color="red">' + v + "</font>";
			}
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