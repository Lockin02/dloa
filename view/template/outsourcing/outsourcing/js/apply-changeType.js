function changeType(v) {
    if (v == "1" || v == "2") {
        $(".typeForm").show();
        $(".typeGrid").hide();
    } else if (v == "3") {
        $(".typeForm").hide();
        $(".typeGrid").show();
        try {
            initPerson();
        } catch (e) {
            console.log(e);
        }
    }
}

function userName(oth) {
    var x;
    oth.find("input").each(function () {
        x = $(this).attr("name");
        x = x.replace("stiehao", "");
        $(this).attr("name", x);
    });
    oth.find("textarea").each(function () {
        x = $(this).attr("name");
        x = x.replace("stiehao", "");
        $(this).attr("name", x);
    });
    oth.find("select").each(function () {
        x = $(this).attr("name");
        x = x.replace("stiehao", "");
        $(this).attr("name", x);
    });
}

function notUseNname(th) {
    var x;
    th.find("input").each(function () {
        x = $(this).attr("name");
        $(this).attr("name", "stiehao" + x);
    });
    th.find("textarea").each(function () {
        x = $(this).attr("name");
        $(this).attr("name", "stiehao" + x);
    });
    th.find("select").each(function () {
        x = $(this).attr("name");
        $(this).attr("name", "stiehao" + x);
    });
}

function addline() {
    var yb = '<tr class="line" >' + $(".line").eq(0).html() + "</tr>";
    $(".line").last().after(yb);
    var i = 0;
    $(".line").each(function () {
        $(this).find("input").eq(0).attr("name", "appjb" + i);
        $(this).find("input").eq(1).attr("name", "appsl" + i);
        $(this).find("input").eq(2).attr("name", "appta" + i);
        $(this).find("input").eq(3).attr("name", "apptb" + i);
        $(this).find("input").eq(4).attr("name", "appcb" + i);
        $(this).find("input").eq(5).attr("name", "appwb" + i);
        $(this).find("input").eq(6).attr("name", "appjn" + i);
        i++;
    });
}
function deleteline(th) {
    var i = $(".line").length;
    if (i <= 1) {
        alert("�Ѿ������һ���ˣ�");
    } else {
        th.parent().parent().remove();
    }
}

function changeSl() {//ͳ�������Ա����
    var totalNum = 0;
    var cmps = $("#wapdiv").yxeditgrid("getCmpByCol", "peopleCount");
    cmps.each(function () {
        totalNum = accAdd(totalNum, $(this).val(), 0);
    });
    $("#total").val(totalNum);
}

function changeCB() {//ͳ�������Ա�ɱ�
    var pcb = 0;
    var cmps = $("#wapdiv").yxeditgrid("getCmpByCol", "inBudget");
    cmps.each(function () {
        pcb = accAdd(pcb, $(this).val(), 0);
    });
    $("#total2").val(pcb);

    var cy = parseInt($("#total3").val()) - pcb;
    if (cy == "NaN" || !cy) {
        $("#diversity").val("");
    } else {
        $("#diversity").val(cy);
        var str = Math.round((parseFloat(cy / pcb) * 100) * 100 / 100) + "%";
        $("#costingc").val(str);
    }

}

function changeWB() {//ͳ�������Ա����۸�
    var ocb = 0;
    var cmps = $("#wapdiv").yxeditgrid("getCmpByCol", "outBudget");
    cmps.each(function () {
        ocb = accAdd(ocb, $(this).val(), 0);
    });
    $("#total3").val(ocb);
    var pcb = parseInt($("#total2").val());
    var cy = ocb - pcb;
    if (cy == "NaN" || !cy) {
        $("#diversity").val("");
    } else {
        $("#diversity").val(cy);
        var str = Math.round((parseFloat(cy / pcb) * 100) * 100 / 100) + "%";
        $("#costingc").val(str);
    }
}

//��������
function countDate(thisKey, rowNum) {
    var objGrid = $("#wapdiv");
    //��������
    var planBeginDateObj = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "startTime");
    //�뿪����
    var planEndDateObj = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "endTime");

    if (planBeginDateObj.val() != "" && planEndDateObj.val() != "") {
        //ʵ������
        var actDay = DateDiff(planBeginDateObj.val(), planEndDateObj.val()) + 1;

        //����ʵ������
        objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "totalDay").val(actDay);
    }
}

//���������ɱ�
function countPerson(rowNum) {
    var objGrid = $("#wapdiv");

    //����
    var price = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "inBudgetPrice").val();
    if (price != "") {
        var numberOne = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "peopleCount").val();
        var numberTwo = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "totalDay").val();

        if (numberOne != "" && numberTwo != "") {
            var budgetDay = accMul(numberOne, numberTwo, 2); //����
            var amount = accMul(budgetDay, price, 2); //�ɱ�
            objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "inBudget").val(amount);
        }
    }
}

//���������ɱ�
function countPersonOut(rowNum) {
    var objGrid = $("#wapdiv");

    //����
    var price = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "outBudgetPrice").val();
    if (price != "") {
        var numberOne = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "peopleCount").val();
        var numberTwo = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "totalDay").val();

        if (numberOne != "" && numberTwo != "") {
            var budgetDay = accMul(numberOne, numberTwo, 2); //����
            var amount = accMul(budgetDay, price, 2); //�ɱ�
            objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "outBudget").val(amount);
        }
    }
}