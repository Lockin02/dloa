/**
 * ��ʼʡ������
 * @param {} $t
 * @return {Boolean}
 */
function initOffice() {
    //��ȡʡ�ݶ�Ӧ�İ��´�
    $.ajax({
        type: "POST",
        url: "?model=engineering_officeinfo_range&action=getOfficeInfoForId",
        data: {
            "provinceId": $("#provinceId").val(),
            "businessBelong": $("#businessBelong").val(),
            "productLine": $("#productLine").val()
        },
        async: false,
        success: function (data) {
            if (data != "") {
                var dataObj = eval("(" + data + ")");
                $("#officeName").val(dataObj.officeName);
                $("#officeId").val(dataObj.officeId);
                $("#deptId").val(dataObj.feeDeptId);
                $("#deptName").val(dataObj.feeDeptName);
                $("#productLine").val(dataObj.productLine);
            }
        }
    });
}

//����������رղ���֤
function timeCheck($t) {
    var startDate = $('#planBeginDate').val();
    var endDate = $('#planEndDate').val();
    if (startDate == "" || endDate == "") {
        return false;
    }
    var s = DateDiff(startDate, endDate) + 1;
    if (s < 0) {
        alert("Ԥ�ƿ�ʼ���ܱ�Ԥ�ƽ���ʱ����");
        $t.value = "";
        return false;
    }
}

// ��ȡ���߿���ռ��
function initWorkRate() {
    $("#workRate").attr('readonly', true).removeClass('txt').addClass('readOnlyText');
    $.ajax({
        type: "POST",
        url: "?model=engineering_project_esmproject&action=getWorkrateCanUse",
        data: {
            "contractId": $("#contractId").val(),
            "contractType": $("#contractType").val(),
            "productLine": $("#productLine").val()
        },
        async: false,
        success: function (data) {
            var workRateObj = $("#workRate");
            if (workRateObj.val() * 1 == 0 || data * 1 < workRateObj.val() * 1) {
                workRateObj.val(data);
            }
            $("#remainWorkRate").val(data);
            workRateObj.attr('readonly', false).removeClass('readOnlyText').addClass('txt');
        }
    });
}

//����֤
function checkform() {
    var workRateObj = $("#workRate");
    var remainWorkRateObj = $("#remainWorkRate");

    if (workRateObj.val() * 1 == 0) {
        alert('����ռ�Ȳ���Ϊ0');
        return false;
    }

    if (workRateObj.val() * 1 > remainWorkRateObj.val() * 1) {
        alert('����ռ���ѳ�����Χ');

        workRateObj.val(remainWorkRateObj.val());
        return false;
    }

    //��Ŀ���ΨһУ��
    var unRepeat = true;
    $.ajax({
        type: "POST",
        url: "?model=engineering_project_esmproject&action=checkIsRepeat",
        data: {projectCode: $("#projectCode").val()},
        async: false,
        success: function (data) {
            if (data == "1") {
                alert('��Ŀ����Ѵ���');
                unRepeat = false;
            }
        }
    });

    //  ��֤��Ʒ��
    if(unRepeat == true){
        $.ajax({
            type: "POST",
            url: "?model=contract_conproject_conproject&action=getisExistByLine",
            data: {cid: $("#contractId").val(), productLine: $("#productLine").val()},
            async: false,
            success: function (data) {
                if (data == "1") {
                    alert('�ò�Ʒ���ں�ͬ�в����ڣ����ܽ����������');
                    unRepeat = false;
                }
            }
        });
    }

    return unRepeat;
}

$(document).ready(function () {
    // ��������
    initOffice();

    // ��ѡ���´�
    $("#officeName").yxcombogrid_office({
        hiddenId: 'officeId',
        height: 250,
        gridOptions: {
            showcheckbox: false,
            isTitle: true,
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#deptId").val(data.feeDeptId);
                    $("#deptName").val(data.feeDeptName);
                    $("#productLine").val(data.productLine);
                    initWorkRate();
                }
            }

        }
    });

    // ��ʼ�����߿���ռ��
    initWorkRate();

    // ��ѡ��Ŀ����
    $("#managerName").yxselect_user({
        hiddenId: 'managerId',
        mode: 'check',
        formCode: 'esmcharter'
    });

    // ��������
    $("#deptName").yxselect_dept({
        hiddenId: 'deptId'
    });

    // ��ʼ��������Ϣ
    initCity();

    $("#country").change(function () {
        if ($(this).find("option:selected").text() != '�й�') {
            //	alert('����');
            var cityUrl = "?model=system_procity_city&action=pageJson"; // ��ȡ�е�URL
            $("#province").val('32');
            var provinceId = 32;
            $.ajax({
                type: 'POST',
                url: cityUrl,
                data: {
                    provinceId: provinceId,
                    pageSize: 999
                },
                async: false,
                success: function (data) {
                    $('#city').children().remove("option[value!='']");
                    getCitys(data);
                    $('#city').find("option[text='����']").attr("selected", true);
                }
            });
        }
    });

    // ��֤��Ϣ
    validate({
        "projectCode": {	//��Ŀ����
            required: true,
            length: [0, 100]
        },
        "projectName": {	//��Ŀ����
            required: true,
            length: [0, 100]
        },
        "officeName": {	//���´�
            required: true,
            length: [0, 20]
        },
        "managerName": {	//��Ŀ����
            required: true,
            length: [0, 20]
        },
        "planBeginDate": {	//Ԥ����������
            custom: ['date']
        },
        "planEndDate": {	//Ԥ�ƽ�������
            custom: ['date']
        },
        "workRate": {	//����ռ��
            required: true,
            custom: ['percentage']
        },
        "country": {
            required: true
        },
        "province": {
            required: true
        },
        "city": {
            required: true
        },
        "deptName": {
            required: true
        },
        "category": {
            required: true
        }
    });

    /**
     * ���Ψһ����֤
     */
    var url = "?model=engineering_project_esmproject&action=checkRepeat";
    $("#projectCode").ajaxCheck({
        url: url,
        alertText: "* �ñ���Ѵ���",
        alertTextOk: "* �ñ�ſ���"
    });
});