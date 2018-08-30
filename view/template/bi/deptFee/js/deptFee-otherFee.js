$(function () {
    var thisHeight = document.documentElement.clientHeight - 40;

    // ��ʼ��
    $("#search").click(function () {
        var beginYear = $("#beginYear").val() * 1;
        if (beginYear == "") {
            alert("��ѡ��ʼ���");
            return false;
        }
        var beginMonth = $("#beginMonth").val() * 1;
        if (beginMonth == "") {
            alert("��ѡ��ʼ�·�");
            return false;
        }
        var endYear = $("#endYear").val() * 1;
        if (endYear == "") {
            alert("��ѡ��������");
            return false;
        }
        var endMonth = $("#endMonth").val() * 1;
        if (endMonth == "") {
            alert("��ѡ������·�");
            return false;
        }

        // ��ʾloading
        //$("#loading").show();

        // �б���Ⱦ
        $("#grid").datagrid({
            url: "?model=bi_deptFee_deptFee&action=otherFeeDetail&beginYear=" + beginYear
            + "&beginMonth=" + beginMonth
            + "&endYear=" + endYear
            + "&endMonth=" + endMonth
            + "&isImport=" + 1,
            loadMsg: '�����У����Ժ�...',
            emptyMsg: 'û�в�ѯ���������...',
            height: thisHeight,
            frozenColumns: [],
            columns: [[
                {
                    title: '��ҵ��',
                    field: 'business',
                    align: 'left',
                    width: 100
                },
                {
                    title: '��������',
                    field: 'secondDept',
                    align: 'left',
                    width: 100
                },
                {
                    title: '��������',
                    field: 'thirdDept',
                    align: 'left',
                    width: 100
                },
                {
                    title: '�ļ�����',
                    field: 'fourthDept',
                    align: 'left',
                    width: 100
                },
                {
                    title: '��',
                    field: 'thisYear',
                    width: 70
                },
                {
                    title: '��',
                    field: 'thisMonth',
                    width: 70
                },
                {
                    title: '��������',
                    field: 'costType',
                    width: 80
                },
                {
                    title: 'Ԥ��',
                    field: 'budget',
                    align: 'right',
                    formatter: function (v) {
                        return moneyFormat2(v);
                    },
                    width: 80
                },
                {
                    title: '����',
                    field: 'fee',
                    align: 'right',
                    formatter: function (v) {
                        return moneyFormat2(v);
                    },
                    width: 80
                }
            ]]
        });
        return true;
    });

    // ��ʼ��
    $("#searchSummary").click(function () {
        var beginYear = $("#beginYear").val() * 1;
        if (beginYear == "") {
            alert("��ѡ��ʼ���");
            return false;
        }
        var beginMonth = $("#beginMonth").val() * 1;
        if (beginMonth == "") {
            alert("��ѡ��ʼ�·�");
            return false;
        }
        var endYear = $("#endYear").val() * 1;
        if (endYear == "") {
            alert("��ѡ��������");
            return false;
        }
        var endMonth = $("#endMonth").val() * 1;
        if (endMonth == "") {
            alert("��ѡ������·�");
            return false;
        }

        // ��ʾloading
        //$("#loading").show();

        // ����Ⱦ
        var lockCols = [
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
            },
            {
                title: '��������',
                field: 'costType',
                width: 90
            },
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
            }
        ];

        var colModel = [];
        for (var i = beginYear; i <= endYear; i++) {
            var j, month, begin = 1, end = 12;
            if (i == beginYear) {
                begin = beginMonth;
            }
            if (i == endYear) {
                end = endMonth;
            }
            // �·�����Ⱦ
            for (j = begin; j <= end; j++) {
                month = j >= 10 ? j : "0" + j;
                colModel.push({
                    title: i + "" + month,
                    field: 'd' + i + "" + month,
                    align: 'right',
                    formatter: function (v) {
                        return moneyFormat2(v);
                    },
                    width: 90
                });
            }
        }

        // �б���Ⱦ
        $("#grid").datagrid({
            url: "?model=bi_deptFee_deptFee&action=otherFeeSummary&beginYear=" + beginYear
            + "&beginMonth=" + beginMonth
            + "&endYear=" + endYear
            + "&endMonth=" + endMonth
            + "&isImport=" + 1,
            height: thisHeight,
            columns: [colModel],
            frozenColumns: [lockCols],
            loadMsg: '�����У����Ժ�...',
            emptyMsg: 'û�в�ѯ���������...'
        });
        return true;
    });

    // �����¼���
    $("#import").click(function () {
        showThickboxWin("?model=bi_deptFee_deptFee&action=toImport"
            + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
    });

    // ����ʱ���
    $("#export").click(function () {
        window.open("?model=bi_deptFee_deptFee&action=export&beginYear="
            + $("#beginYear").val() + "&beginMonth=" + $("#beginMonth").val() + "&endYear="
            + $("#endYear").val() + "&endMonth=" + $("#endMonth").val() + "&isImport=" + 1);
    });
});