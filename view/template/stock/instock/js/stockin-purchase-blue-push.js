/**
 * ��Ⱦ�����嵥���ϲֿ�combogrid
 */
function reloadItemStock() {
    var itemscount = $('#itemscount').val();
    for (var i = 0; i < itemscount; i++) {
        $("#inStockName" + i).yxcombogrid_stockinfo("remove");
        $("#inStockName" + i).yxcombogrid_stockinfo({
            hiddenId: 'inStockId' + i,
            nameCol: 'stockName',
            gridOptions: {
                showcheckbox: false,
                model: 'stock_stockinfo_stockinfo',
                action: 'pageJson',
                event: {
                    'row_dblclick': function (i) {
                        return function (e, row, data) {
                            $('#inStockCode' + i).val(data.stockCode);
                        }
                    }(i)
                }
            }
        });
    }
}

//ɾ��
function delItem(obj) {
    if (confirm('ȷ��Ҫɾ�����У�')) {
        var rowNo = obj.parentNode.parentNode.rowIndex - 2;
        $(obj).parent().parent().hide();
        $(obj).parent().append('<input type="hidden" name="stockin[items]['
        + rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
        + '" />');

        reloadItemCount();
    }
}

// ��У��
function checkForm() {
    var itemscount = $("#itemscount").val();
    var deleteCount = 0;
    for (var i = 0; i < itemscount; i++) {
        if ($("#isDelTag" + i).val() == 1) {
            deleteCount = deleteCount + 1;
        }

    }
    if (deleteCount == itemscount) {
        alert("������������Ϣ...");
        return false;
    }
    if (itemscount < 1) {
        alert("��ѡ��������Ϣ...");
        return false;
    } else {
        for (var i = 0; i < itemscount; i++) {
            if ($("#isDelTag" + i).val() != 1 && $("#productId" + i).length == 1) {
                if ($("#productId" + i).val() == "") {
                    alert("������Ϣ����Ϊ�գ���ѡ��...");
                    return false;
                }
                if ($("#inStockId" + i).val() == ""
                    || parseInt($("#inStockId" + i).val()) == 0) {
                    alert("���ϲֿⲻ��Ϊ�գ���ѡ��...");
                    return false;
                }
                // �����������Ƿ�Ϸ�
                var actNum = $("#actNum" + i);
                if (!isNum(actNum.val())) {// �ж�����
                    alert("ʵ������" + "<" + actNum.val() + ">" + "��д����!");
                    actNum.focus();
                    return false;
                }
                if (actNum.val()*1 > $("#storageNum" + i).val()*1) {
                    alert("ʵ���������ܴ���Ӧ������");
                    actNum.focus();
                    return false;
                }
            }
        }
    }
    return true;
}

//���
function confirmAudit() {
	if (confirm("��˺󵥾ݽ������޸ģ���ȷ��Ҫ�����?")) {
		if (checkForm()) {
			$("#form1").attr("action" ,"?model=stock_instock_stockin&action=add&actType=audit");
			$("#docStatus").val("YSH");
			$("#form1").submit();
		}
	}
}