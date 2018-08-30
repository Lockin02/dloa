$(function() {
    var mainColModel = [{
        display: 'id',
        name: 'id',
        type: 'hidden'
    },{
        display: '������ϸ�ϼ�',
        name: 'parentTypeName'
    },{
        display: '������ϸ',
        name: 'costTypeName',
        process: function(v,row){
            var proLineCode = $("#proLineCode").val();
            return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=contract_conproject_conproject" +
                "&action=feeCostDetailList&costTypeId=" + row.costTypeId + "&contractId=" + row.contractId +
                "&proLineCode="+proLineCode+
                "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500\")'>" + v + "</a>";
        }
    },{
        display: '���',
        name: 'costMoney',
        process: function(v,row){
            return moneyFormat2(v);
        }
    }];

    var detailColModel = [
        {
            display: 'id',
            name: 'id',
            type: 'hidden'
        },{
            display: '������ϸ',
            name: 'costTypeName',
            process: function(v,row){
                return v;
            }
        },{
            display: '����',
            name: 'inPeriod',
            process: function(v,row){
                return v;
            }
        },{
            display: '���',
            name: 'costMoney',
            process: function(v,row){
                return moneyFormat2(v);
            }
        }
    ];

    var paramArr = {cId: $("#contractId").val(),proLineCode: $("#proLineCode").val()};
    var currentModel = [];
    switch($("#showType").val()){
        case 'detail':
            $("#wrap").removeClass('wrapWidth');
            paramArr = {cId: $("#contractId").val(),costTypeId: $("#costTypeId").val(),proLineCode: $("#proLineCode").val()};
            currentModel = detailColModel;
            break;
        default:
            currentModel = mainColModel;
            break;
    }

    $("#pageGrid").yxeditgrid({
        url: '?model=contract_conproject_conproject&action=feeCostListJson',
        type: 'view',
        param: paramArr,
        //����Ϣ
        colModel: currentModel,
        event: {
        }
    });
});