$(function () { 
    //渲染分摊明细
    $("#innerTableCostshare").yxeditgrid({
        objName: 'expensecostshare[expensecostshare]',
        title: '分摊明细',
        tableClass: 'form_in_table',
        url: '?model=finance_expense_expensecostshare&action=listJson',
        param: {
        	'BillNo': $("#BillNo").val(),
        	'CostTypeID': $("#CostTypeID").val()
        },
        isAdd: true,
        event: {
            removeRow: function (t, rowNum, rowData) {

            }
        },
        colModel: [{
            display: 'ID',
            name: 'ID',
            type: 'hidden'
        }, {
            display: '所属板块',
            name: 'module',
            validation: {
                required: true
            },
            tclass: 'select',
            type: 'select',
            datacode: 'HTBK'
        }, {
            display: '分摊金额',
            name: 'CostMoney',
            validation: {
                required: true
            },
            type: 'money',
            tclass: 'txt'
        }]
    })
});

//保存发票类型变更
function checkform() {
    //金额验证
    var costMoney = $("#costMoney").val();
    $("#innerTableCostshare").yxeditgrid("getCmpByCol", "CostMoney").each(function (i, n) {
        //计算当前单价
        costMoney = accSub(costMoney, this.value, 2);
    });
    //如果金额不为0，则单据金额不一致
    if (costMoney != 0) {
        alert('费用金额与分摊金额不一致');
        return false;
    }

    return confirm('确认修改吗？');
}