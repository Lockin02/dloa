var show_page = function(page) {
    $("#loanGrid").yxgrid("reload");
};

$(function() {
    $("#loanGrid").yxgrid({
        model   : 'loan_loan_loan',
        action  : 'listMyLoanJson',
        param   : {"projectId" : $("#projectId").val()},
        title   : '借款列表',
        isViewAction    : false,
        isAddAction     : false,
        isEditAction    : false,
        isDelAction     : false,
        showcheckbox    : false,

        //列信息
        colModel : [{
            name: 'PrepaymentDate',
            display: '是否逾期归还',
            sortable: true,
            width: 70,
            align: 'center',
            process: function (v, row) {
                if (v) {
                    if (row.PrepaymentDateDays != '' && row.PrepaymentDateDays != '-'){
                        return "<img title='"+v+" 逾期未还款' src='images/icon/red.gif'></img>";
                    }else{
                        return "<img title='"+v+" 未逾期' src='images/icon/green.gif'></img>";
                    }
                }else if(row.mark == "sum"){
                    return "合计";
                }else{
                    return "-";
                }
            }
        },{
            name : 'id',
            display : '借款申请单ID',
            sortable : true,
            width: 80,
            hide: true,
            align: 'center'
        }, {
            name : 'DebtorName',
            display : '借款人',
            sortable : false,
            width: 70
        }, {
            name : 'Debtor',
            display : '借款人ID',
            sortable : true,
            hide : true,
            width: 70
        }, {
            name : 'DebtorPersnlNum',
            display : '借款人工号',
            sortable : false,
            hide : true,
            width: 70
        }, {
            name : 'Amount',
            display : '借款金额',
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
            display : '需还金额',
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
            display : '借款理由',
            sortable : true
        }, {
            name : 'PrepaymentDate',
            display : '预计还款时间',
            sortable : true
        }, {
            name : 'Status',
            display : '单据状态',
            sortable : true
        },  {
            name : 'no_writeoff',
            display : '是否允许冲销',
            sortable : true,
            hide : true,
            process: function (v,row) {
                if (v == '1') {
                    return "禁止冲销";
                }else if(row.mark != 'sum' ){
                    return "允许冲销";
                }else{
                    return "";
                }
            }
        }, {
            name : 'loanNatureName',
            display : '借款性质',
            sortable : true
        }, {
            name : 'loanNature',
            display : '借款性质ID',
            sortable : true,
            hide : true
        },{
            name : 'ReceiptDT',
            display : '实际还款时间',
            sortable : true,
            hide : true
        },{
            name : 'isBackMoney',
            display : '已还款金额',
            process: function (v,row) {
                var tempMoney = row.Amount - row.reMoney;
                return "<div class='text-right'>"+moneyFormat2(tempMoney)+"</div>";
            }
        },{
            name : 'PrepaymentDateDays',
            display : '逾期天数',
            align: 'center',
            process: function (v) {
                return v;
            }
        },{
            name : 'PayDT',
            display : '出纳付款时间',
            sortable : true
        }],

        menusEx: [
            {
                text: '查看',
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
            text: '借款性质',
            key: 'loanNature',
            data: [
                {
                    text: '租房押金',
                    value: '1'
                },{
                    text: '短期借款',
                    value: '2'
                }
            ]
        },{
            text: '借款状态',
            key: 'Status',
            data: [
                {
                    text: '编辑',
                    value: '编辑'
                },{
                    text: '打回',
                    value: '打回'
                },{
                    text: '部门审批',
                    value: '部门审批'
                },{
                    text: '变更审批中',
                    value: '变更审批中'
                },{
                    text: '未还款',
                    value: '未还款'
                },{
                    text: '已还款',
                    value: '已还款'
                }
            ]
        }],
        searchitems : [{
            display : "借款单ID",
            name : 'IDSch'
        },{
            display : "借款人",
            name : 'DebtorNameSch'
        },{
            display : "借款人工号",
            name : 'DebtorPersnlNumSch'
        },{
            display : "借款理由",
            name : 'Reason'
        },{
            display : "项目信息",
            name : 'ProjectNoSch'
        },{
            display : "借款人公司",
            name : 'DebtorCompanySch'
        },{
            display : "预计还款时间",
            name : 'PrepaymentDateSch'
        }]
    });

    /**
     * 时间对象的格式化;
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