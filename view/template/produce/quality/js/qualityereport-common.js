//变更质检方式触发方法
function changeCheckType() {
    var qualityTypeObj = $("#qualityType");
    $("#standardName").val("");
    $("#fileImage").html("");
    $("#standardHtml").html("");

    //全检
    if (qualityTypeObj.val() == "ZJFSQJ") {
        $("#checkNum").attr("readonly", true).attr('class', 'readOnlyTxtNormal validate[required,custom[percentageNum]]');
        $("#checkNumNeed").attr("class", "");
        var htmlStr='<select id="standardId" name="qualityereport[standardId]" class="select" onchange="standardFile(this.value);changeName(\'standardId\',\'standardName\');"><option></option></select>';
        $("#standardHtml").html(htmlStr);
        initQualitystandard("standardId");

        //计算质检数量
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

//变更质检方式触发方法
function changeCheckTypeClear() {
    var qualityTypeObj = $("#qualityType");

    //全检
    if (qualityTypeObj.val() == "ZJFSQJ") {
        $("#checkNum").attr("readonly", true).attr('class', 'readOnlyTxtNormal validate[required,custom[percentageNum]]');
        $("#checkNumNeed").attr("class", "");
    } else {
        $("#checkNum").attr("readonly", false).attr('class', 'txt validate[required,custom[percentageNum]]');
        $("#checkNumNeed").attr("class", "blue");
    }
}


//初始化质检标准
function initstandardName(){
    //获取建议补充方式
    var addModeNameArr = $('#standardName').val();
    var str;
    //建议补充方式渲染
    var addModeNameObj = $('#standardId');
    addModeNameObj.combobox({
        url:'index1.php?model=produce_quality_standard&action=listJson',
        multiple:true,
        valueField:'standardName',
        textField:'standardName',
        editable : false,
        formatter: function(obj){
            //判断 如果没有初始化数组中，则选中
            if(addModeNameArr.indexOf(obj.standardName) == -1){
                str = "<input type='checkbox' id='standardId"+ obj.standardName +"' value='"+ obj.standardName +"'/> " + obj.standardName;
            }else{
                str = "<input type='checkbox' id='standardId"+ obj.standardName +"' value='"+ obj.standardName +"' checked='checked'/> " + obj.standardName;
            }
            return str;
        },
        onSelect : function(obj){
            //checkbox设值
            $("#standardId" + obj.standardName).attr('checked',true);
            //设置对象下的选中项
            mulSelectSet(addModeNameObj);
        },
        onUnselect : function(obj){
            //checkbox设值
            $("#standardId" + obj.standardName).attr('checked',false);
            //设置隐藏域
            mulSelectSet(addModeNameObj);
        }
    });

    //初始化赋值
    mulSelectInit(addModeNameObj);
}

//隐藏区域设置
function mulSelectSet(thisObj){
    thisObj.next().find("input").each(function(i,n){
        if($(this).attr('class') == 'combo-text validatebox-text'){
            $("#standardName").val(this.value);
        }
    });
}
//设值多选值 -- 初始化赋值
function mulSelectInit(thisObj){
    //初始化对应内容
    var objVal = $("#standardName").val();
    if(objVal != "" ){
        thisObj.combobox("setValues",objVal.split(','));
    }
    $("#standardName").val($("#standardId").val());
}

//计算数量
function countAll() {
    var cmps = $("#ereportequitem").yxeditgrid("getCmpByCol", "thisCheckNum");
    var allNum = 0;
    cmps.each(function(i, n) {
        //过滤掉删除的行
        if ($("#qualityereport[ereportequitem]_" + i + "_isDelTag").length == 0) {
            allNum = accAdd($(this).val(), allNum);
        }
    });

    //计算一个数量
    if (checkNum != undefined) {
        checkNum = allNum;
    }
    $("#checkNum").val(allNum);
    $("#supportNum").val(allNum);
}

//计算合格数量
function countQualitedNum() {
    var cmps = $("#ereportequitem").yxeditgrid("getCmpByCol", "qualitedNum");
    var allNum = 0;
    cmps.each(function(i, n) {
        //过滤掉删除的行
        if ($("#qualityereport[ereportequitem]_" + i + "_isDelTag").length == 0) {
            allNum = accAdd($(this).val(), allNum);
        }
    });
    $("#qualitedNum").val(allNum);
}

//计算不合格数
function countProduceNum() {
    var cmps = $("#ereportequitem").yxeditgrid("getCmpByCol", "produceNum");
    var allNum = 0;
    cmps.each(function(i, n) {
        //过滤掉删除的行
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
            //过滤掉删除的行
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

//计算质检内容数量
function countEquQualitedNum(thisKey, rowNum) {
    //实际检验数量
    var checkNum = $("#checkNum").val();

    //缓存质检内容表
    var itemTableObj = $("#ereportequitem");

    //本次检验数量
//	var supportNum = $("#thisCheckNum").val();
    var sum = 0;
    var thisCheckNum = itemTableObj.yxeditgrid("getCmpByCol", "thisCheckNum");
    thisCheckNum.each(function() {
        sum = accAdd($(this).val(), sum);
    });

    //如果质检数量等于报检数量
    if (checkNum * 1 == sum * 1) {
        //本行报检数量
        var supportNum = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "thisCheckNum").val() * 1;
        if (thisKey == "qualitedNum") {
            var qualitedNum = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "qualitedNum").val() * 1;

            //不合格数量
            var produceNum = accSub(supportNum, qualitedNum) * 1;
            if (produceNum < 0) {
                alert('数量错误');
                return false;
            }
            itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "produceNum").val(produceNum);
        } else {
            var produceNum = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "produceNum").val() * 1;
            //合格数量
            var qualitedNum = accSub(supportNum, produceNum) * 1;
            if (qualitedNum < 0) {
                alert('数量错误');
                return false;
            }
            itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "qualitedNum").val(qualitedNum);
        }
    }

    //计算一些数量
    countProduceNum();
    countQualitedNum();
}

