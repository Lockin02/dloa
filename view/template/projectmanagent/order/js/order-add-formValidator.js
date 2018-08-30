$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function (msg){
      var prinvipalName = $("#prinvipalName").val();

      var areaCode = $("#areaCode").val();

      var productId = $("#myequ").find("input[id^='productId']").val();
      var tep = $("#PreNum").val();
	  for(var i=1;i<=tep;i++){
         	var pequName=$("#PequName"+i).val();
         	if(pequName == undefined){
             continue;
           }else  if(pequName != ''){
         	 break;
          }
      }
      if((productId == '' || productId == undefined) && (pequName == ''||pequName==undefined)){
         alert ("����ȷ��д��Ʒ�嵥��");
         return false;

      }
      if (prinvipalName == ''){
             alert ("��ͬ�����˲���Ϊ�գ�");
             return false;
          }
      if (areaCode == ''){
             alert ("����ȷѡ���ͬ��������");
             return false;
      }
      var deliveryDate = $("#deliveryDate").val();
      if(deliveryDate == ''){
            alert("�ƻ��������ڲ���Ϊ�գ�");
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
        data: "model=projectmanagent_order_order&action=ajaxOrderCode",
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
        data: "model=projectmanagent_order_order&action=ajaxOrderTempCode",
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
 * �ͻ�����
 */
	$("#customerId").formValidator({
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
//
///**
// *��������
// */
//$("#createTime").focus(function(){
//     WdatePicker({skin:'whyGreen',oncleared:function(){
//     $(this).blur();},
//     onpicked:function(){$(this).blur();}})}).formValidator({
//         onshow:"�����봴������",
//     	 onfocus:"�������ڲ���Ϊ��",
//     	 oncorrect:"����������ںϷ�"
//     }).inputValidator({
//     	 min:"1900-01-01",
//     	 max:"3000-01-01",
//     	 type:"date",
//     	 onerror:"���ڱ�����\"1900-01-01\"��\"3000-01-01\"֮��"});//.defaultPassed();


});

//��֤�绰
	function tel(Num){
	    var tel = $("#telephone"+Num).val();
	    var t = /(\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$/ ;
	    if(t.test(tel) == false){
	        alert ("����ȷ��д�绰��Ϣ��");
	        $("#telephone"+Num).val("");
	    }
	}

//��֤����
	function Email(Num){
	     var email = $("#Email"+Num).val();
	     var E =  /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
         if(E.test(email) == false){
             alert("����д��ȷ��������Ϣ");
             $("#Email"+Num).val("");
         }
	}

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













