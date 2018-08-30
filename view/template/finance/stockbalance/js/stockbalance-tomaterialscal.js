/**
 * 选择核算对象
 * @param {} thisVal
 */
function showInput(thisVal) {
    $("#checkType").val(thisVal);
    $("#productNo").yxcombogrid_product("remove").attr("disabled", "disabled").val("");
    $("#productId").val("");
    $("#productNoBegin").yxcombogrid_product("remove").attr("disabled", "disabled").val("");
    $("#productIdBegin").val("");
    $("#productNoEnd").yxcombogrid_product("remove").attr("disabled", "disabled").val("");
    $("#productIdEnd").val("");
    if (thisVal == 2) {
        $("#productNo").attr("disabled", "").yxcombogrid_product({
            hiddenId: 'productId',
            nameCol: 'productCode',
            height: 300,
            width: 720,
            gridOptions: {
                param: {"properties": "WLSXWG"},
                showcheckbox: false
            }
        });
    } else if (thisVal == 3) {
        $("#productNoBegin").attr("disabled", "").yxcombogrid_product({
            hiddenId: 'productIdBegin',
            nameCol: 'productCode',
            height: 300,
            width: 720,
            gridOptions: {
                param: {"properties": "WLSXWG"},
                showcheckbox: false
            }
        });
        $("#productNoEnd").attr("disabled", "").yxcombogrid_product({
            hiddenId: 'productIdEnd',
            nameCol: 'productCode',
            height: 300,
            width: 720,
            gridOptions: {
                param: {"properties": "WLSXWG"},
                showcheckbox: false
            }
        });
    }
}

/**
 * 核算处理
 * @returns {boolean}
 */
function materialsCal() {
    var checkType = $("#checkType").val();
    if (checkType == 2) {
        if ($("#productId").val() == "") {
            alert('指定物料编码时，请选择对应物料编码');
            return false;
        }
    }
    if (checkType == 3) {
        if ($("#productIdBegin").val() == "") {
            alert('指定物料编码范围时，请选择对应物料编码');
            return false;
        }
        if ($("#productIdEnd").val() == "") {
            alert('指定物料编码范围时，请选择对应物料编码');
            return false;
        }
    }
    if (confirm("确定要进行核算吗?")) {
        $("#loading").show();
        $.ajax({
            type: "POST",
            url: "?model=finance_stockbalance_stockbalance&action=materialsCal",
            data: {
                "thisYear": $("#thisYear").val(),
                "thisMonth": $("#thisMonth").val(),
                "productId": $("#productId").val(),
                "productNo": $("#productNo").val(),
                "productNoBegin": $("#productNoBegin").val(),
                "productNoEnd": $("#productNoEnd").val(),
                "checkType": checkType
            },
            success: function (msg) {
                if (msg == 1) {
                    alert('核算成功！');
                    $("#loading").hide();
                    $("#toResult").show();
                } else {
                    alert("核算失败! ");
                    $("#loading").hide();
                }
            }
        });
    }
}

/**
 * 核算明细
 */
function calDetail() {
    var url = '?model=finance_stockbalance_stockbalance&action=calResultList&thisYear='
            + $("#thisYear").val() + '&thisMonth=' + $("#thisMonth").val()
            + '&properties=WLSXWG' + '&checkType=' + $("#checkType").val()
            + '&productId=' + $("#productId").val()
            + '&productNoBegin=' + $("#productNoBegin").val()
            + '&productNoEnd=' + $("#productNoEnd").val()
        ;
    showOpenWin(url);
}


/**
 * 更新合同、赠送物料成本
 * @returns {boolean}
 */
function materialsCostAct() {
    var checkType = $("#checkType").val();
    if (checkType == 2) {
        if ($("#productId").val() == "") {
            alert('指定物料编码时，请选择对应物料编码');
            return false;
        }
    }
    if (checkType == 3) {
        if ($("#productIdBegin").val() == "") {
            alert('指定物料编码范围时，请选择对应物料编码');
            return false;
        }
        if ($("#productIdEnd").val() == "") {
            alert('指定物料编码范围时，请选择对应物料编码');
            return false;
        }
    }
    if (confirm("确定要进行更新吗?")) {
        $("#loading").show();
        $.ajax({
            type: "POST",
            url: "?model=finance_stockbalance_stockbalance&action=materialsCostAct",
            data: {
                "thisYear": $("#thisYear").val(),
                "thisMonth": $("#thisMonth").val(),
                "productId": $("#productId").val(),
                "productNo": $("#productNo").val(),
                "productNoBegin": $("#productNoBegin").val(),
                "productNoEnd": $("#productNoEnd").val(),
                "checkType": checkType
            },
            success: function (msg) {
                if (msg == 1) {
                    alert('更新成功！');
                    $("#loading").hide();
                } else {
                    alert("更新失败! ");
                    $("#loading").hide();
                }
            }
        });
    }
}

/**
 * 更新物料转资产物料单价
 * @returns {boolean}
 */
function updateProductAssetPrice() {
    var checkType = $("#checkType").val();
    if (checkType == 2) {
        if ($("#productId").val() == "") {
            alert('指定物料编码时，请选择对应物料编码');
            return false;
        }
    }
    if (checkType == 3) {
        if ($("#productIdBegin").val() == "") {
            alert('指定物料编码范围时，请选择对应物料编码');
            return false;
        }
        if ($("#productIdEnd").val() == "") {
            alert('指定物料编码范围时，请选择对应物料编码');
            return false;
        }
    }
    if (confirm("确定要进行更新吗?")) {
        $("#loading").show();
        $.ajax({
            type: "POST",
            url: "?model=finance_stockbalance_stockbalance&action=updateProductAssetPrice",
            data: {
                "thisYear": $("#thisYear").val(),
                "thisMonth": $("#thisMonth").val(),
                "productId": $("#productId").val(),
                "productNo": $("#productNo").val(),
                "productNoBegin": $("#productNoBegin").val(),
                "productNoEnd": $("#productNoEnd").val(),
                "checkType": checkType
            },
            success: function (msg) {
                if (msg == 1) {
                    alert('更新成功！');
                    $("#loading").hide();
                } else {
                    alert("更新失败! ");
                    $("#loading").hide();
                }
            }
        });
    }
}