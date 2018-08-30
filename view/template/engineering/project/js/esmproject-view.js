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

    // �������Ԥ��ʱ��������ʾ
    if ($("#feeAll").val() * 1 > $("#budgetAll").val() * 1) {
        $("#feeAllShow").addClass('red');
    }

    // ë��Ԥ��
    var exgrossObj = $("#exgross");
    if (exgrossObj.val() * 1 < $("#budgetExgross").val() * 1) {
        $(".exgross").attr('style', 'color:red').find(".Bar").find("div").find('span').attr('style', 'color:red');
    }

    // ��ë��ʱ���ı���ȾΪ��ɫ
    if ($("#grossProfit").val() * 1 < 0) {
        $(".grossProfit").attr('style', 'color:red').find(".Bar").find("div").find('span').attr('style', 'color:red');
    }

    // ����Ŀ���ȵ���0ʱ
    if ($("#projectProcess").val() * 1 == 0) {
        $("#SPIShow").html('-');
    } else {
        // SPIԤ��
        var SPI = $("#SPI").val() * 1;
        if (SPI >= 0 && SPI < 0.8) {
            $(".SPI").attr('style', 'color:red');
        }
    }

    // ����Ŀ���ȵ���0ʱ
    if ($("#feeAll").val() * 1 == 0) {
        $("#CPIShow").html('-');
    } else {
        // CPIԤ��
        var CPI = $("#CPI").val() * 1;
        if (CPI >= 0 && CPI  < 0.8) {
            $(".CPI").attr('style', 'color:red');
        }
    }

    // ��Ⱦ�������
    initShowProcess('budgetAll');
    initShowProcess('budgetPerson');
    initShowProcess('budgetField');
    initShowProcess('budgetEqu');
    initShowProcess('budgetOutsourcing');
    initShowProcess('budgetOther');
});