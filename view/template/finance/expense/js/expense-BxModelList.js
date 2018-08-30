// 弹窗的宽高
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
        fit: true,   //自适应大小
        columns: [[
            { field: "id", title: "id", align: "center", hidden : false, width: 30},
            { field: "templateName", title: "模块名称", align: "center", width: 100},
            { field: "contentId", title: "默认字段ID", align: "left", hidden : true, width: 30},
            { field: "content", title: "默认字段", align: "left", width: 250 },
            { field: "operation", title: "操作", align: "center", width: 100, formatter: function (value, row, index)
            {
                var str = "";
                str += "<a href=\"#\" onclick=\"editModel(" + row.id + ")\">编辑</a>";
                str += "&nbsp;&nbsp;&nbsp;&nbsp;";
                str += "<a href=\"#\" onclick=\"delModel('" + row.id +"')\">删除</a>";
                return str;
            }
            }
        ]],
        toolbar: [{
            iconCls: "icon-add",
            text: "新增模板",
            handler: function () {
                cleanPage();
                addModel();
            }
        }],
        onLoadSuccess: function () {
        },
    });
}

// 清理页面
function cleanPage(){
    // 如果存在dialog窗口,则关闭
    if($('#modifyModel_Box').parent('.panel').css("display") == 'block'){
        $('#modifyModel_Box iframe').attr("src","");
        $('#modifyModel_Box').hide();
        $('#modifyModel_Box').dialog({
            closed: true
        })
    }
}

// 添加表单流程
function addModel(){
    // 显示弹框
    $('#modifyModel_Box iframe').attr("src","?model=finance_expense_expense&action=toAddModel");
    $('#modifyModel_Box').show();
    $('#modifyModel_Box').removeClass("hidden").dialog({
        title: '添加报销模板',
        closed: false,
        width: boxWidth,
        height: boxHeight
    });
}

// 编辑模板
function editModel(id){
    cleanPage();

    // 显示弹框
    $('#modifyModel_Box iframe').attr("src","?model=finance_expense_expense&action=toEditModel&id="+id);
    $('#modifyModel_Box').show();
    $('#modifyModel_Box').removeClass("hidden").dialog({
        title: '编辑报销模板',
        closed: false,
        width: boxWidth,
        height: boxHeight
    });
}

// 删除模板
function delModel(id){
    cleanPage();//先清除页面所以弹框
    if(confirm("确定要删除模板吗?")){
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
            alert("删除成功!");
        }else{
            alert("删除失败!");
        }
        loadList();
    }
}
function reloadParentModelList(){
    parent.reloadModelList();
}
// ============================= 维护表单公用函数（开始) ========================= //
$(function(){
    if($("#isFormPage").val() != undefined && $("#isFormPage").val() == 1){
        //获取当前有的费用类型
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
// ============================= 维护表单公用函数（结束) ========================= //