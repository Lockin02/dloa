/**
 * Created by Kuangzw on 2017/3/30.
 */
$(function () {
    $("#confirm1").click(function () {
        if (confirm('�������ݻ���Ҫ���ѽϳ�ʱ�䣬ȷ�����д˲�����')) {
            $("#showMsg").text('���ݸ�����......');
            var imgObj = $("#imgLoading");
            //��ʾ����ͼ
            imgObj.show();
            //��ʾ��ʾ
            $("#trip").show();

            //���ð�ť
            var btnObj = $(this);
            btnObj.attr('disabled', true);

            setTimeout(function () {
                //���ø��¹���
                $.ajax({
                    type: "POST",
                    url: "?model=contract_counting_counting&action=update",
                    data: {
                        contractCode: $("#contractCode1").val(),
                        year: $("#year1").val(), "month": $("#month1").val()
                    },
                    success: function (data) {
                        if (data == '0') {
                            $("#showMsg").text('�����ݸ���');
                        } else {
                            if (data == "1") {
                                $("#showMsg").text('���³ɹ�');
                            } else {
                                $("#showMsg").text(data);
                            }
                        }
                        //���ؽ���ͼ
                        imgObj.hide();
                        btnObj.attr('disabled', false);
                    }
                });
            }, 200);
        }
    });

    // ����ɾ��
    $("#resetDel").click(function () {
        $("#showMsg").text('���ݴ�����......');
        var imgObj = $("#imgLoading");
        //��ʾ����ͼ
        imgObj.show();
        //��ʾ��ʾ
        $("#trip").show();

        //���ð�ť
        var btnObj = $(this);
        btnObj.attr('disabled', true);
        $.ajax({
            type: "POST",
            url: "?model=contract_counting_counting&action=update",
            data: {
                contractCode: $("#contractCode1").val(),
                year: $("#year1").val(),
                "month": $("#month1").val(),
                "resetField" : 'isDel'
            },
            success: function (data) {
                if (data == '0') {
                    $("#showMsg").text('����ʧ��');
                } else {
                    if (data == "1") {
                        $("#showMsg").text('���³ɹ�');
                    } else {
                        $("#showMsg").text(data);
                    }
                }
                //���ؽ���ͼ
                imgObj.hide();
                btnObj.attr('disabled', false);
            }
        });
    });

    // ����������
    $("#resetTrue").click(function () {
        $("#showMsg").text('���ݴ�����......');
        var imgObj = $("#imgLoading");
        //��ʾ����ͼ
        imgObj.show();
        //��ʾ��ʾ
        $("#trip").show();

        //���ð�ť
        var btnObj = $(this);
        btnObj.attr('disabled', true);
        $.ajax({
            type: "POST",
            url: "?model=contract_counting_counting&action=update",
            data: {
                contractCode: $("#contractCode1").val(),
                year: $("#year1").val(),
                "month": $("#month1").val(),
                "resetField" : 'isTrue'
            },
            success: function (data) {
                if (data == '0') {
                    $("#showMsg").text('����ʧ��');
                } else {
                    if (data == "1") {
                        $("#showMsg").text('���³ɹ�');
                    } else {
                        $("#showMsg").text(data);
                    }
                }
                //���ؽ���ͼ
                imgObj.hide();
                btnObj.attr('disabled', false);
            }
        });
    });
});