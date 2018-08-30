$(document).ready(function () {
    //������˾
    $("#businessBelongName").yxcombogrid_branch({
        hiddenId: 'businessBelong',
        height: 250,
        isFocusoutCheck: false,
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {
                    //��ʼ�����ṹ
                    initTree();
                    //�������η�Χ
                    reloadManager();
                }
            }
        }
    });

    $("#innerTable").yxeditgrid({
        objName: 'invother[items]',
        title: '��Ʊ��ϸ',
        event: {
            removeRow: function (t, rowNum, rowData) {
                countForm();
            }
        },
        colModel: [
            {
                display: '��Ʊ��Ŀ',
                name: 'productId',
                type: 'hidden'
            },
            {
                display: '��Ʊ��Ŀ����',
                name: 'productCode',
                type: 'hidden'
            },
            {
                display: '��Ʊ��Ŀ',
                name: 'productName',
                validation: {
                    required: true
                },
                tclass: 'txt'
            },
            {
                display: '����',
                name: 'number',
                tclass: 'txtshort',
                event: {
                    blur: function () {
                        countAll($(this).data("rowNum"));
                    }
                }
            },
            {
                display: '����',
                name: 'price',
                type: 'money',
                event: {
                    blur: function () {
                        countAll($(this).data("rowNum"), 'price');
                    }
                }
            },
            {
                display: '��˰����',
                name: 'taxPrice',
                type: 'money',
                event: {
                    blur: function () {
                        countAll($(this).data("rowNum"), 'taxPrice');
                    }
                }
            },
            {
                display: '���',
                name: 'amount',
                validation: {
                    required: true
                },
                type: 'money',
                event: {
                    blur: function () {
                        //��ʼ�����ۺ�����
                        initNumAndPrice($(this).data("rowNum"), 'amount');
                        countAccount($(this).data("rowNum"));
                        countForm();
                    }
                }
            },
            {
                display: '˰��',
                name: 'assessment',
                type: 'money',
                event: {
                    blur: function () {
                        countAccount($(this).data("rowNum"));
                        countForm();
                    }
                }
            },
            {
                display: '��˰�ϼ�',
                name: 'allCount',
                type: 'money',
                event: {
                    blur: function () {
                        //��ʼ�����ۺ�����
                        initNumAndPrice($(this).data("rowNum"), 'allCount');
                        countForm();
                    }
                }
            },
            {
                display: 'Դ�����',
                name: 'objCode',
                readonly: true,
                tclass: 'readOnlyTxtNormal',
                value: $("#menuNo").val()
            },
            {
                display: 'Դ��id',
                name: 'objId',
                type: 'hidden',
                value: $("#menuId").val()
            },
            {
                display: 'Դ������',
                name: 'objType',
                type: 'hidden',
                value: $("#sourceType").val()
            }
        ]
    });

    //��ʼ���ϼ�
    initListCount();
});