//计算质检内容数量
function countQuaInfoNum(thisKey, rowNum) {
    //实际检验数量
    var checkNum = $("#checkNum").val();

    //缓存质检内容表
    var itemTableObj = $("#itemTable");

    var cr = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "crNum").val() * 1;
    var ma = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "maNum").val() * 1;
    var mi = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "miNum").val() * 1;
    var qualitedNumObj = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "qualitedNum");

    //重新计算
    var newQualitedNum = accSub(checkNum, accAdd(cr, accAdd(ma, mi))) * 1;

    if (newQualitedNum < 0) {
        alert('质检数量已大于实际检验数量，请修正');
    }
    qualitedNumObj.val(newQualitedNum);
}


//计算审核数量
function countConfirmNum() {
    var objGrid = $("#ereportequitem");

    //正常接收数量
    var allNum = 0;
    objGrid.yxeditgrid("getCmpByCol", "passNum").each(function(i, n) {
        //过滤掉删除的行
        allNum = accAdd($(this).val(), allNum);
    });
    $("#passNum").val(allNum);

    //让步接收数量
    allNum = 0;
    objGrid.yxeditgrid("getCmpByCol", "receiveNum").each(function(i, n) {
        //过滤掉删除的行
        allNum = accAdd($(this).val(), allNum);
    });
    $("#receiveNum").val(allNum);

    //退回数量
    allNum = 0;
    objGrid.yxeditgrid("getCmpByCol", "backNum").each(function(i, n) {
        //过滤掉删除的行
        allNum = accAdd($(this).val(), allNum);
    });
    $("#backNum").val(allNum);
}

//计算质检内容数量
function countEquConfirmNum(thisKey, rowNum) {
    //缓存质检内容表
    var itemTableObj = $("#ereportequitem");

    //本行报检数量
    var supportNum = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "thisCheckNum").val() * 1;

    var passNumObj = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "passNum");
    var receiveNumObj = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "receiveNum");
    var backNumObj = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "backNum");
    var produceNumObj = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "produceNum");

    var lastNum;

    if (thisKey == "passNum") {
        //正常接收
        lastNum = accSub(supportNum, accAdd(passNumObj.val(), receiveNumObj.val()));
        backNumObj.val(lastNum);

    } else if (thisKey == "receiveNum") {
        //让步接收
        lastNum = accSub(supportNum, accAdd(passNumObj.val(), receiveNumObj.val()));
        backNumObj.val(lastNum);
    } else {
        lastNum = accSub(supportNum, backNumObj.val());

        //判断数量从哪里扣除
        if (passNumObj.val() * 1 == 0) {
            receiveNumObj.val(lastNum);
        } else {
            passNumObj.val(lastNum);
        }
    }

    //如果让步接收数量大于不合格数量，则重新初始化此记录
    if (Number(produceNumObj.val()) < Number(receiveNumObj.val())) {
        alert('让步接收数不能大于不合格数');
        passNumObj.val(accSub(supportNum, produceNumObj.val()));
        receiveNumObj.val(0);
        backNumObj.val(produceNumObj.val());
        return false;
    }

    //计算一些数量
    countConfirmNum();
}

