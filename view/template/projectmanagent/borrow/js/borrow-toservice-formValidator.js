//��֤������ת���۵Ŀ�ִ������
						 function num(){
							   temp = $("#borproductNumber").val();
							   for(var i=1;i<= temp;i++){
							   	   var Num = $("#num"+i).val();
							       var proNum = $("#bornumber"+i).val();
							       if(proNum*1 > Num*1){
							          alert("��Ʒ�����ѳ���������д����");
							          $("#bornumber"+i).val(Num);
							       }
							       if(proNum*1 <= 0){
							          alert("��Ʒ��������Ϊ��0��");
							          $("#bornumber"+i).val(Num);
							       }
							   }
							}

//����ύ���͡�Ϊ���ύ��ˡ����һ��ʶ�����app
function comitToApp(){
	document.getElementById('form1').action = "index1.php?model=projectmanagent_borrow_borrow&action=toServiceAdd&act=app";
}


$(document).ready(function(){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){}
		,
		onsuccess: function (msg){
      var prinvipalName = $("#orderPrincipal").val();
      var areaName = $("#areaName").val();

      if (prinvipalName == ''){
             alert ("��ͬ�����˲���Ϊ�գ�");
             return false;
          }
      if (areaName == ''){
             alert ("��ͬ����������Ϊ�գ�");
             return false;
      }

      var shipments = $("input[name='serviceContract[isShipments]']:checked").val();
      if(shipments == '��'){
          var shipCondition = $("#shipCondition").val();
          if(shipCondition == ''){
               alert("��ѡ�񷢻�������");
               return false;
          }
          var deliveryDate = $("#deliveryDate").val();
	      if (deliveryDate == ''){
	            alert("�ƻ��������ڲ���Ϊ�գ�");
	            return false;
	      }
          var productId = $("#invbody").find("input[id^='productId']").val();
	      var pequName =  $("#mycustom").find("input[id^='PequName']").val();
	      var borrowequId = $("#borrowequ").find("input[id^='borproductId']").val();
	      if((productId == '' || productId == undefined) && (pequName == ''||pequName==undefined) && (borrowequId == '' || borrowequId == undefined)){
	         alert ("����ȷ��д��Ʒ�嵥��");
	         return false;

	      }
      }
      var orderNature = $("#orderNature").val();
      if(orderNature == ''){
      	   alert("��ѡ���ͬ����!");
      	   return false;
      }
      var beginDate = $("#beginDate").val();
      var endDate = $("#endDate").val();
      if(beginDate == '' || endDate == ''){
          alert("����ȷ��д��ͬ��������");
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
        data: "model=engineering_serviceContract_serviceContract&action=ajaxOrderCode",
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
        data: "model=engineering_serviceContract_serviceContract&action=ajaxOrderTempCode",
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
	$("#cusNameId").formValidator({
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
		oncorrect : "�������������Ч"
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
		oncorrect : "�������������Ч"
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


	$("#orderName").formValidator({
		onshow : "�������ͬ����",
		onfocus : "��ͬ��������2���ַ������50���ַ�",
		oncorrect : "������ĺ�ͬ������Ч"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "��ͬ�������߲����пշ���"
		}
//		onerror : "����������ĺ�ͬǩԼ������"
	});

})


//ͨ��URL��ַ���ж��Ǵ��ĸ�ҳ����ת�����ģ������ǣ���ӳɹ�֮��ѡ����ת���ĸ�ҳ��
//���˷�����ʹ���ҳ�����֤���ʧЧ
//function getActionName(){
//	var actionName = window.opener.location.href;
//	alert('����ɹ�');
//	parent.close();
//	if(actionName == 'toUnDoContractList'){
//		window.opener.location('?model=engineering_serviceContract_serviceContract&action=toUnDoContractList');
//	}else if(actionName == 'toMyApplicationTab'){
//		window.opener.location('?model=engineering_serviceContract_serviceContract&action=toMyApplicationTab');
//	}
//	return actionName;
//}


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
