//��������
function searchBtn() {
    window.open("?model=purchase_contract_purchasecontract&action=statisticsSearch"
        + "&logic=" + $("#logic").val()
        + "&field=" + $("#field").val()
        + "&relation=" + $("#relation").val()
        + "&values=" + $("#values").val()
        , 'newwindow1', 'height=500,width=900');
}
//У����ֵ��Ϊ����
function checkValues() {
    $.each($(".values"), function () {
        if ($(this).val() == "") {
            alert("�������ѯֵ");
            $(this).focus();
            return false;
        }
    });
}