//快速设置合格
function quickSetPass() {
    //实际检验数量
    var checkNum = $("#checkNum").val();

    //缓存质检内容表
    var itemTableObj = $("#itemTable");

    //CR设置
    var cmps = itemTableObj.yxeditgrid("getCmpByCol", "crNum");
    cmps.each(function(i, n) {
        //过滤掉删除的行
        if ($("#qualityereport[itemTable]_" + i + "_isDelTag").length == 0) {
            $(this).val(0);
        }
    });

    //MA设置
    var cmps = itemTableObj.yxeditgrid("getCmpByCol", "maNum");
    cmps.each(function(i, n) {
        //过滤掉删除的行
        if ($("#qualityereport[itemTable]_" + i + "_isDelTag").length == 0) {
            $(this).val(0);
        }
    });

    //MI设置
    var cmps = itemTableObj.yxeditgrid("getCmpByCol", "miNum");
    cmps.each(function(i, n) {
        //过滤掉删除的行
        if ($("#qualityereport[itemTable]_" + i + "_isDelTag").length == 0) {
            $(this).val(0);
        }
    });

    //合格数设置
    itemTableObj.yxeditgrid("getCmpByCol", "qualitedNum").each(function(i, n) {
        //过滤掉删除的行
        if ($("#qualityereport[itemTable]_" + i + "_isDelTag").length == 0) {
            $(this).val(checkNum);
        }
    });

    //缓存质检内容表
    var ereportequitemObj = $("#ereportequitem");

    //本次检验数量
//	var supportNum = $("#thisCheckNum").val();
    var sum = 0;
    var thisCheckNum = ereportequitemObj.yxeditgrid("getCmpByCol", "thisCheckNum");
    thisCheckNum.each(function() {
        sum = accAdd($(this).val(), sum);
    });

    //报检数量等于实际检验数量时才执行此部分代码
    if (sum * 1 == checkNum * 1) {
        //合格数设置
        var cmps = ereportequitemObj.yxeditgrid("getCmpByCol", "qualitedNum");
        cmps.each(function(i, n) {
            var rowNum = $(this).data("rowNum");
            //过滤掉删除的行
            if ($("#qualityereport[ereportequitem]_" + rowNum + "_isDelTag").length == 0) {
                $(this).val(ereportequitemObj.yxeditgrid("getCmpByRowAndCol", rowNum, "thisCheckNum").val() * 1);
            }
        });
    }

    //不合格数设置
    ereportequitemObj.yxeditgrid("getCmpByCol", "produceNum").each(function(i) {
        //过滤掉删除的行
        if ($("#qualityereport[ereportequitem]_" + i + "_isDelTag").length == 0) {
            $(this).val(0);
        }
    });

    //计算一些数量
    countProduceNum();
    countQualitedNum();
    //如果是换货或者借用归还类的质检报告，则删除不合格部分
    var relDocType = $("#relDocType").val();
    if (relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH") {
        $("#failureItem").yxeditgrid('remove');
        //变更方案会删除不合格物料明细
        if ($("#isChangeItem").length > 0) {
            $("#isChangeItem").val(1);
        }
    }
}

//初始化邮件接收人
function initMailPerson() {
    //默认邮件接收人
    var TO_NAME = $("#TO_NAME").val();
    var TO_ID = $("#TO_ID").val();
    var TO_NAMEArr = TO_NAME.split(",");
    var TO_IDArr = TO_ID.split(",");

    //缓存质检内容表
    var ereportequitemObj = $("#ereportequitem");
    //不合格数设置
    ereportequitemObj.yxeditgrid("getCmpByCol", "purchaserId").each(function(i, n) {
        //过滤掉删除的行
        if ($("#qualityereport[ereportequitem]_" + i + "_isDelTag").length == 0) {
            if ($(this).val() != "" && jQuery.inArray($(this).val(), TO_IDArr) == -1) {
                TO_IDArr.push($(this).val());
            }
        }
    });

    //不合格数设置
    ereportequitemObj.yxeditgrid("getCmpByCol", "purchaserName").each(function(i, n) {
        //过滤掉删除的行
        if ($("#qualityereport[ereportequitem]_" + i + "_isDelTag").length == 0) {
            if ($(this).val() != "" && jQuery.inArray($(this).val(), TO_NAMEArr) == -1) {
                TO_NAMEArr.push($(this).val());
            }
        }
    });

    $("#TO_NAME").val(TO_NAMEArr.toString());
    $("#TO_ID").val(TO_IDArr.toString());
}

