//验证借试用转销售的可执行数量
						 function num(){
							   temp = $("#borproductNumber").val();
							   for(var i=1;i<= temp;i++){
							   	   var Num = $("#num"+i).val();
							       var proNum = $("#bornumber"+i).val();
							       if(proNum*1 > Num*1){
							          alert("产品数量已超过最大可填写数量");
							          $("#bornumber"+i).val(Num);
							       }
							       if(proNum*1 <= 0){
							          alert("产品数量不能为“0”");
							          $("#bornumber"+i).val(Num);
							       }
							   }
							}

//标记提交类型。为“提交审核”添加一个识别参数app
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
             alert ("合同负责人不能为空！");
             return false;
          }
      if (areaName == ''){
             alert ("合同所属区域不能为空！");
             return false;
      }

      var shipments = $("input[name='serviceContract[isShipments]']:checked").val();
      if(shipments == '是'){
          var shipCondition = $("#shipCondition").val();
          if(shipCondition == ''){
               alert("请选择发货条件！");
               return false;
          }
          var deliveryDate = $("#deliveryDate").val();
	      if (deliveryDate == ''){
	            alert("计划交货日期不能为空！");
	            return false;
	      }
          var productId = $("#invbody").find("input[id^='productId']").val();
	      var pequName =  $("#mycustom").find("input[id^='PequName']").val();
	      var borrowequId = $("#borrowequ").find("input[id^='borproductId']").val();
	      if((productId == '' || productId == undefined) && (pequName == ''||pequName==undefined) && (borrowequId == '' || borrowequId == undefined)){
	         alert ("请正确填写产品清单！");
	         return false;

	      }
      }
      var orderNature = $("#orderNature").val();
      if(orderNature == ''){
      	   alert("请选择合同属性!");
      	   return false;
      }
      var beginDate = $("#beginDate").val();
      var endDate = $("#endDate").val();
      if(beginDate == '' || endDate == ''){
          alert("请正确填写合同服务日期");
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
	$("#cusNameId").formValidator({
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
		oncorrect : "您输入的名称有效"
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
		oncorrect : "您输入的名称有效"
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


	$("#orderName").formValidator({
		onshow : "请输入合同名称",
		onfocus : "合同名称至少2个字符，最多50个字符",
		oncorrect : "您输入的合同名称有效"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "合同名称两边不能有空符号"
		}
//		onerror : "请检查您输入的合同签约方名称"
	});

})


//通过URL地址来判断是从哪个页面跳转过来的，作用是，添加成功之后，选择跳转回哪个页面
//但此方法会使添加页面的验证插件失效
//function getActionName(){
//	var actionName = window.opener.location.href;
//	alert('保存成功');
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
