$(function () {
    var borrowInput = $("#borrowInput").val();
    if (borrowInput == '1') {
        $("#Code").attr('class', "readOnlyTxtNormal");
        $("#orderTempCode").attr('readOnly', true);

    }
});
function toApp() {
    document.getElementById('form1').action = "index1.php?model=projectmanagent_borrow_borrow&action=add&act=app";
}
function toSave() {
    document.getElementById('form1').action = "index1.php?model=projectmanagent_borrow_borrow&action=add";

}
// Դ������
$(function () {
    var SingleTypeT = $("#SingleTypeT").val();
    switch (SingleTypeT) {
        case "" :
            document.getElementById("SingleType").options.length = 0;
            document.getElementById("SingleType").options
                .add(new Option("��Դ��", "��Դ��"));
            document.getElementById("SingleType").options
                .add(new Option("�̻�", "�̻�"));
            document.getElementById("SingleType").options
                .add(new Option("��ͬ", "��ͬ"));
            singleSelect();
            break;
        case "chance" :
            document.getElementById("SingleType").options.length = 0;
            document.getElementById("SingleType").options
                .add(new Option("�̻�", "�̻�"));
            singleSelect();
            break;
    }

});
// ��ȡԴ������ѡ��
function singleSelect() {
    var SingleType = $("#SingleType").val();
    var chanceCode = $("#chanceCodeT").val();
    var chanceId = $("#chanceIdT").val();
    switch (SingleType) {
        case "��Դ��" :
            $("#contractNum").yxcombogrid_allcontract('remove');
            $("#chanceCode").yxcombogrid_chance('remove');
            $("#single").html("<input type='text' class='readOnlyText' readonly='readonly'>");
            $("#SingleTypeT").val("");
            break;
        case "�̻�" :
            $("#contractNum").yxcombogrid_allcontract('remove');
            $("#single").html("<input type='text' class='txt' name='borrow[chanceCode]' id='chanceCode' >"
                + "<input type='hidden' class='txt' name='borrow[chanceId]' id='chanceId'>");
            if (chanceCode != '') {
                $("#chanceCode").val(chanceCode);
            }
            if (chanceId != '') {
                $("#chanceId").val(chanceId);
            }
            $("#SingleTypeT").val("chance");
            $("#chanceCode").yxcombogrid_chance({
                nameCol: 'chanceCode',
                hiddenId: 'chanceId',
                isShowButton: false,
                gridOptions: {
                    param: {'isTemp': '0', 'prinvipalId': $("#createId").val(), 'status': '5'},
                    showcheckbox: false,
                    event: {
                        'row_dblclick': function (e, row, data) {
                            $("#customerName").val(data.customerName);
                            $("#customerId").val(data.customerId);
                            $("#areaCode").val(data.areaCode);
                            $("#areaName").val(data.areaName);
//							getAreaMoneyByCustomer(data.customerId,data.customerName);
                            // ��ȡ�ӱ�����
                            $.get('index1.php', {
                                model: 'projectmanagent_borrow_borrow',
                                action: 'ajaxSingle',
                                id: data.id,
                                type: 'chance',
                                dataType: "html"
                            }, function (pro) {
                                if (pro) {
                                    $("#invbody").html(pro);
                                    var rowNums = $("tr[id^='equTab_']").length;
                                    $("#productNumber").val(rowNums);
                                    recount("invbody");
                                } else {
                                }
                            })
                        }
                    }
                }
            });
            break;
        case "��ͬ" :
            $("#chanceCode").yxcombogrid_chance('remove');
            $("#single")
                .html("<input type='text' class='txt' name='borrow[contractNum]' id='contractNum'>"
                    + "<input type='hidden' class='txt' name='borrow[contractName]' id='contractName'>"
                    + "<input type='hidden' class='txt' name='borrow[contractId]' id='contractId'>"
                    + "<input type='hidden' class='txt' name='borrow[contractType]' id='contractType'>");
            $("#SingleTypeT").val("order");
            $("#contractNum").yxcombogrid_allcontract({
                hiddenId: 'id',
                searchName: 'contractCode',
                isShowButton: false,
                gridOptions: {
                    param: {'isTemp': '0', 'prinvipalId': $("#createId").val(), 'states': '2,4'},
                    showcheckbox: false,
                    event: {
                        'row_dblclick': function (e, row, data) {
                            $("#contractNum").val(data.contractCode);
                            $("#contractName").val(data.contractName);
                            $("#contractId").val(data.id);
                            $("#customerName").val(data.customerName);
                            $("#customerId").val(data.customerId);
//							getAreaMoneyByCustomer(data.customerId,data.customerName);
                            $("#contractType").val(data.contractType);
//							// ��ȡ�ӱ�����
//							$.get('index1.php', {
//										model : 'projectmanagent_borrow_borrow',
//										action : 'ajaxSingle',
//										id : data.orgid,
//										type : 'order',
//										orderType : data.tablename,
//										dataType : "html"
//									}, function(pro) {
//										if (pro) {
//											$("#invbody").html(pro);
//											var rowNums = $("tr[id^='equTab_']").length;
//											$("#productNumber").val(rowNums);
//											recount("invbody");
//										} else {
//										}
//									})
                        }
                    }
                }
            });
            break;
    }

}
/**
 * license
 */
