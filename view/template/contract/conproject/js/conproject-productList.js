$(function() {
        //产品清单
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
                    display: '产品线',
                    width: 100
                },
                {
                    name: 'exeDeptName',
                    display: '执行区域',
                    width: 100
                },
                {
                    name: 'proType',
                    display: '产品类型',
                    width: 80
                },
                {
                    display: '产品名称',
                    name: 'conProductName',
                    tclass: 'txt',
                    process: function (v, row) {
                        return '<a title=点击查看发货清单 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
                            + row.id
                            + '&contractId='
                            + row.contractId
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                            + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
                    }
                },
                {
                    display: '产品Id',
                    name: 'conProductId',
                    tclass: 'txt',
                    type: 'hidden'
                },
                {
                    display: '产品描述',
                    name: 'conProductDes',
                    tclass: 'txt'
                },
                {
                    display: '数量',
                    name: 'number',
                    tclass: 'txtshort'
                },
                {
                    display: '单价',
                    name: 'price',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    display: '金额',
                    name: 'money',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    display: '加密配置Id',
                    name: 'license',
                    type: 'hidden'
                },
                //{
                //    name: 'licenseButton',
                //    display: '加密配置',
                //    process: function (v, row) {
                //        if (row.license != "") {
                //            return "<a href='javascript:void(0)' onclick='showLicense(\""
                //                + row.license + "\")'>加密配置</a>";
                //        }
                //    }
                //},
                {
                    display: '产品配置Id',
                    name: 'deploy',
                    type: 'hidden'
                },
                {
                    display: '概算金额',
                    name: 'cost',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    display: '完成发货',
                    name: 'isDone',
                    tclass: 'txtshort'
                },
                {
                    name: 'deployButton',
                    display: '产品配置',
                    process: function (v, row) {
                        if (row.deploy != "") {
                            return "<a href='javascript:void(0)' onclick='showGoods(\""
                                + row.deploy
                                + "\",\""
                                + row.conProductName
                                + "\")'>产品配置</a>";
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