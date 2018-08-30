var costTypeNameCache = [];
var costTypeIdCache = [];
var costTypeCfgNum = 0;
var maxCostTypeNum = 0;

// 初始化公共报表单
var initCommonForm = function(){
    // 清空所有动态加载的字段信息
    $("#cm_configuratorItemId").val('');
    $("#cm_belongDeptName").val('');
    $("#cm_belongDeptId").val('');
    $("#cm_conFig1").val('');
    $("#cm_conFigSub1").val('');
    $("#cm_conFig2").val('');
    $("#cm_conFigSub2").val('');
    $("#cm_conFig3").val('');
    $("#cm_conFigSub3").val('');
    $("#cm_remarks").val('');

    // 隐藏所有动态加载的字段
    $(".cm_configItem").hide();
    $("#cm_conFig1ExtWrap").html("");
    $("#cm_conFig2ExtWrap").html("");
    $("#cm_conFig3ExtWrap").html("");

    if($("#cm_conFig1Textarea").val() != undefined){
        $(".cm_conFig1TextareaRelative").remove();
    }

    // 清除特殊的配置初始化类型
    $("#cm_conFig3").yxselect_user('remove');

    // 复原加过效果以及函数绑定
    $("#cm_conFig1").attr("name","configurator[config_item1]");
    $("#cm_conFig1").show();
    $("#cm_conFig2").show();
    $("#cm_conFig3").show();
    $("#cm_conFig2").removeAttr("readonly");
    $("#cm_conFig2").unbind("click");
}

