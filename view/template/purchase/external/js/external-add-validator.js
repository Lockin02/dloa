$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
//			return true;
		}
	});


    $("#sendTime").formValidator({
        onshow: "请选择申请日期",
        onfocus: "请选择申请日期",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,申请日期为空"
    }).compareValidator({
		desid : "dateHope",
		operateor : "<=",
		onerror : "申请日期不能大于期望完成日期"
	});;

    $("#dateHope").formValidator({
        onshow: "请选择期望完成日期",
        onfocus: "请选择日期，不能小于下达日期",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,期望完成日期不能为空"
    }).compareValidator({
		desid : "sendTime",
		operateor : ">=",
		onerror : "期望完成日期不能小于申请日期"
	});
    $("#batchNumb").formValidator({
    	onshow: "请输入批次号",
    	onfocus: "请输入批次号",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"请输入批次号"
    });

    $("#depName").formValidator({
    	onshow: "请选择申请部门",
    	onfocus: "请选择申请部门",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"请选择申请部门"
    });


    $("#rdprojectSourceName").formValidator({
    	onshow: "请选择源单据号",
    	onfocus: "请选择源单据号",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"请选择源单据号"
    });




    $("#purchDepart").formValidator({
    	onshow: "请选择采购部门",
    	onfocus: "请选择采购部门",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"请选择采购部门"
    });

     $("#sendName").formValidator({
    	onshow: "请选择申请人名称",
    	onfocus: "请选择申请人名称",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"请选择下达人名称"
    });
    $("#applyReason").formValidator({
    	onshow: "请输入申请原因",
    	onfocus: "请输入申请原因",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"请输入申请原因"
    })

//     $("select").formValidator({
//    	onshow: "请选择采购类型",
//    	onfocus: "请选择采购类型",
//    	oncorrect: "OK"
//    }).inputValidator({
//    	min:1,
//    	onerror:"请选择采购类型"
//    });


})