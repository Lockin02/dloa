$(document).ready(function() {
    //邮件渲染
    $("#TO_NAME").yxselect_user({
        hiddenId: 'TO_ID',
        mode: 'check',
        formCode: 'qualityReport'
    });

    //绑定键盘回车
    $("form").live('keypress', function(e) {
        var p = e.which;
        if (p == 13) {
            return false;
        }
    });

    //渲染质检标准
    initQualitystandard("standardId");

    //检测标准
    dimensionArr = getDimension();
    //检测方式
    checkTypeArr = getCheckType();

    //实例化物料
    initEqu();

    validate({
        "docDate": {
            required: true
        },
        "checkNum": {
            required: true,
            custom: ['percentageNum']
        }
    });
    
    //源单类型为生产检验的，显示文档类型
    if($("#relDocType").val() == 'ZJSQYDSC'){
    	showDucumentType();
    	//隐藏质检方案，质检标准
    	$("#qualityPlanName").parents("tr:first").hide();
    	//显示计划单及合同编号
    	$("#relCodeTr").show();
    	//显示备注
    	$("#remark").parents("tr:first").show();
    	//显示指引文档
    	$("#guideDocTr").show();
    }else{
        //实例化质检内容
        initQualityInfo();
        //实例化质检方案
        $("#qualityPlanName").yxcombogrid_qualityprogram({
            hiddenId: 'qualityPlanId',
            gridOptions: {
                event: {
                    row_dblclick: function(e, row, data) {
                        $("#standardName").val(data.standardName);
                        $("#standardId").val(data.standardId);
                        standardFile(data.standardId);
                        //清空后重新加载质检模板内容
                        $("#itemTable").empty();
                        initQualityInfo(data.id);
                    }
                }
            }
        });
    }
});

//刷新
function show_page() {}

//检验数量
var checkNum = 0;

/**
 * 实例化质检物料
 * @return {Boolean}
 */
