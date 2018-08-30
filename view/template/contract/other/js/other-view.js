$(document).ready(function () {
    //�ύ������鿴����ʱ���عرհ�ť
    if ($("#showBtn").val() == "1") {
        $(".btn").show();
    }

    //�����ͬ��ʼ��
    var fundType = $("#fundType").val();
    if (fundType == 'KXXZB') {
        //$("#projectInfo").show();

        //����Ƕ�Ʊ���㵥
        var projectId = $("#projectId").val();
        if ($("#projectType").val() == 'QTHTXMLX-03' && !isNaN(projectId)) {
            $("#flightsBalanceTr").show();
            $('#tt').tabs();
        }

        //�ж��Ƿ���ڹر�ԭ�򣬲�����������
        var closeReasonObj = $("#closeReason");
        if (closeReasonObj.val() == '') {
            closeReasonObj.parents("tr:first").hide();
        }

        // ������Ϣ�¼��ֶ�
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
        //��ʾ���÷�̯��ϸ
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
                            shareGridObj.find("tbody").append("<tr><td colspan='10'> -- ���޷�̯���� --</td></tr>");
                        } else {
                            g.costShareMoneyView(data);
                        }
                    }
                }
            });
        }
    }
});