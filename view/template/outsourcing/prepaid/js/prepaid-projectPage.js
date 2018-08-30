$(function () {
    // ������ͷ
    var lockCols = [
        {
            title: '�����ͬ��',
            field: 'contractCode',
            halign: 'center',
            width: 90
        }
    ];
    var colModel = [
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
            sortable : true,
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
            sortable : true,
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
            sortable : true,
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
            sortable : true,
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
            width: 90
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
        // ��ʼ��
        var thisHeight = document.documentElement.clientHeight - 40;
        var projectId = $("#projectId").val();

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
            + "&beginMonth=" + beginMonth + "&endYear=" + endYear + "&endMonth=" + endMonth
            + "&searchVal=" + $("#searchVal").val() + "&projectId=" + projectId,
            loadMsg: '�����У����Ժ�...',
            emptyMsg: 'û�в�ѯ���������...',
            height: thisHeight,
            columns: [colModel],
            frozenColumns: [lockCols]
        });
    }).trigger("click");
});