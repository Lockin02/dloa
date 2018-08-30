$(document).ready(function () {
    initElements();

    // 数据更新
    $("#updateConprojectData").click(function(){
        var contractCode = $("#contractCode0").val();
        var contract = getContractByCode(contractCode);
        if(contract && contract == 'null'){
            $("#tips").text("无相关合同");
        }else{
            $("#tips").text("");
        }
        var contractId = (contract.id != undefined)? contract.id : '';
        var statesVal =  $('#radioc0-updateStates option:selected').val();
        if(confirm('更新数据会需要花费较长时间，确定进行此操作吗？')){
            updateFunctions("updateConprojectData",{contractId : contractId,states : statesVal});
        }
    });

    // 更新检查项结果
    $("#updateCheckItems").click(function(){
        var contractCode = $("#contractCode1").val();
        var contract = getContractByCode(contractCode);
        if(contract && contract == 'null'){
            $("#tips").text("无相关合同");
        }else{
            $("#tips").text("");
        }
        var contractId = (contract.id != undefined)? contract.id : '';
        if(confirm('更新数据会需要花费较长时间，确定进行此操作吗？')){
            updateFunctions("updateCheckItems",{contractId : contractId});
        }
    });
});

// 通过合同号获取合同信息
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

// 变更操作类型
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

// 将部分共用的元素初始化
var initElements = function(){
    $("#tips").text("");
    $(".inputInfo").val("");
    $("#showMsg").css("color","#3F5393");
    $("#showMsg").text("");
};

// 更新数据的方法
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

    //显示数据处理提示
    $("#showMsg").css("color","#3F5393");
    $("#showMsg").text('数据更新中......');
    var imgObj = $("#imgLoading");
    //显示进度图
    imgObj.show();
    $("#trip").show();

    //禁用按钮
    var btnObj = $(".cltBtn");
    btnObj.attr('disabled', true);

    setTimeout(function () {
        //调用更新功能
        if(url != ""){
            var backData = $.ajax({
                type : "POST",
                data: paramObj,
                url : url,
                dataType : 'json',
                async : false
            }).responseText;

            if(backData == 1){
                $("#showMsg").text('更新成功!');

                //隐藏进度图,开放按钮操作
                imgObj.hide();
                btnObj.attr('disabled', false);
            }else{
                $("#showMsg").text('更新失败!');
                $("#showMsg").css("color","red");

                //隐藏进度图,开放按钮操作
                imgObj.hide();
                btnObj.attr('disabled', false);
            }
        }else{
            $("#showMsg").text('关联业务路径有误,请联系系统管理员。');
            $("#showMsg").css("color","red");

            //隐藏进度图,开放按钮操作
            imgObj.hide();
            btnObj.attr('disabled', false);
        }
    }, 200);
}