var show_page = function(page) {
    $("#loanGrid").yxgrid("reload");
};

$(function() {
    $("#loanGrid").yxgrid({
        model   : 'loan_loan_loan',
        action  : 'listMyLoanJson',
        param   : {"projectId" : $("#projectId").val()},
        title   : '����б�',
        isViewAction    : false,
        isAddAction     : false,
        isEditAction    : false,
        isDelAction     : false,
        showcheckbox    : false,

        //����Ϣ
        colModel : [{
            name: 'PrepaymentDate',
            display: '�Ƿ����ڹ黹',
            sortable: true,
            width: 70,
            align: 'center',
            process: function (v, row) {
                if (v) {
                    if (row.PrepaymentDateDays != '' && row.PrepaymentDateDays != '-'){
                        return "<img title='"+v+" ����δ����' src='images/icon/red.gif'></img>";
                    }else{
                        return "<img title='"+v+" δ����' src='images/icon/green.gif'></img>";
                    }
                }else if(row.mark == "sum"){
                    return "�ϼ�";
                }else{
                    return "-";
                }
            }
        },{
            name : 'id',
            display : '������뵥ID',
            sortable : true,
            width: 80,
            hide: true,
            align: 'center'
        }, {
            name : 'DebtorName',
            display : '�����',
            sortable : false,
            width: 70
        }, {
            name : 'Debtor',
            display : '�����ID',
            sortable : true,
            hide : true,
            width: 70
        }, {
            name : 'DebtorPersnlNum',
            display : '����˹���',
            sortable : false,
            hide : true,
            width: 70
        }, {
            name : 'Amount',
            display : '�����',
            sortable : true,
            process: function (v, row) {
                if (v == '') {
                    return "<div class='text-right'>0.00</div>";
                } else {
                    return "<div class='text-right'>"+moneyFormat2(v)+"</div>";
                }
            }
        },{
            name : 'reMoney',
            display : '�軹���',
            hide : true,
            process: function (v, row) {
                if (v == '') {
                    return "<div class='text-right'>0.00</div>";
                } else {
                    return "<div class='text-right'>"+moneyFormat2(v)+"</div>";
                }
            }
        },{
            name : 'Reason',
            display : '�������',
            sortable : true
        }, {
            name : 'PrepaymentDate',
            display : 'Ԥ�ƻ���ʱ��',
            sortable : true
        }, {
            name : 'Status',
            display : '����״̬',
            sortable : true
        },  {
            name : 'no_writeoff',
            display : '�Ƿ��������',
            sortable : true,
            hide : true,
            process: function (v,row) {
                if (v == '1') {
                    return "��ֹ����";
                }else if(row.mark != 'sum' ){
                    return "�������";
                }else{
                    return "";
                }
            }
        }, {
            name : 'loanNatureName',
            display : '�������',
            sortable : true
        }, {
            name : 'loanNature',
            display : '�������ID',
            sortable : true,
            hide : true
        },{
            name : 'ReceiptDT',
            display : 'ʵ�ʻ���ʱ��',
            sortable : true,
            hide : true
        },{
            name : 'isBackMoney',
            display : '�ѻ�����',
            process: function (v,row) {
                var tempMoney = row.Amount - row.reMoney;
                return "<div class='text-right'>"+moneyFormat2(tempMoney)+"</div>";
            }
        },{
            name : 'PrepaymentDateDays',
            display : '��������',
            align: 'center',
            process: function (v) {
                return v;
            }
        },{
            name : 'PayDT',
            display : '���ɸ���ʱ��',
            sortable : true
        }],

        menusEx: [
            {
                text: '�鿴',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row != undefined) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    if (row) {
                        showThickboxWin("?model=loan_loan_loan&action=toViewTab&id="
                            + row.id
                            + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
                    }
                }

            }
        ],
        comboEx: [{
            text: '�������',
            key: 'loanNature',
            data: [
                {
                    text: '�ⷿѺ��',
                    value: '1'
                },{
                    text: '���ڽ��',
                    value: '2'
                }
            ]
        },{
            text: '���״̬',
            key: 'Status',
            data: [
                {
                    text: '�༭',
                    value: '�༭'
                },{
                    text: '���',
                    value: '���'
                },{
                    text: '��������',
                    value: '��������'
                },{
                    text: '���������',
                    value: '���������'
                },{
                    text: 'δ����',
                    value: 'δ����'
                },{
                    text: '�ѻ���',
                    value: '�ѻ���'
                }
            ]
        }],
        searchitems : [{
            display : "��ID",
            name : 'IDSch'
        },{
            display : "�����",
            name : 'DebtorNameSch'
        },{
            display : "����˹���",
            name : 'DebtorPersnlNumSch'
        },{
            display : "�������",
            name : 'Reason'
        },{
            display : "��Ŀ��Ϣ",
            name : 'ProjectNoSch'
        },{
            display : "����˹�˾",
            name : 'DebtorCompanySch'
        },{
            display : "Ԥ�ƻ���ʱ��",
            name : 'PrepaymentDateSch'
        }]
    });

    /**
     * ʱ�����ĸ�ʽ��;
     */
    Date.prototype.format = function (format) {
        /*
         * eg:format="YYYY-MM-dd hh:mm:ss";
         */
        var o = {
            "M+": this.getMonth() + 1, // month
            "d+": this.getDate(), // day
            "h+": this.getHours(), // hour
            "m+": this.getMinutes(), // minute
            "s+": this.getSeconds(), // second
            "q+": Math.floor((this.getMonth() + 3) / 3), // quarter
            "S": this.getMilliseconds()
            // millisecond
        }

        if (/(y+)/.test(format)) {
            format = format.replace(RegExp.$1, (this.getFullYear() + "")
                .substr(4 - RegExp.$1.length));
        }

        for (var k in o) {
            if (new RegExp("(" + k + ")").test(format)) {
                format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k]
                    : ("00" + o[k]).substr(("" + o[k]).length));
            }
        }
        return format;
    }
});