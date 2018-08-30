var show_page = function () {
    $("#engGrid").yxsubgrid("reload");
};

$(function () {
	//初始化右键按钮数组
	menusArr = [
			{
			    text: '查看',
			    icon: 'view',
			    action: function (row) {
			        showModalWin('?model=contract_contract_contract&action=toViewTab&id='
			        + row.id
			        + "&skey="
			        + row.skey_);
			    }
			}],
	FBXMZC = {
            text: '发布项目章程',
            icon: 'add',
//            showMenuFn: function (row) {
//                return !(row.projectRate * 1 == 100 || row.state == "3");
//            },
            action: function (row, rows, grid) {
                if (row) {
//                    if (row.projectRate * 1 == 100) {
//                        alert('工作量比例已达到100%，不能继续下达');
//                        return false;
//                    }
                    showOpenWin("?model=engineering_charter_esmcharter&action=toAdd&contractId="
                    + row.id);
                }
            }
        };
	//获取导出权限
	$.ajax({
		type : "POST",
		url : "?model=engineering_project_esmproject&action=getLimits",
		data : {
			limitName : '操作权限-服务合同'
		},
		async: false,
		success : function(data) {
			if(data==1){
				menusArr.push(FBXMZC);
			}
		}
	});
    $("#engGrid").yxsubgrid({
        model: 'contract_contract_contract',
        action: 'esmContractJson',
        title: '项目合同列表',
        param: {
            'states': '2,3,4',
            'isTemp': '0'
        },
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        showcheckbox: false,
        customCode: 'engGrid',
        // 扩展右键菜单
        menusEx: menusArr,
        // 列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'createDate',
            display: '建立日期',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'contractType',
            display: '合同类型',
            sortable: true,
            datacode: 'HTLX',
            width: 60,
            hide: true
        }, {
            name: 'contractNatureName',
            display: '合同属性',
            sortable: true,
            width: 60
        }, {
            name: 'contractCode',
            display: '合同编号',
            sortable: true,
            width: 180,
            process: function (v, row) {
                return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.id
                    + '\')">' + v + '</a>';
            }
        }, {
            name: 'customerName',
            display: '客户名称',
            sortable: true,
            width: 100
        }, {
            name: 'customerId',
            display: '客户Id',
            sortable: true,
            width: 100,
            hide: true
        }, {
            name: 'customerType',
            display: '客户类型',
            sortable: true,
            datacode: 'KHLX',
            width: 70
        }, {
            name: 'contractName',
            display: '合同名称',
            sortable: true,
            width: 150,
            hide: true
        }, {
            name: 'contractProvince',
            display: '合同省份',
            sortable: true,
            width: 70
        }, {
            name: 'signStatus',
            display: '签收状态',
            sortable: true,
            width: 80,
            process: function (v, row) {
                if (v == '0') {
                    return "未签收";
                } else if (v == '1') {
                    return "已签收";
                } else if (v == '2') {
                    return "变更未签收";
                }
            },
            hide: true
        }, {
            name: 'contractTempMoney',
            display: '预计合同金额',
            sortable: true,
            width: 80,
            process: function (v, row) {
                if (row.contractMoney == '' || row.contractMoney == 0.00
                    || row.id == 'allMoney') {
                    return moneyFormat2(v);
                } else {
                    return "<font color = '#B2AB9B'>" + moneyFormat2(v)
                        + "</font>";
                }

            }
        }, {
            name: 'contractMoney',
            display: '签约合同金额',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'ExaStatus',
            display: '审批状态',
            sortable: true,
            width: 60
        }, {
            name: 'sign',
            display: '是否签约',
            sortable: true,
            width: 70,
            process: function (v, row) {
                if (row.id == '' || row.id == undefined) {
                    return "";
                } else if (v == 0 && row.id != '') {
                    return "未签约";
                } else if (v == 1) {
                    return "已签约";
                }
            },
            hide: true
        }, {
            name: 'areaName',
            display: '归属区域',
            sortable: true,
            width: 60
        }, {
            name: 'areaPrincipal',
            display: '区域负责人',
            sortable: true
        }, {
            name: 'prinvipalName',
            display: '合同负责人',
            sortable: true,
            width: 80
        }, {
            name: 'state',
            display: '合同状态',
            sortable: true,
            process: function (v) {
                if (v == '0') {
                    return "未提交";
                } else if (v == '1') {
                    return "审批中";
                } else if (v == '2') {
                    return "执行中";
                } else if (v == '3') {
                    return "已关闭";
                } else if (v == '4') {
                    return "已完成";
                } else if (v == '5') {
                    return "已合并";
                } else if (v == '6') {
                    return "已拆分";
                } else if (v == '7') {
                    return "异常关闭";
                }
            },
            width: 60
        }, {
            name: 'objCode',
            display: '业务编号',
            sortable: true,
            width: 120
        }, {
            name: 'projectRate',
            display: '工作量比例',
            sortable: true,
            process: function (v) {
                return v + " %";
            },
            width: 80
        }],
        comboEx: [{
            text: '合同状态',
            key: 'state',
            data: [{
                text: '审批中',
                value: '1'
            }, {
                text: '执行中',
                value: '2'
            }, {
                text: '已完成',
                value: '4'
            }, {
                text: '已关闭',
                value: '3'
            }, {
                text: '已合并',
                value: '5'
            }, {
                text: '已拆分',
                value: '6'
            }, {
                text: '异常关闭',
                value: '7'
            }]
        }],
        // 主从表格设置
        subGridOptions: {
            url: '?model=contract_contract_product&action=pageJson',// 获取从表数据url
            // 传递到后台的参数设置数组
            param: [{
                paramId: 'contractId',// 传递给后台的参数名称
                colId: 'id'// 获取主表行数据的列名称

            }],
            // 显示的列
            colModel: [{
                name: 'conProductName',
                width: 200,
                display: '产品名称'
            }, {
                name: 'conProductDes',
                display: '产品描述',
                width: 80
            }, {
                name: 'number',
                display: '数量',
                width: 80
            }, {
                name: 'price',
                display: '单价',
                width: 80
            }, {
                name: 'money',
                display: '金额',
                width: 80
            }, {
                name: 'licenseButton',
                display: '加密配置',
                process: function (v, row) {
                    if (row.license != "") {
                        return "<a href='#' onclick='showLicense(\'" + row.license + "\')'>查看</a>";
                    } else {
                        return "";
                    }
                }
            }, {
                name: 'deployButton',
                display: '产品配置',
                process: function (v, row) {
                    if (row.deploy != "") {
                        return "<a href='#' onclick='showGoods(\"" + row.deploy + "\",\"" + row.conProductName + "\")'>查看</a>";
                    } else {
                        return "";
                    }
                }
            }]
        },
        /**
         * 快速搜索
         */
        searchitems: [{
            display: '合同编号',
            name: 'contractCode'
        }, {
            display: '合同名称',
            name: 'contractName'
        }, {
            display: '客户名称',
            name: 'customerName'
        }, {
            display: '业务编号',
            name: 'objCode'
        }],
        sortname: "createTime",
        // 高级搜索
        advSearchOptions: {
            modelName: 'contractInfo',
            // 选择字段后进行重置值操作
            selectFn: function ($valInput) {
                $valInput.yxcombogrid_area("remove");
            },
            searchConfig: [{
                name: '创建日期',
                value: 'c.createTime',
                changeFn: function ($t, $valInput) {
                    $valInput.click(function () {
                        WdatePicker({
                            dateFmt: 'yyyy-MM-dd'
                        });
                    });
                }
            }, {
                name: '归属区域',
                value: 'c.areaPrincipal',
                changeFn: function ($t, $valInput, rowNum) {
                    if (!$("#areaPrincipalId" + rowNum)[0]) {
                        $hiddenCmp = $("<input type='hidden' id='areaPrincipalId" + rowNum + "' value=''>");
                        $valInput.after($hiddenCmp);
                    }
                    $valInput.yxcombogrid_area({
                        hiddenId: 'areaPrincipalId' + rowNum,
                        height: 200,
                        width: 550,
                        gridOptions: {
                            showcheckbox: true
                        }
                    });
                }
            }]
        }
    });
});