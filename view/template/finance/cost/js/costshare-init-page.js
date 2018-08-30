//var timestamp;
/**
 * Created by show on 14-5-6.
 * ���÷�̯���
 */
(function ($) {
    //Ĭ������
    var defaults = {
        title: '���÷�̯��Ϣ',
        objName: '', // ҵ������
        type: 'edit', // ��ʾ���� '' ��, edit �༭, view �鿴, audit ���, change ���
        url: '', // ·��
        param: {}, // ����
        isAdd: true, // �Ƿ���ʾ������ť
        event: {}, // �Զ����¼�
        async: true, // �첽
        isShowExcelBtn: false, // �Ƿ���ʾexcel���밴ť
        isShowCountRow: false, // �Ƿ���ʾ�ϼ���
        countKey: '' // �ϼ���������ֶ�
    };

    $.fn.costShareGrid = function (options, other1, other2) {
        if (typeof(options) != 'object') {
            return $(this).costShareGirdInit(options, other1, other2);
        } else {
            //�ϲ�����
            var options = $.extend(defaults, options);

            // ��������
            options.param.dir = 'ASC';

            if (options.type != 'change') {
                options.param.pageSize = 10;

                if (!options.param.page) {
                    options.param.page = 1;
                }
            }

            //֧��ѡ�����Լ���ʽ����
            return this.each(function () {
                $(this).costShareGirdInit({
                    title: options.title,
                    objName: options.objName,
                    url: options.url,
                    param: options.param,
                    type: options.type,
                    isAdd: options.isAdd,
                    event: options.event,
                    async: options.async,
                    countKey: options.countKey,
                    isShowCountRow: options.isShowCountRow
                });

                if (options.isShowExcelBtn) {
                    initExcelButton($(this));
                }

                if (options.isShowCountRow) {
                    initCountRow($(this), options);
                }
            });
        }
    };

    var toShowImportPage = function(url){
        // ���н�ֹѡ��ķ�����ϸ,��������ҳ��ȥ
        var extParamStr = '';
        var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
        if(mainTypeSlted != undefined && mainTypeSlted.val() != ''){
            var mainTypeCode = mainTypeSlted.val();
            extParamStr = '&payForBusiness='+mainTypeCode;
        }

        url += extParamStr + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900";
        showThickboxWin(url);
    };

    // ��ʼ��excel���밴ť
    var initExcelButton = function (g) {
        g.find('td.form_header').append('&nbsp;<input type="button" class="txt_btn_a" value="EXCEL����" ' +
            'id="costShareExcelButton"/>');
        $("#costShareExcelButton").click(function(){
            var url = '?model=finance_cost_costshare&action=importExcel';
            toShowImportPage(url);
        });
    };

    // ��ʼ���ϼ�
    var initCountRow = function (g, options) {
        if (options.countKey != "") {
            var countRow = '';

            if (options.type != "change") {
                countRow = '<tr class="tr_count">' +
                    '<td colspan="9" align="right">ÿҳ 10 ����' +
                    '<span><a href="javascript:void(0);" onclick="$(\'#shareGrid\').costShareGrid(\'prevPage\')">ǰһҳ</a></span>��' +
                    '<span>��ǰ��<span id="' + g.attr("id") + '_nowPage"></span>ҳ</span>��' +
                    '<span><a href="javascript:void(0);" onclick="$(\'#shareGrid\').costShareGrid(\'nextPage\')">��һҳ</a></span>��' +
                    '<span>�ܹ�<span id="' + g.attr("id") + '_maxPage"></span>ҳ</span>��' +
                    '<span><span id="' + g.attr("id") + '_totalSize"></span>������</span>��' +
                    '<span><a href="javascript:void(0);" onclick="$(\'#shareGrid\').costShareGrid(\'ajaxSave\')">���ٱ���</a></span>��' +
                    '<span><a id="quickImport" href="javascript:void(0);" toUrl="?model=finance_cost_costshare&action=importExcel' +
                    '&readImport=1&objId=' + options.param.objId + '&objType=' + options.param.objType;

                if (g.type == 'type') {
                    countRow += '&change=1';
                }

                countRow += '">��ݵ���</a></span>&nbsp;&nbsp;' +
                    '</td>' +
                    '<td>�ɷ�̯��' +
                    '<input type="text" id="costShareCanShare" class="readOnlyTxtShortCount" readonly="readonly"/>' +
                    '</td>' +
                    '<td>�ϼ�</td>' +
                    '<td>' +
                    '<input type="text" id="costShareShared" class="readOnlyTxtShortCount" readonly="readonly"/>' +
                    '</td>' +
                    '</tr>';
            } else {
                countRow = '<tr class="tr_count">' +
                    '<td colspan="9" align="left" style="color:red;">ע������ķ�̯��¼���ڴ�ҳ����ʾ�������ں�ͬ�鿴ҳ���в�ѯ��ط�̯��Ϣ��' +
                    '<span><a id="quickImport" href="javascript:void(0);" toUrl="?model=finance_cost_costshare&action=importExcel' +
                    '&readImport=0&objId=' + options.param.objId + '&objType=' + options.param.objType + '&change=1"';
                countRow += '>��ݵ���</a></span>&nbsp;&nbsp;' +
                    '</td>' +
                    '<td>�ɷ�̯��' +
                    '<input type="text" id="costShareCanShare" class="readOnlyTxtShortCount" readonly="readonly"/>' +
                    '</td>' +
                    '<td>�ϼ�</td>' +
                    '<td>' +
                    '<input type="text" id="costShareShared" class="readOnlyTxtShortCount" readonly="readonly"/>' +
                    '</td>' +
                    '</tr>';
            }
            g.find('tbody').after(countRow);

            $("#quickImport").click(function(){
                // ���н�ֹѡ��ķ�����ϸ,��������ҳ��ȥ
                var extParamStr = '';
                var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
                if(mainTypeSlted != undefined && mainTypeSlted.val() != undefined && mainTypeSlted.val() != '' && extParamStr == ""){
                    var mainTypeCode = mainTypeSlted.val();
                    extParamStr = '&payForBusiness='+mainTypeCode;
                }

                if($("#payForBusinessVal").val() != undefined && $("#payForBusinessVal").val() != '' && extParamStr == ""){
                    var payForBusinessVal = $("#payForBusinessVal").val();
                    extParamStr = '&payForBusiness='+payForBusinessVal;
                }
                var payForBusiness = $("#pagePayForBusiness").val();
                if(payForBusiness != undefined && payForBusiness != '' && extParamStr == ""){
                    extParamStr = '&payForBusiness='+payForBusiness;
                }

                var url = $("#quickImport").attr("toUrl") + extParamStr;
                url += "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900";
                // console.log(url);
                showThickboxWin(url);
            })
        }
    };

    //��ȡ��˾ѡ��
    var companyOptions;
    $.ajax({
        type: "POST",
        url: "?model=deptuser_branch_branch&action=listForSelect",
        async: false,
        dataType: "json",
        success: function (data) {
            companyOptions = data;
        }
    });

    //���ڻ�ȡ
    var periodNoOptions;
    var periodNoCurArr; // ��ǰ���������飬��'.'�ָ�
    $.ajax({
        type: "POST",
        url: "?model=finance_period_period&action=getNextOneYearPeriod",
        data : {type : 'cost'},
        async: false,
        dataType: "json",
        success: function (data) {
            periodNoOptions = data;
            periodNoCurArr = periodNoOptions[0].value.split('.');// ���ڵ�һ��ѡ��Ϊ��ǰ������
        }
    });

    var provinceArray; // ʡ�ݻ���
    var customerTypeArray; // �ͻ�����
    var saleDeptArray; // ��ǰ����
    var contractDeptArray; // �ۺ���
    var shareObjTypeOptions = []; // ȫ�ֱ����������̯��������
    $.ajax({
        type: "POST",
        url: "?model=system_datadict_datadict&action=ajaxGetForEasyUI",
        data: {parentCode: 'FTDXLX', isUse: 0},
        async: false,
        dataType: "json",
        success: function (data) {
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
        totalSize: 0,
        totalPage: 0,
        otherPageMoney: 0,
        colModel: [{
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
            display: '�������',
            width: 65,
            type: 'select',
            datacode: 'HTBK',
            emptyOption: true,
            event: {
                'change': function () {
                    var g = $(this).data("grid");
                    var rowNum = $(this).data('rowNum');
                    g.getCmpByRowAndCol(rowNum, 'moduleName').val($(this).find("option:selected").text());
                    var shareObjType = g.getCmpByRowAndCol(rowNum, 'shareObjType').val();
                    if(shareObjType == 'FTDXLX-07' || shareObjType == 'FTDXLX-08'){
                        // ���۸�����
                        g.setAreaInfo(rowNum, g.getCmpByRowAndCol(rowNum, "customerType").val(),
                            g.getCmpByRowAndCol(rowNum, "province").val(),
                            g.getCmpByRowAndCol(rowNum, "belongCompanyName").val(),
                            $(this).find("option:selected").text());
                    }
                }
            }
        }, {
            name: 'moduleName',
            display: '�����������',
            type: 'hidden'
        }, {
            name: 'belongCompany',
            display: '������˾',
            width: 80,
            type: "select",
            options: companyOptions,
            event: {
                'change': function () {
                    var g = $(this).data("grid");
                    var rowNum = $(this).data('rowNum');
                    g.getCmpByRowAndCol(rowNum, 'belongCompanyName').val($(this).find("option:selected").text());
                    var shareObjType = g.getCmpByRowAndCol(rowNum, 'shareObjType').val();
                    if(shareObjType == 'FTDXLX-07' || shareObjType == 'FTDXLX-08'){
                        // ���۸�����
                        g.setAreaInfo(rowNum, g.getCmpByRowAndCol(rowNum, "customerType").val(),
                            g.getCmpByRowAndCol(rowNum, "province").val(),
                            $(this).find("option:selected").text(),
                            g.getCmpByRowAndCol(rowNum, "moduleName").val());
                    }
                }
            },
            process: function (html, rowData) {
                if (rowData) {
                    return rowData.belongCompanyName;
                }
            }
        }, {
            name: 'belongCompanyName',
            display: '������˾',
            type: 'hidden'
        }, {
            name: 'inPeriod',
            display: '���������ڼ�',
            width: 80,
            type: 'select',
            otherValue: true,
            options: periodNoOptions
        }, {
            name: 'belongPeriod',
            display: '���ù����ڼ�',
            width: 80
        }, {
            name: 'detailType',
            display: '��������',
            width: 100,
            type: 'select',
            options: [{
                name: '���ŷ���',
                value: '1'
            }, {
                name: '��ͬ��Ŀ����',
                value: '2'
            }, {
                name: '�з�����',
                value: '3'
            }, {
                name: '��ǰ����',
                value: '4'
            }, {
                name: '�ۺ����',
                value: '5'
            }],
            event: {
                change: function () {
                    //�������ͱ��ʱ��Ӧ�Ķ�����ֶ�������
                    var g = $(this).data("grid");
                    g.initDetailType($(this).val(), $(this).data('rowNum'), false);
                }
            },
            process: function (html, rowData, $tr, g) {
                return g.getDetailTypeCN(html);
            }
        }, {
            name: 'shareObjType',
            display: '��̯��������',
            width: 100,
            type: 'select',
            datacode: 'FTDXLX',
            options: [],
            event: {
                change: function () {
                    //�������ͱ��ʱ��Ӧ�Ķ�����ֶ�������
                    var g = $(this).data("grid");
                    var rowNum = $(this).data('rowNum');
                    g.initShareObj(rowNum, g.getCmpByRowAndCol(rowNum, "shareObjType").val(), false);
                }
            },
            process: function (html, rowData, $tr, g) {
                if (rowData) {
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
            width: 150,
            align: 'left',
            type: 'statictext',
            process: function (html, rowData, $tr, g, $input, rowNum) {
                if (rowData && g.options.type == 'view') {
                    return g.initShareObjView(rowData.shareObjType, rowNum, rowData);
                }
            }
        }, {
            name: 'costShareObjExtends',
            display: '������Ϣ',
            width: 150,
            align: 'left',
            type: 'statictext',
            process: function (html, rowData, $tr, g, $input, rowNum) {
                if (rowData && g.options.type == 'view') {
                    return g.initShareObjExtendsView(rowData.shareObjType, rowNum, rowData);
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
            width: 100,
            readonly: true,
            event: {
                click: function (e) {
                    var g = e.data.gird;
                    var rowNum = e.data.rowNum;
                    if (g.options.type != 'view') {
                        g.showFeeType(rowNum);
                    }
                }
            }
        }, {
            name: 'costMoney',
            display: '��̯���',
            type: 'moneyAndNegative',
            align: 'right',
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
        }],
        /**
         * �������ʼ������
         */
        processData: function() {
            var g = this, el = this.el, p = this.options;
            // ����̬����
            if (p.data.length > 0) {
                g.reloadData(p.data);
            } else if (p.url) {// ��̨�첽����
                $.ajax({
                    type: 'POST',
                    url: p.url,
                    data: p.param,
                    async: p.async !== false,
                    dataType: 'json',
                    success: function(data) {
                        if (p.type == 'change') {
                            data = data ? data : [];
                            var suitData = [];
                            if (data.length > 100) {
                                var showAuditId = []; // ��ʾ��id
                                var showNum = 50; // ��ʾ��¼��
                                var start = data.length - 1; // ��ʼ����
                                for (var i = start; i >= 0; i--) {
                                    if (data[i].auditStatus == "1" && showNum > 0) {
                                        showAuditId.push(data[i].id);
                                        showNum--;
                                    }
                                }
                                for (var i = 0; i < data.length; i++) {
                                    if (data[i].auditStatus != "1" || $.inArray(data[i].id, showAuditId) > -1) {
                                        suitData.push(data[i]);
                                    } else {
                                        g.otherPageMoney = accAdd(g.otherPageMoney, data[i].costMoney, 2);
                                    }
                                }
                            } else {
                                suitData = data;
                            }
                            g.reloadData(suitData);
                            $(el).trigger('reloadData', [g, suitData]);
                        } else {
                            if (data.collection) {
                                // ��������ֵ
                                g.otherPageMoney = data.otherPageMoney;
                                g.totalSize = data.totalSize;
                                g.totalPage = Math.ceil(data.totalSize/p.param.pageSize);

                                // ��ҳ��ֵ
                                $("#" + el.attr("id") + "_nowPage").text(data.page);
                                $("#" + el.attr("id") + "_maxPage").text(g.totalPage);
                                $("#" + el.attr("id") + "_totalSize").text(g.totalSize);

                                // ���ݸ�ֵ
                                data = data.collection;
                            } else {
                                data = data ? data : [];
                                // ��������ֵ
                                g.totalSize = data.length;
                            }
                            g.reloadData(data);
                            $(el).trigger('reloadData', [g, data]);
                        }
                    }
                });
            }
            //timestamp = (new Date()).valueOf();
        },
        // ��дadd row
        addRow: function(rowNum, rowData) {
            var g = this, el = this.el, p = this.options;
            var elId = el.attr('id');
            rowNum = rowNum ? rowNum : 0;
            g.curRowNum++;
            g.curShowRowNum++;
            g.allAddRowNum++;
            var $tr = $("<tr class='tr_even'>");
            $tr.trigger('beforeAddRow', [rowNum, rowData, g]);
            var $removeBn = $('<span class="removeBn" id="' + elId + "_cmp_removeBn" + rowNum + '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>');
            // ��һ���Ƿ���ʾɾ����ť���Ҳ����ǵ�һ�� ���Ҳ��ǲ鿴?�� ?K��isAddAndDel??true
            if (p.type != 'view' && p.isAddAndDel == true) {
                if (p.isFristRowDenyDel == true && rowNum == 0) {
                    $removeBn.hide();
                }
                var $opTd = $("<td valign='top'>");
                $opTd.append($removeBn);
                var $h = $("<input type='hidden' name='" + p.objName + "["
                    + rowNum + "][rowNum_]' value='" + rowNum + "'>");
                $opTd.append($h);
                $tr.append($opTd);
            }

            // ���
            var hideStyle = p.hideRowNum == false ? '' : 'style="display:none;"';
            var $indexTd = $("<td valign='top' type='rowNum' " + hideStyle + ">");
            $indexTd.append(g.curRowNum);
            $tr.append($indexTd);
            $tr.data("index", rowNum);
            $tr.attr("rowNum", rowNum);
            $tr.data("rowData", rowData);

            $removeBn.click(function() {
                var n = $(this).parent().parent().attr("rowNum");
                g.isRemoveAction = true;
                $tr.trigger('beforeRemoveRow', [rowNum, rowData, g]);
                if (g.isRemoveAction !== false) {// ֧�����¼��������ô˲��������Ƿ�ɾ��
                    g.removeRow(n);
                }
            });

            // TODO ��취����������Ⱦ���
            g.tbody.append($tr);

            // ����ǰ�¼�
            $tr.trigger('beforeAddRow', [rowNum, rowData, g]);

            // ����
            var colLength = p.colModel.length;
            for (var i = 0; i < colLength; i++) {
                var config = p.colModel[i];
                var emptyOption = config.emptyOption ? config.emptyOption : false;//ѡ�����п���
                var type = config.type ? config.type : p.defaultType;// �ؼ�����
                var tclass = config.tclass ? config.tclass : p.defaultClass;// �ؼ���ʽ
                var name = config.name;
                var val = rowData ? rowData[name] : config.value;
                if (config.staticVal) {
                    val = config.staticVal;
                }
                var cmpId = elId + "_cmp_" + name + rowNum;// �ؼ�id
                var cmpName = p.objName + "[" + rowNum + "][" + name + "]";// �ؼ�����
                var $input;

                // �߼�����������ǲ鿴/����������Ϊ��̬ģʽ
                if (config.type != 'hidden') {
                    if (p.type == 'view') {
                        type = "statictext";
                        config.tclass = "";
                    } else if (p.type == 'view' && rowData) {
                        type = "statictext";
                        config.tclass = "";
                    }
                }

                switch (type) {
                    case 'select' :// ����ѡ��
                        $input = $("<select>");
                        var optionStr = "";
                        var option;
                        var selected;
                        // ��ѡ��
                        if (emptyOption == true) {
                            optionStr += "<option value=''></option>";
                        }
                        // ���������ֵ�
                        if (config.datacode) {
                            var data = g.datadict[name].datadictData;
                            if (data) {
                                for (var d = 0; d < data.length; d++) {
                                    option = data[d];
                                    selected = val == option.dataCode ? selected = "selected" : "";
                                    optionStr += "<option " + selected
                                        + " value='" + option.dataCode
                                        + "' title='" + option.dataName
                                        + "'>" + option.dataName
                                        + "</option>";
                                }
                            }
                        } else {// ����̬����
                            if (config.options) {
                                var otherValue = true;
                                for (var j = 0; j < config.options.length; j++) {
                                    option = config.options[j];
                                    if (val == option.value) {
                                        selected = "selected";
                                        otherValue = false;
                                    } else {
                                        selected = "";
                                    }
                                    optionStr += "<option " + selected
                                        + " title='" + option.name
                                        + "' value='" + option.value + "'>"
                                        + option.name + "</option>";
                                }
                                if (val && otherValue && config.otherValue) {
                                    var isAddOption = true;
                                    if(name == 'inPeriod'){// ���������ڼ䴦���������ڼ���ڵ�ǰ��ѡ������ʱ����׷��ѡ�����Ĭ��Ϊ��ǰ������
                                        var valArr = val.split('.');
                                        if(valArr[0] < periodNoCurArr[0] || (valArr[0] == periodNoCurArr[0] && valArr[1] < periodNoCurArr[1])){
                                            isAddOption = false;
                                        }
                                    }
                                    if(isAddOption){
                                        optionStr += "<option " + selected
                                            + " title='" + val
                                            + "' value='" + val + "'>"
                                            + val + "</option>";
                                    }
                                }
                            }
                        }
                        $input.append(optionStr);
                        break;
                    case 'statictext' :// ��̬�ı�
                        if (!config.tclass) {
                            tclass = "";// ��̬�ı�������Ĭ�ϵ���ʽ
                        }
                        $input = $("<div class='divChangeLine'>");
                        var html = config.html;
                        var oldHtml = html;
                        if (!html) {
                            html = val;
                            oldHtml = val;
                        }
                        if (config.process) {
                            html = config
                                .process(html, rowData, $tr, g, $input, rowNum);
                        }
                        $input.append(html);
                        break;
                    case 'hidden' :// ������
                        $input = $("<input type='hidden'>");
                        break;
                    default :
                        $input = $("<input type='text'>");
                        break;
                }
                if (val) {
                    $input.val(val);
                }
                // attr
                $input.attr('id', cmpId);
                $input.addClass(tclass);
                $input.attr('name', cmpName);
                $input.attr("readonly", config.readonly);
                // �����ʼֵ�洢,�ṩ���ʱ�ж��Ƿ��Ѿ��޸�
                $input.data("oldVal", $input.val());
                if (config.validation) {
                    $input.validation(config.validation);
                }
                // ���ؼ���������
                $input.data("rowNum", rowNum);// �ڼ���
                $input.data("colNum", i);// �ڼ���
                $input.data("grid", g);

                var $td = $("<td valign='top'>");
                if (type == 'hidden') {
                    $td.hide();
                }
                $td.append($input).css("text-align",
                    config.align ? config.align : "center");
                $tr.append($td);
                if (type != 'statictext' && config.process) {
                    config.process($input, rowData, $tr, g);
                }

                // ����
                if (type == 'moneyAndNegative') { // �������
                    $input.attr("etype", "moneyAndNegative");
                    createFormatOnClick($input.attr("id"), null, null, null, 2, true);
                }
                // �¼�����
                if (config.event) {
                    for (var e in config.event) {
                        $input.bind(e, {
                            rowData: rowData,
                            rowNum: rowNum,
                            colNum: i,
                            gird: g
                        }, config.event[e]);
                    }
                }
                if (config.width) {
                    if ((config.width + "").indexOf("%") > 0) {
                        $input.width('95%');
                    } else {
                        $input.width(config.width);
                    }
                }
            }

            // �����к�ʱ��
            $tr.trigger('addRow', [rowNum, rowData, g, $tr]);
        },
        event: {
            'removeRow': function (e, rowNum, rowData, index, g) {
                if (g.options.countKey != "") {
                    g.countShareMoney();
                }

                if(g.curRowNum <= 2){
                    var pageAct = $("#pageAct").val();
                    if(pageAct == 'edit' || pageAct == 'add') {
                        var validNum = 0;
                        setTimeout(function(){
                            $("[id^='shareGrid_cmp_removeBn']").each(function (i, item) {
                                if($(item).parents(".tr_even").css("display") != 'none'){
                                    validNum += 1;
                                }
                            });

                            $("[id^='shareGrid_cmp_removeBn']").each(function (i, item) {
                                if (validNum > 1) {
                                    $(item).show();
                                } else {
                                    $(item).hide();
                                }
                            });
                        },100);
                    }
                }
            },
            'reloadData': function (e, g, data) {
                if (data) {
                    //�Զ���ʼ��
                    //g.autoInitDetailType(data);

                    if (g.options.url != "" && g.options.countKey != "") {
                        g.countShareMoney();
                    }
                }
                //console.log((new Date()).valueOf() - timestamp);
            },
            // ���������ťʱ����
            'clickAddRow': function (e, rowNum, g) {
                if (rowNum == 0) {
                    g.getCmpByRowAndCol(rowNum, 'belongCompany').trigger('change');
                    g.initDetailType(g.getCmpByRowAndCol(rowNum, 'detailType').val(), rowNum);
                } else {
                    g.initNext(rowNum);
                }

                // ���ٱ���һ������,����ɾ��
                var pageAct = $("#pageAct").val();
                if(pageAct == 'edit' || pageAct == 'add') {
                    var validNum = 0;
                    $("[id^='shareGrid_cmp_removeBn']").each(function (i, item) {
                        if($(item).parents(".tr_even").css("display") != 'none'){
                            validNum += 1;
                        }
                    });
                    $("[id^='shareGrid_cmp_removeBn']").each(function (i, item) {
                        if (validNum > 1) {
                            $(item).show();
                        } else {
                            $(item).hide();
                        }
                    });

                    // �������
                    var newIndex = 1;
                    $("td[type='rowNum']").each(function(i,item){
                        if($(item).parents(".tr_even").css("display") != 'none'){
                            $(item).html(newIndex);
                            newIndex += 1;
                        }
                    })
                }

                // �������Ĭ��ֵ�������ֶ�,�򴥷���鲢�Զ������̯���ݵķ���
                if($("#defaultSelectedCostTypeId").val() != undefined){
                    dealDefaultCostshareInfo(rowNum);
                }
            },
            // �������ݺ���Ⱦ
            'addRow': function (e, rowNum, rowData, g) {
                if (rowData) {
                    g.initDetailType(rowData.detailType, rowNum, rowData);
                }
            }
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
         * ��ʼ����̯����ģ��
         * @param detailType
         * @param rowNum
         * @param rowData
         */
        initDetailType: function (detailType, rowNum, rowData) {
            // �����״̬��0�����棩ʱ����ʾΪֻ��
            if ((this.options.type == 'change' || this.options.type == 'edit') && rowData
                && rowData.auditStatus != "0" && rowData.auditStatus != "3") {
                this.initRowChange(rowNum, rowData.shareObjType, rowData);
            } else if (this.options.type == 'audit' && rowData && rowData.auditStatus == "1") {
                this.initRowChange(rowNum, this.getCmpByRowAndCol(rowNum, "shareObjType").val(), rowData);
            } else {
                // ��ʼ����̯�������͵�ѡ��
                var optionStr = '';
                var tmpShareObjType = shareObjTypeOptions[detailType];
                for (var i = 0; i < tmpShareObjType.length; i++) {
                    if ((rowData && rowData.shareObjType == tmpShareObjType[i].value)
                        || tmpShareObjType[i].value == this.getCmpByRowAndCol(rowNum, "defaultShareObjType").val()) {
                        optionStr += "<option value='" + tmpShareObjType[i].value + "' selected='selected'>" +
                            tmpShareObjType[i].text + "</option>";
                    } else {
                        optionStr += "<option value='" + tmpShareObjType[i].value + "'>" +
                            tmpShareObjType[i].text + "</option>";
                    }
                }
                this.getCmpByRowAndCol(rowNum, "shareObjType").empty().append(optionStr);
                // ��ʼ����̯�����Լ�������Ϣ
                if ((this.options.type == 'edit' || this.options.type == 'change' || this.options.type == 'audit')) {
                    if (rowData) {
                        this.initShareObj(rowNum, rowData.shareObjType, rowData);
                    } else {
                        this.initShareObj(rowNum, this.getCmpByRowAndCol(rowNum, "shareObjType").val(), rowData);
                    }
                }
            }
        },
        /**
         * �ѹ�����¼����
         * @param rowNum
         * @param shareObjType
         * @param rowData
         */
        initRowChange: function (rowNum, shareObjType, rowData) {
            this.getCmpByRowAndCol(rowNum, "removeBn").hide();
            this.getCmpByRowAndCol(rowNum, "module").hide().after(rowData.moduleName);
            this.getCmpByRowAndCol(rowNum, "belongCompany").hide().after(rowData.belongCompanyName);
            this.getCmpByRowAndCol(rowNum, "belongPeriod").hide().after(rowData.belongPeriod);
            this.getCmpByRowAndCol(rowNum, "inPeriod").empty()
                .append("<option value='" + rowData.inPeriod + "'>" + rowData.inPeriod + "</option>")
                .hide().after(rowData.inPeriod);
            this.getCmpByRowAndCol(rowNum, "detailType").hide().after(this.getDetailTypeCN(rowData.detailType));
            this.getCmpByRowAndCol(rowNum, "shareObjType").hide()
                .after(this.getDatadictValue('shareObjType', rowData.shareObjType));
            this.getCmpByRowAndCol(rowNum, "costTypeName").hide().after(rowData.costTypeName);
            this.getCmpByRowAndCol(rowNum, "costMoney").hide().after(moneyFormat2(rowData.costMoney));
            this.getCmpByRowAndCol(rowNum, "costShareObj")
                .append(this.initShareObjView(rowData.shareObjType, rowNum, rowData));
            this.getCmpByRowAndCol(rowNum, "costShareObjExtends")
                .append(this.initShareObjExtendsChange(rowData.shareObjType, rowNum, rowData));
        },
        /**
         * ��Ⱦ��̯����
         * @param rowNum
         * @param shareObjType
         * @param rowData
         */
        initShareObj: function (rowNum, shareObjType, rowData) {
            if (!rowData) {
                rowData = {
                    deptId: '',
                    deptName: '',
                    projectId: '',
                    projectCode: '',
                    projectName: '',
                    chanceId: '',
                    chanceCode: '',
                    province: '',
                    customerType: '',
                    contractId: '',
                    contractCode: '',
                    contractName: '',
                    belongId: '',
                    belongName: '',
                    belongDeptId: '',
                    belongDeptName: '',
                    projectType: '',
                    customerName: '',
                    customerId: '',
                    salesAreaId: '',
                    salesArea: '',
                    feeMan: '',
                    feeManId: ''
                };
            }
            var appendStr;
            var extendStr;
            var g = this;
            var tbId = g.el.attr('id');
            var objName = g.options.objName;
            var textWidth = g.getCmpByRowAndCol(rowNum, "costShareObj").width();
            // ��һЩ�������
            g.getCmpByRowAndCol(rowNum, "belongDeptName").yxselect_dept('remove');
            g.getCmpByRowAndCol(rowNum, "projectCode").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove').yxcombogrid_rdprojectfordl('remove');
            g.getCmpByRowAndCol(rowNum, "projectName").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove').yxcombogrid_rdprojectfordl('remove');
            g.getCmpByRowAndCol(rowNum, "customerName").yxcombogrid_customer('remove');
            g.getCmpByRowAndCol(rowNum, "costShareObj").empty();
            g.getCmpByRowAndCol(rowNum, "costShareObjExtends").empty();
            switch (shareObjType) {
                case 'FTDXLX-01' : // ���ŷ���
                    appendStr = '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
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
                        + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]" value="' + rowData.belongDeptId + '"/>'
                        + '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '"/>'
                    ;
                    textWidth = textWidth - 40;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                    g.getCmpByRowAndCol(rowNum, "belongDeptName").yxselect_dept({
                        hiddenId: g.getCmpByRowAndCol(rowNum, "belongDeptId").attr("id"),
                        disableDeptLevel: '1', // ����ѡ��һ������
                        unDeptFilter: $('#unDeptFilter').val(),
                        unSltDeptFilter: $('#unSltDeptFilter').val()
                    }).width(textWidth).attr("readonly", true);

                    // ������Ϣ��չ
                    extendStr = '�����飺<input type="text" class="txtmiddle" style="width: 100px;" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '" readonly="readonly"/>'
                    g.getCmpByRowAndCol(rowNum, "costShareObjExtends").append(extendStr);

                    // ������
                    g.getCmpByRowAndCol(rowNum, "projectName").yxcombogrid_esmproject({
                        hiddenId: g.getCmpByRowAndCol(rowNum, "projectId").attr('id'),
                        nameCol: 'projectName',
                        height: 250,
                        isFocusoutCheck: false,
                        gridOptions: {
                            param: {attribute: 'GCXMSS-04', statusArr: 'GCXMZT02,GCXMZT04,GCXMZT00'},
                            comboEx: false,
                            event: {
                                row_dblclick: function (e, row, data) {
                                    g.getCmpByRowAndCol(rowNum, "projectCode").val(data.projectCode);
                                    g.getCmpByRowAndCol(rowNum, "projectType").val(projectType);
                                }
                            }
                        },
                        event: {
                            clear: function () {
                                g.getCmpByRowAndCol(rowNum, "projectCode").val('');
                                g.getCmpByRowAndCol(rowNum, "projectType").val('');
                            }
                        }
                    }).width(80);
                    break;
                case 'FTDXLX-03' : // ��ͬ������Ŀ����
                    appendStr = '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]" value="' + rowData.belongDeptId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '"/>'
                    ;
                    textWidth = textWidth - 23;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                    break;
                case 'FTDXLX-02' : // ��ͬ������Ŀ����
                case 'FTDXLX-04' : // ��ͬ�з���Ŀ����
                case 'FTDXLX-10' : // �з�����
                    appendStr = '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]" value="' + rowData.belongDeptId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '"/>'
                    ;
                    textWidth = textWidth - 23;
                    var projectType = shareObjType == 'FTDXLX-02' ? 'esm' : 'rd'; // �ж���Ŀ����
                    if (shareObjType == 'FTDXLX-02') {
                        appendStr += '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                            + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>';
                        g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                        g.getCmpByRowAndCol(rowNum, "projectCode").yxcombogrid_projectall({
                            hiddenId: g.getCmpByRowAndCol(rowNum, "projectId").attr('id'),
                            valueCol: 'projectId',
                            nameCol: 'projectCode',
                            height: 250,
                            isFocusoutCheck: false,
                            gridOptions: {
                                param: {contractType: 'GCXMYD-01'},
                                comboEx: false,
                                event: {
                                    'row_dblclick': function (e, row, data) {
                                        g.getCmpByRowAndCol(rowNum, "projectName").val(data.projectName);
                                        g.getCmpByRowAndCol(rowNum, "projectType").val(projectType);
                                        //���÷��ù�������
                                        g.getCmpByRowAndCol(rowNum, "belongDeptId").val(data.deptId);
                                        g.getCmpByRowAndCol(rowNum, "belongDeptName").val(data.deptName);
                                    }
                                }
                            },
                            event: {
                                'clear': function () {
                                    g.getCmpByRowAndCol(rowNum, "projectName").val('');
                                    g.getCmpByRowAndCol(rowNum, "projectType").val('');
                                    //���÷��ù�������
                                    g.getCmpByRowAndCol(rowNum, "belongDeptId").val('');
                                    g.getCmpByRowAndCol(rowNum, "belongDeptName").val('');
                                }
                            }
                        }).width(textWidth);
                    } else {
                        appendStr += '<input type="hidden" class="txtmiddle" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                            + '<input type="text" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>';
                        g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                        var paramRdProject = {'is_delete': 0};
                        if (shareObjType == 'FTDXLX-04') {
                            paramRdProject['project_type'] = 4;
                        } else {
                            paramRdProject['project_typeNo'] = 4;
                        }
                        paramRdProject['attribute']='GCXMSS-05';
                        paramRdProject['statusArr']='GCXMZT02,GCXMZT04,GCXMZT00';

                        if(shareObjType == 'FTDXLX-10'){
                            paramRdProject['statusArr']='GCXMZT02,GCXMZT04';
                            paramRdProject['setStatusComboEx'] = 'true';// ���ù���ѡ��Ϊ�깤���ڽ�
                        }

                        g.getCmpByRowAndCol(rowNum, "projectName").yxcombogrid_esmproject({
                            hiddenId: g.getCmpByRowAndCol(rowNum, "projectId").attr('id'),
                            nameCol: 'projectName',
                            height: 250,
                            isFocusoutCheck: false,
                            gridOptions: {
                                comboEx: false,
                                param: paramRdProject,
                                event: {
                                    'row_dblclick': function (e, row, data) {
                                        g.getCmpByRowAndCol(rowNum, "projectCode").val(data.projectCode);
                                        g.getCmpByRowAndCol(rowNum, "projectType").val(projectType);
                                        //���÷��ù�������
                                        g.getCmpByRowAndCol(rowNum, "belongDeptId").val(data.deptId);
                                        g.getCmpByRowAndCol(rowNum, "belongDeptName").val(data.deptName);
                                    }
                                }
                            },
                            event: {
                                'clear': function () {
                                    g.getCmpByRowAndCol(rowNum, "projectCode").val('');
                                    g.getCmpByRowAndCol(rowNum, "projectType").val('');
                                    //���÷��ù�������
                                    g.getCmpByRowAndCol(rowNum, "belongDeptId").val('');
                                    g.getCmpByRowAndCol(rowNum, "belongDeptName").val('');
                                }
                            }
                        }).width(textWidth);
                    }
                    break;
                case 'FTDXLX-05' : // ��ǰ���� - ������Ŀ
                    appendStr = '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]" value="' + rowData.belongDeptId + '"/><div style="margin: 2px 0"></div>'
                        + '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_feeMan' + rowNum + '" name="' + objName + '[' + rowNum + '][feeMan]" value="' + rowData.feeMan + '" readonly="readonly" style="display:none;"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_feeManId' + rowNum + '" name="' + objName + '[' + rowNum + '][feeManId]" value="' + rowData.feeManId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_salesAreaId' + rowNum + '" name="' + objName + '[' + rowNum + '][salesAreaId]" value="' + rowData.salesAreaId + '"/>'
                    ;
                    textWidth = textWidth - 23;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                    var params = {'contractType': 'GCXMYD-04', 'statusArr': 'GCXMZT02,GCXMZT04,GCXMZT00'};
                    if(shareObjType == 'FTDXLX-05'){
                        params['statusArr']='GCXMZT02,GCXMZT04';
                        params['setStatusComboEx'] = 'true';// ���ù���ѡ��Ϊ�깤���ڽ�
                    }

                    g.getCmpByRowAndCol(rowNum, "projectCode").yxcombogrid_esmproject({
                        hiddenId: g.getCmpByRowAndCol(rowNum, "projectId").attr('id'),
                        nameCol: 'projectCode',
                        height: 250,
                        isFocusoutCheck: false,
                        gridOptions: {
                            param: params,
                            event: {
                                'row_dblclick': function (e, row, data) {
                                    g.getCmpByRowAndCol(rowNum, "projectName").val(data.projectName);
                                    g.getCmpByRowAndCol(rowNum, "projectType").val('esm');

                                    // ��ȡ������ĿId
                                    var trialProjectInfo = g.getTrialProject(data.contractId);
                                    if (trialProjectInfo && trialProjectInfo != false) {
                                        g.getCmpByRowAndCol(rowNum, "chanceId").val(trialProjectInfo.chanceId);
                                        g.getCmpByRowAndCol(rowNum, "chanceCode").val(trialProjectInfo.chanceCode);
                                        g.getCmpByRowAndCol(rowNum, "customerName").val(trialProjectInfo.customerName);
                                        g.getCmpByRowAndCol(rowNum, "customerId").val(trialProjectInfo.customerId);
                                        g.getCmpByRowAndCol(rowNum, "province").val(trialProjectInfo.province);
                                        g.getCmpByRowAndCol(rowNum, "customerType").val(trialProjectInfo.customerTypeName);
                                        g.getCmpByRowAndCol(rowNum, "salesArea").val(trialProjectInfo.areaName);
                                        g.getCmpByRowAndCol(rowNum, "salesAreaId").val(trialProjectInfo.areaCode);
                                        //���÷��ù�������
                                        g.getCmpByRowAndCol(rowNum, "belongDeptId").val(trialProjectInfo.deptId);
                                        g.getCmpByRowAndCol(rowNum, "belongDeptName").val(trialProjectInfo.deptName);

                                        // ��Ⱦ���óе���
                                        g.dealFeeMan(rowNum, trialProjectInfo.deptId, textWidth - 17);
                                    } else {
                                        //���÷��ù�������
                                        g.getCmpByRowAndCol(rowNum, "chanceId").val('');
                                        g.getCmpByRowAndCol(rowNum, "chanceCode").val('');
                                        g.getCmpByRowAndCol(rowNum, "customerName").val('');
                                        g.getCmpByRowAndCol(rowNum, "customerId").val('');
                                        g.getCmpByRowAndCol(rowNum, "province").val('');
                                        g.getCmpByRowAndCol(rowNum, "customerType").val('');
                                        g.getCmpByRowAndCol(rowNum, "salesArea").val('');
                                        g.getCmpByRowAndCol(rowNum, "salesAreaId").val('');
                                        g.getCmpByRowAndCol(rowNum, "belongDeptId").val('');
                                        g.getCmpByRowAndCol(rowNum, "belongDeptName").val('');
                                    }
                                }
                            }
                        },
                        event: {
                            'clear': function () {
                                g.getCmpByRowAndCol(rowNum, "projectName").val('');
                                g.getCmpByRowAndCol(rowNum, "projectType").val('');
                                //���÷��ù�������
                                g.getCmpByRowAndCol(rowNum, "belongDeptId").val('');
                                g.getCmpByRowAndCol(rowNum, "belongDeptName").val('');
                                g.getCmpByRowAndCol(rowNum, "chanceId").val('');
                                g.getCmpByRowAndCol(rowNum, "chanceCode").val('');
                                g.getCmpByRowAndCol(rowNum, "customerName").val('');
                                g.getCmpByRowAndCol(rowNum, "customerId").val('');
                                g.getCmpByRowAndCol(rowNum, "province").val('');
                                g.getCmpByRowAndCol(rowNum, "customerType").val('');
                                g.getCmpByRowAndCol(rowNum, "salesArea").val('');
                                g.getCmpByRowAndCol(rowNum, "salesAreaId").val('');
                                // ��Ⱦ���óе���
                                g.dealFeeMan(rowNum, '', textWidth - 17);
                            }
                        }
                    }).width(textWidth);
                    // ������Ϣ��չ
                    extendStr = '�̻���<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '" readonly="readonly"/><br/>'
                        + '�ͻ���<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '" readonly="readonly"/><br/>'
                        + 'ʡ�ݣ�<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '" readonly="readonly"/><br/>'
                        + '�ͻ����ͣ�<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '" readonly="readonly"/><br/>'
                        + '�������ţ�<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '" readonly="readonly"/><br/>'
                        + '��������<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_salesArea' + rowNum + '" name="' + objName + '[' + rowNum + '][salesArea]" value="' + rowData.salesArea + '" readonly="readonly"/>';
                    g.getCmpByRowAndCol(rowNum, "costShareObjExtends").append(extendStr);
                    // ��Ⱦ���óе���
                    if(rowData.belongDeptId != ''){
                        g.dealFeeMan(rowNum, rowData.belongDeptId, textWidth - 17, 'init');
                    }
                    break;
                case 'FTDXLX-06' :  // ��ǰ���� - �̻�
                    appendStr = '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]" value="' + rowData.belongDeptId + '"/><div style="margin: 2px 0"></div>'
                        + '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_feeMan' + rowNum + '" name="' + objName + '[' + rowNum + '][feeMan]" value="' + rowData.feeMan + '" readonly="readonly" style="display:none;"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_feeManId' + rowNum + '" name="' + objName + '[' + rowNum + '][feeManId]" value="' + rowData.feeManId + '" data-default="'+rowData.feeManId+'"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_salesAreaId' + rowNum + '" name="' + objName + '[' + rowNum + '][salesAreaId]" value="' + rowData.salesAreaId + '"/>'
                    ;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                    //���������Ⱦ
                    var codeObj = g.getCmpByRowAndCol(rowNum, "chanceCode");
                    if (codeObj.attr('wchangeTag2')) {
                        return false;
                    }
                    var $button = $("<span class='search-trigger' title='�̻����'>&nbsp;</span>");
                    $button.click(function () {
                        if (codeObj.val() == "") {
                            alert('������һ���̻����');
                            return false;
                        }
                    });
                    textWidth = textWidth - 40;
                    //�����հ�ť
                    var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
                    $button2.click(function () {
                        codeObj.val('');
                        // ��Ⱦ���óе���
                        g.dealFeeMan(rowNum, '', textWidth);
                    });
                    codeObj.bind('blur', function () {
                        g.getChanceInfo(rowNum);
                        // ��Ⱦ���óе���
                        g.dealFeeMan(rowNum, g.getCmpByRowAndCol(rowNum, "belongDeptId").val(), textWidth);
                        //������ַ��óе��ˣ���Ĭ��Ϊ���۸�����
                        if(g.getCmpByRowAndCol(rowNum, "feeMan").is(':visible')){
                            g.getCmpByRowAndCol(rowNum, "feeMan").val(g.getCmpByRowAndCol(rowNum, "belongName").val());
                            g.getCmpByRowAndCol(rowNum, "feeManId").val(g.getCmpByRowAndCol(rowNum, "belongId").val());
                        }
                    }).after($button2).after($button).attr("wchangeTag2", true).width(textWidth);

                    // ������Ϣ��չ
                    extendStr = '�ͻ���<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '" readonly="readonly"/><br/>'
                        + 'ʡ�ݣ�<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '" readonly="readonly"/><br/>'
                        + '�ͻ����ͣ�<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '" readonly="readonly"/><br/>'
                        + '�������ţ�<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '" readonly="readonly"/><br/>'
                        + '��������<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_salesArea' + rowNum + '" name="' + objName + '[' + rowNum + '][salesArea]" value="' + rowData.salesArea + '" readonly="readonly"/>';
                    g.getCmpByRowAndCol(rowNum, "costShareObjExtends").append(extendStr);
                    // ��Ⱦ���óе���
                    if(rowData.belongDeptId != ''){
                        g.dealFeeMan(rowNum, rowData.belongDeptId, textWidth, 'init');
                    }
                    break;
                case 'FTDXLX-07' : // ��ǰ���� - �ͻ�
                    appendStr = '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '" readonly="readonly"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '"/><div style="margin: 2px 0"></div>'
                        + '<select class="txtmiddle" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]"></select><div style="margin: 2px 0"></div>'
                        + '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_feeMan' + rowNum + '" name="' + objName + '[' + rowNum + '][feeMan]" value="' + rowData.feeMan + '" readonly="readonly" style="display:none;"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_feeManId' + rowNum + '" name="' + objName + '[' + rowNum + '][feeManId]" value="' + rowData.feeManId + '" data-default="'+rowData.feeManId+'"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_salesArea' + rowNum + '" name="' + objName + '[' + rowNum + '][salesArea]" value="' + rowData.salesArea + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_salesAreaId' + rowNum + '" name="' + objName + '[' + rowNum + '][salesAreaId]" value="' + rowData.salesAreaId + '"/>'
                    ;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);

                    // ��Ⱦ�ͻ�
                    g.getCmpByRowAndCol(rowNum, "customerName").yxcombogrid_customer({
                        hiddenId: g.getCmpByRowAndCol(rowNum, "customerId").attr('id'),
                        isFocusoutCheck: false,
                        isDown: false,
                        gridOptions: {
                            showcheckbox: false,
                            event: {
                                'row_dblclick': function (e, row, data) {
                                    g.getCmpByRowAndCol(rowNum, "province").val(data.Prov);
                                    var customerType = g.getCustomerNameByCode(data.TypeOne);
                                    g.getCmpByRowAndCol(rowNum, "customerType").val(customerType);
                                    // ���۸�����
                                    g.setAreaInfo(rowNum, customerType, data.Prov,
                                        g.getCmpByRowAndCol(rowNum, "belongCompanyName").val(),
                                        g.getCmpByRowAndCol(rowNum, "moduleName").val());
                                }
                            }
                        },
                        event: {
                            'clear': function () {
                                g.getCmpByRowAndCol(rowNum, "province").val('');
                                g.getCmpByRowAndCol(rowNum, "customerType").val('');
                                g.getCmpByRowAndCol(rowNum, "salesArea").val('');
                                g.getCmpByRowAndCol(rowNum, "salesAreaId").val('');
                            }
                        }
                    }).width(textWidth - 23);

                    // ��������ѡ��
                    g.getCmpByRowAndCol(rowNum, "belongDeptId").width(textWidth - 4).append(g.getSaleDeptOptions(rowData.belongDeptId))
                        .bind('change', function () {
                            g.changeSelectDept(rowNum);
                            // ��Ⱦ���óе���
                            g.dealFeeMan(rowNum, $(this).val(), textWidth - 40);
                        });

                    // ������Ϣ��չ
                    extendStr = 'ʡ�ݣ�<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '" readonly="readonly"/><br/>'
                        + '�ͻ����ͣ�<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '" readonly="readonly"/><br/>'
                        + '��������<select id="' + tbId + '_cmp_salesAreaOpt' + rowNum + '" data-initNum="0" style="width: 90px;margin-top:3px;display:none"></select><input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_salesAreaRead' + rowNum + '" value="' + rowData.salesArea + '" readonly="readonly"/>';
                    g.getCmpByRowAndCol(rowNum, "costShareObjExtends").append(extendStr);
                    this.setAreaInfo(rowNum,rowData.customerType,rowData.province,rowData.belongCompanyName,rowData.moduleName);

                    // ��Ⱦ���óе���
                    if(rowData.belongDeptId != ''){
                        g.dealFeeMan(rowNum, rowData.belongDeptId, textWidth - 40, 'init');
                    }
                    break;
                case 'FTDXLX-08' : // ��ǰ���� - ʡ��/�ͻ�����/��������
                    appendStr = '<select class="txtmiddle" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '"></select><div style="margin: 2px 0"></div>'
                        + '<select class="txtmiddle" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '"></select><div style="margin: 2px 0"></div>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '"/><div style="margin: 2px 0"></div>'
                        + '<select class="txtmiddle" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]"></select><div style="margin: 2px 0"></div>'
                        + '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_feeMan' + rowNum + '" name="' + objName + '[' + rowNum + '][feeMan]" value="' + rowData.feeMan + '" readonly="readonly" style="display:none;"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_feeManId' + rowNum + '" name="' + objName + '[' + rowNum + '][feeManId]" value="' + rowData.feeManId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_salesArea' + rowNum + '" name="' + objName + '[' + rowNum + '][salesArea]" value="' + rowData.salesArea + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_salesAreaId' + rowNum + '" name="' + objName + '[' + rowNum + '][salesAreaId]" value="' + rowData.salesAreaId + '"/>'
                    ;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);

                    // ʡ��ѡ��
                    g.getCmpByRowAndCol(rowNum, "province").width(textWidth - 4).append(g.getProvinceOptions(rowData.province))
                        .bind('change', function () {
                            // ���۸�����
                            g.setAreaInfo(rowNum, g.getCmpByRowAndCol(rowNum, "customerType").val(),
                                $(this).find("option:selected").text(),
                                g.getCmpByRowAndCol(rowNum, "belongCompanyName").val(),
                                g.getCmpByRowAndCol(rowNum, "moduleName").val());
                        });
                    // �ͻ�����ѡ��
                    g.getCmpByRowAndCol(rowNum, "customerType").width(textWidth - 4).append(g.getCustomerTypeOptions(rowData.customerType))
                        .bind('change', function () {
                            // ���۸�����
                            g.setAreaInfo(rowNum, $(this).find("option:selected").text(),
                                g.getCmpByRowAndCol(rowNum, "province").val(),
                                g.getCmpByRowAndCol(rowNum, "belongCompanyName").val(),
                                g.getCmpByRowAndCol(rowNum, "moduleName").val());
                        });
                    // ��������ѡ��
                    g.getCmpByRowAndCol(rowNum, "belongDeptId").width(textWidth - 4).append(g.getSaleDeptOptions(rowData.belongDeptId))
                        .bind('change', function () {
                            g.changeSelectDept(rowNum);
                            // ��Ⱦ���óе���
                            g.dealFeeMan(rowNum, $(this).val(), textWidth - 40);
                        });
                    // ������Ϣ��չ
                    extendStr = '��������<select id="' + tbId + '_cmp_salesAreaOpt' + rowNum + '" data-initNum="0" style="width: 90px;margin-top:3px;display:none"></select><input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_salesAreaRead' + rowNum + '" value="' + rowData.salesArea + '" readonly="readonly"/>';
                    g.getCmpByRowAndCol(rowNum, "costShareObjExtends").append(extendStr);
                    this.setAreaInfo(rowNum,rowData.customerType,rowData.province,rowData.belongCompanyName,rowData.moduleName);
                    
                    // ��Ⱦ���óе���
                    if(rowData.belongDeptId != ''){
                        g.dealFeeMan(rowNum, rowData.belongDeptId, textWidth - 40, 'init');
                    }
                    break;
                case 'FTDXLX-09' : // ���ۺ�ͬ / ��������
                    appendStr = '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '"/><div style="margin: 2px 0"></div>'
                        + '<select class="txtmiddle" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]"></select><div style="margin: 2px 0"></div>'
                        + '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_feeMan' + rowNum + '" name="' + objName + '[' + rowNum + '][feeMan]" value="' + rowData.feeMan + '" readonly="readonly" style="display:none;"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_feeManId' + rowNum + '" name="' + objName + '[' + rowNum + '][feeManId]" value="' + rowData.feeManId + '" data-default="'+rowData.feeManId+'"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_salesAreaId' + rowNum + '" name="' + objName + '[' + rowNum + '][salesAreaId]" value="' + rowData.salesAreaId + '"/>'
                    ;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                    //���������Ⱦ
                    var codeObj = g.getCmpByRowAndCol(rowNum, "contractCode");
                    if (codeObj.attr('wchangeTag2')) {
                        return false;
                    }
                    var $button = $("<span class='search-trigger' title='��ͬ���'>&nbsp;</span>");
                    $button.click(function () {
                        if (codeObj.val() == "") {
                            alert('������һ����ͬ���');
                            return false;
                        }
                    });
                    //�����հ�ť
                    var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
                    $button2.click(function () {
                        codeObj.val('');
                    });
                    codeObj.after($button2).after($button).attr("wchangeTag2", true).width(textWidth - 40).
                        bind('blur', function () {
                            g.getContractInfo(rowNum);
                            // ��Ⱦ���óе���
                            g.dealFeeMan(rowNum, g.getCmpByRowAndCol(rowNum, "belongDeptId").val(), textWidth - 40);
                        });

                    // ��������ѡ��
                    g.getCmpByRowAndCol(rowNum, "belongDeptId").width(textWidth - 4).
                        append(g.getContractDeptOptions(rowData.belongDeptId)).
                        bind('change', function () {
                            g.changeSelectDept(rowNum);
                            // ��Ⱦ���óе���
                            g.dealFeeMan(rowNum, $(this).val(), textWidth - 40);
                        });
                    // ������Ϣ��չ
                    extendStr = '��������<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_salesArea' + rowNum + '" name="' + objName + '[' + rowNum + '][salesArea]" value="' + rowData.salesArea + '" readonly="readonly"/>';
                    g.getCmpByRowAndCol(rowNum, "costShareObjExtends").append(extendStr);
                    // ��Ⱦ���óе���
                    if(rowData.belongDeptId != ''){
                        g.dealFeeMan(rowNum, rowData.belongDeptId, textWidth - 40, 'init');
                    }
                    break;
                case 'FTDXLX-11' : // ��ͬ
                    appendStr = '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]" value="' + rowData.belongDeptId + '"/><div style="margin: 2px 0"></div>'
                        + '<input type="hidden" id="' + tbId + '_cmp_salesAreaId' + rowNum + '" name="' + objName + '[' + rowNum + '][salesAreaId]" value="' + rowData.salesAreaId + '"/>'
                    ;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                    //���������Ⱦ
                    var codeObj = g.getCmpByRowAndCol(rowNum, "contractCode");
                    if (codeObj.attr('wchangeTag2')) {
                        return false;
                    }
                    var $button = $("<span class='search-trigger' title='��ͬ���'>&nbsp;</span>");
                    $button.click(function () {
                        if (codeObj.val() == "") {
                            alert('������һ����ͬ���');
                            return false;
                        }
                    });
                    //�����հ�ť
                    var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
                    $button2.click(function () {
                        codeObj.val('');
                    });
                    codeObj.after($button2).after($button).attr("wchangeTag2", true).width(textWidth - 40).
                        bind('blur', function () {
                            g.getContractInfo(rowNum,'���');
                        });

                    // ������Ϣ��չ
                    extendStr = '�ͻ���<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '" readonly="readonly"/><br/>'
                        + 'ʡ�ݣ�<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '" readonly="readonly"/><br/>'
                        + '�ͻ����ͣ�<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '" readonly="readonly"/><br/>'
                        + '�������ţ�<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '" readonly="readonly"/><br/>'
                        + '��������<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_salesArea' + rowNum + '" name="' + objName + '[' + rowNum + '][salesArea]" value="' + rowData.salesArea + '" readonly="readonly"/>';
                    g.getCmpByRowAndCol(rowNum, "costShareObjExtends").append(extendStr);
                    break;
                default :
            }
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
                    return rowData.projectName;
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
         * init share object extends info for change
         * @param shareObjType
         * @param rowNum
         * @param rowData
         */
        initShareObjExtendsChange: function (shareObjType, rowNum, rowData) {
            var stringBuffer = [];
            switch (shareObjType) {
                case 'FTDXLX-05' : // ��ǰ���� - ������Ŀ
                    // ������Ϣ��չ
                    stringBuffer.push('�̻���');
                    stringBuffer.push(rowData.chanceCode);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('�ͻ���');
                    stringBuffer.push(rowData.customerName);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('ʡ�ݣ�');
                    stringBuffer.push(rowData.province);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('�ͻ����ͣ�');
                    stringBuffer.push(rowData.customerType);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('�������ţ�');
                    stringBuffer.push(rowData.belongDeptName);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('��������');
                    stringBuffer.push(rowData.salesArea);
                    break;
                case 'FTDXLX-06' :  // ��ǰ���� - �̻�
                    stringBuffer.push('�ͻ���');
                    stringBuffer.push(rowData.customerName);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('ʡ�ݣ�');
                    stringBuffer.push(rowData.province);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('�ͻ����ͣ�');
                    stringBuffer.push(rowData.customerType);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('�������ţ�');
                    stringBuffer.push(rowData.belongDeptName);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('��������');
                    stringBuffer.push(rowData.salesArea);
                    break;
                case 'FTDXLX-07' : // ��ǰ���� - �ͻ�
                    stringBuffer.push('ʡ�ݣ�');
                    stringBuffer.push(rowData.province);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('�ͻ����ͣ�');
                    stringBuffer.push(rowData.customerType);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('�������ţ�');
                    stringBuffer.push(rowData.belongDeptName);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('��������');
                    stringBuffer.push(rowData.salesArea);
                    break;
                case 'FTDXLX-08' : // ��ǰ���� - ʡ��/�ͻ�����/��������
                    stringBuffer.push('��������');
                    stringBuffer.push(rowData.salesArea);
                    break;
                case 'FTDXLX-09' : // �ۺ���� - ��ͬ
                    stringBuffer.push('��������');
                    stringBuffer.push(rowData.salesArea);
                    break;
                case 'FTDXLX-11' : // ��ͬ��Ŀ���� - ��ͬ
                    stringBuffer.push('�ͻ���');
                    stringBuffer.push(rowData.customerName);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('ʡ�ݣ�');
                    stringBuffer.push(rowData.province);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('�ͻ����ͣ�');
                    stringBuffer.push(rowData.customerType);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('�������ţ�');
                    stringBuffer.push(rowData.belongDeptName);
                    stringBuffer.push('<br/>');
                    stringBuffer.push('��������');
                    stringBuffer.push(rowData.salesArea);
                    break;
                default :
            }
            // append share info
            var g = this;
            var tbId = g.el.attr('id');
            var objName = g.options.objName;
            stringBuffer.push(this.hiddenBuild(tbId, "belongDeptName", rowData.belongDeptName, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "belongDeptId", rowData.belongDeptId, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "projectId", rowData.projectId, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "projectName", rowData.projectName, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "projectCode", rowData.projectCode, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "projectType", rowData.projectType, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "chanceId", rowData.chanceId, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "chanceCode", rowData.chanceCode, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "customerId", rowData.customerId, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "customerName", rowData.customerName, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "customerType", rowData.customerType, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "province", rowData.province, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "contractId", rowData.contractId, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "contractCode", rowData.contractCode, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "contractName", rowData.contractName, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "belongName", rowData.belongName, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "belongId", rowData.belongId, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "feeManId", rowData.feeManId, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "feeMan", rowData.feeMan, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "salesAreaId", rowData.salesAreaId, rowNum, objName));
            stringBuffer.push(this.hiddenBuild(tbId, "salesArea", rowData.salesArea, rowNum, objName));
            return stringBuffer.join("");
        },
        /**
         * �Զ���ʼ�� - �������
         * @param data
         */
        autoInitDetailType: function (data) {
            for (var i = 0; i < data.length; i++) {
                this.initDetailType(data[i].detailType, i, data[i]);
            }
        },
        /**
         * ��ȡ��ͬ
         * @param rowNum
         * @param ExaStatus
         */
        getContractInfo: function (rowNum, ExaStatus) {
            var contractCodeObj = this.getCmpByRowAndCol(rowNum, "contractCode");
            var contractCode = contractCodeObj.val();
            var g = this;
            if (strTrim(contractCode) != '') {
                $.ajax({
                    type: "POST",
                    url: "?model=contract_contract_contract&action=ajaxGetContract",
                    data: {contractCode: contractCode,ExaStatus: ExaStatus},
                    async: false,
                    success: function (data) {
                        if (data != "") {
                            var obj = eval("(" + data + ")");
                            //�жϺ�ͬ״̬
                            // if(obj.state == '3' || obj.state == '7'){
                            //     alert('��ͬ�ѹر�,�������̯');

                            //�����ۺ��̯�ĺ�ͬ״̬����ѡ�����ִ���У���ɣ��رա� ID2182
                            if(obj.state == '2' || obj.state == '3' || obj.state == '4'){
                                g.getCmpByRowAndCol(rowNum, "contractId").val(obj.id);
                                g.getCmpByRowAndCol(rowNum, "contractName").val(obj.contractName);
                                g.getCmpByRowAndCol(rowNum, "customerId").val(obj.customerId);
                                g.getCmpByRowAndCol(rowNum, "customerName").val(obj.customerName);
                                g.getCmpByRowAndCol(rowNum, "customerType").val(obj.customerTypeName);
                                g.getCmpByRowAndCol(rowNum, "province").val(obj.contractProvince);
                                g.getCmpByRowAndCol(rowNum, "belongName").val(obj.prinvipalName);
                                g.getCmpByRowAndCol(rowNum, "belongId").val(obj.prinvipalId);
                                g.getCmpByRowAndCol(rowNum, "belongDeptId").val(obj.prinvipalDeptId);
                                g.getCmpByRowAndCol(rowNum, "belongDeptName").val(obj.prinvipalDept);
                                g.getCmpByRowAndCol(rowNum, "salesArea").val(obj.areaName);
                                g.getCmpByRowAndCol(rowNum, "salesAreaId").val(obj.areaCode);
                            }else{
                                alert('�ú�ͬ������Ч��ͬ���������̯��');
                                contractCodeObj.val('');
                                g.getCmpByRowAndCol(rowNum, "contractId").val('');
                                g.getCmpByRowAndCol(rowNum, "contractName").val('');
                                g.getCmpByRowAndCol(rowNum, "customerId").val('');
                                g.getCmpByRowAndCol(rowNum, "customerName").val('');
                                g.getCmpByRowAndCol(rowNum, "customerType").val('');
                                g.getCmpByRowAndCol(rowNum, "province").val('');
                                g.getCmpByRowAndCol(rowNum, "belongName").val('');
                                g.getCmpByRowAndCol(rowNum, "belongId").val('');
                                g.getCmpByRowAndCol(rowNum, "belongDeptId").val('');
                                g.getCmpByRowAndCol(rowNum, "belongDeptName").val('');
                                g.getCmpByRowAndCol(rowNum, "salesArea").val('');
                                g.getCmpByRowAndCol(rowNum, "salesAreaId").val('');
                            }
                        } else {
                            alert('ϵͳ�в����ڴ˺�ͬ�ţ�����������');
                            contractCodeObj.val('');
                            g.getCmpByRowAndCol(rowNum, "contractId").val('');
                            g.getCmpByRowAndCol(rowNum, "contractName").val('');
                            g.getCmpByRowAndCol(rowNum, "customerId").val('');
                            g.getCmpByRowAndCol(rowNum, "customerName").val('');
                            g.getCmpByRowAndCol(rowNum, "customerType").val('');
                            g.getCmpByRowAndCol(rowNum, "province").val('');
                            g.getCmpByRowAndCol(rowNum, "belongName").val('');
                            g.getCmpByRowAndCol(rowNum, "belongId").val('');
                            g.getCmpByRowAndCol(rowNum, "belongDeptId").val('');
                            g.getCmpByRowAndCol(rowNum, "belongDeptName").val('');
                            g.getCmpByRowAndCol(rowNum, "salesArea").val('');
                            g.getCmpByRowAndCol(rowNum, "salesAreaId").val('');
                        }
                    }
                });
            }
        },
        /**
         * get chance info by row number
         * @param rowNum
         */
        getChanceInfo: function (rowNum) {
            var chanceCodeObj = this.getCmpByRowAndCol(rowNum, "chanceCode");
            var chanceCode = chanceCodeObj.val();
            var g = this;
            if (strTrim(chanceCode) != '') {
                $.ajax({
                    type: "POST",
                    url: "?model=projectmanagent_chance_chance&action=ajaxChanceByCode",
                    data: {"chanceCode": chanceCode},
                    async: false,
                    success: function (data) {
                        if (data != "") {
                            var obj = eval("(" + data + ")");
                            g.getCmpByRowAndCol(rowNum, "chanceId").val(obj.id);
                            g.getCmpByRowAndCol(rowNum, "customerId").val(obj.customerId);
                            g.getCmpByRowAndCol(rowNum, "customerName").val(obj.customerName);
                            g.getCmpByRowAndCol(rowNum, "customerType").val(obj.customerTypeName);
                            g.getCmpByRowAndCol(rowNum, "province").val(obj.Province);
                            g.getCmpByRowAndCol(rowNum, "belongName").val(obj.prinvipalName);
                            g.getCmpByRowAndCol(rowNum, "belongId").val(obj.prinvipalId);
                            g.getCmpByRowAndCol(rowNum, "belongDeptId").val(obj.prinvipalDeptId);
                            g.getCmpByRowAndCol(rowNum, "belongDeptName").val(obj.prinvipalDept);
                            g.getCmpByRowAndCol(rowNum, "salesArea").val(obj.areaName);
                            g.getCmpByRowAndCol(rowNum, "salesAreaId").val(obj.areaCode);
                        } else {
                            alert('ϵͳ�в����ڴ��̻�������������');
                            chanceCodeObj.val('');
                            g.getCmpByRowAndCol(rowNum, "chanceId").val('');
                            g.getCmpByRowAndCol(rowNum, "customerId").val('');
                            g.getCmpByRowAndCol(rowNum, "customerName").val('');
                            g.getCmpByRowAndCol(rowNum, "customerType").val('');
                            g.getCmpByRowAndCol(rowNum, "province").val('');
                            g.getCmpByRowAndCol(rowNum, "belongName").val('');
                            g.getCmpByRowAndCol(rowNum, "belongId").val('');
                            g.getCmpByRowAndCol(rowNum, "belongDeptId").val('');
                            g.getCmpByRowAndCol(rowNum, "belongDeptName").val('');
                            g.getCmpByRowAndCol(rowNum, "salesArea").val('');
                            g.getCmpByRowAndCol(rowNum, "salesAreaId").val('');
                        }
                    }
                });
            }
        },
        /**
         * get trialProject info by row number
         * @param id
         */
        getTrialProject: function (id) {
            var obj = $.ajax({
                type: "POST",
                url: "?model=projectmanagent_trialproject_trialproject&action=ajaxGetInfo",
                data: {"id": id},
                async: false
            }).responseText;
            if (obj != "") {
                return eval("(" + obj + ")");
            } else {
                return false;
            }
        },
        /**
         * get customer type
         */
        getCustomerType: function () {
            if (!customerTypeArray) {
                $.ajax({
                    type: "GET",
                    url: 'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
                    async: false,
                    dataType: 'json',
                    success: function(obj) {
                        customerTypeArray = obj;
                    }
                });
            }
            return customerTypeArray;
        },
        /**
         * get customer type options
         * @param value
         */
        getCustomerTypeOptions: function (value) {
            var thisCustomerTypeArray = this.getCustomerType();
            var optionStr = '<option value=""></option>';
            for (var i = 0; i < thisCustomerTypeArray.length; i++) {
                if (value == thisCustomerTypeArray[i].text) {
                    optionStr += '<option value="' + thisCustomerTypeArray[i].text + '" selected="selected">' +
                        thisCustomerTypeArray[i].text + '</option>';
                } else {
                    optionStr += '<option value="' + thisCustomerTypeArray[i].text + '">' +
                        thisCustomerTypeArray[i].text + '</option>';
                }
            }
            return optionStr;
        },
        /**
         * get customer type name by code
         * @param value
         */
        getCustomerNameByCode: function (value) {
            var thisCustomerTypeArray = this.getCustomerType();
            for (var i = 0; i < thisCustomerTypeArray.length; i++) {
                if (value == thisCustomerTypeArray[i].value) {
                    return thisCustomerTypeArray[i].text;
                }
            }
            return value;
        },
        /**
         * get province
         */
        getProvince: function () {
            if (!provinceArray) {
                $.ajax({
                    type: "POST",
                    url: "?model=system_procity_province&action=getProvinceForEditGrid",
                    async: false,
                    dataType: 'json',
                    success: function(obj) {
                        provinceArray = obj;
                    }
                });
            }
            return provinceArray;
        },
        /**
         * get province options
         * @param value
         * @returns {string}
         */
        getProvinceOptions: function (value) {
            var thisProvinceArray = this.getProvince();
            var optionStr = '<option value=""></option>';
            for (var i = 0; i < thisProvinceArray.length; i++) {
                if (value == thisProvinceArray[i].name) {
                    optionStr += '<option value="' + thisProvinceArray[i].name + '" selected="selected">' + thisProvinceArray[i].name + '</option>';
                } else {
                    optionStr += '<option value="' + thisProvinceArray[i].name + '">' + thisProvinceArray[i].name + '</option>';
                }
            }
            return optionStr;
        },
        /**
         * get sale department
         */
        getSaleDept: function () {
            if (!saleDeptArray) {
                $.ajax({
                    type: "POST",
                    url: 'index1.php?model=finance_expense_expense&action=getSaleDept&detailType=4',
                    async: false,
                    dataType: 'json',
                    success: function(obj) {
                        saleDeptArray = obj;
                    }
                });
            }
            return saleDeptArray;
        },
        /**
         * get sale department options
         * @param value
         * @returns {string}
         */
        getSaleDeptOptions: function (value) {
            var thisSaleDept = this.getSaleDept();
            var optionStr = '<option value=""></option>';
            for (var i = 0; i < thisSaleDept.length; i++) {
                if (value == thisSaleDept[i].value) {
                    optionStr += '<option value="' + thisSaleDept[i].value + '" selected="selected">' + thisSaleDept[i].text + '</option>';
                } else {
                    optionStr += '<option value="' + thisSaleDept[i].value + '">' + thisSaleDept[i].text + '</option>';
                }
            }
            return optionStr;
        },
        /**
         * get contract department
         */
        getContractDept: function () {
            if (!contractDeptArray) {
                $.ajax({
                    type: "POST",
                    url: 'index1.php?model=finance_expense_expense&action=getSaleDept&detailType=5',
                    async: false,
                    dataType: 'json',
                    success: function(obj) {
                        contractDeptArray = obj;
                    }
                });
            }
            return contractDeptArray;
        },
        /**
         * get contract department options
         * @param value
         * @returns {string}
         */
        getContractDeptOptions: function (value) {
            var thisContractDept = this.getContractDept();
            var optionStr = '<option value=""></option>';
            for (var i = 0; i < thisContractDept.length; i++) {
                if (value == thisContractDept[i].value) {
                    optionStr += '<option value="' + thisContractDept[i].value + '" selected="selected">' +
                        thisContractDept[i].text + '</option>';
                } else {
                    optionStr += '<option value="' + thisContractDept[i].value + '">' +
                        thisContractDept[i].text + '</option>';
                }
            }
            return optionStr;
        },
        /**
         * when user change department selector, will change department name too
         * @param rowNum
         */
        changeSelectDept: function (rowNum) {
            this.getCmpByRowAndCol(rowNum, "belongDeptName")
                .val(this.getCmpByRowAndCol(rowNum, "belongDeptId").find("option:selected").text());
        },
        /**
         * form validate
         * @return {boolean}
         */
        checkForm: function (formMoney, needEqual) {
            var checkMoney = formMoney == undefined ? false : true;
            formMoney = Number(formMoney); // ǿ������ת��
            if (needEqual == undefined) needEqual = true;
            var g = this, el = this.el;
            var shareObjTypeArray = $("[id^='" + el.attr('id') + "_cmp_shareObjType']"); // ��Ϊ��������ʹ�õ����������ݣ���������Ҫ���⴦��
            var rs = true; // ��֤����ֵ
            var shareMoney = 0; // ��̯���
            if (shareObjTypeArray.length > 0) {
                shareObjTypeArray.each(function () {
                    var rowNum = $(this).data('rowNum');
                    if (g.isRowDel(rowNum) == false) {
                        var showNum = Number(rowNum) + 1;
                        if($("#fundType").val() == "KXXZB" && $("#hasRelativeContract option:selected").val() == 1 && g.getCmpByRowAndCol(rowNum, "costTypeName").val() == "Ͷ������" && ($("#relativeContractId").val() <= 0 || $("#relativeContractId").val() == '') && $("#relativeContract").val() != undefined){
                            alert('������ϸ����Ͷ�����ѡ����������ͬ,��֤������������ͬ�ű���');
                            $("#relativeContract").focus();
                            rs = false;
                            return false;
                        }

                        if (g.getCmpByRowAndCol(rowNum, "module").val() == "") {
                            alert('���÷�̯��ϸ��ڡ�' + showNum + '����û��ѡ���������');
                            rs = false;
                            return false;
                        }else if(g.getCmpByRowAndCol(rowNum, "costTypeName").val() == ""){
                            alert('���÷�̯��ϸ��ڡ�' + showNum + '���з�����ϸ����Ϊ��');
                            rs = false;
                            return false;
                        }

                        switch ($(this).val()) {
                            case 'FTDXLX-01' :
                                if (g.getCmpByRowAndCol(rowNum, "belongDeptName").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û�з��ù�������');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-02' : // ��ͬ������Ŀ����
                                if (g.getCmpByRowAndCol(rowNum, "projectCode").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û��ѡ����ù�����Ŀ');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-03' : // ��ͬ������Ŀ����
                                if (g.getCmpByRowAndCol(rowNum, "projectCode").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û��ѡ����ù�����Ŀ');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-04' : // ��ͬ�з���Ŀ����
                                if (g.getCmpByRowAndCol(rowNum, "projectCode").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û��ѡ����ù�����Ŀ');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-05' : // ��ǰ���� - ������Ŀ
                                if (g.getCmpByRowAndCol(rowNum, "projectCode").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û��ѡ����ù�����Ŀ');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '' || g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '0') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û����������');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "feeManId").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û��ѡ����óе���');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-10' : // �з�����
                                if (g.getCmpByRowAndCol(rowNum, "projectCode").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û��ѡ����ù�����Ŀ');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-06' :  // ��ǰ���� - �̻�
                                if (g.getCmpByRowAndCol(rowNum, "chanceCode").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û����д���ù����̻�');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "feeManId").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û��ѡ����óе���');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '' || g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '0') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û����������');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-07' :  // ��ǰ���� - �ͻ�/��������
                                if (g.getCmpByRowAndCol(rowNum, "customerName").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û�з��ÿͻ���Ϣ');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "belongDeptName").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û�з��ù�������');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '' || g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '0') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û����������');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "feeManId").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û��ѡ����óе���');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-08' :  // ��ǰ���� - �ͻ�ʡ��/�ͻ�����/��������
                                if (g.getCmpByRowAndCol(rowNum, "province").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û�з��õ�ʡ����Ϣ');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "customerType").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û�з��õĿͻ�����');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "belongDeptName").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û�з��ù�������');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '' || g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '0') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û����������');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "feeManId").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û��ѡ����óе���');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-09' : // ��ͬ����
                                if (g.getCmpByRowAndCol(rowNum, "contractCode").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û����д���ù�����ͬ');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "belongDeptName").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û�з��ù�������');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "feeManId").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û��ѡ����óе���');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-11' : // ��ͬ����
                                if (g.getCmpByRowAndCol(rowNum, "contractCode").val() == '') {
                                    alert('���÷�̯��ϸ��ڡ�' + showNum + '����û����д���ù�����ͬ');
                                    rs = false;
                                    return false;
                                }
                                break;
                            default :
                        }

                        if (g.getCmpByRowAndCol(rowNum, "costType").val() == "") {
                            alert('���÷�̯��ϸ��ڡ�' + showNum + '����û��ѡ�������ϸ');
                            rs = false;
                            return false;
                        }

                        var costMoney = g.getCmpByRowAndCol(rowNum, "costMoney").val();
                        if (costMoney == "" || costMoney == 0) {
                            alert('���÷�̯��ϸ��ڡ�' + showNum + '���н���Ϊ�ջ�0');
                            rs = false;
                            return false;
                        }

                        shareMoney = accAdd(shareMoney, costMoney, 2);
                    }
                });

                // ��������ҳ��ķ�̯���
                shareMoney = accAdd(shareMoney, g.otherPageMoney, 2);

                // ��������˽�����Ҫ���н����֤
                if (checkMoney && rs == true && formMoney != shareMoney) {
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
         * open select fee type window
         * @param rowNum
         */
        showFeeType: function (rowNum) {
            var g = this;
            // ��̬���ɷ�������ѡ��
            var selectDiv = $("#costShareSelectDiv");
            if (selectDiv.length == 0) {
                // �������һ��div���ڱ����������ѡ����
                var divObj = $("<div id='costShareSelectArea' style='display:none;'></div>");
                g.el.after(divObj);
                divObj.append("<div id='costShareSelectDiv'></div>");

                $.ajax({
                    type: "POST",
                    url: "?model=finance_expense_costtype&action=getCostType",
                    data: {isAction: 0},
                    async: false,
                    success: function (data) {
                        if (data != "") {
                            // ���ػ���
                            $("#costShareSelectDiv").append(data);
                        } else {
                            alert('û���ҵ��Զ���ķ�������');
                        }
                    }
                });

                var btnObj = "<input type='button' id='showCostTypeButton' value='ѡ���������' class='thickbox'" +
                    " alt='#TB_inline?height=600&width=1000&inlineId=costShareSelectArea' style='display:none;'/>";
                g.el.after(btnObj);
                tb_init('#showCostTypeButton');// ʹ�����thickbox����
                $("#showCostTypeButton").bind('click', function () {
                    return true;
                }).trigger('click');
                setTimeout(function () {
                    $('#costShareSelectDiv').masonry({
                        itemSelector: '.box'
                    });
                }, 200);
            } else {
                $("#showCostTypeButton").trigger('click');
                selectDiv.masonry({
                    itemSelector: '.box'
                });
            }

            var selected = '';
            g.getCmpByCol("costTypeId").each(function () {
                if ($(this).val() != "" && $(this).val() != "0") {
                    if ($(this).data('rowNum') == rowNum) {
                        selected = $(this).val();
                        $("#chk" + $(this).val()).attr('checked', true).next().attr('class', 'blue');
                        $("#costTypeSelectedHidden").val($(this).val());
                    } else if (selected != $(this).val()) {
                        $("#chk" + $(this).val()).attr('checked', false).next().attr('class', '');
                    }
                }
            });

            // ��������¼�
            $("#costShareSelectDiv input").unbind("click").bind("click", function () {
                g.setCostType(rowNum, $(this));
            });

            // ���ҳ���н�ֹѡ���IDֵ,������ֹѡ����
            var unSelectableIds = $("#unSelectableIds").val();
            if(unSelectableIds && unSelectableIds != undefined  && unSelectableIds != ''){
                var unSelectableIdsArr = unSelectableIds.split(",");
                $.each(unSelectableIdsArr,function(i,item){
                    if(item != ''){
                        $("#chk"+item).attr('checked', false).next().attr('class', '');
                        $("#chk"+item).attr("disabled","disabled");
                        $("#chk"+item).css("display","none");
                        if(g.getCmpByRowAndCol(rowNum, "costTypeId").val() == item){
                            g.setRowColValue(rowNum, 'costTypeId', '');
                            g.setRowColValue(rowNum, 'costTypeName', '');
                            g.setRowColValue(rowNum, 'parentTypeId', '');
                            g.setRowColValue(rowNum, 'parentTypeName', '');
                            $("#costTypeSelectedHidden").val('');
                        }
                    }
                });
            }else{
                $('#costShareSelectArea input[type="checkbox"]').each(function(i,item){
                    if($(item).attr("disabled")){
                        $(item).removeAttr("disabled");
                        $(item).css("display","inline-block");
                    }
                });
                $('#costShareSelectDiv input[type="checkbox"]').each(function(i,item){
                    if($(item).attr("disabled")){
                        $(item).removeAttr("disabled");
                        $(item).css("display","inline-block");
                    }
                });
            }

            // ����һ��ʼ��ֵѡ����,��������ڵ����ݺ�,�ٵ���������,����������ϸѡ��ҳ��,֮ǰ�������ѡ�е�
            if(g.getCmpByRowAndCol(rowNum, "costTypeId").val() == ''){
                $('#costShareSelectArea input[type="checkbox"]').each(function(i,item){
                    $(item).attr('checked', false).next().attr('class', '');
                });
                $('#costShareSelectDiv input[type="checkbox"]').each(function(i,item){
                    $(item).attr('checked', false).next().attr('class', '');
                });
            }
        },
        /**
         * select cost type
         * @param rowNum
         * @param jq
         */
        setCostType: function (rowNum, jq) {
            var costTypeSelectedHiddenObj = $("#costTypeSelectedHidden");
            $("#chk" + costTypeSelectedHiddenObj.val()).attr('checked', false).next().attr('class', ''); // ȡ��ѡ������
            if (jq.attr("checked") == true) {
                if($("#fundType") && $("#fundType").val() == 'KXXZB' && jq.attr('name') == 'Ͷ������'){
                    $("#isNeedRelativeContract").val("1");
                }else{
                    $("#relativeContract").val("");
                    $("#relativeContractId").val("");
                    $("#isNeedRelativeContract").val("");
                }

                this.setRowColValue(rowNum, 'costTypeId', jq.val());
                this.setRowColValue(rowNum, 'costTypeName', jq.attr('name'));
                this.setRowColValue(rowNum, 'parentTypeId', jq.attr('parentId'));
                this.setRowColValue(rowNum, 'parentTypeName', jq.attr('parentName'));
                jq.next().attr('class', 'blue');
                costTypeSelectedHiddenObj.val(jq.val());
            } else {
                this.setRowColValue(rowNum, 'costTypeId', '');
                this.setRowColValue(rowNum, 'costTypeName', '');
                this.setRowColValue(rowNum, 'parentTypeId', '');
                this.setRowColValue(rowNum, 'parentTypeName', '');
                jq.next().attr('class', '');
                $("#relativeContract").val("");
                $("#relativeContractId").val("");
                $("#isNeedRelativeContract").val("");
                costTypeSelectedHiddenObj.val('');
            }
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
         * ��������
         * @param data
         */
        importData: function (data) {
            var g = this;
            data = eval("(" + data + ")");
            var j = g.getAllAddRowNum();
            var i;
            for (i in data) {
                // �����������ݴ��ڴ�����ֱ������
                if (data[i].result && data[i].result != "") continue;

                // ����һ��Ĭ��ֵ
                data[i].auditStatus = "0";

                // ��������
                g.addRow(j, data[i]);
                // ��ʼ����̯�����Լ�������Ϣ
                // var optionStr = '';
                // var tmpShareObjType = shareObjTypeOptions[data[i].detailType];
                // for (var k = 0; k < tmpShareObjType.length; k++) {
                //     if (tmpShareObjType[k].value == data[i].shareObjType) {
                //         optionStr += "<option value='" + tmpShareObjType[k].value + "' selected='selected'>"
                //         + tmpShareObjType[k].text + "</option>";
                //     } else {
                //         optionStr += "<option value='" + tmpShareObjType[k].value + "'>" + tmpShareObjType[k].text
                //         + "</option>";
                //     }
                // }
                // this.getCmpByRowAndCol(j, "shareObjType").empty().append(optionStr);
                // this.initShareObj(j, this.getCmpByRowAndCol(j, "shareObjType").val(), data[i]);
                this.setAreaInfo(j,data[i]['customerType'],data[i]['province'],data[i]['belongCompanyName'],data[i]['moduleName']);
                j++;
            }

            var pageAct = $("#pageAct").val();
            if(pageAct == 'edit' || pageAct == 'add') {
                // �������
                var newIndex = 1;
                $("td[type='rowNum']").each(function (i, item) {
                    if ($(item).parents(".tr_even").css("display") != 'none') {
                        $(item).html(newIndex);
                        newIndex += 1;
                    }
                })
            }

            g.countShareMoney();
        },
        /**
         * �����̯���
         */
        countShareMoney: function () {
            var g = this;
            var formMoney = $("#" + g.options.countKey).val();
            if (formMoney != '') {
                var sharedMoney = 0;
                g.getCmpByCol('costMoney').each(function () {
                    if ($(this).val() != "") {
                        sharedMoney = accAdd(sharedMoney, $(this).val(), 2);
                    }
                });
                // ��������ҳ��Ҳ����
                sharedMoney = accAdd(sharedMoney, g.otherPageMoney, 2);

                var canShareMoney = accSub(formMoney, sharedMoney, 2);
                $("#costShareCanShare").val(moneyFormat2(canShareMoney));
                $("#costShareShared").val(moneyFormat2(sharedMoney));
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
                '<td style="text-align: right">' +
                moneyFormat2(sharedMoney) +
                '</td>' +
                '</tr>');
        },
        /**
         * ��ȡ���ڵ�ֵ
         * @param rowNum
         */
        getRowData: function (rowNum) {
            var g = this;
            return {
                projectId: g.getCmpByRowAndCol(rowNum, "projectId").val(),
                projectCode: g.getCmpByRowAndCol(rowNum, "projectCode").val(),
                projectName: g.getCmpByRowAndCol(rowNum, "projectName").val(),
                chanceId: g.getCmpByRowAndCol(rowNum, "chanceId").val(),
                chanceCode: g.getCmpByRowAndCol(rowNum, "chanceCode").val(),
                province: g.getCmpByRowAndCol(rowNum, "province").val(),
                customerType: g.getCmpByRowAndCol(rowNum, "customerType").val(),
                contractId: g.getCmpByRowAndCol(rowNum, "contractId").val(),
                contractCode: g.getCmpByRowAndCol(rowNum, "contractCode").val(),
                contractName: g.getCmpByRowAndCol(rowNum, "contractName").val(),
                belongId: g.getCmpByRowAndCol(rowNum, "belongId").val(),
                belongName: g.getCmpByRowAndCol(rowNum, "belongName").val(),
                belongDeptId: g.getCmpByRowAndCol(rowNum, "belongDeptId").val(),
                belongDeptName: g.getCmpByRowAndCol(rowNum, "belongDeptName").val(),
                projectType: g.getCmpByRowAndCol(rowNum, "projectType").val(),
                customerName: g.getCmpByRowAndCol(rowNum, "customerName").val(),
                customerId: g.getCmpByRowAndCol(rowNum, "customerId").val(),
                parentTypeId: g.getCmpByRowAndCol(rowNum, "parentTypeId").val(),
                parentTypeName: g.getCmpByRowAndCol(rowNum, "parentTypeName").val(),
                costTypeId: g.getCmpByRowAndCol(rowNum, "costTypeId").val(),
                costTypeName: g.getCmpByRowAndCol(rowNum, "costTypeName").val(),
                costMoney: g.getCmpByRowAndCol(rowNum, "costMoney", true).val(),
                inPeriod: g.getCmpByRowAndCol(rowNum, "inPeriod").val(),
                belongPeriod: g.getCmpByRowAndCol(rowNum, "belongPeriod").val(),
                belongCompany: g.getCmpByRowAndCol(rowNum, "belongCompany").val(),
                belongCompanyName: g.getCmpByRowAndCol(rowNum, "belongCompanyName").val(),
                detailType: g.getCmpByRowAndCol(rowNum, "detailType").val(),
                shareObjType: g.getCmpByRowAndCol(rowNum, "shareObjType").val(),
                auditStatus: 0,
                feeManId: g.getCmpByRowAndCol(rowNum, "feeManId").val(),
                feeMan: g.getCmpByRowAndCol(rowNum, "feeMan").val(),
                salesAreaId: g.getCmpByRowAndCol(rowNum, "salesAreaId").val(),
                salesArea: g.getCmpByRowAndCol(rowNum, "salesArea").val()
            };
        },
        /**
         * ��ʼ����һ������
         * @param rowNum
         */
        initNext: function (rowNum) {
            var g = this;
            var prevRowNum = rowNum - 1;
            while (prevRowNum >= 0) {
                if (g.isRowDel(prevRowNum) == true) {
                    prevRowNum--;
                } else {
                    break;
                }
            }
            if (g.getCmpByRowAndCol(rowNum, "projectId").length == 0) {
                g.getCmpByRowAndCol(rowNum, 'belongCompany').trigger('change');
                g.initDetailType(g.getCmpByRowAndCol(rowNum, 'detailType').val(), rowNum);
            } else {
                var prevRowData = g.getRowData(prevRowNum);
                // ��Щ����Ҫ���и�ֵ
                g.setRowColValue(rowNum, "costTypeId", prevRowData.costTypeId);
                g.setRowColValue(rowNum, "costTypeName", prevRowData.costTypeName);
                g.setRowColValue(rowNum, "parentTypeId", prevRowData.parentTypeId);
                g.setRowColValue(rowNum, "parentTypeName", prevRowData.parentTypeName);
                g.setRowColValue(rowNum, "costMoney", prevRowData.costMoney, true);
                g.setRowColValue(rowNum, "inPeriod", prevRowData.inPeriod);
                g.setRowColValue(rowNum, "belongPeriod", prevRowData.belongPeriod);
                g.setRowColValue(rowNum, "belongCompany", prevRowData.belongCompany);
                g.setRowColValue(rowNum, "belongCompanyName", prevRowData.belongCompanyName);
                g.setRowColValue(rowNum, "detailType", prevRowData.detailType);
                g.setRowColValue(rowNum, "shareObjType", prevRowData.shareObjType);

                // ���ط�̯������Ϣ
                g.initDetailType(prevRowData.detailType, rowNum, prevRowData);
            }
        },
        /**
         * �ж����Ƿ񱻼�ɾ��
         */
        isRowDel : function(rowNum) {
            var g = this, el = this.el, p = this.options;
            // �����ѯ�����У����ʾ�Ѿ�ɾ��
            if ($(el).find("tr[rowNum='" + rowNum + "']").length == 0) {
                return true;
            } else {
                var delTagNameObj = $("#" + el.attr('id') + "_cmp_" + p.delTagName + rowNum);
                if (delTagNameObj.length == 0) {
                    return false;
                } else {
                    return delTagNameObj.val() == 1;
                }
            }
        },
        /**
         * ���óе���
         * @param rowNum
         * @param deptId
         * @param textWidth
         * @param type
         */
        dealFeeMan: function (rowNum, deptId, textWidth, type) {
            var g = this;
            var feeManObj = g.getCmpByRowAndCol(rowNum, "feeMan");
            var feeManIdObj = g.getCmpByRowAndCol(rowNum, "feeManId");
            $("#feeMansSelect_"+rowNum).remove();
            //��ȡ���۲�
            var saleDeptIdArr = $("#saleDeptId").val().split(",");
            //���ù�������Ϊ���۲�������ʾ���óе���
            if(deptId == 329){// ϵͳ������
                feeManObj.hide();
                feeManObj.val('');
                feeManIdObj.val('');
                feeManObj.yxselect_user("remove");
                var optsStr = getFeeMansSelectOpts(rowNum);
                if(optsStr != ""){
                    feeManObj.before("<select id='feeMansSelect_"+rowNum+"' onchange='setFeeManInfo(\""+rowNum+"\")' style='width:100px'>"+optsStr+"</select>");
                    setFeeManInfo(rowNum);
                }
            }else if(saleDeptIdArr.indexOf(deptId) != -1){
                //��ʾ���óе���
                feeManObj.show();
                //��Ⱦ���óе���
                feeManObj.yxselect_user("remove").yxselect_user({
                    hiddenId: feeManIdObj.attr('id'),
                    deptIds: deptId,
                    isDeptAddedUser: true,//������Ҫ���������Ա
                    isDeptSetUserRange: true,//����������Աѡ��Χ
                    event: {
                        clearReturn: function() {
                        }
                    }
                }).width(textWidth);
                //���ñ��������ڲ�������ù���������ͬ�����Զ���ȡ���ñ�����ԱΪ���óе���
                if(type != 'init'){//��ʼ����ʱ�򲻸���ԭ����ֵ
                    if($("#deptId").val() == deptId){
                        feeManObj.val($("#principalName").val());
                        feeManIdObj.val($("#principalId").val());
                    }else{
                        feeManObj.val("");
                        feeManIdObj.val("");
                    }
                }
            }else{
                //���ط��óе���
                feeManObj.yxselect_user("remove").hide();
                //���óе���Ĭ��Ϊ������������
                feeManObj.val($("#principalName").val());
                feeManIdObj.val($("#principalId").val());
            }
        },
        /**
         * ���۸�����
         * @param rowNum
         * @param customerType
         * @param province
         * @param businessBelong
         * @param module
         */
        setAreaInfo: function (rowNum, customerType, province, businessBelong, module) {
            var g = this;
            var shareObjType = g.getCmpByRowAndCol(rowNum, "shareObjType").val();
            var needRelodSalesArea = false;// �Ƿ���Ҫ�첽��������������Ϣ��ʾ ����PMS2383
            // �༭ҳ��༭������ʾ
            var iniNum = g.getCmpByRowAndCol(rowNum, "salesAreaOpt").attr("data-initNum");
            iniNum = parseInt(iniNum);

            if(shareObjType == "FTDXLX-07" || shareObjType == "FTDXLX-08"){
                needRelodSalesArea = true;
            }
            if (customerType != '' && province != '' && businessBelong != '' && module != '') {
                $.ajax({
                    type: 'POST',
                    url: "?model=system_region_region&action=ajaxConRegionByName",
                    data: {
                        customerType: customerType,
                        province: province,
                        businessBelong: businessBelong,
                        module: module,
                        needAll: (needRelodSalesArea)? 1 : 0
                    },
                    async: false,
                    dataType: 'json',
                    success: function(returnValue) {
                        if (returnValue) {
                            if(needRelodSalesArea){// �����̯�������ڡ���ǰ���� - ʡ��/�ͻ�����/�������š�����ǰ���� - �ͻ���
                                if(g.getCmpByRowAndCol(rowNum, "salesArea").val() != '' && iniNum <= 0){

                                    g.getCmpByRowAndCol(rowNum, "salesAreaOpt").html("");
                                    g.getCmpByRowAndCol(rowNum, "salesAreaRead").val("");
                                }else{
                                    g.getCmpByRowAndCol(rowNum, "salesAreaOpt").html("");
                                    g.getCmpByRowAndCol(rowNum, "salesAreaRead").val("");
                                    g.getCmpByRowAndCol(rowNum, "salesArea").val("");
                                    g.getCmpByRowAndCol(rowNum, "salesAreaId").val("");
                                }
                                if(returnValue.length > 1){// �ж����������,��ʾ����ѡ��,�����ı���
                                    var optionsStr = '<option value="">..��ѡ��..</option>';
                                    $.each(returnValue,function(){
                                        if(g.getCmpByRowAndCol(rowNum, "salesArea").val() != '' && iniNum <= 0){
                                            if(g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == $(this)[0]['id']){
                                                iniNum += 1;
                                                g.getCmpByRowAndCol(rowNum, "salesAreaOpt").attr("data-initNum",iniNum);
                                                g.getCmpByRowAndCol(rowNum, "salesAreaRead").val($(this)[0]['areaName']);
                                                optionsStr += '<option value="'+$(this)[0]['id']+'" selected>'+$(this)[0]['areaName']+'</option>';
                                            }else{
                                                optionsStr += '<option value="'+$(this)[0]['id']+'">'+$(this)[0]['areaName']+'</option>';
                                            }
                                        }else{
                                            optionsStr += '<option value="'+$(this)[0]['id']+'">'+$(this)[0]['areaName']+'</option>';
                                        }
                                    });
                                    g.getCmpByRowAndCol(rowNum, "salesAreaOpt").html(optionsStr);
                                    g.getCmpByRowAndCol(rowNum, "salesAreaOpt").show();
                                    g.getCmpByRowAndCol(rowNum, "salesAreaRead").hide();

                                    // �û�ѡ�������,������Ӧλ�õ�����������Ϣ
                                    var selectOrId = g.getCmpByRowAndCol(rowNum, "salesAreaOpt").attr("id");
                                    $("#"+selectOrId).change(function(){
                                        var salesAreaId = $('#'+selectOrId+' option:selected').val();
                                        var salesArea = $('#'+selectOrId+' option:selected').text();
                                        g.getCmpByRowAndCol(rowNum, "salesAreaRead").val(salesArea);
                                        g.getCmpByRowAndCol(rowNum, "salesArea").val(salesArea);
                                        g.getCmpByRowAndCol(rowNum, "salesAreaId").val(salesAreaId);
                                    });
                                }else{
                                    g.getCmpByRowAndCol(rowNum, "salesArea").val(returnValue[0].areaName);
                                    g.getCmpByRowAndCol(rowNum, "salesAreaId").val(returnValue[0].id);
                                    g.getCmpByRowAndCol(rowNum, "salesAreaRead").val(returnValue[0].areaName);
                                    g.getCmpByRowAndCol(rowNum, "salesAreaRead").show();
                                    g.getCmpByRowAndCol(rowNum, "salesAreaOpt").html("");
                                    g.getCmpByRowAndCol(rowNum, "salesAreaOpt").hide();
                                }
                            }else{
                                g.getCmpByRowAndCol(rowNum, "salesArea").val(returnValue[0].areaName);
                                g.getCmpByRowAndCol(rowNum, "salesAreaId").val(returnValue[0].id);
                            }
                        } else {
                            if(needRelodSalesArea){// �����̯�������ڡ���ǰ���� - ʡ��/�ͻ�����/�������š�����ǰ���� - �ͻ���
                                g.getCmpByRowAndCol(rowNum, "salesAreaOpt").html("");
                                g.getCmpByRowAndCol(rowNum, "salesAreaRead").val("");
                                g.getCmpByRowAndCol(rowNum, "salesAreaOpt").hide();
                                g.getCmpByRowAndCol(rowNum, "salesAreaRead").show();
                            }
                            g.getCmpByRowAndCol(rowNum, "salesArea").val("");
                            g.getCmpByRowAndCol(rowNum, "salesAreaId").val("");
                        }
                    }
                });
            } else {
                return false;
            }
        },
        /**
         * �����ַ���ƴ��
         * @param tableId
         * @param key
         * @param val
         * @param rowNum
         * @param objName
         */
        hiddenBuild: function (tableId, key, val, rowNum, objName) {
            var stringBuffer = [];
            stringBuffer.push('<input type="hidden" id="');
            stringBuffer.push(tableId);
            stringBuffer.push('_cmp_');
            stringBuffer.push(key);
            stringBuffer.push(rowNum);
            stringBuffer.push('" name="');
            stringBuffer.push(objName);
            stringBuffer.push('[');
            stringBuffer.push(rowNum);
            stringBuffer.push('][');
            stringBuffer.push(key);
            stringBuffer.push(']" value="');
            stringBuffer.push(val);
            stringBuffer.push('"/>');
            return stringBuffer.join("");
        },
        /**
         * ��һҳ
         */
        prevPage: function () {
            var p = this.options.param;
            if (p.page == 1) {
                alert("�Ѿ��ǵ�һҳ");
            } else {
                // ����һ�±�ҳ����
                this.ajaxSave(true);

                p.page--;
                this.setParam(p);
                this.processData();
            }
        },
        /**
         * ��һҳ
         */
        nextPage: function () {
            var p = this.options.param;
            if (p.page == this.totalPage) {
                alert("�Ѿ������һҳ");
            } else {
                // ����һ�±�ҳ����
                this.ajaxSave(true);

                p.page++;
                this.setParam(p);
                this.processData();
            }
        },
        /**
         * AJAX����
         */
        ajaxSave: function (quiet) {
            var form = $("#form1");
            if (form.length == 0) {
                alert('û�л�ȡ�����Ա��������');
                return false;
            } else {
                //��֤
                if(this.checkForm()){
                    var g = this;
                    var p = this.options.param;
                    var param = form.serializeObject();
                    param.objId = p.objId;
                    param.objType = p.objType;
                    // ���ٱ���
                    $.ajax({
                        type: 'POST',
                        url: "?model=finance_cost_costshare&action=ajaxSave",
                        data: param,
                        dataType: 'json',
                        success: function(data) {
                            if (!quiet) {
                                if (data == "1") {
                                    alert("����ɹ�");
                                    g.processData();
                                } else {
                                    alert("����ʧ��");
                                }
                            }
                        }
                    });
                } else  {
                    return false;
                }
            }
        }
    });
})(jQuery);

// ����һ�����л��ķ���
$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

var getFeeMansSelectOpts = function (rowNum){
    var idObj = '#shareGrid_cmp_feeManId'+rowNum;
    var opts = "";
    if($("#feemansForXtsSales").val() != undefined){
        var feemansForXtsSales = $("#feemansForXtsSales").val();
        var feemansForXtsSalesArr = feemansForXtsSales.split(",");
        if(feemansForXtsSalesArr .length > 0){
            var feemanDefaultId = $(idObj).attr("data-default");
            $(idObj).attr("data-default","");
            $.each(feemansForXtsSalesArr,function(i,item){
                if(item != ''){
                    var itemArr = item.split(":");
                    if(feemanDefaultId != ''){
                        opts += (itemArr[0] == feemanDefaultId)? "<option value = '"+itemArr[0]+"' data-name = '"+itemArr[1]+"' selected>"+itemArr[1]+"</option>" : "<option value = '"+itemArr[0]+"' data-name = '"+itemArr[1]+"'>"+itemArr[1]+"</option>";
                    }else{
                        opts += (opts == "")? "<option value = '"+itemArr[0]+"' data-name = '"+itemArr[1]+"' selected>"+itemArr[1]+"</option>" : "<option value = '"+itemArr[0]+"' data-name = '"+itemArr[1]+"'>"+itemArr[1]+"</option>";
                    }
                }
            })
        }
    }
    return opts;
}

// ���÷��óе�����Ϣ
var setFeeManInfo = function (rowNum){
    var sltObj = '#feeMansSelect_'+rowNum;
    var idObj = '#shareGrid_cmp_feeManId'+rowNum;
    var nameObj = '#shareGrid_cmp_feeMan'+rowNum;

    var seletedVal = $(sltObj).find("option:selected");
    $(idObj).val(seletedVal.val());
    $(nameObj).val(seletedVal.attr('data-name'));
}

$(function(){
    var pageAct = $("#pageAct").val();
    if(pageAct == 'edit' || pageAct == 'add'){
        setTimeout(function(){
            // ���ٱ���һ������,����ɾ��
            $("[id^='shareGrid_cmp_removeBn']").each(function(i,item){
                if(i > 0){
                    $(item).show();
                }else{
                    if($("[id^='shareGrid_cmp_removeBn']").length > 1){
                        $(item).show();
                    }else{
                        $(item).hide();
                    }
                }
            });
        },200);
    }
});