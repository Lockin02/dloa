//����ύ���͡�Ϊ���ύ��ˡ����һ��ʶ�����app
function comitToApp(){

	document.getElementById('form1').action = "index1.php?model=contract_rental_rentalcontract&action=add&act=app";
}

$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function (msg){
      var prinvipalName = $("#hiresName").val();
      var areaName = $("#areaName").val();

      var prodcutId = $("#invbody").find("input[id^='productId']").val();
      var tep=$("#PreNum").val();
	  for(var i=1;i<=tep;i++){
         	var pequName=$("#PequName"+i).val();
         	if(pequName == undefined){
	  	      continue;
	  	  }else if( pequName != ''){
	  	      break;
	  	  }
      }
      if((prodcutId == '' || prodcutId == undefined) && (pequName==''||pequName==undefined)){
         alert ("����ȷ��д��Ʒ�嵥��");
         return false;

      }
      if (prinvipalName == ''){
             alert ("��ͬ�����˲���Ϊ�գ�");
             return false;
          }
      if (areaName == ''){
             alert ("��ͬ����������Ϊ�գ�");
             return false;
      }
      var shipCondition = $("#shipCondition").val();
      if(shipCondition == ''){
           alert("��ѡ�񷢻�������");
           return false;
      }
      var orderNature = $("#orderNature").val();
      if(orderNature == ''){
      	   alert("��ѡ���ͬ����!");
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

window.onload=function(){
	   $("#orderMoney_v").unFormValidator(true);
       var orderInput = $("#orderInput").val();
       if(orderInput == '0'){
          //��ʽ��ͬ��
	$("#orderCode").formValidator({
		onshow : "�������ͬ��",
		onfocus : "��ͬ������2���ַ������50���ַ�",
		oncorrect : "������ĺ�ͬ����ȷ"
	}).regexValidator({
		regexp : "num",
		datatype:"enum",
		onerror : "��ͬ����Ӣ�����������"
	})
	.ajaxValidator({
        type: "get",
        url: "index1.php",
        data: "model=contract_rental_rentalcontract&action=ajaxOrderCode",
        datatype: "json",

        success: function(data) {

            if (data == "1") {
                return true;
            } else {
                return false;
            }
        },
        error: function() {

            alert("������û�з������ݣ����ܷ�����æ��������");
        },
        onerror: "�����Ʋ����ã������",
        onwait: "���ڶ���Ŀ���ƽ��кϷ���У�飬���Ժ�..."
    });

	//��ʱ��ͬ�ţ���ҪΨһ����֤
	$("#orderTempCode").formValidator({
		onshow : "��������ʱ��ͬ��",
		onfocus : "��ͬ������2���ַ������50���ַ�",
		oncorrect : "���������ʱ��ͬ����Ч"
	}).regexValidator({
		regexp : "num",
		datatype:"enum",
		onerror : "��ͬ����Ӣ�����������"
	})
	.ajaxValidator({
        type: "get",
        url: "index1.php",
        data: "model=contract_rental_rentalcontract&action=ajaxOrderTempCode",
        datatype: "json",

        success: function(data) {

            if (data == "1") {
                return true;
            } else {
                return false;
            }
        },
        error: function() {

            alert("������û�з������ݣ����ܷ�����æ��������");
        },
        onerror: "�����Ʋ����ã������",
        onwait: "���ڶ���Ŀ���ƽ��кϷ���У�飬���Ժ�..."
    });

           $("#orderCode").unFormValidator(true);
       }else {
         $("#orderTempCode").attr('class',"readOnlyTxtNormal");
         $("#orderCode").attr('class',"readOnlyTxtNormal");
       }
    }

 /**
 * �ͻ�����
 */
	$("#tenantId").formValidator({
		onshow : "��ѡ��ͻ�����",
		onfocus : "��������2���ַ������50���ַ�",
		oncorrect : "�������������Ч"
	}).inputValidator({
		min : 1,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�������߲���Ϊ��"
		},
		onerror : "����������Ʋ��Ϸ�������������"
	});

 /**
 * Ԥ�ƺ�ͬ���
 */
	$("#orderTempMoney_v").formValidator({
		onshow : "������Ԥ�ƺ�ͬ���",
		onfocus : "������Ϊ����",
		oncorrect : "������Ľ����Ч"
	}).inputValidator({
		min : 5,
		max : 500,

		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "������߲���Ϊ��"
		},
		onerror : "����ȷ������"
	});
 /**
 * ǩԼ��ͬ���
 */
	$("#orderMoney_v").formValidator({
		onshow : "������ǩԼ��ͬ���",
		onfocus : "������Ϊ����",
		oncorrect : "������Ľ����Ч"
	}).inputValidator({
		min : 5,
		max : 500,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "������߲���Ϊ��"
		},
		onerror : "����ȷ������"
	});

 /**
 * ��ͬ����
 */
	$("#orderName").formValidator({
		onshow : "�������ͬ����",
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

/**************************************************************/
	 /**
	 * by maizp
	 */
//��֤����
function amount(id){
		var num = $("#"+id).val();
		num=num.match(/^0+[1-9]+/)?num=num.replace(/^0+/g,''):num.match(/^0+/)?num=num.replace(/0+/g,'0'):num;//ɾ������ǰ���0
	    var n  =  /^((^$)|0|[1-9]\d*)$/;
         if(n.test(num) == false){
             alert("����ֵ����");
             $("#"+id).val("");
         }else{
         	$("#"+id).val(num);
         }
	}