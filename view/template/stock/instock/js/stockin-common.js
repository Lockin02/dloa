/**根据类型ID获取一级类型*/
function getParentProType(proTypeId){
    var parentProType="";
    var parentProTypeId="";
    $.ajax({// 查询一级
        type: "POST",
        async: false,
        url: "?model=stock_productinfo_productinfo&action=getParentTypeJson",
        data: {
            "proTypeId": proTypeId
        },
        success: function (result) {
            var obj = eval("(" + result + ")");
            parentProType=obj.proType;
        }
    });
    return parentProType;
}