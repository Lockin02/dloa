$(function(){
	//ʡ��
	$("#provincecity").yxcombogrid_province({
		hiddenId : 'provinceId',
		gridOptions : {
			showcheckbox : false
		}
	});

	//����
	$("#areaName").yxcombogrid_area({
		hiddenId : 'areaCode',
		gridOptions : {
			showcheckbox : false
		}
	});

	//������
	$("#prinvipalName").yxselect_user({
		hiddenId : 'prinvipalId'
	});

	//�ͻ�
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		isShowButton : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$('#customerType').val(data.customerType);
				}
			}
		}
	});

	//����
	var monthVal = $("#month").val()*1;
	$("#beginMonth").val(monthVal);
	$("#endMonth").val(monthVal);

	//��ʼ�����к�ͬ
	initAllOrder();
});

function changeCondition(){
	var objType=$("#objType").val();
	switch(objType){
		case 'KPRK-01':var orderType='oa_sale_order';var objSign='��';break;
		case 'KPRK-02':var orderType='oa_sale_order';var objSign='��';break;
		case 'KPRK-03':var orderType='oa_sale_service';var objSign='��';break;
		case 'KPRK-04':var orderType='oa_sale_service';var objSign='��';break;
		case 'KPRK-05':var orderType='oa_sale_lease';var objSign='��';break;
		case 'KPRK-06':var orderType='oa_sale_lease';var objSign='��';break;
		case 'KPRK-07':var orderType='oa_sale_rdproject';var objSign='��';break;
		case 'KPRK-08':var orderType='oa_sale_rdproject';var objSign='��';break;
		default:var orderType='';var objSign='';break;
	}
	objParam = {
		"custId" : $("#customerId").val(),
		"customerType" : $("#customerType").val(),
		"searchYear" : $("#year").val(),
		"begin" : $("#beginMonth").val(),
		"areaName" : $("#areaName").val(),
		"end" : $("#endMonth").val(),
		"prinvipalId" : $("#prinvipalId").val(),
		"customerProvince" : $("#provincecity").val(),
		"orderId" : $("#objId").val(),
		"objSign" : objSign,
		"orderType" : orderType
	};

	var listGrid= opener.$("#receviableGrid").data('yxgrid');
	listGrid.options.param = objParam;
	listGrid.reload();
	self.close();
}


/*********************��ͬ���ֳ�ʼ��***********************/

//��ͬ���ֳ�ʼ��
$thisInitCode = 'objCode';
$thisInitId = 'objId';
$thisInitType = 'objType';

//��ʼ����񣬴����
function initGrid(thisVal){
	var thisObj = $("#" + $thisInitCode);
	thisObj.yxcombogrid_allorderforincome('remove');
	thisObj.yxcombogrid_orderforincome('remove');
	thisObj.yxcombogrid_serviceContForIncome('remove');
	thisObj.yxcombogrid_rentalContForIncome('remove');
	thisObj.yxcombogrid_orderRdprojectForIncome('remove');

	thisObj.val('');
	$("#" + $thisInitId).val('');

	initGridNoClear(thisVal)
}

//����ռ���grid �� ���ڱ༭������
function initGridNoClear(thisVal){
	switch(thisVal){
		case 'KPRK-01' : initOrder();break;
		case 'KPRK-02' : initOrderTemp();break;
		case 'KPRK-03' : initServiceContract();break;
		case 'KPRK-04' : initServiceContractTemp();break;
		case 'KPRK-05' : initRentalContract();break;
		case 'KPRK-06' : initRentalContractTemp();break;
		case 'KPRK-07' : initOrderRdproject();break;
		case 'KPRK-08' : initOrderRdprojectTemp();break;
		default : initAllOrder();break;
	}
}
//��ʼ�����۶���
function initOrder(){
	$("#" + $thisInitCode).yxcombogrid_orderforincome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"customerId":$("#customerId").val() , 'states' : '2,4,7,8','ExaStatus' : '���','sign':'��', 'isTemp' : 0},
			showcheckbox :false
		}
	});
	$("#" + $thisInitCode).yxcombogrid_orderforincome("showCombo");
}

//��ʼ�����۶���
function initOrderTemp(){
	$("#" + $thisInitCode).yxcombogrid_orderforincome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"customerId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '���','sign':'��', 'isTemp' : 0},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#" + $thisInitCode).yxcombogrid_orderforincome('setText',data.orderTempCode);
				}
			}
		}
	});
	$("#" + $thisInitCode).yxcombogrid_orderforincome("showCombo");
}

//��ʼ�������ͬ
function initServiceContract(){
	$("#" + $thisInitCode).yxcombogrid_serviceContForIncome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"cusNameId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '���','sign':'��', 'isTemp' : 0},
			showcheckbox :false
		}
	});
}

//��ʼ�������ͬ
function initServiceContractTemp(){
	$("#" + $thisInitCode).yxcombogrid_serviceContForIncome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"cusNameId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '���','sign':'��' , 'isTemp' : 0},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#" + $thisInitCode).yxcombogrid_serviceContForIncome('setText',data.orderTempCode);
				}
			}
		}
	});
	$("#" + $thisInitCode).yxcombogrid_serviceContForIncome("showCombo");
}

//��ʼ�����޺�ͬ
function initRentalContract(){
	$("#" + $thisInitCode).yxcombogrid_rentalContForIncome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"tenantId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '���','sign':'��', 'isTemp' : 0},
			showcheckbox :false
		}
	});
}

//��ʼ�����޺�ͬ
function initRentalContractTemp(){
	$("#" + $thisInitCode).yxcombogrid_rentalContForIncome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"tenantId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '���','sign':'��', 'isTemp' : 0},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#" + $thisInitCode).yxcombogrid_rentalContForIncome('setText',data.orderTempCode);
				}
			}
		}
	});
}

//��ʼ���з���ͬ
function initOrderRdproject(){
	$("#" + $thisInitCode).yxcombogrid_orderRdprojectForIncome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"cusNameId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '���','sign':'��', 'isTemp' : 0},
			showcheckbox :false
		}
	});
}

//��ʼ���з���ͬ
function initOrderRdprojectTemp(){
	$("#" + $thisInitCode).yxcombogrid_orderRdprojectForIncome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"cusNameId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '���','sign':'��', 'isTemp' : 0},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#" + $thisInitCode).yxcombogrid_orderRdprojectForIncome('setText',data.orderTempCode);
				}
			}
		}
	});
}

//��ʼ���������ͱ��
function initAllOrder(){
	$("#" + $thisInitCode).yxcombogrid_allorderforincome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"customerId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '���'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					if(data.orderCode == ""){
						$("#" + $thisInitCode).yxcombogrid_allorderforincome('setText',data.orderTempCode);
					}
					$("#" + $thisInitType).val(data.tablename);
					$("#" + $thisInitId).val(data.orgid);
				}
			}
		}
	});
}