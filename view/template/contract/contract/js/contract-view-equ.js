$(function (){

  //�����嵥
 $("#equInfo").yxeditgrid({
		objName : 'contract[equ]',
		url:'?model=contract_contract_equ&action=listJson',
		param: {
                'isDel': '0'
            },
    	type:'view',
    	param:{
    	       conProductId:$("#conProductId").val(),
    	       contractId:$("#contractId").val(),
    	       'isDel': '0'
    	      },
		colModel : [{
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'txt'
		},{
			display : '��������',
			name : 'productName',
			tclass : 'txt'
		}, {
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '����',
			name : 'price',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '���',
			name : 'money',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '������',
			name : 'warrantyPeriod',
			type : 'hidden',
			tclass : 'txtshort'
		}, {
			display : '��������Id',
			name : 'license',
			type : 'hidden'
		},{
			name : 'licenseButton',
			display : '��������',
			type : 'hidden',
			process : function(v,row){
				if(row.license != ""){
					return "<a href='#' onclick='showLicense(\""+ row.license + "\")'>�鿴</a>";
				}
			}
		}]
	});
});