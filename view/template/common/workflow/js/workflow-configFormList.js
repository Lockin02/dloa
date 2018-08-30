// �����Ŀ��
var boxWidth = 1000;
var boxHeight = 700;

// ��ʼ��ҳ��
$(document).ready(function() {
    allFormList();
});

// ��ȡ�����б�
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
        title: "��"+formName+"�����������:",
        url: url,
        singleSelect: true,
        fitColumns: true,
        pageSize: 20,
        pagination: true,
        fit: true,   //����Ӧ��С
        columns: [[
            { field: "FLOW_ID", title: "id", align: "center", hidden : false, width: 30},
            { field: "FORM_ID", title: "������ID", hidden : true, width: 100},
            { field: "FLOW_NAME", title: "��������", align: "center", width: 200 },
            { field: "className", title: "��������", align: "center", width: 90 },
            { field: "flowType", title: "�������", align: "center", width: 90 },
            { field: "MinMoney", title: "�������", align: "center", width: 80 },
            { field: "MaxMoney", title: "�������", align: "center", width: 80 },
            { field: "EnterUserName", title: "�鵵��", align: "center", width: 100 },
            { field: "Enter_user", title: "�鵵��id", hidden : true, width: 100},
            { field: "CreatorName", title: "������", align: "center", width: 100 },
            { field: "Creator", title: "������id", hidden : true, width: 100},
            { field: "Idate", title: "����ʱ��", align: "center", width: 100 },
            { field: "operation", title: "����", align: "center", width: 200, formatter: function (value, row, index)
            {
                var str = "";
                // str += "<a href=\"#\" onclick=\"viewFlow(" + row.FLOW_ID + ")\">�鿴</a>";
                // str += "&nbsp;&nbsp;&nbsp;&nbsp;";
                str += "<a href=\"#\" onclick=\"editFlow(" + row.FLOW_ID + ")\">�༭</a>";
                str += "&nbsp;&nbsp;&nbsp;&nbsp;";
                str += "<a href=\"#\" onclick=\"delFlow('" + row.FLOW_ID +"','"+formName+"','"+formId+"')\">ɾ��</a>";
                return str;
            } }
        ]],
        toolbar: [
        //          {
        //     iconCls: "icon-tip",
        //     text: "�鿴��",
        //     handler: function () {
        //         cleanPage();
        //         viewForm(formId);
        //         // console.log(formId+"�鿴��");
        //     }
        // },
        {
            iconCls: "icon-edit",
            text: "�༭��",
            handler: function () {
                cleanPage();
                editForm(formId);
                // console.log(formId+"�༭��");
            }
        },{
            iconCls: "icon-add",
            text: "��������",
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
 * �û���������
 */
// �༭��
function editForm(formId){
    var result = getForm(formId);
    if (result.msg == 'ok') {
        var data = result.data;
        // ������Ϣ����
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

        // ��ȡժҪ����
        $('#infomation').val(data['infomation']);
        $('#infomationSql').val(data['infomationSql']);
        $('#infomationClass').val(data['infomationClass']);
        $('#infomationFun').val(data['infomationFun']);

        // ��������
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

        // ��ʾ����
        $('#editForm_Box').show();
        $('#editForm_Box').removeClass("hidden").dialog({
            title: "�༭��:"+data['FORM_NAME'],
            closed: false,
            width: boxWidth,
            height: boxHeight
        });
    }
}

// �鿴��
function viewForm(formId){
    var result = getForm(formId);
    if (result.msg == 'ok') {
        var data = result.data;
        // ������Ϣ����
        $('#view_formName').text(data['FORM_NAME']);
        $('#view_formId').text(data['FORM_ID']);
        var isChangeFlow = (data['isChangeFlow'] == 1)? '��' : '��';
        $('#view_isChangeFlow').text(data['isChangeFlow']);
        $('#view_encryptKey').text(data['encryptKey']);
        $('#view_changeCode').text(data['changeCode']);
        $('#view_changeTrueFlow').text(data['changeTrueFlow']);
        $('#view_changeFilterCode').text(data['changeFilterCode']);
        $('#view_viewUrl').text(data['viewUrl']);

        // ��ȡժҪ����
        $('#view_infomation').text(data['infomation']);
        $('#view_infomationSql').text(data['infomationSql']);
        $('#view_infomationClass').text(data['infomationClass']);
        $('#view_infomationFun').text(data['infomationFun']);

        // ��������
        $('#view_mailCode').text(data['mailCode']);
        var callbackEachStep = (data['callbackEachStep'] == 1)? '��' : '��';
        $('#view_callbackEachStep').text(callbackEachStep);
        $('#view_passSql').text(data['PASS_SQL']);
        $('#view_passCallbackClass').text(data['passCallbackClass']);
        $('#view_passCallbackFun').text(data['passCallbackFun']);
        $('#view_disPassSql').text(data['DISPASS_SQL']);
        $('#view_dispassCallbackClass').text(data['dispassCallbackClass']);
        $('#view_dispassCallbackFun').text(data['dispassCallbackFun']);

        // ��ʾ����
        $('#viewForm_Box').show();
        $('#viewForm_Box').removeClass("hidden").dialog({
            title: data['FORM_NAME'],
            closed: false,
            width: boxWidth,
            height: boxHeight
        });
    }
}

// ��ӱ�����
function addFlow(formName,formId){
    // ��ʾ����

    // $('#modifyType').val("add");
    $('#modifyFlow_Box iframe').attr('data-formName',formName);
    $('#modifyFlow_Box iframe').attr('data-formId',formId);
    $('#modifyFlow_Box iframe').attr("src","?model=common_workflow_workflow&action=toModifyFlow&modifyType=Add&formId="+formId);
    $('#modifyFlow_Box').show();
    $('#modifyFlow_Box').removeClass("hidden").dialog({
        title: '��ӱ�����',
        closed: false,
        width: boxWidth,
        height: boxHeight
    });
}

// �鿴������
function viewFlow(flowId){
    cleanPage();//�����ҳ�����Ե���
    var flowArr = getFlowByFlowId(flowId);
    $.each(flowArr,function(i,item){
        if($("#flowView_"+i)){
            $("#flowView_"+i).text(item);
        }
    });

    // ��ȡ������Ϣ
    var stepsArr = getFlowStepsByFlowId(flowId);
    console.log(stepsArr);
    var stepsHtml = '';
    $("#flowSteps").html(stepsHtml);
    // ��ʾ����
    $('#viewFlow_Box').show();
    $('#viewFlow_Box').removeClass("hidden").dialog({
        title: '�鿴������',
        closed: false,
        width: boxWidth,
        height: boxHeight
    });
}

// �༭������
function editFlow(flowId){
    cleanPage();//�����ҳ�����Ե���
    $('#modifyFlow_Box iframe').attr('data-formName',$('#openFormName').val());
    $('#modifyFlow_Box iframe').attr('data-formId',$('#openFormId').val());
    $('#modifyFlow_Box iframe').attr("src","?model=common_workflow_workflow&action=toModifyFlow&modifyType=Edit&flowId="+flowId);
    $('#modifyFlow_Box').show();
    $('#modifyFlow_Box').removeClass("hidden").dialog({
        title: '�༭������',
        closed: false,
        width: boxWidth,
        height: boxHeight
    });
}

// ɾ��������
function delFlow(flowId,formName,formId){
    cleanPage();//�����ҳ�����Ե���
    if(confirm("ȷ��Ҫɾ����������?")){
        var backData = $.ajax({
            type : "POST",
            data: {
                flowId : flowId
            },
            url : "?model=common_workflow_workflow&action=delFlowById",
            async : false
        }).responseText;
        if(backData == "ok"){
            alert("ɾ���ɹ�!");
        }else{
            alert("ɾ��ʧ��!");
        }
        reloadList(formName,formId);
    }
}

/**
 * ϵͳִ�к���
 */
// ����ҳ��
function cleanPage(){
    // �������dialog����,��ر�
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

// ˢ��ҳ���б�
function reloadList(formName,formId){
    cleanPage();
    workFlowList(formName,formId);
}

// ���ݱ�ID��ȡ����Ϣ
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

// ��������ID��ȡ������������
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

// ��������ID��ȡ�������̲�������
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