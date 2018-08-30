var show_page = function () {
    $("#grid").yxgrid("reload");
};

$(function () {
    $("#grid").yxgrid({
        model: 'contract_counting_counting',
        param: {},
        title: '��ͬ���ʱ�',
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        isOpButton: false,
        autoload: false,
        showcheckbox: false,
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'contractName',
            display: '��ͬ����',
            sortable: true,
            width: 120
        }, {
            name: 'contractCode',
            display: '��ͬ���',
            sortable: true,
            process: function (v, row) {
                return (v == 'NULL' || v == '')? '' : '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.contractId
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>"
                    + v
                    + "</font>"
                    + '</a>';
            }
        }, {
            name: 'contractMoney',
            display: '��ͬ��',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'projectMoney',
            display: '��Ŀ���',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'projectRate',
            display: '��Ŀռ��ͬ',
            align: 'right',
            sortable: true,
            process: function (v) {
                return accMul(v, 100, 7) + " %";
            }
        }, {
            name: 'projectName',
            display: '��Ŀ����',
            sortable: true,
            width: 120
        }, {
            name: 'projectCode',
            display: '��Ŀ���',
            sortable: true,
            process: function (v, row) {
                var skey = url = "";
                if(row.projectType == '����'){
                    $.ajax({
                        type : "POST",
                        url : "?model=engineering_project_esmproject&action=md5RowAjax",
                        data : {
                            "id" : row.projectId
                        },
                        async : false,
                        success : function(data) {
                            skey = data;
                        }
                    });
                    skey = "&skey=" + skey;
                    url = '?model=engineering_project_esmproject&action=viewTab&id='+ row.projectId+skey;
                }else{
                    url = '?model=contract_conproject_conproject&action=viewTab&id='+ row.projectId;
                }

                return (v == 'NULL' || v == '')? '' : '<a href="javascript:void(0)" onclick="javascript:showModalWin(\''+url
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>"
                    + v
                    + "</font>"
                    + '</a>';
            }
        }, {
            name: 'module',
            display: '���',
            sortable: true
        }, {
            name: 'productType',
            display: '��Ʒ����',
            sortable: true
        }, {
            name: 'newProLine',
            display: '��Ʒ��',
            sortable: true
        }, {
            name: 'newProLineMoney',
            display: '���߽��',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'area',
            display: 'ִ������',
            sortable: true
        }, {
            name: 'product',
            display: '��Ʒ',
            sortable: true
        }, {
            name: 'productMoney',
            display: '��Ʒ���',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'productRate',
            display: '��Ʒռ��Ŀ',
            align: 'right',
            sortable: true,
            process: function (v) {
                return accMul(v, 100, 7) + " %";
            }
        }, {
            name: 'projectIncome',
            display: '��Ŀ����',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'productIncome',
            display: '��Ʒ����',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'projectFee',
            display: '��Ŀ�ɱ�',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'productFee',
            display: '��Ʒ�ɱ�',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'productProfit',
            display: '��Ʒë��',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'createTime',
            display: '����ʱ��',
            sortable: true,
            width: 120
        }, {
            name: 'lastChange',
            display: '�����',
            sortable: true
        }, {
            name: 'countingCheck',
            display: '���˼��',
            sortable: true
        }, {
            name: 'buildCheck',
            display: '������',
            sortable: true,
            process: function (v,row) {
                return (row.isTrue == 1)? "��ȷ" : v;
            }
        }],
        comboEx: [{
            text: '�����',
            key: 'chkResult',
            data: [{
                text: '����',
                value: 'correct'
            }, {
                text: '�쳣',
                value: 'wrong'
            }]
        }],
        buttonsEx: [{
            text: "������Ŀ",
            icon: 'add',
            action: function () {
                showThickboxWin("?model=contract_counting_counting&action=toUpdate"
                    + "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700")
            }
        }, {
            text: '��������',
            icon: 'excel',
            action: function () {
                var thisGrid = $("#grid").data('yxgrid');
                var colName = [];
                var colCode = [];
                var colModel = thisGrid.options.colModel;
                for (var i = 0; i < colModel.length; i++) {
                    if (!colModel[i].hide) {
                        colName.push(colModel[i].display);
                        colCode.push(colModel[i].name);
                    }
                }
                var url = "?model=contract_counting_counting&action=export"
                    + '&colName=' + colName.toString() + '&colCode=' + colCode.toString();
                if (thisGrid.options.qtype) {
                    url += "&" + thisGrid.options.qtype + "=" + thisGrid.options.query;
                }
                window.open(url, "", "width=200,height=200,top=200,left=200");
            }
        }],
        searchitems: [{
            display: '��ͬ����',
            name: 'contractNameSearch'
        }, {
            display: '��ͬ��',
            name: 'contractCodeSearch'
        }, {
            display: '��Ŀ����',
            name: 'projectNameSearch'
        }, {
            display: '��Ŀ���',
            name: 'projectCodeSearch'
        }],
        // Ĭ�������ֶ���
        sortname: "c.createTime",
        // Ĭ������˳�� ����
        sortorder: "DESC",
        menusEx: [
            {
                text: 'ɾ����ͬ',
                icon: 'edit',
                showMenuFn: function (row) {
                    return true;
                },
                action: function (row) {
                    if(confirm("ȷ��ɾ���ú�ͬ��"+row.contractCode+"���µ�ȫ��������?")){
                        $.ajax({
                            type : "POST",
                            url : "?model=contract_counting_counting&action=delProject",
                            data : {
                                "id" : row.contractId
                            },
                            async : false,
                            success : function(data) {
                                if(data == 'ok'){
                                    show_page();
                                    alert("ɾ���ɹ�!");
                                }else{
                                    alert("ɾ��ʧ��!");
                                }
                            }
                        });
                    }
                }
            },
            {
                text: '������ȷ',
                icon: 'edit',
                showMenuFn: function (row) {
                    if(row.isTrue == 0 && row.buildCheck != '��ȷ'){
                        return true;
                    }else{
                        return false;
                    }
                },
                action: function (row) {
                    if(confirm("ȷ��Ҫ��Ǹú�ͬ��"+row.contractCode+"���µ�����������ȷ��?")){
                        $.ajax({
                            type : "POST",
                            url : "?model=contract_counting_counting&action=setProjectIsTrue",
                            data : {
                                "id" : row.contractId
                            },
                            async : false,
                            success : function(data) {
                                if(data == 'ok'){
                                    show_page();
                                    alert("��ǳɹ�!");
                                }else{
                                    alert("���ʧ��!");
                                }
                            }
                        });
                    }
                }
            }
        ]

    });
});