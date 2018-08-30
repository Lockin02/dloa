// ������
function hideList(listId) {
	var temp = document.getElementById(listId);
	var tempH = document.getElementById(listId + "H");
	if (temp.style.display == '') {
		temp.style.display = "none";
		tempH.style.display = "";
	} else if (temp.style.display == "none") {
		temp.style.display = '';
		tempH.style.display = 'none';
	}
}

$(function() {
	// �˻�����
	$("#backequinfo").yxeditgrid({
		objName : 'exchange[backequ]',
		url:'?model=projectmanagent_exchange_exchangebackequ&action=listJson',
    	type:'view',
    	param:{
        	'exchangeId' : $("#exchangeId").val(),
			'isDel' : '0'
        },
		tableClass : 'form_in_table',
		isAddOneRow:false,
		isAdd : false,
		colModel : [{
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 200
		}, {
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '�ͺ�/�汾',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '�˻�����',
			name : 'number',
			tclass : 'txtshort',
			width : 100
		}, {
			display : 'ִ������',
			name : 'executedNum',
			tclass : 'txtshort',
			width : 100
		}]
	});
	// ������Ʒ
	$("#productInfo").yxeditgrid({
		objName : 'exchange[product]',
		url:'?model=projectmanagent_exchange_exchangeproduct&action=listJson',
    	type:'view',
    	param:{
        	'exchangeId' : $("#exchangeId").val(),
			'isDel' : '0'
        },
		tableClass : 'form_in_table',
		isAddOneRow:false,
		isAdd : false,
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
			},
			type : 'money'
		}, {
			display : '���',
			name : 'money',
			tclass : 'txtshort',
			process : function(v){
				return moneyFormat2(v);
			},
			type : 'money'
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
	// ��������
	$("#equinfo").yxeditgrid({
		objName : 'return[equ]',
		url:'?model=projectmanagent_exchange_exchangeequ&action=listJson',
    	type:'view',
    	param:{
        	'exchangeId' : $("#exchangeId").val(),
			'isDel' : '0'
        },
		tableClass : 'form_in_table',
		isAddOneRow:false,
		isAdd : false,
		colModel : [{
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 200
		}, {
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '�ͺ�/�汾',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '��������',
			name : 'number',
			tclass : 'txtshort',
			width : 100
		}, {
			display : 'ִ������',
			name : 'executedNum',
			tclass : 'txtshort',
			width : 100
		}]
	});
});

// ��ϸ���ϳɱ�
function equCoseView() {
	showThickboxWin('?model=contract_contract_contract&action=equCoseView&contractId='
			+ $("#cid").val()
			+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900');
}
