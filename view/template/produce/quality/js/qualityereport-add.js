$(document).ready(function() {
    //�ʼ���Ⱦ
    $("#TO_NAME").yxselect_user({
        hiddenId: 'TO_ID',
        mode: 'check',
        formCode: 'qualityReport'
    });

    //�󶨼��̻س�
    $("form").live('keypress', function(e) {
        var p = e.which;
        if (p == 13) {
            return false;
        }
    });

    //��Ⱦ�ʼ��׼
    initQualitystandard("standardId");

    //����׼
    dimensionArr = getDimension();
    //��ⷽʽ
    checkTypeArr = getCheckType();

    //ʵ��������
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
    
    //Դ������Ϊ��������ģ���ʾ�ĵ�����
    if($("#relDocType").val() == 'ZJSQYDSC'){
    	showDucumentType();
    	//�����ʼ췽�����ʼ��׼
    	$("#qualityPlanName").parents("tr:first").hide();
    	//��ʾ�ƻ�������ͬ���
    	$("#relCodeTr").show();
    	//��ʾ��ע
    	$("#remark").parents("tr:first").show();
    	//��ʾָ���ĵ�
    	$("#guideDocTr").show();
    }else{
        //ʵ�����ʼ�����
        initQualityInfo();
        //ʵ�����ʼ췽��
        $("#qualityPlanName").yxcombogrid_qualityprogram({
            hiddenId: 'qualityPlanId',
            gridOptions: {
                event: {
                    row_dblclick: function(e, row, data) {
                        $("#standardName").val(data.standardName);
                        $("#standardId").val(data.standardId);
                        standardFile(data.standardId);
                        //��պ����¼����ʼ�ģ������
                        $("#itemTable").empty();
                        initQualityInfo(data.id);
                    }
                }
            }
        });
    }
});

//ˢ��
function show_page() {}

//��������
var checkNum = 0;

/**
 * ʵ�����ʼ�����
 * @return {Boolean}
 */
