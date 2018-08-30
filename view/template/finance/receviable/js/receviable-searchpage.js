
//人员渲染
$(document).ready(function(){
    $("#prinvipalName").yxselect_user({
		hiddenId : 'prinvipalId'
	});
    $("#areaPrincipal").yxselect_user({
		hiddenId : 'areaPrincipalId'
	});

	$("#customerProvince").yxcombogrid_province({
		hiddenId : 'customerProvinceId',
		height : 200,
		width : 400,
		gridOptions : {
			showcheckbox : false
		}
	});
});


/** ****************合同 区域负责人 --- 合同归属区域************************************** */
$(function() {
	$("#areaName").yxcombogrid_area({
		hiddenId : 'chargeId',
		width : 550,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#areaId").val(data.id);
				}
			}
		}
	});
});


$(function() {
	$("#customerName").yxcombogrid_customer({
		isShowButton : false,
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
				}
			}
		}
	});
	//所有合同的combogrid
	$("#orderCode").yxcombogrid_allorderforincome({
		hiddenId : 'orderId',
		height : 250,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					if(data.orderCode){
						$('#orderCode').val(data.orderCode);
					}else{
						$('#orderCode').val(data.orderTempCode);
					}
					$('#orderType').val(data.tablename);
				}
			}
		}
	});
	// 客户类型
	customerTypeArr = getData('KHLX');
	addDataToSelect(customerTypeArr, 'customerType');

	$('#beginMonth').val($('#monthStr').val());
	$('#endMonth').val($('#monthStr').val());
});
	function confirm() {
		var beginYear=$("#beginYear").val();
		var beginMonth=$("#beginMonth").val();
		var endYear=$("#endYear").val();
		var endMonth=$("#endMonth").val();
		var areaName=$("#areaName").val();
		var customerId=$("#customerId").val();
		var customerType=$("#customerType").val();
		var orderId=$("#orderId").val();
		var prinvipalId=$("#prinvipalId").val();
		var customerProvince = $("#customerProvince").val();
//		var areaPrincipalId=$("#areaPrincipalId").val();
		this.opener.location = "?model=finance_receviable_receviable&action=incomeAnalysis&beginMonth="+beginMonth
							+"&beginYear="+beginYear+"&endMonth="+endMonth+"&endYear="+endYear
							+"&areaName="+areaName+"&customerId="+customerId+"&customerType="+customerType
							+"&orderId="+orderId+"&prinvipalId="+prinvipalId
							+ "&customerProvince=" + customerProvince;
		this.close();
	}

	function refresh(){
		$('.toClear').val("");
	}
