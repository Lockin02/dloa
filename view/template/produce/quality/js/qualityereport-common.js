//����ʼ췽ʽ��������
function changeCheckType() {
    var qualityTypeObj = $("#qualityType");
    $("#standardName").val("");
    $("#fileImage").html("");
    $("#standardHtml").html("");

    //ȫ��
    if (qualityTypeObj.val() == "ZJFSQJ") {
        $("#checkNum").attr("readonly", true).attr('class', 'readOnlyTxtNormal validate[required,custom[percentageNum]]');
        $("#checkNumNeed").attr("class", "");
        var htmlStr='<select id="standardId" name="qualityereport[standardId]" class="select" onchange="standardFile(this.value);changeName(\'standardId\',\'standardName\');"><option></option></select>';
        $("#standardHtml").html(htmlStr);
        initQualitystandard("standardId");

        //�����ʼ�����
        countAll();
    } else {
        $("#checkNum").attr("readonly", false).attr('class', 'txt validate[required,custom[percentageNum]]');
//        $("#checkNum").attr('standardId', 'checked="checked"');
        $("#checkNumNeed").attr("class", "blue");
        var htmlStr='<input type="text" class="txt" id="standardId" name="qualityereport[standardId]" style="width:202px;" readonly/>';
        $("#standardHtml").html(htmlStr);
        initstandardName();
    }
}

//����ʼ췽ʽ��������
function changeCheckTypeClear() {
    var qualityTypeObj = $("#qualityType");

    //ȫ��
    if (qualityTypeObj.val() == "ZJFSQJ") {
        $("#checkNum").attr("readonly", true).attr('class', 'readOnlyTxtNormal validate[required,custom[percentageNum]]');
        $("#checkNumNeed").attr("class", "");
    } else {
        $("#checkNum").attr("readonly", false).attr('class', 'txt validate[required,custom[percentageNum]]');
        $("#checkNumNeed").attr("class", "blue");
    }
}


//��ʼ���ʼ��׼
function initstandardName(){
    //��ȡ���鲹�䷽ʽ
    var addModeNameArr = $('#standardName').val();
    var str;
    //���鲹�䷽ʽ��Ⱦ
    var addModeNameObj = $('#standardId');
    addModeNameObj.combobox({
        url:'index1.php?model=produce_quality_standard&action=listJson',
        multiple:true,
        valueField:'standardName',
        textField:'standardName',
        editable : false,
        formatter: function(obj){
            //�ж� ���û�г�ʼ�������У���ѡ��
            if(addModeNameArr.indexOf(obj.standardName) == -1){
                str = "<input type='checkbox' id='standardId"+ obj.standardName +"' value='"+ obj.standardName +"'/> " + obj.standardName;
            }else{
                str = "<input type='checkbox' id='standardId"+ obj.standardName +"' value='"+ obj.standardName +"' checked='checked'/> " + obj.standardName;
            }
            return str;
        },
        onSelect : function(obj){
            //checkbox��ֵ
            $("#standardId" + obj.standardName).attr('checked',true);
            //���ö����µ�ѡ����
            mulSelectSet(addModeNameObj);
        },
        onUnselect : function(obj){
            //checkbox��ֵ
            $("#standardId" + obj.standardName).attr('checked',false);
            //����������
            mulSelectSet(addModeNameObj);
        }
    });

    //��ʼ����ֵ
    mulSelectInit(addModeNameObj);
}

//������������
function mulSelectSet(thisObj){
    thisObj.next().find("input").each(function(i,n){
        if($(this).attr('class') == 'combo-text validatebox-text'){
            $("#standardName").val(this.value);
        }
    });
}
//��ֵ��ѡֵ -- ��ʼ����ֵ
function mulSelectInit(thisObj){
    //��ʼ����Ӧ����
    var objVal = $("#standardName").val();
    if(objVal != "" ){
        thisObj.combobox("setValues",objVal.split(','));
    }
    $("#standardName").val($("#standardId").val());
}