function initEqu() {
    //如果是换货或者借用归还类的质检报告，则生成不合格信息表
    var relDocType = $("#relDocType").val();
    var qualityType = relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH";//这里判断是否为归还换货检验
    var purchaserNameType = relDocType == 'ZJSQYDSC' ? 'hidden' : 'text';//源单类型为生产检验的，隐藏采购员字段
    $("#ereportequitem").yxeditgrid({
        objName: 'qualityereport[ereportequitem]',
        title: '物料信息及检验结果',
        isAdd: false,
        url: "?model=produce_quality_qualitytaskitem&action=listJsonForReport",
        param: {"mainId": $("#mainId").val(), 'checkStatusNull': '1'},
        event: {
            'reloadData': function() {
                //如果获取不到从表
                if ($("#ereportequitem").yxeditgrid("getCmpByCol", "supportNum").length == 0) {
                    alert('质检任务已经完成或已录入相关报告,不能继续录入质检报告');
                    window.close();
                }
                //计算所有数量
                countAll();
                //初始化邮件发送人
                initMailPerson();
                $("input[id^='ereportequitem_cmp_thisCheckNum']").trigger('blur');
            },
            "beforeRemoveRow" : function(e, rowNum, rowData, g){
                if(rowData.objType == 'ZJSQDLBF'){//呆料报废质检必须整单处理,不能删除物料
                    alert("呆料报废质检必须整单处理,不能删除物料");
                    g.isRemoveAction=false;
                    return false;
                }
            },
            "removeRow": function() {
                //计算所有数量
                countAll();
                //计算合格数
                countQualitedNum();
                //计算合格数
                countProduceNum();
                //如果是换货或者借用归还类的质检报告，则生成不合格信息表
                if (qualityType == true) {
                    //初始化不合格部分
                    autoFailureItem();
                }
            }
        },
        colModel: [{
            name: 'id',
            display: 'id',
            type: 'hidden'
        }, {
            name: 'relItemId',
            display: 'relItemId',
            type: 'hidden'
        }, {
            name: 'objId',
            display: 'objId',
            type: 'hidden'
        }, {
            name: 'objCode',
            display: 'objCode',
            type: 'hidden'
        }, {
            name: 'objType',
            display: 'objType',
            type: 'hidden'
        }, {
            name: 'objItemId',
            display: 'objItemId',
            type: 'hidden'
        }, {
            name: 'productId',
            display: 'productId',
            type: 'hidden'
        }, {
            name: 'productCode',
            tclass: 'readOnlyTxtMiddle',
            readonly: true,
            display: '物料编码',
            width: 80
        }, {
            name: 'productName',
            tclass: 'readOnlyTxtMiddle',
            readonly: true,
            display: '物料名称'
        }, {
            name: 'pattern',
            tclass: 'readOnlyTxtMiddle',
            readonly: true,
            display: '规格型号'
        }, {
            name: 'supplierName',
            tclass: 'readOnlyTxtMiddle',
            readonly: true,
            display: '供应商'
        }, {
            name: 'supplierId',
            display: 'supplierId',
            type: 'hidden'
        }, {
            name: 'supportNum',
            tclass: 'readOnlyTxtShort',
            readonly: true,
            display: '报检数量',
            width: 70
        }, {
            name: 'thisCheckNum',
            display: '本次质检数量',
            width: 70,
            tclass: qualityType == true ? 'readOnlyTxtShort' : '',
            readonly: qualityType
        },{
            name: 'samplingNum',
            display: '抽检数量',
            width: 70,
            tclass: qualityType == true ? 'readOnlyTxtShort' : '',
            readonly: qualityType,
            event: {
                blur: function(e) {
                    var g = e.data.gird;
                    var checkedNum = g.getCmpByCol("samplingNum");
                    var sum = 0;
                    checkedNum.each(function() {
                        sum = accAdd($(this).val(), sum);
                    });
                    $('#checkNum').val(sum);
                }
            }
        },  {
            name: 'unitName',
            tclass: 'readOnlyTxtShort',
            readonly: true,
            display: '单位',
            width: 70
        }, {
            name: 'supportTime',
            tclass: 'readOnlyTxtMiddle',
            readonly: true,
            display: '报检时间'
        }, {
            name: 'relDocId',
            readonly: true,
            display: '源单id',
            type: 'hidden'
        }, {
            name: 'relDocCode',
            tclass: 'readOnlyTxtShort',
            readonly: true,
            display: '源单号',
            width: 90
        }, {
            name: 'purchaserName',
            tclass: 'readOnlyTxtShort',
            readonly: true,
            display: '采购员',
            width: 90,
            type: purchaserNameType
        }, {
            name: 'purchaserId',
            display: '采购员帐号',
            type: 'hidden'
        }, {
            name: 'priority',
            type: 'select',
            display: '紧急程度',
            datacode: 'ZJJJCD',
            width: 70
        }, {
            name: 'qualitedNum',
            tclass: 'txtshort',
            display: '合格数',
            width: 70,
            validation: {
                required: true,
                custom: ['percentageNum']
            },
            event: {
                blur: function() {
                    //计算合格数
                    countQualitedNum();
                    //计算质检内容数量
                    countEquQualitedNum('qualitedNum', $(this).data("rowNum"));

                    //如果是换货或者借用归还类的质检报告，则生成不合格信息表
                    if (qualityType == true) {
                        //初始化不合格部分
                        initFailureItem($(this).data("rowNum"));
                    }
                }
            }
        }, {
            name: 'produceNum',
            tclass: 'txtshort produceNum',
            display: '不合格数',
            width: 70,
            validation: {
                required: true,
                custom: ['percentageNum']
            },
            event: {
                blur: function() {
                    //计算合格数
                    countProduceNum();
                    //计算质检内容数量
                    countEquQualitedNum('produceNum', $(this).data("rowNum"));

                    //如果是换货或者借用归还类的质检报告，则生成不合格信息表
                    if (qualityType == true) {
                        //初始化不合格部分
                        initFailureItem($(this).data("rowNum"));
                    }
                },
                dblclick: function(e) {
                    var rowData = e.data.rowData  //id
                    if (!isNaN(Number($(this).val()))) {
                        showThickboxWin("?model=produce_quality_serialno"
                        + "&productId=" + rowData.productId
                        + "&productCode=" + rowData.productCode
                        + "&productName=" + rowData.productName
                        + "&pattern=" + rowData.pattern
                        + "&relDocId=" + rowData.relItemId
                        + "&relDocType=qualityEreport"
                        + '&productNum=' + $(this).val()
                        + "&action=toDeal&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
                    }
                }
            }
        }, {
            name: 'remark',
            tclass: 'txt',
            display: '备注'
        }]
    });
}

//实例化质检内容
function initQualityInfo(programId) {
    //查询方案路径
    var url = "";
    //如果有传入质检方案id，则改变url
    if (programId) {
        url = "?model=produce_quality_quaprogramitem&action=listjson&mainId=" + programId;
    }
    //如果是换货或者借用归还类的质检报告，则生成不合格信息表
    var relDocType = $("#relDocType").val();
    var cType = relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH" ? 'hidden' : 'text';
    var needCheck = relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH" ? false : {
        required: true,
        custom: ['percentageNum']
    };

    $("#itemTable").yxeditgrid({
        objName: 'qualityereport[items]',
        isAddOneRow: true,
        title: '质检内容',
        url: url,
        colModel: [{
            name: 'dimensionId',
            display: 'dimensionId',
            type: 'hidden'
        }, {
            name: 'dimensionName',
            tclass: 'txtmiddle',
            display: '检验项目',
            validation: {
                required: true
            },
            type: 'select',
            options: dimensionArr,
            emptyOption: true,
            event: {
                change: function() {
                    //如果是换货或者借用归还类的质检报告，则生成不合格信息表
                    if (relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH") {
                        //初始化不合格部分
                        autoFailureItem();
                    }
                }
            }
        }, {
            name: 'examTypeId',
            display: 'examTypeId',
            type: 'hidden'
        }, {
            name: 'examTypeName',
            display: '检验方式',
            tclass: 'txtmiddle',
            type: 'select',
            options: checkTypeArr,
            emptyOption: true,
            validation: {
                required: true
            }
        }, {
            name: 'crNum',
            tclass: 'txtshort',
            display: 'CR',
            validation: needCheck,
            width: 90,
            event: {
                blur: function() {
                    //计算质检内容数量
                    countQuaInfoNum('CR', $(this).data("rowNum"));
                }
            },
            type: cType
        }, {
            name: 'maNum',
            tclass: 'txtshort',
            display: 'MA',
            validation: needCheck,
            width: 90,
            event: {
                blur: function() {
                    //计算质检内容数量
                    countQuaInfoNum('MA', $(this).data("rowNum"));
                }
            },
            type: cType
        }, {
            name: 'miNum',
            tclass: 'txtshort',
            display: 'MI',
            validation: needCheck,
            width: 90,
            event: {
                blur: function() {
                    //计算质检内容数量
                    countQuaInfoNum('MI', $(this).data("rowNum"));
                }
            },
            type: cType
        }, {
            name: 'qualitedNum',
            tclass: 'txtshort',
            display: '合格数',
            validation: needCheck,
            type: cType
        }, {
            name: 'itemNum',
            display: '实际检验数量',
            type: 'hidden'
        }, {
            name: 'remark',
            tclass: 'txt',
            display: '备注'
        }],
        event: {
            removeRow: function() {
                //如果是换货或者借用归还类的质检报告，则生成不合格信息表
                if (relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH") {
                    //初始化不合格部分
                    autoFailureItem();
                }
            }
        }
    });
}

