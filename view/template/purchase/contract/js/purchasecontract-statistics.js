//搜索方法
function searchBtn() {
    window.open("?model=purchase_contract_purchasecontract&action=statisticsSearch"
        + "&logic=" + $("#logic").val()
        + "&field=" + $("#field").val()
        + "&relation=" + $("#relation").val()
        + "&values=" + $("#values").val()
        , 'newwindow1', 'height=500,width=900');
}
//校验数值框为必填
function checkValues() {
    $.each($(".values"), function () {
        if ($(this).val() == "") {
            alert("请输入查询值");
            $(this).focus();
            return false;
        }
    });
}