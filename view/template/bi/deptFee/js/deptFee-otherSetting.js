$(function () {
    // ��ȡ���˵ķ�������
    $.ajax({
        url: "?model=common_otherdatas&action=getConfig",
        data: {name: 'deptFee_filter_costType', type: ''},
        type: 'post',
        async: false,
        success: function(msg) {
            $("#costType").val(msg);
        }
    });

    $.ajax({
        url: "?model=common_otherdatas&action=getConfig",
        data: {name: 'deptFee_filter_deptLevel', type: ''},
        type: 'post',
        async: false,
        success: function(msg) {
            $("#deptLevel").val(msg);
        }
    });

    // ���水ť
    $("#costTypeBtn").click(function() {
        $.ajax({
            url: "?model=common_otherdatas&action=updateConfig",
            data: {name: 'deptFee_filter_costType', type: '', value: $("#costType").val()},
            type: 'post',
            async: false,
            success: function(msg) {
                alert(msg);
            }
        });
    });

    $.ajax({
        url: "?model=common_otherdatas&action=getConfig",
        data: {name: 'deptFee_pk_mapping', type: ''},
        type: 'post',
        async: false,
        success: function(msg) {
            $("#pkMapping").val(msg);
        }
    });

    // ���水ť
    $("#pkMappingBtn").click(function() {
        $.ajax({
            url: "?model=common_otherdatas&action=updateConfig",
            data: {name: 'deptFee_pk_mapping', type: '', value: $("#pkMapping").val()},
            type: 'post',
            async: false,
            success: function(msg) {
                alert(msg);
            }
        });
    });

    // ���水ť
    $("#deptLevelBtn").click(function() {
        $.ajax({
            url: "?model=common_otherdatas&action=updateConfig",
            data: {name: 'deptFee_filter_deptLevel', type: '', value: $("#deptLevel").val()},
            type: 'post',
            async: false,
            success: function(msg) {
                alert(msg);
            }
        });
    });

    var allLength;
    var now;

    // ���水ť
    $("#feeUpdateBtn").click(function() {
        var feeTypeTypes = $("input[id^='feeType_']:checked");

        if (feeTypeTypes.length == 0) {
            alert("��ѡ��һ�ָ�������");
        } else if (confirm('ȷ�ϸ��¾���������')) {
            // ���½���
            allLength = feeTypeTypes.length;

            // ���ø������
            reloadUpdateResult();

            feeTypeTypes.each(function() {
                $.ajax({
                    url: "?model=bi_deptFee_deptFee&action=updateFee",
                    data: {feeType: $(this).val(), year: $("#year").val(), month: $("#month").val()},
                    type: 'post',
                    success: function(msg) {
                        setUpdateResult(msg);
                    }
                });
            });
        }
    });

    // ���ظ������
    var reloadUpdateResult = function() {
        // ����loading
        $("#loading").show();
        $("#feeShowArea").html("").append("<table class='table' id='showAreaTable' border='0' cellspacing='0' cellpadding='0'>" +
            "<thead style='text-align: left;'><th>��������</th><th>����״̬</th>" +
            "<th style='text-align: right;'>���½��</th><th style='text-align: right;'>�쳣���</th>" +
            "<th style='width:200px;'>�쳣��Ϣ</th></thead>" +
            "<tbody id='showAreaBody'></tbody>" +
            "</table>");
        now = 0;
    };

    // �������
    var setUpdateResult = function(msg) {
        now++;
        msg = eval("(" + msg + ")");
        var state = msg.rst == "none" ? "�޸�������" : "�������";
        $("#showAreaBody").append("<tr><td>" + msg.feeTypeName + "</td>" +
            "<td>" + state + "</td>" +
            "<td style='text-align: right;'>" + msg.feeAll + "</td>" +
            "<td style='text-align: right;'>" + msg.ignore + "</td>" +
            "<td>" + msg.ignoreInfo + "</td></tr>");
        if (now == allLength) {
            $("#loading").hide();
        }
    };
});