$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){

		}
	});

	//供应商名称
    $("#suppName").formValidator({
        onshow: "请输入供应商名称",
        onfocus: "请输入供应商名称",
        oncorrect: "您输入的供应商名称可用"
    }).inputValidator({
        min: 2,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "供应商名称两边不能有空符号"
        },
        onerror: "你输入的名称不可用,请确认"
    }).ajaxValidator({
    	type : "get",
    	url : "index1.php" ,
    	data : "model=supplierManage_formal_flibrary&action=ajaxSuppName",
    	datatype : "json",
    	success : function(data){
    		if( data == "1" ){
    			return true;
    		}else{
    			return false;
    		}
    	},
    	//这个buttons是提交按钮的id名
    	buttons : $("#saveAndNext"),
    	error : function(){
    		alert("服务器没有返回数据，可能服务器忙，请重试");
    	},
    	onerror : "该名称不可用，请更换",
    	onwait : "正在对供应商名称进行合法性校验，请稍候..."
    });

    //注册资金
//	$("#registeredFunds").formValidator({
//		onshow : "请输入注册资金",
//		onfocus : "请输入注册资金",
//		oncorrect : "OK"
//	}).inputValidator({
//		min : 1,
//
//		onerror : "请输入注册资金"
//	});

	//供应商地址
	$("#address").formValidator({
        onshow: "请输入供应商地址",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
        max: 500,

        onerror: "请输入供应商地址"
    });

    //端口名称
	$("#products").formValidator({
        onshow: "请输入主要经营范围",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
        max: 500,

        onerror: "主要经营范围为空,请输入"
    });

    //端口名称
	$("#legalRepre").formValidator({
        onshow: "请输入法人代表",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
        max: 50,

        onerror: "法人代表为空,请输入"
    });

	//工商登记号
	$("#businRegistCode").formValidator({
		onshow : "请输入工商登记号",
		oncorrect : "OK"
	}).inputValidator({
		min:2,
		max:50,
		onerror: "请输入工商登记号"
	});

	//营业执照编号
	$("#businessCode").formValidator({
		onshow : "请输入营业执照编号",
		oncorrect : "OK"
	}).inputValidator({
		min:2,
		max:50,
		onerror: "请输入营业执照编号"
	});
/**
    //注册资金
	$("#registeredFunds").formValidator({
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

	//供应商编号
	$("#busiCode").formValidator({
		onshow : "请输入供应商编号",
		onfocus : "输入供应商编号",
		oncorrect : "OK"
	}).inputValidator({
		min:2,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"供应商编号两边不能有空格"
		}
	});

	//供应商地址
	$("#address").formValidator({
        onshow: "请输入供应商地址",
        oncorrect: "您输入的供应商地址正确"
    }).inputValidator({
        min: 1,
        max: 500,

        onerror: "你输入的地址不合法,请确认"
    });

    //端口名称
	$("#products").formValidator({
        onshow: "请输入经营范围",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
        max: 500,

        onerror: "经营范围为空,请输入"
    });

    //创建日期
    $("#registeredDate").formValidator({
        onshow: "请选择创建日期",
        onfocus: "请选择日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,并创建日期不能为空"
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
    });

    $("#effectDate").formValidator({
        onshow: "请选择供货生效日期",
        onfocus: "请选择日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,并计划供货生效日期为空"
    });

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
	});
	*/
})