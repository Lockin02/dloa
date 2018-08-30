function toSupport(){
	var beginYearVal = $("#beginYear").val();
	var endYearVal = $("#endYear").val();
	var beginMonthVal = $("#beginMonth").val();
	var endMonthVal = $("#endMonth").val();
	var customerId = $("#customerId").val();
	var customerName = $("#customerName").val();
	var objId = $("#objId").val();
	var objCode = $("#objCode").val();


	if(beginYearVal == ""){
		alert("��ʼ��ݲ���Ϊ��");
		return false;
	}

	if(isNaN(beginYearVal)){
		alert("��������ȷ�Ŀ�ʼ���");
		return false;
	}


	if(endYearVal == ""){
		alert("������ݲ���Ϊ��");
		return false;
	}

	if(isNaN(endYearVal)){
		alert("��������ȷ�Ľ������");
		return false;
	}

	if(beginYearVal > endYearVal){
		alert("��ʼ�����ҪС�ڻ���ڽ������");
		return false;
	}

	if(beginYearVal == endYearVal){
		if( beginMonthVal > endMonthVal ){
			alert("��ʼ�·���ҪС�ڻ���ڽ����·�");
			return false;
		}
	}
	var objType = $("#objType").val();
	var url = '?model=finance_invoice_invoice&action=invoiceInfoList&objType=' + objType + "&beginYear=" + beginYearVal
			+ "&endYear=" + endYearVal
			+ "&beginMonth=" + beginMonthVal
			+ "&endMonth=" + endMonthVal
			+ "&customerId=" + customerId
			+ "&customerName=" + customerName
			+ "&objId=" + objId
			+ "&objCode=" + objCode
			;
	location = url;
}

$(function(){
	$("#beginMonth").val($("#month").val());
	$("#endMonth").val($("#month").val());


	//�ͻ�
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		height : 400,
		isShowButton : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					var getGrid = function() {
						return $("#objCode")
								.yxcombogrid_order("getGrid");
					}
					var getGridOptions = function() {
						return $("#objCode")
								.yxcombogrid_order("getGridOptions");
					}
					if (getGrid().reload) {
						getGridOptions().param = {
							"customerId" : data.id,
							"ExaStatus" : "���"
						};
						getGrid().reload();
					} else {
						getGridOptions().param = {
							"customerId" : data.id,
							"ExaStatus" : "���"
						}
					}
				}
			}
		}
	});

	//��ͬ���
	$("#objCode").yxcombogrid_order({
		hiddenId : 'objId',
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		height : 400,
		width : 700,
		gridOptions : {
			param : {"ExaStatus" : "���"},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					if(data.orderCode == ""){
						$("#objCode").yxcombogrid_order('setText',data.orderTempCode);
					}
				}
			}
		}
	});
});