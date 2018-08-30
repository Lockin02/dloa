$(document).ready(function () {
    initElements();

    // ���ݸ���
    $("#updateConprojectData").click(function(){
        var contractCode = $("#contractCode0").val();
        var contract = getContractByCode(contractCode);
        if(contract && contract == 'null'){
            $("#tips").text("����غ�ͬ");
        }else{
            $("#tips").text("");
        }
        var contractId = (contract.id != undefined)? contract.id : '';
        var statesVal =  $('#radioc0-updateStates option:selected').val();
        if(confirm('�������ݻ���Ҫ���ѽϳ�ʱ�䣬ȷ�����д˲�����')){
            updateFunctions("updateConprojectData",{contractId : contractId,states : statesVal});
        }
    });

    // ���¼������
    $("#updateCheckItems").click(function(){
        var contractCode = $("#contractCode1").val();
        var contract = getContractByCode(contractCode);
        if(contract && contract == 'null'){
            $("#tips").text("����غ�ͬ");
        }else{
            $("#tips").text("");
        }
        var contractId = (contract.id != undefined)? contract.id : '';
        if(confirm('�������ݻ���Ҫ���ѽϳ�ʱ�䣬ȷ�����д˲�����')){
            updateFunctions("updateCheckItems",{contractId : contractId});
        }
    });
});

// ͨ����ͬ�Ż�ȡ��ͬ��Ϣ
function getContractByCode(contractCode){
    if(contractCode == ''){
        $("#tips").text("");
        return 'all';
    }else{
        var url = "?model=contract_contract_contract&action=ajaxGetContract";
        var backData = $.ajax({
            type : "POST",
            data: {contractCode : contractCode, contractName : ''},
            url : url,
            dataType : 'json',
            async : false
        }).responseText;
        var data = (backData != '')? eval("(" + backData + ")") : 'null';
        return data;
    }
}

// �����������
function changeInfo(thisVal) {
    initElements();

    $("span[id^='remarkInfo']").each(function () {
        var selectdVal = $(this).attr('val');
        if (selectdVal == thisVal) {
            $(this).show();
            $("#span" + selectdVal).addClass('green');
            $("#condition" + selectdVal).show();
            $("#range" + selectdVal).show();
        } else {
            $(this).hide();
            $("#span" + selectdVal).removeClass('green');
            $("#condition" + selectdVal).hide();
            $("#range" + selectdVal).hide();
        }
    });
}

// �����ֹ��õ�Ԫ�س�ʼ��
var initElements = function(){
    $("#tips").text("");
    $(".inputInfo").val("");
    $("#showMsg").css("color","#3F5393");
    $("#showMsg").text("");
};

// �������ݵķ���
var updateFunctions = function(actType,paramObj){
    var url = "";
    var param = paramObj;
    switch (actType){
        case 'updateConprojectData':
            url = "?model=contract_contract_contract&action=ajaxUpdateSalesContractVal";
            break;
        case 'updateCheckItems':
            url = "?model=contract_contract_contract&action=ajaxUpdateCheckedItems";
            break;
    }

    //��ʾ���ݴ�����ʾ
    $("#showMsg").css("color","#3F5393");
    $("#showMsg").text('���ݸ�����......');
    var imgObj = $("#imgLoading");
    //��ʾ����ͼ
    imgObj.show();
    $("#trip").show();

    //���ð�ť
    var btnObj = $(".cltBtn");
    btnObj.attr('disabled', true);

    setTimeout(function () {
        //���ø��¹���
        if(url != ""){
            var backData = $.ajax({
                type : "POST",
                data: paramObj,
                url : url,
                dataType : 'json',
                async : false
            }).responseText;

            if(backData == 1){
                $("#showMsg").text('���³ɹ�!');

                //���ؽ���ͼ,���Ű�ť����
                imgObj.hide();
                btnObj.attr('disabled', false);
            }else{
                $("#showMsg").text('����ʧ��!');
                $("#showMsg").css("color","red");

                //���ؽ���ͼ,���Ű�ť����
                imgObj.hide();
                btnObj.attr('disabled', false);
            }
        }else{
            $("#showMsg").text('����ҵ��·������,����ϵϵͳ����Ա��');
            $("#showMsg").css("color","red");

            //���ؽ���ͼ,���Ű�ť����
            imgObj.hide();
            btnObj.attr('disabled', false);
        }
    }, 200);
}