//提交审批改变隐藏值
function audit(thisType) {
    $("#auditStatus").val(thisType);
}

/**
 * 表单检验
 * @returns {Boolean}
 */
function checkForm() {
    var checkNum = $("#checkNum").val() * 1; //质检数量
    var qualitedNum = $("#qualitedNum").val() * 1; //合格数
    var produceNum = $("#produceNum").val() * 1; //不合格数

    var relDocType = $("#relDocType").val();

    // 呆料报废
    if(relDocType == "ZJSQDLBF"){
        var totalSerialChkedNum = countSerialNum();
        if(produceNum != totalSerialChkedNum){
            alert("不报废序列号数量与不报废物料数量必须相等！");
            return false;
        }
    }

    if (qualitedNum > checkNum) {
        alert("合格数不能大于实际检验数量！");
        return false;
    }

    var sumNum = accAdd(produceNum, qualitedNum); //合格数 + 不合格数
    if (sumNum != checkNum) {
        if(relDocType == "ZJSQDLBF"){
            alert("报废数+不报废数(" + sumNum + ") 不等于 实际检验数量(" + checkNum + "),请重新核对数量！");
        }else{
            alert("合格数+不合格数(" + sumNum + ") 不等于 实际检验数量(" + checkNum + "),请重新核对数量！");
        }

        return false;
    }

    //add chenrf 20130615
    /*****************************/
    if ($("#pageType").val() == 'edit') {   //编辑也面(新增页面和编辑页面取数据为不同表)
        //获取id
        var $idArr = $("#ereportequitem").yxeditgrid('getCmpByCol', 'relItemId');
    } else {//新增页面
        $("input[id^='ereportequitem_cmp_id']").removeAttr('name');  //删除物料信息及检验结果id的name属性，防止提交
        //获取id
        $idArr = $("#ereportequitem").yxeditgrid('getCmpByCol', 'id');
    }
    //获取不合格数量
    var $failArr = $("#ereportequitem").yxeditgrid('getCmpByCol', 'produceNum');
    //获取物料编码
    var $productCodeArr = $("#ereportequitem").yxeditgrid('getCmpByCol', 'productCode');

    var msgArr = [];
    for (var i = 0; i < $idArr.length; i++) {
        var relDocId = $($idArr[i]).val();
        var failCount = ($($failArr[i]).val()) * 1;   //填写的不合格数
        var productCode = $($productCodeArr[i]).val();
        var count = $.ajax({
            url: '?model=produce_quality_serialno&action=ajaxCount&relDocId=' + relDocId + '&relDocType=qualityEreport',
            type: 'get',
            async: false,
            error: function() {
                alert('连接服务器失败!');
            }
        }).responseText;

        var error = '物料编号：' + productCode + '\n不合格数：' + failCount + '\n填写序列号总数：' + count;
        if(relDocType == "ZJSQDLBF"){
            error = '物料编号：' + productCode + '\n不报废数：' + failCount + '\n填写序列号总数：' + countSerialNum();
        }

        if (Number(count) != failCount) {
            msgArr.push(error);
        }
        if (Number(count) > failCount) {
            alert(error + '\n请删除多余序列号');
            return false;
        }
    }

    //检验不合格物料信息
    var failureItem = $("#failureItem");
    var rs = true;
    if (failureItem.text() != "") {
        //缓存审核项
        var dimensionNameArr = $("#itemTable").yxeditgrid("getCmpByCol", "dimensionName");

        failureItem.yxeditgrid("getCmpByCol", "serialNo").each(function() {
            //序列号
            if ($(this).val() == "") {
                alert('存在没有填写序列号的不合格物料信息');
                rs = false;
                return false;
            }
            //检验项目检验结果
            if (dimensionNameArr.length > 0) {
                var isAllEmpty = true;
                for (var i = 1; i <= dimensionNameArr.length; i++) {
                    if (failureItem.yxeditgrid("getCmpByRowAndCol", $(this).data('rowNum'), "result" + i).val() != "") {
                        isAllEmpty = false;
                        break;
                    }
                }
                if (isAllEmpty == true) {
                    alert('存在检验结果全部为空的不合格物料信息');
                    rs = false;
                    return false;
                }
            }
        });
    }
    if (rs == false) return false;
    
    //add By weijb 20150714
    //源单类型为换货归还时,当归还物料检验不合格时,需要新增上传图片证据/文本描述等
    if($("#relDocType").val() == "ZJSQYDGH" && $("#produceNum").val() != '0'){
    	if($.trim($("#uploadfileList").html()) == "" || $("#uploadfileList").html() == "暂无任何附件"){
    		alert("当前存在检验不合格的物料,请上传图片证据或文本描述");
    	    return false;
    	}
    }
    
    //确认操作
    if ($("#auditStatus").val() == "WSH") {
        if (msgArr.length > 0) {
            return confirm(msgArr[0] + '\n确认提交此质检报告吗？')
        } else
            return confirm('\n确认提交此质检报告吗？')
    } else {
        if (msgArr.length > 0) {
            return confirm(msgArr[0] + '\n确定保存？')
        }
    }

    return true;
}

