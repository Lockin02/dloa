/**
 * 渲染物料清单收料仓库combogrid
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

//删除
function delItem(obj) {
    if (confirm('确定要删除该行？')) {
        var rowNo = obj.parentNode.parentNode.rowIndex - 2;
        $(obj).parent().parent().hide();
        $(obj).parent().append('<input type="hidden" name="stockin[items]['
        + rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
        + '" />');

        reloadItemCount();
    }
}

// 表单校验
function checkForm() {
    var itemscount = $("#itemscount").val();
    var deleteCount = 0;
    for (var i = 0; i < itemscount; i++) {
        if ($("#isDelTag" + i).val() == 1) {
            deleteCount = deleteCount + 1;
        }

    }
    if (deleteCount == itemscount) {
        alert("请新增物料信息...");
        return false;
    }
    if (itemscount < 1) {
        alert("请选择物料信息...");
        return false;
    } else {
        for (var i = 0; i < itemscount; i++) {
            if ($("#isDelTag" + i).val() != 1 && $("#productId" + i).length == 1) {
                if ($("#productId" + i).val() == "") {
                    alert("物料信息不能为空，请选择...");
                    return false;
                }
                if ($("#inStockId" + i).val() == ""
                    || parseInt($("#inStockId" + i).val()) == 0) {
                    alert("收料仓库不能为空，请选择...");
                    return false;
                }
                // 检查入库数量是否合法
                var actNum = $("#actNum" + i);
                if (!isNum(actNum.val())) {// 判断数量
                    alert("实收数量" + "<" + actNum.val() + ">" + "填写有误!");
                    actNum.focus();
                    return false;
                }
                if (actNum.val()*1 > $("#storageNum" + i).val()*1) {
                    alert("实收数量不能大于应收数量");
                    actNum.focus();
                    return false;
                }
            }
        }
    }
    return true;
}

//审核
function confirmAudit() {
	if (confirm("审核后单据将不可修改，你确定要审核吗?")) {
		if (checkForm()) {
			$("#form1").attr("action" ,"?model=stock_instock_stockin&action=add&actType=audit");
			$("#docStatus").val("YSH");
			$("#form1").submit();
		}
	}
}