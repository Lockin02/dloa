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
			tclass : 'txt'
		}, {
			display : '����',
			name : 'number',
			validation : {
				required : true ,
				custom : ['onlyNumber']
			},
			tclass : 'txtshort'
		}, {
			display : 'Ԥ�ƽ��',
			name : 'expectAmount',
			tclass : 'txtshort',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : 'Ԥ�ƽ�������',
			name : 'dateHope',
			type:'date',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : 'ѯ�۽��',
			name : 'inquiryAmount',
			type : 'money',
			process : function(v){
				return '<font color="red">' + moneyFormat2(v) + "</font>";
			}
		}, {
			display : '�������',
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