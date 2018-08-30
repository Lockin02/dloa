/**
 * Created by zhangjb on 16-6-30.
 */
$(function () {
    $('.processView').each(function () {
        if (parseFloat($(this).text()) == $(this).text()) {
            $(this).html(formatProgress($(this).text()));
        }
    });

    $('.processView2').each(function () {
        if (parseFloat($(this).text()) == $(this).text()) {
            $(this).html(formatProgress2($(this).text()));
        }
    });

    // 决算大于预算时，红字显示
    if ($("#feeAll").val() * 1 > $("#budgetAll").val() * 1) {
        $("#feeAllShow").addClass('red');
    }

    // 毛利预警
    var exgrossObj = $("#exgross");
    if (exgrossObj.val() * 1 < $("#budgetExgross").val() * 1) {
        $(".exgross").attr('style', 'color:red').find(".Bar").find("div").find('span').attr('style', 'color:red');
    }

    // 负毛利时将文本渲染为红色
    if ($("#grossProfit").val() * 1 < 0) {
        $(".grossProfit").attr('style', 'color:red').find(".Bar").find("div").find('span').attr('style', 'color:red');
    }

    // 当项目进度等于0时
    if ($("#projectProcess").val() * 1 == 0) {
        $("#SPIShow").html('-');
    } else {
        // SPI预警
        var SPI = $("#SPI").val() * 1;
        if (SPI >= 0 && SPI < 0.8) {
            $(".SPI").attr('style', 'color:red');
        }
    }

    // 当项目进度等于0时
    if ($("#feeAll").val() * 1 == 0) {
        $("#CPIShow").html('-');
    } else {
        // CPI预警
        var CPI = $("#CPI").val() * 1;
        if (CPI >= 0 && CPI  < 0.8) {
            $(".CPI").attr('style', 'color:red');
        }
    }

    // 渲染决算进度
    initShowProcess('budgetAll');
    initShowProcess('budgetPerson');
    initShowProcess('budgetField');
    initShowProcess('budgetEqu');
    initShowProcess('budgetOutsourcing');
    initShowProcess('budgetOther');
});