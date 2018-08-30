// 配置项内容修改表单信息验证
var validateForm = function(mainType){
    var pass = true;
    switch(mainType){
        // 服务线报销限制
        case "FWBXXZ":
            break;

        // 租车费用名目对应
        case "ZCFYMM":
            // 检验租车系统科目在配置项内是否唯一
            var itemId = $("#cm_configuratorItemId").val();
            var rentalCarTyps = $("#cm_conFigSub1").val();
            var rentalCarTypArr = (rentalCarTyps == '')? '' : rentalCarTyps.split(",");

            var responseText = $.ajax({
                url:'index1.php?model=system_configurator_configurator&action=chkDuptrentalCarType',
                data : {"id" : itemId,"selectedTypes" : rentalCarTypArr},
                type : "POST",
                async : false
            }).responseText;

            if(responseText != 1){
                alert("所选的租车系统名目不得同时存在于多个配置项中。");
                pass = false;
            }

            break;
    }
    return pass;
}

$(function(){
    //配置部门选择
    $("#belongDeptName").yxselect_dept({
        hiddenId : 'belongDeptId',
        mode : 'check'
    });

    configuratorsMenu();
});

// 获取配置端模块菜单
var configuratorsMenu = function() {
    $("#configurator_tree").tree({
        url: publicUrl + "&action=loadConfiguratorMenu",
        lines: true,
        onlyLeafCheck: true,
        onClick: function(node) {
            if(!isNaN(node.id)){
                if($('#configuratorItem-edit').css("display") != "none"){
                    $('#configuratorItem-edit').dialog({closed: true});
                }
                if($('#configuratorItem-commonEdit').css("display") != "none"){
                    $('#configuratorItem-commonEdit').dialog({closed: true});
                }
                configuratorItemsList(node);
            }
        },onLoadSuccess: function (t, datas) {
            configuratorItemsList(datas[0].children[0]);
        }
    });
};

// 删除配置信息
var deleteConfiguratorItem = function(id){
    var responseText = $.ajax({
        url:'index1.php?model=system_configurator_configurator&action=deleteConfiguratorItem&id='+id,
        type : "POST",
        async : false
    }).responseText;
    if(responseText == 1){
        alert('删除成功!');
        $("#configuratorItems-list").datagrid('reload');
    }else{
        alert('删除失败!');
    }
};


// 添加配置项内容
var addConfigItem = function(mainType,mainName,mainId){
    $("#cm_footerWrap").append('<input type="hidden" name="configurator[groupBelongName]" value="'+mainName+'"/><input type="hidden" name="configurator[mainId]" value="'+mainId+'"/>');

    // 根据配置模块制定相应的表单内容
    switch (mainType){
        // 服务线报销限制
        case "FWBXXZ":
            initCommonForm();
            reloadConfigForm(mainType);
            break;

        // 租车费用名目对应
        case "ZCFYMM":
            initCommonForm();
            reloadConfigForm(mainType);
            break;

        // 阿里商旅费用名目对应
        case "ALSLFYMM":
            initCommonForm();
            reloadConfigForm(mainType);
            break;

        // 报销小类审批人配置
        case "BXXLAUDITORS":
            initCommonForm();
            reloadConfigForm(mainType);
            break;

        // 总监配置
        case "MAJORCONFIG":
            initCommonForm();
            reloadConfigForm(mainType);
            break;
    }

    $('#configuratorItem-commonEdit').show();
    if($("#costTypeInner").html() != ""){
        $("#costTypeInner").html("");
        $("#costTypeInner").dialog('close');
    }
    $('#configuratorItem-commonEdit').removeClass("hidden").dialog({
        title: "新增配置项",
        closed: false,
        width: 480,
        height: 350,
    });

    // 提交按钮促发事件
    $("#cm_submit").unbind('click');
    $("#cm_submit").bind('click',function(){
        if(validateForm(mainType)){
            $.ajax({
                cache: true,
                type: "POST",
                url:"?model=system_configurator_configurator&action=ajaxAddConfiguratorItem",
                data:$('#form2').serialize(),
                async: false,
                error: function(request) {
                    console.log("Connection error");
                },
                success: function(data) {
                    if(data == 1){
                        alert('新增成功!');
                        $('#configuratorItem-commonEdit').dialog('close');
                        $("#configuratorItems-list").datagrid('reload');
                    }else{
                        alert('新增失败!');
                    }
                }
            });
        }
    });
}


