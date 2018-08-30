$(function (){
$("#linkmanListInfo").yxeditgrid({
	objName : 'contract[linkman]',
	url:'?model=contract_contract_linkman&action=listJsonLimit',
	type:'view',
	param:{
		'contractId':$("#contractId").val(),
		'prinvipalId':$("#prinvipalId").val(),
		'createId':$("#createId").val(),
		'areaPrincipalId':$("#areaPrincipalId").val(),
			'isTemp' : '1',
			'isDel' : '0'
	},
	tableClass : 'form_in_table',
	colModel : [{
		display : '�ͻ���ϵ��',
		name : 'linkmanName',
		tclass : 'txt'
	}, {
		display : '��ϵ��ID',
		name : 'linkmanId',
		type : 'hidden'
	}, {
		display : '�绰',
		name : 'telephone',
		tclass : 'txt'
	}, {
		display : 'QQ',
		name : 'QQ',
		tclass : 'txt'
	}, {
		display : '����',
		name : 'Email',
		tclass : 'txt'
	}, {
		display : '��ע',
		name : 'remark',
		tclass : 'txt'
	}]
});

  //��Ʒ�嵥
 $("#productInfo").yxeditgrid({
		objName : 'contract[product]',
		url:'?model=contract_contract_product&action=listJsonLimit',
    	type:'view',
    	tableClass : 'form_in_table',
    	param:{
        	'contractId' : $("#contractId").val(),
        	'dir' : 'ASC',
			'prinvipalId':$("#prinvipalId").val(),
			'createId':$("#createId").val(),
			'areaPrincipalId':$("#areaPrincipalId").val(),
			'isTemp' : '1',
			'isDel' : '0'
        },
		colModel : [{
			display : '��Ʒ����',
			name : 'conProductName',
			tclass : 'txt',
			process : function(v, row) {
							 	return  '<a title=����鿴�����嵥 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
									+ row.id
									+ '&contractId='
									+ $("#contractId").val()
									+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'+"<font color = '#4169E1'>" + v +"</font>"+'</a>';
			            }
		}, {
			display : '��ƷId',
			name : 'conProductId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '��Ʒ����',
			name : 'conProductDes',
			tclass : 'txt'
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '���',
			name : 'money',
			tclass : 'txtshort',
			process : function(v){
				return moneyFormat2(v);
			}
//		}, {
//			display : '������',
//			name : 'warrantyPeriod',
//			tclass : 'txtshort'
		}, {
			display : '��������Id',
			name : 'license',
			type : 'hidden'
		},{
			name : 'licenseButton',
			display : '��������',
			process : function(v,row){
				if(row.license != ""){
					return "<a href='#' onclick='showLicense(\""+ row.license + "\")'>�鿴</a>";
				}
			}
		}, {
			display : '��Ʒ����Id',
			name : 'deploy',
			type : 'hidden'
		},{
			name : 'deployButton',
			display : '��Ʒ����',
			process : function(v,row){
				if(row.deploy != ""){
					return "<a href='#' onclick='showGoods(\""+ row.deploy + "\",\""+ row.conProductName + "\")'>�鿴</a>";
				}
			}
		}],
		event : {
			'reloadData' : function(e){
				initCacheInfo();
			}
		}
	});
 //�����嵥
 $("#equInfo").yxeditgrid({
		objName : 'contract[equ]',
		url:'?model=contract_contract_equ&action=listJson',
    	type:'view',
    	tableClass : 'form_in_table',
    	param:{
    	    'contractId':$("#contractId").val(),
			'isTemp' : '1',
			'isDel' : '0'
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
			type : 'hidden',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '���',
			name : 'money',
			type : 'hidden',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '������',
			name : 'warrantyPeriod',
			tclass : 'txtshort'
		}, {
			display : '��������Id',
			name : 'license',
			type : 'hidden'
		},{
			name : 'licenseButton',
			display : '��������',
			process : function(v,row){
				if(row.license != ""){
					return "<a href='#' onclick='showLicense(\""+ row.license + "\")'>�鿴</a>";
				}
			}
		}]
	});
//��Ʊ�ƻ�
	$("#invoiceListInfo").yxeditgrid({
		objName : 'contract[invoice]',
		url:'?model=contract_contract_invoice&action=listJson',
        type:'view',
        tableClass : 'form_in_table',
    	param:{
    	  contractId:$("#contractId").val(),
			'isTemp' : '1',
			'isDel' : '0'
    	  },
		colModel : [{
			display : '��Ʊ���',
			name : 'money',
			tclass : 'txt'
		}, {
			display : '������',
			name : 'softMoney',
			tclass : 'txt'
		}, {
			display : '��Ʊ����',
			name : 'iType',
			type : 'select',
			datacode : 'FPLX'
		}, {
			display : '��Ʊ����',
			name : 'invDT',
			type : 'date'
		}, {
			display : '��Ʊ����',
			name : 'remark',
			tclass : 'txt'
		}]
	});

//�տ�ƻ�
	$("#incomeListInfo").yxeditgrid({
		objName : 'contract[income]',
		url:'?model=contract_contract_receiptplan&action=listJson',
    	type:'view',
    	tableClass : 'form_in_table',
    	param:{
    	   contractId:$("#contractId").val(),
			'isTemp' : '1',
			'isDel' : '0'

    	   },
		colModel : [{
			display : '�տ���',
			name : 'money',
			tclass : 'txt'
		}, {
			display : '�տ�����',
			name : 'payDT',
			type : 'date'
		}, {
			display : '�տʽ',
			name : 'pType',
			tclass : 'txt'
		}, {
			display : '�տ�����',
			name : 'collectionTerms',
			tclass : 'txtlong'
		}]
	});

//��ѵ�ƻ�
	$("#trainListInfo").yxeditgrid({
		objName : 'contract[train]',
		url:'?model=contract_contract_trainingplan&action=listJson',
    	type:'view',
    	tableClass : 'form_in_table',
    	param:{contractId:$("#contractId").val(),
			'isTemp' : '1',
			'isDel' : '0'},
		colModel : [{
			display : '��ѵ��ʼ����',
			name : 'beginDT',
			type : 'date'
		}, {
			display : '��ѵ��������',
			name : 'endDT',
			type : 'date'
		}, {
			display : '��������',
			name : 'traNum',
			tclass : 'txtshort'
		}, {
			display : '��ѵ�ص�',
			name : 'adress',
			tclass : 'txtshort'
		}, {
			display : '��ѵ����',
			name : 'content',
			tclass : 'txtshort'
		}, {
			display : '��ѵ����ʦҪ��',
			name : 'trainer',
			tclass : 'txtshort'
		}]
	});

});
	//������
  function hideList(listId){
	        var temp = document.getElementById(listId);
			var tempH = document.getElementById(listId+"H");
				if (temp.style.display == ''){
					temp.style.display = "none";
					tempH.style.display = "";
				} else if (temp.style.display == "none"){
					temp.style.display = '';
					tempH.style.display = 'none';
				}
      }
   $(function() {
			var currency = $("#currency").html();
			if (currency != '�����' && currency != '') {
				document.getElementById("currencyRate").style.display = "";
			}
		});