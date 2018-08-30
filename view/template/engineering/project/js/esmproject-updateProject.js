$(document).ready(function () {
    //�󶨸��������������¼�
    $("#confirmSalary").click(function () {
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
                    url: "?model=engineering_person_esmperson&action=updateSalary",
                    data: {"thisYear": $("#year").val(), "thisMonth": $("#month").val()},
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
    //�󶨸���ë���ʵ���¼�
    $("#confirmExgross").click(function () {
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
                    url: "?model=engineering_project_esmproject&action=updateExgross",
                    data: {"updateDate": $("#updateDate").val(), 'projectCode': $("#projectCode").val(), 'status': $("#status").val()},
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
    //�󶨸���PK��Ŀӳ�����¼�
    $("#confirmRemapping").click(function () {
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
                    url: "?model=engineering_project_esmmapping&action=remapping",
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

    //�󶨸����豸�������¼�
    $("#confirmEquFee").click(function () {
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
                    url: "?model=engineering_resources_esmdevicefee&action=updateFee",
                    data: {"thisYear": $("#yearEqu").val(), "thisMonth": $("#monthEqu").val()},
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

    //�󶨸����ֳ��������¼�
    $("#confirmFieldFee").click(function () {
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
                    url: "?model=engineering_records_esmfieldrecord&action=updateFee",
                    data: {"thisYear": $("#yearField").val(), "thisMonth": $("#monthField").val(), "projectCode": $("#projectCode5").val()},
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

    // ������Ŀ��Ϣ
    $("#confirmProjectInfo").click(function () {
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
                    url: "?model=engineering_project_esmproject&action=updateProjectFields",
                    data: {"projectCode": $("#projectCode8").val()},
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

    // �������ݴ浵
    $("#confirmProjectIncome").click(function () {
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
                    url: "?model=engineering_records_esmincome&action=updateIncome",
                    data: {projectCode: $("#incomeProjectCode").val()},
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

    // �������ݴ浵
    $("#confirmFeeVersion").click(function () {
        var year = $("#year7").val();
        if (year == "") {
            alert('��ݲ���Ϊ��');
            return false;
        }
        var month = $("#month7").val();
        if (month == "") {
            alert('�·ݲ���Ϊ��');
            return false;
        }
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
                    url: "?model=engineering_records_esmfielddetail&action=saveFeeVersion",
                    data: {
                        budgetType: $("#budgetType").val(), year: year, month: month,
                        projectCode: $("#feeProjectCode").val()
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

    // ��Ʒ��Ŀ - ��������汾
    $("#confirmIncome_c").click(function () {
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
                    url: "?model=contract_conproject_conproject&action=confirmIncome",
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
    // ��Ʒ��Ŀ - ���³ɱ��汾
    $("#confirmCost_c").click(function () {
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
                    url: "?model=contract_conproject_conproject&action=confirmCost",
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


    $("#updateConproject").click(function(){
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
                    url: "?model=contract_conproject_conproject&action=updateSaleProjectVal",
                    data: {"projectCode": $("#projectCodec3").val()},
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

    // ���²�Ʒ��Ŀ����
    $("#updateConprojectData").click(function(){
        if (confirm('ȷ�ϸ���������')) {
            showThickboxWin('?model=contract_conproject_conprojectRecord&action=updateRecord'
                + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
        }
    });

    // ����汾����
    $("#saveConprojectVersion").click(function(){
        showThickboxWin('?model=contract_conproject_conprojectRecord&action=toSetUsing'
            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=280&width=550');
    });
});

// �����������
function changeInfo(thisVal) {
    $("span[id^='remarkInfo']").each(function () {
        var selectdVal = $(this).attr('val');
        if (selectdVal == thisVal) {
            $(this).show();
            $("#span" + selectdVal).addClass('green');
            $("#condition" + selectdVal).show();
            $("#range" + selectdVal).show();
        } else {
            $(this).hide();
            $("#span" + selectdVal).removeClass('green');
            $("#condition" + selectdVal).hide();
            $("#range" + selectdVal).hide();
        }
    });
}