function License(licenseId) {
    var licenseVal = $("#" + licenseId).val();
    if (licenseVal == "") {
        // ���Ϊ��,�򲻴�ֵ
        showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
            + '&focusId='
            + licenseId
            + '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
    } else {
        // ��Ϊ����ֵ
        showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
            + '&focusId='
            + licenseId
            + '&licenseId='
            + licenseVal
            + '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
    }
}

// ��дid
function setLicenseId(licenseId, thisVal) {
    $('#' + licenseId).val(thisVal);
}

// ������
function countAll() {
    var invnumber = $('#productNumber').val();
    var incomeMoney = $('#money').val();
    var thisAmount = 0;
    var allAmount = 0;
    for (var i = 1; i <= invnumber; i++) {
        thisAmount = $('#money' + i).val() * 1;
        if (!isNaN(thisAmount)) {
            allAmount += thisAmount;
        }
    }

    $('#orderMoney').val(allAmount);

}
$(function () {
    // ��֯������Աѡ��
    $("#salesName").yxselect_user({
        hiddenId: 'salesNameId'
    });

    $("#scienceName").yxselect_user({
        hiddenId: 'scienceNameId'
    });
});

// ѡ��ʡ��
$(function () {

    $("#customerProvince").yxcombogrid_province({
        hiddenId: 'customerProvinceId',
        gridOptions: {
            showcheckbox: false
        }
    });
});

// ѡ��ͻ�
// $(function() {
//
// $("#customerName").yxcombogrid_customer({
// hiddenId : 'customerId',
// gridOptions : {
// showcheckbox : false
// }
// });
// });
$(function () {
    // �ͻ�����
//			customerTypeArr = getData('KHLX');
//			addDataToSelect(customerTypeArr, 'customerType');
//			addDataToSelect(customerTypeArr, 'customerListTypeArr1');
    // ��Ʊ����
    invoiceTypeArr = getData('FPLX');
    addDataToSelect(invoiceTypeArr, 'invoiceType');
    addDataToSelect(invoiceTypeArr, 'invoiceListType1');

});

function reloadCombo() {
    // alert( $("#customerLinkman").yxcombogrid('grid').param );
    $("#customerLinkman").yxcombogrid('grid').reload;
}
// �ͻ���ϵ��
function reloadCombo() {
    // alert( $("#customerLinkman").yxcombogrid('grid').param );
    $("#customerLinkman").yxcombogrid('grid').reload;

}

