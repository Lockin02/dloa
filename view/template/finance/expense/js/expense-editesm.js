$(function() {
    //设置title
    initAmountTitle($("#feeRegular").val(), $("#feeSubsidy").val());

    //渲染同行人
    $("#memberNames").yxselect_user({
        hiddenId: 'memberIds',
        formCode: 'expenseMember',
        mode: 'check',
        event: {
            "select": function(obj, row) {
                if (row != undefined) {
                    if (row.val != '') {
                        var memberArr = row.val.split(',');
                        $("#memberNumber").val(memberArr.length);
                    } else {
                        $("#memberNumber").val('');
                    }
                }
            },
            "clearReturn": function() {
                $("#memberNumber").val('');
            }
        }
    });

    // 发票类型缓存
    billTypeArr = getBillType();
    
	//所属板块缓存
	moduleArr = getData('HTBK');
	//分摊金额合计初始化
	countAllCostshare();
});