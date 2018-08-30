$(document).ready(function() {
	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireitem&action=listByRequireJson',
		objName : 'requirement[items]',
		type:'view',
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
			tclass : 'txt'
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'txt'
		}, {
			display : '���Ͻ��',
			name : 'productPrice',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '����Ʒ��',
			name : 'brand',
			tclass : 'txt'
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txt'
		}, {
			display : '�豸����',
			name : 'name',
			tclass : 'txt'
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
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '�������',
			name : 'suggestion',
			type : 'textarea'
		}]
	})
})