//自动初始化
function autoFailureItem() {
    //直接清除
    $("#failureItem").yxeditgrid('remove');
    //初始化表格后添加不合格物料行
    $("#ereportequitem").yxeditgrid("getCmpByCol", "produceNum").each(function() {
        if ($(this).val() * 1 > 0) {
            initFailureItem($(this).data('rowNum'));
        }
    });
}

//初始化不合格部分
function initFailureItem(rowNum) {
    //当不合格列表没有内容的时候直接初始化
    if ($("#failureItem").html() == "") {
        //获取检验项目
        var dimensionNameArr = $("#itemTable").yxeditgrid("getCmpByCol", "dimensionName");
        //当包含检验项目并且物料信息不为空的时候,就初始化不合格列表
        if (dimensionNameArr.length > 0) {
            var baseTitle = initFailureItemGrid(dimensionNameArr);
            $("#failureItem").yxeditgrid({
                objName: 'qualityereport[failureitem]',
                isAddOneRow: false,
                isAddAndDel: false,
                title: '不合格物料信息',
                colModel: baseTitle
            });
        }
    }

    //初始化表格后添加不合格物料行
    var ereportequitemObj = $("#ereportequitem");
    var objItemId = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "objItemId").val();
    var failureItemObj = $("#failureItem");//不合格物料表
    //判断如果有相同设备的记录,清空
    var failureObjItemIdArr = failureItemObj.yxeditgrid("getCmpByCol", "objItemId");
    if (failureObjItemIdArr.length > 0) {
        failureObjItemIdArr.each(function() {
            if ($(this).val() == objItemId) {
                failureItemObj.yxeditgrid("removeRow", $(this).data('rowNum'));
            }
        });
    }
    //获取不合格的数量
    var produceNum = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "produceNum").val();
    //当不合格数量大于0的时候才处理不合格部分
    if (produceNum > 0) {
        var productId = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "productId").val();
        var productCode = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "productCode").val();
        var productName = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "productName").val();
        var pattern = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "pattern").val();
        var unitName = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "unitName").val();
        var objId = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "objId").val();
        var objCode = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "objCode").val();
        var objType = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "objType").val();

        //生成不合格记录
        for (var i = 0; i < produceNum; i++) {
            //获取当前行数
            var currentNum = failureItemObj.yxeditgrid('getAllAddRowNum');
            //添加
            failureItemObj.yxeditgrid('addRow', currentNum, {
                'productId': productId, 'productCode': productCode, 'productName': productName,
                'pattern': pattern, 'unitName': unitName, 'objId': objId, 'objCode': objCode,
                'objType': objType, 'objItemId': objItemId
            });
        }
    }
    //重新获取一遍，检验不合格数是否为空了
    failureObjItemIdArr = failureItemObj.yxeditgrid("getCmpByCol", "objItemId");
    if (failureObjItemIdArr.length == 0) {
        autoFailureItem();
    }
}

