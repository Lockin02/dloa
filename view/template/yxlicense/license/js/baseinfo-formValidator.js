$(document).ready(function () {
    $.formValidator.initConfig({
        formid: "form1",
        //autotip: true,
        onerror: function (msg) {
            //alert(msg);
        }
    });

    /**
     * 名称验证
     */
    $("#name").formValidator({
        onshow: "请输入名称",
        onfocus: "名称至少1个字符，最多50个字符",
        oncorrect: "您输入的名称有效"
    }).inputValidator({
        min: 1,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "名称两边不能为空"
        },
        onerror: "您输入的名称不合法，请重新输入"
    }).ajaxValidator({
        type: "GET",
        url: "index1.php",
        data: "model=yxlicense_license_baseinfo&action=ajaxDataCode",
        dataType: "json",
        success: function (data) {
            return data == "0";
        },
        //buttons: $("#button"),
        error: function () {
            alert("服务器没有返回数据，可能服务器忙，请重试");
        },
        onError: "该名称不可用，请更换名称",
        onWait: "正在进行校验，请稍候..."
    });

    /**
     *加载完页面后判断是否启用
     */
    checkIsUse();

});
//获取当前页面的URL
function saveHtm() {
    var id = $("#id").val();
    var name = $("#name").val();
    $.ajax({// 缓存序列号
        type: "get",
        async: false,
        url: "?model=yxlicense_license_baseinfo&action=saveHtm",
        data: {
            "id": id,
            "name": name
        }
    })
}

//检测是否使用
function checkIsUse() {
    var status = $("#isUseHidden").val();
    $("input[name='baseinfo[isUse]']").each(function () {
        if ($(this).val() == status) {
            $(this).attr("checked", true);
            return false;
        }
    });
}