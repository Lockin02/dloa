// �����Ŀ��
var boxWidth = 800;
var boxHeight = 600;

$(function(){
    loadList();
});

function loadList(){
    var url = "index1.php?model=finance_expense_customtemplate&action=myJsonForModify";
    $("#listWrap").html("");
    $("#listWrap").datagrid({
        url: url,
        singleSelect: true,
        fitColumns: true,
        pageSize: 20,
        pagination: true,
        fit: true,   //����Ӧ��С
        columns: [[
            { field: "id", title: "id", align: "center", hidden : false, width: 30},
            { field: "templateName", title: "ģ������", align: "center", width: 100},
            { field: "contentId", title: "Ĭ���ֶ�ID", align: "left", hidden : true, width: 30},
            { field: "content", title: "Ĭ���ֶ�", align: "left", width: 250 },
            { field: "operation", title: "����", align: "center", width: 100, formatter: function (value, row, index)
            {
                var str = "";
                str += "<a href=\"#\" onclick=\"editModel(" + row.id + ")\">�༭</a>";
                str += "&nbsp;&nbsp;&nbsp;&nbsp;";
                str += "<a href=\"#\" onclick=\"delModel('" + row.id +"')\">ɾ��</a>";
                return str;
            }
            }
        ]],
        toolbar: [{
            iconCls: "icon-add",
            text: "����ģ��",
            handler: function () {
                cleanPage();
                addModel();
            }
        }],
        onLoadSuccess: function () {
        },
    });
}

// ����ҳ��
function cleanPage(){
    // �������dialog����,��ر�
    if($('#modifyModel_Box').parent('.panel').css("display") == 'block'){
        $('#modifyModel_Box iframe').attr("src","");
        $('#modifyModel_Box').hide();
        $('#modifyModel_Box').dialog({
            closed: true
        })
    }
}

// ��ӱ�����
function addModel(){
    // ��ʾ����
    $('#modifyModel_Box iframe').attr("src","?model=finance_expense_expense&action=toAddModel");
    $('#modifyModel_Box').show();
    $('#modifyModel_Box').removeClass("hidden").dialog({
        title: '��ӱ���ģ��',
        closed: false,
        width: boxWidth,
        height: boxHeight
    });
}

// �༭ģ��
function editModel(id){
    cleanPage();

    // ��ʾ����
    $('#modifyModel_Box iframe').attr("src","?model=finance_expense_expense&action=toEditModel&id="+id);
    $('#modifyModel_Box').show();
    $('#modifyModel_Box').removeClass("hidden").dialog({
        title: '�༭����ģ��',
        closed: false,
        width: boxWidth,
        height: boxHeight
    });
}

// ɾ��ģ��
function delModel(id){
    cleanPage();//�����ҳ�����Ե���
    if(confirm("ȷ��Ҫɾ��ģ����?")){
        var backData = $.ajax({
            type : "POST",
            data: {
                modelId : id
            },
            url : "?model=finance_expense_expense&action=deleteModel",
            async : false
        }).responseText;
        if(backData == "ok"){
            reloadParentModelList();
            alert("ɾ���ɹ�!");
        }else{
            alert("ɾ��ʧ��!");
        }
        loadList();
    }
}
function reloadParentModelList(){
    parent.reloadModelList();
}
// ============================= ά�������ú�������ʼ) ========================= //
$(function(){
    if($("#isFormPage").val() != undefined && $("#isFormPage").val() == 1){
        //��ȡ��ǰ�еķ�������
        var costTypeArr = $("#costTypeWrapTb tbody input[id^='costTypeId']");
    }

    $("#modelName").blur(function(){
        $("#modelName_Input").val($(this).val());
    })
});

function setCustomCostType(costTypeId,thisObj){
    $(thisObj).attr("id","costTypeId"+costTypeId);

    var costTypeArr = $("#costTypeWrapTb tbody input[id^='costTypeId']");

    var includeCostTypeIds = $("#includeCostTypeIds_Input").val();
    var costTypeIds = (includeCostTypeIds != '')? includeCostTypeIds.split(",") : [];;
    var includeCostTypes = $("#includeCostTypes_Input").val();
    var costTypeNames = (includeCostTypes != '')? includeCostTypes.split(",") : [];

    costTypeArr.each(function(i,n){
        var typeId = this.value;
        var typeName = this.name;
        if(this.checked){
            $("#view"+typeId).css('color',"blue");
            if(jQuery.inArray( typeId, costTypeIds ) < 0){
                costTypeIds.push(typeId);
            }

            if(jQuery.inArray( typeName, costTypeNames ) < 0){
                costTypeNames.push(typeName);
            }
        }else{
            $("#view"+typeId).css('color',"black");
            var idIndex = jQuery.inArray( typeId, costTypeIds );
            if(idIndex >= 0){
                costTypeIds.splice(idIndex,1);
            }

            var nameIndex = jQuery.inArray( typeName, costTypeNames );
            if(nameIndex >= 0){
                costTypeNames.splice(nameIndex,1);
            }
        }
    });

    var costTypeIdsStr = costTypeIds.join(",");
    $("#includeCostTypeIds_Input").val(costTypeIdsStr);

    var costTypeNamesStr = costTypeNames.join(",");
    $("#includeCostTypes_Input").val(costTypeNamesStr);
    $("#includeCostTypes").text(costTypeNamesStr);
}
// ============================= ά�������ú���������) ========================= //