//��ʾloading
function showLoading(){
    $("#loading").show();
}

//����
function hideLoading(){
    $("#loading").hide();
}

//��ʼ��������ͳ�Ʊ�
function initDataGrid() {
    var beginDate = $("#beginDate").val();
    var endDate = $("#endDate").val();

    if (beginDate == "" || endDate == "") {
        alert('��ѡ����������');
        return false;
    }
    showLoading(); // ��ʾ����ͼ��

    var objGrid = $("#dataListGrid");

    setTimeout(function(){
        //��������
        $.ajax({
            url: '?model=finance_expense_expense&action=searchAliGridJson',
            data: {
                userId: $("#userId").val(),
                beginDate: beginDate,
                endDate: endDate
            },
            type: 'POST',
            async: false,
            success: function(data) {
                if (objGrid.html() != "") {
                    objGrid.empty();
                }
                objGrid.html(data);
                hideLoading(); // ���ؼ���ͼ��

                // ��Ⱦ ǧ��λ���
                $.each($(".formatMoney"), function(i, n) {
                    if ($(this).get(0).tagName == 'INPUT') {
                        var strHidden = "<input type='hidden' name='" + n.name
                            + "' id='" + n.id + "' value='" + n.value + "' />";
                        $(this).attr('name', '');
                        $(this).attr('id', n.id + '_v');
                        $(this).val(moneyFormat2(n.value, 2));
                        $(this).bind("blur", function() {
                            moneyFormat1(this, 2);
                            if (n.onblur)
                                n.onblur();
                        });
                        $(this).after(strHidden);
                    } else {
                        returnMoney = moneyFormat2($(this).text(), 2);
                        $(this).text(returnMoney);
                    }
                });
            }
        });
    },200);
}

//����������ͳ��
function exportExcel() {
    var url = "?model=finance_expense_expense&action=exportAliGridJson"
            + "&userId=" + $("#userId").val()
            + "&beginDate=" + $("#beginDate").val()
            + "&endDate=" + $("#endDate").val()
        ;
    showOpenWin(url, 1, 150, 300, 'exportExcel');
}

$(function(){
    showLoading();
    initDataGrid();
})
