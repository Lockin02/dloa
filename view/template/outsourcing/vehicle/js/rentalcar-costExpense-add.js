$(function(){
    var registerids = window.parent.registeridsGlobal;
    if(registerids != undefined && registerids != ''){
        $("#registerIds").val(registerids);
    }

    billTypeArr = getBillType();
    billTypeStr = setBillTypeStr(billTypeArr);

    if($("#costTypeInner").html() == ""){
        var payDetailJson = $("#payDetailJson").val();
        var payDetail = (payDetailJson != '')? eval("("+payDetailJson+")") : {};
        var expenseTmpId = $("#expenseTmpId").val();
        var useCarDateStr = $("#useCarDateStr").val();

        isFirst = true;
        $.ajax({
            type: "POST",
            url : "?model=outsourcing_vehicle_rentalcar&action=getCostTypeByRentalCarType",
            data : {payDetail : payDetail,expenseTmpId : expenseTmpId,useCarDateStr : useCarDateStr},
            async: false,
            success: function(data){
                var costType = eval("("+data+")");
                if(costType.length > 0){
                    var costTypeHtmlStr = "";
                    totalFee = 0;
                    $.each(costType,function(i,item){
                        if(item.costMoney > 0){// 只显示费用金额大于0的
                            costTypeHtmlStr += setCustomCostType(item.CostTypeID,item,i);
                        }
                    });

                    $("#costTypeInner").html(costTypeHtmlStr);
                    $("#imgLoading").hide();
                    $("#feeTbl").show();
                }
            }
        });

        countInvoiceInfo();

        var costMoneyCost = countMoney();
        $("#countMoney").val(costMoneyCost);
        $("#feeRegular").val(costMoneyCost);
    }
});