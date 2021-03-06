$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function() {
            if (confirm("您输入成功，确定保存吗？")) {
                return true;
            } else {
                return false;
            }
        }
    });

//    $("#groupSName").formValidator({
//        onshow: "请选择组合",
//        oncorrect: "您选择的组合可用"
//    }).inputValidator({
//        min: 2,
//        max: 50,
//        nValue:"请选择...",
//        empty: {
//            leftempty: false,
//            rightempty: false,
//            emptyerror: "组合两边不能有空符号"
//        },
//        onerror: "你选择的组合非法,请确认"
//    }).functionValidator({
//        fun: function(val, elem) {
//            if (val != "请选择...") {
//                return true;
//            } else {
//                return "请选择项目组合";
//            }
//        }
//    });
    $("#IpoId").formValidator({
        onshow: "请选择募投项目",
        onfocus: "募投项目是必选项",
        oncorrect: "谢谢你的配合"
    }).inputValidator({
        min: 1,
        onerror: "你是不是忘记选择募投项目了!"
    });

    $("#projectName").formValidator({
        onshow: "请输入项目名称",
        onfocus: "项目名称至少2个字符,最多50个字符",
        oncorrect: "您输入的项目名称可用"
    }).inputValidator({
        min: 2,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "项目名称两边不能有空符号"
        },
        onerror: "你输入的名称不合法,请确认"
    }).ajaxValidator({
        type: "get",
        url: "index1.php",
        data: "model=rdproject_project_rdproject&action=ajaxProjectName",
        datatype: "json",
        success: function(data) {
            if (data == "1") {
                return true;
            } else {
                return false;
            }
        },
        buttons: $("#submitSave"),
        error: function() {
            alert("服务器没有返回数据，可能服务器忙，请重试");
        },
        onerror: "该名称不可用，请更换",
        onwait: "正在对项目名称进行合法性校验，请稍候..."
    });




//    $("#simpleName").formValidator({
//        onshow: "请输入项目简称",
//        onfocus: "简称至少2个字符,最多20个字符",
//        oncorrect: "您输入的简称可用"
//    }).inputValidator({
//        min: 2,
//        max: 20,
//        empty: {
//            leftempty: false,
//            rightempty: false,
//            emptyerror: "简称两边不能有空符号"
//        },
//        onerror: "你输入的简称非法,请确认"
//    });

    $("#projectCode").formValidator({
        onshow: "请输入项目编号",
        onfocus: "项目编号至少5个字符,最多50个字符",
        oncorrect: "您输入的项目编号可用"
    }).inputValidator({
        min: 5,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "项目编号两边不能有空符号"
        },
        onerror: "你输入的项目编号非法,请确认"
    }).ajaxValidator({
		type : "get",
		url : "index1.php",
		data : "model=rdproject_project_rdproject&action=checkProjectCode",
		datatype : "json",
		success : function(data) {
			if (data == "1") {
				return true;
			} else {
				return false;
			}
		},
		buttons : $("#submitSave"),
		error : function() {
			alert("服务器没有返回数据，可能服务器忙，请重试");
		},
		onerror : "该项目编号已重复，请更换",
		onwait : "正在对物料编号进行合法性校验，请稍候..."
	});

	$("#projectType").formValidator({
        onshow: "请选择项目类型",
        onfocus: "项目类型是必选项",
        oncorrect: "谢谢你的配合"
    }).inputValidator({
        min: 1,
        onerror: "你是不是忘记选择项目类型了!"
    });

	$("#projectLevel").formValidator({
        onshow: "请选择项目的优先级",
        onfocus: "优先级必须选择",
        oncorrect: "谢谢你的配合"
    }).inputValidator({
        min: 1,
        onerror: "你是不是忘记选择项目优先级了!"
    });

    $("#planDateStart").formValidator({
        onshow: "请选择计划开始日期",
        onfocus: "请选择日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,并计划开始日期不能为空"
    }); //.defaultPassed();

    $("#planDateClose").formValidator({
        onshow: "请选择计划计划结束日期",
        onfocus: "请选择日期，不能小于计划开始日期哦",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,并计划开始日期不能为空"
    }).compareValidator({
		desid : "planDateStart",
		operateor : ">=",
		onerror : "计划完成日期不能小于计划开始日期"
	}); // .defaultPassed();

//	$("#appraiseWorkload").formValidator({
//		forcevalid : true,
//		triggerevent : "change",
//		onshow : "请输入估计工作量（数字）",
//		onfocus : "请输入数字(1-999999)",
//		oncorrect : "你输入的内容正确"
//	}).inputValidator({
//		min : 1,
//		max : 999999,
//		type : "value",
//		onerrormin : "你输入的值必须在1-999999之间",
//		onerror : "请输入估计工作量(数字1-999999)"
//	});

    $("#managerName").formValidator({
        onshow: "请选择责任人",
        onfocus: "不可输入，请选择",
        oncorrect: "您选择的负责人有效"
    }).inputValidator({
        min: 1,
        onerror: "请选择负责人"
    });

//    $("#depName").formValidator({
//        onshow: "请选择所属部门",
//        onfocus: "不可输入，请选择",
//        oncorrect: "您选择的部门有效"
//    }).inputValidator({
//        min: 1,
//        onerror: "请选择所属部门"
//    });

});