$(function () {
    // 关闭网页时加载的事件
    $(window).bind('beforeunload',function(){
        window.opener.show_page();
    });

    var thisPeriod = $("#thisPeriod").val();

    // 加载分摊列表
    $("#shareGrid").costShareGrid({
        title: "",
        objName: "costshare[detail]",
        type: "audit",
        url: "?model=finance_cost_costshare&action=listjson",
        // param: {objType: $("#objType").val(), objId: $("#objId").val(), 'inPeriodSmall': thisPeriod},
        param: {objType: $("#objType").val(), objId: $("#objId").val()},
        isShowCountRow: true,
        countKey: "objMoney",
        thisPeriod: thisPeriod
    });

    // 绑定审核事件
    $("#audit").bind('click', function () {
        //显示费用分摊明细
        if ($("#shareGrid").costShareGrid('checkForm', $("#objMoney").val(), false) != false) {
            if (confirm("确认审核吗？") == true) {
                $("form").submit();
            }
        }
    });

    // 绑定并进入下一跳
    $("#auditAndNext").bind('click', function () {
        //显示费用分摊明细
        if ($("#shareGrid").costShareGrid('checkForm', $("#objMoney").val(), false) != false) {
            if (confirm("确认审核吗？") == true) {
                $("#goNext").val(1);
                $("form").submit();
            }
        }
    });

    // 导出数据
    $("#exportAudit").bind('click', function () {
        window.open(
            '?model=finance_cost_costshare&action=exportAudit&objType=' + $("#objType").val() +
                '&objId=' + $("#objId").val()
            ,
            '费用分摊导出',
            'width=200,height=200,top=200,left=200'
        );
    });

    // 绑定撤回时间
    $("#back").bind('click', function () {
        if (confirm("确认撤回单据吗？") == true) {
            $.ajax({
                type: "POST",
                url: "?model=finance_cost_costshare&action=back",
                data: {objType: $("#objType").val(), objId: $("#objId").val()},
                async: false,
                success: function (data) {
                    if (data == "0") {
                        alert('撤回失败。');
                    } else if (data == "1") {
                        alert('撤回成功，已无需要审核的单据。');
                        window.close();
                    } else {
                        alert('撤回成功。');
                        // 路径跳转
                        location.href = data;
                    }
                }
            });
        }
    });
});