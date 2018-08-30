var show_page = function(page) {
    $("#financialList").yxgrid("reload");
};

$(function() {
    $("#financialList").yxgrid({
        model : 'contract_contract_contract',
		action : 'TdayPageJson',
//        param : {
//            'states' : '1,2,3,4,5,6,7',
//            'isTemp' : '0',
//            'ExaStatus' : '���',
//            'signStatusArr' : '1'
//        },

        title : '�տ�ȷ��',
        isViewAction : false,
        isEditAction : false,
        isDelAction : false,
        isAddAction : false,
        isOpButton : false,
//        customCode : 'contractSigninCom',
        buttonsEx : [{
            text: "����",
            icon: 'delete',
            action: function (row) {
                history.go(0);
            }
        },{
            name : 'Add',
            text : "����ȷ��",
            icon : 'add',
            action : function(rowData, rows, rowIds, g) {
                if (rowData) {
                    var checkArr = new Array();
                    for (var i = 0; i < rows.length; i++) {
                        var rNum = rows[i].rowNum;
                        if($("#tday"+rNum).val() == '' ){
                            alert("T-�գ����ڲ�����Ϊ�գ�");
                            return false;
                        }else if(rows[i].isConfirm == '1'){
                            alert("��ȷ��T-�����ݲ�����ʹ������ȷ�ϲ�����");
                            return false;
                        }else{
                            var jsonTemp = {
                                id : rows[i].id,
                                tday : $("#tday"+rNum).val()
                            }
                            checkArr.push(jsonTemp);
                        }
                    }
                    $.ajax({
                        type : 'POST',
                        url : "?model=contract_contract_contract&action=updateTdayBatch",
                        data : {
                            checkArr : checkArr
                        },
                        async : false,
                        success : function(data) {
                            if (data == '1') {
                                alert("ȷ�����");
                                $("#financialList").yxgrid("reload");
                            }else{
//                                alert(data)
                                alert("ȷ��ʧ��");
                                $("#financialList").yxgrid("reload");
                            }
                        }
                    });
                } else {
                    alert('����ѡ���¼');
                }
            }
        }
//            ,{
//            name : 'export',
//            text : "����",
//            icon : 'excel',
//            action : function(row) {
//                var searchConditionKey = "";
//                var searchConditionVal = "";
//                for (var t in $("#comsigninGrid").data('yxgrid').options.searchParam) {
//                    if (t != "") {
//                        searchConditionKey += t;
//                        searchConditionVal += $("#comsigninGrid")
//                            .data('yxgrid').options.searchParam[t];
//                    }
//                }
//                var state = $("#state").val();
//                var ExaStatus = $("#ExaStatus").val();
//                var contractType = $("#contractType").val();
//                var beginDate = $("#comsigninGrid").data('yxgrid').options.extParam.beginDate;//��ʼʱ��
//                var endDate = $("#comsigninGrid").data('yxgrid').options.extParam.endDate;//��ֹʱ��
//                var ExaDT = $("#comsigninGrid").data('yxgrid').options.extParam.ExaDT;//����ʱ��
//                var areaNameArr = $("#comsigninGrid").data('yxgrid').options.extParam.areaNameArr;//��������
//                var orderCodeOrTempSearch = $("#comsigninGrid").data('yxgrid').options.extParam.orderCodeOrTempSearch;//��ͬ���
//                var prinvipalName = $("#comsigninGrid").data('yxgrid').options.extParam.prinvipalName;//��ͬ������
//                var customerName = $("#comsigninGrid").data('yxgrid').options.extParam.customerName;//�ͻ�����
//                var customerType = $("#comsigninGrid").data('yxgrid').options.extParam.customerType;//�ͻ�����
//                var orderNatureArr = $("#comsigninGrid").data('yxgrid').options.extParam.orderNatureArr;//��ͬ����
//                var DeliveryStatusArr = $("#comsigninGrid").data('yxgrid').options.extParam.DeliveryStatusArr;//�Ƿ��з�����¼
//                var i = 1;
//                var colId = "";
//                var colName = "";
//                $("#comsigninGrid_hTable").children("thead").children("tr")
//                    .children("th").each(function() {
//                        if ($(this).css("display") != "none"
//                            && $(this).attr("colId") != undefined) {
//                            colName += $(this).children("div").html() + ",";
//                            colId += $(this).attr("colId") + ",";
//                            i++;
//                        }
//                    })
//
//                window.open("?model=contract_contract_contract&action=singInExportExcel&colId="
//                    + colId + "&colName=" + colName+ "&state=" + state + "&ExaStatus=" + ExaStatus + "&contractType=" + contractType
//                    + "&beginDate=" + beginDate + "&endDate=" + endDate + "&ExaDT=" + ExaDT
//                    + "&areaNameArr=" + areaNameArr + "&orderCodeOrTempSearch=" + orderCodeOrTempSearch
//                    + "&prinvipalName=" + prinvipalName + "&customerName=" + customerName
//                    + "&customerType=" + customerType
//                    + "&orderNatureArr=" + orderNatureArr
//                    + "&DeliveryStatusArr=" + DeliveryStatusArr
//                    + "&searchConditionKey="
//                    + searchConditionKey
//                    + "&searchConditionVal="
//                    + searchConditionVal
//                    + "&signStatusArr=1"
////								+ "&ExaStatus=���,���������"
////								+ "&states=2,3,4"
//                    + "&1width=200,height=200,top=200,left=200,resizable=yes")
//            }
//        }
        ],
        // ��չ�Ҽ��˵�

        menusEx : [{
            text : '�鿴',
            icon : 'view',
            action : function(row) {
                showModalWin('?model=contract_contract_contract&action=toViewTab&id='
                    + row.id + "&skey=" + row['skey_']);
            }
        }],

        // ����Ϣ
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }, {
            name : 'isFlag',
            display : '�Ƿ�ȷ��',
            sortable : true,
            align: 'center',
            width : 50
        }, {
            name : 'ExaDtOne',
            display : '����ʱ��',
            sortable : true,
            width : 80,
            hide : true
        }, {
            name : 'contractCode',
            display : '��ͬ���',
            sortable : true,
            width : 120,
            process : function(v, row) {
                return  '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.contractId
                    +'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'+"<font color = '#4169E1'>" + v +"</font>"+'</a>';
            }
        }, {
            name : 'contractName',
            display : '��ͬ����',
            sortable : true,
            width : 150,
            hide : true
        }, {
            name : 'paymentterm',
            display : '��������',
            sortable : true,
            width : 120
        }, {
            name : 'paymentPer',
            display : '����ٷֱ�',
            sortable : true,
            width : 60,
            align: 'center',
            process : function(v) {
                return v + "%";
            }
        }, {
            name : 'money',
            display : '�ƻ�������',
            sortable : true,
            align: 'right',
            width : 80,
            process : function(v, row) {
                return moneyFormat2(v);
            }
        }, {
            name : 'proEndDate',
            display : '��ͬ���ʱ��',
            sortable : true,
            width : 80
        }, {
            name : 'planInvoiceMoney',
            display : '�ƻ���Ʊ���',
            sortable : true,
            align: 'right',
            width : 80,
            process : function(v, row) {
                return moneyFormat2(v);
            }
        }, {
            name : 'remark',
            display : '��ע',
            sortable : true,
            hide : true
        }, {
            name : 'schedulePer',
            display : '���Ȱٷֱ�',
            align: 'center',
            sortable : true,
            process : function(v) {
                if(v > 0){
                    return v + "%";
                }else{
                    return "-";
                }
            }
        },{
            name : 'Tday',
            display : '����T-��',
            align: 'center',
            sortable : true
//            process : function(v,row){
//                if(row.confirmStatus == '��ȷ��' && row.checkStatus == '������'){
//                    return v+'<span class="blue" onclick = "changeHistory('+row.id+');"> <img title="�鿴�����ʷ" src="images/icon/view.gif"></span>';
//                }else if(row.confirmStatus == '��ȷ��'){
//                    return '<input type="text" id="checkDate'+row.id+'" onfocus="WdatePicker();" style="width:100px" class="Wdate" value="'+ v +'"/>'+'<span class="blue" onclick = "changeHistory('+row.id+');"> <img title="�鿴�����ʷ" src="images/icon/view.gif"></span>';
//                }else{
//                    return '<input type="text" id="checkDate'+row.id+'" onfocus="WdatePicker();" style="width:100px" class="Wdate" value="'+ v +'"/>'+'<span class="blue" onclick = "changeHistory('+row.id+');"> <img title="�鿴�����ʷ" src="images/icon/view.gif"></span>';
//                }
//            }
        }, {
            name : 'invoiceMoney',
            display : '��Ʊ���',
            width : 80,
            sortable : true,
            align: 'right',
            process : function(v, row) {
                return moneyFormat2(v);
            }
        }, {
            name : 'incomMoney',
            display : '������',
            width : 80,
            sortable : true,
            align: 'right',
            process : function(v, row) {
                return moneyFormat2(v);
            }
        }, {
            name : 'unInvoiceMoney',
            display : 'δ��Ʊ���',
            width : 80,
            align: 'right',
            process : function(v, row) {
            	var tempMoney = row.money - row.deductMoney - row.invoiceMoney;
                return moneyFormat2(tempMoney);
            }
        }, {
            name : 'unIncomMoney',
            display : 'δ������',
            width : 80,
            align: 'right',
            process : function(v, row) {
                var tempMoney = row.money - row.deductMoney - row.incomMoney;
                return moneyFormat2(tempMoney);
            }
        }, {
            name : 'deductMoney',
            display : '�ۿ���',
            width : 60,
            align: 'right',
            sortable : true,
            process : function(v, row) {
                return moneyFormat2(v);
            },
            hide : true
        }, {
            name : 'isCom',
            display : '�Ƿ����',
            sortable : true,
            align: 'center',
            width : 60,
            process : function(v){
                if(v == 0){
                    return "δ���";
                }else{
                    return "�����";

                }
            }
        }, {
            name : 'comDate',
            display : '���ʱ��',
            sortable : true,
            width : 60,
            hide : true
        }, {
            name : 'periodName',
            display : '����״̬',
            sortable : true,
            hide : true
        }, {
            name : 'confirmBtn',
            display : 'ȷ��T��',
            sortable : true,
            width : 80
        }, {
            name : 'changeTips',
            display : '���ԭ��',
            sortable : true,
            width : 320
        }],
        comboEx : [{
            text : '�Ƿ����',
            key : 'isCom',
            data : [{
                text : '�����',
                value : '1'
            }, {
                text : 'δ���',
                value : '0'
            }]
        }, {
            text : '�Ƿ�ȷ��',
            key : 'isConfirm',
            value : '0',
            data : [{
                text : 'δȷ��',
                value : '0'
            }, {
                text : '��ȷ��',
                value : '1'
            }]
        },{
            text: '����',
            key: 'contractType',
            data: [
                {
                    text: '���ۺ�ͬ',
                    value: 'HTLX-XSHT'
                },
                {
                    text: '�����ͬ',
                    value: 'HTLX-FWHT'
                },
                {
                    text: '���޺�ͬ',
                    value: 'HTLX-ZLHT'
                },
                {
                    text: '�з���ͬ',
                    value: 'HTLX-YFHT'
                }
            ]
        }],
        /**
         * ��������
         */
        searchitems : [{
            display : '��ͬ���',
            name : 'contractCode'
        }, {
            display : '��ͬ����',
            name : 'contractName'
        }]
    });
});


function confirmTday(id, k,isConfirm) {
    var tday = $("#tday" + k).val();
    var tdayOld = $("#tdayOld"+k).val();
    if (tday == '' || tday == undefined) {
        alert("����ȷѡ�񡰲���T-�ա�");
    } else {
        if(isConfirm == '1'){
             if($("#changeTips"+k).val() == ''){
                 alert("����д���ԭ��");
                 return false;
             }else{
                 var changeTips = $("#changeTips"+k).val();
             }
        }else{
            var changeTips = "";
        }
        $.ajax({
            type : 'POST',
            url : "?model=contract_contract_contract&action=updateTday",
            data : {
                id : id,
                tday : tday,
                tdayOld : tdayOld,
                changeTips : changeTips
            },
            async : false,
            success : function(data) {
                if (data == '1') {
                    alert("ȷ�����")
                    $("#financialList").yxgrid("reload");
                }else{
                    alert(data)
                }
            }
        });
    }
}


//�鿴�����ʷ
function changeHistory(id){
    showThickboxWin('?model=contract_contract_contract&action=showChanceHistory&id='
        + id
        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600');
}
