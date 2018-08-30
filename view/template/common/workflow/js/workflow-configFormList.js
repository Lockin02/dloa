// 弹窗的宽高
var boxWidth = 1000;
var boxHeight = 700;

// 初始化页面
$(document).ready(function() {
    allFormList();
});

// 读取左侧表单列表
function allFormList() {
    $("#formData_list").tree({
        url: "?model=common_workflow_workflow&action=listForms",
        lines: true,
//		checkbox: true,
        onlyLeafCheck: true,
        onClick: function(node) {
            cleanPage();
            workFlowList(node.text,node.id);
        },onLoadSuccess: function (t, datas) {
            workFlowList(datas[0].text,datas[0].id);
        }
    });
}


function workFlowList(formName,formId){
    var url = "index1.php?model=common_workflow_workflow&action=getWfByFormId&formId="+formId;
    $('#openFormName').val(formName);
    $('#openFormId').val(formId);
    $("#flow-list").datagrid({
        title: "《"+formName+"》的相关流程:",
        url: url,
        singleSelect: true,
        fitColumns: true,
        pageSize: 20,
        pagination: true,
        fit: true,   //自适应大小
        columns: [[
            { field: "FLOW_ID", title: "id", align: "center", hidden : false, width: 30},
            { field: "FORM_ID", title: "表单类型ID", hidden : true, width: 100},
            { field: "FLOW_NAME", title: "审批流程", align: "center", width: 200 },
            { field: "className", title: "流程类型", align: "center", width: 90 },
            { field: "flowType", title: "流程类别", align: "center", width: 90 },
            { field: "MinMoney", title: "金额下限", align: "center", width: 80 },
            { field: "MaxMoney", title: "金额上限", align: "center", width: 80 },
            { field: "EnterUserName", title: "归档人", align: "center", width: 100 },
            { field: "Enter_user", title: "归档人id", hidden : true, width: 100},
            { field: "CreatorName", title: "创建人", align: "center", width: 100 },
            { field: "Creator", title: "创建人id", hidden : true, width: 100},
            { field: "Idate", title: "创建时间", align: "center", width: 100 },
            { field: "operation", title: "操作", align: "center", width: 200, formatter: function (value, row, index)
            {
                var str = "";
                // str += "<a href=\"#\" onclick=\"viewFlow(" + row.FLOW_ID + ")\">查看</a>";
                // str += "&nbsp;&nbsp;&nbsp;&nbsp;";
                str += "<a href=\"#\" onclick=\"editFlow(" + row.FLOW_ID + ")\">编辑</a>";
                str += "&nbsp;&nbsp;&nbsp;&nbsp;";
                str += "<a href=\"#\" onclick=\"delFlow('" + row.FLOW_ID +"','"+formName+"','"+formId+"')\">删除</a>";
                return str;
            } }
        ]],
        toolbar: [
        //          {
        //     iconCls: "icon-tip",
        //     text: "查看表单",
        //     handler: function () {
        //         cleanPage();
        //         viewForm(formId);
        //         // console.log(formId+"查看表单");
        //     }
        // },
        {
            iconCls: "icon-edit",
            text: "编辑表单",
            handler: function () {
                cleanPage();
                editForm(formId);
                // console.log(formId+"编辑表单");
            }
        },{
            iconCls: "icon-add",
            text: "新增流程",
            handler: function () {
                cleanPage();
                addFlow(formName,formId);
            }
        }],
        onLoadSuccess: function () {
        },
    });
}

/**
 * 用户触发函数
 */
