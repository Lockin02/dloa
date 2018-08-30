$(function () {
    // ���ŵȼ�
    var deptLevel = $("#deptLevel").val();

    // ��ʼ��
    $("#search").click(function () {
        // ���û���岿�Ų㼶�����ѯ����ʾ
        if (deptLevel == "0") {
            alert('δ���ò��Ų㼶�������ڲ㼶�����н�������');
            return true;
        } else {
            var year = $("#year").val();
            if (year == "") {
                alert("��ѡ�����");
                return false;
            }

            // ��ʾloading
            $("#loading").show();

            // ����
            var deptModel = [
                {
                    display: '��ҵ��',
                    name: 'business',
                    align: 'left',
                    width: 120
                },
                {
                    display: '��������',
                    name: 'secondDept',
                    align: 'left',
                    width: 120
                },
                {
                    display: '��������',
                    name: 'thirdDept',
                    align: 'left',
                    width: 120
                },
                {
                    display: '�ļ�����',
                    name: 'fourthDept',
                    align: 'left',
                    width: 120
                }
            ];

            // ������ʾ�㼶�Ĳ���
            var colModel = deptModel.slice(0, deptLevel);

            // ����Ⱦ
            colModel.push(
                {
                    display: 'Ԥ��',
                    name: 'budget',
                    align: 'right',
                    process: function (v) {
                        return moneyFormat2(v);
                    },
                    width: 90
                },
                {
                    display: '����',
                    name: 'fee',
                    align: 'right',
                    process: function (v) {
                        return moneyFormat2(v);
                    },
                    width: 90
                },
                {
                    display: '���ý���',
                    name: 'feeProcess',
                    align: 'right',
                    process: function (v) {
                        return v + " %";
                    },
                    width: 90
                }
            );

            var beginYear = $("#beginYear").val();
            var beginMonth = $("#beginMonth").val();
            var endYear = $("#endYear").val();
            var endMonth = $("#endMonth").val();

            // ��ȡ���ද̬��
            $.ajax({
                url: "?model=bi_deptFee_deptFee&action=getCostTypeList",
                data: {
                    beginYear: beginYear,
                    beginMonth: beginMonth,
                    endYear: endYear,
                    endMonth: endMonth
                },
                type: 'POST',
                async: false,
                dataType: 'json',
                success: function(data) {
                    for (var i = 0; i < data.length; i++) {
                        colModel.push({
                            display: data[i].costType,
                            name: data[i].costType,
                            align: 'right',
                            process: function () {
                                return "-";
                            },
                            width: 90
                        });
                    }
                }
            });

            // ����
            colModel.push({
                display: "�������",
                name: "costCategory",
                align: 'left',
                width: 120
            });

            // ����
            colModel.push({
                display: "",
                name: ""
            });

            var cl = colModel.length + 1;

            // �б���Ⱦ
            $("#grid").yxeditgrid('remove').yxeditgrid({
                url: "?model=bi_deptFee_deptFee&action=summaryList",
                param: {
                    beginYear: beginYear,
                    beginMonth: beginMonth,
                    endYear: endYear,
                    endMonth: endMonth
                },
                titleAlign: true,
                type: 'view',
                event: {
                    reloadData: function (e, g, data) {
                        if (!data || data.length == 0) {
                            // ����������ʾ
                            $(this).find('tbody').append("<tr><td colspan='" + cl + "'>-- ����������� --</td></tr>");
                            // ���ؼ���
                            $("#loading").hide();
                        } else {
                            // ������ϸ����
                            loadData();
                        }
                    }
                },
                colModel: colModel
            });
            return true;
        }
    });

    // ��������
    var loadData = function() {
        $("#grid").yxeditgrid("getCmpByCol", "business").each(function() {
            // �к�
            var beginYear = $("#beginYear").val();
            var beginMonth = $("#beginMonth").val();
            var endYear = $("#endYear").val();
            var endMonth = $("#endMonth").val();
            var rowNum = $(this).data("rowNum");
            var business = $(this).val();
            var secondDept = $("#grid_cmp_secondDept" + rowNum).val();
            var thirdDept = $("#grid_cmp_thirdDept" + rowNum).val();
            var fourthDept = $("#grid_cmp_fourthDept" + rowNum).val();
            var fee = $("#grid_cmp_fee" + rowNum).val();

            // ����������0���Ž�����ϸ����
            if (fee > 0) {
                // ��ȡ���ද̬��
                $.ajax({
                    url: "?model=bi_deptFee_deptFee&action=summaryDetail",
                    data: {
                        rowNum: rowNum,
                        beginYear: beginYear,
                        beginMonth: beginMonth,
                        endYear: endYear,
                        endMonth: endMonth,
                        business: business,
                        secondDept: secondDept,
                        thirdDept: thirdDept,
                        fourthDept: fourthDept
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        var rowNum = data.rowNum;
                        var rows = data.rows;

                        for (var i in rows) {
                            $("#grid_cmp_" + i + rowNum).text(moneyFormat2(rows[i]));
                        }
                    }
                });
            }
            // ���ؼ���
            $("#loading").hide();
        });
    };

    // ���Ȩ�޲��Ұ󶨵�������
    $.ajax({
        type: 'POST',
        url: '?model=bi_deptFee_deptFee&action=getLimit',
        data: {limitName: "���ŷ���ͳ�Ƶ���"},
        async: false,
        success: function (data) {
            if (data == "1") {
                // ����ʱ���
                $("#export").show().click(function () {
                    var thisGrid = $("#grid").data('yxeditgrid');
                    var colName = [];
                    var colCode = [];
                    var colModel = thisGrid.options.colModel;
                    for (var i = 0; i < colModel.length; i++) {
                        if (!colModel[i].hide) {
                            colName.push(colModel[i].display);
                            colCode.push(colModel[i].name);
                        }
                    }
                    var url = "?model=bi_deptFee_deptFee&action=exportSummary"
                        + '&colName=' + colName.toString() + '&colCode=' + colCode.toString();
                    window.open(url + "&beginYear=" + $("#beginYear").val()
                        + "&beginMonth=" + $("#beginMonth").val()
                        + "&endYear=" + $("#endYear").val()
                        + "&endMonth=" + $("#endMonth").val(), "", "width=200,height=200,top=200,left=200");
                });
            }
        }
    });
});