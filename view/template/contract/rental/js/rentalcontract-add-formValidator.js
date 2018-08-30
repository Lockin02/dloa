//标记提交类型。为“提交审核”添加一个识别参数app
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
         alert ("请正确填写产品清单！");
         return false;

      }
      if (prinvipalName == ''){
             alert ("合同负责人不能为空！");
             return false;
          }
      if (areaName == ''){
             alert ("合同所属区域不能为空！");
             return false;
      }
      var shipCondition = $("#shipCondition").val();
      if(shipCondition == ''){
           alert("请选择发货条件！");
           return false;
      }
      var orderNature = $("#orderNature").val();
      if(orderNature == ''){
      	   alert("请选择合同属性!");
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

window.onload=function(){
	   $("#orderMoney_v").unFormValidator(true);
       var orderInput = $("#orderInput").val();
       if(orderInput == '0'){
          //正式合同号
	$("#orderCode").formValidator({
		onshow : "请输入合同号",
		onfocus : "合同号至少2个字符，最多50个字符",
		oncorrect : "您输入的合同号正确"
	}).regexValidator({
		regexp : "num",
		datatype:"enum",
		onerror : "合同号由英文与数字组成"
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

            alert("服务器没有返回数据，可能服务器忙，请重试");
        },
        onerror: "该名称不可用，请更换",
        onwait: "正在对项目名称进行合法性校验，请稍候..."
    });

	//临时合同号，需要唯一性验证
	$("#orderTempCode").formValidator({
		onshow : "请输入临时合同号",
		onfocus : "合同号至少2个字符，最多50个字符",
		oncorrect : "您输入的临时合同号有效"
	}).regexValidator({
		regexp : "num",
		datatype:"enum",
		onerror : "合同号由英文与数字组成"
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

            alert("服务器没有返回数据，可能服务器忙，请重试");
        },
        onerror: "该名称不可用，请更换",
        onwait: "正在对项目名称进行合法性校验，请稍候..."
    });

           $("#orderCode").unFormValidator(true);
       }else {
         $("#orderTempCode").attr('class',"readOnlyTxtNormal");
         $("#orderCode").attr('class',"readOnlyTxtNormal");
       }
    }

 /**
 * 客户名称
 */
	$("#tenantId").formValidator({
		onshow : "请选择客户名称",
		onfocus : "名称至少2个字符，最多50个字符",
		oncorrect : "您输入的名称有效"
	}).inputValidator({
		min : 1,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "名称两边不能为空"
		},
		onerror : "您输入的名称不合法，请重新输入"
	});

 /**
 * 预计合同金额
 */
	$("#orderTempMoney_v").formValidator({
		onshow : "请输入预计合同金额",
		onfocus : "金额必须为数字",
		oncorrect : "您输入的金额有效"
	}).inputValidator({
		min : 5,
		max : 500,

		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "金额两边不能为空"
		},
		onerror : "请正确输入金额"
	});
 /**
 * 签约合同金额
 */
	$("#orderMoney_v").formValidator({
		onshow : "请输入签约合同金额",
		onfocus : "金额必须为数字",
		oncorrect : "您输入的金额有效"
	}).inputValidator({
		min : 5,
		max : 500,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "金额两边不能为空"
		},
		onerror : "请正确输入金额"
	});

 /**
 * 合同名称
 */
	$("#orderName").formValidator({
		onshow : "请输入合同名称",
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

/**************************************************************/
	 /**
	 * by maizp
	 */
//验证数量
function amount(id){
		var num = $("#"+id).val();
		num=num.match(/^0+[1-9]+/)?num=num.replace(/^0+/g,''):num.match(/^0+/)?num=num.replace(/0+/g,'0'):num;//删除数字前面的0
	    var n  =  /^((^$)|0|[1-9]\d*)$/;
         if(n.test(num) == false){
             alert("输入值有误");
             $("#"+id).val("");
         }else{
         	$("#"+id).val(num);
         }
	}