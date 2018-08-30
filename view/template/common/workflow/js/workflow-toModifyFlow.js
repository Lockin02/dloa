$(document).ready(function() {
    // 流程归档人
    $("#enterUser").yxselect_user({
        hiddenId: 'enterUserId'
    });
    // 流程创建人
    $("#creator").yxselect_user({
        hiddenId: 'creatorId'
    });

    $('#editFlowBtn').click(function(){
        var param = $('#modifyFlowForm').serialize();
        var url = "?model=common_workflow_workflow&action=modifyFlow";
        var backData = $.ajax({
            type : "POST",
            data: param,
            url : url,
            dataType : 'json',
            async : false
        }).responseText;
        var data = eval("(" + backData + ")");
        if(data.msg == 'ok'){
            if($("#modifyType").val() == "edit"){
                alert("编辑成功!");
            }else if($("#modifyType").val() == "add"){
                alert("添加成功!");
            }
            reload();
        }else{
            alert("操作失败!");
            reload();
        }
    });


    var specName_logicOpts = [];
    var logicOpts = $.ajax({
        type : "POST",
        url : "?model=common_workflow_workflow&action=getLogicOpts",
        dataType : 'json',
        async : false
    }).responseText;
    var logicOptsData = eval("(" + logicOpts + ")");


    // ======= 流程步骤 ======= //
    var colConfig = [{
        display: 'ID',
        name: 'ID',
        type: 'hidden'
    },{
        display: '步骤序号',
        width : '20px',
        name: 'PRCS_ID',
        tclass: 'txt',
        type: 'hidden'
    },{
        display: '步骤名称',
        width : '120px',
        name: 'PRCS_NAME',
        tclass: 'txt'
    },{
        display: '办理人',
        name: 'show_ProcessUserName',
        tclass: 'txt',
        process : function($input, rowData) {
            var rowNum = $input.data("rowNum");
            var g = $input.data("grid");
            $input.attr('name','');
            $input.yxselect_user({
                hiddenId : g.el.attr('id') + '_cmp_PRCS_USER' + rowNum,
                mode : 'check'
            });
        }
    },{
        display: '办理人ID',
        name: 'PRCS_USER',
        tclass: 'txt',
        type: 'hidden'
    },{
        display: '特殊办理人类型',
        name: 'SPEC_TYPE',
        width : '130px',
        type: 'select',
        options : [{'name':'用户组','value':'0'},{'name':'用户ID','value':'1'}],
        process : function (html,rowData,q,qq,qwe) {
            var rowNum = $(q).attr("rownum");
            $(html).change(function () {
                var typeId = $(html).val();
                var userSltId = 'innerStepsTable_cmp_show_SpecName'+rowNum;
                var optsId = 'innerStepsTable_cmp_show_SpecNameOpts'+rowNum;
                var spcValId = 'innerStepsTable_cmp_PRCS_SPEC'+rowNum;
                $("#"+spcValId).val('');
                $.each($("#"+optsId).children("option"),function(i,item){
                    $(item).attr("selected",false);
                });
                switch (typeId){
                    case '1':
                        $("#"+optsId).hide();
                        $("#"+userSltId).show();
                        $("#"+userSltId).nextAll('span').show();
                        break;
                    case '0':
                        $("#"+optsId).show();
                        $("#"+userSltId).hide();
                        $("#"+userSltId).val('');
                        $("#"+userSltId).nextAll('span').hide();
                        break;
                }
            });
        }
    },{
        display: '特殊办理人',
        name: 'show_SpecName',
        tclass: 'txt',
        process : function($input, rowData) {
            var rowNum = $input.data("rowNum");
            var g = $input.data("grid");
            $input.attr('name','');

            // 用户ID选项框
            $input.yxselect_user({
                hiddenId : g.el.attr('id') + '_cmp_PRCS_SPEC' + rowNum,
                mode : 'check'
            });

            // 用户组选项
            var userSltId = 'innerStepsTable_cmp_show_SpecName'+rowNum;
            var optsId = 'innerStepsTable_cmp_show_SpecNameOpts'+rowNum;
            var opts = '<option value=""> ..请选择.. </option>';
            var spacArr = (!rowData || rowData.PRCS_SPEC == '')? [] : rowData.PRCS_SPEC.split(',');
            if(logicOptsData.msg = 'ok'){
                $.each(logicOptsData.data,function(i,item){
                    var inArr = false;
                    $.each(spacArr,function(spci,spcVal){
                        if(spcVal === item.code){
                            inArr = true;
                        }
                    });
                    if(inArr){
                        opts += '<option value="'+item.code+'" selected> '+item.name+' </option>';
                    }else{
                        opts += '<option value="'+item.code+'"> '+item.name+' </option>';
                    }
                });
            }
            // var sltOpts = '<select id="'+optsId+'" class="txtmiddle" name="" style="width: 100px;height:60px;"  multiple="multiple">'+opts+'</select>';
            var sltOpts = '<select id="'+optsId+'" class="txtmiddle" name="" style="width: 100px;">'+opts+'</select>';
            $input.before(sltOpts);
            $("#"+optsId).change(function(){
                var spcValId = 'innerStepsTable_cmp_PRCS_SPEC'+rowNum;
                var sltedOpts = $("#"+optsId).val().toString();
                $("#"+spcValId).val(sltedOpts);
            });

            // 根据类型显示对应的信息
            if(!rowData || rowData.SPEC_TYPE == 0 || rowData.SPEC_TYPE == null || rowData.SPEC_TYPE == ''){// 默认是用户组
                $("#"+optsId).show();
                $("#"+userSltId).hide();
                $("#"+userSltId).nextAll('span').hide();
            }else{
                $("#"+optsId).hide();
                $("#"+userSltId).show();
                $("#"+userSltId).nextAll('span').show();
            }
        }
    },{
        display: '特殊办理人ID',
        name: 'PRCS_SPEC',
        tclass: 'txt',
        type: 'hidden'
    },{
        display: '办理人查询脚本',
        name: 'executorSearchSql',
        width : '220px',
        tclass: 'txt'
    },{
        display: '办理人查询类',
        name: 'executorSearchClass',
        tclass: 'txt'
    },{
        display: '办理人查询函数',
        name: 'executorSearchFun',
        tclass: 'txt'
    },{
        display: '用户自定义办理人',
        name: 'customize',
        width : '50px',
        type: 'select',
        options : [{'name':'否','value':'0'},{'name':'是','value':'1'}],
        process : function (html, rowData) {
            if(rowData){
                return rowData.customize;
            }
        }
    },{
        display: '节点判定脚本',
        width : '220px',
        name: 'decisionSql',
        tclass: 'txt'
    },{
        display: '节点判定类',
        name: 'decisionClass',
        tclass: 'txt'
    },{
        display: '节点判定函数',
        name: 'decisionFun',
        tclass: 'txt'
    }];

    if($("#modifyType").val() == "edit"){
        $("#innerStepsTable").yxeditgrid({
            url: '?model=common_workflow_workflow&action=listSteps',
            objName: 'step',
            title: '流程步骤信息',
            param: {'flowId': $('#flowId').val()},
            tableClass: 'form_in_table',
            colModel: colConfig
//                event: {
//                    'reloadData': function() {
//                    },
//                    removeRow: function(t, rowNum, rowData) {
//                    },
//                    clickAddRow: function (e, rowNum, g) {
//                    }
//                }
        });
    }else if($("#modifyType").val() == "add"){
        $("#innerStepsTable").yxeditgrid({
            objName: 'step',
            title: '流程步骤信息',
            tableClass: 'form_in_table',
            colModel: colConfig
        });
    }

    $("#innerStepsTable").width(document.documentElement.clientWidth - 30);
});

// 提交后页面刷新
function reload(){
    var formName = $(parent.document).find('#modifyFlow_Box iframe').attr('data-formName');
    var formId = $(parent.document).find('#modifyFlow_Box iframe').attr('data-formId');
    parent.reloadList(formName,formId);
};

// 显示流程步骤
function showStep(stepImgId,tbId,trId) {
    showAndHide(stepImgId,tbId);
    if($("#"+trId).css('display') == 'none'){
        $("#"+trId).show();
    }else{
        $("#"+trId).hide();
    }
}