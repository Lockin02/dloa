var show_page = function(page) {
	$("#loanMyGrid").yxgrid("reload");
};

$(function() {
    var buttonsArr = [{
        name : 'Add',
        // hide : true,
        text : "新增",
        icon : 'add',
        action : function(row) {
            showThickboxWin("?model=loan_loan_loan&action=toAdd"
                + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
        }
    },{
        name : 'excelOut',
        text : "导出",
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
            name : 'createDate',
            display : '申请时间',
            sortable : true
        }, {
            name : 'PrepaymentDate',
            display : '预计还款时间',
            sortable : true
        }, {
            name : 'Status',
            display : '单据状态',
            sortable : true,
            process: function (v,row) {
                if(v == '出纳支付'){
                    return "未支付";
                }else{
                    return v;
                }
            }
        },  {
            name : 'no_writeoff',
            display : '是否允许冲销',
            sortable : true,
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
            name : 'XmFlag',
            display : '借款类型',
            process: function (v,row) {
                if (v == '1') {
                    return "项目借款";
                }else if(row.mark != 'sum' ){
                    return "部门借款";
                }else{
                    return "";
                }
            },
            sortable : true
        },{
            name : 'loanNatureName',
            display : '借款性质',
            sortable : true
        }, {
            name : 'loanNature',
            display : '借款性质ID',
            sortable : true,
            hide : true
        },{
            name : 'DebtorCompany',
            display : '借款人公司信息',
            hide : true
        },{
            name : 'DebtorDeptName',
            display : '借款人部门',
            hide : true
        },{
            name : 'DebtorBusiness',
            display : '借款人事业部',
            hide : true
        },{
            name : 'DebtorModule',
            display : '所属板块',
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
            hide : true,
            process: function (v) {
                return v;
            }
        },{
            name : 'isBackInfo',
            display : '还款情况',
            hide : true,
            process: function (v,row) {
                var str = (row.mark != 'sum')? '<a href=\'javascript:void(0)\' onclick=\'showThickboxWin\("general/costmanage/query/loan/repayment_view.php?ID='
                    + row.id
                    + '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")\'>还款情况</a>' : '';

                return str;
            }
        },{
            name : 'ProjectNo',
            display : '项目信息',
            sortable : true,
            width : 110,
            hide : true,
            process: function (v) {
                return v;
            }
        },{
            name : 'PayType',
            display : '支付方式',
            sortable : true,
            hide : true
        },{
            name : 'PayDT',
            display : '出纳付款时间',
            sortable : true,
            hide : true
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

            },
            {
                text: '编辑',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row != undefined && (row.Status == "编辑" || row.Status == "打回")) {
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
                        alert("请选中一条数据");
                    }
                }
            },
            {
                text: '借款变更',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row != undefined && row.loanNature == 1 && (row.Status == "未还款" || row.Status == "出纳支付") &&  row.ExaStatus != '变更审批中') {
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
                        alert("请选中一条数据");
                    }
                }
            },
            {
                text: '删除',
                icon: 'delete',
                showMenuFn: function (row) {
                    if (row != undefined && (row.Status == "编辑" || row.Status == "打回")) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    if (window.confirm(("确定要删除?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=loan_loan_loan&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('删除成功！');
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
                    text: '未支付',
                    value: '出纳支付'
                },{
                    text: '未还款',
                    value: '未还款'
                },{
                    text: '已还款',
                    value: '已还款'
                }
            ]
        },{
            text: '借款类型',
            key: 'XmFlag',
            data: [
                {
                    text: '部门借款',
                    value: '0'
                },
                {
                    text: '项目借款',
                    value: '1'
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