/**
 * 表单检验
 * @returns {Boolean}
 */
function checkFormConfirm() {
    var auditStatus = $("#auditStatus").val();
    // 检查让步接收数量
    var gridObj = $("#ereportequitem");
    var needConfirmRbjs = false;
    gridObj.yxeditgrid("getCmpByCol", "receiveNum").each(function(i, n) {
        if(parseInt($(n).val()) == 0){
            needConfirmRbjs = true;
        }
    });

    //确认操作
    if(auditStatus == "RBJS" && needConfirmRbjs){
        return confirm('存在让步接收数量为0的物料,是否提交审批？点击确认,进入审核,点击取消,回到审核页面。');
    }else{
        return confirm('确认审核此报告吗？');
    }
}

//审核页面质检物料处理
function changeAuditStatus() {
    var auditStatus = $("#auditStatus").val();
    if (auditStatus == 'BHG') {
        initBack(1);
    } else {
        initReceive(1);
    }

    //计算一些数量
    countConfirmNum();
}

//页面初始化 - 无设置值
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

//页面初始化 - 无设置值
function initConfirmClear() {
    var auditStatus = $("#auditStatus").val();
    if (auditStatus == 'BHG') {
        initBack(0);
    } else {
        initReceive(0);
    }
}

//初始化不合格
function initBack(isSetValue) {
    var gridObj = $("#ereportequitem");
    //初始化 报检数量
    var cmps = gridObj.yxeditgrid("getCmpByCol", "thisCheckNum");
    var allNum = 0;
    cmps.each(function(i, n) {
        //计算总的数量
        allNum = accAdd($(this).val(), allNum);

        //初始化退回数量
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "backNum").attr("class", "readOnlyTxtMiddle").attr("readonly", true);
        //初始化让步接收数量
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "receiveNum").attr("class", "readOnlyTxtMiddle").attr("readonly", true);
        //初始化正常接收数量
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "passNum").attr("class", "readOnlyTxtMiddle").attr("readonly", true);

        //判断是否需要设置初始值
        if (isSetValue == "1") {
            //初始化退回数量
            gridObj.yxeditgrid("getCmpByRowAndCol", i, "backNum").val($(this).val() * 1);
            //初始化让步接收数量
            gridObj.yxeditgrid("getCmpByRowAndCol", i, "receiveNum").val(0);
            //初始化正常接收数量
            gridObj.yxeditgrid("getCmpByRowAndCol", i, "passNum").val(0);
        }

    });
    //判断是否需要设置初始值
    if (isSetValue == "1") {
        $("#backNum").val(allNum);
        $("#receiveNum").val(0);
        $("#passNum").val(0);
    }
}