//��������
function countAll() {
    var cmps = $("#ereportequitem").yxeditgrid("getCmpByCol", "thisCheckNum");
    var allNum = 0;
    cmps.each(function(i, n) {
        //���˵�ɾ������
        if ($("#qualityereport[ereportequitem]_" + i + "_isDelTag").length == 0) {
            allNum = accAdd($(this).val(), allNum);
        }
    });

    //����һ������
    if (checkNum != undefined) {
        checkNum = allNum;
    }
    $("#checkNum").val(allNum);
    $("#supportNum").val(allNum);
}

//����ϸ�����
function countQualitedNum() {
    var cmps = $("#ereportequitem").yxeditgrid("getCmpByCol", "qualitedNum");
    var allNum = 0;
    cmps.each(function(i, n) {
        //���˵�ɾ������
        if ($("#qualityereport[ereportequitem]_" + i + "_isDelTag").length == 0) {
            allNum = accAdd($(this).val(), allNum);
        }
    });
    $("#qualitedNum").val(allNum);
}

//���㲻�ϸ���
function countProduceNum() {
    var cmps = $("#ereportequitem").yxeditgrid("getCmpByCol", "produceNum");
    var allNum = 0;
    cmps.each(function(i, n) {
        //���˵�ɾ������
        if ($("#qualityereport[ereportequitem]_" + i + "_isDelTag").length == 0) {
            allNum = accAdd($(this).val(), allNum);
        }
    });
    $("#produceNum").val(allNum);
}

function countSerialNum(){
    var cmps = $("#ereportequitem").yxeditgrid("getCmpByCol", "serialnoChkedNum");
    var allNum = 0;
    if(cmps.length > 0){
        cmps.each(function(i, n) {
            //���˵�ɾ������
            if ($("#qualityereport[ereportequitem]_" + i + "_isDelTag").length == 0) {
                allNum = accAdd($(this).val(), allNum);
                if($(this).val() == "" || $(this).val() == 0){
                    var serialNoStr = $("ereportequitem_cmp_serialnoName"+ i).val();
                    if(serialNoStr == undefined || serialNoStr == ""){
                        allNum = 0;
                    }else{
                        var serialNoArr = serialNoStr.split(",");
                        allNum = serialNoArr.length;
                    }
                }
            }
        });
    }
    return allNum;
}

//�����ʼ���������
function countEquQualitedNum(thisKey, rowNum) {
    //ʵ�ʼ�������
    var checkNum = $("#checkNum").val();

    //�����ʼ����ݱ�
    var itemTableObj = $("#ereportequitem");

    //���μ�������
//	var supportNum = $("#thisCheckNum").val();
    var sum = 0;
    var thisCheckNum = itemTableObj.yxeditgrid("getCmpByCol", "thisCheckNum");
    thisCheckNum.each(function() {
        sum = accAdd($(this).val(), sum);
    });

    //����ʼ��������ڱ�������
    if (checkNum * 1 == sum * 1) {
        //���б�������
        var supportNum = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "thisCheckNum").val() * 1;
        if (thisKey == "qualitedNum") {
            var qualitedNum = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "qualitedNum").val() * 1;

            //���ϸ�����
            var produceNum = accSub(supportNum, qualitedNum) * 1;
            if (produceNum < 0) {
                alert('��������');
                return false;
            }
            itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "produceNum").val(produceNum);
        } else {
            var produceNum = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "produceNum").val() * 1;
            //�ϸ�����
            var qualitedNum = accSub(supportNum, produceNum) * 1;
            if (qualitedNum < 0) {
                alert('��������');
                return false;
            }
            itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "qualitedNum").val(qualitedNum);
        }
    }

    //����һЩ����
    countProduceNum();
    countQualitedNum();
}

