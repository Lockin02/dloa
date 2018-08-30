/**
 * Created by kuangzw on 16-12-26.
 */
(function ($) {
    //默认属性
    var defaults = {
        title: "审批情况",
        mainForm: "mainForm", // 表单
        mainFormObj: "mainForm", // 表单对象
        formName: "",
        examCode: "",
        type: "view",
        boId: 0, // 业务单id - 标准参数，实际上穿到后台的不是这个键名
        boType: "", // 业务类型 - 标准参数，实际上穿到后台的不是这个键名
        spid: 0, // 流程步骤ID
        sid: 0 // session id
    };

    $.fn.workflow = function (options) {
        //合并属性
        $.extend(defaults, options);

        // 初始化表单对象
        defaults.mainFormObj = $('<form id="' + defaults.mainForm + '"></form>');

        // 初始化检测
        initCheck($(this), defaults);

        //支持选择器以及链式操作
        return this.each(function () {
            init($(this), defaults);
        });
    };

    // 初始化检测
    var initCheck = function(g, p) {

    };

    // 初始化方法
    var init = function(g, p) {
        switch(p.type) {
            case "build" : // 审批流创建
                initBuild(g, p);
                break;
            case "audit" : // 审批
                initAudit(g, p);
                break;
            case "view" : // 查看以及默认读取表单查看

            default:
                initView(g, p);
        }
    };

    // 审批发起页面
    var initBuild = function(g, p) {
        // 构建流程
        var defaultFlowId = buildFlow(g, p);

        // 构建流程步骤
        buildProcess(g, p, defaultFlowId);

        // 构建按钮以及提交事件
        buildBtn(g, p);

        g.append(p.mainFormObj);
    };

    // 构建流程
    var buildFlow = function(g, p) {
        // 构建可用流程部分
        var flow = $('<div class="demo-item" id="flowList"></div>');

        // 标题
        flow.append('<p class="demo-desc">' + p.formName + '</p>');

        // 获取可用流程
        var flowList = getFlow(p);
        var div = $('<div class="demo-block"></div>');
        var ul = $('<ul class="ui-list ui-list-text ui-list-radio ui-border-tb"></ul>');
        var flowId = 0;

        console.log(flowList);

        // 循环创建可用流程列表
        for (var i = 0; i < flowList.length; i++) {
            var li = $('<li class="ui-border-t"></li>');
            var pEle = $('<p>' + flowList[i].flowName + '</p>');
            var label = $('<label class="ui-radio"></label>');
            var radio = $('<input type="radio" name="flowId" value="' + flowList[i].flowId + '"/>');

            // 加载默认的审批流步骤
            if (i == 0) {
                radio.attr('checked', true);
                flowId = flowList[i].flowId;
            }
            // 绑定监听事件
            radio.on('change', {g: g, p: p, flowId: flowList[i].flowId}, function(e) {
                buildProcess(e.data.g, e.data.p, e.data.flowId);
            });

            // 开始组装行数据
            label.append(radio);
            li.append(label).append(pEle);
            ul.append(li);
        }
        div.append(ul);
        flow.append(div);
        g.append(flow);
        return flowId;
    }

    // 可审核流程获取
    var getFlow = function(p) {
        var flowList = [];
        // 初始化数据列表
        $.ajax({
            type: 'POST',
            // url: './jd?sid=' + p.sid + '&cmd=com.youngheart.apps.mobile.workflow_getFlowList',
            url: '?model=common_workflow_workflow&action=getFlow',
            data: {formName: p.formName},
            dataType: 'json',
            async: false,
            success: function(data) {
                if (data.result && data.result == "error") {
                    // 异常提示
                } else {
                    flowList = data;
                }
            }
        });
        return flowList;
        /**
         return [{
            flowName: '1000元以下审批',
            flowId: '1'
        }, {
            flowName: '1000元及以上审批',
            flowId: '2'
        }];
         **/
    };

    // 构建对应流程的步骤
    var buildProcess = function(g, p, flowId) {
        // 判定是否已经渲染过流程，如果有，则清空重新渲染
        var process = $("#process");
        var realAppend = false; // 是否需要插入流程步骤div
        if (process.length == 0) {
            process = $('<div class="demo-item" id="process"></div>');
            realAppend = true;
        } else {
            process.empty();
        }

        // 获取流程数据
        var processList = getProcess(g, p, flowId);

        // 标题
        process.append('<p class="demo-desc">流程步骤</p>');
        var ul = $('<ul class="ui-list ui-list-text ui-list-radio ui-border-tb"></ul>');

        // ul载入一些系统需要字段
        ul.append('<input type="hidden" name="flowId" value="' + flowId + '">')
            .append('<input type="hidden" name="formName" value="' + p.formName + '">')
            .append('<input type="hidden" name="examCode" value="' + p.examCode + '">')
            .append('<input type="hidden" name="billId" value="' + p.billId + '">');

        // 循环生成列表
        for (var i = 0; i < processList.length; i++) {
            var j = i + 1;
            ul.append('<li class="ui-border-t">' +
                '<div class="ui-list-info">' +
                '<p class="ui-nowrap">第' + j + '步 ' + processList[i].stepType + '</p>' +
                '<h4 class="ui-nowrap">' + processList[i].stepName + '</h4>' +
                '<input type="hidden" name="steps[' + j + '][stepName]" value="' + processList[i].stepName + '">' +
                '<input type="hidden" name="steps[' + j + '][stepId]" value="' + processList[i].stepId + '">' +
                '<input type="hidden" name="steps[' + j + '][stepType]" value="' + processList[i].stepType + '">' +
                '<input type="hidden" name="steps[' + j + '][stepUserId]" value="' + processList[i].stepUserId + '">' +
                '<input type="hidden" name="steps[' + j + '][stepUser]" value="' + processList[i].stepUser + '">' +
                '</div>' +
                '<div class="ui-list-action">' + processList[i].stepUser + '</div>' +
                '</li>');
        }
        process.append(ul);
        if (realAppend == true) {
            p.mainFormObj.append(process);
        }
    };

    // 获取流程步骤
    var getProcess = function(g, p, flowId) {
        var process = [];
        // 初始化数据列表
        $.ajax({
            type: 'POST',
            // url: './jd?sid=' + p.sid + '&cmd=com.youngheart.apps.mobile.workflow_getProcess',
            url: '?model=common_workflow_workflow&action=getProcessViewJson',
            data: {"process[flowId]": flowId},
            dataType: 'json',
            async: false,
            success: function(data) {
                if (data.result && data.result == "error") {
                    // 异常提示
                } else {
                    process = data.data.steps;
                }
            }
        });
        console.log(process);
        return process;
        /**
         return [{
            stepName: '部门领导审批',
            stepId: '1',
            stepType: '混签',
            stepUserId: 'admin,admin2',
            stepUser: '名字叫ADMIN,管理员2'
        }, {
            stepName: '财务会计审批',
            stepId: '2',
            stepType: '混签',
            stepUserId: 'guoz',
            stepUser: '郭靖'
        }, {
            stepName: '财务领导审批',
            stepId: '3',
            stepType: '混签',
            stepUserId: 'oy',
            stepUser: '欧阳锋'
        }];
         **/
    };

    var buildBtn = function(g, p) {
        var btn = $('<div class="demo-item" id="btn"></div>');

        // 标题
        btn.append('<p class="demo-desc">通知设置</p>');

        // 内容显示域
        var div = $('<div class="demo-block"></div>');

        // 通知设置
        div.append('<div class="ui-form ui-border-t">' +
            '<div class="ui-form-item ui-form-item-switch ui-border-b">' +
            '<p>' +
            '通知下一步审批人' +
            '</p>' +
            '<label class="ui-switch">' +
            '<input type="checkbox" name="isSendNotify" checked="checked" value="y"/>' +
            '</label>' +
            '</div>' +
            '</div>');
        btn.append(div);

        // 按钮div
        var btnDvi = $('<div class="ui-btn-group"></div>');

        // 提交实现
        var subBtn = $('<button class="ui-btn-lg ui-btn-primary" id="sub" type="button">提交</button>')
            .on('click', {g: g, p: p}, function() {
                subApply(g, p);
            }
        );

        // 返回实现
        var backBtn = $('<button class="ui-btn-lg" id="back" type="button">返回</button>').on('click', function() {
            window.history.back();
            return false;
        });

        btnDvi.append(subBtn).append(backBtn);
        btn.append(btnDvi);

        p.mainFormObj.append(btn);
    }

    // 单据提交表单 - 生成流程
    var subApply = function(g, p) {
        // 先打开loading
        var eloading = $.loading({content:'加载中...'});
        
        var formData = p.mainFormObj.serializeObject();
        // 初始化数据列表
        $.ajax({
            type: 'POST',
            // url: './jd?sid=' + p.sid + '&cmd=com.youngheart.apps.mobile.workflow_build',
            url: '?model=common_workflow_workflow&action=saveAudit',
            data: formData,
            dataType: 'json',
            async: false,
            success: function(resultdt) {
                var data = resultdt.data;
                if (resultdt.msg != 'ok' && data.error > 0) {
                    $.tips({
                        content:data.result,
                        stayTime:2000,
                        type:"error"
                    });
                } else {
                    $.tips({
                        content:'提交成功',
                        stayTime:2000,
                        type:"success"
                    });

                    setTimeout(function() {
                        $(".ui-icon-return").trigger('click');
                    }, 2000);
                }
            }
        });
    };

    // 审批渲染
    var initAudit = function(g, p) {
        // 内容显示域
        var funItem = $('<div class="demo-item"></div>');

        // 办理部分块
        var funBlock = $('<div class="ui-form ui-border-t"></div>');

        // 标题
        funBlock.append('<div class="ui-form-item ui-form-item-switch ui-border-b">' +
            '<p>' +
            '审批结果' +
            '</p>' +
            '</div>' +
            '<div class="ui-form-item ui-form-item-radio ui-border-b">' +
            '<label class="ui-radio">' +
            '<input type="radio" name="wf_result" checked="checked" value="ok"/>' +
            '</label>' +
            '<p>同意</p>' +
            '</div>' +
            '<div class="ui-form-item ui-form-item-radio ui-border-b">' +
            '<label class="ui-radio">' +
            '<input type="radio" name="wf_result" value="no"/>' +
            '</label>' +
            '<p>不同意</p>' +
            '</div>' +
            '<div class="ui-form-item ui-form-item-textarea ui-border-b">' +
            '<label>' +
            '审批意见' +
            '</label>' +
            '<textarea placeholder="审批意见" id="wf_content"></textarea>' +
            '</div>' +
            '<div class="ui-form-item ui-form-item-switch ui-border-b">' +
            '<p>' +
            '通知申请人' +
            '</p>' +
            '<label class="ui-switch">' +
            '<input type="checkbox" id="wf_isSend" checked="checked" value="y"/>' +
            '</label>' +
            '</div>' +
            '<div class="ui-form-item ui-form-item-switch ui-border-b">' +
            '<p>' +
            '通知下一步审批人' +
            '</p>' +
            '<label class="ui-switch">' +
            '<input type="checkbox" id="wf_isSendNext" checked="checked" value="y"/>' +
            '</label>' +
            '</div>' +
            '<div class="placeholder">' +
            '<!--占位-->' +
            '</div>');

        // 按钮块
        var btnItem = $('<div class="demo-item"></div>');
        var btnBlock = $('<div class="demo-block"></div>');

        // 按钮div
        var btnDiv = $('<div class="ui-footer ui-footer-stable ui-btn-group ui-border-t">');

        // 提交实现
        var subBtn = $('<button class="ui-btn-lg ui-btn-primary" id="sub" type="button">提交</button>')
            .on('click', {g: g, p: p}, function() {
                subAudit(g, p);
            }
        );

        // 返回实现
        var backBtn = $('<button class="ui-btn-lg" id="back" type="button">返回</button>').on('click', function() {
            $(".ui-icon-return").trigger('click');
        });

        // 按钮部分加载
        btnDiv.append(subBtn).append(backBtn);
        btnBlock.append(btnDiv);
        btnItem.append(btnBlock);

        // 表单部分整合加载
        funItem.append('<p class="demo-desc">审批处理</p>').append(funBlock);

        g.append(funItem).append(btnItem);
    };

    // 流程办理
    var subAudit = function(g, p) {
        // 先打开loading
        var eloading = $.loading({content:'加载中...'});

        $.ajax({
            type: "POST",
            // url: "./jd?sid=" + p.sid + "&cmd=com.youngheart.apps.mobile.workflow_subAudit",
            url: '?model=common_workflow_workflow&action=ajaxAudit',
            data: {
                spid: p.spid,
                result: $("input[name=wf_result]:checked").val(),
                content: $("#wf_content").val(),
                isSend: $("#wf_isSend").val(),
                isSendNext: $("#wf_isSendNext").val()
            },
            async: false,
            success: function(data){
                var msg = data != "1" ? "办理失败：失败原因" + data : "办理成功，2秒后跳转回上级页面。";

                var el = $.tips({
                    content: msg,
                    stayTime: 2000,
                    type:"success"
                });

                el.on("tips:hide",function(){
                    $(".ui-icon-return").trigger('click');
                });
            }
        });
    }

    // 查看 TODO
    var initView = function(g, p) {
        var historyList = getHistory(p.pid, p.itemType);

        // 如果存在历史，则开始构建审批历史
        if (historyList.length > 0) {
            for (var i = 0; i < historyList.length; i++) {
                // 块div
                var itemDiv = $('<div class="demo-item"></div>');
                // 加入审批标题
                itemDiv.append('<p class="demo-desc">' +
                    historyList[i].name + ' ' + historyList[i].start.substring(0, 10) +
                    '</p>');

                // 详细步骤
                var steps = historyList[i].steps;

                // 步骤区域内容
                var blockDiv = $('<div class="demo-block"></div>');
                // 列表
                var blockUl = $('<ul class="ui-list ui-list-text ui-border-tb"></ul>');

                // 插入审批详细
                for (var j = 0; j < steps.length; j++) {
                    // 审批情况图标
                    var img = '';
                    switch(steps[j].result) {
                        case 'ok' :
                            img = 'checked.png';
                            break;
                        case 'no' :
                            img = 'cancel.png';
                            break;
                        default:
                            img = 'process.png';
                    }
                    // 办理人处理
                    var stepUsers = steps[j].stepUser.split(',');
                    var stepUserStr = "";
                    for (var k = 0; k < stepUsers.length; k++) {
                        if (stepUsers[k] == steps[j].backUser) {
                            stepUserStr += '<span style="color: red;">' + stepUsers[k] + "</span><br/>";
                        } else {
                            stepUserStr += stepUsers[k] + "<br/>";
                        }
                    }

                    // 办理时间
                    var endTime = steps[j].endTime == "" ? " -- 待 审 批 -- " : steps[j].endTime.substring(0, 10);

                    blockUl.append('<li class="ui-border-t">' +
                        '<div class="ui-list-icon">' +
                            '<span style="background-image:url(images/icon/' + img + ')"></span>' +
                        '</div>' +
                        '<div class="ui-list-info">' +
                            '<h4 class="ui-nowrap">' + steps[j].stepName + '</h4>' +
                            '<p class="ui-wrap">' + steps[j].content + '</p>' +
                        '</div>' +
                        '<div class="ui-list-action">' + stepUserStr +
                            '<div class="ui-badge-muted">' + endTime + '</div>' +
                        '</div>' +
                    '</li>');
                }
                blockDiv.append(blockUl);
                itemDiv.append(blockDiv);

                // 插入页面div
                g.append(itemDiv);
            }
        } else {

        }
    };

    // 获取流程步骤
    var getHistory = function(pid, itemType) {
        var history = [];
        $.ajax({
            type: "POST",
            url: '?model=common_workflow_workflow&action=viewMobile',
            data: {
                pid: pid,
                itemType: itemType
            },
            async: false,
            dataType: 'json',
            success: function(data){
                if (data == "") {
                    $.tips({
                        content: "数据获取失败，请刷新重试",
                        stayTime: 2000,
                        type:"success"
                    });
                } else {
                    history = data;
                }
            }
        });
        return history;
//        return [{
//            name: '付款申请审批', start: '2017-02-06 13:31:21', steps: [{
//                stepName: '部门领导审批',
//                stepId: '1',
//                result: 'ok',
//                content: '好的',
//                stepUser: '管理员,管理员2',
//                endTime: '2017-02-06 14:12:42'
//            }, {
//                stepName: '财务会计审批',
//                stepId: '2',
//                result: 'ok',
//                content: '没问题',
//                stepUser: '郭靖',
//                endTime: '2017-02-06 14:12:42'
//            }, {
//                stepName: '财务领导审批',
//                stepId: '3',
//                result: 'no',
//                content: '恩恩额',
//                stepUser: '欧阳锋,欧阳克',
//                backUser: '欧阳克',
//                endTime: '2017-02-06 14:12:42'
//            }]
//        }];
    };
})($);


/**
 *  序列化form表单元素为json
 **/
$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};