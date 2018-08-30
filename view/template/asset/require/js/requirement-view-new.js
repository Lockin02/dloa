$(document).ready(function() {
	var requireDetails = eval("(" + $("#requireDetails").val() + ")");
	$("#itemTable").yxeditgrid({
		data : requireDetails,
		type:'view',
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
			name : 'devicename',
			tclass : 'txt'
		}, {
			display : '�豸����',
			name : 'devicedescription',
			validation : {
				required : true
			},
			tclass : 'txt'
		}, {
			display : '����',
			name : 'amount',
			tclass : 'txtshort'
		}, {
			display : 'Ԥ�ƽ��',
			name : 'expectAmount',
			tclass : 'txtshort',
			process : function(v,row){
				v = accMul(row.deviceprice,row.amount);
				return moneyFormat2(v);
			}
		}, {
			display : 'Ԥ�ƽ�������',
			name : 'borrowdate',
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
			name : 'advice',
			type : 'textarea'
		}]
	})
})