$(function () {
    $("#provincecity").yxcombogrid_province({
        hiddenId: 'provinceId',
        gridOptions: {
            showcheckbox: false
        }
    });
});


$(function () {
    $("#customerName").yxcombogrid_customer({
        hiddenId: 'customerId',
        isShowButton: false,
        gridOptions: {
            showcheckbox: false,
            // param :{"contid":$('#contractId').val()},
            event: {
                'row_dblclick': function (e, row, data) {
                    var getGrid = function () {
                        return $("#customerLinkman")
                            .yxcombogrid_linkman("getGrid");
                    }
                    var getGridOptions = function () {
                        return $("#customerLinkman")
                            .yxcombogrid_linkman("getGridOptions");
                    }
                    if (getGrid().reload) {
                        getGridOptions().param = {
                            customerId: data.id
                        };
                        getGrid().reload();
                    } else {
                        getGridOptions().param = {
                            customerId: data.id
                        }
                    }

                    $("#customerType").val(data.TypeOne);
                    $("#customerProvince").val(data.Prov);
                    $("#customerId").val(data.id);
                    $("#district").val(data.Prov);
                    // $("#customerLinkman").yxcombogrid('grid').param={}
                    // $("#customerLinkman").yxcombogrid('grid').reload;
                }
            }
        }
    });
    // customerId = $("#customerId").val()
    // $("#customerId").val(customerId)
    $("#customerLinkman").yxcombogrid_linkman({
        hiddenId: 'customerLinkmanId',
        gridOptions: {
            reload: true,
            showcheckbox: false,
            // param : param,
            event: {
                'row_dblclick': function (e, row, data) {
                    // alert( $('#customerId').val() );
                    // unset($('#customerId'));
                    $("#customerName").val(data.customerName);
                    $("#customerId").val(data.customerId);
                    $("#customerTel").val(data.mobile);
                    $("#customerEmail").val(data.email);
                }
            }
        }
    });

});

/**
 *
 * @param {}
 *            mycount ��Ⱦ��ϵ�������б�
 *
 */
function reloadLinkman(linkman) {
    var getGrid = function () {
        return $("#" + linkman).yxcombogrid_linkman("getGrid");
    }
    var getGridOptions = function () {
        return $("#" + linkman).yxcombogrid_linkman("getGridOptions");
    }
    if (!$('#customerId').val()) {
    } else {
        if (getGrid().reload) {
            getGridOptions().param = {
                customerId: $('#customerId').val()
            };
            getGrid().reload();
        } else {
            getGridOptions().param = {
                customerId: $('#customerId').val()
            }
        }
    }
}

/** ******************************************************************************************** */

$(function () {
    temp = $('#productNumber').val();
    for (var i = 1; i <= temp; i++) {
        $("#productNo" + i).yxcombogrid_product({
            hiddenId: 'productId' + i,
            gridOptions: {
                showcheckbox: false,
                event: {
                    'row_dblclick': function (i) {
                        return function (e, row, data) {
                            myedit(document.getElementById("Del" + i),
                                "invbody");
                            $("#productName" + i).val(data.productName);
                            $("#productModel" + i).val(data.pattern);
                            $("#unitName" + i).val(data.unitName);
                            $("#warrantyPeriod" + i).val(data.warranty);
                            $("#number" + i).val(1);
                            $("#price" + i).val(data.priCost);
                            $("#price" + i + "_v").val(data.priCost);
                            $("#money" + i).val(data.priCost);
                            $("#money" + i + "_v").val(data.priCost);
                            var encrypt = data.encrypt;
                            if (encrypt == 'on') {
                                alert("�����������ü��ܱ������ԣ�����д��������");
                                License('licenseId' + i);
                            }
                            var allocation = data.allocation;
                            $("#isCon" + i).val("isCon_" + i);
                            $.get('index1.php', {
                                model: 'projectmanagent_borrow_borrow',
                                action: 'ajaxorder',
                                id: data.id,
                                trId: "isCon_" + i,
                                Num: $("#productNumber").val(),
                                dataType: "html"
                            }, function (pro) {
                                if (pro) {
                                    $("#equTab_" + i).after(pro);
                                    var rowNums = $("tr[name^='equTab_']").length
                                        * 1
                                        + $("tr[id^='equTab_']").length
                                        * 1;
                                    document
                                        .getElementById("productNumber").value = document
                                        .getElementById("productNumber").value
                                        * 1 + 1;
                                    recount("invbody");
                                } else {
                                }
                            })
                        }
                    }(i)
                }
            }
        });
    }
});

