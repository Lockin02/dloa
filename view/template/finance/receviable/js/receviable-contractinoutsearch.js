
//��Ա��Ⱦ
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


/** ****************��ͬ �������� --- ��ͬ��������************************************** */
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
	//���к�ͬ��combogrid
	$("#orderCode").yxcombogrid_allcontract({
		hiddenId : 'orderId',
		height : 250,
		gridOptions : {
			param : {'ExaStatus': '���'},
			showcheckbox : false
		}
	});
	// �ͻ�����
	customerTypeArr = getData('KHLX');
	addDataToSelect(customerTypeArr, 'customerType');

	$('#periodYear').val($('#thisYear').val());
	$('#periodMonth').val($('#thisMonth').val());
});


function confirm() {
	var beginYear=$("#beginYear").val();
	var beginMonth=$("#beginMonth").val();
	var endYear=$("#endYear").val();
	var endMonth=$("#endMonth").val();
	var customerId=$("#customerId").val();
	var orderId=$("#orderId").val();
	var periodYear=$("#periodYear").val();
	var periodMonth=$("#periodMonth").val();
	this.opener.location = "?model=finance_receviable_receviable&action=contractInOut&beginMonth="+beginMonth
						+ "&beginYear="+beginYear
						+ "&endMonth="+endMonth
						+ "&endYear="+endYear
						+ "&customerId="+customerId
						+ "&orderId="+orderId
						+ "&periodYear="+periodYear
						+ "&periodMonth="+periodMonth
						;
	this.close();
}

function refresh(){
	$('.toClear').val("");
}