//�����ʼ���������
function countQuaInfoNum(thisKey, rowNum) {
    //ʵ�ʼ�������
    var checkNum = $("#checkNum").val();

    //�����ʼ����ݱ�
    var itemTableObj = $("#itemTable");

    var cr = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "crNum").val() * 1;
    var ma = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "maNum").val() * 1;
    var mi = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "miNum").val() * 1;
    var qualitedNumObj = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "qualitedNum");

    //���¼���
    var newQualitedNum = accSub(checkNum, accAdd(cr, accAdd(ma, mi))) * 1;

    if (newQualitedNum < 0) {
        alert('�ʼ������Ѵ���ʵ�ʼ���������������');
    }
    qualitedNumObj.val(newQualitedNum);
}


//�����������
function countConfirmNum() {
    var objGrid = $("#ereportequitem");

    //������������
    var allNum = 0;
    objGrid.yxeditgrid("getCmpByCol", "passNum").each(function(i, n) {
        //���˵�ɾ������
        allNum = accAdd($(this).val(), allNum);
    });
    $("#passNum").val(allNum);

    //�ò���������
    allNum = 0;
    objGrid.yxeditgrid("getCmpByCol", "receiveNum").each(function(i, n) {
        //���˵�ɾ������
        allNum = accAdd($(this).val(), allNum);
    });
    $("#receiveNum").val(allNum);

    //�˻�����
    allNum = 0;
    objGrid.yxeditgrid("getCmpByCol", "backNum").each(function(i, n) {
        //���˵�ɾ������
        allNum = accAdd($(this).val(), allNum);
    });
    $("#backNum").val(allNum);
}

//�����ʼ���������
function countEquConfirmNum(thisKey, rowNum) {
    //�����ʼ����ݱ�
    var itemTableObj = $("#ereportequitem");

    //���б�������
    var supportNum = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "thisCheckNum").val() * 1;

    var passNumObj = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "passNum");
    var receiveNumObj = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "receiveNum");
    var backNumObj = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "backNum");
    var produceNumObj = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "produceNum");

    var lastNum;

    if (thisKey == "passNum") {
        //��������
        lastNum = accSub(supportNum, accAdd(passNumObj.val(), receiveNumObj.val()));
        backNumObj.val(lastNum);

    } else if (thisKey == "receiveNum") {
        //�ò�����
        lastNum = accSub(supportNum, accAdd(passNumObj.val(), receiveNumObj.val()));
        backNumObj.val(lastNum);
    } else {
        lastNum = accSub(supportNum, backNumObj.val());

        //�ж�����������۳�
        if (passNumObj.val() * 1 == 0) {
            receiveNumObj.val(lastNum);
        } else {
            passNumObj.val(lastNum);
        }
    }

    //����ò������������ڲ��ϸ������������³�ʼ���˼�¼
    if (Number(produceNumObj.val()) < Number(receiveNumObj.val())) {
        alert('�ò����������ܴ��ڲ��ϸ���');
        passNumObj.val(accSub(supportNum, produceNumObj.val()));
        receiveNumObj.val(0);
        backNumObj.val(produceNumObj.val());
        return false;
    }

    //����һЩ����
    countConfirmNum();
}

