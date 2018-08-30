$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function() {
            if (confirm("您输入成功，确定提交吗？")) {
                return true;
            } else {
                return false;
            }
        }
    });

//    $(".tipShortTxt").css("width", "275px");
//    $(".tipLongTxt").css("width", "505px");

    $("#groupName").formValidator({
        onshow: "请输入项目组合名称",
        onfocus: "组合名称至少2个字符,最多50个字符",
        oncorrect: "您输入的组合名称可用"
    }).inputValidator({
        min: 2,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "组合名称两边不能有空符号"
        },
        onerror: "你输入的名称不合法,请确认"
    }).ajaxValidator({
        type: "get",
        url: "index1.php",
        data: "model=rdproject_group_rdgroup&action=ajaxGroupName",
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
        onwait: "正在对组合名称进行合法性校验，请稍候..."
    });
//
//    $("#simpleName").formValidator({
//        onshow: "请输入组合简称",
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

    $("#groupCode").formValidator({
        onshow: "请输入组合编号",
        onfocus: "组合编号至少5个字符,最多50个字符",
        oncorrect: "您输入的组合编号可用"
    }).inputValidator({
        min: 5,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "组合编号两边不能有空符号"
        },
        onerror: "你输入的组合编号非法,请确认"
    });

    $("#managerName").formValidator({
        onshow: "请选择组合责任人",
        onfocus: "不可输入，请选择",
        oncorrect: "您选择的负责人有效"
    }).inputValidator({
        min: 1,
        onerror: "请选择组合负责人"
    });

//    $("#depName").formValidator({
//        onshow: "请选择组合所属部门",
//        onfocus: "不可输入，请选择",
//        oncorrect: "您选择的部门有效"
//    }).inputValidator({
//        min: 1,
//        onerror: "请选择组合所属部门"
//    });

});