var show_page = function (page) {
    $("#MyBorrowGrid").yxsubgrid("reload");
};
$(function () {
    addBtn = {
        name: 'Add',
        // hide : true,
        text: "新增",
        icon: 'add',

        action: function (row) {
            showOpenWin('?model=projectmanagent_borrow_borrow&action=toAdd');
        }
    },
        buttonsArr = [
            {
                name: 'edit',
                // hide : true,
                text: "借试用转销售",
                icon: 'edit',
                action: function (row) {
                    showModalWin('?model=projectmanagent_borrow_borrow&action=borrowTurnList'
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                }
            }
        ]
    if ($("#deptName").val() == '海外支撑团队' || $("#createId").val() == 'quanzhou.luo') {
        buttonsArr.push(addBtn);
    }
    $("#MyBorrowGrid").yxsubgrid({
        model: 'projectmanagent_borrow_borrow',
        action: 'MyBorrowPageJson',
        param: {
            'limits': '客户'
            ////			'statusArr' : '0,1'
        },
        title: '我的借试用',
        //按钮
        isViewAction: false,
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        //列信息
        colModel: [
            {
                display: '到期预警',
                name: 'endDate',
                sortable: true,
                width: 30,
                process: function (v, row) {
                    if (row.backStatus == '1') {
                        return "<img src='images/icon/icon073.gif'></img>";
                    } else if (v) {
                        var date = new Date();
                        var time = date.format('yyyy-MM-dd');
                        if (v < time)
                            return "<a href='?model=projectmanagent_penalty_borrowPenalty&action=toMyPage' target='_blank'><img src='images/icon/icon070.gif'></img></a>";
                        if (v > time)
                            return "<img src='images/icon/green.gif'></img>";
                        if (v = time)
                            return "<img src='images/icon/hblue.gif'></img>";
                    }
                }
            },
            {
                display: 'initTip',
                name: 'initTip',
                sortable: true,
                hide: true
            },
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'chanceId',
                display: '商机Id',
                sortable: true,
                hide: true
            },
            {
                name: 'Code',
                display: '编号',
                sortable: true
            },
            {
                name: 'Type',
                display: '类型',
                sortable: true,
                hide: true
            },
            {
                name: 'customerName',
                display: '客户名称',
                sortable: true,
                width: 120
            },
            {
                name: 'salesName',
                display: '销售负责人',
                sortable: true,
                width: 80
            },
            {
                name: 'beginTime',
                display: '开始日期',
                sortable: true,
                width: 80
            },
            {
                name: 'closeTime',
                display: '截止日期',
                sortable: true,
                width: 80
            },
            {
                name: 'scienceName',
                display: '技术负责人',
                sortable: true,
                width: 80
            },
            {
                name: 'status',
                display: '单据状态',
                sortable: true,
                process: function (v) {
                    if (v == '0') {
                        return "正常";
                    } else if (v == '1') {
                        return "部分归还";
                    } else if (v == '2') {
                        return "关闭";
                    } else if (v == '3') {
                        return "退回";
                    } else if (v == '4') {
                        return "续借申请中"
                    } else if (v == '5') {
                        return "转至执行部"
                    } else if (v == '6') {
                        return "转借确认中"
                    } else {
                        return v;
                    }
                },
                width: 70
            },
            {
                name: 'ExaStatus',
                display: '审批状态',
                sortable: true,
                width: 70,
                process: function (v,row) {
                    if(row.lExaStatus != '变更审批中'){
                        return v;
                    }else{
                        return '变更审批中';
                    }
                }
            },
            {
                name : 'checkFile',
                display : '验收文件',
                sortable : true,
                width : 90,
                process: function (v,row) {
                    if(v == '有'){
                        return v;
                    }else{
                        return '否';
                    }
                }
            },
            {
                name: 'backStatus',
                display: '归还状态',
                sortable: true,
                process: function (v) {
                    if (v == '0') {
                        return "未归还";
                    } else if (v == '1') {
                        return "已归还";
                    } else if (v == '2') {
                        return "部分归还";
                    }
                },
                width: 70
            }
            ,
            {
                name: 'DeliveryStatus',
                display: '发货状态',
                sortable: true,
                process: function (v) {
                    if (v == 'WFH') {
                        return "未发货";
                    } else if (v == 'YFH') {
                        return "已发货";
                    } else if (v == 'BFFH') {
                        return "部分发货";
                    } else if (v == 'TZFH') {
                        return "停止发货";
                    }
                },
                width: 70
            },
            {
                name: 'ExaDT',
                display: '审批时间',
                sortable: true,
                hide: true,
                width: 70,
                process: function (v,row){
                    if(row['ExaStatus'] == "部门审批"){
                        return '';
                    }else{
                        return v;
                    }
                }
            },
            {
                name: 'createName',
                display: '创建人',
                sortable: true,
                hide: true
            },
            {
                name: 'remark',
                display: '申请原因',
                sortable: true
            },
            {
                name: 'objCode',
                display: '业务编号',
                width: 120
            }
        ],
        comboEx: [
            {
                text: '归还状态',
                key: 'backStatu',
                data: [
                    {
                        text: '未归还',
                        value: '0'
                    },{
                        text: '已归还',
                        value: '1'
                    },{
                        text: '部分归还',
                        value: '2'
                    }
                ]
            },{
                text: '审批状态',
                key: 'ExaStatus',
                data: [
                    {
                        text: '物料确认',
                        value: '物料确认'
                    },{
                        text: '变更审批中',
                        value: '变更审批中'
                    },{
                        text: '未审批',
                        value: '未审批'
                    },{
                        text: '部门审批',
                        value: '部门审批'
                    },{
                        text: '完成',
                        value: '完成'
                    }
                ]
            }
        ],
        // 主从表格设置
        subGridOptions: {
            url: '?model=projectmanagent_borrow_borrowequ&action=listPageJson',// 获取从表数据url
            // 传递到后台的参数设置数组
            param: [
                {
                    paramId: 'borrowId',// 传递给后台的参数名称
                    colId: 'id'// 获取主表行数据的列名称

                }
            ],
            // 显示的列
            colModel: [
                {
                    name: 'productNo',
                    width: 200,
                    display: '产品编号',
                    process: function (v, row) {
                        return v + "&nbsp;&nbsp;K3:" + row['productNoKS'];
                    }
                },
                {
                    name: 'productName',
                    width: 200,
                    display: '产品名称',
                    process: function (v, row) {
                        return v + "&nbsp;&nbsp;K3:" + row['productNameKS'];
                    }
                },
                {
                    name: 'number',
                    display: '申请数量',
                    width: 80
                },
                {
                    name: 'executedNum',
                    display: '已执行数量',
                    width: 80
                },
                {
                    name: 'applyBackNum',
                    display: '已申请归还数量'
                },
                {
                    name: 'backNum',
                    display: '已归还数量',
                    width: 80
                }
            ]
        },
        /**
         * 快速搜索
         */
        searchitems: [
            {
                display: '编号',
                name: 'Code'
            },
            {
                display: '销售负责人',
                name: 'salesName'
            },
            {
                display: '申请人',
                name: 'createNmae'
            },
            {
                display: '申请日期',
                name: 'createTime'
            },
            {
                display: '客户名称',
                name: 'customerName'
            },
            {
                display: 'K3物料名称',
                name: 'productNameKS'
            },
            {
                display: '系统物料名称',
                name: 'productName'
            },
            {
                display: 'K3物料编码',
                name: 'productNoKS'
            },
            {
                display: '系统物料编码',
                name: 'productNo'
            },
            {
                display: '序列号',
                name: 'serialName2'
            }
        ],
        // 扩展右键菜单
        menusEx: [
            {
                text: '查看',
                icon: 'view',
                action: function (row) {
                    if (row) {
                        showOpenWin("?model=projectmanagent_borrow_borrow&action=toViewTab&id="
                            + row.id + "&skey=" + row['skey_']);
                    }
                }

            },
            {
                text: '编辑',
                icon: 'edit',
                showMenuFn: function (row) {
                    if ((row.ExaStatus == '未审批' || row.ExaStatus == '打回')
                    		&& (row.createId != 'quanzhou.luo' || $("#createId").val() == 'quanzhou.luo')) {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {

                        showOpenWin("?model=projectmanagent_borrow_borrow&action=init&id="
                            + row.id
                            + "&skey="
                            + row['skey_']
                            + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
                    } else {
                        alert("请选中一条数据");
                    }
                }
            },
            {
                text: '提交审核',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '未审批') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    // if (row) {
                    //     showThickboxWin('controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId='
                    //         + row.id
                    //         + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                    // }
                    if (window.confirm(("确定提交吗?"))) {
                        $.ajax({
                            type : "POST",
                            url : "?model=projectmanagent_borrow_borrow&action=ajaxSubForm",
                            data : {
                                id : row.id
                            },
                            success : function(msg) {
                                if(msg != ""){
                                    alert(msg);
                                }else{
                                    alert("提交失败。请重试!");
                                }
                                $("#MyBorrowGrid").yxsubgrid("reload");
                            }
                        });
                    }
                }
            },
            {

                text: '变更',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '部门审批' || row.ExaStatus == '物料确认' || row.ExaStatus == '变更审批中' || row.lExaStatus == '变更审批中' || row.ExaStatus == '未审批'
                    	|| row.initTip == '1' || $("#createId").val() == 'quanzhou.luo' || row.status == 2) {
                        return false;
                    }else if(row.backStatus == '1' && (row.DeliveryStatus == 'YFH' || row.DeliveryStatus == 'BFFH')){
                        return false;
                    }
                    return true;
                },
                action: function (row) {
                    location = '?model=projectmanagent_borrow_borrow&action=toChange&changer=changer&id='
                        + row.id + "&skey=" + row['skey_'];
                }
            },{

                text: '发货物料确认',
                icon: 'edit',
                showMenuFn: function (row) {
                    if((row.needSalesConfirm > 0 && row.ExaStatus == '物料确认') || (row.needSalesConfirm === '3' && row.salesConfirmId > 0)){
                        return true;
                    }else{
                        return false;
                    }
                },
                action: function (row) {
                    showModalWin('?model=projectmanagent_borrow_borrow&action=toConfirmEqu&id='+ row.id
                        + '&needSalesConfirm='+ row.needSalesConfirm +'&salesConfirmId='+ row.salesConfirmId
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750');
                }
            },
            {

//                text: '归还物料',
//                icon: 'add',
//                showMenuFn: function (row) {
//                    if (row.ExaStatus == '部门审批' || row.ExaStatus == '变更审批中'
//                        || row.ExaStatus == '未审批' || row.backStatus == '1' || $("#createId").val() == 'quanzhou.luo') {
//                        return false;
//                    }
//                    return true;
//                },
//                action: function (row) {
//                    showOpenWin('?model=projectmanagent_borrowreturn_borrowreturn&action=toAdd&id=' + row.id);
//                }
//            },
//            {
                text: '删除',
                icon: 'delete',
                showMenuFn: function (row) {
                    if ((row.ExaStatus == '未审批' || row.ExaStatus == '打回')
                    		&& (row.createId != 'quanzhou.luo' || $("#createId").val() == 'quanzhou.luo')) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    if (window.confirm(("确定要删除?"))) {
                    	// 罗权洲新建单据,判断是否存在发货记录
                    	if(row.createId == 'quanzhou.luo'){
                            $.ajax({
                                type: "POST",
                                url: "?model=projectmanagent_borrow_borrow&action=isExistOutplan",
                                data: {
                                    id: row.id
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('存在发货记录,不能删除！');
                                        return false;
                                    }else{
                                        $.ajax({
                                            type: "POST",
                                            url: "?model=projectmanagent_borrow_borrow&action=ajaxdeletes",
                                            data: {
                                                id: row.id
                                            },
                                            success: function (msg) {
                                                if (msg == 1) {
                                                    alert('删除成功！');
                                                    $("#MyBorrowGrid").yxsubgrid("reload");
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                    	}else{
                            $.ajax({
                                type: "POST",
                                url: "?model=projectmanagent_borrow_borrow&action=ajaxdeletes",
                                data: {
                                    id: row.id
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('删除成功！');
                                        $("#MyBorrowGrid").yxsubgrid("reload");
                                    }
                                }
                            });
                    	}
                    }
                }
            }
            ,
            {
                text: '转为销售',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '未审批' || row.ExaStatus == '部门审批'
                        || row.ExaStatus == '变更审批中' || row.ExaStatus == '物料确认' || row.status == '6' || row.backStatus == '1' || $("#createId").val() == 'quanzhou.luo') {
                        return false;
                    }
                    return true;
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showModalWin('?model=projectmanagent_borrow_borrow&action=borrowTurnList&id='
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");


//					showThickboxWin('?model=projectmanagent_borrow_borrow&action=borrowToOrder&id='
//							+ row.id
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=500");
                    }
                }
            }
            ,
            {
                text: '转借修改',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row.status == 6 && $("#createId").val() != 'quanzhou.luo') {
                        return true;
                    }
                    return false;

                },
                action: function (row, rows, grid) {
                    if (row) {
                        if (row) {
                            showOpenWin("?model=projectmanagent_borrow_borrow&action=subtenancyEdit&id="
                                + row.id
                                + "&skey="
                                + row['skey_']
                                + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
                        } else {
                            alert("请选中一条数据");
                        }
                    }
                }
            },
            {
                text: '转借确认',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row.status == 6 && $("#createId").val() != 'quanzhou.luo') {
                        return true;
                    }
                    return false;

                },
                action: function (row, rows, grid) {
                    if (row) {
                        if (row) {
                            showOpenWin("?model=projectmanagent_borrow_borrow&action=subtenancyAff&id="
                                + row.id
                                + "&skey="
                                + row['skey_']
                                + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
                        } else {
                            alert("请选中一条数据");
                        }
                    }
                }
            },
            {
                text: '关联商机',
                icon: 'edit',
                showMenuFn: function (row) {
                    if ((row.ExaStatus == '未审批' || row.ExaStatus == '打回')
                    		&& row.createId == 'quanzhou.luo' && $("#createId").val() != 'quanzhou.luo') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {
                    	showThickboxWin("?model=projectmanagent_borrow_borrow&action=toRelateChance&id="
                            + row.id
                            + "&skey="
                            + row['skey_']
                            + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800")
                    } else {
                        alert("请选中一条数据");
                    }
                }
            },
            {
                text: '关闭',
                icon: 'delete',
                showMenuFn: function (row) {
                    if (row.lExaStatus != '变更审批中' && (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'TZFH') && row.ExaStatus == '完成' && row.status != 2) {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {
                        $.ajax({
                            type: "POST",
                            url: "?model=projectmanagent_borrow_borrow&action=isCanClose",
                            data: {
                                id: row.id,
                            },
                            success: function (msg) {
                                if (msg == 0) {
                                    showThickboxWin('controller/projectmanagent/borrow/ewf_close.php?actTo=ewfSelect&billId='
                                        + row.id
                                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                                }else{
                                	if(msg == 1){
                                        alert('存在未关闭的发货需求，请联系交付罗权洲关闭后重新申请！');
                                        return false;
                                	}
                                	if(msg == 2){
                                        alert('存在未关闭的发货计划，请联系交付罗权洲关闭后重新申请！');
                                        return false;
                                	}
                                	if(msg == 3){
                                        alert('存在调拨单，请联系交付罗权洲关闭后重新申请！');
                                        return false;
                                	}
                                }
                            }
                        });
                    }
                }
            }
        ],
        buttonsEx: buttonsArr
    });

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