// �����������޸ı���Ϣ��֤
var validateForm = function(mainType){
    var pass = true;
    switch(mainType){
        // �����߱�������
        case "FWBXXZ":
            break;

        // �⳵������Ŀ��Ӧ
        case "ZCFYMM":
            // �����⳵ϵͳ��Ŀ�����������Ƿ�Ψһ
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
                alert("��ѡ���⳵ϵͳ��Ŀ����ͬʱ�����ڶ���������С�");
                pass = false;
            }

            break;
    }
    return pass;
}

$(function(){
    //���ò���ѡ��
    $("#belongDeptName").yxselect_dept({
        hiddenId : 'belongDeptId',
        mode : 'check'
    });

    configuratorsMenu();
});

// ��ȡ���ö�ģ��˵�
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

// ɾ��������Ϣ
var deleteConfiguratorItem = function(id){
    var responseText = $.ajax({
        url:'index1.php?model=system_configurator_configurator&action=deleteConfiguratorItem&id='+id,
        type : "POST",
        async : false
    }).responseText;
    if(responseText == 1){
        alert('ɾ���ɹ�!');
        $("#configuratorItems-list").datagrid('reload');
    }else{
        alert('ɾ��ʧ��!');
    }
};


// �������������
var addConfigItem = function(mainType,mainName,mainId){
    $("#cm_footerWrap").append('<input type="hidden" name="configurator[groupBelongName]" value="'+mainName+'"/><input type="hidden" name="configurator[mainId]" value="'+mainId+'"/>');

    // ��������ģ���ƶ���Ӧ�ı�����
    switch (mainType){
        // �����߱�������
        case "FWBXXZ":
            initCommonForm();
            reloadConfigForm(mainType);
            break;

        // �⳵������Ŀ��Ӧ
        case "ZCFYMM":
            initCommonForm();
            reloadConfigForm(mainType);
            break;

        // �������÷�����Ŀ��Ӧ
        case "ALSLFYMM":
            initCommonForm();
            reloadConfigForm(mainType);
            break;

        // ����С������������
        case "BXXLAUDITORS":
            initCommonForm();
            reloadConfigForm(mainType);
            break;

        // �ܼ�����
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
        title: "����������",
        closed: false,
        width: 480,
        height: 350,
    });

    // �ύ��ť�ٷ��¼�
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
                        alert('�����ɹ�!');
                        $('#configuratorItem-commonEdit').dialog('close');
                        $("#configuratorItems-list").datagrid('reload');
                    }else{
                        alert('����ʧ��!');
                    }
                }
            });
        }
    });
}


// �༭������Ϣ
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

    if(mainType == "BXFT_CONFIG1"){// ��������̯���á�
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
            title: "�༭������",
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
            title: "�༭������",
            closed: false,
            width: dialogWidthVal,
            height: dialogHeightVal,
        });
    }

    // �ύ��ť�ٷ��¼�
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
                        alert('�޸ĳɹ�!');
                        $('#configuratorItem-commonEdit').dialog('close');
                        $("#configuratorItems-list").datagrid('reload');
                    }else{
                        alert('�޸�ʧ��!');
                    }
                }
            });
        }
    });
};

// =================================================== ��������ѡ��� ===================================================  //
//ѡ���Զ����������
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
        // ����Ƿ��������ѡ����������
        if(maxCostTypeNum != ''){
            if(costTypeIdCache.length == maxCostTypeNum-1){
                $("#view" + thisCostType).attr('class','blue');
                costTypeIdCache.push(thisCostType);
                costTypeNameCache.push($(thisObj).attr('name'));
            }else{
                alert("��������ķ����������ֻ��ѡ"+maxCostTypeNum+"��!");
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

//�ж������
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

//�ٲ�������
function initMasonry(){
    $('#costTypeInner2').masonry({
        itemSelector: '.box'
    });
}

//�Զ������ѡ���� - ����ѡ��
var selectCostType = function(){

    //��ǰ���ڷ��������ַ���
    var costTypeIds = (costTypeCfgNum > 0)? $("#cm_conFigSub"+costTypeCfgNum).val() : '';
    var costTypeName = (costTypeCfgNum > 0)? $("#cm_conFig"+costTypeCfgNum).val() : '';
    costTypeNameCache = costTypeName.split(",");
    costTypeIdCache = costTypeIds.split(",");

    //��ǰ���ڷ�����������
    var costTypeIdArr = (costTypeIds =='')? [] : costTypeIds.split(",");

    $.ajax({
        type: "POST",
        url: "?model=finance_expense_costtype&action=getCostType",
        async: false,
        success: function(data){
            if(data != ""){
                $("#costTypeInner").html("<div id='costTypeInner2'>" + data + "</div>")
                if(costTypeIds != ""){
                    //��ֵ
                    for(var i = 0; i < costTypeIdArr.length;i++){
                        $("#chk" + costTypeIdArr[i]).attr('checked',true);
                        $("#view" + costTypeIdArr[i]).attr('class','blue');
                    }

                }
            }else{
                alert('û���ҵ��Զ���ķ�������');
            }

        }
    });

    $("#costTypeInner").dialog({
        title : '������������',
        closed: false,
        height : 400,
        width : 760
    });

    //��ʱ�������򷽷�
    setTimeout(function(){
        initMasonry();
        if(checkExplorer() == 1){
            $("#costTypeInner2").height(560).css("overflow-y","scroll");
        }
    },200);
}