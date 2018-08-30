$(function () {
    $("#purchaseProductTable").yxeditgrid({
        event: {
            removeRow: function (t, rowNum, rowData) {
                countAmount();
            }
        },
        isAdd: false,
        objName: 'apply[applyItem]',
        url: '?model=asset_require_requireitem&action=requireJsonApply',
        param: {
            mainId: $("#relDocId").val()
        },
        isAddOneRow: true,
        colModel: [{
            display: '物料Id',
            name: 'productId',
            type: 'hidden'
        }, {
            display: '物料编码',
            name: 'productCode',
            tclass: 'readOnlyTxtNormal',
            width: 120,
            readonly: true
        }, {
            display: '物料名称',
            name: 'productName',
            tclass: 'readOnlyTxtNormal',
            readonly: true
        }, {
            display: '资产名称',
            name: 'inputProductName',
            tclass: 'txt'
        }, {
            display: '资产描述',
            name: 'description',
            validation: {
                required: true
            },
            tclass: 'txt'
        }, {
            display: 'requireItemId',
            name: 'requireItemId',
            type: "hidden"
        }, {
            display: '物料类别',
            name: 'productCategoryCode',
            validation: {
                required: true
            },
            tclass: 'txtshort',
            type: 'select',
            datacode: 'CGWLLB',
            processData: function (data) {
                var newData = [{
                    dataName: '',
                    dataCode: ''
                }];
                for (var i = 0; i < data.length; i++) {
                    newData.push(data[i]);
                }
                return newData;
            }
        }, {
            display: '规格',
            name: 'pattem',
            validation: {
                required: true
            }
        }, {
            display: '申请数量',
            name: 'applyAmount',
            tclass: 'txtshort',
            validation: {
                custom: ['onlyNumber']
            },
            event: {
                blur: function (e) {
                    var rownum = $(this).data('rowNum');// 第几行
                    var grid = $(this).data('grid');// 表格组件

                    var applyAmount = grid.getCmpByRowAndCol(rownum, 'applyAmount').val();
                    var maxAmount = grid.getCmpByRowAndCol(rownum, 'maxAmount').val();

                    if ($(this).val() * 1 < 0) {
                        alert("数量不能小于0！");
                        $(this).val(maxAmount);
                    }

                    if ($(this).val() * 1 > maxAmount * 1) {
                        alert("数量不能大于剩余可申请数量");
                        $(this).val(maxAmount);
                    }
                }
            }
        }, {
            display: '最大数量',
            name: 'maxAmount',
            type: "hidden"
        }, {
            display: '预计金额',
            name: 'amounts',
            tclass: 'txtshort',
            type: 'money',
            // blur 失焦触发计算金额和数量的方法
            event: {
                blur: function () {
                    countAmount();
                }
            }
        }, {
            display: '单位',
            name: 'unitName',
            tclass: 'txtshort'
        }, {
            display: '希望交货日期',
            name: 'dateHope',
            type: 'date',
            tclass: 'txtshort',
            validation: {
                custom: ['date']
            }
        }, {
            display: '备注',
            name: 'remark',
            tclass: 'txt'
        }, {
            display: '询价金额',
            name: 'inquiryAmount',
            type: 'money',
            tclass: 'txtshort'
        }, {
            display: '行政意见',
            name: 'suggestion',
            type: 'textarea',
            cols: '40',
            rows: '3'
        }]
    });
//	// 选择人员组件
//	$("#userName").yxselect_user({
//		hiddenId : 'userId',
//		isGetDept : [true, "useDetId", "useDetName"]
//	});

    $("#applicantName").yxselect_user({
        hiddenId: 'applicantId',
        isGetDept: [true, "applyDetId", "applyDetName"]
    });

    // 根据采购种类来显示资产用途
    purchCategoryArr = getData('CGZL');
    addDataToSelect(purchCategoryArr, 'purchCategory');
    $('#purchCategory').change(function () {
        $('#assetUseCode').empty();
        assetUseArr = getData($('#purchCategory').val());
        addDataToSelect(assetUseArr, 'assetUseCode');
        if ($('#assetUseCode').val()) {
            $('#assetUse').val($('#assetUseCode').get(0).options[$('#assetUseCode').get(0).selectedIndex].innerText);
        }
    });

    // 根据采购类型来显示部分字段（计划编号、计划年度）
    $("#hiddenA").hide();
    $('#purchaseType').change(function () {
        if ($("#purchaseType").val() == "CGLX-JHN") {
            $("#hiddenA").show();
        } else {
            $('#planYear').val("");
            $("#hiddenA").hide();
        }
    });

    // 根据采购种类为“研发类”时来显示部分字段（采购分类、重大专项名称、募集资金项目、其它研发项目）
    $("#hiddenD").hide();
    $("#hiddenE").hide();
    $("#purchCategory").change(function () {
        if ($("#purchCategory").val() == "CGZL-YFL") {
            $('#assetUseCode').val("");
            $('#assetUse').val("");
            $("#hiddenC").hide();
            $("#hiddenD").show();
            $("#hiddenE").show();
        } else {
            $('#assetClass').val("");
            $('#importProject').val("");
            $('#moneyProject').val("");
            $('#otherProject').val("");
            $("#hiddenC").show();
            $("#hiddenD").hide();
            $("#hiddenE").hide();
        }
    });

    /**
     * 验证信息
     */
    validate({
        "userName": {
            required: true
        },
        "applicantName": {
            required: true
        },
        "applyTime": {
            custom: ['date']
        },
        "amounts_v": {
            required: true
        }
    });

    $("#purchaseViewTable").yxeditgrid({
        objName: 'apply[applyItem]',
        url: '?model=asset_purchase_apply_applyItem&action=preListJson',
        delTagName: 'isDelTag',
        type: 'view',
        param: {
            relDocId: $("#relDocId").val(),
            "isDel": '0'
        },
        colModel: [{
            display: '物料名称',
            name: 'inputProductName',
            tclass: 'readOnlyTxtItem'
        }, {
            display: '规格',
            name: 'pattem',
            tclass: 'readOnlyTxtItem'
        }, {
            display: '申请数量',
            name: 'applyAmount',
            tclass: 'txtshort'
        }, {
            display: '供应商',
            name: 'supplierName',
            tclass: 'txtmiddle'
        }, {
            display: '单位',
            name: 'unitName',
            tclass: 'readOnlyTxtItem'
        }, {
            display: '希望交货日期',
            name: 'dateHope',
            type: 'date'
        }, {
            display: '备注',
            name: 'remark',
            tclass: 'txt'
        }]
    });

    $("#itemTable").yxeditgrid({
        url: '?model=asset_require_requireitem&action=listByRequireJson',
        objName: 'requirement[items]',
        type: 'view',
        param: {
            mainId: $("#relDocId").val()
        },
        isAddOneRow: true,
        colModel: [{
            display: 'id',
            name: 'id',
            tclass: 'txtshort',
            type: 'hidden'
        }, {
            display: '设备描述',
            name: 'description',
            validation: {
                required: true
            },
            tclass: 'txt'
        }, {
            display: '数量',
            name: 'number',
            validation: {
                required: true,
                custom: ['onlyNumber']
            },
            tclass: 'txtshort'
        }, {
            display: '已发货数量',
            name: 'executedNum',
            validation: {
                required: true,
                custom: ['onlyNumber']
            },
            tclass: 'txtshort'
        }, {
            display: '预计金额',
            name: 'expectAmount',
            tclass: 'txtshort',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '预计交货日期',
            name: 'dateHope',
            type: 'date',
            validation: {
                required: true
            },
            tclass: 'txtshort'
        }, {
            display: '备注',
            name: 'remark',
            tclass: 'txt'
        }, {
            display: '询价金额',
            name: 'inquiryAmount',
            type: 'money',
            tclass: 'txtshort'
        }, {
            display: '行政意见',
            name: 'suggestion',
            type: 'textarea'
        }]
    })
});

