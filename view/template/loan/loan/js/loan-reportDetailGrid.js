var show_page = function(page) {
    $("#loanGrid").yxgrid("reload");
};
$(function() {
    var searchType = $("#searchType").val();
    var searchParam = $("#searchParam").val();
    var buttonsArr = [];
    var excelOut = {
        name : 'excelOut',
        text : "����",
        icon : 'excel',
        action : function(row) {
            var searchConditionKey = "";
            var searchConditionVal = "";
            for (var t in $("#loanGrid").data('yxgrid').options.searchParam) {
                if (t != "") {
                    searchConditionKey += t;
                    searchConditionVal += $("#loanGrid").data('yxgrid').options.searchParam[t];
                }
            }

            var i = 1;
            var colId = "";
            var colName = "";
            $("#loanGrid_hTable").children("thead").children("tr")
                .children("th").each(function () {
                if ($(this).css("display") != "none"
                    && $(this).attr("colId") != undefined) {
                    colName += $(this).children("div").html() + ",";
                    colId += $(this).attr("colId") + ",";
                    i++;
                }
            })
            var searchSql = $("#loanGrid").data('yxgrid').getAdvSql();
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
    };

    $.ajax({
        type : 'POST',
        url : '?model=loan_loan_loan&action=chkExcelOutLimit',
        async : false,
        success : function(data) {
            if (data == 1) {
                buttonsArr.push(excelOut);
            }
        }
    });

    var param = {'isTemp' : 0};
    if(searchParam != ''){
        var searchParamArr = searchParam.split("|");
        param[searchParamArr[0]] = searchParamArr[1];
    }

    $("#loanGrid").yxgrid({
        model   : 'loan_loan_loan',
        param   : param,
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
            name : 'belongcom',
            display : '����˹�˾��Ϣ'
        },{
            name : 'DebtorDeptName',
            display : '����˲���'
        },{
            name : 'DebtorBusiness',
            display : '�������ҵ��'
        },{
            name : 'DebtorModule',
            display : '�������'
        },{
            name : 'ReceiptDT',
            display : 'ʵ�ʻ���ʱ��',
            sortable : true
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
            name : 'isBackInfo',
            display : '�������',
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
            process: function (v) {
                return v;
            }
        },{
            name : 'PayType',
            display : '֧����ʽ',
            sortable : true
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
                            + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
                    }
                }

            }
        ],
        buttonsEx : buttonsArr,
        comboEx: [],
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
            display : "����˲���",
            name : 'DebtorDeptName'
        },{
            display : "�������",
            name : 'Reason'
        },{
            display : "��Ŀ��Ϣ",
            name : 'ProjectNoSch'
        },{
            display : "����˹�˾",
            name : 'belongcom'
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