//�������úϸ�
function quickSetPass() {
    //ʵ�ʼ�������
    var checkNum = $("#checkNum").val();

    //�����ʼ����ݱ�
    var itemTableObj = $("#itemTable");

    //CR����
    var cmps = itemTableObj.yxeditgrid("getCmpByCol", "crNum");
    cmps.each(function(i, n) {
        //���˵�ɾ������
        if ($("#qualityereport[itemTable]_" + i + "_isDelTag").length == 0) {
            $(this).val(0);
        }
    });

    //MA����
    var cmps = itemTableObj.yxeditgrid("getCmpByCol", "maNum");
    cmps.each(function(i, n) {
        //���˵�ɾ������
        if ($("#qualityereport[itemTable]_" + i + "_isDelTag").length == 0) {
            $(this).val(0);
        }
    });

    //MI����
    var cmps = itemTableObj.yxeditgrid("getCmpByCol", "miNum");
    cmps.each(function(i, n) {
        //���˵�ɾ������
        if ($("#qualityereport[itemTable]_" + i + "_isDelTag").length == 0) {
            $(this).val(0);
        }
    });

    //�ϸ�������
    itemTableObj.yxeditgrid("getCmpByCol", "qualitedNum").each(function(i, n) {
        //���˵�ɾ������
        if ($("#qualityereport[itemTable]_" + i + "_isDelTag").length == 0) {
            $(this).val(checkNum);
        }
    });

    //�����ʼ����ݱ�
    var ereportequitemObj = $("#ereportequitem");

    //���μ�������
//	var supportNum = $("#thisCheckNum").val();
    var sum = 0;
    var thisCheckNum = ereportequitemObj.yxeditgrid("getCmpByCol", "thisCheckNum");
    thisCheckNum.each(function() {
        sum = accAdd($(this).val(), sum);
    });

    //������������ʵ�ʼ�������ʱ��ִ�д˲��ִ���
    if (sum * 1 == checkNum * 1) {
        //�ϸ�������
        var cmps = ereportequitemObj.yxeditgrid("getCmpByCol", "qualitedNum");
        cmps.each(function(i, n) {
            var rowNum = $(this).data("rowNum");
            //���˵�ɾ������
            if ($("#qualityereport[ereportequitem]_" + rowNum + "_isDelTag").length == 0) {
                $(this).val(ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "thisCheckNum").val() * 1);
            }
        });
    }

    //���ϸ�������
    ereportequitemObj.yxeditgrid("getCmpByCol", "produceNum").each(function(i) {
        //���˵�ɾ������
        if ($("#qualityereport[ereportequitem]_" + i + "_isDelTag").length == 0) {
            $(this).val(0);
        }
    });

    //����һЩ����
    countProduceNum();
    countQualitedNum();
    //����ǻ������߽��ù黹����ʼ챨�棬��ɾ�����ϸ񲿷�
    var relDocType = $("#relDocType").val();
    if (relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH") {
        $("#failureItem").yxeditgrid('remove');
        //���������ɾ�����ϸ�������ϸ
        if ($("#isChangeItem").length > 0) {
            $("#isChangeItem").val(1);
        }
    }
}

//��ʼ���ʼ�������
function initMailPerson() {
    //Ĭ���ʼ�������
    var TO_NAME = $("#TO_NAME").val();
    var TO_ID = $("#TO_ID").val();
    var TO_NAMEArr = TO_NAME.split(",");
    var TO_IDArr = TO_ID.split(",");

    //�����ʼ����ݱ�
    var ereportequitemObj = $("#ereportequitem");
    //���ϸ�������
    ereportequitemObj.yxeditgrid("getCmpByCol", "purchaserId").each(function(i, n) {
        //���˵�ɾ������
        if ($("#qualityereport[ereportequitem]_" + i + "_isDelTag").length == 0) {
            if ($(this).val() != "" && jQuery.inArray($(this).val(), TO_IDArr) == -1) {
                TO_IDArr.push($(this).val());
            }
        }
    });

    //���ϸ�������
    ereportequitemObj.yxeditgrid("getCmpByCol", "purchaserName").each(function(i, n) {
        //���˵�ɾ������
        if ($("#qualityereport[ereportequitem]_" + i + "_isDelTag").length == 0) {
            if ($(this).val() != "" && jQuery.inArray($(this).val(), TO_NAMEArr) == -1) {
                TO_NAMEArr.push($(this).val());
            }
        }
    });

    $("#TO_NAME").val(TO_NAMEArr.toString());
    $("#TO_ID").val(TO_IDArr.toString());
}

//�ύ�����ı�����ֵ
function audit(thisType) {
    $("#auditStatus").val(thisType);
}

/**
 * ������
 * @returns {Boolean}
 */
