//����֤����
function checkform() {
    if ($("#inputExcel").val() == "") {
        alert("��ѡ����Ҫ�����EXCEL�ļ�");
        return false;
    }

//	alert("��ǰ����δ���");
    $("#loading").show();

    return true;
}

//��ע��Ϣ��ʾ
function changeInfo(thisVal) {
    if (thisVal == 0) {
        $("#remarkInfo").html('�˵��빦�ܶ�Ӧ����ͬ��Ϣ��excelģ�壬����ʱ���ѯ��Ŀ����Ƿ��Ѵ��ڣ�<br/>��������£���������������Ŀ��<br/>���빦��ֻ֧��<span class="red">��ͬ����Ŀ/�޺�ͬ��</span>���롣<br/>������������뽫����/����/��˾�����ֶζ���д��ɣ����򲻻����������Ϣ��');
    } else if (thisVal == 1) {
        $("#remarkInfo").html('�˵��빦�ܶ�Ӧ<span class="red">���������ģ�塷</span>��<br/>�ɲ�����Աʹ�ã����ڵ��������Ŀ���ܷ��á�');
    } else if (thisVal == 2) {
        $("#remarkInfo").html('1.�˵��빦�ܶ�Ӧ<span class="red">����Ŀ����ģ�塷</span>�����ڸ�����Ŀ���ݣ�����������Ŀ����<br/>' +
            '2.�˹���<span class="red">������</span>�ܾ��㡢�ֳ�����(���ñ���)���ֳ�����(����ά��)��<br/>' +
            '���ý����Լ���ͬ��Ϣ��<br/>' +
            '3.ʹ�ô˹��ܸ����ֳ�Ԥ�㡢����Ԥ�㡢�豸Ԥ����ܵ�����ĿԤ����Ϣ���ҡ�');
    } else if (thisVal == 3) {
        $("#remarkInfo").html('�˵��빦�ܶ�Ӧ������õ�ģ�壬<br/>���ڸ�����Ŀ������ã�������ҵ����');
    } else if (thisVal == 4) {
        $("#remarkInfo").html('�˵��빦�ܶ�Ӧ����Ԥ���㣬<br/>���ڸ�����Ŀ����Ԥ���㣬������ҵ����');
    } else if (thisVal == 5) {
        $("#remarkInfo").html('�˵��빦�ܶ�Ӧ���Ԥ���㣬<br/>���ڸ�����Ŀ���Ԥ���㣬������ҵ����');
    } else if (thisVal == 6) {
        $("#remarkInfo").html('�˵��빦�ܶ�Ӧ����Ԥ���㣬<br/>���ڸ�������Ԥ���㣬������ҵ����');
    } else if (thisVal == 7) {
        $("#remarkInfo").html('�˵��빦�ܶ�Ӧ�豸���㣬<br/>���ڸ����豸���㣬������ҵ����');
    }

    var spanId = 'span' + thisVal;
    var buttonId = 'button' + thisVal;

    $.each($("span[id^='span']"), function (i, n) {
        if (this.id == spanId) {
            this.className = 'green';
        } else {
            this.className = '';
        }
    });

    $.each($("input[id^='button']"), function (i, n) {
        if (this.id == buttonId) {
            this.className = 'txt_btn_a_green';
        } else {
            this.className = 'txt_btn_a';
        }
    });
}