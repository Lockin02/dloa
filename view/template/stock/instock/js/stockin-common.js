/**��������ID��ȡһ������*/
function getParentProType(proTypeId){
    var parentProType="";
    var parentProTypeId="";
    $.ajax({// ��ѯһ��
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