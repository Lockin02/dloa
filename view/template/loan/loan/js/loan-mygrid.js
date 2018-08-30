var show_page = function(page) {
	$("#loanMyGrid").yxgrid("reload");
};

$(function() {
    var buttonsArr = [{
        name : 'Add',
        // hide : true,
        text : "����",
        icon : 'add',
        action : function(row) {
            showThickboxWin("?model=loan_loan_loan&action=toAdd"
                + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
        }
    },{
        name : 'excelOut',
        text : "����",
        icon : 'excel',
        action : function(row) {
            var searchConditionKey = "";
            var searchConditionVal = "";
            for (var t in $("#loanMyGrid").data('yxgrid').options.searchParam) {
                if (t != "") {
                    searchConditionKey += t;
                    searchConditionVal += $("#loanMyGrid").data('yxgrid').options.searchParam[t];
                }
            }

            var i = 1;
            var colId = "";
            var colName = "";
            $("#loanMyGrid_hTable").children("thead").children("tr")
                .children("th").each(function () {
                if ($(this).css("display") != "none"
                    && $(this).attr("colId") != undefined) {
                    colName += $(this).children("div").html() + ",";
                    colId += $(this).attr("colId") + ",";
                    i++;
                }
            })
            var searchSql = $("#loanMyGrid").data('yxgrid').getAdvSql();
            var pageArr = [];
            pageArr['colName'] = colName;
            pageArr['colId'] = colId;
            pageArr['searchSql'] = searchSql;
            pageArr['schConditionKey'] = searchConditionKey;
            pageArr['schConditionVal'] = searchConditionVal;

            var msg = $.ajax({
                url: '?model=loan_loan_loan&action=setColInfoToSession',
                data: 'ColId=' + colId + '&ColName='+colName,
                dataType: 'html',
                type: 'post',
                async: false
            }).responseText;

            if(msg == 1){
                window.open("?model=loan_loan_loan&action=excelOut");
            }
        }
    }];

	$("#loanMyGrid").yxgrid({
		model   : 'loan_loan_loan',
        action  : 'listMyLoanJson',
        param   : {"Debtor" : $("#debtor").val()},
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
            name : 'createDate',
            display : '����ʱ��',
            sortable : true
        }, {
            name : 'PrepaymentDate',
            display : 'Ԥ�ƻ���ʱ��',
            sortable : true
        }, {
            name : 'Status',
            display : '����״̬',
            sortable : true,
            process: function (v,row) {
                if(v == '����֧��'){
                    return "δ֧��";
                }else{
                    return v;
                }
            }
        },  {
            name : 'no_writeoff',
            display : '�Ƿ��������',
            sortable : true,
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
            name : 'XmFlag',
            display : '�������',
            process: function (v,row) {
                if (v == '1') {
                    return "��Ŀ���";
                }else if(row.mark != 'sum' ){
                    return "���Ž��";
                }else{
                    return "";
                }
            },
            sortable : true
        },{
            name : 'loanNatureName',
            display : '�������',
            sortable : true
        }, {
            name : 'loanNature',
            display : '�������ID',
            sortable : true,
            hide : true
        },{
            name : 'DebtorCompany',
            display : '����˹�˾��Ϣ',
            hide : true
        },{
            name : 'DebtorDeptName',
            display : '����˲���',
            hide : true
        },{
            name : 'DebtorBusiness',
            display : '�������ҵ��',
            hide : true
        },{
            name : 'DebtorModule',
            display : '�������',
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
            hide : true,
            process: function (v) {
                return v;
            }
        },{
            name : 'isBackInfo',
            display : '�������',
            hide : true,
            process: function (v,row) {
                var str = (row.mark != 'sum')? '<a href=\'javascript:void(0)\' onclick=\'showThickboxWin\("general/costmanage/query/loan/repayment_view.php?ID='
                    + row.id
                    + '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")\'>�������</a>' : '';

                return str;
            }
        },{
            name : 'ProjectNo',
            display : '��Ŀ��Ϣ',
            sortable : true,
            width : 110,
            hide : true,
            process: function (v) {
                return v;
            }
        },{
            name : 'PayType',
            display : '֧����ʽ',
            sortable : true,
            hide : true
        },{
            name : 'PayDT',
            display : '���ɸ���ʱ��',
            sortable : true,
            hide : true
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

            },
            {
                text: '�༭',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row != undefined && (row.Status == "�༭" || row.Status == "���")) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    if (row) {
                        showThickboxWin("?model=loan_loan_loan&action=toEdit&id="
                            + row.id
                            + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
                    } else {
                        alert("��ѡ��һ������");
                    }
                }
            },
            {
                text: '�����',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row != undefined && row.loanNature == 1 && (row.Status == "δ����" || row.Status == "����֧��") &&  row.ExaStatus != '���������') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {

                        showThickboxWin("?model=loan_loan_loan&action=toChange&id="
                            + row.id
                            + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
                    } else {
                        alert("��ѡ��һ������");
                    }
                }
            },
            {
                text: 'ɾ��',
                icon: 'delete',
                showMenuFn: function (row) {
                    if (row != undefined && (row.Status == "�༭" || row.Status == "���")) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    if (window.confirm(("ȷ��Ҫɾ��?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=loan_loan_loan&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('ɾ���ɹ���');
                                    show_page();
                                }
                            }
                        });
                    }
                }
            }
        ],
        buttonsEx : buttonsArr,
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
                    text: 'δ֧��',
                    value: '����֧��'
                },{
                    text: 'δ����',
                    value: 'δ����'
                },{
                    text: '�ѻ���',
                    value: '�ѻ���'
                }
            ]
        },{
            text: '�������',
            key: 'XmFlag',
            data: [
                {
                    text: '���Ž��',
                    value: '0'
                },
                {
                    text: '��Ŀ���',
                    value: '1'
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