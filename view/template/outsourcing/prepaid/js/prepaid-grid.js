$(function () {
    // ������ͷ
    var lockCols = [
        {
            title: '�����ͬ��',
            field: 'contractCode',
            halign: 'center',
            align: 'left',
            width: 90
        },
        {
            title: 'ǩԼ��ͬ��',
            field: 'signCode',
            halign: 'center',
            align: 'left',
            width: 90
        },
        {
            title: "����",
            field: "area",
            halign: 'center',
            align: 'left',
            width: 80
        },
        {
            title: "��Ŀ����",
            field: "projectName",
            halign: 'center',
            align: 'left',
            width: 140,
            formatter: function (v) {
                return "<div style='word-wrap:break-word;word-break:break-all;white-space: normal;'>"+v+"</div>";
            }
        }
    ];
    var colModel = [
        {
            title: "��Ŀ���",
            field: "projectCode",
            halign: 'center',
            align: 'left',
            width: 120
        },

        {
            title: "������ʽ",
            field: "businessType",
            halign: 'center',
            align: 'left',
            width: 80
        },
        {
            title: '˰��',
            field: 'taxRate',
            halign: 'center',
            align: 'center',
            formatter: function (v) {
                return v == "" ? "" : v + " %";
            },
            width: 70
        },
        {
            title: '��Ԥ�ᣨ����˰��',
            field: 'subPrepaid',
            halign: 'center',
            align: 'right',
            sortable: true,
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 100
        },
        {
            title: '��Ԥ��',
            field: 'subPrepaidWithTax',
            halign: 'center',
            align: 'right',
            sortable: true,
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '��֧��',
            field: 'subPay',
            halign: 'center',
            align: 'right',
            sortable: true,
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: 'δ��ɽ���',
            field: 'unDeal',
            halign: 'center',
            align: 'right',
            sortable: true,
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '�·�',
            field: 'yearMonth',
            halign: 'center',
            align: 'center',
            width: 90
        },
        {
            title: 'ԭʼ',
            field: 'original',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '�����',
            field: 'adjust',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: 'Ԥ��',
            field: 'prepaidWithTax',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: 'Ԥ��(����˰)',
            field: 'prepaid',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '��ע',
            field: 'remark',
            halign: 'center',
            align: 'left',
            width: 90,
            formatter: function (v) {
                return "<div style='word-wrap:break-word;word-break:break-all;white-space: normal;'>"+v+"</div>";
            }
        },
        {
            title: 'ʵ��',
            field: 'act',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '��Ʊ',
            field: 'invoice',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '��Ʊ����',
            field: 'invoiceDT',
            halign: 'center',
            align: 'center',
            width: 90
        },
        {
            title: '֧��',
            field: 'pay',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        },
        {
            title: '֧������',
            field: 'payDT',
            halign: 'center',
            align: 'center',
            width: 90
        },
        {
            title: 'Ԥ����ʵ�ʲ��',
            field: 'diff',
            halign: 'center',
            align: 'right',
            formatter: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        }
    ]; // ��ͷ

    // ��ʼ��
    $("#search").click(function () {
        var year = $("#year").val();
        if (year == "") {
            alert("��ѡ�����");
            return false;
        }

        var thisHeight = document.documentElement.clientHeight - 40;

        var beginYear = $("#beginYear").val();
        var beginMonth = $("#beginMonth").val();
        if (beginYear != "" && beginMonth == "") {
            alert("��ͬʱ��д��ʼ��Ϳ�ʼ��");
            return false;
        }

        var endYear = $("#endYear").val();
        var endMonth = $("#endMonth").val();
        if (endYear != "" && endMonth == "") {
            alert("��ͬʱ��д������ͽ�����");
            return false;
        }

        $('#grid').datagrid({
            url: "?model=outsourcing_prepaid_prepaid&action=summaryList&beginYear=" + beginYear
            + "&beginMonth=" + beginMonth + "&endYear=" + endYear + "&endMonth=" + endMonth + "&searchVal=" + $("#searchVal").val(),
            loadMsg: '�����У����Ժ�...',
            emptyMsg: 'û�в�ѯ���������...',
            height: thisHeight,
            columns: [colModel],
            frozenColumns: [lockCols]
        });
    });

    // ���Ȩ�޲��Ұ󶨵�������
    $.ajax({
        type: 'POST',
        url: '?model=outsourcing_prepaid_prepaid&action=getLimits',
        data: {limitName: "����Ȩ��"},
        async: false,
        success: function (data) {
            if (data == "1") {
                // ����ʱ���
                $("#export").show().click(function () {
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
                    var url = "?model=outsourcing_prepaid_prepaid&action=export"
                        + '&colName=' + colName.toString() + '&colCode=' + colCode.toString();
                    window.open(url + "&beginYear=" + $("#beginYear").val()
                        + "&beginMonth=" + $("#beginMonth").val()
                        + "&endYear=" + $("#endYear").val()
                        + "&endMonth=" + $("#endMonth").val() + "&searchVal=" + $("#searchVal").val(), "", "width=200,height=200,top=200,left=200");
                });
            }
        }
    });

    // ���Ȩ�޲��Ұ󶨵�������
    $.ajax({
        type: 'POST',
        url: '?model=outsourcing_prepaid_prepaid&action=getLimits',
        data: {limitName: "����Ȩ��"},
        async: false,
        success: function (data) {
            if (data == "1") {
                // ����ʱ���
                $("#import").show().click(function () {
                    showThickboxWin("?model=outsourcing_prepaid_prepaid&action=toExcelIn" +
                        "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
                });
            }
        }
    });
});

