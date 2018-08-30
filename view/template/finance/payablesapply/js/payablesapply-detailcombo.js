
$thisInitCode = 'objCode';
$thisInitId = 'objId';
$thisInitName = 'objName';
$thisInitType = 'objType';

/**
 * ������ϸ������ʼ������
 * �ڳ�ʼ����ͬʱ���ȥ������ϵ
 * @param {} mycount
 */
function initGrid(thisVal,mycount){

	var thisObj = $("#" + $thisInitType + mycount);

	if(thisObj.val() != "" && $("#supplierId").val() == ""){
		alert('����ѡ��Ӧ��');
		thisObj.val("");
		return false;
	}

	var objCode = $("#objCode" + mycount );
	//����������
	objCode.yxcombogrid_purchcontract('remove');
	objCode.val("");

	$("#" + $thisInitId + mycount ).val("");

	$("#purchaseMoney" + mycount ).val("");
	$("#payed" + mycount ).val("");
	$("#handInvoiceMoney" + mycount ).val("");

	if(thisObj.val() != 'YFRK-01'){
		alert('�ǲɹ�����ҵ�����ڶ�Ӧҵ�������븶��');
		thisObj.val("");
	}
	//������Ϊ���ۺ�ͬʱ,��ʼ�����ۺ�ͬgrid
	initGridNoClear(mycount);
}

//��ʼ��grid,����ȡ��������ϵ
function initGridNoClear(mycount){
	var thisObj = $("#objType" + mycount);
	//������Ϊ���ۺ�ͬʱ,��ʼ�����ۺ�ͬgrid
	if(thisObj.val() == 'YFRK-01' ){
		if($("#owner").val() == ""){
			$("#objCode"+ mycount).yxcombogrid_purchcontract({
				hiddenId :  'objId'+ mycount,
				height : 250,
				width : 700,
				gridOptions : {
					action : 'purDetailPageJson',
					showcheckbox : false,
					param : { 'csuppId' : $('#supplierId').val(), 'cannotpayed' : 1 , 'state' : 7 } ,
					event : {
						'row_dblclick' : function(mycount){
							return function(e, row, data) {
								$("#oldMoney" + mycount).val(data.canApply);
								$("#money" + mycount).val(data.canApply);
								$("#money" + mycount + "_v").val(moneyFormat2(data.canApply));
								$("#purchaseMoney" + mycount).val(data.allMoney);
								$("#purchaseMoney" + mycount + "_v").val(moneyFormat2(data.allMoney));
								$("#payed" + mycount).val(data.payed);
								$("#payed" + mycount + "_v").val(moneyFormat2(data.payed));
								$("#handInvoiceMoney" + mycount).val(data.handInvoiceMoney);
								$("#handInvoiceMoney" + mycount + "_v").val(moneyFormat2(data.handInvoiceMoney));
								$("#money" + mycount + "_v").focus();
							};
					  	}(mycount)
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
						},{
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
		}else{
			if($("#salesmanId").val() == ""){
				var thisMan = $("#createId").val();
			}else{
				var thisMan = $("#salesmanId").val();
			}
			$("#objCode"+ mycount).yxcombogrid_purchcontract({
				hiddenId :  'objId'+ mycount,
				height : 250,
				width : 700,
				gridOptions : {
					action : 'purDetailPageJson',
					showcheckbox : false,
					param : { 'csuppId' : $('#supplierId').val() , 'createId':thisMan , 'cannotpayed' : 1, 'state' : 7},
					event : {
						'row_dblclick' : function(mycount){
							return function(e, row, data) {
								$("#oldMoney" + mycount).val(data.canApply);
								$("#money" + mycount).val(data.canApply);
								$("#money" + mycount + "_v").val(moneyFormat2(data.canApply));
								$("#purchaseMoney" + mycount).val(data.allMoney);
								$("#purchaseMoney" + mycount + "_v").val(moneyFormat2(data.allMoney));
								$("#payed" + mycount).val(data.payed);
								$("#payed" + mycount + "_v").val(moneyFormat2(data.payed));
								$("#handInvoiceMoney" + mycount).val(data.handInvoiceMoney);
								$("#handInvoiceMoney" + mycount + "_v").val(moneyFormat2(data.handInvoiceMoney));
								$("#money" + mycount + "_v").focus();
							};
					  	}(mycount)
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
						},{
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
	}
}

//�༭ʱ��ʼ���б�gird
$(function(){
	var invnumber = $('#coutNumb').val();
	for(var i = 1;i <= invnumber;i++){
		initGridNoClear(i);
	}
})

