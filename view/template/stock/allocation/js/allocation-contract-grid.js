var show_page = function () {
    $("#allocationGrid").yxsubgrid("reload");
};
$(function () {
    $("#allocationGrid")
        .yxsubgrid(
        {
            model: 'stock_allocation_allocation',
            action: 'pageListGridJson',
            param: {
                'contractTypeArr': $("#contractType").val(),
                "contractIdArr": $("#contractId").val()
            },
            title: '调拨单管理',
            isAddAction: false,
            isViewAction: false,
            isEditAction: false,
            isDelAction: false,
            showcheckbox: false,
            // 列信息
            colModel: [
                {
                    display: 'id',
                    name: 'id',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'docCode',
                    display: '单据编号',
                    sortable: true
                },
                {
                    name: 'auditDate',
                    display: '单据日期',
                    sortable: true
                },
                {
                    name: 'toUse',
                    display: '出库用途',
                    sortable: true,
                    datacode: 'CHUKUYT',
                    width: 70
                },
                {
                    name: 'docType',
                    display: '调拨类型',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'relDocId',
                    display: '源单id',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'relDocCode',
                    display: '源单编号',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'relDocName',
                    display: '源单名称',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'relDocType',
                    display: '源单类型',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'contractId',
                    display: '合同id',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'contractName',
                    display: '合同名称',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'contractCode',
                    display: '合同编号',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'outStartDate',
                    display: '借出开始日期',
                    sortable: true
                },
                {
                    name: 'outEndDate',
                    display: '借出结束日期',
                    sortable: true

                },
                {
                    name: 'customerName',
                    display: '客户(单位)名称',
                    sortable: true
                },
                {
                    name: 'customerId',
                    display: '客户(单位)id',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'linkmanId',
                    display: '客户联系人id',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'linkmanName',
                    display: '客户联系人',
                    sortable: true
                },
                {
                    name: 'exportStockId',
                    display: '调出仓库id',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'exportStockCode',
                    display: '调出仓库代码',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'exportStockName',
                    display: '调出仓库名称',
                    sortable: true
                },
                {
                    name: 'importStockId',
                    display: '调入仓库id',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'importStockCode',
                    display: '调入仓库代码',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'importStockName',
                    display: '调入仓库名称',
                    sortable: true
                },
                {
                    name: 'deptName',
                    display: '部门',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'deptCode',
                    display: '部门编码',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'pickName',
                    display: '领料人(职员)名称',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'docStatus',
                    display: '单据状态',
                    sortable: true,
                    process: function (v) {
                        if (v == "WSH") {
                            return "未审核";
                        } else {
                            return "已审核";
                        }
                    },
                    width: 50
                },
                {
                    name: 'remark',
                    display: '备注',
                    sortable: true,
                    hide: true

                },
                {
                    name: 'pickCode',
                    display: '领料人(职员)编码',
                    sortable: true,
                    hide: true

                },
                {
                    name: 'auditerCode',
                    display: '审核人编号',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'custosCode',
                    display: '保管人编号',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'custosName',
                    display: '保管人名称',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'chargeCode',
                    display: '负责人编号',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'chargeName',
                    display: '负责人名称',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'acceptorCode',
                    display: '验收人编号',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'acceptorName',
                    display: '验收人名称',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'accounterCode',
                    display: '记账人编号',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'accounterName',
                    display: '记账人名称',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'updateId',
                    display: '修改人',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'updateTime',
                    display: '修改时间',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'updateName',
                    display: '修改人名称',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'createId',
                    display: '创建人',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'createName',
                    display: '制单',
                    sortable: true
                },
                {
                    name: 'createTime',
                    display: '创建时间',
                    sortable: true,
                    hide: true
                },
                {
                    name: 'auditerName',
                    display: '审核人',
                    sortable: true
                }
            ],
            // 主从表格设置
            subGridOptions: {
                url: '?model=stock_allocation_allocationitem&action=pageJson',
                param: [
                    {
                        paramId: 'mainId',
                        colId: 'id'
                    }
                ],
                colModel: [
                    {
                        name: 'productCode',
                        display: '物料编号'
                    },
                    {
                        name: 'productName',
                        width: 200,
                        display: '物料名称'
                    },
                    {
                        name: 'allocatNum',
                        display: "调拨数量"
                    },
                    {
                        name: 'serialnoName',
                        display: "序列号",
                        width: '500'
                    }
                ]
            },
            toViewConfig: {
                formWidth: 1170,
                formHeight: 500
            },
            comboEx: [
                {
                    text: '单据状态',
                    key: 'docStatus',
                    data: [
                        {
                            text: '未审核',
                            value: 'WSH'
                        },
                        {
                            text: '已审核',
                            value: 'YSH'
                        }
                    ]
                }
            ],
            searchitems: [
                {
                    display: "序列号",
                    name: 'serialnoName'
                },
                {
                    display: '单据编号',
                    name: 'docCode'
                },
                {
                    name: 'customerName',
                    display: '客户(单位)名称'
                },
                {
                    name: 'pickName',
                    display: '职员名称'
                },
                {
                    display: '物料代码',
                    name: 'productCode'
                },
                {
                    display: '物料名称',
                    name: 'productName'
                },
                {
                    display: '物料规格型号',
                    name: 'pattern'
                }
            ],
            sortorder: "DESC",
            menusEx: [
                {
                    name: 'view',
                    text: "查看",
                    icon: 'view',
                    action: function (row, rows, grid) {
                        showThickboxWin("?model=stock_allocation_allocation&action=toView&id="
                            + row.id
                            + "&skey="
                            + row['skey_']
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
                    }

                }
            ]
        });
});