function checkForm() {
    var checkNum = $("#checkNum").val() * 1; //�ʼ�����
    var qualitedNum = $("#qualitedNum").val() * 1; //�ϸ���
    var produceNum = $("#produceNum").val() * 1; //���ϸ���

    var relDocType = $("#relDocType").val();

    // ���ϱ���
    if(relDocType == "ZJSQDLBF"){
        var totalSerialChkedNum = countSerialNum();
        if(produceNum != totalSerialChkedNum){
            alert("���������к������벻������������������ȣ�");
            return false;
        }
    }

    if (qualitedNum > checkNum) {
        alert("�ϸ������ܴ���ʵ�ʼ���������");
        return false;
    }

    var sumNum = accAdd(produceNum, qualitedNum); //�ϸ��� + ���ϸ���
    if (sumNum != checkNum) {
        if(relDocType == "ZJSQDLBF"){
            alert("������+��������(" + sumNum + ") ������ ʵ�ʼ�������(" + checkNum + "),�����º˶�������");
        }else{
            alert("�ϸ���+���ϸ���(" + sumNum + ") ������ ʵ�ʼ�������(" + checkNum + "),�����º˶�������");
        }

        return false;
    }

    //add chenrf 20130615
    /*****************************/
    if ($("#pageType").val() == 'edit') {   //�༭Ҳ��(����ҳ��ͱ༭ҳ��ȡ����Ϊ��ͬ��)
        //��ȡid
        var $idArr = $("#ereportequitem").yxeditgrid('getCmpByCol', 'relItemId');
    } else {//����ҳ��
        $("input[id^='ereportequitem_cmp_id']").removeAttr('name');  //ɾ��������Ϣ��������id��name���ԣ���ֹ�ύ
        //��ȡid
        $idArr = $("#ereportequitem").yxeditgrid('getCmpByCol', 'id');
    }
    //��ȡ���ϸ�����
    var $failArr = $("#ereportequitem").yxeditgrid('getCmpByCol', 'produceNum');
    //��ȡ���ϱ���
    var $productCodeArr = $("#ereportequitem").yxeditgrid('getCmpByCol', 'productCode');

    var msgArr = [];
    for (var i = 0; i < $idArr.length; i++) {
        var relDocId = $($idArr[i]).val();
        var failCount = ($($failArr[i]).val()) * 1;   //��д�Ĳ��ϸ���
        var productCode = $($productCodeArr[i]).val();
        var count = $.ajax({
            url: '?model=produce_quality_serialno&action=ajaxCount&relDocId=' + relDocId + '&relDocType=qualityEreport',
            type: 'get',
            async: false,
            error: function() {
                alert('���ӷ�����ʧ��!');
            }
        }).responseText;

        var error = '���ϱ�ţ�' + productCode + '\n���ϸ�����' + failCount + '\n��д���к�������' + count;
        if(relDocType == "ZJSQDLBF"){
            error = '���ϱ�ţ�' + productCode + '\n����������' + failCount + '\n��д���к�������' + countSerialNum();
        }

        if (Number(count) != failCount) {
            msgArr.push(error);
        }
        if (Number(count) > failCount) {
            alert(error + '\n��ɾ���������к�');
            return false;
        }
    }

    //���鲻�ϸ�������Ϣ
    var failureItem = $("#failureItem");
    var rs = true;
    if (failureItem.text() != "") {
        //���������
        var dimensionNameArr = $("#itemTable").yxeditgrid("getCmpByCol", "dimensionName");

        failureItem.yxeditgrid("getCmpByCol", "serialNo").each(function() {
            //���к�
            if ($(this).val() == "") {
                alert('����û����д���кŵĲ��ϸ�������Ϣ');
                rs = false;
                return false;
            }
            //������Ŀ������
            if (dimensionNameArr.length > 0) {
                var isAllEmpty = true;
                for (var i = 1; i <= dimensionNameArr.length; i++) {
                    if (failureItem.yxeditgrid("getCmpByRowAndCol", $(this).data('rowNum'), "result" + i).val() != "") {
                        isAllEmpty = false;
                        break;
                    }
                }
                if (isAllEmpty == true) {
                    alert('���ڼ�����ȫ��Ϊ�յĲ��ϸ�������Ϣ');
                    rs = false;
                    return false;
                }
            }
        });
    }
    if (rs == false) return false;
    
    //add By weijb 20150714
    //Դ������Ϊ�����黹ʱ,���黹���ϼ��鲻�ϸ�ʱ,��Ҫ�����ϴ�ͼƬ֤��/�ı�������
    if($("#relDocType").val() == "ZJSQYDGH" && $("#produceNum").val() != '0'){
    	if($.trim($("#uploadfileList").html()) == "" || $("#uploadfileList").html() == "�����κθ���"){
    		alert("��ǰ���ڼ��鲻�ϸ������,���ϴ�ͼƬ֤�ݻ��ı�����");
    	    return false;
    	}
    }
    
    //ȷ�ϲ���
    if ($("#auditStatus").val() == "WSH") {
        if (msgArr.length > 0) {
            return confirm(msgArr[0] + '\nȷ���ύ���ʼ챨����')
        } else
            return confirm('\nȷ���ύ���ʼ챨����')
    } else {
        if (msgArr.length > 0) {
            return confirm(msgArr[0] + '\nȷ�����棿')
        }
    }

    return true;
}