$(function () {
    temp = $('#productNumber').val();
    for (var i = 1; i <= temp; i++) {
        $("#productName" + i).yxcombogrid_productName({
            hiddenId: 'productId' + i,
            gridOptions: {
                showcheckbox: false,
                event: {
                    'row_dblclick': function (i) {
                        return function (e, row, data) {
                            myedit(document.getElementById("Del" + i),
                                "invbody");
                            $("#productNo" + i).val(data.productCode);
                            $("#productModel" + i).val(data.pattern);
                            $("#unitName" + i).val(data.unitName);
                            $("#warrantyPeriod" + i).val(data.warranty);
                            $("#number" + i).val(1);
                            $("#price" + i).val(data.priCost);
                            $("#price" + i + "_v").val(data.priCost);
                            $("#money" + i).val(data.priCost);
                            $("#money" + i + "_v").val(data.priCost);
                            var encrypt = data.encrypt;
                            if (encrypt == 'on') {
                                alert("�����������ü��ܱ������ԣ�����д��������");
                                License('licenseId' + i);
                            }
                            var allocation = data.allocation;
                            $("#isCon" + i).val("isCon_" + i);
                            $.get('index1.php', {
                                model: 'projectmanagent_borrow_borrow',
                                action: 'ajaxorder',
                                id: data.id,
                                trId: "isCon_" + i,
                                Num: $("#productNumber").val(),
                                dataType: "html"
                            }, function (pro) {
                                if (pro) {
                                    $("#equTab_" + i).after(pro);
                                    var rowNums = $("tr[name^='equTab_']").length
                                        * 1
                                        + $("tr[id^='equTab_']").length
                                        * 1;
                                    document
                                        .getElementById("productNumber").value = document
                                        .getElementById("productNumber").value
                                        * 1 + 1;
                                    recount("invbody");
                                } else {
                                }
                            })
                        }
                    }(i)
                }
            }
        });
    }
});
/** ********************��Ʒ��Ϣ************************ */
function dynamic_add(packinglist, countNumP) {
    deliveryDate = $('#deliveryDate').val();
    // ��ȡ��ǰ���� ,���������
    var rowNums = $("tr[name^='equTab_']").length * 1
        + $("tr[id^='equTab_']").length * 1;
    // ��ȡ��ǰ����ֵ,������id����
    mycount = $('#' + countNumP).val() * 50 + 1;
    // ���������
    var packinglist = document.getElementById(packinglist);
    // ������
    oTR = packinglist.insertRow([rowNums]);
    oTR.id = "equTab_" + mycount;
    // ��ǰ�к�
    j = rowNums + 1;

    var oTL0 = oTR.insertCell([0]);
    oTL0.innerHTML = j;
    var oTL1 = oTR.insertCell([1]);
    oTL1.innerHTML = "<input type='text' id='productNo" + mycount
        + "' class='txtmiddle' name='borrow[borrowequ][" + mycount
        + "][productNo]' >";
    // ��ѡ��Ʒ
    $("#productNo" + mycount).yxcombogrid_product({
        hiddenId: 'productId' + mycount,
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (mycount) {
                    return function (e, row, data) {
                        myedit(document.getElementById("Del" + mycount),
                            "invbody");
                        $("#productName" + mycount).val(data.productName);
                        $("#productModel" + mycount).val(data.pattern);
                        $("#unitName" + mycount).val(data.unitName);
                        $("#warrantyPeriod" + mycount).val(data.warranty);
                        $("#number" + mycount + "_v").val(1);
                        $("#number" + mycount).val(1);
                        $("#price" + mycount).val(data.priCost);
                        $("#price" + mycount + "_v").val(data.priCost);
                        $("#money" + mycount).val(data.priCost);
                        $("#money" + mycount + "_v").val(data.priCost);
                        var encrypt = data.encrypt;
                        if (encrypt == 'on') {
                            alert("�����������ü��ܱ������ԣ�����д��������");
                            License('licenseId' + mycount);
                        }
                        var allocation = data.allocation;
                        $("#isCon" + mycount).val("isCon_" + mycount);
                        $.get('index1.php', {
                            model: 'projectmanagent_borrow_borrow',
                            action: 'ajaxorder',
                            id: data.id,
                            trId: "isCon_" + mycount,
                            Num: mycount,
                            dataType: "html"
                        }, function (pro) {
                            if (pro) {
                                $("#equTab_" + mycount).after(pro);
                                var rowNums = $("tr[name^='equTab_']").length
                                    * 1
                                    + $("tr[id^='equTab_']").length
                                    * 1;
                                document.getElementById(countNumP).value = document
                                    .getElementById(countNumP).value
                                    * 1 + 1;
                                recount("invbody");
                            } else {
                            }
                        })
                    };
                }(mycount)
            }
        }
    });
    var oTL2 = oTR.insertCell([2]);
    oTL2.innerHTML = "<input type='hidden' id='productId" + mycount
        + "'  name='borrow[borrowequ][" + mycount + "][productId]'/>"
        + "<input id='productName" + mycount
        + "' type='text' class='txt' name='borrow[borrowequ][" + mycount
        + "][productName]' />";
    $("#productName" + mycount).yxcombogrid_productName({
        hiddenId: 'productId' + mycount,
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (mycount) {
                    return function (e, row, data) {
                        myedit(document.getElementById("Del" + mycount),
                            "invbody");
                        $("#productNo" + mycount).val(data.productCode);
                        $("#productModel" + mycount).val(data.pattern);
                        $("#unitName" + mycount).val(data.unitName);
                        $("#warrantyPeriod" + mycount).val(data.warranty);
                        $("#number" + mycount + "_v").val(1);
                        $("#number" + mycount).val(1);
                        $("#price" + mycount).val(data.priCost);
                        $("#price" + mycount + "_v").val(data.priCost);
                        $("#money" + mycount).val(data.priCost);
                        $("#money" + mycount + "_v").val(data.priCost);
                        var encrypt = data.encrypt;
                        if (encrypt == 'on') {
                            alert("�����������ü��ܱ������ԣ�����д��������");
                            License('licenseId' + mycount);
                        }
                        var allocation = data.allocation;
                        $("#isCon" + mycount).val("isCon_" + mycount);
                        $.get('index1.php', {
                            model: 'projectmanagent_borrow_borrow',
                            action: 'ajaxorder',
                            id: data.id,
                            trId: "isCon_" + mycount,
                            Num: mycount,
                            dataType: "html"
                        }, function (pro) {
                            if (pro) {
                                $("#equTab_" + mycount).after(pro);
                                var rowNums = $("tr[name^='equTab_']").length
                                    * 1
                                    + $("tr[id^='equTab_']").length
                                    * 1;
                                document.getElementById(countNumP).value = document
                                    .getElementById(countNumP).value
                                    * 1 + 1;
                                recount("invbody");
                            } else {
                            }
                        })
                    };
                }(mycount)
            }
        }
    });
    var oTL3 = oTR.insertCell([3]);
    oTL3.innerHTML = "<input id='productModel" + mycount
        + "' type='text' class='txtmiddle' name='borrow[borrowequ]["
        + mycount + "][productModel]' readonly>";
    var oTL4 = oTR.insertCell([4]);
    oTL4.innerHTML = "<input class='txtshort' type='text' name='borrow[borrowequ]["
        + mycount + "][number]' id='number" + mycount + "'  />";
    var oTL5 = oTR.insertCell([5]);
    oTL5.innerHTML = "<input class='txtshort' type='text' name='borrow[borrowequ]["
        + mycount + "][unitName]' id='unitName" + mycount + "' >"
    var oTL6 = oTR.insertCell([6]);
    oTL6.innerHTML = "<input class='readOnlyTxtShort' readonly='readonly' type='text' name='borrow[borrowequ]["
        + mycount + "][price]' id='price" + mycount + "' />";
    var oTL7 = oTR.insertCell([7]);
    oTL7.innerHTML = "<input class='txtshort' type='text' name='borrow[borrowequ]["
        + mycount + "][money]' id='money" + mycount + "' />";
    var oTL8 = oTR.insertCell([8]);
    oTL8.innerHTML = "<input type='text' class='txtshort' name='borrow[borrowequ]["
        + mycount
        + "][warrantyPeriod]' id='warrantyPeriod"
        + mycount
        + "'>";
    var oTL9 = oTR.insertCell([9]);
    oTL9.innerHTML = "<input type='hidden' id='licenseId"
        + mycount
        + "' name='borrow[borrowequ]["
        + mycount
        + "][License]'/>"
        + "<input type='button' name='' class='txt_btn_a' value='����' onclick='License(\"licenseId"
        + mycount + "\");'>"
        + "<input type='hidden' name='borrow[borrowequ][" + mycount
        + "][isCon]' id='isCon" + mycount + "'>"
        + "<input type='hidden' name='borrow[borrowequ][" + mycount
        + "][isConfig]' id='isConfig" + mycount + "'>";
    var oTL10 = oTR.insertCell([10]);
    oTL10.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
        + packinglist.id + "\")' title='ɾ����' id='Del" + mycount + "'>";

    document.getElementById(countNumP).value = document
        .getElementById(countNumP).value
        * 1 + 1;
    // ǧ��λ������
    createFormatOnClick('number' + mycount, 'number' + mycount, 'price'
        + mycount, 'money' + mycount);
    createFormatOnClick('price' + mycount, 'number' + mycount, 'price'
        + mycount, 'money' + mycount);
    createFormatOnClick('money' + mycount, 'number' + mycount, 'price'
        + mycount, 'money' + mycount);

}

