

//初始化表格
function initGrid(thisVal){
	$("#objCode").yxcombogrid_order('remove');
	$("#objCode").yxcombogrid_serviceContract('remove');
	$("#objCode").yxcombogrid_rentalcontract('remove');
	$("#objCode").yxcombogrid_orderRdproject('remove');

	$("#objCode").val('');
	$("#objName").val('');
	$("#objId").val('');


	if( thisVal == 'KPRK-01' || thisVal == 'KPRK-02'){
		initOrder();
	}else if( thisVal == 'KPRK-03' || thisVal == 'KPRK-04'){
		initServiceContract();
	}else if( thisVal == 'KPRK-05' || thisVal == 'KPRK-06'){
		initRentalContract();
	}else if( thisVal == 'KPRK-07' || thisVal == 'KPRK-08'){
		initOrderRdproject();
	}else{
	}
}
//初始化销售订单
function initOrder(){
	$("#objCode").yxcombogrid_order({
		hiddenId : 'objId',
		gridOptions : {
			param : {"customerId":$("#customerId").val() , 'states' : '2'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#objCode").val(data.orderCode);
					if(data.orderCode == "" ){
						$("#objCode").val(data.orderTempCode);
						$("#objType").val('KPRK-02');
					}else{
						$("#objType").val('KPRK-01');
					}
					$("#objName").val(data.orderName);
					$("#customerName").val(data.customerName);
					$("#customerId").val(data.customerId);
					$("#customerTypeView").val(data.customerType);
					$("#customerType").val(data.customerType);
					$("#contAmount").val(data.orderMoney);
					$("#contAmountView").val(moneyFormat2(data.orderMoney));
				}
			}
		}
	});
}

//初始化服务合同
function initServiceContract(){
	$("#objCode").yxcombogrid_serviceContract({
		hiddenId : 'objId',
		gridOptions : {
			param : {"cusId":$("#customerId").val() , 'states' : '2'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#objCode").val(data.orderCode);
					if(data.orderCode == "" ){
						$("#objCode").val(data.orderTempCode);
						$("#objType").val('KPRK-04');
					}else{
						$("#objType").val('KPRK-03');
					}
					$("#objName").val(data.orderName);
					$("#customerName").val(data.cusName);
					$("#customerId").val(data.cusId);
					$("#customerTypeView").val(data.customerType);
					$("#customerType").val(data.customerType);
					$("#contAmount").val(data.orderMoney);
					$("#contAmountView").val(moneyFormat2(data.orderMoney));
				}
			}
		}
	});
}

//初始化租赁合同
function initRentalContract(){
	$("#objCode").yxcombogrid_rentalcontract({
		hiddenId : 'objId',
		gridOptions : {
			param : {"tenantId":$("#customerId").val() , 'states' : '2'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#objCode").val(data.orderCode);
					if(data.orderCode == "" ){
						$("#objCode").val(data.orderTempCode);
						$("#objType").val('KPRK-08');
					}else{
						$("#objType").val('KPRK-07');
					}
					$("#objName").val(data.orderName);
					$("#customerName").val(data.tenantName);
					$("#customerId").val(data.tenantId);
					$("#customerTypeView").val(data.customerType);
					$("#customerType").val(data.customerType);
					$("#contAmount").val(data.orderMoney);
					$("#contAmountView").val(moneyFormat2(data.orderMoney));
				}
			}
		}
	});
}

//初始化研发合同
function initOrderRdproject(){
	$("#objCode").yxcombogrid_orderRdproject({
		hiddenId : 'objId',
		gridOptions : {
			param : {"cusNameId":$("#customerId").val() , 'states' : '2'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#objCode").val(data.orderCode);
					if(data.orderCode == "" ){
						$("#objCode").val(data.orderTempCode);
						$("#objType").val('KPRK-06');
					}else{
						$("#objType").val('KPRK-05');
					}
					$("#objName").val(data.orderName);
					$("#customerName").val(data.cusName);
					$("#customerId").val(data.cusNameId);
					$("#customerTypeView").val(data.customerType);
					$("#customerType").val(data.customerType);
					$("#contAmount").val(data.orderMoney);
					$("#contAmountView").val(moneyFormat2(data.orderMoney));
				}
			}
		}
	});
}