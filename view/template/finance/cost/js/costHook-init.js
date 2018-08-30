/**
 * Created by show on 14-5-6.
 * ��Ʊ�������
 */
(function ($) {

    //Ĭ������
    var defaults = {
        objName: "", // ҵ������
        type: "hook", // ��ʾ���� "" ��, hookEdit �����༭, hook ����, hookSelect ����ѡ��
        url: "", // ·��
        param: {}, // ����
        showSelectWin: false, // �Ƿ񿪷�ѡ�񴰿ڣ�һ������������Ʊѡ�������
        event: {}, // �Զ����¼�
        async: true, // �첽
        parentGrid: "", // ������
        isShowCountRow: false, // �Ƿ���ʾ�ϼ���
        countKey: "", // �ϼ���������ֶ�
        colModel: [{ // Ĭ����
            name: 'id',
            display: 'id',
            type: 'hidden'
        }, {
            name: 'oldId',
            display: 'oldId',
            type: 'hidden'
        }, {
            name: 'hookId',
            display: 'hookId',
            type: 'hidden'
        }, {
            name: 'mainId',
            display: 'mainId',
            type: 'hidden'
        }, {
            name: 'hookDetailId',
            display: 'hookDetailId',
            type: 'hidden'
        }, {
            name: 'objCode',
            display: 'Դ�����',
            type: 'hidden'
        }, {
            name: 'objType',
            display: 'Դ������',
            type: 'hidden'
        }, {
            name: 'company',
            display: '��˾����',
            type: 'hidden'
        }, {
            name: 'companyName',
            display: '��˾����',
            type: 'hidden'
        }, {
            name: 'module',
            display: '���������',
            sortable: true,
            width: 60,
            type: 'hidden'
        }, {
            name: 'moduleName',
            display: '�������',
            sortable: true,
            width: 60,
            type: 'statictext',
            isSubmit: true
        }, {
            name: 'belongCompany',
            display: '������˾',
            width: 70,
            process: function (html, rowData, $tr, g) {
                if (rowData && g.options.type == 'view') {
                    return rowData.belongCompanyName;
                } else if (rowData && g.options.type == 'hook') {
                    html.hide().after(rowData.belongCompanyName);
                }
            }
        }, {
            name: 'belongCompanyName',
            display: '������˾',
            type: 'hidden'
        }, {
            name: 'inPeriod',
            display: '���������ڼ�',
            width: 70,
            process: function (html, rowData, $tr, g) {
                if (rowData && g.options.type == 'hook') {
                    html.hide().after(rowData.inPeriod);
                } else {
                    return html;
                }
            }
        }, {
            name: 'belongPeriod',
            display: '���ù����ڼ�',
            width: 70,
            process: function (html, rowData, $tr, g) {
                if (rowData && g.options.type == 'hook') {
                    html.hide().after(rowData.belongPeriod);
                } else {
                    return html;
                }
            }
        }, {
            name: 'detailType',
            display: '��������',
            width: 80,
            process: function (html, rowData, $tr, g) {
                if (rowData && g.options.type == 'hook') {
                    html.hide().after(g.getDetailTypeCN(rowData.detailType));
                } else {
                    return g.getDetailTypeCN(html);
                }
            }
        }, {
            name: 'shareObjType',
            display: '��̯��������',
            width: 80,
            type: 'select',
            datacode: 'FTDXLX',
            options: [],
            process: function (html, rowData, $tr, g) {
                if (rowData && g.options.type == 'hook') {
                    html.hide().after(g.getDatadictValue('shareObjType', rowData.shareObjType));
                } else if (rowData) {
                    return g.getDatadictValue('shareObjType', rowData.shareObjType);
                }
            }
        }, {
            name: 'defaultShareObjType',
            display: 'defaultShareObjType',
            type: 'hidden'
        }, {
            name: 'costShareObj',
            display: '��̯����',
            width: 120,
            align: 'left',
            type: 'statictext',
            process: function (html, rowData, $tr, g, $input, rowNum) {
                if ((g.options.type == 'view' || g.options.type == 'hook') && rowData) {
                    return g.initShareObjView(rowData.shareObjType, rowNum, rowData);
                }
            }
        }, {
            name: 'costShareObjExtends',
            display: '������Ϣ',
            width: 130,
            align: 'left',
            type: 'statictext',
            process: function (html, rowData, $tr, g, $input, rowNum) {
                if (rowData) {
                    if (g.options.type == 'view') {
                        return g.initShareObjExtendsView(rowData.shareObjType, rowNum, rowData);
                    } else if (g.options.type == 'hook') {
                        return g.initShareObjExtendsHook(rowData.shareObjType, rowNum, rowData);
                    }
                }
            }
        }, {
            name: 'parentTypeId',
            display: 'parentTypeId',
            type: 'hidden'
        }, {
            name: 'parentTypeName',
            display: '������ϸ�ϼ�',
            type: 'hidden'
        }, {
            name: 'costTypeId',
            display: 'costTypeId',
            type: 'hidden'
        }, {
            name: 'costTypeName',
            display: '������ϸ',
            width: 80,
            process: function (html, rowData, $tr, g) {
                if (rowData && g.options.type == 'hook') {
                    html.hide().after(rowData.costTypeName);
                } else if (rowData) {
                    return rowData.costTypeName;
                }
            }
        }, {
            name: 'actCostMoney',
            display: '�ɹ������',
            type: 'hidden'
        }, {
            name: 'costMoney',
            display: '���ι������',
            type: 'moneyAndNegative',
            event: {
                blur: function (e) {
                    var g = e.data.gird;
                    if (g.options.type != 'view') {
                        g.countShareMoney();
                    }
                }
            },
            process: function (v) {
                if (v < 0) {
                    return '<span class="red">' + moneyFormat2(v) + '</span>';
                } else {
                    return moneyFormat2(v);
                }
            },
            width: 80
        }]
    };

    $.fn.costShareGrid = function (options, other1, other2, other3, other4) {
        if (typeof(options) != 'object') {
            return $(this).costShareGirdInit(options, other1, other2, other3, other4);
        } else {
            //�ϲ�����
            var options = $.extend(defaults, options);
            //֧��ѡ�����Լ���ʽ����
            return this.each(function () {

                $(this).costShareGirdInit({
                    objName: options.objName,
                    url: options.url,
                    param: options.param,
                    type: options.type,
                    isAdd: false,
                    event: options.event,
                    async: options.async,
                    parentGrid: options.parentGrid,
                    countKey: options.countKey,
                    isShowCountRow: options.isShowCountRow,
                    isAddOneRow: false,
                    colModel: options.colModel
                });

                // ����д�������������򸽼ӷ�̯��ϸѡ���
                if (options.showSelectWin && options.showSelectWin == true) {
                    $(this).costShareGirdInit('initSelectWin');
                }

                if (options.isShowCountRow == true) {
                    initCountRow($(this), options);
                }
            });
        }
    };

    // ��ʼ���ϼ�
    var initCountRow = function (g, options) {
        if (options.countKey != "") {
            g.find('tbody').after('<tr class="tr_count">' +
            '<td colspan="9"></td>' +
            '<td>ʣ��ɷ�̯��' +
            '<input type="text" id="costShareCanShare" class="readOnlyTxtShortCount" readonly="readonly"/>' +
            '</td>' +
            '<td>�ϼ�</td>' +
            '<td>' +
            '<input type="text" id="costShareShared" class="readOnlyTxtShortCount" readonly="readonly"/>' +
            '</td>' +
            '</tr>');
        }
    };

    var shareObjTypeOptions = []; // ȫ�ֱ����������̯��������
    $.ajax({
        type: "POST",
        url: "?model=system_datadict_datadict&action=ajaxGetForEasyUI",
        data: {parentCode: 'FTDXLX', isUse: 0},
        async: false,
        success: function (data) {
            data = eval("(" + data + ")");
            var j;
            for (var i = 0; i < data.length; i++) {
                j = data[i].expand1;
                if (!shareObjTypeOptions[j]) {
                    shareObjTypeOptions[j] = [];
                }
                shareObjTypeOptions[j].push({text: data[i].text, value: data[i].id});
            }
        }
    });

    //��ʼ�����ñ��
    $.woo.yxeditgrid.subclass('woo.costShareGirdInit', {
        title: '���÷�̯��ϸ',
        realDel: false,
        width: '100%',
        tableClass: 'form_in_table',
        defaultClass: 'txt-auto',
        event: {
            removeRow: function (e, rowNum, rowData, index, g) {
                g.countShareMoney();
            },
            reloadData: function (e, g, data) {
                if (data) {
                    // �Զ���ʼ��
                    g.autoInitDetailType(data);

                    // ��ʼ���ϼ�
                    if (g.options.type != 'hookSelect') {
                        g.countShareMoney();
                    }
                } else if ((g.options.type == 'hook' || g.options.type == 'hookSelect') && !data) {
                    g.el.find('tbody').append("<tr><td colspan='99'> -- û�пɺ����ķ�̯��ϸ����Ҫ�����̯��¼����֪ͨ������� -- </td></tr>");
                }

                // �����ѡ���
                if (g.options.type == 'hookSelect') {
                    g.initSelectButton();
                }
                
                // ����ȫѡ��ť
                $("ȫѡ<input type='checkbox' id='checkAll' onclick='checkAll(this);'/>").appendTo("#costShareListGrid .main_tr_header th:first");
            }
        },
        /**
         * init select btn
         */
        initSelectButton: function () {
            var g = this;
            var btn = $("<input type='button' class='txt_btn_a' value='ȷ��ѡ��'/>");
            btn.bind('click', function () {
                if ($("#" + g.el.attr("id") + " input[id^='costShareCheckBox']:checked").length == 0) {
                    alert('����ѡ��һ���̯��¼');
                    return false;
                }
                var k = g.options.parentGrid;

                var idMap = {}; // ���浱ǰ�б����е�id
                $("[id^='" + k.el.attr('id') + "_cmp_id']").each(function () {
                    idMap[$(this).val()] = {
                        id: $(this).attr("id"),
                        rowNum: $(this).data('rowNum'),
                        isDel: !k.isRowDel($(this).data('rowNum'))
                    };
                });

                // �������ѡ���
                var costShareListGridObj = $("#costShareListGrid");

                // ����ȷ��ѡ�������
                $("#" + g.el.attr("id") + " input[id^='costShareCheckBox']").each(function () {
                    if (!idMap[$(this).val()] && $(this).attr('checked') == true) {
                        // ���������д���
                        var nowRowNum = k.getAllAddRowNum();
                        // ��ʱ��������
                        var tempData = $(this).data('rowData');
                        // ���ι�����ֵ
                        tempData.costMoney = costShareListGridObj.costShareGrid('getCmpByRowAndCol',
                            $(this).attr("rowNum"), 'costMoney', true).val();

                        k.addRow(nowRowNum, tempData);
                    } else if (idMap[$(this).val()] && $(this).attr('checked') == false) {
                        // ��߿۳���
                        k.removeRow(idMap[$(this).val()].rowNum);
                    } else if (idMap[$(this).val()] && idMap[$(this).val()].isDel == false && $(this).attr('checked') == true) {
                        // ���ڼ�ɾ�������Ǻ���Ҫ�ӻ�����
                        k.resetRow(idMap[$(this).val()].rowNum);
                    }

                    // �����ѡ�У���Ϊ�������ֵ
                    if (idMap[$(this).val()] && $(this).attr('checked') == true) {
                        // ��ȡ�к�
                        var rowNum = $(this).attr("rowNum");

                        // ���ι������
                        var costMoney = costShareListGridObj.costShareGrid('getCmpByRowAndCol', rowNum, 'costMoney', true).val();

                        // ��ֵ
                        k.setRowColValue(idMap[$(this).val()].rowNum, 'costMoney', costMoney, true);
                    }
                });
                k.countShareMoney();

                self.parent.tb_remove();
            });
            this.getTitleObj().append("&nbsp;").append(btn);
        },
        /**
         * init select window TODO
         */
        initSelectWin: function () {
            var g = this;
            var btn = $("<input type='button' class='txt_btn_a thickbox' value='ѡ���̯��ϸ'" +
            " alt='#TB_inline?height=600&width=1050&inlineId=costShareListDiv' id='costShareListButton'/>");
            btn.bind('click', function () {
                // ��̬���ɷ�������ѡ��
                var selectDiv = $("#costShareListDiv");
                if (selectDiv.length == 0) {
                    // �������һ��div���ڱ����������ѡ����
                    g.el.after("<div id='costShareListDiv' style='display:none;'><div id='costShareListGrid'></div></div>");

                    //��ʾ���÷�̯��ϸ
                    $("#costShareListGrid").costShareGrid({
                        url: "?model=finance_cost_costshare&action=hookSelectJson",
                        objName: "selectList",
                        param: g.options.param,
                        type: "hookSelect",
                        async: false,
                        showSelectWin: false,
                        isShowCountRow: false,
                        parentGrid: g,
                        colModel: [{ // Ĭ����
                            name: 'id',
                            display: 'id',
                            type: 'hidden'
                        }, {
                            name: 'oldId',
                            display: 'oldId',
                            type: 'hidden'
                        }, {
                            name: 'hookId',
                            display: 'hookId',
                            type: 'hidden'
                        }, {
                            name: 'mainId',
                            display: 'mainId',
                            type: 'hidden'
                        }, {
                            name: 'hookDetailId',
                            display: 'hookDetailId',
                            type: 'hidden'
                        }, {
                            name: 'objCode',
                            display: 'Դ�����',
                            type: 'hidden'
                        }, {
                            name: 'objType',
                            display: 'Դ������',
                            type: 'hidden'
                        }, {
                            name: 'company',
                            display: '��˾����',
                            type: 'hidden'
                        }, {
                            name: 'companyName',
                            display: '��˾����',
                            type: 'hidden'
                        }, {
                            name: 'belongCompany',
                            display: '������˾',
                            type: 'hidden'
                        }, {
                            name: 'belongCompanyName',
                            display: '������˾',
                            width: 70,
                            type: 'hidden'
                        }, {
                            name: 'inPeriod',
                            display: '���������ڼ�',
                            width: 70
                        }, {
                            name: 'belongPeriod',
                            display: '���ù����ڼ�',
                            width: 70
                        }, {
                            name: 'module',
                            display: '���������',
                            width: 60,
                            type: 'hidden'
                        }, {
                            name: 'moduleName',
                            display: '�������',
                            width: 60,
                            type: 'statictext'
                        }, {
                            name: 'detailType',
                            display: '��������',
                            width: 80,
                            process: function (html, rowData, $tr, g) {
                                return g.getDetailTypeCN(html);
                            }
                        }, {
                            name: 'shareObjType',
                            display: '��̯��������',
                            width: 80,
                            type: 'select',
                            datacode: 'FTDXLX',
                            options: [],
                            process: function (html, rowData, $tr, g) {
                                return g.getDatadictValue('shareObjType', rowData.shareObjType);
                            }
                        }, {
                            name: 'defaultShareObjType',
                            display: 'defaultShareObjType',
                            type: 'hidden'
                        }, {
                            name: 'costShareObj',
                            display: '��̯����',
                            width: 120,
                            align: 'left',
                            type: 'statictext'
                        }, {
                            name: 'costShareObjExtends',
                            display: '������Ϣ',
                            width: 130,
                            align: 'left',
                            type: 'statictext'
                        }, {
                            name: 'parentTypeId',
                            display: 'parentTypeId',
                            type: 'hidden'
                        }, {
                            name: 'parentTypeName',
                            display: '������ϸ�ϼ�',
                            type: 'hidden'
                        }, {
                            name: 'costTypeId',
                            display: 'costTypeId',
                            type: 'hidden'
                        }, {
                            name: 'costTypeName',
                            display: '������ϸ',
                            width: 80
                        }, {
                            name: 'actCostMoney',
                            display: 'ԭʼ��̯���',
                            width: 70,
                            type: 'statictext',
                            process: function (v) {
                                return moneyFormat2(v);
                            }
                        }, {
                            name: 'hookMoney',
                            display: '�ѹ������',
                            width: 70,
                            type: 'statictext',
                            process: function (v) {
                                return moneyFormat2(v);
                            }
                        }, {
                            name: 'unHookMoney',
                            display: 'δ�������',
                            width: 70,
                            type: 'statictext',
                            process: function (v) {
                                return moneyFormat2(v);
                            }
                        }, {
                            name: 'costMoney',
                            display: '���ι������',
                            type: 'moneyAndNegative',
                            align: 'right',
                            process: function (v) {
                                if (v < 0) {
                                    return '<span class="red">' + moneyFormat2(v) + '</span>';
                                } else {
                                    return moneyFormat2(v);
                                }
                            },
                            width: 70
                        }]
                    });
                }
                // Ϊcheckbox��ֵ
                g.getCmpByCol("id").each(function () {
                    var checkBoxObj = $("#costShareCheckBox" + $(this).val());

                    // ѡ��
                    checkBoxObj.attr("checked", true);

                    // �кŻ�ȡ
                    var rowNum = checkBoxObj.attr('rowNum');

                    // ����ȡ
                    var costMoney = g.getCmpByRowAndCol($(this).data('rowNum'), 'costMoney', true).val();

                    // ��ֵ
                    $("#costShareListGrid").costShareGrid('setRowColValue', rowNum, 'costMoney', costMoney, true);
                });
            });
            this.getTitleObj().append("&nbsp;").append(btn);
            tb_init('#costShareListButton'); // ʹ�����thickbox����
        },
        /**
         * get detail type cn
         * @param v
         * @returns {*}
         */
        getDetailTypeCN: function (v) {
            switch (v) {
                case '1' :
                    return '���ŷ���';
                case '2' :
                    return '��ͬ��Ŀ����';
                case '3' :
                    return '�з�����';
                case '4' :
                    return '��ǰ����';
                case '5' :
                    return '�ۺ����';
                default :
                    return v;
            }
        },
        /**
         * get datadict value
         * @param type
         * @param val
         */
        getDatadictValue: function (type, val) {
            var dataArr = this.datadict[type].datadictData;
            for (var i = 0; i < dataArr.length; i++) {
                if (dataArr[i].dataCode == val) {
                    return dataArr[i].dataName;
                }
            }
        },
        /**
         * �ɹ�ѡ�ļ�¼
         * @param rowNum
         * @param shareObjType
         * @param rowData
         */
        initRowSelect: function (rowNum, shareObjType, rowData) {
            var checkBox = $('<input type="checkbox" name="costShareListCheckBox" id="costShareCheckBox' + rowData.id
            + '" value="' + rowData.id + '" rowNum="' + rowNum + '"/>');
            checkBox.data('rowData', rowData);
            this.getCmpByRowAndCol(rowNum, "removeBn").hide().after(checkBox);
            this.getCmpByRowAndCol(rowNum, "belongCompany").hide().after(rowData.belongCompanyName);
            this.getCmpByRowAndCol(rowNum, "belongPeriod").hide().after(rowData.belongPeriod);
            this.getCmpByRowAndCol(rowNum, "inPeriod").hide().after(rowData.inPeriod);
            this.getCmpByRowAndCol(rowNum, "detailType").hide().after(this.getDetailTypeCN(rowData.detailType));
            this.getCmpByRowAndCol(rowNum, "shareObjType").hide()
                .after(this.getDatadictValue('shareObjType', rowData.shareObjType));
            this.getCmpByRowAndCol(rowNum, "costTypeName").hide().after(rowData.costTypeName);
            this.getCmpByRowAndCol(rowNum, "costShareObj")
                .append(this.initShareObjView(rowData.shareObjType, rowNum, rowData));
            this.getCmpByRowAndCol(rowNum, "costShareObjExtends")
                .append(this.initShareObjExtendsHook(rowData.shareObjType, rowNum, rowData));
        },
        /**
         * �ѹ�����¼����
         * @param rowNum
         * @param shareObjType
         * @param rowData
         */
        initRowHooked: function (rowNum, shareObjType, rowData) {
            this.getCmpByRowAndCol(rowNum, "removeBn").hide();
            this.getCmpByRowAndCol(rowNum, "belongCompany").hide().after(rowData.belongCompanyName);
            this.getCmpByRowAndCol(rowNum, "belongPeriod").hide().after(rowData.belongPeriod);
            this.getCmpByRowAndCol(rowNum, "inPeriod").hide().after(rowData.inPeriod);
            this.getCmpByRowAndCol(rowNum, "detailType").hide().after(this.getDetailTypeCN(rowData.detailType));
            this.getCmpByRowAndCol(rowNum, "shareObjType").hide()
                .after(this.getDatadictValue('shareObjType', rowData.shareObjType));
            this.getCmpByRowAndCol(rowNum, "costTypeName").hide().after(rowData.costTypeName);
            this.getCmpByRowAndCol(rowNum, "costMoney").hide().after(moneyFormat2(rowData.costMoney));
            this.getCmpByRowAndCol(rowNum, "costShareObj")
                .append(this.initShareObjView(rowData.shareObjType, rowNum, rowData));
            this.getCmpByRowAndCol(rowNum, "costShareObjExtends")
                .append(this.initShareObjExtendsHook(rowData.shareObjType, rowNum, rowData));
        },
        /**
         * init share object info
         * @param shareObjType
         * @param rowNum
         * @param rowData
         */
        initShareObjView: function (shareObjType, rowNum, rowData) {
            switch (shareObjType) {
	            case 'FTDXLX-01' : // ���ŷ���
	                return rowData.belongDeptName;
	            case 'FTDXLX-03' : // ��ͬ������Ŀ����
	            case 'FTDXLX-02' : // ��ͬ������Ŀ����
	            case 'FTDXLX-04' : // ��ͬ�з���Ŀ����
	            case 'FTDXLX-05' : // ��ǰ���� - ������Ŀ
	                return rowData.projectCode + '<div style="margin: 2px 0"></div>'
	                	+ rowData.feeMan;
	            case 'FTDXLX-10' : // �з�����
	                // return rowData.projectCode;
                    return rowData.projectName;// �з���Ŀ�ķ�̯������ʾ��Ŀ���� PMS 2579
	            case 'FTDXLX-06' :  // ��ǰ���� - �̻�
	                return rowData.chanceCode + '<div style="margin: 2px 0"></div>'
	                	+ rowData.feeMan;
	            case 'FTDXLX-07' : // ��ǰ���� - �ͻ�
	                return rowData.customerName + '<div style="margin: 2px 0"></div>'
	                    + rowData.belongDeptName + '<div style="margin: 2px 0"></div>'
	                    + rowData.feeMan;
	            case 'FTDXLX-08' : // ��ǰ���� - ʡ��/�ͻ�����/��������
	                return rowData.province + '<div style="margin: 2px 0"></div>'
	                    + rowData.customerType + '<div style="margin: 2px 0"></div>'
	                    + rowData.belongDeptName + '<div style="margin: 2px 0"></div>'
	                    + rowData.feeMan;
	            case 'FTDXLX-09' :// �ۺ���� - ��ͬ
	                return rowData.contractCode + '<div style="margin: 2px 0"></div>'
	                    + rowData.belongDeptName + '<div style="margin: 2px 0"></div>'
	                    + rowData.feeMan;
	            case 'FTDXLX-11' :// ��ͬ��Ŀ���� - ��ͬ
	                return rowData.contractCode;
	            default :
	        }
        },
        /**
         * init share object extends info
         * @param shareObjType
         * @param rowNum
         * @param rowData
         */
        initShareObjExtendsView: function (shareObjType, rowNum, rowData) {
            switch (shareObjType) {
				case 'FTDXLX-01' : // ���ŷ���
					if (rowData.projectName != "") {
						return '�����飺' + rowData.projectName;
					}
					break;
	            case 'FTDXLX-05' : // ��ǰ���� - ������Ŀ
	                // ������Ϣ��չ
	                return '�̻���' + rowData.chanceCode + '<br/>'
	                    + '�ͻ���' + rowData.customerName + '<br/>'
	                    + 'ʡ�ݣ�' + rowData.province + '<br/>'
	                    + '�ͻ����ͣ�' + rowData.customerType + '<br/>'
	                    + '�������ţ�' + rowData.belongDeptName + '<br/>'
	                	+ '��������' + rowData.salesArea;
	                break;
	            case 'FTDXLX-06' :  // ��ǰ���� - �̻�
	                return '�ͻ���' + rowData.customerName + '<br/>'
	                    + 'ʡ�ݣ�' + rowData.province + '<br/>'
	                    + '�ͻ����ͣ�' + rowData.customerType + '<br/>'
	                    + '�������ţ�' + rowData.belongDeptName + '<br/>'
	                	+ '��������' + rowData.salesArea;
	                break;
	            case 'FTDXLX-07' : // ��ǰ���� - �ͻ�
	                return 'ʡ�ݣ�' + rowData.province + '<br/>'
	                    + '�ͻ����ͣ�' + rowData.customerType + '<br/>'
	                	+ '��������' + rowData.salesArea;
	                break;
	            case 'FTDXLX-08' : // ��ǰ���� - ʡ��/�ͻ�����/��������
	                return '��������' + rowData.salesArea;
	                break;
	            case 'FTDXLX-09' : // �ۺ���� - ��ͬ
	                return '��������' + rowData.salesArea;
	                break;
	            case 'FTDXLX-11' : // ��ͬ��Ŀ���� - ��ͬ
	                return '�ͻ���' + rowData.customerName + '<br/>'
	                    + 'ʡ�ݣ�' + rowData.province + '<br/>'
	                    + '�ͻ����ͣ�' + rowData.customerType + '<br/>'
	                    + '�������ţ�' + rowData.belongDeptName + '<br/>'
	                	+ '��������' + rowData.salesArea;
	                break;
	            default :
	        }
        },
        /**
         * init share object extends info  for hook
         * @param shareObjType
         * @param rowNum
         * @param rowData
         */
        initShareObjExtendsHook: function (shareObjType, rowNum, rowData) {
            var str = "";
            switch (shareObjType) {
				case 'FTDXLX-01' : // ���ŷ���
					if (rowData.projectName != "") {
						return '�����飺' + rowData.projectName;
					}
					break;
                case 'FTDXLX-05' : // ��ǰ���� - ������Ŀ
                    // ������Ϣ��չ
                    str += '�̻���' + rowData.chanceCode + '<br/>'
	                    + '�ͻ���' + rowData.customerName + '<br/>'
	                    + 'ʡ�ݣ�' + rowData.province + '<br/>'
	                    + '�ͻ����ͣ�' + rowData.customerType + '<br/>'
	                    + '�������ţ�' + rowData.belongDeptName + '<br/>'
	                    + '��������' + rowData.salesArea;
                    break;
                case 'FTDXLX-06' :  // ��ǰ���� - �̻�
                    str += '�ͻ���' + rowData.customerName + '<br/>'
	                    + 'ʡ�ݣ�' + rowData.province + '<br/>'
	                    + '�ͻ����ͣ�' + rowData.customerType + '<br/>'
	                    + '�������ţ�' + rowData.belongDeptName + '<br/>'
	                    + '��������' + rowData.salesArea;
                    break;
                case 'FTDXLX-07' : // ��ǰ���� - �ͻ�
                    str += 'ʡ�ݣ�' + rowData.province + '<br/>'
	                    + '�ͻ����ͣ�' + rowData.customerType + '<br/>'
	                    + '��������' + rowData.salesArea;
                    break;
                case 'FTDXLX-08' : // ��ǰ���� - ʡ��/�ͻ�����/��������
                	str += '��������' + rowData.salesArea;
                    break;
                case 'FTDXLX-09' : // �ۺ���� - ��ͬ
                	str += '��������' + rowData.salesArea;
                    break;
                case 'FTDXLX-11' : // ��ͬ��Ŀ���� - ��ͬ
                	str += '�ͻ���' + rowData.customerName + '<br/>'
                        + 'ʡ�ݣ�' + rowData.province + '<br/>'
                        + '�ͻ����ͣ�' + rowData.customerType + '<br/>'
                        + '�������ţ�' + rowData.belongDeptName + '<br/>'
                    	+ '��������' + rowData.salesArea;
                    break;
                default :
            }
            // append share info
            var g = this;
            var tbId = g.el.attr('id');
            var objName = g.options.objName;
            str += '<input type="hidden" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]" value="' + rowData.belongDeptId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_feeManId' + rowNum + '" name="' + objName + '[' + rowNum + '][feeManId]" value="' + rowData.feeManId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_feeMan' + rowNum + '" name="' + objName + '[' + rowNum + '][feeMan]" value="' + rowData.feeMan + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_salesAreaId' + rowNum + '" name="' + objName + '[' + rowNum + '][salesAreaId]" value="' + rowData.salesAreaId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_salesArea' + rowNum + '" name="' + objName + '[' + rowNum + '][salesArea]" value="' + rowData.salesArea + '"/>';

            return str;
        },
        /**
         * �Զ���ʼ�� - �������
         * @param data
         */
        autoInitDetailType: function (data) {
            for (var i = 0; i < data.length; i++) {
                // �����Ѿ������ļ�¼����������ִ����������
                if (this.options.type == 'hookSelect') {
                    this.initRowSelect(i, this.getCmpByRowAndCol(i, "shareObjType").val(), data[i]);
                }
            }
        },
        /**
         * form validate
         * @return {boolean}
         */
        checkForm: function (formMoney, needEqual) {
            formMoney = Number(formMoney); // ǿ������ת��
            if (needEqual == undefined) needEqual = true;
            var g = this, el = this.el, p = this.options;
            var shareObjTypeArray = $("[id^='" + el.attr('id') + "_cmp_shareObjType']"); // ��Ϊ��������ʹ�õ����������ݣ���������Ҫ���⴦��
            var rs = true; // ��֤����ֵ
            var shareMoney = 0; // ��̯���
            if (shareObjTypeArray.length > 0) {
                shareObjTypeArray.each(function () {
                    var rowNum = $(this).data('rowNum');
                    if (g.isRowDel(rowNum) == false) {
                        var showNum = Number(rowNum) + 1;

                        var costMoney = g.getCmpByRowAndCol(rowNum, "costMoney", true).val();
                        var actCostMoney = g.getCmpByRowAndCol(rowNum, "actCostMoney", true).val(); // ������ʵ�ʿɹ������
                        if (costMoney == "" || parseFloat(costMoney) == 0) {
                            alert('���÷�̯��ϸ��ڡ�' + showNum + '���н���Ϊ�ջ�0');
                            rs = false;
                            return false;
                        }

                        if (parseFloat(actCostMoney) < parseFloat(costMoney)) {
                            alert('���÷�̯��ϸ��ڡ�' + showNum + '���еı��ι�������Ѿ����ڿɹ������');
                            rs = false;
                            return false;
                        }

                        shareMoney = accAdd(shareMoney, costMoney, 2);
                    }
                });

                // ��������˽�����Ҫ���н����֤
                if (formMoney && rs == true && formMoney != shareMoney) {
                    // ǿ��Ҫ����һ��
                    if (needEqual == true) {
                        alert('��̯��' + shareMoney + '���뵥�ݽ�' + formMoney + '����һ�£�');
                        rs = false;
                    } else if (formMoney < shareMoney) {
                        alert('��̯��' + shareMoney + '�����ܴ��ڵ��ݽ�' + formMoney + '��');
                        rs = false;
                    } else {
                        rs = confirm('��̯��' + shareMoney + '��С�ڵ��ݽ�' + formMoney + '����ȷ���ύ��');
                    }
                }
            } else {
                // ǿ��Ҫ����һ��
                if (needEqual == true) {
                    alert('��Ҫ��д��̯���');
                    rs = false;
                } else {
                    rs = confirm('û�з��÷�̯��ϸ��ȷ��Ҫ�ύ������');
                }
            }
            return rs;
        },
        /**
         * ����һ��ɾ��������
         * @param rowNum
         */
        resetRow: function (rowNum) {
            var g = this, el = this.el, p = this.options;
            var $tr = $(el).find("tr[rowNum='" + rowNum + "']");
            if ($tr.length > 0) {
                var index = $tr.data("index");
                var rowData = $tr.data("rowData");
                $("#" + el.attr('id') + "_cmp_" + p.delTagName + index).remove();
                g.curShowRowNum++;
                $tr.show();
                // �������
                $tr.nextAll("tr").find("td[type='rowNum']").each(function (v) {
                    var index = $(this).html();
                    index = parseInt(index);
                    if (index > rowNum) {
                        index++;
                        $(this).html(index);
                    }
                });
            }
            g.processRowNum();
        },
        /**
         * �����̯���
         */
        countShareMoney: function () {
            var g = this;
            if (g.options.countKey != "") {
                var formMoney = $("#" + g.options.countKey).val();
                var sharedMoney = 0;
                var costMoneyArr = g.getCmpByCol('costMoney');
                if (costMoneyArr.length > 0) {
                    costMoneyArr.each(function () {
                        if ($(this).val() != "") {
                            sharedMoney = accAdd(sharedMoney, $(this).val(), 2);
                        }
                    });
                    $("#costShareShared").val(moneyFormat2(sharedMoney));
                }

                if (formMoney != '' && parseFloat(formMoney) != 0) {
                    var canShareMoney = accSub(formMoney, sharedMoney, 2);
                    $("#costShareCanShare").val(moneyFormat2(canShareMoney));
                }
            }
        },
        /**
         * ������ϼ��ֶ�
         * @param newKey
         */
        changeCountKey: function (newKey) {
            var g = this, p = this.options;
            if (newKey != p.countKey) {
                p.countKey = newKey;
                g.countShareMoney();
            }
        },
        /**
         * �鿴ҳ���ͳ�ƽ��鿴
         * @param data
         */
        costShareMoneyView: function (data) {
            var g = this;
            var sharedMoney = 0;
            for (var i = 0; i < data.length; i++) {
                sharedMoney = accAdd(sharedMoney, data[i].costMoney, 2);
            }
            g.el.find('tbody').after('<tr class="tr_count">' +
            '<td colspan="9"></td>' +
            '<td>�ϼ�</td>' +
            '<td>' +
            moneyFormat2(sharedMoney) +
            '</td>' +
            '</tr>');
        }
    });
})(jQuery);

//ȫѡ
function checkAll(obj){
	var checked = $(obj).attr("checked");
	$(obj).parents("#costShareListGrid .form_in_table:first").find("tbody input[type='checkbox']").each(function(){
		$(this).attr("checked",checked);
	});
}