//初始化不合格列表内容
function initFailureItemGrid(dimensionNameArr) {
    //获取评价等级
    var datadict = getQualityResult();
    //基本项目表头
    var baseTitle = [{
        name: 'objId',
        display: 'objId',
        type: 'hidden'
    }, {
        name: 'objCode',
        display: 'objCode',
        type: 'hidden'
    }, {
        name: 'objType',
        display: 'objType',
        type: 'hidden'
    }, {
        name: 'objItemId',
        display: 'objItemId',
        type: 'hidden'
    }, {
        name: 'productId',
        display: 'productId',
        type: 'hidden'
    }, {
        name: 'productCode',
        tclass: 'readOnlyTxtMiddle',
        readonly: true,
        display: '物料编码',
        width: 80
    }, {
        name: 'productName',
        tclass: 'readOnlyTxtMiddle',
        readonly: true,
        display: '物料名称'
    }, {
        name: 'pattern',
        tclass: 'readOnlyTxtMiddle',
        readonly: true,
        display: '规格型号'
    }, {
        name: 'unitName',
        tclass: 'readOnlyTxtShort',
        readonly: true,
        display: '单位'
    }, {
        name: 'serialNo',
        display: '序列号'
    }];

    //载入检验内容
    dimensionNameArr.each(function(i) {
        var resultNum = i + 1;
        baseTitle.push({
            name: 'result' + resultNum,
            display: $(this).val(),
            type: 'select',
            emptyOption: true,
            options: datadict,
            width: 80
        });
    });

    //载入评价内容、备注等信息
    baseTitle.push({
        name: 'result',
        display: '鉴定结论',
        type: 'select',
        datacode: 'ZJJDJL',
        width: 80
    });
    baseTitle.push({
        name: 'level',
        display: '鉴定级别',
        type: 'select',
        datacode: 'ZJJDJB',
        width: 80
    });
    baseTitle.push({
        name: 'remark',
        display: '备注',
        tclass: 'txt'
    });
    return baseTitle;
}