// 编辑配置信息
var editConfiguratorItem = function(itemId,mainType,dialogWidth,dialogHeight){
    if($('#configuratorItem-edit').css("display") != "none"){
        $('#configuratorItem-edit').dialog({closed: true});
    }
    if($('#configuratorItem-commonEdit').css("display") != "none"){
        $('#configuratorItem-commonEdit').dialog({closed: true});
    }

    $("#configuratorItemId").val(itemId);
    var responseText = $.ajax({
        url:'index1.php?model=system_configurator_configurator&action=getConfiguratorItemsList&id='+itemId,
        type : "POST",
        async : false
    }).responseText;

    if(mainType == "BXFT_CONFIG1"){// “报销分摊配置”
        if(responseText!='' && responseText!='false'){
            var responseObj = eval("("+responseText+")");
            var data = responseObj[0];
            $("#belongDeptName").val(data.belongDeptNames);
            $("#belongDeptId").val(data.belongDeptIds);
            $("#remarks").val(data.remarks);
        }

        var dialogWidthVal = (dialogWidth == undefined)? 380 : dialogWidth;
        var dialogHeightVal = (dialogHeight == undefined)? 200 : dialogHeight;
        $('#configuratorItem-edit').show();
        $('#configuratorItem-edit').removeClass("hidden").dialog({
            title: "编辑配置项",
            closed: false,
            width: dialogWidthVal,
            height: dialogHeightVal,
        });
    }else{
        initCommonForm();
        $("#cm_configuratorItemId").val(itemId);

        if(responseText!='' && responseText!='false'){
            var responseObj = eval("("+responseText+")");
            var data = responseObj[0];
            $("#cm_belongDeptName").val(data.belongDeptNames);
            $("#cm_belongDeptId").val(data.belongDeptIds);
            $("#cm_conFig1").val(data.config_item1);
            $("#cm_conFigSub1").val(data.config_itemSub1);
            $("#cm_conFig2").val(data.config_item2);
            $("#cm_conFigSub2").val(data.config_itemSub2);
            $("#cm_conFig3").val(data.config_item3);
            $("#cm_conFigSub3").val(data.config_itemSub3);
            $("#cm_conFig4").val(data.config_item4);
            $("#cm_conFigSub4").val(data.config_itemSub4);
            $("#cm_remarks").val(data.remarks);
        }
        reloadConfigForm(mainType,"edit");

        var dialogWidthVal = (dialogWidth == undefined)? 480 : dialogWidth;
        var dialogHeightVal = (dialogHeight == undefined)? 350 : dialogHeight;
        $('#configuratorItem-commonEdit').show();
        $('#configuratorItem-commonEdit').removeClass("hidden").dialog({
            title: "编辑配置项",
            closed: false,
            width: dialogWidthVal,
            height: dialogHeightVal,
        });
    }

    // 提交按钮促发事件
    $("#cm_submit").unbind('click');
    $("#cm_submit").bind('click',function(){
        if(validateForm(mainType)){
            $.ajax({
                cache: true,
                type: "POST",
                url:"?model=system_configurator_configurator&action=ajaxEditConfiguratorItem",
                data:$('#form2').serialize(),
                async: false,
                error: function(request) {
                    console.log("Connection error");
                },
                success: function(data) {
                    if(data == 1){
                        alert('修改成功!');
                        $('#configuratorItem-commonEdit').dialog('close');
                        $("#configuratorItems-list").datagrid('reload');
                    }else{
                        alert('修改失败!');
                    }
                }
            });
        }
    });
};

