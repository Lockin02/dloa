//������
function show_page() {
};

$(document).ready(function() {
    //�ʼ���Ⱦ
    $("#TO_NAME").yxselect_user({
        hiddenId: 'TO_ID',
        mode: 'check',
        formCode: 'qualityReport'
    });

    //��Ⱦ�ʼ��׼
    initQualitystandard("standardId", $("#standardIdHidden").val());

    var qualityTypeObj = $("#qualityTypeHidden");
    $("#standardHtml").html("");
    //ȫ��
    if (qualityTypeObj.val() == "ZJFSQJ") {
        var htmlStr='<select id="standardId" name="qualityereport[standardId]" class="select" onchange="standardFile(this.value);changeName(\'standardId\',\'standardName\');"><option></option></select>';
        $("#standardHtml").html(htmlStr);
        initQualitystandard("standardId");
    } else {
        var htmlStr='<input type="text" class="txt" id="standardId" name="qualityereport[standardId]" style="width:202px;" readonly/>';
        $("#standardHtml").html(htmlStr);
        initstandardName();
    }

    //����׼
    dimensionArr = getDimension();
    //��ⷽʽ
    checkTypeArr = getCheckType();

    //����֤
    validate({
        "docDate": {
            required: true
        },
        "checkNum": {
            required: true,
            custom: ['percentageNum']
        }
    });

    //ѡ��
    setSelect("qualityType");
    //��ʼ��
    changeCheckTypeClear();

    //ʵ��������
    initEqu();
    
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
		if($("#guideDocId").val() != ''){
			var idArr = $("#guideDocId").val().split(',');
			var nameArr = $("#guideDocName").val().split(',');
			var html = '';
			for(var i = 0; i < idArr.length; i++){
				html += '<div class="upload"><a title="�������" href="index1.php?model=file_uploadfile_management&action=toDownFileById&fileId=' + idArr[i] 
				+ '">' + nameArr[i] + '</a></div>';
			}
			$("#guideDocTr .upload").html(html);
		}
    }else{
        //ʵ�����ʼ�����
        var rs = initQualityInfo();
        if (rs == true) {
            //ʵ�������ϸ�
            showDetail();
        }
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
                        //����ǻ������߽��ù黹����ʼ챨�棬�����ɲ��ϸ���Ϣ��
                        var relDocType = $("#relDocType").val();
                        if (relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH") {
                            //��ʼ�����ϸ񲿷�
                            autoFailureItem();
                        }
                    }
                }
            }
        });
    }
});

//��������
var checkNum = 0;

/**
 * ʵ�����ʼ�����
 * @return {Boolean}
 */
function initEqu() {
    //Դ������
    var relDocType = $("#relDocType").val();
    var purchaserNameType = relDocType == 'ZJSQYDSC' ? 'hidden' : 'text';//Դ������Ϊ��������ģ����زɹ�Ա�ֶ�
    $("#ereportequitem").yxeditgrid({
        objName: 'qualityereport[ereportequitem]',
        title: '������Ϣ��������',
        isAdd: false,
        url: "?model=produce_quality_qualityereportequitem&action=listJson",
        param: {"mainId": $("#id").val()},
        event: {
            'reloadData': function() {
                //��ʼ���ʼ�������
                initMailPerson();
            },
            "removeRow": function() {
                //������������
                countAll();
                //����ϸ���
                countQualitedNum();
                //����ϸ���
                countProduceNum();
                //����ǻ������߽��ù黹����ʼ챨�棬�����ɲ��ϸ���Ϣ��
                if (relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH") {
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
            tclass: 'readOnlyTxtShort',
            readonly: true,
            display: '�����ʼ�����',
            width: 70
        },  {
            name: 'samplingNum',
            tclass: 'readOnlyTxtShort',
            readonly: true,
            display: '�������',
            width: 70
        }, {
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
                    if (relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH") {
                        //��ʼ�����ϸ񲿷�
                        initFailureItem($(this).data("rowNum"));
                    }
                }
            }
        }, {
            name: 'produceNum',
            tclass: 'txtshort',
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
                    if (relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH") {
                        //��ʼ�����ϸ񲿷�
                        initFailureItem($(this).data("rowNum"));
                    }
                },
                dblclick: function(e) {
                    var rowData = e.data.rowData  //id
                    if (!isNaN(Number($(this).val())))
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
        }, {
            name: 'remark',
            tclass: 'txt',
            display: '��ע'
        }]
    });
}

//�ʼ�����
function initQualityInfo(programId) {
    //��ѯ����·��
    var url = "";
    //�нṹ
    var colArr = [];
    //����д����ʼ췽��id����ı�url
    if (programId) {
        url = "?model=produce_quality_quaprogramitem&action=listjson&mainId=" + programId;
    } else {
        url = '?model=produce_quality_qualityereportitem&action=editItemJson&mainId=' + $("#id").val();
        colArr.push({
            name: 'id',
            display: 'id',
            type: 'hidden'
        });
    }
    //����ǻ������߽��ù黹����ʼ챨�棬�����ɲ��ϸ���Ϣ��
    var relDocType = $("#relDocType").val();
    var cType = relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH" ? 'hidden' : 'text';
    var needCheck = relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH" ? false : {
        required: true,
        custom: ['percentageNum']
    };

    //������������
    colArr.push({
        name: 'dimensionName',
        tclass: 'txtmiddle',
        display: '������Ŀ',
        validation: {
            required: true
        },
        type: 'select',
        options: dimensionArr,
        emptyOption: true
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
            blur: function(e) {
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
            blur: function(e) {
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
            blur: function(e) {
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
    });

    var rs = false;
    $("#itemTable").yxeditgrid({
        objName: 'qualityereport[items]',
        url: url,
        title: '�ʼ�����',
        colModel: colArr,
        async: false,
        event: {
            reloadData: function() {
                rs = true;
            },
            removeRow: function() {
                //����ǻ������߽��ù黹����ʼ챨�棬�����ɲ��ϸ���Ϣ��
                if (relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH") {
                    //��ʼ�����ϸ񲿷�
                    autoFailureItem();
                }
            }
        }
    });
    return rs;
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

    //���������ɾ�����ϸ�������ϸ
    $("#isChangeItem").val(1);
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

/**
 * ��ʾ���ϸ���ϸ�б�
 */
function showDetail() {
    //��ȡ������Ŀ
    var dimensionNameArr = $("#itemTable").yxeditgrid("getCmpByCol", "dimensionName");
    var baseTitle = initFailureItemGrid(dimensionNameArr);
    $("#failureItem").yxeditgrid({
        url: '?model=produce_quality_failureitem&action=listJson',
        param: {
            mainId: $("#id").val()
        },
        objName: 'qualityereport[failureitem]',
        title: '���ϸ�������Ϣ',
        isAddOneRow: false,
        isAddAndDel: false,
        colModel: baseTitle
    });
}

//��ʼ�����ϸ��б�����
function initFailureItemGrid(dimensionNameArr) {
    //��ȡ���۵ȼ�
    var datadict = getQualityResult();
    //������Ŀ��ͷ
    var baseTitle = [{
        name: 'id',
        display: 'id',
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
        name: 'unitName',
        tclass: 'readOnlyTxtShort',
        readonly: true,
        display: '��λ'
    }, {
        name: 'serialNo',
        display: '���к�',
        validation: {
            required: true
        }
    }];

    //�����������
    dimensionNameArr.each(function(i) {
        var resultNum = i + 1;
        baseTitle.push({
            name: 'result' + resultNum,
            display: $(this).val(),
            type: 'select',
            options: datadict,
            emptyOption: true,
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