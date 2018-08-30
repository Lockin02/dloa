$(function () {
    // ���ŵȼ�
    var deptLevel = $("#deptLevel").val();
    var lockCols = []; // ������ͷ
    var colModel = []; // ��ͷ

    // ��ʼ��
    $("#search").click(function () {
        // ���û���岿�Ų㼶�����ѯ����ʾ
        if (deptLevel == "0") {
            alert('δ���ò��Ų㼶�������ڲ㼶�����н�������');
            return true;
        } else {
            var beginYear = $("#beginYear").val();
            if (beginYear == "") {
                alert("��ѡ��ʼ���");
                return false;
            }
            var beginMonth = $("#beginMonth").val();
            if (beginMonth == "") {
                alert("��ѡ��ʼ�·�");
                return false;
            }
            var endYear = $("#endYear").val();
            if (endYear == "") {
                alert("��ѡ��������");
                return false;
            }
            var endMonth = $("#endMonth").val();
            if (endMonth == "") {
                alert("��ѡ������·�");
                return false;
            }

            // ��ͷ����
            var deptModel = [
                {
                    title: '��ҵ��',
                    field: 'business',
                    align: 'left',
                    width: 120
                },
                {
                    title: '��������',
                    field: 'secondDept',
                    align: 'left',
                    width: 120
                },
                {
                    title: '��������',
                    field: 'thirdDept',
                    align: 'left',
                    width: 120
                },
                {
                    title: '�ļ�����',
                    field: 'fourthDept',
                    align: 'left',
                    width: 120
                }
            ];

            // ������ʾ�㼶�Ĳ���
            colModel = deptModel.slice(0, deptLevel);

            // ����Ⱦ
            colModel.push(
                {
                    title: 'Ԥ��',
                    field: 'budget',
                    align: 'right',
                    formatter: function (v) {
                        return moneyFormat2(v);
                    },
                    width: 90
                },
                {
                    title: '����',
                    field: 'fee',
                    align: 'right',
                    formatter: function (v) {
                        return moneyFormat2(v);
                    },
                    width: 90
                },
                {
                    title: '���ý���',
                    field: 'feeProcess',
                    align: 'right',
                    formatter: function (v) {
                        return v + " %";
                    },
                    width: 90
                }
            );

            lockCols = colModel;

            colModel = [];

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
                success: function (data) {
                    for (var i = 0; i < data.length; i++) {
                        colModel.push({
                            title: data[i].costType,
                            field: data[i].costType,
                            align: 'right',
                            formatter: function (v) {
                                return moneyFormat2(v);
                            },
                            width: 90
                        });
                    }
                }
            });

            // ����
            colModel.push({
                title: "��Ʒ��",
                field: "productLine",
                align: 'left',
                width: 120
            }, {
                title: "�������",
                field: "costCategory",
                align: 'left',
                width: 120
            });

            var thisHeight = document.documentElement.clientHeight - 40;

            $('#grid').datagrid({
                url: "?model=bi_deptFee_deptFee&action=summaryList&beginYear=" + beginYear
                + "&beginMonth=" + beginMonth + "&endYear=" + endYear + "&endMonth=" + endMonth,
                loadMsg: '�����У����Ժ�...',
                emptyMsg: 'û�в�ѯ���������...',
                height: thisHeight,
                columns: [colModel],
                frozenColumns: [lockCols]
            });
        }
    });

    // ���Ȩ�޲��Ұ󶨵�������
    $.ajax({
        type: 'POST',
        url: '?model=bi_deptFee_deptFee&action=getLimit',
        data: {limitName: "���ŷ���ͳ�Ƶ���"},
        async: false,
        success: function (data) {
            if (data == "1") {
                // �����¼���
                $("#export").show().click(function () {
                    if (colModel.length == 0) {
                        alert('���ѯ���ٵ�������');
                    } else {
                        var colName = [];
                        var colCode = [];
                        for (var i = 0; i < lockCols.length; i++) {
                            if (!colModel[i].hide) {
                                colName.push(lockCols[i].title);
                                colCode.push(lockCols[i].field);
                            }
                        }
                        for (i = 0; i < colModel.length; i++) {
                            if (!colModel[i].hide) {
                                colName.push(colModel[i].title);
                                colCode.push(colModel[i].field);
                            }
                        }
                        var url = "?model=bi_deptFee_deptFee&action=exportSummary"
                            + '&colName=' + colName.toString() + '&colCode=' + colCode.toString();
                        window.open(url + "&beginYear=" + $("#beginYear").val()
                            + "&beginMonth=" + $("#beginMonth").val()
                            + "&endYear=" + $("#endYear").val()
                            + "&endMonth=" + $("#endMonth").val(), "", "width=200,height=200,top=200,left=200");
                    }
                });

                // ������ϸ��
                $("#toExportDetail").show().click(function () {
                    $('#detailWindow').show().window({
                        title: 'ѡ����Ҫ��������ϸ����',
                        width: 600,
                        height: 300,
                        modal: true,
                        collapsible: false,
                        minimizable: false,
                        maximizable: false
                    });
                });

                // �󶨵����¼�
                $("#exportDetail").show().click(function () {
                    // ����
                    var exportYear = $("#exportYear").val();
                    if (exportYear == "") {
                        alert("��ѡ�����");
                        return false;
                    }
                    var exportMonth = $("#exportMonth").val();
                    if (exportMonth == "") {
                        alert("��ѡ���·�");
                        return false;
                    }

                    var selectedFeeType = $("input[id^='feeType_']:checked");
                    if (selectedFeeType.length == 0) {
                        alert("��ѡ����Ҫ����������");
                        return false;
                    }
                    var exportItems = [];
                    selectedFeeType.each(function () {
                        if ($(this).attr("checked")) {
                            exportItems.push($(this).val());
                        }
                    });

                    window.open("?model=bi_deptFee_deptFee&action=exportDetail" + "&exportYear=" + exportYear
                        + "&exportMonth=" + exportMonth
                        + "&exportItems=" + exportItems.toString(), "", "width=200,height=200,top=200,left=200");
                });
            }
        }
    });
});