// 编辑表单
function editForm(formId){
    var result = getForm(formId);
    if (result.msg == 'ok') {
        var data = result.data;
        // 基本信息配置
        $('#formName').val(data['FORM_NAME']);
        $('#formId').val(data['FORM_ID']);
        $('input[name="workflow[isChangeFlow]"]').each(function(){
            this.checked = false;
            var isChangeFlow = (data['isChangeFlow'] == 1)? 1 : 0;
            if(this.value == isChangeFlow){
                this.checked = true;
            }
        });
        $('#encryptKey').val(data['encryptKey']);
        $('#changeCode').val(data['changeCode']);
        $('#changeTrueFlow').val(data['changeTrueFlow']);
        $('#changeFilterCode').val(data['changeFilterCode']);
        $('#viewUrl').val(data['viewUrl']);

        // 获取摘要配置
        $('#infomation').val(data['infomation']);
        $('#infomationSql').val(data['infomationSql']);
        $('#infomationClass').val(data['infomationClass']);
        $('#infomationFun').val(data['infomationFun']);

        // 审批配置
        $('#mailCode').val(data['mailCode']);
        $('input[name="workflow[callbackEachStep]"]').each(function(){
            this.checked = false;
            var callbackEachStep = (data['callbackEachStep'] == 1)? 1 : 0;
            if(this.value == callbackEachStep){
                this.checked = true;
            }
        });
        $('#passSql').val(data['PASS_SQL']);
        $('#passCallbackClass').val(data['passCallbackClass']);
        $('#passCallbackFun').val(data['passCallbackFun']);
        $('#disPassSql').val(data['DISPASS_SQL']);
        $('#dispassCallbackClass').val(data['dispassCallbackClass']);
        $('#dispassCallbackFun').val(data['dispassCallbackFun']);

        // 显示弹框
        $('#editForm_Box').show();
        $('#editForm_Box').removeClass("hidden").dialog({
            title: "编辑表单:"+data['FORM_NAME'],
            closed: false,
            width: boxWidth,
            height: boxHeight
        });
    }
}

// 查看表单
function viewForm(formId){
    var result = getForm(formId);
    if (result.msg == 'ok') {
        var data = result.data;
        // 基本信息配置
        $('#view_formName').text(data['FORM_NAME']);
        $('#view_formId').text(data['FORM_ID']);
        var isChangeFlow = (data['isChangeFlow'] == 1)? '是' : '否';
        $('#view_isChangeFlow').text(data['isChangeFlow']);
        $('#view_encryptKey').text(data['encryptKey']);
        $('#view_changeCode').text(data['changeCode']);
        $('#view_changeTrueFlow').text(data['changeTrueFlow']);
        $('#view_changeFilterCode').text(data['changeFilterCode']);
        $('#view_viewUrl').text(data['viewUrl']);

        // 获取摘要配置
        $('#view_infomation').text(data['infomation']);
        $('#view_infomationSql').text(data['infomationSql']);
        $('#view_infomationClass').text(data['infomationClass']);
        $('#view_infomationFun').text(data['infomationFun']);

        // 审批配置
        $('#view_mailCode').text(data['mailCode']);
        var callbackEachStep = (data['callbackEachStep'] == 1)? '是' : '否';
        $('#view_callbackEachStep').text(callbackEachStep);
        $('#view_passSql').text(data['PASS_SQL']);
        $('#view_passCallbackClass').text(data['passCallbackClass']);
        $('#view_passCallbackFun').text(data['passCallbackFun']);
        $('#view_disPassSql').text(data['DISPASS_SQL']);
        $('#view_dispassCallbackClass').text(data['dispassCallbackClass']);
        $('#view_dispassCallbackFun').text(data['dispassCallbackFun']);

        // 显示弹框
        $('#viewForm_Box').show();
        $('#viewForm_Box').removeClass("hidden").dialog({
            title: data['FORM_NAME'],
            closed: false,
            width: boxWidth,
            height: boxHeight
        });
    }
}

// 添加表单流程
function addFlow(formName,formId){
    // 显示弹框

    // $('#modifyType').val("add");
    $('#modifyFlow_Box iframe').attr('data-formName',formName);
    $('#modifyFlow_Box iframe').attr('data-formId',formId);
    $('#modifyFlow_Box iframe').attr("src","?model=common_workflow_workflow&action=toModifyFlow&modifyType=Add&formId="+formId);
    $('#modifyFlow_Box').show();
    $('#modifyFlow_Box').removeClass("hidden").dialog({
        title: '添加表单流程',
        closed: false,
        width: boxWidth,
        height: boxHeight
    });
}

