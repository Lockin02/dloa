var show_page = function (page) {
    $("#periodGrid").yxgrid("reload");
};

// ��ȡ��˾ѡ��
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

    // �Ҽ��˵���ť����
    var menuArr = [];
    var companyData = [];
    companyData = getCompanyOptions();

    // �ֿ�����Ȩ�޻�ȡ
    $.ajax({
        type: 'POST',
        url: '?model=finance_period_period&action=getLimits',
        data: {
            'limitName': '�ֿ�����Ȩ��'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                menuArr.push(
                    {
                        name: 'checkout',
                        text: '����(�ֿ�)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCheckout == 0 && row.isUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('ȷ��Ҫ���ˣ�')) {
                                $.post("?model=finance_period_period&action=checkout",
                                    { "id": row.id, 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('���˳ɹ�');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('����ʧ��');
                                            $("#periodGrid").yxgrid("reload");
                                        }
                                    }
                                );
                            }
                        }
                    },
                    {
                        name: 'close',
                        text: '����(�ֿ�)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCheckout == 0 && row.isClosed == 0 && row.isUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('ȷ��Ҫ���ˣ�')) {
                                $.post("?model=finance_period_period&action=close",
                                    { "id": row.id, 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('���˳ɹ�');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('����ʧ��');
                                            $("#periodGrid").yxgrid("reload");
                                        }
                                    }
                                );
                            }
                        }
                    },
                    {
                        name: 'unclose',
                        text: '������(�ֿ�)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCheckout == 0 && row.isClosed == 1 && row.isUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('ȷ��Ҫ�����ˣ�')) {
                                $.post("?model=finance_period_period&action=unclose",
                                    { "id": row.id, 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('�����˳ɹ�');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('������ʧ��');
                                            $("#periodGrid").yxgrid("reload");
                                        }
                                    }
                                );
                            }
                        }
                    },
                    {
                        name: 'setPeriod',
                        text: '������(�ֿ�)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCheckout == 0 && row.isUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('ȷ��Ҫ�����ˣ�')) {
                                // console.log(row.businessBelong);
                                $.post("?model=finance_period_period&action=uncheckout",
                                    { "id": row.id, 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('�����˳ɹ�');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('������ʧ��');
                                        }
                                    }
                                );
                            }
                        }
                    });
            }
        }
    });

    // ��������Ȩ�޻�ȡ
    $.ajax({
        type: 'POST',
        url: '?model=finance_period_period&action=getLimits',
        data: {
            'limitName': '��������Ȩ��'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                menuArr.push({
                        name: 'checkout',
                        text: '����(����)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCostCheckout == 0 && row.isCostUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('ȷ��Ҫ���ˣ�')) {
                                $.post("?model=finance_period_period&action=checkout",
                                    { "id": row.id, "type": 'cost', 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('���˳ɹ�');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('����ʧ��');
                                            $("#periodGrid").yxgrid("reload");
                                        }
                                    }
                                );
                            }
                        }
                    }, {
                        name: 'close',
                        text: '����(����)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCostCheckout == 0 && row.isCostClosed == 0 && row.isCostUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('ȷ��Ҫ���ˣ�')) {
                                $.post("?model=finance_period_period&action=close",
                                    { "id": row.id, "type": 'cost', 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('���˳ɹ�');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('����ʧ��');
                                            $("#periodGrid").yxgrid("reload");
                                        }
                                    }
                                );
                            }
                        }
                    }, {
                        name: 'unclose',
                        text: '������(����)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCostCheckout == 0 && row.isCostClosed == 1 && row.isCostUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('ȷ��Ҫ�����ˣ�')) {
                                $.post("?model=finance_period_period&action=unclose",
                                    { "id": row.id, "type": 'cost', 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('�����˳ɹ�');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('������ʧ��');
                                            $("#periodGrid").yxgrid("reload");
                                        }
                                    }
                                );
                            }
                        }
                    }, {
                        name: 'setPeriod',
                        text: '������(����)',
                        icon: 'edit',
                        showMenuFn: function (row) {
                            return row.isCostCheckout == 0 && row.isCostUsing == 1;
                        },
                        action: function (row) {
                            if (confirm('ȷ��Ҫ�����ˣ�')) {
                                $.post("?model=finance_period_period&action=uncheckout",
                                    { "id": row.id, "type": 'cost', 'businessBelong' : row.businessBelong},
                                    function (data) {
                                        if (data == 1) {
                                            alert('�����˳ɹ�');
                                            $("#periodGrid").yxgrid("reload");
                                        } else {
                                            alert('������ʧ��');
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
        title: '�������ڼ��',
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        showcheckbox: false,
        //����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'businessBelong',
                display: '������˾ID',
                sortable: true,
                hide: true
            },
            {
                name: 'businessBelongName',
                display: '������˾',
                sortable: true
            },
            {
                name: 'periodNo',
                display: '����ں�',
                sortable: true
            },
            {
                name: 'thisYear',
                display: '��',
                sortable: true
            },
            {
                name: 'thisMonth',
                display: '��',
                sortable: true
            },
            {
                name: 'isUsing',
                display: '�ֿ⵱ǰ������',
                sortable: true,
                process: function (v) {
                    if (v != 0) {
                        return '<span class="red">��</span>';
                    } else {
                        return '��';
                    }
                }
            },
            {
                name: 'isCheckout',
                display: '�ֿ��Ƿ��ѽ���',
                sortable: true,
                process: function (v) {
                    if (v == 0) {
                        return '��';
                    } else {
                        return '<span class="red">��</span>';
                    }
                }
            },
            {
                name: 'isClosed',
                display: '�ֿ��Ƿ��ѹ���',
                sortable: true,
                process: function (v) {
                    if (v == 0) {
                        return '��';
                    } else {
                        return '<span class="red">��</span>';
                    }
                }
            },
            {
                name: 'isCostUsing',
                display: '���õ�ǰ������',
                sortable: true,
                process: function (v) {
                    if (v != 0) {
                        return '<span class="red">��</span>';
                    } else {
                        return '��';
                    }
                }
            },
            {
                name: 'isCostCheckout',
                display: '�����Ƿ��ѽ���',
                sortable: true,
                process: function (v) {
                    if (v == 0) {
                        return '��';
                    } else {
                        return '<span class="red">��</span>';
                    }
                }
            },
            {
                name: 'isCostClosed',
                display: '�����Ƿ��ѹ���',
                sortable: true,
                process: function (v) {
                    if (v == 0) {
                        return '��';
                    } else {
                        return '<span class="red">��</span>';
                    }
                }
            }
        ],
        buttonsEx: [
            {
                name: 'setPeriod',
                text: '���õ�ǰ��������',
                icon: 'add',
                action: function () {
                    showThickboxWin('?model=finance_period_period&action=toCreatePeriod'
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=500"
                        + "&width=800");
                }
            }
        ],
        comboEx: [{
            text: "������˾",
            key: 'businessBelong',
            data: companyData
        }],
        menusEx: menuArr
    });
});