/** ***********************��ѵ�ƻ�********************************** */

function train_add(mytra, countNum) {
    mycount = document.getElementById(countNum).value * 1 + 1;
    var mytra = document.getElementById(mytra);
    i = mytra.rows.length;
    oTR = mytra.insertRow([i]);
    oTR.className = "TableData";
    oTR.align = "center";
    oTR.height = "30px";
    oTL0 = oTR.insertCell([0]);
    oTL0.innerHTML = i;
    oTL1 = oTR.insertCell([1]);
    oTL1.innerHTML = "<input class='txtshort' type='text' name='borrow[trainingplan]["
        + mycount
        + "][beginDT]' id='TraDT"
        + mycount
        + "' size='10' onfocus='WdatePicker()'>";
    oTL2 = oTR.insertCell([2]);
    oTL2.innerHTML = "<input class='txtshort' type='text' name='borrow[trainingplan]["
        + mycount
        + "][endDT]' id='TraEndDT"
        + mycount
        + "' size='10' onfocus='WdatePicker()'>";
    oTL3 = oTR.insertCell([3]);
    oTL3.innerHTML = "<input class='txtshort' type='text' name='borrow[trainingplan]["
        + mycount + "][traNum]' value='' size='8' maxlength='40'/>";
    oTL4 = oTR.insertCell([4]);
    oTL4.innerHTML = "<textarea name='borrow[trainingplan][" + mycount
        + "][adress]' rows='3' cols='15' style='width: 100%'></textarea>";
    oTL5 = oTR.insertCell([5]);
    oTL5.innerHTML = "<textarea name='borrow[trainingplan][" + mycount
        + "][content]' rows='3' style='width: 100%'></textarea>";
    oTL6 = oTR.insertCell([6]);
    oTL6.innerHTML = "<textarea name='borrow[trainingplan][" + mycount
        + "][trainer]' rows='3' style='width: 100%'></textarea>";
    oTL7 = oTR.insertCell([7]);
    oTL7.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
        + mytra.id + "\")' title='ɾ����'>";
    document.getElementById(countNum).value = document.getElementById(countNum).value
        * 1 + 1;
}

