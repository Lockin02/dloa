$(document).ready(function () {
    $("#assesManName").yxselect_user({
        hiddenId: 'assesManId',
        mode: "single",
        formCode: 'assessTask'
    });
    if ($("#assessType").val() == "gysjd") {
        $(".gysjd").show();
    }
    $("#assesQuarter").val($("#thisQuarter").val());

    /**
     * ��֤��Ϣ
     */
    validate({
        "assesManName": {
            required: true
        },
        "hopeDate": {
            required: true
        },
        "assessType": {
            required: true
        }
    });
});

//��֤�Ƿ�ѡ������������
function checkSelectSupp() {
    var assessType = $("#assessType").val();
    if (assessType == "") {
        alert("����ѡ����������");
    }
}

//��֤�Ƿ�ѡ������������
function checkAssesType() {
    var assessType = $("#assessType").val();
    if (assessType == "") {
        alert("����ѡ����������");
    }
}
//��ʼʱ�������ʱ�����֤
function timeCheck($t) {
    var s = plusDateInfo('formDate', 'hopeDate');
    if (s < 0) {
        alert("�´�ʱ�䲻�ܱ��������ʱ����");
        $t.value = "";
        return false;
    }
}

//�ύ����֤����
function checkData(){
    //��֤�ù�Ӧ�̸ü��ȵ��Ƿ��Ѵ��ڿ�������
    var flag=true;
    $.ajax({
        type : 'POST',
        url : '?model=supplierManage_assessment_task&action=checkData',
        data:{
            suppId : $("#suppId").val(),
            assesYear : $("#assesYear").val(),
            assesQuarter : $("#assesQuarter").val(),
            assessType: $("#assessType").val()
        },
        async: false,
        success : function(data){
            if(data==0){
                alert("�ù�Ӧ�̸ü������´￼������");
                flag=false;
            }
        }
    });
    return flag;
}