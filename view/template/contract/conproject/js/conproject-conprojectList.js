var show_page = function (page) {
    $("#conprojectListGrid").yxgrid("reload");
};
$(function () {
    buttonsArr = [{
        text: "��������",
        icon: 'add',
        action: function (row) {
            showThickboxWin("?model=contract_conproject_conproject&action=updateSaleProjectVal"
                + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
        }
    }];
    menuArr = [];
    	SRFS = {
    	         text: 'ȷ��������㷽ʽ',
    	         icon: 'add',
    	         action: function (row) {
    	             showThickboxWin('?model=contract_conproject_conproject&action=incomeAcc&id='
    	                 + row.id
//    	                 + '&pid='
//    	                 + row.pid
    	                 + '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600');
    	         }
    	     }
    	SC = {
    	         text: 'ɾ��',
    	         icon: 'delete',
    	         showMenuFn: function (row) {
    	             if (row) {
    	                 return true;
    	             }
    	             return false;
    	         },
    	         action: function (row) {
    	             if (window.confirm(("ɾ����Ŀ��Ϣ���޷���ԭ��������������Ƿ������"))) {
    	                 $.ajax({
    	                     type: "POST",
    	                     url: "?model=contract_conproject_conproject&action=ajaxdeletes",
    	                     data: {
    	                         id: row.id
    	                     },
    	                     success: function (msg) {
    	                         if (msg == 1) {
    	                             alert('ɾ���ɹ���');
    	                             $("#conprojectListGrid").yxgrid("reload");
    	                         }
    	                     }
    	                 });
    	             }
    	         }
    	     };
        LBDC = {
            name: 'export',
            text: "�б����ݵ���",
            icon: 'excel',
            action: function (row) {
                var searchConditionKey = "";
                var searchConditionVal = "";
                for (var t in $("#conprojectListGrid").data('yxgrid').options.searchParam) {
                    if (t != "") {
                        searchConditionKey += t;
                        searchConditionVal += $("#conprojectListGrid")
                            .data('yxgrid').options.searchParam[t];
                    }
                }
                var i = 1;
                var colId = "";
                var colName = "";
                $("#conprojectListGrid_hTable").children("thead").children("tr")
                    .children("th").each(function () {
                        if ($(this).css("display") != "none"
                            && $(this).attr("colId") != undefined) {
                            colName += $(this).children("div").html() + ",";
                            colId += $(this).attr("colId") + ",";
                            i++;
                        }
                    })
                var searchSql = $("#conprojectListGrid").data('yxgrid').getAdvSql();
                var searchArr = [];
                searchArr[0] = searchSql;
                searchArr[1] = searchConditionKey;
                searchArr[2] = searchConditionVal;

                window
                    .open("?model=contract_conproject_conproject&action=exportExcel&colId="
                        + colId
                        + "&colName="
                        + colName
                        + "&searchConditionKey="
                        + searchConditionKey
                        + "&searchConditionVal=" + searchConditionVal)
            }
        }
    $.ajax({
        type: 'POST',
        url: '?model=contract_conproject_conproject&action=getLimits',
        data: {
            'limitName': '�б���'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
//				buttonsArr.push(LBDC);
            }
        }
    });

    DR = {
        name: 'excel',
        text: "�������",
        icon: 'excel',
        items: [
            {
                text: "��Ŀ���㵼��",
                icon: 'excel',
                action: function (row) {
                    showThickboxWin("?model=contract_conproject_conproject&action=toLeadfinanceMoney"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=550")
                }
            },
            {
                text: "��ͬ��Ŀ����",
                icon: 'excel',
                action: function (row) {
                    showThickboxWin("?model=contract_conproject_conproject&action=toExcel"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
                }
            },
            {
                text: "����ȷ�Ϸ�ʽ����",
                icon: 'excel',
                action: function (row) {
                    showThickboxWin("?model=contract_conproject_conproject&action=toExcelExtend"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
                }
            }
        ]
    };
    $.ajax({
        type: 'POST',
        url: '?model=contract_conproject_conproject&action=getLimits',
        data: {
            'limitName': '���ݵ���'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(DR);
                menuArr.push(SRFS);
            }
        }
    });
    menuArr.push(SC);

    $("#conprojectListGrid").yxgrid({
        model: 'contract_conproject_conproject',
        action: 'conprojectJson',
        customCode: 'conprojectListNewList',
        title: '��ͬ��Ŀ��',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        showcheckbox: false,
        leftLayout: true,
//		lockCol : ['conflag','checkTip'],// ����������
        //����Ϣ
        colModel: [
            {
                display: '��Ŀ����',
                name: 'proType',
                sortable: true,
                width: 50,
                align: 'center',
                process: function (v, row) {
//				return "<img src='images/icon/kong.gif' style='width:15px;height:15px;'>";
                    if (row.esmProjectId != '' && row.esmProjectId != '0') {
                        return "<img src='images/icon/service.jpg' ></span>";
                    } else {
                        return "<img src='images/icon/devi.jpg' ></span>";
                    }
                }
            },
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'contractId',
                display: '��ͬid',
                sortable: true,
                hide: true
            }
//		,{
//			display : '���˱�ʶ',
//			name : 'checkTip',
//			sortable : true,
//			width : 50,
//			align : 'center',
//			process:function(v,row){
////				return "<img src='images/icon/kong.gif' style='width:15px;height:15px;'>";
//                if(v =='0'){
//                	return "<span  onclick='checkTip(\""+ row.id +"\",1)'><img src='images/icon/kong.gif' style='width:20px;height:20px;'></span>";
//                }else if(v=='1'){
//                	return "<span  onclick='checkTip(\""+ row.id +"\",0)'><img src='images/icon/shi.gif' style='width:20px;height:20px;'></span>";
//                }
//			}
//		}
            ,
            {
                name: 'contractCode',
                display: '��ͬ���',
                sortable: true,
                width: 100,
                process: function (v, row) {
                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                        + row.contractId
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                        + "<font color = '#4169E1'>"
                        + row.contractCode
                        + "</font>" + '</a>';
                }
            },
            {
                display: 'esmProjectId',
                name: 'esmProjectId',
                sortable: true,
                hide: true
            },
            {
                name: 'projectCode',
                display: '��Ŀ���',
                sortable: true,
                width: 130,
                process: function (v, row) {
                    if (row.esmProjectId != '' && row.esmProjectId != '0') {
                        var skey = "";
                        $.ajax({
                            type: "POST",
                            url: "?model=engineering_project_esmproject&action=md5RowAjax",
                            data: { "id": row.esmProjectId },
                            async: false,
                            success: function (data) {
                                skey = data;
                            }
                        });
                        return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=engineering_project_esmproject&action=viewTab&id='
                            + row.esmProjectId
                            + '&skey='
                            + skey
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                            + "<font color = '#4169E1'>"
                            + v
                            + "</font>" + '</a>';
                    } else {
                        return v;

                    }
                }
            },
            {
                name: 'projectName',
                display: '��Ŀ����',
                sortable: true,
                hide: true
            },
            {
                name: 'proLineName',
                display: '��Ʒ��',
                sortable: true
            },
            {
                name: 'proLineCode',
                display: '��Ʒ�߱���',
                sortable: true,
                hide: true
            },
            {
                name: 'proportionTrue',
                display: '����ռ��',
                sortable: true,
                align: 'right',
                width: 50,
                hide: true,
                process: function (v) {
                    return v + "%";
                }
            },
            {
                name: 'officeName',
                display: 'ִ������',
                sortable: true
            },
            {
                name: 'officeId',
                display: 'ִ������id',
                sortable: true,
                hide: true
            },
            {
                name: 'proMoney',
                display: '��Ŀ��ͬ��',
                sortable: true,
                width: 60,
                align: 'right',
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'rateMoney',
                display: '����˰���',
                sortable: true,
                align: 'right',
                width: 80,
                hide: true,
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'proportion',
                display: '��Ŀռ��',
                sortable: true,
                align: 'right',
                width: 50,
                process: function (v) {
                    return v + "%";
                }
            },
            {
                name: 'contractMoney',
                display: '��ͬ���',
                sortable: true,
                align: 'right',
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                hide: true
            },
            {
                name: 'status',
                display: '��Ŀ״̬',
                sortable: true,
                align: 'center',
                width: 50,
                datacode: 'GCXMZT'
            },
            {
                name: 'schedule',
                display: '��Ŀ����',
                sortable: true,
                width: 80,
                process: function (v) {
                    var v = formatProgress(v);
                    return v;
                }
            },
            {
                name: 'exgross',
                display: 'Ԥ��ë����',
                sortable: true,
                align: 'right',
                width: 70,
                process: function (v) {
                    if (v)
                        if (v < 0)
                            return "<span class='red'>" + v + "%</span>";
                        else if (v == 0)
                            return "-";
                        else
                            return v + "%";
                    else
                        return "-";
                }
            },
            {
                name: 'exgrossTrue',
                display: 'ë����',
                width: 50,
                sortable: true,
                align: 'right',
                process: function (v, row) {
                    if (v && (row.cost != '0.00' || row.costAct != '0.00') && row.schedule != '0.00')
                        if (v < 0 || ((v - row.exgross) < 0 && v != 0))
                            return "<span class='red'>" + v + "%</span>";
                        else
                            return v + "%";
                    else
                        return "-";
                }
            },
            {
                name: 'estimates',
                display: '����',
                sortable: true,
                width: 60,
                process: function (v, row) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                },
                align: 'right'
            },
            {
                name: 'budget',
                display: 'Ԥ��',
                sortable: true,
                width: 60,
                process: function (v, row) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    } else if ((v - row.estimates) > 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                },
                align: 'right'
            },
            {
                name: 'cost',
                display: '����',
                sortable: true,
                align: 'right',
                width: 60,
                process: function (v, row) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    } else if ((v - row.budget) > 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'costAct',
                display: '�������',
                sortable: true,
                align: 'right',
                width: 60,
                process: function (v, row) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    } else if ((v - row.budget) > 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'earnings',
                display: '����',
                sortable: true,
                width: 70,
                process: function (v) {
                    if (v == '0.00' || v == '0') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                align: 'right'
            },
            {
                name: 'grossTrue',
                display: 'ë��',
                sortable: true,
                align: 'right',
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'gross',
                display: 'Ԥ��ë��',
                sortable: true,
                align: 'right',
                hide: true,
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'earningsTypeName',
                display: '����ȷ�Ϸ�ʽ',
                width: 80,
                sortable: true
            },
            {
                name: 'earningsTypeCode',
                display: '����ȷ�Ϸ�ʽ����',
                sortable: true,
                hide: true
            },
            {
                name: 'reserveEarnings',
                display: 'Ԥ��Ӫ��',
                sortable: true,
                width: 70,
                process: function (v) {
                    if (v == '0.00' || v == '0') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                align: 'right',
                hide: true
            },
            {
                name: 'txaRate',
                display: '˰��',
                sortable: true,
                align: 'right',
                width: 50,
                hide: true,
                process: function (v) {
                    if (v)
                        return v + "%";
                    else
                        return "-";
                }
            },
            {
                name: 'moduleName',
                display: '���',
                hide: true
            },
            {
                name: 'module',
                display: '������',
                sortable: true,
                hide: true
            },
            {
                name: 'planBeginDate',
                display: 'Ԥ�ƿ�ʼ����',
                sortable: true,
                hide: true
            },
            {
                name: 'planEndDate',
                display: 'Ԥ�ƽ�������',
                sortable: true,
                hide: true
            },
            {
                name: 'actBeginDate',
                display: 'ʵ�ʿ�ʼ����',
                sortable: true,
                hide: true
            },
            {
                name: 'actEndDate',
                display: 'ʵ�ʽ�������',
                sortable: true,
                hide: true
            },
            {
                name: 'deductMoney',
                display: '�ۿ�',
                align: 'right',
                width: 60,
                process: function (v, row) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                hide: true
            },
            {
                name: 'badMoney',
                display: '����',
                align: 'right',
                width: 60,
                process: function (v, row) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                hide: true
            }
        ],
        toEditConfig: {
            action: 'toEdit'
        },
        toViewConfig: {
            action: 'toView'
        },
        sortname: "contractId",
        searchitems: [
            {
                display: "��ͬ���",
                name: 'contractCode'
            },
            {
                display: "��Ŀ���",
                name: 'projectCode'
            }
        ],
        menusEx: menuArr,
        comboEx: [
          {
              text: '��Ʒ��',
              key: 'proLineCode',
              datacode: 'HTCPX'
          },
          {
              text: 'ִ������',
              key: 'officeIdReal',
              datacode: 'GCSCX'
          },
          {
              text: '��Ŀ״̬',
              key: 'status',
              datacode: 'GCXMZT'
          },
          {
              text: '���',
              key: 'moduleReal',
              datacode: 'HTBK'
          }
//          ,{
//              text: 'Ԥ������',
//              key: 'warningStr',
//              data: [
//                  {
//                      text: 'Ԥ������',
//                      value: '1'
//                  }
//              ]
//          }
        ],
        buttonsEx: buttonsArr
    });
});

//�����б������ʾ
function formatProgress(value) {
    if (value) {
        var s = '<div style="width:auto;height:auto;border:1px solid #ccc;padding: 0px;">'
            + '<div style="width:'
            + value
            + '%;background:#66FF66;white-space:nowrap;padding: 0px;">'
            + value + '%' + '</div>'
        '</div>';
        return s;
    } else {
        return '';
    }
}

//���˱�ʶ
function checkTip(pid, val) {
//   $.ajax({
//	    type: "POST",
//	    url: "?model=contract_conproject_conproject&action=ajaxCheckTip",
//	    data: { "id" : pid , "val" : val},
//	    async: false,
//	    success: function(data){
//	   	   skey = data;
//	   	   $("#conprojectListGrid").yxgrid("reload");
//		}
//	});
}