/** ********************ɾ����̬��************************ */
function myedit(obj, mytable) {
    var rowSize = $("#" + mytable).children().length;
    var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 2;
    var mytable = document.getElementById(mytable);
    var objA = obj.parentNode.parentNode;
    if ($(objA).find("input[id^='isConfig']").val() == '') {
        $("tr[parentRowId=" + $(objA).find("input[id^='isCon']").val() + "]")
            .remove();
    }
    var myrows = rowSize - 1;
    for (i = 0; i < myrows; i++) {
        // mytable.rows[i].childNodes[0].innerHTML = i + 1;
    }

}
function mydel(obj, mytable) {
    if (confirm('ȷ��Ҫɾ�����У�')) {
        var rowSize = $("#" + mytable).children().length;
        var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 2;
        var mytable = document.getElementById(mytable);
        var objA = obj.parentNode.parentNode;
        if ($(objA).find("input[id^='isConfig']").val() == '') {
            $("tr[parentRowId=" + $(objA).find("input[id^='isCon']").val()
                + "]").remove();
        }
        mytable.deleteRow(rowNo);
        var myrows = rowSize - 1;
        for (i = 0; i < myrows; i++) {
            mytable.rows[i].childNodes[0].innerHTML = i + 1;
        }

    }
}

/**
 * ���¼��������
 *
 * @param {}
 *            name
 */
