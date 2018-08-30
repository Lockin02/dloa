$(function(){
    var type = $("#type").val();
    var objId = $("#objId").val();
    var title = (type == "returnMoney")? "�����¼" : "����Ʊ��¼";

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
                display: '��ͬ���',
                width: 130
            } , {
                name: 'objName',
                display: '��ͬ����',
                width: 130
            } ,{
                name: 'createTime',
                display: '��������',
                width: 130
            }, {
                name: 'createName',
                display: '������',
                sortable: true,
                width: 80
            },{
                name: 'contractMoney',
                display: "��ͬ���",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'incomeMoney',
                display: "������",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'invoiceMoney',
                display: "���շ�Ʊ",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'costAmount',
                display: "������",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'remarks',
                display: '��ע',
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
                display: '��ͬ���',
                width: 130
            } ,{
                name: 'createName',
                display: '������',
                sortable: true,
                width: 80
            },{
                name: 'createTime',
                display: '��������',
                width: 130
            }, {
                name: 'isRed',
                display: "�Ƿ����",
                sortable: true,
                process: function (v,row) {
                    if(row.id == 'noId'){
                        return '';
                    }else{
                        return (v == 0)? "��" : "��";
                    }
                },
                width: 80
            },{
                name: 'contractMoney',
                display: '��ͬ���',
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            } ,{
                name: 'invoiceMoney',
                display: "��Ʊ���",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'incomeMoney',
                display: "������",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'uninvoiceMoney',
                display: "��¼����Ʊ���",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'canUninvoiceMoney',
                display: "��¼����Ʊ���",
                sortable: true,
                process: function (v) {
                    return "<div style='text-align: right'>"+moneyFormat2(v)+"</div>";
                },
                width: 80
            },{
                name: 'costAmount',
                display: "����Ʊ���",
                sortable: true,
                process: function (v,row) {
                    var valNum = (row.isRed == 1)? "-"+moneyFormat2(v) : moneyFormat2(v);
                    var returnStr = (row.isRed == 1)? "<div style='text-align: right;color:red;'>"+valNum+"</div>" : "<div style='text-align: right'>"+valNum+"</div>";
                    return returnStr;
                },
                width: 80
            },{
                name: 'remarks',
                display: '��ע',
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
        //����Ϣ
        colModel: colModel,
        // Ĭ�������ֶ���
        sortname: "c.id",
        // Ĭ������˳�� ����DESC ����ASC
        sortorder: "DESC"
    });
});