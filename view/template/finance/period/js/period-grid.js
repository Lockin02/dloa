var show_page = function (page) {
    $("#periodGrid").yxgrid("reload");
};

// 获取公司选项
var getCompanyOptions = function(){
    var arr = [];
    var responseText = $.ajax({
        url: 'index1.php?model=deptuser_branch_branch&action=listForSelect&limit=999',
        type: "POST",
        async: false
    }).responseText;
    if(responseText != '' || responseText != 'false'){
        var opts = eval("(" + responseText + ")");
        $.each(opts,function(i,item){
            var catchVal = [];
            catchVal['text'] = item.name;
            catchVal['value'] = item.value;
            arr.push(catchVal);
        });
    }
    return arr;
};

$(function () {

    // 右键菜单按钮集合
    var menuArr = [];
    var companyData = [];
    companyData = getCompanyOptions();

    // 仓库周期权限获取
    $.ajax({
        type: 'POST',
        url: '?model=finance_period_period&action=getLimits',
        data: {
            'limitName': '仓库周期权限'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                menuArr.push(
                    {
                        name: 'checkout',
                        text: '结账(仓库)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCheckout == 0 && row.isUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('确定要结账？')) {
                                $.post("?model=finance_period_period&action=checkout",
                                    { "id": row.id, 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('结账成功');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('结账失败');
                                            $("#periodGrid").yxgrid("reload");
                                        }
                                    }
                                );
                            }
                        }
                    },
                    {
                        name: 'close',
                        text: '关账(仓库)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCheckout == 0 && row.isClosed == 0 && row.isUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('确定要关账？')) {
                                $.post("?model=finance_period_period&action=close",
                                    { "id": row.id, 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('关账成功');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('关账失败');
                                            $("#periodGrid").yxgrid("reload");
                                        }
                                    }
                                );
                            }
                        }
                    },
                    {
                        name: 'unclose',
                        text: '反关账(仓库)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCheckout == 0 && row.isClosed == 1 && row.isUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('确定要反关账？')) {
                                $.post("?model=finance_period_period&action=unclose",
                                    { "id": row.id, 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('反关账成功');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('反关账失败');
                                            $("#periodGrid").yxgrid("reload");
                                        }
                                    }
                                );
                            }
                        }
                    },
                    {
                        name: 'setPeriod',
                        text: '反结账(仓库)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCheckout == 0 && row.isUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('确定要反结账？')) {
                                // console.log(row.businessBelong);
                                $.post("?model=finance_period_period&action=uncheckout",
                                    { "id": row.id, 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('反结账成功');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('反结账失败');
                                        }
                                    }
                                );
                            }
                        }
                    });
            }
        }
    });

    // 费用周期权限获取
    $.ajax({
        type: 'POST',
        url: '?model=finance_period_period&action=getLimits',
        data: {
            'limitName': '费用周期权限'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                menuArr.push({
                        name: 'checkout',
                        text: '结账(费用)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCostCheckout == 0 && row.isCostUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('确定要结账？')) {
                                $.post("?model=finance_period_period&action=checkout",
                                    { "id": row.id, "type": 'cost', 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('结账成功');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('结账失败');
                                            $("#periodGrid").yxgrid("reload");
                                        }
                                    }
                                );
                            }
                        }
                    }, {
                        name: 'close',
                        text: '关账(费用)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCostCheckout == 0 && row.isCostClosed == 0 && row.isCostUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('确定要关账？')) {
                                $.post("?model=finance_period_period&action=close",
                                    { "id": row.id, "type": 'cost', 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('关账成功');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('关账失败');
                                            $("#periodGrid").yxgrid("reload");
                                        }
                                    }
                                );
                            }
                        }
                    }, {
                        name: 'unclose',
                        text: '反关账(费用)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCostCheckout == 0 && row.isCostClosed == 1 && row.isCostUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('确定要反关账？')) {
                                $.post("?model=finance_period_period&action=unclose",
                                    { "id": row.id, "type": 'cost', 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('反关账成功');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('反关账失败');
                                            $("#periodGrid").yxgrid("reload");
                                        }
                                    }
                                );
                            }
                        }
                    }, {
                        name: 'setPeriod',
                        text: '反结账(费用)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCostCheckout == 0 && row.isCostUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('确定要反结账？')) {
                                $.post("?model=finance_period_period&action=uncheckout",
                                    { "id": row.id, "type": 'cost', 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('反结账成功');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('反结账失败');
                                        }
                                    }
                                );
                            }
                        }
                    }
                );
            }
        }
    });

    $("#periodGrid").yxgrid({
        model: 'finance_period_period',
        title: '财务会计期间表',
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        showcheckbox: false,
        //列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'businessBelong',
                display: '归属公司ID',
                sortable: true,
                hide: true
            },
            {
                name: 'businessBelongName',
                display: '归属公司',
                sortable: true
            },
            {
                name: 'periodNo',
                display: '会计期号',
                sortable: true
            },
            {
                name: 'thisYear',
                display: '年',
                sortable: true
            },
            {
                name: 'thisMonth',
                display: '月',
                sortable: true
            },
            {
                name: 'isUsing',
                display: '仓库当前财务期',
                sortable: true,
                process: function (v) {
                    if (v != 0) {
                        return '<span class="red">是</span>';
                    } else {
                        return '否';
                    }
                }
            },
            {
                name: 'isCheckout',
                display: '仓库是否已结账',
                sortable: true,
                process: function (v) {
                    if (v == 0) {
                        return '否';
                    } else {
                        return '<span class="red">是</span>';
                    }
                }
            },
            {
                name: 'isClosed',
                display: '仓库是否已关账',
                sortable: true,
                process: function (v) {
                    if (v == 0) {
                        return '否';
                    } else {
                        return '<span class="red">是</span>';
                    }
                }
            },
            {
                name: 'isCostUsing',
                display: '费用当前财务期',
                sortable: true,
                process: function (v) {
                    if (v != 0) {
                        return '<span class="red">是</span>';
                    } else {
                        return '否';
                    }
                }
            },
            {
                name: 'isCostCheckout',
                display: '费用是否已结账',
                sortable: true,
                process: function (v) {
                    if (v == 0) {
                        return '否';
                    } else {
                        return '<span class="red">是</span>';
                    }
                }
            },
            {
                name: 'isCostClosed',
                display: '费用是否已关账',
                sortable: true,
                process: function (v) {
                    if (v == 0) {
                        return '否';
                    } else {
                        return '<span class="red">是</span>';
                    }
                }
            }
        ],
        buttonsEx: [
            {
                name: 'setPeriod',
                text: '设置当前财务日期',
                icon: 'add',
                action: function () {
                    showThickboxWin('?model=finance_period_period&action=toCreatePeriod'
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=500"
                        + "&width=800");
                }
            }
        ],
        comboEx: [{
            text: "归属公司",
            key: 'businessBelong',
            data: companyData
        }],
        menusEx: menuArr
    });
});