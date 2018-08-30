$(function (){

  //发货清单
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
			display : '物料编号',
			name : 'productCode',
			tclass : 'txt'
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'txt'
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '单价',
			name : 'price',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '金额',
			name : 'money',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '保修期',
			name : 'warrantyPeriod',
			type : 'hidden',
			tclass : 'txtshort'
		}, {
			display : '加密配置Id',
			name : 'license',
			type : 'hidden'
		},{
			name : 'licenseButton',
			display : '加密配置',
			type : 'hidden',
			process : function(v,row){
				if(row.license != ""){
					return "<a href='#' onclick='showLicense(\""+ row.license + "\")'>查看</a>";
				}
			}
		}]
	});
});