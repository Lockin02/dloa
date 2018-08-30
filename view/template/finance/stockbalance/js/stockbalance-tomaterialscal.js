/**
 * ѡ��������
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
 * ���㴦��
 * @returns {boolean}
 */
function materialsCal() {
    var checkType = $("#checkType").val();
    if (checkType == 2) {
        if ($("#productId").val() == "") {
            alert('ָ�����ϱ���ʱ����ѡ���Ӧ���ϱ���');
            return false;
        }
    }
    if (checkType == 3) {
        if ($("#productIdBegin").val() == "") {
            alert('ָ�����ϱ��뷶Χʱ����ѡ���Ӧ���ϱ���');
            return false;
        }
        if ($("#productIdEnd").val() == "") {
            alert('ָ�����ϱ��뷶Χʱ����ѡ���Ӧ���ϱ���');
            return false;
        }
    }
    if (confirm("ȷ��Ҫ���к�����?")) {
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
                    alert('����ɹ���');
                    $("#loading").hide();
                    $("#toResult").show();
                } else {
                    alert("����ʧ��! ");
                    $("#loading").hide();
                }
            }
        });
    }
}

/**
 * ������ϸ
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
 * ���º�ͬ���������ϳɱ�
 * @returns {boolean}
 */
function materialsCostAct() {
    var checkType = $("#checkType").val();
    if (checkType == 2) {
        if ($("#productId").val() == "") {
            alert('ָ�����ϱ���ʱ����ѡ���Ӧ���ϱ���');
            return false;
        }
    }
    if (checkType == 3) {
        if ($("#productIdBegin").val() == "") {
            alert('ָ�����ϱ��뷶Χʱ����ѡ���Ӧ���ϱ���');
            return false;
        }
        if ($("#productIdEnd").val() == "") {
            alert('ָ�����ϱ��뷶Χʱ����ѡ���Ӧ���ϱ���');
            return false;
        }
    }
    if (confirm("ȷ��Ҫ���и�����?")) {
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
                    alert('���³ɹ���');
                    $("#loading").hide();
                } else {
                    alert("����ʧ��! ");
                    $("#loading").hide();
                }
            }
        });
    }
}

/**
 * ��������ת�ʲ����ϵ���
 * @returns {boolean}
 */
function updateProductAssetPrice() {
    var checkType = $("#checkType").val();
    if (checkType == 2) {
        if ($("#productId").val() == "") {
            alert('ָ�����ϱ���ʱ����ѡ���Ӧ���ϱ���');
            return false;
        }
    }
    if (checkType == 3) {
        if ($("#productIdBegin").val() == "") {
            alert('ָ�����ϱ��뷶Χʱ����ѡ���Ӧ���ϱ���');
            return false;
        }
        if ($("#productIdEnd").val() == "") {
            alert('ָ�����ϱ��뷶Χʱ����ѡ���Ӧ���ϱ���');
            return false;
        }
    }
    if (confirm("ȷ��Ҫ���и�����?")) {
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
                    alert('���³ɹ���');
                    $("#loading").hide();
                } else {
                    alert("����ʧ��! ");
                    $("#loading").hide();
                }
            }
        });
    }
}