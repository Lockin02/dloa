
$thisInitCode = 'saleCode';
$thisInitIn = 'saleId';
$thisInitType = 'saleType';
//��ʼ�����
function initGrid(thisVal){
	$("#" + $thisInitCode).yxcombogrid_order('remove');
	$("#" + $thisInitCode).yxcombogrid_serviceContract('remove');
	$("#" + $thisInitCode).yxcombogrid_rentalcontract('remove');
	$("#" + $thisInitCode).yxcombogrid_orderRdproject('remove');

	$("#" + $thisInitCode).val('');
	$("#" + $thisInitIn).val('');

	$("#outStockId").val('');
	$("#outStockCode").val('');

	initGridNoEmpty(thisVal);
}

//��ʼ�����,�����
function initGridNoEmpty(thisVal){
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

//��ʼ�����۶���
function initOrder(){
	$("#" + $thisInitCode).yxcombogrid_order({
		hiddenId : $thisInitIn,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {'ExaStatus' : '���'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					if(data.orderCode == "" ){
						$("#" + $thisInitCode).val(data.orderTempCode);
						$("#" + $thisInitType).val('KPRK-02');
					}else{
						$("#" + $thisInitType).val('KPRK-01');
					}
					$("#outStockId").val('');
					$("#outStockCode").val('');
				}
			}
		}
	});
}

//��ʼ�������ͬ
function initServiceContract(){
	$("#" + $thisInitCode).yxcombogrid_serviceContract({
		hiddenId : $thisInitIn,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {'ExaStatus' : '���'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					if(data.orderCode == "" ){
						$("#" + $thisInitCode).val(data.orderTempCode);
						$("#" + $thisInitType).val('KPRK-04');
					}else{
						$("#" + $thisInitType).val('KPRK-03');
					}
					$("#outStockId").val('');
					$("#outStockCode").val('');
				}
			}
		}
	});
}

//��ʼ�����޺�ͬ
function initRentalContract(){
	$("#" + $thisInitCode).yxcombogrid_rentalcontract({
		hiddenId : $thisInitIn,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {'ExaStatus' : '���'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					if(data.orderCode == "" ){
						$("#" + $thisInitCode).val(data.orderTempCode);
						$("#" + $thisInitType).val('KPRK-06');
					}else{
						$("#" + $thisInitType).val('KPRK-05');
					}
					$("#outStockId").val('');
					$("#outStockCode").val('');
				}
			}
		}
	});
}

//��ʼ���з���ͬ
function initOrderRdproject(){
	$("#" + $thisInitCode).yxcombogrid_orderRdproject({
		hiddenId : $thisInitIn,
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		gridOptions : {
			param : {'ExaStatus' : '���'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					if(data.orderCode == "" ){
						$("#" + $thisInitCode).val(data.orderTempCode);
						$("#" + $thisInitType).val('KPRK-08');
					}else{
						$("#" + $thisInitType).val('KPRK-07');
					}
					$("#outStockId").val('');
					$("#outStockCode").val('');
				}
			}
		}
	});
}