/**
 * ������
 * @returns {Boolean}
 */
function checkFormConfirm() {
    var auditStatus = $("#auditStatus").val();
    // ����ò���������
    var gridObj = $("#ereportequitem");
    var needConfirmRbjs = false;
    gridObj.yxeditgrid("getCmpByCol", "receiveNum").each(function(i, n) {
        if(parseInt($(n).val()) == 0){
            needConfirmRbjs = true;
        }
    });

    //ȷ�ϲ���
    if(auditStatus == "RBJS" && needConfirmRbjs){
        return confirm('�����ò���������Ϊ0������,�Ƿ��ύ���������ȷ��,�������,���ȡ��,�ص����ҳ�档');
    }else{
        return confirm('ȷ����˴˱�����');
    }
}

//���ҳ���ʼ����ϴ���
function changeAuditStatus() {
    var auditStatus = $("#auditStatus").val();
    if (auditStatus == 'BHG') {
        initBack(1);
    } else {
        initReceive(1);
    }

    //����һЩ����
    countConfirmNum();
}

//ҳ���ʼ�� - ������ֵ
function initConfirm() {
    var allNum;
    allNum = accAdd($("#receiveNum").val(), $("#passNum").val());
    allNum = accAdd(allNum, $("#backNum").val());

    if (allNum * 1 == 0) {
        changeAuditStatus();
    } else {
        var auditStatus = $("#auditStatus").val();
        if (auditStatus == 'BHG') {
            initBack(0);
        } else {
            initReceive(0);
        }
    }
}

//ҳ���ʼ�� - ������ֵ
function initConfirmClear() {
    var auditStatus = $("#auditStatus").val();
    if (auditStatus == 'BHG') {
        initBack(0);
    } else {
        initReceive(0);
    }
}

