$(document).ready(function () {
    //提交审批后查看单据时隐藏关闭按钮
    if ($("#showBtn").val() == "1") {
        $(".btn").show();
    }

    //付款合同初始化
    var fundType = $("#fundType").val();
    if (fundType == 'KXXZB') {
        //$("#projectInfo").show();

        //如果是订票结算单
        var projectId = $("#projectId").val();
        if ($("#projectType").val() == 'QTHTXMLX-03' && !isNaN(projectId)) {
            $("#flightsBalanceTr").show();
            $('#tt').tabs();
        }

        //判断是否存在关闭原因，不存在则隐藏
        var closeReasonObj = $("#closeReason");
        if (closeReasonObj.val() == '') {
            closeReasonObj.parents("tr:first").hide();
        }

        // 付款信息新加字段
        $('.payForBusinessMainTd').show();
        var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
        // console.log(mainTypeSlted);
        var mainTypeCode = mainTypeSlted.val();
        var extCode = mainTypeSlted.attr("e1");
        switch (extCode){
            case 'FKYWLX_EXT1':
                $(".prefBidDateWrap").show();
                $("#EXT1").show();
                $("#EXT1-2").show();
                break;
            case 'FKYWLX_EXT2':
                $("#EXT2").show();
                $("#EXT1-2").show();
                break;
            case 'FKYWLX_EXT3':
                $("#EXT1-2").hide();
                $("#EXT3").show();
                break;
            case 'FKYWLX_EXT4':
                $(".relativeContract").show();
                $("#EXT4").show();
                break;
            case 'FKYWLX_EXT5':
                $("#EXT1").show();
                $(".prefBidDateWrap").hide();
                break;
        }
    }

    if (fundType == 'KXXZB' || fundType == 'KXXZC') {
        //显示费用分摊明细
        var shareGridObj = $("#shareGrid");
        if (shareGridObj.length > 0) {
            $("#shareGridTr").show();
            $('#st').tabs();
            shareGridObj.costShareGrid({
                url: "?model=finance_cost_costshare&action=listjson",
                param: {'objType': 2, 'objId': $("#id").val()},
                type: 'view',
                isShowCountRow: true,
                title: '',
                event: {
                    'reloadData': function (e, g, data) {
                        if (!data) {
                            shareGridObj.find("tbody").append("<tr><td colspan='10'> -- 暂无分摊数据 --</td></tr>");
                        } else {
                            g.costShareMoneyView(data);
                        }
                    }
                }
            });
        }
    }
});