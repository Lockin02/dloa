$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
			if(confirm("你输入成功,确定提交吗?")){
				return true;
			}else{
				return false;
			}

		}
	});
    $("#suppName").formValidator({
        onshow: "请输入供应商名称",
        onfocus: "供应商名称至少2个字符,最多50个字符",
        oncorrect: "您输入的供应商名称可用"
    }).inputValidator({
        min: 2,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "供应商名称两边不能有空符号"
        },
        onerror: "你输入的名称不合法,请确认"
    });


    $("#busiCode").formValidator({
        onshow: "请输入业务编号",
        onfocus: "业务编号至少5个字符,最多50个字符",
        oncorrect: "您输入的业务编号可用"
    }).inputValidator({
        min: 5,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "业务编号两边不能有空符号"
        },
        onerror: "你输入的业务编号非法,请确认"
    });
	$("#regiCapital").formValidator({
		forcevalid : true,
		triggerevent : "change",
		onshow : "请输入注册资金（数字）",
		onfocus : "请输入数字",
		oncorrect : "你输入的内容正确"
	}).inputValidator({
		min : 1,
		max : 999999999999999999999999999,
		type : "value",

		onerror : "请输入注册资金"
	});
	$("#address").formValidator({
        onshow: "请输入供应商地址",
        oncorrect: "您输入的供应商地址正确"
    }).inputValidator({
        min: 1,
        max: 500,

        onerror: "你输入的地址不合法,请确认"
    });
	$("#products").formValidator({
        onshow: "请输入商品名称",
        oncorrect: "您输入的商品名称正确"
    }).inputValidator({
        min: 1,
        max: 500,

        onerror: "你输入的商品名称不合法,请确认"
    });
    $("#foundedDate").formValidator({
        onshow: "请选择创建日期",
        onfocus: "请选择日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,并创建日期不能为空"
    }); //.defaultPassed();
     $("#foundedDate").formValidator({
        onshow: "请选择创建日期",
        onfocus: "请选择日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,并创建日期不能为空"
    }); //.defaultPassed();})
    $("#effectDate").formValidator({
        onshow: "请选择供货生效日期",
        onfocus: "请选择日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,并计划供货生效日期为空"
    }); //.defaultPassed();

    $("#failureDate").formValidator({
        onshow: "请选择供货失效日期",
        onfocus: "请选择日期，不能小于供货失效日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,并供货失效日期不能为空"
    }).compareValidator({
		desid : "effectDate",
		operateor : ">=",
		onerror : "计划完成日期不能小于供货生效日期"
	}); // .defaultPassed();
})