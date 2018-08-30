//表单验证方法
function checkform() {
    if ($("#inputExcel").val() == "") {
        alert("请选择需要导入的EXCEL文件");
        return false;
    }

//	alert("当前功能未完成");
    $("#loading").show();

    return true;
}

//备注信息显示
function changeInfo(thisVal) {
    if (thisVal == 0) {
        $("#remarkInfo").html('此导入功能对应带合同信息的excel模板，导入时会查询项目编号是否已存在，<br/>存在则更新，不存在则新增项目。<br/>导入功能只支持<span class="red">合同类项目/无合同类</span>导入。<br/>如需更新区域，请将区域/产线/公司三个字段都填写完成，否则不会更新区域信息。');
    } else if (thisVal == 1) {
        $("#remarkInfo").html('此导入功能对应<span class="red">《财务费用模板》</span>，<br/>由财务人员使用，用于导入更新项目的总费用。');
    } else if (thisVal == 2) {
        $("#remarkInfo").html('1.此导入功能对应<span class="red">《项目导出模板》</span>，用于更新项目数据，不做新增项目处理。<br/>' +
            '2.此功能<span class="red">不更新</span>总决算、现场决算(费用报销)、现场决算(费用维护)、<br/>' +
            '费用进度以及合同信息。<br/>' +
            '3.使用此功能更新现场预算、人力预算、设备预算可能导致项目预算信息混乱。');
    } else if (thisVal == 3) {
        $("#remarkInfo").html('此导入功能对应决算费用的模板，<br/>用于更新项目决算费用，无其余业务处理。');
    } else if (thisVal == 4) {
        $("#remarkInfo").html('此导入功能对应其他预决算，<br/>用于更新项目其他预决算，无其余业务处理。');
    } else if (thisVal == 5) {
        $("#remarkInfo").html('此导入功能对应外包预决算，<br/>用于更新项目外包预决算，无其余业务处理。');
    } else if (thisVal == 6) {
        $("#remarkInfo").html('此导入功能对应人力预决算，<br/>用于更新人力预决算，无其余业务处理。');
    } else if (thisVal == 7) {
        $("#remarkInfo").html('此导入功能对应设备决算，<br/>用于更新设备决算，无其余业务处理。');
    }

    var spanId = 'span' + thisVal;
    var buttonId = 'button' + thisVal;

    $.each($("span[id^='span']"), function (i, n) {
        if (this.id == spanId) {
            this.className = 'green';
        } else {
            this.className = '';
        }
    });

    $.each($("input[id^='button']"), function (i, n) {
        if (this.id == buttonId) {
            this.className = 'txt_btn_a_green';
        } else {
            this.className = 'txt_btn_a';
        }
    });
}