/*
 * 审核确认
 */
function confirmAudit() {
    //部门是否有不同
//	var deptDiff = false;
//	var markDept;
//	$("select[id^='purchaseProductTable_cmp_purchDept']").each(function(i,n){
//		//如果不是删除数据，才处理
//		if($("#apply[applyItem]_"+ i +"_isDelTag").length == 0){
//			if(!markDept){
//				markDept = this.value;
//			}else if(markDept != this.value){
//				deptDiff = true;
//				return false;
//			}
//		}
//	});
//	//如果部门不同，则不能提交表单
//	if(deptDiff == true){
//		alert('单次下达采购申请必须保持采购部门一致');
//		return false;
//	}

    if (confirm("确定要提交吗?")) {
        var purchaseDept = $('#purchaseDept').val();
        var audit = (purchaseDept == '1') ? 'noaudit' : 'audit';
        $("#form1").attr("action", "?model=asset_purchase_apply_apply&action=add&actType=" + audit).submit();
    } else {
        return false;
    }
}
// 根据从表的残值动态计算应付总金额
function countAmount() {
    // 获取当前的行数即卡片的资产数
    var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurRowNum");
    var rowAmountVa = 0;
    var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "amounts");
    cmps.each(function () {
        rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
    });
    $("#amounts").val(rowAmountVa);
    $("#amounts_v").val(moneyFormat2(rowAmountVa));
    return true;
}