//初始化让步接收
function initReceive(isSetValue) {
    var gridObj = $("#ereportequitem");
    //初始化 退回数量
    var cmps = gridObj.yxeditgrid("getCmpByCol", "thisCheckNum");
    var backNumAll = 0;
    var receiveNumAll = 0;
    var passNumAll = 0;
    var backObj, receiveObj;
    cmps.each(function(i, n) {
        //获取合格数
        qualitedNum = gridObj.yxeditgrid("getCmpByRowAndCol", i, "qualitedNum").val();
        //获取不合格数
        produceNum = gridObj.yxeditgrid("getCmpByRowAndCol", i, "produceNum").val();
        //本物料检验数量
        var rowCheckNum = accAdd(qualitedNum, produceNum);

        //初始化正常接收数量
        passObj = gridObj.yxeditgrid("getCmpByRowAndCol", i, "passNum");
        passNumAll = accAdd(passNumAll, passObj.val());
        passObj.attr("class", "txt").attr("readonly", false);

        //初始化让步接收数量
        receiveObj = gridObj.yxeditgrid("getCmpByRowAndCol", i, "receiveNum");
        receiveObj.attr("class", "txt").attr("readonly", false);

        //初始化退回数量
        backObj = gridObj.yxeditgrid("getCmpByRowAndCol", i, "backNum");
        backNumAll = accAdd(backNumAll, backObj.val());
        backObj.attr("class", "txt").attr("readonly", false);

        if (isSetValue == "1") {
            //判断检验数量和报检数量来设置初始值
            passObj.val(accSub(this.value, produceNum));
            backObj.val(produceNum);
        }
    });
    //判断是否需要设置初始值
    if (isSetValue == "1") {
        $("#backNum").val(backNumAll);
        $("#passNum").val(passNumAll);
        $("#receiveNum").val(0);
    }
}
//类型为换货归还时初始化
function initHHGH() {
    $("#auditStatus option[value='RBJS']").remove(); //去掉审核结果的让步接收选项

    var gridObj = $("#ereportequitem");
    gridObj.yxeditgrid("getCmpByCol", "thisCheckNum").each(function(i, n) {
        //获取报检数量
        supportNum = $(this).val();
        //获取合格数量
        var qualitedNum = gridObj.yxeditgrid("getCmpByRowAndCol", i, "qualitedNum");
        //初始化正常接收数量，等于合格数
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "passNum").val(qualitedNum.val());
        //初始化让步接收数量，等于不合格数
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "receiveNum").val(accSub(supportNum, qualitedNum.val()));
        //初始化退回数量,置零
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "backNum").val(accSub(supportNum, supportNum));
    });
}
//类型为生产时初始化
function initSC() {
    $("#auditStatus option[value='RBJS']").remove(); //去掉审核结果的让步接收选项

    var gridObj = $("#ereportequitem");
    gridObj.yxeditgrid("getCmpByCol", "thisCheckNum").each(function(i, n) {
        //获取报检数量
        supportNum = $(this).val();
        //获取合格数量
        var qualitedNum = gridObj.yxeditgrid("getCmpByRowAndCol", i, "qualitedNum");
        //初始化正常接收数量，等于合格数
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "passNum").val(qualitedNum.val());
        //初始化让步接收数量，置零
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "receiveNum").val(accSub(supportNum, supportNum));
        //初始化退回数量，等于不合格数
        gridObj.yxeditgrid("getCmpByRowAndCol", i, "backNum").val(accSub(supportNum, qualitedNum.val()));
    });
}

//源单为生产质检处理
function showDucumentType(){
	// 渲染报告分类
	$("#parentName").yxcombotree({
		hiddenId : 'parentId',
		treeOptions : {
			url : "?model=produce_document_documenttype&action=getTreeDataByParentId&typeId=2"//只能选择"日常报表"下的分类
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
		onshow : "请选择附件文档分类",
		onfocus : "附件文档分类不能为空",
		oncorrect : "附件文档分类有效"
	}).inputValidator({
		min : 1,
		max : 50,
		onerror : "附件文档分类不能为空，请选择"
	});
	// 渲染指引文件
	$("#guideDocName").yxcombotree({
		hiddenId : 'guideDocId',// 隐藏控件id
		nameCol : 'name',
		valueCol : 'id',
		treeOptions : {
			checkable : true,// 多选
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
									html += '<div class="upload"><a title="点击下载" href="index1.php?model=file_uploadfile_management&action=toDownFileById&fileId=' + idArr[i] 
										+ '">' + name + '</a></div>';
								}
							}
						}
					}else{
						html += '<div class="upload"><a title="点击下载" href="index1.php?model=file_uploadfile_management&action=toDownFileById&fileId=' + id 
							+ '">' + treeNode.name + '</a></div>';
					}
					$("#guideDocTr .upload").html(html);
				}
			},
			url : "index1.php?model=produce_document_document&action=getDocuments&parentId=1"// 这里只获取规范文档，分类id为1
		}
	});
}

/**
 * 选择序列号
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
        "选择不报废序列号")
}