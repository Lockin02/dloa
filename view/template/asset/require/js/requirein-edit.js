$(document).ready(function() {
	//��֤��Ϣ
	validate({
		"applyName" : {
			required : true
		}
	});
	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireinitem&action=listByRequireJson',
		objName : 'requirein[items]',
		title : '������Ϣ',
		param : {
			mainId : $("#id").val()
		},
		isAddOneRow : true,
		isAdd : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'name',
			validation : {
				required : true
			},
			width : 150,
			tclass : 'txt'
		}, {
			display : '�豸����',
			name : 'description',
			validation : {
				required : true
			},
			width : 150,
			tclass : 'txt'
		}, {
			display : '����',
			name : 'number',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 60
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt',
			width : 200
		}, {
			display : '����id',
			name : 'productId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 120
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : '���Ͻ��',
			name : 'productPrice',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '����Ʒ��',
			name : 'brand',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 100
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 100
		}]
	})
});

/*
 * ����/���ȷ��
 */
function confirmSubmit(type) {
	if(type == 'audit'){
		if (confirm("��ȷ��Ҫ�ύ������?")) {
			$("#form1").attr("action","?model=asset_require_requirein&action=edit&actType=audit");
			$("#form1").submit();
		} else {
			return false;
		}
	}else{
		$("#form1").attr("action","?model=asset_require_requirein&action=edit");
		$("#form1").submit();
	}
}

