var show_page = function(page) {
    $("#content").yxgrid("reload");
}

$(function() {
    $("#content").yxgrid({
        model: 'flights_balance_balance',
        title: '订票结算',
		showcheckbox : false,
        isAddAction: false,
        isDelAction: false,
        isOpButton : false,
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        },
        {
            name: 'balanceCode',
            display: '结算单号',
            sortable: true,
            width : 120
        },
        {
            name: 'balanceDateB',
            display: '结算开始',
            sortable: true,
            width : 80
        },
        {
            name: 'balanceDateE',
            display: '结算结束',
            sortable: true,
            width : 80
        },
        {
            name: 'balanceSum',
            display: '单据金额',
            sortable: true,
            process : function(v){
            	return moneyFormat2(v);
            },
            width : 80
        },
        {
            name: 'actualMoney',
            display: '结算金额',
            sortable: true,
            process : function(v){
            	return moneyFormat2(v);
            },
            width : 80
        },
        {
            name: 'rebate',
            display: '返利',
            sortable: true,
            process : function(v){
            	return moneyFormat2(v);
            },
            width : 70
        },
        {
            name: 'exchange',
            display: '兑换积分数',
            sortable: true,
            process : function(v){
            	return moneyFormat2(v);
            },
            width : 80
        },
        {
            name: 'exchangeMoney',
            display: '积分兑换金额',
            sortable: true,
            process : function(v){
            	return moneyFormat2(v);
            },
            width : 80
        },
        {
            name: 'deptId',
            display: '部门ID',
            sortable: true,
            hide: true
        },
        {
            name: 'deptName',
            display: '结算部门',
            sortable: true,
            hide: true
        },
        {
            name: 'balanceStatus',
            display: '结算状态',
            sortable: true,
            process: function(row) {
                if (row == "0") {
                    return "<span style='color: red;' >未支付</span>";
                } else if(row == "1") {
                    return "<span style='color: green;' >已支付</span>";
                } else if(row == "2") {
                    return "<span style='color: blue;' >支付流程中</span>";
                }
            },
            width : 70
        },
        {
            name: 'billCode',
            display: '发票编号',
            sortable: true
        },
        {
            name: 'createName',
            display: '创建人',
            sortable: true,
            hide: true
        },
        {
            name: 'createTime',
            display: '创建时间',
            sortable: true,
            width : 130
        }],
		toEditConfig : {
			action : 'toEdit',
			showMenuFn : function(row) {
                return row.balanceStatus == '0';
			},
			toEditFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=flights_balance_balance&action=toEdit&id=" + rowData[p.keyField] ,1,rowData.id);
			}
		},
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=flights_balance_balance&action=toView&id=" + rowData[p.keyField] ,1,rowData.id);
			}
		},
        // 扩展按钮
        buttonsEx: [{
            text: '生成结算单',
            icon: 'add',
            action: function(row) {
                showModalWin("?model=flights_balance_balance&action=toSubAdd");
            }
        }],
        // 扩展右键菜单
        menusEx: [{
            name: 'delete',
            text: '删除',
            icon: 'delete',
            showMenuFn: function(row) {
                if (row.balanceStatus == 1 || row.billCode != "") {
                    return false;
                } else {
                    return true;
                }
            },
            action: function(rowDate) {
                if (confirm("确认要删除吗?")) {
                    $.ajax({
                        type: "POST",
                        url: "?model=flights_balance_balance&action=delete",
                        data: {
                            'id': rowDate.id,
                            'msgId' : rowDate.msgId,
                            'itemIds' : rowDate.itemIds
                        },
                        async: false,
                        success: function(data) {
                            if (data == 1) {
                                alert("删除成功");
                                show_page();

                            } else {
                                alert("删除失败");
                            }
                        }

                    });
                }
            }
        },
        {
            text: '录入发票',
            icon: 'add',
            showMenuFn: function(row) {
                if (row.billCode != "") {
                    return false;
                }
            },
            action: function(row, rows) {
                if (rows && rows.length == 1) {
                    if (row.billCode == "") {
                        showThickboxWin("?model=flights_balance_bill&action=toAdd&id="
                        	+ row.id
                        	+ "&balanceCode=" + row.balanceCode
                        	+ "&actualMoney=" + row.actualMoney
                        	+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
                    } else {
                        alert("此数据已有发票");
                    }
                } else {
                    alert('请选择一条数据');
                }
            }
        },
        {
            text: '提请支付',
            icon: 'add',
            showMenuFn: function(row) {
                if (row.balanceStatus != '0' || row.billCode == "") {
                    return false;
                }
            },
            action: function(row) {
                if (row.balanceStatus == 1) {
                    alert('此订单已支付');
                } else {
                    if (row.billCode != "") {
                        showModalWin("?model=contract_other_other&action=toAddPay&projectType=QTHTXMLX-03&projectId="
                        	+row.id
                        	+"&projectCode="
                        	+row.balanceCode
                        	+"&projectName="
                        	+row.balanceCode
                        	+"&orderMoney="
                        	+row.actualMoney
                    	);
                    } else {
                        alert('此数据没有发票，不能提请支付！');
                    }
                }
            }
        },
        {
            text: '修改发票信息',
            icon: 'edit',
            showMenuFn: function(row) {
                if (row.billCode == "" || row.balanceStatus == '1') {
                    return false;
                }
            },
            action: function(row, rows, grid) {
                if (row.billCode != "") {
                    showThickboxWin("?model=flights_balance_bill&action=toEdit&mainId=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                } else {
                    alert("此数据没有发票");
                }
            }
        }],
        comboEx : [ {// 头栏目下拉查看
			text : '结算状态',
			key : 'balanceStatus',
			data : [{
				text : '未支付',
				value : '0'
			}, {
				text : '已支付',
				value : '1'
			}, {
				text : '支付流程中',
				value : '2'
			}]
		} ],
        searchitems: [{
            display: "结算票号",
            name: 'balanceCode'
        }],
        sortorder: "DESC"
    });
});