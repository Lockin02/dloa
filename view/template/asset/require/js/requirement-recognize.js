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
			display : '���ϱ��',
			name : 'productCode',
			type : 'statictext'
		}, {
			display : '��������',
			name : 'productName',
			type : 'statictext'
		}, {
			display : '�豸����',
			name : 'name',
			type : 'statictext'
		}, {
			display : '�豸����',
			name : 'description',
			validation : {
				required : true
			},
			type : 'statictext'
		}, {
			display : '����',
			name : 'number',
			validation : {
				required : true ,
				custom : ['onlyNumber']
			},
			type : 'statictext'
		}, {
			display : 'Ԥ�ƽ��',
			name : 'expectAmount',
			process : function(v){
				return moneyFormat2(v);
			},
			type : 'statictext'
		}, {
			display : 'Ԥ�ƽ�������',
			name : 'dateHope',
			type:'date',
			validation : {
				required : true
			},
			type : 'statictext'
		}, {
			display : '��ע',
			name : 'remark',
			type : 'statictext'
		}, {
			display : 'ѯ�۽��',
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
			display : '�������',
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
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
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
//	if(rowAmountVa>100){alert("�ܺͲ��ܳ���100��");return false;}
	$("#recognizeAmount").val(rowAmountVa);
	$("#recognizeAmount_v").val(moneyFormat2(rowAmountVa));
	return true;
}