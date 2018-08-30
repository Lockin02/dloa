$(function(){
	//省份
	$("#provincecity").yxcombogrid_province({
		hiddenId : 'provinceId',
		gridOptions : {
			showcheckbox : false
		}
	});

	//区域
	$("#areaName").yxcombogrid_area({
		hiddenId : 'areaCode',
		gridOptions : {
			showcheckbox : false
		}
	});

	//负责人
	$("#prinvipalName").yxselect_user({
		hiddenId : 'prinvipalId'
	});

	//客户
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

	//年月
	var monthVal = $("#month").val()*1;
	$("#beginMonth").val(monthVal);
	$("#endMonth").val(monthVal);

	//初始化所有合同
	initAllOrder();
});

function changeCondition(){
	var objType=$("#objType").val();
	switch(objType){
		case 'KPRK-01':var orderType='oa_sale_order';var objSign='是';break;
		case 'KPRK-02':var orderType='oa_sale_order';var objSign='否';break;
		case 'KPRK-03':var orderType='oa_sale_service';var objSign='是';break;
		case 'KPRK-04':var orderType='oa_sale_service';var objSign='否';break;
		case 'KPRK-05':var orderType='oa_sale_lease';var objSign='是';break;
		case 'KPRK-06':var orderType='oa_sale_lease';var objSign='否';break;
		case 'KPRK-07':var orderType='oa_sale_rdproject';var objSign='是';break;
		case 'KPRK-08':var orderType='oa_sale_rdproject';var objSign='否';break;
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


/*********************合同部分初始化***********************/

//合同部分初始化
$thisInitCode = 'objCode';
$thisInitId = 'objId';
$thisInitType = 'objType';

//初始化表格，带清空
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

//无清空加载grid ， 用于编辑款额分配
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
//初始化销售订单
function initOrder(){
	$("#" + $thisInitCode).yxcombogrid_orderforincome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"customerId":$("#customerId").val() , 'states' : '2,4,7,8','ExaStatus' : '完成','sign':'是', 'isTemp' : 0},
			showcheckbox :false
		}
	});
	$("#" + $thisInitCode).yxcombogrid_orderforincome("showCombo");
}

//初始化销售订单
function initOrderTemp(){
	$("#" + $thisInitCode).yxcombogrid_orderforincome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"customerId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '完成','sign':'否', 'isTemp' : 0},
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

//初始化服务合同
function initServiceContract(){
	$("#" + $thisInitCode).yxcombogrid_serviceContForIncome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"cusNameId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '完成','sign':'是', 'isTemp' : 0},
			showcheckbox :false
		}
	});
}

//初始化服务合同
function initServiceContractTemp(){
	$("#" + $thisInitCode).yxcombogrid_serviceContForIncome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"cusNameId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '完成','sign':'否' , 'isTemp' : 0},
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

//初始化租赁合同
function initRentalContract(){
	$("#" + $thisInitCode).yxcombogrid_rentalContForIncome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"tenantId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '完成','sign':'是', 'isTemp' : 0},
			showcheckbox :false
		}
	});
}

//初始化租赁合同
function initRentalContractTemp(){
	$("#" + $thisInitCode).yxcombogrid_rentalContForIncome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"tenantId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '完成','sign':'否', 'isTemp' : 0},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#" + $thisInitCode).yxcombogrid_rentalContForIncome('setText',data.orderTempCode);
				}
			}
		}
	});
}

//初始化研发合同
function initOrderRdproject(){
	$("#" + $thisInitCode).yxcombogrid_orderRdprojectForIncome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"cusNameId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '完成','sign':'是', 'isTemp' : 0},
			showcheckbox :false
		}
	});
}

//初始化研发合同
function initOrderRdprojectTemp(){
	$("#" + $thisInitCode).yxcombogrid_orderRdprojectForIncome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"cusNameId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '完成','sign':'否', 'isTemp' : 0},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#" + $thisInitCode).yxcombogrid_orderRdprojectForIncome('setText',data.orderTempCode);
				}
			}
		}
	});
}

//初始化所有类型表格
function initAllOrder(){
	$("#" + $thisInitCode).yxcombogrid_allorderforincome({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {"customerId":$("#customerId").val()  , 'states' : '2,4,7,8' , 'ExaStatus' : '完成'},
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