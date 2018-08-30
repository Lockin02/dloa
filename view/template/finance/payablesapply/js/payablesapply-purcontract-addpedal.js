
$(function() {

	// ��ѡ�ͻ�
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
		isShowButton : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(){
					$("#objCode").yxcombogrid_purchcontract('remove');

					$("#objCode").val('');
					$("#objId").val('');
					initPurcontract();
			  	}
			}
		}
	});
});

//��ʼ������
function initPurcontract(){
	$("#objCode").yxcombogrid_purchcontract({
		hiddenId :  'objId',
		height : 250,
		width : 650,
		gridOptions : {
			isTitle : true,
			action : 'purDetailPageJson',
			param : { "csuppId" : $("#supplierId").val() , "sendUserId" : $("#sendUserId").val() ,'cannotpayed' : 1 , 'state' : 7 } ,
			event : {
				'row_check' : function(){

			  	}
			},colModel : [{
					display : 'id',
					name : 'id',
					hide : true,
					width:130
				},{
					display : '�������',
					name : 'hwapplyNumb',
					width:100
				},{
					display : '�ɹ�Ա',
					name : 'sendName',
					width:80
				},{
					display : '��Ӧ������',
					name : 'suppName',
					width:150
				},{
					display : '��Ӧ��Id',
					name : 'suppId',
					hide : true
				},{
					display : '���ݽ��',
					name : 'allMoney',
					process : function(v){
						return moneyFormat2(v);
					},
					width:80
				},{
					display : '�Ѹ����',
					name : 'payed',
					process : function(v){
						return moneyFormat2(v);
					},
					width:80
				}, {
		            name: 'allMoneyCur',
		            display: '��λ�ҽ��',
		            sortable: true,
		            process: function (v) {
		                if (v >= 0) {
		                    return moneyFormat2(v);
		                } else {
		                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
		                }
		            },
		            width: 80
		        }, {
		            name: 'currency',
		            display: '�������',
		            sortable: true,
		            width: 60
		        }, {
		            name: 'rate',
		            display: '����',
		            sortable: true,
		            width: 60
		        }, {
					display : '�ύ��Ʊ���',
					name : 'handInvoiceMoney',
					process : function(v){
						return moneyFormat2(v);
					},
					width:80
				}
			]
		}
	});
}


//����Դ�����͵���
function openNoSourceType(){
//	showModalWin("?model=finance_payablesapply_payablesapply&action=toAdd");
	showModalWin("?model=finance_payablesapply_payablesapply&action=toAddDept&sourceType=YFRK-04");
}


//�򿪲ɹ����͸����
function openPurcontract(){
	if($("#objId").val() == ""){
		alert("��ѡ���Ӧ�ɹ�����");
		return false;
	}
	showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&addType=push&objType=YFRK-01&objId=" + $("#objId").val());
}

function show_page(){
	parent.show_page();
	closeFun();
}
