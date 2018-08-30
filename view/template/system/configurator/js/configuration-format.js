var costTypeNameCache = [];
var costTypeIdCache = [];
var costTypeCfgNum = 0;
var maxCostTypeNum = 0;

// ��ʼ����������
var initCommonForm = function(){
    // ������ж�̬���ص��ֶ���Ϣ
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

    // �������ж�̬���ص��ֶ�
    $(".cm_configItem").hide();
    $("#cm_conFig1ExtWrap").html("");
    $("#cm_conFig2ExtWrap").html("");
    $("#cm_conFig3ExtWrap").html("");

    if($("#cm_conFig1Textarea").val() != undefined){
        $(".cm_conFig1TextareaRelative").remove();
    }

    // �����������ó�ʼ������
    $("#cm_conFig3").yxselect_user('remove');

    // ��ԭ�ӹ�Ч���Լ�������
    $("#cm_conFig1").attr("name","configurator[config_item1]");
    $("#cm_conFig1").show();
    $("#cm_conFig2").show();
    $("#cm_conFig3").show();
    $("#cm_conFig2").removeAttr("readonly");
    $("#cm_conFig2").unbind("click");
}

// ������ģ�����¼��ر���ʽ
var reloadConfigForm = function(mainType,act){
    $("#cm_conFig1Opts").remove();
    $("div.cm_conFig1TextareaRelative").text('');
    // �ٸ�������������ʾ��Ӧ�ı��ֶ�
    switch (mainType){
        // �����߱�������
        case 'FWBXXZ':
            $("#cm_belongDeptTr").show();
            $("#cm_conFig1Tr").show();
            $("#cm_conFig2Tr").show();
            $("#cm_conFig1Label").text("���Ʒ������� ");
            $("#cm_conFig2Label").text("���ƿ�Ŀ");

            // ���Ʒ�������
            var expenseTypesObj = {
                1 :'���ŷ���',
                2 :'��ͬ��Ŀ����',
                3 :'�з�����',
                4 :'��ǰ����',
                5 :'�ۺ����'
            };

            var expenseTypesOpts = "<option value=''>...��ѡ��...</option>";
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

            // ����ϵͳ��Ŀ
            costTypeCfgNum = 2;
            maxCostTypeNum = '';
            if(act == "edit"){
                // ����Ĭ��ѡ�еķ�����������
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

        // �⳵������Ŀ��Ӧ
        case "ZCFYMM":
            $("#cm_conFig1Tr").show();
            $("#cm_conFig2Tr").show();
            $("#cm_conFig3Tr").show();
            $("#cm_conFig1Label").text("�⳵ϵͳ��Ŀ");
            $("#cm_conFig2Label").text("����ϵͳ��Ŀ");
            $("#cm_conFig3Label").text("�����ɷ���");

            // �⳵ϵͳ��Ŀ
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

            // �����ɷ���ѡ��
            $("#cm_conFig3").hide();
            var useForBuildFeeTipRadioStr = '<input type="radio" class="useForBuildFeeTip" checked="" name="radioOpt" value="1">�� <input class="useForBuildFeeTip" type="radio" name="radioOpt" value="0">��';
            $("#cm_conFig3ExtWrap").html(useForBuildFeeTipRadioStr);
            $(".useForBuildFeeTip").unbind('change');
            $(".useForBuildFeeTip").bind('change',function(e){
                var tipName = ($(this).val() == 1)? '��' : '��';
                $("#cm_conFig3").val(tipName);
                $("#cm_conFigSub3").val($(this).val());
            });

            costTypeCfgNum = 2;
            maxCostTypeNum = 1;// �⳵������Ŀ��Ӧ�ķ���ϵͳ��Ŀ���ֻ��ѡһ��
            var carRentCostTypesComboboxDefault = [];
            if(act == "edit"){
                // ����Ĭ��ѡ�еķ�����������
                costTypeNameCache = $("#cm_conFig2").val().split(",");
                costTypeIdCache = $("#cm_conFigSub2").val().split(",");
                carRentCostTypesComboboxDefault = ($("#cm_conFigSub1").val() != '')? $("#cm_conFigSub1").val().split(",") : [];
            }else{
                $("#cm_conFig3").val('��');
                $("#cm_conFigSub3").val(1);
            }

            $('#carRentCostTypesCombobox').combobox('setValues',carRentCostTypesComboboxDefault);

            $("#cm_conFig2").attr("readonly",1);
            $("#cm_conFig2").click(selectCostType);
            break;
        // ��־�澯����
        case "RZGJJOBFILTER":
            $("#cm_conFig1Tr").show();
            $("#cm_conFig1Label").text("��־�澯��������");
            $("#cm_conFig1").hide();
            $("#cm_conFig1").attr("name","");
            $("#cm_conFig1").after("<div id='cm_conFig1Opts'><textarea id='cm_conFig1Textarea' class='cm_conFig1TextareaRelative' name='configurator[config_item1]' rows='10' cols='55'>"+$("#cm_conFig1").val()+"</textarea><div class='cm_conFig1TextareaRelative' style='color:red;'>ע��: �������ö�����Զ��� \",\" ����</div></div>");
            break;
        // Ӧ�տ�����
        case "YSKPZ":
            // �⳵ϵͳ��Ŀ
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
            $("#cm_conFig1Label").text("������Ӧ�տ��\n�ͻ�����");
            $("#cm_conFig1").hide();
            $("#cm_conFig1").attr("name","");
            $("#cm_conFig1").after("<div id='cm_conFig1Opts'><input type='hidden' name='configType' value='YSKPZ'>"+customerTypesStr+"</div>");
            break;
        // �������÷�����Ŀ��Ӧ
        case "ALSLFYMM":
            $("#cm_conFig1Tr").show();
            $("#cm_conFig2Tr").show();
            $("#cm_conFig1Label").text("�������÷�����");
            $("#cm_conFig2Label").text("����ϵͳ��Ŀ");

            costTypeCfgNum = 2;
            maxCostTypeNum = 1;// �⳵������Ŀ��Ӧ�ķ���ϵͳ��Ŀ���ֻ��ѡһ��
            var carRentCostTypesComboboxDefault = [];
            if(act == "edit"){
                // ����Ĭ��ѡ�еķ�����������
                costTypeNameCache = $("#cm_conFig2").val().split(",");
                costTypeIdCache = $("#cm_conFigSub2").val().split(",");
                carRentCostTypesComboboxDefault = ($("#cm_conFigSub1").val() != '')? $("#cm_conFigSub1").val().split(",") : [];
            }

            $('#carRentCostTypesCombobox').combobox('setValues',carRentCostTypesComboboxDefault);

            $(".cm_conFig1TextareaRelative").remove();
            $("#cm_conFig1").after("<div class='cm_conFig1TextareaRelative' style='color:red;'>ע��: �������÷�����������Ҫ�밢�������ϵ�һ��,<br>������ܻᵼ���޷�ƥ�䡣</div>");
            $("#cm_conFig2").attr("readonly",1);
            $("#cm_conFig2").click(selectCostType);
            break;
        // ���۲���
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
            $("#cm_conFig1Label").text("��������̯ģ���\n��������");
            $("#cm_conFig1").hide();
            $("#cm_conFig1").attr("name","");
            $("#cm_conFig1").after("<div id='cm_conFig1Opts'><input type='hidden' name='configType' value='SALEDEPT'>"+costBelongDeptStr+"</div>");

            // console.log(costBelongDeptArr);
            break;
        // ����С������������
        case "BXXLAUDITORS":
            $("#cm_conFig1Tr").show();
            $("#cm_conFig2Tr").show();
            $("#cm_conFig3Tr").show();
            $("#cm_conFig4Tr").show();
            $("#cm_conFig1Label").text("���Ʒ������� ");
            $("#cm_conFig2Label").text("���ƿ�Ŀ");
            $("#cm_conFig3Label").text("������");
            $("#cm_conFig4Label").text("�����ڵ�");

            // ���Ʒ�������
            var expenseTypesObj = { 1 :'���ŷ���', 2 :'��ͬ��Ŀ����', 3 :'�з�����', 4 :'��ǰ����', 5 :'�ۺ����' };
            var expenseTypesOpts = "<option value=''>...��ѡ��...</option>";
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

            // ����ϵͳ��Ŀ
            costTypeCfgNum = 2;
            maxCostTypeNum = '';
            if(act == "edit"){
                // ����Ĭ��ѡ�еķ�����������
                costTypeNameCache = $("#cm_conFig2").val().split(",");
                costTypeIdCache = $("#cm_conFigSub2").val().split(",");
            }
            $("#cm_conFig2").attr("readonly",1);
            $("#cm_conFig2").click(selectCostType);

            // ������
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

            // �����ڵ�
            var auditPointObj = {1 :'��һ��',2 :'��������ǰ'};
            var auditPointOpts = "<option value=''>...��ѡ��...</option>";
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
        // �ܼ�����
        case "MAJORCONFIG":
            $("#cm_conFig1Tr").show();
            $("#cm_conFig2Tr").show();
            $("#cm_conFig1Label").text("���");
            $("#cm_conFig2Label").text("��Ա");

            // ������
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
        //���ù�������
        case 'FYGSDEPT':
            $("#cm_belongDeptTr").show();

            $("#cm_belongDeptName").yxselect_dept('remove');
            $("#cm_belongDeptName").yxselect_dept({
                hiddenId : 'cm_belongDeptId',
                mode : 'check'
            });
            $("#cm_belongDeptName").before("<div class='cm_conFig1TextareaRelative' style='color:red;'>ע��: ���������õĲ���,<br>���������ڷ��ù������š�</div>");
            break;

    }
}

// ���������б��ʼ��
var configuratorItemsList = function( data ){
    var url = "index1.php" + publicUrl + "&action=getConfiguratorItemsList&mainId="+data.id;

    var attributesObj = (data.attributes != '')? eval("("+data.attributes+")") : {};
    var columnsCfg = [];
    var toolbarCfg = [];
    switch(attributesObj.code){
        // ������̯����
        case "BXFT_CONFIG1":
            // ����ͷ������Ϣ
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 50},
                { field: "belongDeptNames", title: "���ò���", align: "center", width: 180 },
                { field: "belongDeptIds", title: "���ò���ID", align: "center", width: 180 },
                { field: "remarks", title: "��ע��Ϣ", align: "center", width: 250 },
                { field: "operation", title: "����", align: "center", width: 150, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id  + ",'"+ attributesObj.code +"')\">�༭</a>";
                    return str;
                } }
            ]];
            break;

        // �����߱�������
        case "FWBXXZ":
            // ����ͷ������Ϣ
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 50},
                { field: "belongDeptNames", title: "���ò���", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.belongDeptNames+"</div>";
                    return str;
                }},
                { field: "config_item1", title: "���Ʒ�������", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item1+"</div>";
                    return str;
                }},
                { field: "config_item2", title: "���ƿ�Ŀ", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item2+"</div>";
                    return str;
                }},
                { field: "remarks", title: "��ע��Ϣ", align: "center", width: 250,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                    return str;
                }},
                { field: "operation", title: "����", align: "center", width: 150, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id + ",'"+ attributesObj.code +"')\">�༭</a>";
                    str += "&nbsp;&nbsp;<a href=\"#\" onclick=\"deleteConfiguratorItem(" + row.id + ")\">ɾ��</a>";
                    return str;
                } }
            ]];

            // ����ģ�������ť
            toolbarCfg = [{
                iconCls: "icon-add",
                text: "��������",
                handler: function () {
                    addConfigItem(attributesObj.code,data.text,data.id);
                }
            }];
            break;

        // �⳵������Ŀ��Ӧ
        case "ZCFYMM":
            // ����ͷ������Ϣ
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 50},
                { field: "config_item1", title: "�⳵ϵͳ��Ŀ", align: "center", width: 180 ,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item1+"</div>";
                    return str;
                }},
                { field: "config_item2", title: "����ϵͳ��Ŀ", align: "center", width: 180 ,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item2+"</div>";
                    return str;
                }},
                { field: "config_item3", title: "�����ɷ���", align: "center", width: 180 ,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item3+"</div>";
                    return str;
                }},
                { field: "remarks", title: "��ע��Ϣ", align: "center", width: 250 ,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                    return str;
                }},
                { field: "operation", title: "����", align: "center", width: 150, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id + ",'"+ attributesObj.code +"')\">�༭</a>";
                    str += "&nbsp;&nbsp;<a href=\"#\" onclick=\"deleteConfiguratorItem(" + row.id + ")\">ɾ��</a>";
                    return str;
                } }
            ]];

            // ����ģ�������ť
            toolbarCfg = [{
                iconCls: "icon-add",
                text: "��������",
                handler: function () {
                    addConfigItem(attributesObj.code,data.text,data.id);
                }
            }];
            break;
        // ��־�澯����
        case "RZGJJOBFILTER":
            // ����ͷ������Ϣ
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 10},
                { field: "config_item1", title: "��־�澯��������", align: "center", width: 50 },
                { field: "remarks", title: "��ע��Ϣ", align: "center", width: 25,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                    return str;
                }},
                { field: "operation", title: "����", align: "center", width: 15, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id  + ",'"+ attributesObj.code +"')\">�༭</a>";
                    return str;
                } }
            ]];
            break;
        // Ӧ�տ�����
        case "YSKPZ":
            // ����ͷ������Ϣ
            columnsCfg = [[
                // { field: "id", title: "id",align: "center", width: 10},
                { field: "config_item1", title: "������Ӧ�տ�Ŀͻ�����", align: "center", width: 60,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal;padding:10px;'>"+row.config_item1+"</div>";
                    return str;
                }},
                { field: "remarks", title: "��ע��Ϣ", align: "center", width: 35,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                    return str;
                }},
                { field: "operation", title: "����", align: "center", width: 15, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id  + ",'"+ attributesObj.code +"',480,480)\">�༭</a>";
                    return str;
                } }
            ]];
            break;

        // �������÷�����Ŀ��Ӧ
        case "ALSLFYMM":
            // ����ͷ������Ϣ
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 50},
                { field: "config_item1", title: "�������÷�����", align: "center", width: 180 ,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item1+"</div>";
                    return str;
                }},
                { field: "config_item2", title: "����ϵͳ��Ŀ", align: "center", width: 180 ,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item2+"</div>";
                    return str;
                }},
                { field: "remarks", title: "��ע��Ϣ", align: "center", width: 250 ,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                    return str;
                }},
                { field: "operation", title: "����", align: "center", width: 150, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id + ",'"+ attributesObj.code +"')\">�༭</a>";
                    str += "&nbsp;&nbsp;<a href=\"#\" onclick=\"deleteConfiguratorItem(" + row.id + ")\">ɾ��</a>";
                    return str;
                } }
            ]];

            // ����ģ�������ť
            toolbarCfg = [{
                iconCls: "icon-add",
                text: "��������",
                handler: function () {
                    addConfigItem(attributesObj.code,data.text,data.id);
                }
            }];
            break;
        // ���۲���
        case "SALEDEPT":
            // ����ͷ������Ϣ
            columnsCfg = [[
                { field: "belongDeptNames", title: "���ò���", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal;padding:10px;'>"+row.belongDeptNames+"</div>";
                    return str;
                }},
                { field: "belongDeptIds", title: "���ò���ID", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal;padding:10px;'>"+row.belongDeptIds+"</div>";
                    return str;
                }},
                { field: "remarks", title: "��ע��Ϣ", align: "center", width: 250 },
                { field: "operation", title: "����", align: "center", width: 150, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id  + ",'"+ attributesObj.code +"',480,380)\">�༭</a>";
                    return str;
                } }
            ]];
            break;
        // ����С������������
        case "BXXLAUDITORS":
            // ����ͷ������Ϣ
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 50},
                { field: "config_item1", title: "��������", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item1+"</div>";
                    return str;
                }},
                { field: "config_item2", title: "��Ŀ", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item2+"</div>";
                    return str;
                }},
                { field: "config_item3", title: "������", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item3+"</div>";
                    return str;
                }},
                { field: "config_item4", title: "�����ڵ�", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item4+"</div>";
                    return str;
                }},
                { field: "remarks", title: "��ע��Ϣ", align: "center", width: 250,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                    return str;
                }},
                { field: "operation", title: "����", align: "center", width: 150, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id + ",'"+ attributesObj.code +"')\">�༭</a>";
                    str += "&nbsp;&nbsp;<a href=\"#\" onclick=\"deleteConfiguratorItem(" + row.id + ")\">ɾ��</a>";
                    return str;
                } }
            ]];

            // ����ģ�������ť
            toolbarCfg = [{
                iconCls: "icon-add",
                text: "��������",
                handler: function () {
                    addConfigItem(attributesObj.code,data.text,data.id);
                }
            }];
            break;

        // �ܼ�����
        case "MAJORCONFIG":
            // ����ͷ������Ϣ
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 50},
                { field: "config_item1", title: "���", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item1+"</div>";
                    return str;
                }},
                { field: "config_item2", title: "��Ա", align: "center", width: 180,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.config_item2+"</div>";
                    return str;
                }},
                { field: "remarks", title: "��ע��Ϣ", align: "center", width: 250,formatter: function (value, row, index){
                    var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                    return str;
                }},
                { field: "operation", title: "����", align: "center", width: 150, formatter: function (value, row, index)
                {
                    var str = "";
                    str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id + ",'"+ attributesObj.code +"')\">�༭</a>";
                    str += "&nbsp;&nbsp;<a href=\"#\" onclick=\"deleteConfiguratorItem(" + row.id + ")\">ɾ��</a>";
                    return str;
                } }
            ]];

            // ����ģ�������ť
            toolbarCfg = [{
                iconCls: "icon-add",
                text: "��������",
                handler: function () {
                    addConfigItem(attributesObj.code,data.text,data.id);
                }
            }];
            break;

        // ���ù�������
        case "FYGSDEPT":
            // ����ͷ������Ϣ
            columnsCfg = [[
                { field: "id", title: "id",align: "center", width: 10},
                { field: "belongDeptNames", title: "���ò��� ( �������ò���,���������ڷ��ù��� )",align: "center",width: 43,formatter: function (value, row, index){
                        var str = "<div style='white-space: normal'>"+row.belongDeptNames+"</div>";
                        return str;
                    }},
                { field: "belongDeptIds", title: "���ò���ID", align: "center", width: 30,formatter: function (value, row, index){
                        var str = "<div style='white-space: normal'>"+row.belongDeptIds+"</div>";
                        return str;
                    }},
                { field: "remarks", title: "��ע��Ϣ", align: "center", width: 20,formatter: function (value, row, index){
                        var str = "<div style='white-space: normal'>"+row.remarks+"</div>";
                        return str;
                    }},
                { field: "operation", title: "����", align: "center", width: 10, formatter: function (value, row, index)
                    {
                        var str = "";
                        str += "<a href=\"#\" onclick=\"editConfiguratorItem(" + row.id + ",'"+ attributesObj.code +"')\">�༭</a>";
                        // str += "&nbsp;&nbsp;<a href=\"#\" onclick=\"deleteConfiguratorItem(" + row.id + ")\">ɾ��</a>";
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
        fit: true,   //����Ӧ��С
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