$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {

        },
        onsuccess: function (msg){
             var cus = $("#customerName").val();
			   if(cus == ''){
			      alert("�ͻ���Ϣ����Ϊ�գ�");
			     return false;
			   }
			  var orderCode = $("#orderCode").val();
			  var orderTempCode = $("#orderTempCode").val();
			  var sign = $("input[name='order[sign]']:checked").val();
			  if(orderCode == '' && orderTempCode == ''){
			        alert ("��ͬ�Ų���Ϊ��");
			        return false;
			  }
			  var orderMoney = $("#orderMoney").val();
			  var orderMoney_v = $("#orderMoney_v").val();
			  var orderTempMoney = $("#orderTempMoney").val();
			  var orderTempMoney_v = $("#orderTempMoney_v").val();
			  if((orderMoney == '' || orderMoney == '0' || orderMoney == '0.00') && (orderTempMoney == '' || orderTempMoney == '0' || orderTempMoney == '0.00')){
			        alert("��ͬ����Ϊ��");
			        return false;
			  }
			  var productId = $("#myequ").find("input[id^='productId']").val();
			  var pequName = $("#mycustom").find("input[id^='PequName']").val();
			  var borrowbody = $("#borrowbody").html();
			      if((productId == '' || productId == undefined) && (pequName == ''||pequName==undefined) && (borrowbody == '' || borrowbody == null)){
			         alert ("����ȷ��д��Ʒ�嵥��");
			         return false;
			      }
			  var deliveryDate = $("#deliveryDate").val();
			  if(deliveryDate == ''){
			      alert("�ƻ��������ڲ���Ϊ��");
			      return false;
			  }
			  var areaCode = $("#areaCode").val();
			  var areaName = $("#areaName").val();
			  if(areaCode =='' || areaName == ''){
			      alert("����ȷѡ���ͬ��������");
			      return false;
			  }
			  var prinvipalId = $("#prinvipalId").val();
			  var prinvipalName = $("#prinvipalName").val();
			  if(prinvipalId == '' || prinvipalName == ''){
                  alert("����ȷѡ���ͬ������");
                  return false;
			  }
			  var province = $("#province").val();
		      var city = $("#city").val();
		      if(city == '' || province == ''){
		           alert("����ȷѡ��ʡ����Ϣ");
		           return false;
		      }
			  return true;
        }

    });
 /**
 * ��������
 */
	$("#orderName").formValidator({
		onshow : "�����붩������",
		onfocus : "��������2���ַ������50���ַ�",
		oncorrect : "�������������Ч"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�������߲���Ϊ��"
		},
		onerror : "����������Ʋ��Ϸ�������������"
	});
});

function ajaxCode(){
	if ($('#orderCode').val() == '') {
//		$('#icon').html('����Ϊ�գ�');
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
						$('#icon').html('��ͬ���Ѵ��ڣ�');
						$("#orderCode").focus();
					} else {
						$('#icon').html('��');
					}
				})
	}
}

function ajaxTempCode(){
	if ($('#orderTempCode').val() == '') {
//		$('#icon1').html('����Ϊ�գ�');
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
						$('#icon1').html('��ͬ���Ѵ��ڣ�');
						$("#orderTempCode").focus();
					} else {
						$('#icon1').html('��');
					}
				})
	}
}