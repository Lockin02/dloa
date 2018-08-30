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

    $("#normName").formValidator({
        onshow: "请输入指标名称",
        onfocus: "指标名称至少5个字符,最多50个字符",
        oncorrect: "您输入的指标名称可用"
    }).inputValidator({
        min: 5,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "指标名称两边不能有空符号"
        },
        onerror: "你输入的名称不合法,请确认"
    }).functionValidator({
        fun: function(val, elem) {
            if (val != "请选择...") {
                return true;
            } else {
                return "请选择或输入指标";
            }
        }
    });

    $("#normTotal").formValidator({
        onshow: "请输入总分",
        onfocus: "总分必须在1-9999之间数字",
        oncorrect: "您输入的总分可用"
    }).inputValidator({
        min: 1,
        max: 9999,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "总分两边不能有空符号"
        },
        type: "number",
        onerror: "请输入合法的总分,并总分不能为空"
    }).defaultPassed();

	$("#weight").formValidator({
        onshow: "请输入权重",
        onfocus: "权重必须在1-100之间数字",
        oncorrect: "您输入的权重可用"
    }).inputValidator({
        min: 1,
        max: 100,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "权重两边不能有空符号"
        },
        type: "number",
        onerror: "请输入合法的权重,并且权重不能为空"
    });

    $("#asseserName").formValidator({
        onshow: "请选择负责人",
        onfocus: "不可输入，请选择",
        oncorrect: "您选择的负责人有效"
    }).inputValidator({
        min: 1,
        onerror: "请选择负责人"
    });

});