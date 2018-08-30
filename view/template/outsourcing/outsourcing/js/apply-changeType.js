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
        alert("已经是最后一行了！");
    } else {
        th.parent().parent().remove();
    }
}

function changeSl() {//统计租借人员数量
    var totalNum = 0;
    var cmps = $("#wapdiv").yxeditgrid("getCmpByCol", "peopleCount");
    cmps.each(function () {
        totalNum = accAdd(totalNum, $(this).val(), 0);
    });
    $("#total").val(totalNum);
}

function changeCB() {//统计租借人员成本
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

function changeWB() {//统计租借人员外包价格
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

//计算天数
function countDate(thisKey, rowNum) {
    var objGrid = $("#wapdiv");
    //加入日期
    var planBeginDateObj = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "startTime");
    //离开日期
    var planEndDateObj = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "endTime");

    if (planBeginDateObj.val() != "" && planEndDateObj.val() != "") {
        //实际天数
        var actDay = DateDiff(planBeginDateObj.val(), planEndDateObj.val()) + 1;

        //设置实际天数
        objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "totalDay").val(actDay);
    }
}

//计算人力成本
function countPerson(rowNum) {
    var objGrid = $("#wapdiv");

    //单价
    var price = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "inBudgetPrice").val();
    if (price != "") {
        var numberOne = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "peopleCount").val();
        var numberTwo = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "totalDay").val();

        if (numberOne != "" && numberTwo != "") {
            var budgetDay = accMul(numberOne, numberTwo, 2); //人天
            var amount = accMul(budgetDay, price, 2); //成本
            objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "inBudget").val(amount);
        }
    }
}

//计算人力成本
function countPersonOut(rowNum) {
    var objGrid = $("#wapdiv");

    //单价
    var price = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "outBudgetPrice").val();
    if (price != "") {
        var numberOne = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "peopleCount").val();
        var numberTwo = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "totalDay").val();

        if (numberOne != "" && numberTwo != "") {
            var budgetDay = accMul(numberOne, numberTwo, 2); //人天
            var amount = accMul(budgetDay, price, 2); //成本
            objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "outBudget").val(amount);
        }
    }
}