// 按所属模块重新加载表单样式
var reloadConfigForm = function(mainType,act){
    $("#cm_conFig1Opts").remove();
    $("div.cm_conFig1TextareaRelative").text('');
    // 再根据条件重新显示对应的表单字段
    switch (mainType){
        // 服务线报销限制
        case 'FWBXXZ':
            $("#cm_belongDeptTr").show();
            $("#cm_conFig1Tr").show();
            $("#cm_conFig2Tr").show();
            $("#cm_conFig1Label").text("限制费用类型 ");
            $("#cm_conFig2Label").text("限制科目");

            // 限制费用类型
            var expenseTypesObj = {
                1 :'部门费用',
                2 :'合同项目费用',
                3 :'研发费用',
                4 :'售前费用',
                5 :'售后费用'
            };

            var expenseTypesOpts = "<option value=''>...请选择...</option>";
            $.each(expenseTypesObj,function(i,item){
                expenseTypesOpts += (act == "edit" && i == $("#cm_conFigSub1").val())? "<option value='"+i+"' selected>"+item+"</option>" : "<option value='"+i+"'>"+item+"</option>";
            });
            var expenseTypesSlts = '<select id="expenseTypesSlts" data-options="multiple:false" class="txt esayui-combobox">'+expenseTypesOpts+'</select>';
            $("#cm_conFig1").hide();
            $("#cm_conFig1ExtWrap").html(expenseTypesSlts);
            $("#expenseTypesSlts").unbind('change');
            $("#expenseTypesSlts").bind('change',function(){
                $("#cm_conFig1").val(expenseTypesObj[$(this).val()]);
                $("#cm_conFigSub1").val($(this).val());
            });

            // 费用系统名目
            costTypeCfgNum = 2;
            maxCostTypeNum = '';
            if(act == "edit"){
                // 设置默认选中的费用类型数据
                costTypeNameCache = $("#cm_conFig2").val().split(",");
                costTypeIdCache = $("#cm_conFigSub2").val().split(",");
            }

            $("#cm_conFig2").attr("readonly",1);
            $("#cm_conFig2").click(selectCostType);

            $("#cm_belongDeptName").yxselect_dept('remove');
            $("#cm_belongDeptName").yxselect_dept({
                hiddenId : 'cm_belongDeptId',
                mode : 'check'
            });
            break;

        // 租车费用名目对应
        case "ZCFYMM":
            $("#cm_conFig1Tr").show();
            $("#cm_conFig2Tr").show();
            $("#cm_conFig3Tr").show();
            $("#cm_conFig1Label").text("租车系统名目");
            $("#cm_conFig2Label").text("费用系统科目");
            $("#cm_conFig3Label").text("需生成费用");

            // 租车系统名目
            var carRentCostTypes =  $.ajax({
                type : 'GET',
                url : '?model=system_configurator_configurator&action=getCarRentCostTypes',
                data : {'datatype' : 'options'},
                async: false,
                success : function(data){
                    return data;
                }
            }).responseText;
            var sltcarRentCostTypesArr = [];
            var carRentCostTypesObj = eval("("+carRentCostTypes+")");
            var carRentCostTypesData = carRentCostTypesObj.data.dataArr;
            var carRentCostTypesOpts = (carRentCostTypesObj.msg == 'ok')? carRentCostTypesObj.data.options : '';
            var carRentCostTypesSlts = '<div id="carRentCostTypesOpts"></div><select id="carRentCostTypesCombobox" data-options="multiple:true" class="txt esayui-combobox" style="width:200px;">'+carRentCostTypesOpts+'</select></div>';
            $("#cm_conFig1").hide();
            $("#carRentCostTypesOpts").remove();
            $("#cm_conFig1ExtWrap").html(carRentCostTypesSlts);
            $('#carRentCostTypesCombobox').combobox({
                onChange: function(e) {
                    sltcarRentCostTypesArr = [];
                    var value = $('#carRentCostTypesCombobox').combobox('getValues');
                    $.each(value,function(i,item){
                        if(item == ''){
                            costTypeIdCache.splice(i,1);
                        }else{
                            sltcarRentCostTypesArr.push(carRentCostTypesData[item]);
                        }
                    });
                    $("#cm_conFig1").val(sltcarRentCostTypesArr.join(','));
                    $("#cm_conFigSub1").val(value.join(','));
                }
            });
            $("#cm_conFig1ExtWrap").children("span").children("input").attr("readonly",1);

            // 需生成费用选项
            $("#cm_conFig3").hide();
            var useForBuildFeeTipRadioStr = '<input type="radio" class="useForBuildFeeTip" checked="" name="radioOpt" value="1">是 <input class="useForBuildFeeTip" type="radio" name="radioOpt" value="0">否';
            $("#cm_conFig3ExtWrap").html(useForBuildFeeTipRadioStr);
            $(".useForBuildFeeTip").unbind('change');
            $(".useForBuildFeeTip").bind('change',function(e){
                var tipName = ($(this).val() == 1)? '是' : '否';
                $("#cm_conFig3").val(tipName);
                $("#cm_conFigSub3").val($(this).val());
            });

            costTypeCfgNum = 2;
            maxCostTypeNum = 1;// 租车费用名目对应的费用系统科目最多只能选一项
            var carRentCostTypesComboboxDefault = [];
            if(act == "edit"){
                // 设置默认选中的费用类型数据
                costTypeNameCache = $("#cm_conFig2").val().split(",");
                costTypeIdCache = $("#cm_conFigSub2").val().split(",");
                carRentCostTypesComboboxDefault = ($("#cm_conFigSub1").val() != '')? $("#cm_conFigSub1").val().split(",") : [];
            }else{
                $("#cm_conFig3").val('是');
                $("#cm_conFigSub3").val(1);
            }

            $('#carRentCostTypesCombobox').combobox('setValues',carRentCostTypesComboboxDefault);

            $("#cm_conFig2").attr("readonly",1);
            $("#cm_conFig2").click(selectCostType);
            break;
        // 日志告警配置
        case "RZGJJOBFILTER":
            $("#cm_conFig1Tr").show();
            $("#cm_conFig1Label").text("日志告警过滤条件");
            $("#cm_conFig1").hide();
            $("#cm_conFig1").attr("name","");
            $("#cm_conFig1").after("<div id='cm_conFig1Opts'><textarea id='cm_conFig1Textarea' class='cm_conFig1TextareaRelative' name='configurator[config_item1]' rows='10' cols='55'>"+$("#cm_conFig1").val()+"</textarea><div class='cm_conFig1TextareaRelative' style='color:red;'>注意: 如需配置多个请以逗号 \",\" 隔开</div></div>");
            break;
        // 应收款配置
        case "YSKPZ":
            // 租车系统名目
            var customerTypes =  $.ajax({
                type : 'GET',
                url : '?model=system_configurator_configurator&action=getCustomerTypes',
                data : {
                    'datatype' : 'checkbox',
                    'configId' : ($("#cm_configuratorItemId").val() != undefined)? $("#cm_configuratorItemId").val() : ''
                },
                async: false,
                success : function(data){
                    return data;
                }
            }).responseText;

            var customerTypesObj = eval("("+customerTypes+")");
            var customerTypesData = customerTypesObj.data.dataArr;
            var customerTypesStr = (customerTypesObj.msg == 'ok')? customerTypesObj.data.htmlStr : '';

            $("#cm_conFig1Tr").show();
            $("#cm_conFig1Label").text("不计算应收款的\n客户类型");
            $("#cm_conFig1").hide();
            $("#cm_conFig1").attr("name","");
            $("#cm_conFig1").after("<div id='cm_conFig1Opts'><input type='hidden' name='configType' value='YSKPZ'>"+customerTypesStr+"</div>");
            break;
        // 阿里商旅费用名目对应
        case "ALSLFYMM":
            $("#cm_conFig1Tr").show();
            $("#cm_conFig2Tr").show();
            $("#cm_conFig1Label").text("阿里商旅费用项");
            $("#cm_conFig2Label").text("费用系统科目");

            costTypeCfgNum = 2;
            maxCostTypeNum = 1;// 租车费用名目对应的费用系统科目最多只能选一项
            var carRentCostTypesComboboxDefault = [];
            if(act == "edit"){
                // 设置默认选中的费用类型数据
                costTypeNameCache = $("#cm_conFig2").val().split(",");
                costTypeIdCache = $("#cm_conFigSub2").val().split(",");
                carRentCostTypesComboboxDefault = ($("#cm_conFigSub1").val() != '')? $("#cm_conFigSub1").val().split(",") : [];
            }

            $('#carRentCostTypesCombobox').combobox('setValues',carRentCostTypesComboboxDefault);

            $(".cm_conFig1TextareaRelative").remove();
            $("#cm_conFig1").after("<div class='cm_conFig1TextareaRelative' style='color:red;'>注意: 阿里商旅费用项名称需要与阿里商旅上的一致,<br>否则可能会导致无法匹配。</div>");
            $("#cm_conFig2").attr("readonly",1);
            $("#cm_conFig2").click(selectCostType);
            break;
        // 销售部门
        case 'SALEDEPT':
            var costBelongDeptJson =  $.ajax({
                type : 'GET',
                url : '?model=system_configurator_configurator&action=getExpenseCostBelongDept',
                data : {
                    'datatype' : 'checkbox',
                    'deptIds' : ($("#cm_belongDeptId").val() != undefined)? $("#cm_belongDeptId").val() : ''
                },
                async: false,
                success : function(data){
                    return data;
                }
            }).responseText;

            var costBelongDeptArr = eval("("+costBelongDeptJson+")");
            var costBelongDeptData = costBelongDeptArr.data.dataArr;
            var costBelongDeptStr = (costBelongDeptArr.msg == 'ok')? costBelongDeptArr.data.htmlStr : '';

            $("#cm_conFig1Tr").show();
            $("#cm_conFig1Label").text("报销及分摊模块的\n归属部门");
            $("#cm_conFig1").hide();
            $("#cm_conFig1").attr("name","");
            $("#cm_conFig1").after("<div id='cm_conFig1Opts'><input type='hidden' name='configType' value='SALEDEPT'>"+costBelongDeptStr+"</div>");

            // console.log(costBelongDeptArr);
            break;
        // 报销小类审批人配置
        case "BXXLAUDITORS":
            $("#cm_conFig1Tr").show();
            $("#cm_conFig2Tr").show();
            $("#cm_conFig3Tr").show();
            $("#cm_conFig4Tr").show();
            $("#cm_conFig1Label").text("限制费用类型 ");
            $("#cm_conFig2Label").text("限制科目");
            $("#cm_conFig3Label").text("审批人");
            $("#cm_conFig4Label").text("审批节点");

            // 限制费用类型
            var expenseTypesObj = { 1 :'部门费用', 2 :'合同项目费用', 3 :'研发费用', 4 :'售前费用', 5 :'售后费用' };
            var expenseTypesOpts = "<option value=''>...请选择...</option>";
            $.each(expenseTypesObj,function(i,item){
                expenseTypesOpts += (act == "edit" && i == $("#cm_conFigSub1").val())? "<option value='"+i+"' selected>"+item+"</option>" : "<option value='"+i+"'>"+item+"</option>";
            });
            var expenseTypesSlts = '<select id="expenseTypesSlts" data-options="multiple:false" class="txt esayui-combobox">'+expenseTypesOpts+'</select>';
            $("#cm_conFig1").hide();
            $("#cm_conFig1ExtWrap").html(expenseTypesSlts);
            $("#expenseTypesSlts").unbind('change');
            $("#expenseTypesSlts").bind('change',function(){
                $("#cm_conFig1").val(expenseTypesObj[$(this).val()]);
                $("#cm_conFigSub1").val($(this).val());
            });

            // 费用系统名目
            costTypeCfgNum = 2;
            maxCostTypeNum = '';
            if(act == "edit"){
                // 设置默认选中的费用类型数据
                costTypeNameCache = $("#cm_conFig2").val().split(",");
                costTypeIdCache = $("#cm_conFigSub2").val().split(",");
            }
            $("#cm_conFig2").attr("readonly",1);
            $("#cm_conFig2").click(selectCostType);

            // 审批人
            $("#cm_conFig3").yxselect_user('remove');
            $("#cm_conFig3").yxselect_user({
                hiddenId: 'cm_conFigSub3',
                formCode: 'cm_conFig3',
                mode: 'check',
                event: {
                    select: function (obj, row) {
                    },
                    clearReturn: function () {
                        $("#cm_conFig3").val('');
                        $("#cm_conFigSub3").val('');
                    }
                }
            });

            // 审批节点
            var auditPointObj = {1 :'第一步',2 :'财务审批前'};
            var auditPointOpts = "<option value=''>...请选择...</option>";
            $.each(auditPointObj,function(i,item){
                auditPointOpts += (act == "edit" && i == $("#cm_conFigSub4").val())? "<option value='"+i+"' selected>"+item+"</option>" : "<option value='"+i+"'>"+item+"</option>";
            });
            var auditPointSlts = '<select id="auditPointSlts" data-options="multiple:false" class="txt esayui-combobox">'+auditPointOpts+'</select>';
            $("#cm_conFig4").hide();
            $("#cm_conFig4ExtWrap").html(auditPointSlts);
            $("#auditPointSlts").unbind('change');
            $("#auditPointSlts").bind('change',function(){
                $("#cm_conFig4").val(auditPointObj[$(this).val()]);
                $("#cm_conFigSub4").val($(this).val());
            });

            break;
        // 总监配置
        case "MAJORCONFIG":
            $("#cm_conFig1Tr").show();
            $("#cm_conFig2Tr").show();
            $("#cm_conFig1Label").text("类别");
            $("#cm_conFig2Label").text("人员");

            // 审批人
            $("#cm_conFig2").yxselect_user('remove');
            $("#cm_conFig2").yxselect_user({
                hiddenId: 'cm_conFigSub2',
                formCode: 'cm_conFig2',
                mode: 'check',
                event: {
                    select: function (obj, row) {
                    },
                    clearReturn: function () {
                        $("#cm_conFig2").val('');
                        $("#cm_conFigSub2").val('');
                    }
                }
            });
            break;
        //费用归属部门
        case 'FYGSDEPT':
            $("#cm_belongDeptTr").show();

            $("#cm_belongDeptName").yxselect_dept('remove');
            $("#cm_belongDeptName").yxselect_dept({
                hiddenId : 'cm_belongDeptId',
                mode : 'check'
            });
            $("#cm_belongDeptName").before("<div class='cm_conFig1TextareaRelative' style='color:red;'>注意: 本处所配置的部门,<br>将不能用于费用归属部门。</div>");
            break;

    }
}

