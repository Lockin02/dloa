$(document).ready(function() {
	//��֤��Ϣ
	validate({
		"applyName" : {
			required : true
		}
	});
	//�ʲ�����������ϸ
	$("#allItemTable").yxeditgrid({
		url : '?model=asset_require_requireitem&action=listByRequireJson',
		type : 'view',
		param : {
			mainId : $("#requireId").val(),
			type : 'add'
		},
		isAddOneRow : true,
		colModel : [{
			display : '�豸����',
			name : 'name',
			tclass : 'txt',
			width : 120
		}, {
			display : '�豸����',
			name : 'description',
			tclass : 'txt',
			width : 120
		}, {
			display : '����id',
			name : 'productId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'txtshort',
			width : 120
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'txtshort',
			width : 120
		}, {
			display : '���Ͻ��',
			name : 'productPrice',
			tclass : 'txtshort',
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '����Ʒ��',
			name : 'brand',
			tclass : 'txtshort',
			width : 100
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txtshort',
			width : 100
		}, {
			display : '����',
			name : 'number',
			tclass : 'txt',
			width : 60
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt',
			width : 200
		}]
	})
	//���´���ϸ
	$("#allreadyItemTable").yxeditgrid({
		url : '?model=asset_require_requireinitem&action=listJson',
		type : 'view',
		param : {
			requireId : $("#requireId").val()
		},
		isAddOneRow : true,
		colModel : [{
			display : '�豸����',
			name : 'name',
			tclass : 'txt',
			width : 120
		}, {
			display : '�豸����',
			name : 'description',
			tclass : 'txt',
			width : 120
		}, {
			display : '����id',
			name : 'productId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'txtshort',
			width : 120
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'txtshort',
			width : 120
		}, {
			display : '���Ͻ��',
			name : 'productPrice',
			tclass : 'txtshort',
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '����Ʒ��',
			name : 'brand',
			tclass : 'txtshort',
			width : 100
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txtshort',
			width : 100
		}, {
			display : '����',
			name : 'number',
			tclass : 'txt',
			width : 60
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt',
			width : 200
		}]
	})
	//�����´���ϸ
	$("#thisItemTable").yxeditgrid({
		url : '?model=asset_require_requireitem&action=requireinJsonApply',
		objName : 'requirein[items]',
		param : {
			mainId : $("#requireId").val()
		},
		isAdd : false,
		isAddOneRow : true,
		colModel : [{
			display : 'requireItemId',
			name : 'requireItemId',
			type : "hidden"
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
			tclass : 'txt',
			width : 60,
			event : {
				blur : function(e){
					var rownum = $(this).data('rowNum');// �ڼ���
					var grid = $(this).data('grid');// ������

					var maxNum = grid.getCmpByRowAndCol(rownum, 'maxNum').val();
					if(!isNum($(this).val())){
						alert("�������벻�Ϸ�");
						$(this).focus().val(maxNum);
					}
					if($(this).val() *1 > maxNum *1){
						alert("�������ܴ���ʣ�����������" + maxNum);
						$(this).focus().val(maxNum);
					}
				}
			}
		}, {
			display : '�������',
			name : 'maxNum',
			type : "hidden"
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt',
			width : 150
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
			type : 'money',
			readonly : 'readonly',
			tclass : 'readOnlyTxtItem',
			width : 80
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
//����/���ȷ��
function confirmSubmit(type) {
	if(type == 'audit'){
		if (confirm("��ȷ��Ҫ�ύ������?")) {
			$("#form1").attr("action",
					"?model=asset_require_requirein&action=add&actType=audit");
			$("#form1").submit();
		} else {
			return false;
		}
	}else{
		$("#form1").attr("action",
		"?model=asset_require_requirein&action=add");
		$("#form1").submit();
	}
}