// =================================================== 费用类型选择框 ===================================================  //
//选择自定义费用类型
function setCustomCostType(thisCostType,thisObj){
    $.each(costTypeNameCache,function(i,item){
        if(item == ''){
            costTypeNameCache.splice(i,1);
        }
    });

    $.each(costTypeIdCache,function(i,item){
        if(item == ''){
            costTypeIdCache.splice(i,1);
        }else{
            costTypeIdCache[i] = Number(item);
        }
    });

    if($(thisObj).attr('checked') == true){
        // 检查是否存在最多可选费用项限制
        if(maxCostTypeNum != ''){
            if(costTypeIdCache.length == maxCostTypeNum-1){
                $("#view" + thisCostType).attr('class','blue');
                costTypeIdCache.push(thisCostType);
                costTypeNameCache.push($(thisObj).attr('name'));
            }else{
                alert("此配置项的费用类型最多只能选"+maxCostTypeNum+"条!");
                $(thisObj).attr('checked',false);
                return false;
            }
        }else{
            $("#view" + thisCostType).attr('class','blue');
            costTypeIdCache.push(thisCostType);
            costTypeNameCache.push($(thisObj).attr('name'));
        }
    }else{
        $("#view" + thisCostType).attr('class','');

        var nameIndex = costTypeNameCache.indexOf($(thisObj).attr('name'));
        var idIndex = costTypeIdCache.indexOf(thisCostType);

        costTypeNameCache.splice(nameIndex,1);
        costTypeIdCache.splice(idIndex,1);
    }

    if(costTypeCfgNum > 0){
        $("#cm_conFig"+costTypeCfgNum).val(costTypeNameCache.join(","));
        $("#cm_conFigSub"+costTypeCfgNum).val(costTypeIdCache.join(","));
    }
}

//判断浏览器
function checkExplorer(){
    var Sys = {};
    var ua = navigator.userAgent.toLowerCase();
    window.ActiveXObject ? Sys.ie = ua.match(/msie ([\d.]+)/)[1] :
        document.getBoxObjectFor ? Sys.firefox = ua.match(/firefox\/([\d.]+)/)[1] :
            window.MessageEvent && !document.getBoxObjectFor ? Sys.chrome = ua.match(/chrome\/([\d.]+)/)[1] :
                window.opera ? Sys.opera = ua.match(/opera.([\d.]+)/)[1] :
                    window.openDatabase ? Sys.safari = ua.match(/version\/([\d.]+)/)[1] : 0;

    if(Sys.ie){
        return 1;
    }
}

//瀑布流排序
function initMasonry(){
    $('#costTypeInner2').masonry({
        itemSelector: '.box'
    });
}

//自定义费用选择功能 - 弹出选择
var selectCostType = function(){

    //当前存在费用类型字符串
    var costTypeIds = (costTypeCfgNum > 0)? $("#cm_conFigSub"+costTypeCfgNum).val() : '';
    var costTypeName = (costTypeCfgNum > 0)? $("#cm_conFig"+costTypeCfgNum).val() : '';
    costTypeNameCache = costTypeName.split(",");
    costTypeIdCache = costTypeIds.split(",");

    //当前存在费用类型数组
    var costTypeIdArr = (costTypeIds =='')? [] : costTypeIds.split(",");

    $.ajax({
        type: "POST",
        url: "?model=finance_expense_costtype&action=getCostType",
        async: false,
        success: function(data){
            if(data != ""){
                $("#costTypeInner").html("<div id='costTypeInner2'>" + data + "</div>")
                if(costTypeIds != ""){
                    //设值
                    for(var i = 0; i < costTypeIdArr.length;i++){
                        $("#chk" + costTypeIdArr[i]).attr('checked',true);
                        $("#view" + costTypeIdArr[i]).attr('class','blue');
                    }

                }
            }else{
                alert('没有找到自定义的费用类型');
            }

        }
    });

    $("#costTypeInner").dialog({
        title : '新增费用类型',
        closed: false,
        height : 400,
        width : 760
    });

    //延时调用排序方法
    setTimeout(function(){
        initMasonry();
        if(checkExplorer() == 1){
            $("#costTypeInner2").height(560).css("overflow-y","scroll");
        }
    },200);
}