// 配置内容列表初始化
var configuratorItemsList = function( data ){
    var url = "index1.php" + publicUrl + "&action=getConfiguratorItemsList&mainId="+data.id;

    var attributesObj = (data.attributes != '')? eval("("+data.attributes+")") : {};
    var columnsCfg = [];
    var toolbarCfg = [];
    switch(attributesObj.code){
        // 报销分摊配置
        case "BXFT_CONFIG1":
            // 表格表头配置信息
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 50},
                { field: "belongDeptNames", title: "配置部门", align: "center", width: 180 },
                { field: "belongDeptIds", title: "配置部门ID", align: "center", width: 180 },
                { field: "remarks", title: "备注信息", align: "center", width: 250 },
                { field: "operation", title: "操作", align: "center", width: 150, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id  + ",'"+ attributesObj.code +"')\">编辑</a>";
                    return str;
                } }
            ]];
            break;

        // 服务线报销限制
        case "FWBXXZ":
            // 表格表头配置信息
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 50},
                { field: "belongDeptNames", title: "配置部门", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.belongDeptNames+"</div>";
                    return str;
                }},
                { field: "config_item1", title: "限制费用类型", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item1+"</div>";
                    return str;
                }},
                { field: "config_item2", title: "限制科目", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item2+"</div>";
                    return str;
                }},
                { field: "remarks", title: "备注信息", align: "center", width: 250,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                    return str;
                }},
                { field: "operation", title: "操作", align: "center", width: 150, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id + ",'"+ attributesObj.code +"')\">编辑</a>";
                    str += "&nbsp;&nbsp;<a href=\"#\" onclick=\"deleteConfiguratorItem(" + row.id + ")\">删除</a>";
                    return str;
                } }
            ]];

            // 配置模块操作按钮
            toolbarCfg = [{
                iconCls: "icon-add",
                text: "新增流程",
                handler: function () {
                    addConfigItem(attributesObj.code,data.text,data.id);
                }
            }];
            break;

        // 租车费用名目对应
        case "ZCFYMM":
            // 表格表头配置信息
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 50},
                { field: "config_item1", title: "租车系统名目", align: "center", width: 180 ,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item1+"</div>";
                    return str;
                }},
                { field: "config_item2", title: "费用系统科目", align: "center", width: 180 ,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item2+"</div>";
                    return str;
                }},
                { field: "config_item3", title: "需生成费用", align: "center", width: 180 ,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item3+"</div>";
                    return str;
                }},
                { field: "remarks", title: "备注信息", align: "center", width: 250 ,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                    return str;
                }},
                { field: "operation", title: "操作", align: "center", width: 150, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id + ",'"+ attributesObj.code +"')\">编辑</a>";
                    str += "&nbsp;&nbsp;<a href=\"#\" onclick=\"deleteConfiguratorItem(" + row.id + ")\">删除</a>";
                    return str;
                } }
            ]];

            // 配置模块操作按钮
            toolbarCfg = [{
                iconCls: "icon-add",
                text: "新增配置",
                handler: function () {
                    addConfigItem(attributesObj.code,data.text,data.id);
                }
            }];
            break;
        // 日志告警配置
        case "RZGJJOBFILTER":
            // 表格表头配置信息
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 10},
                { field: "config_item1", title: "日志告警过滤条件", align: "center", width: 50 },
                { field: "remarks", title: "备注信息", align: "center", width: 25,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                    return str;
                }},
                { field: "operation", title: "操作", align: "center", width: 15, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id  + ",'"+ attributesObj.code +"')\">编辑</a>";
                    return str;
                } }
            ]];
            break;
        // 应收款配置
        case "YSKPZ":
            // 表格表头配置信息
            columnsCfg = [[
                // { field: "id", title: "id",align: "center", width: 10},
                { field: "config_item1", title: "不计算应收款的客户类型", align: "center", width: 60,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal;padding:10px;'>"+row.config_item1+"</div>";
                    return str;
                }},
                { field: "remarks", title: "备注信息", align: "center", width: 35,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                    return str;
                }},
                { field: "operation", title: "操作", align: "center", width: 15, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id  + ",'"+ attributesObj.code +"',480,480)\">编辑</a>";
                    return str;
                } }
            ]];
            break;

        // 阿里商旅费用名目对应
        case "ALSLFYMM":
            // 表格表头配置信息
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 50},
                { field: "config_item1", title: "阿里商旅费用项", align: "center", width: 180 ,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item1+"</div>";
                    return str;
                }},
                { field: "config_item2", title: "费用系统科目", align: "center", width: 180 ,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item2+"</div>";
                    return str;
                }},
                { field: "remarks", title: "备注信息", align: "center", width: 250 ,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                    return str;
                }},
                { field: "operation", title: "操作", align: "center", width: 150, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id + ",'"+ attributesObj.code +"')\">编辑</a>";
                    str += "&nbsp;&nbsp;<a href=\"#\" onclick=\"deleteConfiguratorItem(" + row.id + ")\">删除</a>";
                    return str;
                } }
            ]];

            // 配置模块操作按钮
            toolbarCfg = [{
                iconCls: "icon-add",
                text: "新增配置",
                handler: function () {
                    addConfigItem(attributesObj.code,data.text,data.id);
                }
            }];
            break;
        // 销售部门
        case "SALEDEPT":
            // 表格表头配置信息
            columnsCfg = [[
                { field: "belongDeptNames", title: "配置部门", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal;padding:10px;'>"+row.belongDeptNames+"</div>";
                    return str;
                }},
                { field: "belongDeptIds", title: "配置部门ID", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal;padding:10px;'>"+row.belongDeptIds+"</div>";
                    return str;
                }},
                { field: "remarks", title: "备注信息", align: "center", width: 250 },
                { field: "operation", title: "操作", align: "center", width: 150, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id  + ",'"+ attributesObj.code +"',480,380)\">编辑</a>";
                    return str;
                } }
            ]];
            break;
        // 报销小类审批人配置
        case "BXXLAUDITORS":
            // 表格表头配置信息
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 50},
                { field: "config_item1", title: "费用类型", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item1+"</div>";
                    return str;
                }},
                { field: "config_item2", title: "科目", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item2+"</div>";
                    return str;
                }},
                { field: "config_item3", title: "审批人", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item3+"</div>";
                    return str;
                }},
                { field: "config_item4", title: "审批节点", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item4+"</div>";
                    return str;
                }},
                { field: "remarks", title: "备注信息", align: "center", width: 250,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                    return str;
                }},
                { field: "operation", title: "操作", align: "center", width: 150, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id + ",'"+ attributesObj.code +"')\">编辑</a>";
                    str += "&nbsp;&nbsp;<a href=\"#\" onclick=\"deleteConfiguratorItem(" + row.id + ")\">删除</a>";
                    return str;
                } }
            ]];

            // 配置模块操作按钮
            toolbarCfg = [{
                iconCls: "icon-add",
                text: "新增配置",
                handler: function () {
                    addConfigItem(attributesObj.code,data.text,data.id);
                }
            }];
            break;

        // 总监配置
        case "MAJORCONFIG":
            // 表格表头配置信息
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 50},
                { field: "config_item1", title: "类别", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item1+"</div>";
                    return str;
                }},
                { field: "config_item2", title: "人员", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item2+"</div>";
                    return str;
                }},
                { field: "remarks", title: "备注信息", align: "center", width: 250,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                    return str;
                }},
                { field: "operation", title: "操作", align: "center", width: 150, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id + ",'"+ attributesObj.code +"')\">编辑</a>";
                    str += "&nbsp;&nbsp;<a href=\"#\" onclick=\"deleteConfiguratorItem(" + row.id + ")\">删除</a>";
                    return str;
                } }
            ]];

            // 配置模块操作按钮
            toolbarCfg = [{
                iconCls: "icon-add",
                text: "新增配置",
                handler: function () {
                    addConfigItem(attributesObj.code,data.text,data.id);
                }
            }];
            break;

        // 费用归属部门
        case "FYGSDEPT":
            // 表格表头配置信息
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 10},
                { field: "belongDeptNames", title: "配置部门 ( 本处配置部门,将不能用于费用归属 )",align: "center",width: 43,formatter: function (value, row, index){
                        var str = "<div style='white-space: normal'>"+row.belongDeptNames+"</div>";
                        return str;
                    }},
                { field: "belongDeptIds", title: "配置部门ID", align: "center", width: 30,formatter: function (value, row, index){
                        var str = "<div style='white-space: normal'>"+row.belongDeptIds+"</div>";
                        return str;
                    }},
                { field: "remarks", title: "备注信息", align: "center", width: 20,formatter: function (value, row, index){
                        var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                        return str;
                    }},
                { field: "operation", title: "操作", align: "center", width: 10, formatter: function (value, row, index)
                    {
                        var str = "";
                        str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id + ",'"+ attributesObj.code +"')\">编辑</a>";
                        // str += "&nbsp;&nbsp;<a href=\"#\" onclick=\"deleteConfiguratorItem(" + row.id + ")\">删除</a>";
                        return str;
                    } }
            ]];
            break;

    }

    $("#configuratorItems-list").datagrid({
        title: data.text,
        url: url,
        columns: columnsCfg,
        singleSelect: true,
        fitColumns: true,
        toolbar: [],
        detailFormatter: function(mainIndex, mainRow) {
            return '<div style="padding:2px"><table id="parts_grid_' + mainIndex + '"></table></div>';
        },
        pageSize: 20,
        pagination: true,
        fit: true,   //自适应大小
        onLoadSuccess: function () {
        },
        onBeforeEdit: function (index, row) {
            row.editing = true;
            updateActions(index);
        },
        onAfterEdit: function(index, row) {
            editInventory(row);

            row.editing = false;
            updateActions(index);
        },
        onCancelEdit: function(index, row) {
            row.editing = false;
            updateActions(index);
        },
        toolbar : toolbarCfg
    });

}