// 查看表单流程
function viewFlow(flowId){
    cleanPage();//先清除页面所以弹框
    var flowArr = getFlowByFlowId(flowId);
    $.each(flowArr,function(i,item){
        if($("#flowView_"+i)){
            $("#flowView_"+i).text(item);
        }
    });

    // 获取步骤信息
    var stepsArr = getFlowStepsByFlowId(flowId);
    console.log(stepsArr);
    var stepsHtml = '';
    $("#flowSteps").html(stepsHtml);
    // 显示弹框
    $('#viewFlow_Box').show();
    $('#viewFlow_Box').removeClass("hidden").dialog({
        title: '查看表单流程',
        closed: false,
        width: boxWidth,
        height: boxHeight
    });
}

// 编辑表单流程
function editFlow(flowId){
    cleanPage();//先清除页面所以弹框
    $('#modifyFlow_Box iframe').attr('data-formName',$('#openFormName').val());
    $('#modifyFlow_Box iframe').attr('data-formId',$('#openFormId').val());
    $('#modifyFlow_Box iframe').attr("src","?model=common_workflow_workflow&action=toModifyFlow&modifyType=Edit&flowId="+flowId);
    $('#modifyFlow_Box').show();
    $('#modifyFlow_Box').removeClass("hidden").dialog({
        title: '编辑表单流程',
        closed: false,
        width: boxWidth,
        height: boxHeight
    });
}

// 删除表单流程
function delFlow(flowId,formName,formId){
    cleanPage();//先清除页面所以弹框
    if(confirm("确定要删除该流程吗?")){
        var backData = $.ajax({
            type : "POST",
            data: {
                flowId : flowId
            },
            url : "?model=common_workflow_workflow&action=delFlowById",
            async : false
        }).responseText;
        if(backData == "ok"){
            alert("删除成功!");
        }else{
            alert("删除失败!");
        }
        reloadList(formName,formId);
    }
}

/**
 * 系统执行函数
 */
// 清理页面
function cleanPage(){
    // 如果存在dialog窗口,则关闭
    if($('#editForm_Box').parent('.panel').css("display") == 'block'){
        $('#editForm_Box').hide();
        $('#editForm_Box').dialog({
            closed: true
        })
    }
    if($('#viewForm_Box').parent('.panel').css("display") == 'block'){
        $('#viewForm_Box').hide();
        $('#viewForm_Box').dialog({
            closed: true
        })
    }
    if($('#viewFlow_Box').parent('.panel').css("display") == 'block'){
        $('#viewFlow_Box').hide();
        $('#viewFlow_Box').dialog({
            closed: true
        })
    }
    if($('#modifyFlow_Box').parent('.panel').css("display") == 'block'){
        $('#modifyFlow_Box').hide();
        $('#modifyFlow_Box').dialog({
            closed: true
        })
    }
}

// 刷新页面列表
function reloadList(formName,formId){
    cleanPage();
    workFlowList(formName,formId);
}

// 根据表单ID获取表单信息
function getForm(formId){
    var backData = $.ajax({
        type : "POST",
        data: {
            id : formId
        },
        url : "?model=common_workflow_workflow&action=getFormById",
        dataType : 'json',
        async : false
    }).responseText;
    var data = eval("(" + backData + ")");
    return data;
}

// 根据流程ID获取审批流程数据
function getFlowByFlowId(flowId){
    var backData = $.ajax({
        type : "POST",
        data: {
            flowId : flowId
        },
        url : "?model=common_workflow_workflow&action=getFlowById",
        dataType : 'json',
        async : false
    }).responseText;
    var data = eval("(" + backData + ")");
    return (data.length > 0)? data[0] : '';
}

// 根据流程ID获取审批流程步骤数据
function getFlowStepsByFlowId(flowId){
    var backData = $.ajax({
        type : "POST",
        data: {
            flowId : flowId
        },
        url : "?model=common_workflow_workflow&action=listSteps",
        dataType : 'json',
        async : false
    }).responseText;
    var data = eval("(" + backData + ")");
    return (data.length > 0)? data[0] : '';
}