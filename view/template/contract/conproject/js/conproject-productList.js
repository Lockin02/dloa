$(function() {
        //��Ʒ�嵥
        $("#productList").yxeditgrid({
            objName: 'contract[product]',
            url: '?model=contract_conproject_conproject&action=productListJson',
            type: 'view',
            tableClass: 'form_in_table',
            param: {
                'projectId': $("#projectId").val()
            },
            colModel: [
                {
                    name: 'newProLineName',
                    display: '��Ʒ��',
                    width: 100
                },
                {
                    name: 'exeDeptName',
                    display: 'ִ������',
                    width: 100
                },
                {
                    name: 'proType',
                    display: '��Ʒ����',
                    width: 80
                },
                {
                    display: '��Ʒ����',
                    name: 'conProductName',
                    tclass: 'txt',
                    process: function (v, row) {
                        return '<a title=����鿴�����嵥 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
                            + row.id
                            + '&contractId='
                            + row.contractId
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                            + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
                    }
                },
                {
                    display: '��ƷId',
                    name: 'conProductId',
                    tclass: 'txt',
                    type: 'hidden'
                },
                {
                    display: '��Ʒ����',
                    name: 'conProductDes',
                    tclass: 'txt'
                },
                {
                    display: '����',
                    name: 'number',
                    tclass: 'txtshort'
                },
                {
                    display: '����',
                    name: 'price',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    display: '���',
                    name: 'money',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    display: '��������Id',
                    name: 'license',
                    type: 'hidden'
                },
                //{
                //    name: 'licenseButton',
                //    display: '��������',
                //    process: function (v, row) {
                //        if (row.license != "") {
                //            return "<a href='javascript:void(0)' onclick='showLicense(\""
                //                + row.license + "\")'>��������</a>";
                //        }
                //    }
                //},
                {
                    display: '��Ʒ����Id',
                    name: 'deploy',
                    type: 'hidden'
                },
                {
                    display: '������',
                    name: 'cost',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    display: '��ɷ���',
                    name: 'isDone',
                    tclass: 'txtshort'
                },
                {
                    name: 'deployButton',
                    display: '��Ʒ����',
                    process: function (v, row) {
                        if (row.deploy != "") {
                            return "<a href='javascript:void(0)' onclick='showGoods(\""
                                + row.deploy
                                + "\",\""
                                + row.conProductName
                                + "\")'>��Ʒ����</a>";
                        }
                    }
                }
            ],
            event: {
                'reloadData': function (e) {
                    initCacheInfo();
                }
            }
        });
    });