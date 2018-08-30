var show_page = function (page) {
    $("#MyBorrowGrid").yxsubgrid("reload");
};
$(function () {
    addBtn = {
        name: 'Add',
        // hide : true,
        text: "����",
        icon: 'add',

        action: function (row) {
            showOpenWin('?model=projectmanagent_borrow_borrow&action=toAdd');
        }
    },
        buttonsArr = [
            {
                name: 'edit',
                // hide : true,
                text: "������ת����",
                icon: 'edit',
                action: function (row) {
                    showModalWin('?model=projectmanagent_borrow_borrow&action=borrowTurnList'
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                }
            }
        ]
    if ($("#deptName").val() == '����֧���Ŷ�' || $("#createId").val() == 'quanzhou.luo') {
        buttonsArr.push(addBtn);
    }
    $("#MyBorrowGrid").yxsubgrid({
        model: 'projectmanagent_borrow_borrow',
        action: 'MyBorrowPageJson',
        param: {
            'limits': '�ͻ�'
            ////			'statusArr' : '0,1'
        },
        title: '�ҵĽ�����',
        //��ť
        isViewAction: false,
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        //����Ϣ
        colModel: [
            {
                display: '����Ԥ��',
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
                display: '�̻�Id',
                sortable: true,
                hide: true
            },
            {
                name: 'Code',
                display: '���',
                sortable: true
            },
            {
                name: 'Type',
                display: '����',
                sortable: true,
                hide: true
            },
            {
                name: 'customerName',
                display: '�ͻ�����',
                sortable: true,
                width: 120
            },
            {
                name: 'salesName',
                display: '���۸�����',
                sortable: true,
                width: 80
            },
            {
                name: 'beginTime',
                display: '��ʼ����',
                sortable: true,
                width: 80
            },
            {
                name: 'closeTime',
                display: '��ֹ����',
                sortable: true,
                width: 80
            },
            {
                name: 'scienceName',
                display: '����������',
                sortable: true,
                width: 80
            },
            {
                name: 'status',
                display: '����״̬',
                sortable: true,
                process: function (v) {
                    if (v == '0') {
                        return "����";
                    } else if (v == '1') {
                        return "���ֹ黹";
                    } else if (v == '2') {
                        return "�ر�";
                    } else if (v == '3') {
                        return "�˻�";
                    } else if (v == '4') {
                        return "����������"
                    } else if (v == '5') {
                        return "ת��ִ�в�"
                    } else if (v == '6') {
                        return "ת��ȷ����"
                    } else {
                        return v;
                    }
                },
                width: 70
            },
            {
                name: 'ExaStatus',
                display: '����״̬',
                sortable: true,
                width: 70,
                process: function (v,row) {
                    if(row.lExaStatus != '���������'){
                        return v;
                    }else{
                        return '���������';
                    }
                }
            },
            {
                name : 'checkFile',
                display : '�����ļ�',
                sortable : true,
                width : 90,
                process: function (v,row) {
                    if(v == '��'){
                        return v;
                    }else{
                        return '��';
                    }
                }
            },
            {
                name: 'backStatus',
                display: '�黹״̬',
                sortable: true,
                process: function (v) {
                    if (v == '0') {
                        return "δ�黹";
                    } else if (v == '1') {
                        return "�ѹ黹";
                    } else if (v == '2') {
                        return "���ֹ黹";
                    }
                },
                width: 70
            }
            ,
            {
                name: 'DeliveryStatus',
                display: '����״̬',
                sortable: true,
                process: function (v) {
                    if (v == 'WFH') {
                        return "δ����";
                    } else if (v == 'YFH') {
                        return "�ѷ���";
                    } else if (v == 'BFFH') {
                        return "���ַ���";
                    } else if (v == 'TZFH') {
                        return "ֹͣ����";
                    }
                },
                width: 70
            },
            {
                name: 'ExaDT',
                display: '����ʱ��',
                sortable: true,
                hide: true,
                width: 70,
                process: function (v,row){
                    if(row['ExaStatus'] == "��������"){
                        return '';
                    }else{
                        return v;
                    }
                }
            },
            {
                name: 'createName',
                display: '������',
                sortable: true,
                hide: true
            },
            {
                name: 'remark',
                display: '����ԭ��',
                sortable: true
            },
            {
                name: 'objCode',
                display: 'ҵ����',
                width: 120
            }
        ],
        comboEx: [
            {
                text: '�黹״̬',
                key: 'backStatu',
                data: [
                    {
                        text: 'δ�黹',
                        value: '0'
                    },{
                        text: '�ѹ黹',
                        value: '1'
                    },{
                        text: '���ֹ黹',
                        value: '2'
                    }
                ]
            },{
                text: '����״̬',
                key: 'ExaStatus',
                data: [
                    {
                        text: '����ȷ��',
                        value: '����ȷ��'
                    },{
                        text: '���������',
                        value: '���������'
                    },{
                        text: 'δ����',
                        value: 'δ����'
                    },{
                        text: '��������',
                        value: '��������'
                    },{
                        text: '���',
                        value: '���'
                    }
                ]
            }
        ],
        // ���ӱ������
        subGridOptions: {
            url: '?model=projectmanagent_borrow_borrowequ&action=listPageJson',// ��ȡ�ӱ�����url
            // ���ݵ���̨�Ĳ�����������
            param: [
                {
                    paramId: 'borrowId',// ���ݸ���̨�Ĳ�������
                    colId: 'id'// ��ȡ���������ݵ�������

                }
            ],
            // ��ʾ����
            colModel: [
                {
                    name: 'productNo',
                    width: 200,
                    display: '��Ʒ���',
                    process: function (v, row) {
                        return v + "&nbsp;&nbsp;K3:" + row['productNoKS'];
                    }
                },
                {
                    name: 'productName',
                    width: 200,
                    display: '��Ʒ����',
                    process: function (v, row) {
                        return v + "&nbsp;&nbsp;K3:" + row['productNameKS'];
                    }
                },
                {
                    name: 'number',
                    display: '��������',
                    width: 80
                },
                {
                    name: 'executedNum',
                    display: '��ִ������',
                    width: 80
                },
                {
                    name: 'applyBackNum',
                    display: '������黹����'
                },
                {
                    name: 'backNum',
                    display: '�ѹ黹����',
                    width: 80
                }
            ]
        },
        /**
         * ��������
         */
        searchitems: [
            {
                display: '���',
                name: 'Code'
            },
            {
                display: '���۸�����',
                name: 'salesName'
            },
            {
                display: '������',
                name: 'createNmae'
            },
            {
                display: '��������',
                name: 'createTime'
            },
            {
                display: '�ͻ�����',
                name: 'customerName'
            },
            {
                display: 'K3��������',
                name: 'productNameKS'
            },
            {
                display: 'ϵͳ��������',
                name: 'productName'
            },
            {
                display: 'K3���ϱ���',
                name: 'productNoKS'
            },
            {
                display: 'ϵͳ���ϱ���',
                name: 'productNo'
            },
            {
                display: '���к�',
                name: 'serialName2'
            }
        ],
        // ��չ�Ҽ��˵�
        menusEx: [
            {
                text: '�鿴',
                icon: 'view',
                action: function (row) {
                    if (row) {
                        showOpenWin("?model=projectmanagent_borrow_borrow&action=toViewTab&id="
                            + row.id + "&skey=" + row['skey_']);
                    }
                }

            },
            {
                text: '�༭',
                icon: 'edit',
                showMenuFn: function (row) {
                    if ((row.ExaStatus == 'δ����' || row.ExaStatus == '���')
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
                        alert("��ѡ��һ������");
                    }
                }
            },
            {
                text: '�ύ���',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row.ExaStatus == 'δ����') {
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
                    if (window.confirm(("ȷ���ύ��?"))) {
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
                                    alert("�ύʧ�ܡ�������!");
                                }
                                $("#MyBorrowGrid").yxsubgrid("reload");
                            }
                        });
                    }
                }
            },
            {

                text: '���',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '��������' || row.ExaStatus == '����ȷ��' || row.ExaStatus == '���������' || row.lExaStatus == '���������' || row.ExaStatus == 'δ����'
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

                text: '��������ȷ��',
                icon: 'edit',
                showMenuFn: function (row) {
                    if((row.needSalesConfirm > 0 && row.ExaStatus == '����ȷ��') || (row.needSalesConfirm === '3' && row.salesConfirmId > 0)){
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

//                text: '�黹����',
//                icon: 'add',
//                showMenuFn: function (row) {
//                    if (row.ExaStatus == '��������' || row.ExaStatus == '���������'
//                        || row.ExaStatus == 'δ����' || row.backStatus == '1' || $("#createId").val() == 'quanzhou.luo') {
//                        return false;
//                    }
//                    return true;
//                },
//                action: function (row) {
//                    showOpenWin('?model=projectmanagent_borrowreturn_borrowreturn&action=toAdd&id=' + row.id);
//                }
//            },
//            {
                text: 'ɾ��',
                icon: 'delete',
                showMenuFn: function (row) {
                    if ((row.ExaStatus == 'δ����' || row.ExaStatus == '���')
                    		&& (row.createId != 'quanzhou.luo' || $("#createId").val() == 'quanzhou.luo')) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    if (window.confirm(("ȷ��Ҫɾ��?"))) {
                    	// ��Ȩ���½�����,�ж��Ƿ���ڷ�����¼
                    	if(row.createId == 'quanzhou.luo'){
                            $.ajax({
                                type: "POST",
                                url: "?model=projectmanagent_borrow_borrow&action=isExistOutplan",
                                data: {
                                    id: row.id
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('���ڷ�����¼,����ɾ����');
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
                                                    alert('ɾ���ɹ���');
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
                                        alert('ɾ���ɹ���');
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
                text: 'תΪ����',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row.ExaStatus == 'δ����' || row.ExaStatus == '��������'
                        || row.ExaStatus == '���������' || row.ExaStatus == '����ȷ��' || row.status == '6' || row.backStatus == '1' || $("#createId").val() == 'quanzhou.luo') {
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
                text: 'ת���޸�',
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
                            alert("��ѡ��һ������");
                        }
                    }
                }
            },
            {
                text: 'ת��ȷ��',
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
                            alert("��ѡ��һ������");
                        }
                    }
                }
            },
            {
                text: '�����̻�',
                icon: 'edit',
                showMenuFn: function (row) {
                    if ((row.ExaStatus == 'δ����' || row.ExaStatus == '���')
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
                        alert("��ѡ��һ������");
                    }
                }
            },
            {
                text: '�ر�',
                icon: 'delete',
                showMenuFn: function (row) {
                    if (row.lExaStatus != '���������' && (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'TZFH') && row.ExaStatus == '���' && row.status != 2) {
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
                                        alert('����δ�رյķ�����������ϵ������Ȩ�޹رպ��������룡');
                                        return false;
                                	}
                                	if(msg == 2){
                                        alert('����δ�رյķ����ƻ�������ϵ������Ȩ�޹رպ��������룡');
                                        return false;
                                	}
                                	if(msg == 3){
                                        alert('���ڵ�����������ϵ������Ȩ�޹رպ��������룡');
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