//��ʼ�����ϸ�
function initBack(isSetValue) {
    var gridObj = $("#ereportequitem");
    //��ʼ�� ��������
    var cmps = gridObj.yxeditgrid("getCmpByCol", "thisCheckNum");
    var allNum = 0;
    cmps.each(function(i, n) {
        //�����ܵ�����
        allNum = accAdd($(this).val(), allNum);

        //��ʼ���˻�����
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "backNum").attr("class", "readOnlyTxtMiddle").attr("readonly", true);
        //��ʼ���ò���������
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "receiveNum").attr("class", "readOnlyTxtMiddle").attr("readonly", true);
        //��ʼ��������������
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "passNum").attr("class", "readOnlyTxtMiddle").attr("readonly", true);

        //�ж��Ƿ���Ҫ���ó�ʼֵ
        if (isSetValue == "1") {
            //��ʼ���˻�����
            gridObj.yxeditgrid("getCmpByRowAndCol", i, "backNum").val($(this).val() * 1);
            //��ʼ���ò���������
            gridObj.yxeditgrid("getCmpByRowAndCol", i, "receiveNum").val(0);
            //��ʼ��������������
            gridObj.yxeditgrid("getCmpByRowAndCol", i, "passNum").val(0);
        }

    });
    //�ж��Ƿ���Ҫ���ó�ʼֵ
    if (isSetValue == "1") {
        $("#backNum").val(allNum);
        $("#receiveNum").val(0);
        $("#passNum").val(0);
    }
}

//��ʼ���ò�����
function initReceive(isSetValue) {
    var gridObj = $("#ereportequitem");
    //��ʼ�� �˻�����
    var cmps = gridObj.yxeditgrid("getCmpByCol", "thisCheckNum");
    var backNumAll = 0;
    var receiveNumAll = 0;
    var passNumAll = 0;
    var backObj, receiveObj;
    cmps.each(function(i, n) {
        //��ȡ�ϸ���
        qualitedNum = gridObj.yxeditgrid("getCmpByRowAndCol", i, "qualitedNum").val();
        //��ȡ���ϸ���
        produceNum = gridObj.yxeditgrid("getCmpByRowAndCol", i, "produceNum").val();
        //�����ϼ�������
        var rowCheckNum = accAdd(qualitedNum, produceNum);

        //��ʼ��������������
        passObj = gridObj.yxeditgrid("getCmpByRowAndCol", i, "passNum");
        passNumAll = accAdd(passNumAll, passObj.val());
        passObj.attr("class", "txt").attr("readonly", false);

        //��ʼ���ò���������
        receiveObj = gridObj.yxeditgrid("getCmpByRowAndCol", i, "receiveNum");
        receiveObj.attr("class", "txt").attr("readonly", false);

        //��ʼ���˻�����
        backObj = gridObj.yxeditgrid("getCmpByRowAndCol", i, "backNum");
        backNumAll = accAdd(backNumAll, backObj.val());
        backObj.attr("class", "txt").attr("readonly", false);

        if (isSetValue == "1") {
            //�жϼ��������ͱ������������ó�ʼֵ
            passObj.val(accSub(this.value, produceNum));
            backObj.val(produceNum);
        }
    });
    //�ж��Ƿ���Ҫ���ó�ʼֵ
    if (isSetValue == "1") {
        $("#backNum").val(backNumAll);
        $("#passNum").val(passNumAll);
        $("#receiveNum").val(0);
    }
}
//����Ϊ�����黹ʱ��ʼ��
function initHHGH() {
    $("#auditStatus option[value='RBJS']").remove(); //ȥ����˽�����ò�����ѡ��

    var gridObj = $("#ereportequitem");
    gridObj.yxeditgrid("getCmpByCol", "thisCheckNum").each(function(i, n) {
        //��ȡ��������
        supportNum = $(this).val();
        //��ȡ�ϸ�����
        var qualitedNum = gridObj.yxeditgrid("getCmpByRowAndCol", i, "qualitedNum");
        //��ʼ�������������������ںϸ���
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "passNum").val(qualitedNum.val());
        //��ʼ���ò��������������ڲ��ϸ���
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "receiveNum").val(accSub(supportNum, qualitedNum.val()));
        //��ʼ���˻�����,����
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "backNum").val(accSub(supportNum, supportNum));
    });
}
//����Ϊ����ʱ��ʼ��
function initSC() {
    $("#auditStatus option[value='RBJS']").remove(); //ȥ����˽�����ò�����ѡ��

    var gridObj = $("#ereportequitem");
    gridObj.yxeditgrid("getCmpByCol", "thisCheckNum").each(function(i, n) {
        //��ȡ��������
        supportNum = $(this).val();
        //��ȡ�ϸ�����
        var qualitedNum = gridObj.yxeditgrid("getCmpByRowAndCol", i, "qualitedNum");
        //��ʼ�������������������ںϸ���
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "passNum").val(qualitedNum.val());
        //��ʼ���ò���������������
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "receiveNum").val(accSub(supportNum, supportNum));
        //��ʼ���˻����������ڲ��ϸ���
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "backNum").val(accSub(supportNum, qualitedNum.val()));
    });
}