function initEqu() {
    //����ǻ������߽��ù黹����ʼ챨�棬�����ɲ��ϸ���Ϣ��
    var relDocType = $("#relDocType").val();
    var qualityType = relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH";//�����ж��Ƿ�Ϊ�黹��������
    var purchaserNameType = relDocType == 'ZJSQYDSC' ? 'hidden' : 'text';//Դ������Ϊ��������ģ����زɹ�Ա�ֶ�
    $("#ereportequitem").yxeditgrid({
        objName: 'qualityereport[ereportequitem]',
        title: '������Ϣ��������',
        isAdd: false,
        url: "?model=produce_quality_qualitytaskitem&action=listJsonForReport",
        param: {"mainId": $("#mainId").val(), 'checkStatusNull': '1'},
        event: {
            'reloadData': function() {
                //�����ȡ�����ӱ�
                if ($("#ereportequitem").yxeditgrid("getCmpByCol", "supportNum").length == 0) {
                    alert('�ʼ������Ѿ���ɻ���¼����ر���,���ܼ���¼���ʼ챨��');
                    window.close();
                }
                //������������
                countAll();
                //��ʼ���ʼ�������
                initMailPerson();
                $("input[id^='ereportequitem_cmp_thisCheckNum']").trigger('blur');
            },
            "beforeRemoveRow" : function(e, rowNum, rowData, g){
                if(rowData.objType == 'ZJSQDLBF'){//���ϱ����ʼ������������,����ɾ������
                    alert("���ϱ����ʼ������������,����ɾ������");
                    g.isRemoveAction=false;
                    return false;
                }
            },
            "removeRow": function() {
                //������������
                countAll();
                //����ϸ���
                countQualitedNum();
                //����ϸ���
                countProduceNum();
                //����ǻ������߽��ù黹����ʼ챨�棬�����ɲ��ϸ���Ϣ��
                if (qualityType == true) {
                    //��ʼ�����ϸ񲿷�
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
            display: '���ϱ���',
            width: 80
        }, {
            name: 'productName',
            tclass: 'readOnlyTxtMiddle',
            readonly: true,
            display: '��������'
        }, {
            name: 'pattern',
            tclass: 'readOnlyTxtMiddle',
            readonly: true,
            display: '����ͺ�'
        }, {
            name: 'supplierName',
            tclass: 'readOnlyTxtMiddle',
            readonly: true,
            display: '��Ӧ��'
        }, {
            name: 'supplierId',
            display: 'supplierId',
            type: 'hidden'
        }, {
            name: 'supportNum',
            tclass: 'readOnlyTxtShort',
            readonly: true,
            display: '��������',
            width: 70
        }, {
            name: 'thisCheckNum',
            display: '�����ʼ�����',
            width: 70,
            tclass: qualityType == true ? 'readOnlyTxtShort' : '',
            readonly: qualityType
        },{
            name: 'samplingNum',
            display: '�������',
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
            display: '��λ',
            width: 70
        }, {
            name: 'supportTime',
            tclass: 'readOnlyTxtMiddle',
            readonly: true,
            display: '����ʱ��'
        }, {
            name: 'relDocId',
            readonly: true,
            display: 'Դ��id',
            type: 'hidden'
        }, {
            name: 'relDocCode',
            tclass: 'readOnlyTxtShort',
            readonly: true,
            display: 'Դ����',
            width: 90
        }, {
            name: 'purchaserName',
            tclass: 'readOnlyTxtShort',
            readonly: true,
            display: '�ɹ�Ա',
            width: 90,
            type: purchaserNameType
        }, {
            name: 'purchaserId',
            display: '�ɹ�Ա�ʺ�',
            type: 'hidden'
        }, {
            name: 'priority',
            type: 'select',
            display: '�����̶�',
            datacode: 'ZJJJCD',
            width: 70
        }, {
            name: 'qualitedNum',
            tclass: 'txtshort',
            display: '�ϸ���',
            width: 70,
            validation: {
                required: true,
                custom: ['percentageNum']
            },
            event: {
                blur: function() {
                    //����ϸ���
                    countQualitedNum();
                    //�����ʼ���������
                    countEquQualitedNum('qualitedNum', $(this).data("rowNum"));

                    //����ǻ������߽��ù黹����ʼ챨�棬�����ɲ��ϸ���Ϣ��
                    if (qualityType == true) {
                        //��ʼ�����ϸ񲿷�
                        initFailureItem($(this).data("rowNum"));
                    }
                }
            }
        }, {
            name: 'produceNum',
            tclass: 'txtshort produceNum',
            display: '���ϸ���',
            width: 70,
            validation: {
                required: true,
                custom: ['percentageNum']
            },
            event: {
                blur: function() {
                    //����ϸ���
                    countProduceNum();
                    //�����ʼ���������
                    countEquQualitedNum('produceNum', $(this).data("rowNum"));

                    //����ǻ������߽��ù黹����ʼ챨�棬�����ɲ��ϸ���Ϣ��
                    if (qualityType == true) {
                        //��ʼ�����ϸ񲿷�
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
            display: '��ע'
        }]
    });
}

//ʵ�����ʼ�����
function initQualityInfo(programId) {
    //��ѯ����·��
    var url = "";
    //����д����ʼ췽��id����ı�url
    if (programId) {
        url = "?model=produce_quality_quaprogramitem&action=listjson&mainId=" + programId;
    }
    //����ǻ������߽��ù黹����ʼ챨�棬�����ɲ��ϸ���Ϣ��
    var relDocType = $("#relDocType").val();
    var cType = relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH" ? 'hidden' : 'text';
    var needCheck = relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH" ? false : {
        required: true,
        custom: ['percentageNum']
    };

    $("#itemTable").yxeditgrid({
        objName: 'qualityereport[items]',
        isAddOneRow: true,
        title: '�ʼ�����',
        url: url,
        colModel: [{
            name: 'dimensionId',
            display: 'dimensionId',
            type: 'hidden'
        }, {
            name: 'dimensionName',
            tclass: 'txtmiddle',
            display: '������Ŀ',
            validation: {
                required: true
            },
            type: 'select',
            options: dimensionArr,
            emptyOption: true,
            event: {
                change: function() {
                    //����ǻ������߽��ù黹����ʼ챨�棬�����ɲ��ϸ���Ϣ��
                    if (relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH") {
                        //��ʼ�����ϸ񲿷�
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
            display: '���鷽ʽ',
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
                    //�����ʼ���������
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
                    //�����ʼ���������
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
                    //�����ʼ���������
                    countQuaInfoNum('MI', $(this).data("rowNum"));
                }
            },
            type: cType
        }, {
            name: 'qualitedNum',
            tclass: 'txtshort',
            display: '�ϸ���',
            validation: needCheck,
            type: cType
        }, {
            name: 'itemNum',
            display: 'ʵ�ʼ�������',
            type: 'hidden'
        }, {
            name: 'remark',
            tclass: 'txt',
            display: '��ע'
        }],
        event: {
            removeRow: function() {
                //����ǻ������߽��ù黹����ʼ챨�棬�����ɲ��ϸ���Ϣ��
                if (relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH") {
                    //��ʼ�����ϸ񲿷�
                    autoFailureItem();
                }
            }
        }
    });
}

//�Զ���ʼ��
function autoFailureItem() {
    //ֱ�����
    $("#failureItem").yxeditgrid('remove');
    //��ʼ��������Ӳ��ϸ�������
    $("#ereportequitem").yxeditgrid("getCmpByCol", "produceNum").each(function() {
        if ($(this).val() * 1 > 0) {
            initFailureItem($(this).data('rowNum'));
        }
    });
}

//��ʼ�����ϸ񲿷�
function initFailureItem(rowNum) {
    //�����ϸ��б�û�����ݵ�ʱ��ֱ�ӳ�ʼ��
    if ($("#failureItem").html() == "") {
        //��ȡ������Ŀ
        var dimensionNameArr = $("#itemTable").yxeditgrid("getCmpByCol", "dimensionName");
        //������������Ŀ����������Ϣ��Ϊ�յ�ʱ��,�ͳ�ʼ�����ϸ��б�
        if (dimensionNameArr.length > 0) {
            var baseTitle = initFailureItemGrid(dimensionNameArr);
            $("#failureItem").yxeditgrid({
                objName: 'qualityereport[failureitem]',
                isAddOneRow: false,
                isAddAndDel: false,
                title: '���ϸ�������Ϣ',
                colModel: baseTitle
            });
        }
    }

    //��ʼ��������Ӳ��ϸ�������
    var ereportequitemObj = $("#ereportequitem");
    var objItemId = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "objItemId").val();
    var failureItemObj = $("#failureItem");//���ϸ����ϱ�
    //�ж��������ͬ�豸�ļ�¼,���
    var failureObjItemIdArr = failureItemObj.yxeditgrid("getCmpByCol", "objItemId");
    if (failureObjItemIdArr.length > 0) {
        failureObjItemIdArr.each(function() {
            if ($(this).val() == objItemId) {
                failureItemObj.yxeditgrid("removeRow", $(this).data('rowNum'));
            }
        });
    }
    //��ȡ���ϸ������
    var produceNum = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "produceNum").val();
    //�����ϸ���������0��ʱ��Ŵ����ϸ񲿷�
    if (produceNum > 0) {
        var productId = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "productId").val();
        var productCode = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "productCode").val();
        var productName = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "productName").val();
        var pattern = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "pattern").val();
        var unitName = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "unitName").val();
        var objId = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "objId").val();
        var objCode = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "objCode").val();
        var objType = ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "objType").val();

        //���ɲ��ϸ��¼
        for (var i = 0; i < produceNum; i++) {
            //��ȡ��ǰ����
            var currentNum = failureItemObj.yxeditgrid('getAllAddRowNum');
            //���
            failureItemObj.yxeditgrid('addRow', currentNum, {
                'productId': productId, 'productCode': productCode, 'productName': productName,
                'pattern': pattern, 'unitName': unitName, 'objId': objId, 'objCode': objCode,
                'objType': objType, 'objItemId': objItemId
            });
        }
    }
    //���»�ȡһ�飬���鲻�ϸ����Ƿ�Ϊ����
    failureObjItemIdArr = failureItemObj.yxeditgrid("getCmpByCol", "objItemId");
    if (failureObjItemIdArr.length == 0) {
        autoFailureItem();
    }
}

//��ʼ�����ϸ��б�����
function initFailureItemGrid(dimensionNameArr) {
    //��ȡ���۵ȼ�
    var datadict = getQualityResult();
    //������Ŀ��ͷ
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
        display: '���ϱ���',
        width: 80
    }, {
        name: 'productName',
        tclass: 'readOnlyTxtMiddle',
        readonly: true,
        display: '��������'
    }, {
        name: 'pattern',
        tclass: 'readOnlyTxtMiddle',
        readonly: true,
        display: '����ͺ�'
    }, {
        name: 'unitName',
        tclass: 'readOnlyTxtShort',
        readonly: true,
        display: '��λ'
    }, {
        name: 'serialNo',
        display: '���к�'
    }];

    //�����������
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

    //�����������ݡ���ע����Ϣ
    baseTitle.push({
        name: 'result',
        display: '��������',
        type: 'select',
        datacode: 'ZJJDJL',
        width: 80
    });
    baseTitle.push({
        name: 'level',
        display: '��������',
        type: 'select',
        datacode: 'ZJJDJB',
        width: 80
    });
    baseTitle.push({
        name: 'remark',
        display: '��ע',
        tclass: 'txt'
    });
    return baseTitle;
}