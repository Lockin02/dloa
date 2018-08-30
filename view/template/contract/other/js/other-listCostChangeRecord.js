$(function(){
    var type = $("#type").val();
    var objId = $("#objId").val();
    var title = (type == "returnMoney")? "返款记录" : "不开票记录";

    var colModel = [];
    if(type == "returnMoney"){
        colModel = [
            {
                display: 'id',
                name: 'id',
                width: 20,
                sortable: true,
                process: function (v,row) {
                    if(row.id == 'noId'){
                        return '';
                    }else{
                        return v;
                    }
                }
            }, {
                name: 'objCode',
                display: '合同编号',
                width: 130
            } , {
                name: 'objName',
                display: '合同名称',
                width: 130
            } ,{
                name: 'createTime',
                display: '单据日期',
                width: 130
            }, {
                name: 'createName',
                display: '操作人',
                sortable: true,
                width: 80
            },{
                name: 'contractMoney',
                display: "合同金额",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'incomeMoney',
                display: "付款金额",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'invoiceMoney',
                display: "已收发票",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'costAmount',
                display: "返款金额",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'remarks',
                display: '备注',
                sortable: true,
                width: 350
            }
        ];
    }else{
        colModel = [
            {
                display: 'id',
                name: 'id',
                width: 20,
                sortable: true,
                process: function (v,row) {
                    if(row.id == 'noId'){
                        return '';
                    }else{
                        return v;
                    }
                }
            }, {
                name: 'objCode',
                display: '合同编号',
                width: 130
            } ,{
                name: 'createName',
                display: '操作人',
                sortable: true,
                width: 80
            },{
                name: 'createTime',
                display: '单据日期',
                width: 130
            }, {
                name: 'isRed',
                display: "是否红字",
                sortable: true,
                process: function (v,row) {
                    if(row.id == 'noId'){
                        return '';
                    }else{
                        return (v == 0)? "否" : "是";
                    }
                },
                width: 80
            },{
                name: 'contractMoney',
                display: '合同金额',
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            } ,{
                name: 'invoiceMoney',
                display: "开票金额",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'incomeMoney',
                display: "到款金额",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'uninvoiceMoney',
                display: "已录不开票金额",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'canUninvoiceMoney',
                display: "可录不开票金额",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'costAmount',
                display: "不开票金额",
                sortable: true,
                process: function (v,row) {
                    var valNum = (row.isRed == 1)? "-"+moneyFormat2(v) : moneyFormat2(v);
                    var returnStr = (row.isRed == 1)? "<div style='text-align: right;color:red;'>"+valNum+"</div>" : "<div style='text-align: right'>"+valNum+"</div>";
                    return returnStr;
                },
                width: 80
            },{
                name: 'remarks',
                display: '备注',
                sortable: true,
                width: 350
            }
        ];
    }

    $("#otherGrid").yxgrid({
        model: 'contract_other_other',
        action: 'listCostChangeRecordJson',
        param:  {"type":type, "objId":objId},
        title: title,
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        isOpButton: false,
        showcheckbox : false,
        //列信息
        colModel: colModel,
        // 默认搜索字段名
        sortname: "c.id",
        // 默认搜索顺序 降序DESC 升序ASC
        sortorder: "DESC"
    });
});