//Դ��Ϊ�����ʼ촦��
function showDucumentType(){
	// ��Ⱦ�������
	$("#parentName").yxcombotree({
		hiddenId : 'parentId',
		treeOptions : {
			url : "?model=produce_document_documenttype&action=getTreeDataByParentId&typeId=2"//ֻ��ѡ��"�ճ�����"�µķ���
		}
	});
	$("#ducumentTd").show().next("td").show();
	$("#uploadfileList").parents("td").attr("colspan", 1);
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
        }
    });
	$("#parentId").formValidator({
		onshow : "��ѡ�񸽼��ĵ�����",
		onfocus : "�����ĵ����಻��Ϊ��",
		oncorrect : "�����ĵ�������Ч"
	}).inputValidator({
		min : 1,
		max : 50,
		onerror : "�����ĵ����಻��Ϊ�գ���ѡ��"
	});
	// ��Ⱦָ���ļ�
	$("#guideDocName").yxcombotree({
		hiddenId : 'guideDocId',// ���ؿؼ�id
		nameCol : 'name',
		valueCol : 'id',
		treeOptions : {
			checkable : true,// ��ѡ
			event : {
				"node_click" : function(event, treeId, treeNode) {
					var id = treeNode.id;
					var idArr = new Array();
					var html = '';
					if($("#guideDocId").val() != ''){
						var idArr = $("#guideDocId").val().split(',');
						var nameArr = $("#guideDocName").val().split(',');
						var index = idArr.indexOf(id);
						if(index == -1){
							idArr.push(id);
						}else{
							delete idArr[index];
						}
						var len = idArr.length;
						if(len > 0){
							for(var i = 0; i < len; i++){
								if(idArr[i] != undefined){
									var name = nameArr[i];
									if(name == undefined){
										name = treeNode.name;
									}
									html += '<div class="upload"><a title="�������" href="index1.php?model=file_uploadfile_management&action=toDownFileById&fileId=' + idArr[i] 
										+ '">' + name + '</a></div>';
								}
							}
						}
					}else{
						html += '<div class="upload"><a title="�������" href="index1.php?model=file_uploadfile_management&action=toDownFileById&fileId=' + id 
							+ '">' + treeNode.name + '</a></div>';
					}
					$("#guideDocTr .upload").html(html);
				}
			},
			url : "index1.php?model=produce_document_document&action=getDocuments&parentId=1"// ����ֻ��ȡ�淶�ĵ�������idΪ1
		}
	});
}

/**
 * ѡ�����к�
 */
function chooseSerialNo(rowObj) {
    var rNum = rowObj.rowNum;
    var rowData = rowObj.rowData;
    var rowSerialIds = $("#ereportequitem_cmp_serialnoId"+rNum).val();
    var maxSelectNum = $("#ereportequitem_cmp_produceNum"+rNum).val();

    showThickboxWin(
        "?model=stock_serialno_serialno&action=toChooseQualitySerialno"
        + "&relDocId="+ rowData.objId
        + "&relDocType="+ rowData.objType
        + "&equId="+ rowData.objItemId
        + "&rowNum="+ rNum
        + "&selectedIds="+ rowSerialIds
        + "&maxSelectNum="+ maxSelectNum
        + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=300",
        "ѡ�񲻱������к�")
}