$(function() {

    // 保存按钮
    $("#saveBtn").click(function() {
        $.ajax({
            url: "?model=common_otherdatas&action=updateConfig",
            data: {name: 'engineering_budget_payables', type: '', value: $("#value").val()},
            type: 'post',
            async: false,
            success: function(msg) {
                alert(msg);
            }
        });
    });
    var expenseObj = $("#expense");

    // 保存按钮
    $("#saveExpense").click(function() {
        $.ajax({
            url: "?model=common_otherdatas&action=updateConfig",
            data: {name: 'engineering_budget_expense', type: '', value: expenseObj.val()},
            type: 'post',
            async: false,
            success: function(msg) {
                if (msg == "success") {
                    $.ajax({
                        url: "?model=finance_expense_costtype&action=nameToId",
                        data: {names: expenseObj.val()},
                        type: 'post',
                        async: false,
                        success: function(expenseId) {
                            $.ajax({
                                url: "?model=common_otherdatas&action=updateConfig",
                                data: {name: 'engineering_budget_expense_id', type: '', value: expenseId},
                                type: 'post',
                                async: false,
                                success: function(msg) {
                                    alert(msg);
                                }
                            });
                        }
                    });
                } else {
                    alert(msg);
                }
            }
        });
    });

    // 初始化按钮
    $("#initBtn").click(function() {
        $.ajax({
            url: "?model=engineering_records_esmfieldrecord&action=init",
            data: {category: 'payables'},
            type: 'post',
            async: false,
            success: function(msg) {
                alert(msg);
            }
        });
    });
});

//选择自定义费用类型
function setCustomCostType(thisCostType,thisObj){
    if($(thisObj).attr('checked') == true){
        $("#view" + thisCostType).attr('class','blue');
    }else{
        $("#view" + thisCostType).attr('class','');
    }

    var valueArr = [];

    $("input[id^='chk']:checked").each(function() {
        valueArr.push($(this).attr('name'));
    });

    $("#value").val(valueArr.toString());
}