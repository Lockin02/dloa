$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {

        },
        onsuccess: function (msg){
             var cus = $("#customerName").val();
			   if(cus == ''){
			      alert("客户信息不能为空！");
			     return false;
			   }
			  var orderCode = $("#orderCode").val();
			  var orderTempCode = $("#orderTempCode").val();
			  var sign = $("input[name='order[sign]']:checked").val();
			  if(orderCode == '' && orderTempCode == ''){
			        alert ("合同号不能为空");
			        return false;
			  }
			  var orderMoney = $("#orderMoney").val();
			  var orderMoney_v = $("#orderMoney_v").val();
			  var orderTempMoney = $("#orderTempMoney").val();
			  var orderTempMoney_v = $("#orderTempMoney_v").val();
			  if((orderMoney == '' || orderMoney == '0' || orderMoney == '0.00') && (orderTempMoney == '' || orderTempMoney == '0' || orderTempMoney == '0.00')){
			        alert("合同金额不能为空");
			        return false;
			  }
			  var productId = $("#myequ").find("input[id^='productId']").val();
			  var pequName = $("#mycustom").find("input[id^='PequName']").val();
			  var borrowbody = $("#borrowbody").html();
			      if((productId == '' || productId == undefined) && (pequName == ''||pequName==undefined) && (borrowbody == '' || borrowbody == null)){
			         alert ("请正确填写产品清单！");
			         return false;
			      }
			  var deliveryDate = $("#deliveryDate").val();
			  if(deliveryDate == ''){
			      alert("计划交货日期不能为空");
			      return false;
			  }
			  var areaCode = $("#areaCode").val();
			  var areaName = $("#areaName").val();
			  if(areaCode =='' || areaName == ''){
			      alert("请正确选择合同归属区域");
			      return false;
			  }
			  var prinvipalId = $("#prinvipalId").val();
			  var prinvipalName = $("#prinvipalName").val();
			  if(prinvipalId == '' || prinvipalName == ''){
                  alert("请正确选择合同负责人");
                  return false;
			  }
			  var province = $("#province").val();
		      var city = $("#city").val();
		      if(city == '' || province == ''){
		           alert("请正确选择省市信息");
		           return false;
		      }
			  return true;
        }

    });
 /**
 * 订单名称
 */
	$("#orderName").formValidator({
		onshow : "请输入订单名称",
		onfocus : "名称至少2个字符，最多50个字符",
		oncorrect : "您输入的名称有效"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "名称两边不能为空"
		},
		onerror : "您输入的名称不合法，请重新输入"
	});
});

function ajaxCode(){
	if ($('#orderCode').val() == '') {
//		$('#icon').html('不能为空！');
//		$("#orderCode").focus();
	} else if ($('#orderCode').val() != '') {
		var param = {
			model : 'projectmanagent_order_order',
			action : 'ajaxCode',
			ajaxOrderCode : $('#orderCode').val()
		};
		if ($("#orderId").val() != '') {
			param.id = $("#orderId").val();
		}
		$.get('index1.php', param, function(data) {
					if (data == '1') {
						$('#icon').html('合同号已存在！');
						$("#orderCode").focus();
					} else {
						$('#icon').html('√');
					}
				})
	}
}

function ajaxTempCode(){
	if ($('#orderTempCode').val() == '') {
//		$('#icon1').html('不能为空！');
//		$("#orderTempCode").focus();
	} else if ($('#orderTempCode').val() != '') {
		var param = {
			model : 'projectmanagent_order_order',
			action : 'ajaxTempCode',
			ajaxOrderTempCode : $('#orderTempCode').val()
		};
		if ($("#orderId").val() != '') {
			param.id = $("#orderId").val();
		}
		$.get('index1.php', param, function(data) {
					if (data == '1') {
						$('#icon1').html('合同号已存在！');
						$("#orderTempCode").focus();
					} else {
						$('#icon1').html('√');
					}
				})
	}
}