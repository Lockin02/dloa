$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }

    });

 //验证鼎利合同号
$("#contNumber").formValidator({
		onshow : "请输入合同号",
		onfocus : "编号至少2个字符，最多50个字符",
		oncorrect : "您输入的合同号有效"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "合同号两边不能为空"
		},
		onerror : "您输入的合同号不合法，请重新输入"
	}).ajaxValidator({
        type: "get",
        url: "index1.php",
        data: "model=contract_sales_sales&action=ajaxContNumber",
        datatype: "json",

        success: function(data) {

            if (data == "1") {
                return true;
            } else {
                return false;
            }
        },
//        buttons: $("#submitSave"),
        error: function() {

            alert("服务器没有返回数据，可能服务器忙，请重试");
        },
        onerror: "该名称不可用，请更换",
        onwait: "正在对项目名称进行合法性校验，请稍候..."
    })


});