function recount(mytable) {
    var mytable = document.getElementById(mytable);

    var myrows = mytable.rows.length;
    for (i = 0; i < myrows; i++) {
        mytable.rows[i].childNodes[0].innerHTML = i + 1;
    }
}

/** *****************���ؼƻ�******************************* */
function dis(name) {
    var temp = document.getElementById(name);
    if (temp.style.display == '')
        temp.style.display = "none";
    else if (temp.style.display == "none")
        temp.style.display = '';
}

//���ò�Ʒ��
function initExeDept(data, g) {
	if (data) {
		for (var i = 0; i < data.length; i++) {
			initExeDeptByRow(g, i);
		}
	}
}

// ���ò�Ʒ��- ��
function initExeDeptByRow(g, i) {
	// ��Ʒ��
	var exeDeptCode = g.getCmpByRowAndCol(i, 'newExeDeptCode').val();
	if (exeDeptCode != "") {
		var exeDeptName = g.getCmpByRowAndCol(i, 'newExeDeptName').val();
		var newProLineName = g.getCmpByRowAndCol(i, 'newProLineName').val();
		var exeDeptCodeArr = exeDeptCode.split(',');
		var exeDeptNameArr = exeDeptName.split(',');
		var optionStr = "";

		for (var j = 0; j < exeDeptCodeArr.length; j++) {
			if (newProLineName == exeDeptNameArr[j] || exeDeptCodeArr.length == 1) {
				optionStr += "<option value='" + exeDeptCodeArr[j] + "' selected='selected'>" +
				exeDeptNameArr[j] + "</option>";
			} else {
				optionStr += "<option value='" + exeDeptCodeArr[j] + "'>" +
				exeDeptNameArr[j] + "</option>";
			}
		}
		g.getCmpByRowAndCol(i, 'newProLineCode').append(optionStr);
	}
}