/**
 * �򵥱����� ֻ���в鿴���ݹ���
 *
 * @1.����
 * @2.���϶�����ק
 * @3.��ʾ������
 * @4.��ҳ
 * @5.��ѡ
 *
 */
(function($) {
    $.woo.component.subclass('woo.yxsgrid', {
        options: {
            height: 'fit', // flexigrid����ĸ߶ȣ�auto����Ӧ��fitȫ��
            width: 'auto', // ���ֵ��auto��ʾ����ÿ�еĿ���Զ�����
            striped: true, // �Ƿ���ʾ����Ч����Ĭ������ż��������ʽ
            novstripe: false,
            minwidth: 30, // �е���С���
            minheight: 80, // �е���С�߶�
            resizable: false, // �Ƿ������
            url: false, // ajax��ʽ��Ӧ��url��ַ
            method: 'POST', // ���ݷ��ͷ�ʽ
            dataType: 'json', // ���ݼ��ص����ͣ�xml,json
            errormsg: '��ȡ����ʧ��', // ����������Ϣ
            nowrap: true, // �Ƿ񲻻���
            isShowNum: true,// �Ƿ���ʾ���
            setSearchAll: true, // �Ƿ���ʾ����������������

            // ------ ��ҳ�����Ϣ��ʼ-------
            page: 1, // Ĭ�ϵ�ǰҳ
            total: 1, // ��ҳ����
            pagestat: '��ʾ��¼��{from}��{to}������ {total} ��', // ��ʾ��ǰҳ����ҳ�����ʽ
            usepager: true, // �Ƿ��ҳ
            pageSize: 20, // ÿҳĬ�ϵĽ����
            pageSizeOptions: [1, 10, 15, 20, 25, 40, 100], // ��ѡ���趨��ÿҳ�����
            useRp: true, // �Ƿ���Զ�̬����ÿҳ��ʾ�Ľ����
            dataField: 'collection',// ���ص������б�ʾ�б��������
            totalField: 'totalSize',// ���ص������б�ʾ�б�����������
            // ------ ��ҳ�����Ϣ����-------

            title: '', // ������
            isTitle: true, // �Ƿ�������⹤����
            showTitle: true,// �Ƿ���ʾ�������ϵı���
            procmsg: '���ڴ������ݣ����Ժ� ...', // ���ڴ������ʾ��Ϣ
            query: '', // ������ѯ������
            qtype: '', // ������ѯ�����
            qop: "Eq", // �����Ĳ�����
            nomsg: 'û�з��������ļ�¼����', // �޽������ʾ��Ϣ
            minColToggle: 1, // ��С�ܱ����ص�����
            showToggleBtn: true, // �Ƿ�������ʾ������
            colresize: true,// ���Ƿ�����
            hideOnSubmit: false, // ��ʾ�ڸ�
            collapsible: true, // True��ʾΪ����ǿ������ģ����Զ���Ⱦһ��չ��/�������ֻ���ť�����Ͻ�
            autoload: true, // �Զ�����
            noLoadMsg: '���б�û��Ԥ������Ϣ', //������Ԥ����ʱ��ʾ������
            blockOpacity: 0.5, // ͸��������
            noCheckIdValue: 'nocheck',// ����ʾcheckbox idֵ
            /**
             * ע�����¼�(Ϊ�˷���鿴��Ӧ�¼���ʵ�ʲ�������)
             */
            registerEven: [
                // ������
                'row_click',
                // ˫����
                'row_dblclick',
                // �Ҽ���
                'row_rclick',
                // �����ڸ�ѡ�򱻹�ѡ�¼�
                'row_check',
                // �ɹ����ر�����ݺ��¼�(�����Ѿ���Ⱦ��ҳ����)
                'row_success',
                // ���ر������ǰִ��
                'beforeload',
                // �ɹ����ر�����ݺ�ִ��(����δ��Ⱦ��ҳ����)
                'afterload',
                // �ɹ����ر�����ݺ�ִ��(�����Ѿ���Ⱦ��ҳ����)
                'afterloaddata'],
            // onToggleCol : false, // ������֮��ת��ʱ
            showcheckbox: true, // �Ƿ���ʾcheckbox
            rowbinddata: false,
            param: {},// Ĭ�ϱ���ύ�������˲������ᱻ���
            extParam: {},// ��չ����ύ�������˲����ᱻ���
            gridClass: "bbit-grid",// �����ʽ

            // ------ ��չ���Կ�ʼ-------
            /**
             * ����������
             */
            keyField: 'id',
            /**
             * ҵ��������ƣ�Ӣ�ģ�
             */
            objName: '',
            /**
             * ҵ��������ƣ����ģ�
             */
            boName: '',
            /**
             * ����ҵ�����model,��customer_customer_customer
             */
            model: '',
            /**
             * ���õĺ�̨������
             */
            action: 'pageJson',
            /**
             * ��������������ǿ��ת����Ⱦ�Ľڵ��ǩ����
             */
            cmpTag: "TABLE",
            /**
             * ����Զ��Ʊ���,��myCustomerGrid
             */
            customCode: '',
            /**
             * ���Ʊ���ѯ����
             */
            customSearchParam: '',
            /**
             * �Ƿ�ʱ����
             */
            imSearch: false,
            /**
             * ѡ�е�idֵ����
             */
            checkedIdArr: [],
            /**
             * Ĭ��ѡ�е�id
             */
            selectRowId: "",
            /**
             * Ĭ����ʾ��ҳ��
             */
            newp: 1,
            /**
             * �Ƿ���ʾ��ȫƥ��
             */
            isEquSearch: true,
            /**
             * ������Ĭ�Ͼ���
             */
            bodyAlign: 'left',
            /**
             * �Ƿ��Ѿ������Զ�����
             */
            isSetCustom: false,
            /**
             * �Ƿ񻺴����з�ҳ��ѡ������
             */
            isAllPageCheckedId: false

            // ------ ��չ���Խ���-------
        },
        /**
         * ��ʼ��ǰ�������ṩ���������
         */
        _beforeCreate: $.noop,
        /**
         * ��ʼ�����
         */
        _create: function() {
            var g = this, el = this.el, p = this.options;
            g._beforeCreate();
            // �����Զ�����
            //var timebgein = (new Date()).getTime();

            // ��ȡ�Զ����ѯ��¼����
            if (p.customCode) {
                $.ajax({
                    type: 'POST',
                    url: '?model=system_gridcustom_gridcustom&action=getCustomSearch',
                    data: {
                        'customCode': p.customCode
                    },
                    dataType: p.dataType,
                    async: false,
                    success: function (data) {
                        p.customSearchParam = data;
                    }
                });
                // console.log(p.customSearchParam);
            }

            g._processCustomCol();
            //var timeend = (new Date()).getTime();
            //alert( "ִ��ʱ����: " + eval(timeend - timebgein) + "���� ");
            //if ($(el).context.nodeName != "table") {
            //    $(el).wrapInner(document.createElement("table"));
            //}
            // show if hidden
            $(el).show().attr({
                cellPadding: 0,
                cellSpacing: 0,
                border: 0
            }) // remove padding and spacing
                .removeAttr('width'); // remove width properties
            g._createColModel();// ������
            g._createDiv();// ��������div����
            // ������ͷ������
            if (!g._createToolBar()) {
                if (p.isTitle) {
                    g.tDiv.className = 'tDiv';
                    g._createTitle();
                    $(g.gDiv).prepend(g.tDiv);
                }
            }
            // g._createTitle();// �������� ��������������ϲ� update by chengl
            g._addColModelEffect();// ���������������ص�Ч��
            g._createBody();// �����������
            g._createPage();// ������ҳ������

            // setup cdrops
            g.cdropleft = document.createElement('span');
            g.cdropleft.className = 'cdropleft';
            g.cdropright = document.createElement('span');
            g.cdropright.className = 'cdropright';

            g._createBlock();// �����ڵ���
            // if (p.showToggleBtn) {
            g._createColumnControl();// ������ʾ�����п���
            // }

            // add date edit layer
            $(g.iDiv).addClass('iDiv').css({
                display: 'none'
            });
            $(g.bDiv).append(g.iDiv);

            // add flexigrid events
            $(g.bDiv).hover(function() {
                $(g.nDiv).hide();
                $(g.nBtn).hide();
            }, function() {
                if (g.multisel)
                    g.multisel = false;
            });
            $(g.gDiv).hover(function() {
            }, function() {
                $(g.nDiv).hide();
                $(g.nBtn).hide();
            });

            // add document events
            $(document).mousemove(function(e) {
                g.dragMove(e);
            }).mouseup(function(e) {
                g.dragEnd();
            }).hover(function() {
            }, function() {
                g.dragEnd();
            });

            // browser adjustments
            if ($.browser.msie && $.browser.version < 7.0) {
                $('.hDiv,.bDiv,.mDiv,.pDiv,.vGrip,.tDiv, .sDiv', g.gDiv).css({
                    width: '100%'
                });
                $(g.gDiv).addClass('ie6');
                if (p.width != 'auto')
                    $(g.gDiv).addClass('ie6fullwidthbug');
            }

            // make grid functions accessible
            // t.p = p;
            // t.grid = g;

            // load data
            g.isfirst = true;// ���õ�һ�μ��ر�־
            // ���ݽ���ԭ�;�̬�������� update by chengl 2011-03-26
            if (p.data) {
                g.addData(p.data, true);
            } else {
                p.url = p.url ? p.url : "?model=" + p.model + "&action=" + p.action;

                if (p.url && p.autoload) {
                    g.reload();
                } else {
                    //������Ԥ����ʱ����ʾ��Ϣ
                    $('.pPageStat', this.pDiv).html(p.noLoadMsg);
                    $(el).html("<div style='text-align:center'>" + p.noLoadMsg + "</div>");
                }
            }
            // �󶨱���¼�
            // g.bindGridEvent();
        },
        _processCustomCol: function() {
            var g = this, el = this.el, p = this.options;
            if (p.customCode) {
                var data = $.ajax({
                    type: 'POST',
                    url: '?model=system_gridcustom_gridcustom&action=getCustomCols',
                    data: {
                        'customCode': p.customCode
                    },
                    async: false
                }).responseText;
                data = eval("(" + data + ")");
                if (data) {
                    for (var j = 0; j < p.colModel.length; j++) {
                        var col = p.colModel[j];
                        if (col) {
                            col["index"] = j + 1;
                            for (var i = 0; i < data.length; i++) {
                                var c = data[i];
                                var index = c['colIndex'];
                                if (col['name'] == c.colName) {// ��������ڶ�����
                                    if (c['colWidth']) {
                                        col['width'] = c['colWidth'];
                                    }
                                    col['hide'] = c['isShow'] == 0;
                                    col['index'] = index;
                                }
                            }
                        }
                    }
                    var sortFn = function(a, b) {
                        return parseInt(a["index"]) - parseInt(b["index"]);
                    };
                    p.colModel.sort(sortFn);
                    p.isSetCustom = true;
                }
            }
        },
        /***********************************************************************
         * bindGridEvent : function() { var el = this.el, p = this.options;
		 * el.unbind(ROW_CLICK); el.bind(ROW_CLICK, function(event, row,
		 * rowData) { if ((typeof p.callback[ROW_CLICK]) == "function")
		 * p.callback[ROW_CLICK](event, row, rowData); });
		 * el.unbind(ROW_DBLCLICK); el.bind(ROW_DBLCLICK, function(event, row,
		 * rowData) { if ((typeof p.callback[ROW_DBLCLICK]) == "function")
		 * p.callback[ROW_DBLCLICK](event, row, rowData); });
		 * el.unbind(CHECKBOX_CLICK); el.bind(CHECKBOX_CLICK, function(event,
		 * checkbox, row, rowData) { if ((typeof p.callback[CHECKBOX_CLICK]) ==
		 * "function") p.callback[CHECKBOX_CLICK](event, checkbox, row,
		 * rowData); }); el.unbind(ROW_RCLICK); el.bind(ROW_RCLICK,
		 * function(event, row, rowData) { if ((typeof p.callback[ROW_RCLICK]) ==
		 * "function") p.callback[ROW_RCLICK](event, row, rowData); });
		 * el.unbind(ON_SUCCESS); el.bind(ON_SUCCESS, function(event, rows) { if
		 * ((typeof p.callback[ON_SUCCESS]) == "function")
		 * p.callback[ON_SUCCESS](event, rows); }); el.unbind(BEFORELOAD);
		 * el.bind(BEFORELOAD, function(event) { if ((typeof
		 * p.callback[BEFORELOAD]) == "function") p.callback[BEFORELOAD](event);
		 * }); el.unbind(BEFORELOAD); el.bind(AFTERLOAD, function(event) { if
		 * ((typeof p.callback[AFTERLOAD]) == "function")
		 * p.callback[AFTERLOAD](event); }); },
         **********************************************************************/
        /**
         * ����ͷ����
         */
        _createColModel: function() {
            var g = this, el = this.el, p = this.options;
            if (g.processExtgridCol) {
                g.processExtgridCol();
            }
            // ����ͷ����
            if (p.colModel || p.complexColModel) {
                var thead = document.createElement('thead');

                var $tr = $('<tr></tr>');
                // p.showcheckbox ==true;
                // ��ѡcheckbox
                if (p.showcheckbox) {
                    var cth = $('<th/>');
                    var cthch = jQuery('<input type="checkbox"/>');
                    cthch.addClass("noborder");
                    cth.addClass("cth").attr({
                        'axis': "col-1",
                        width: "15",
                        rowspan: 2,
                        "isch": true
                    }).append(cthch);
                    $tr.append(cth);
                }
                if (p.isShowNum) {
                    var cth = $('<th>&nbsp;</th>').addClass("cth").attr({
                        width: 16,
                        rowspan: 2
                    });
                    $tr.append(cth);
                }
                g.processDatadict();// �����ֵ䴦�� add by chengl
                // if (p.complexColModel) {
                // p.colModel = [];
                // for (var i = 0, l = p.complexColModel.length; i < l; i++) {
                // var cm = p.complexColModel[i];
                // if ($.isArray(cm)) {
                // for (var j = 0, ll = cm.length; j < ll; j++) {
                // if (!cm[j].isComplexCol) {
                // p.colModel.push(cm[j]);
                // }
                // }
                // }
                // }
                // p.colModel = p.colModel.reverse();
                // }
                p.realColModel = [];
                if (p.complexColModel) {
                    p.realColModel = p.complexColModel;
                    p.showToggleBtn = false;// �Ƿ�������ʾ������
                    p.colresize = false;

                } else {
                    p.realColModel = [p.colModel];
                }
                for (var j = 0, ll = p.realColModel.length; j < ll; j++) {
                    var colmodel = p.realColModel[j];
                    if (j > 0) {
                        $tr = $('<tr></tr>');
                    }
                    for (var i = 0, l = colmodel.length; i < l; i++) {
                        var cm = colmodel[i];
                        if (cm && cm.show !== false) {
                            var $th = $("<th></th>");
                            if (cm.rowspan) {
                                $th.attr("rowspan", cm.rowspan);
                            }
                            if (cm.colspan) {
                                $th.attr("colspan", cm.colspan);
                            }
                            $th.html(cm.display);
                            if (cm.thstyle) {
                                $th.attr("style", cm.thstyle);
                            }
                            if (cm.thclass) {
                                $th.addClass(cm.thclass);
                            }

                            // TODO:start(add by zengzx)���һ������ֵ��������name
                            // 2011��8��15�� 10:25:02
                            if (cm.name && !cm.complexCol) {
                                $th.attr('colId', cm.name);
                            } else {
                                $th.data('complexCol', cm.complexCol);

                            }
                            // end

                            if (cm.name && cm.sortable && !cm.complexCol) { // modify
                                // by
                                // chengl
                                if (cm.sortname) {
                                    $th.attr('abbr', cm.sortname);
                                } else {
                                    $th.attr('abbr', cm.name);
                                }
                            }
                            if (!cm.complexCol) {
                                cm.index = i;// ��¼���
                                $th.data('cm', cm);// ��¼ÿһ�е�colModel
                                // th.idx = i;
                                $th.attr('axis', 'col' + i);
                                $th.attr('index', i);
                                cm.isToggle = (cm.isToggle === false
                                    ? false
                                    : true);
                                $th.attr('isToggle', cm.isToggle);// �Ƿ�����ʾ���ع���
                                if (cm.align)
                                    $th.data("align", cm.align);
                                $th.attr('align', cm.align ? cm.align : 'center');
                                $th.attr('width', cm.width ? cm.width : 100);
                                if (cm.hide) {
                                    $th.attr("hide", true);
                                    $th.hide();
                                }
                                if (cm.toggle != undefined) {
                                    $th.attr("toggle", cm.toggle);
                                }
                                if (cm.headProcess) {// ��ͷ�¼�
                                    $th.click((function(cm) {
                                        return function() {
                                            cm.headProcess(this, el.attr('id'));
                                        }
                                    })(cm));
                                }
                                if (cm.process) {// �������¼�
                                    $th.data("process", cm.process);
                                }
                                if (cm.href) {// ����
                                    $th.attr("href", cm.href);
                                    $th.attr("hrefCol", cm.hrefCol);
                                }
                            }
                            $tr.append($th);
                        }
                    }
                    $(thead).append($tr);
                }
                $(el).prepend(thead);
            } // �ж������
        },
        /**
         * �����������ֵ䣬�õ����������������ֵ�����,��ɱ����ַ����ύ����̨��ȡ�����ֵ�������
         */
        processDatadict: function() {
            var g = this, el = this.el, p = this.options, cm = p.colModel;
            var datadict = {};
            for (var v in cm) {
                if (cm[v].datacode) {
                    datadict[cm[v].name] = cm[v];
                }
            }
            var codes = [];
            for (var v in datadict) {
                codes.push(datadict[v].datacode);
            }
            if (codes.length > 0) {
                var datadictData = $.ajax({
                    type: 'POST',
                    url: "?model=system_datadict_datadict&action=getDataJsonByCodes",
                    data: {
                        codes: codes.toString()
                    },
                    async: false
                }).responseText;
                datadictData = eval("(" + datadictData + ")");
                for (var v in datadict) {
                    var code = datadict[v].datacode;
                    datadict[v].datadictData = datadictData[code];
                    if (datadictData[code]) {
                        var fn = datadict[v].process;
                        // ����ʹ�ñհ���Ŀ����Ϊ���ڷ��ص�function��ʹ�ñհ��е�������
                        datadict[v].process = (function(fn) {
                            var data = datadictData[code];
                            return function(v, row, g) {
                                var r = $.woo.getDataName(v, data);
                                if ($.isFunction(fn)) {
                                    r = fn(r, row, g);
                                }
                                return r;
                            }
                        })(fn);
                    }
                }
            }
        },
        /**
         * ��������div
         */
        _createDiv: function() {
            var g = this, el = this.el, p = this.options;
            // init divs
            // create global container ȫ������
            g.gDiv = document.createElement('div');
            // create title container��������
            g.mDiv = document.createElement('div');
            // create header container���������
            g.hDiv = document.createElement('div');
            // create body container body������
            g.bDiv = document.createElement('div');

            // create grip �����������
            g.vDiv = document.createElement('div');
            // create horizontal resizer
            g.rDiv = document.createElement('div');
            // ����������
            // create column drag ��ק������
            g.cDrag = document.createElement('div');
            // creat blocker ��������
            g.block = document.createElement('div');
            // create column show/hide popup
            g.nDiv = document.createElement('div');
            // ѡ���в˵�
            // create column show/hide
            g.nBtn = document.createElement('div');
            // button ѡ���в˵���ť
            // create editable layer
            g.iDiv = document.createElement('div');
            // create toolbar //������
            g.tDiv = document.createElement('div');
            // ������������
            g.sDiv = document.createElement('div');
            if (p.usepager) {
                // create pager container ������ҳ����
                g.pDiv = document.createElement('div');
            }
            g.hTable = document.createElement('table');
            // set gDiv
            g.gDiv.id = p.gridClass;
            g.gDiv.className = p.gridClass;
            if (p.width != 'auto')
                g.gDiv.style.width = p.width + 'px';

            // add conditional classes
            if ($.browser.msie)
                $(g.gDiv).addClass('ie');

            if (p.novstripe)
                $(g.gDiv).addClass('novstripe');

            $(el).before(g.gDiv);
            $(g.gDiv).append(el);
        },
        /**
         * ��̬��Ӱ�ť
         */
        _getButtons: function() {
        },
        /**
         * ����������
         */
        _createToolBar: function() {
            return false;
        },
        /**
         * ���ͷ���е���ʽ�����������ص�Ч��
         */
        _addColModelEffect: function() {
            var g = this, el = this.el, p = this.options;
            // set hDiv
            g.hDiv.className = 'hDiv';
            if (p.complexColModel) {
                g.hDiv.className = 'hDivComplex';
            }
            $(el).before(g.hDiv);
            // set hTable
            g.hTable.id = el.attr('id') + '_hTable';
            g.hTable.cellPadding = 0;
            g.hTable.cellSpacing = 0;
            $(g.hDiv).append('<div class="hDivBox" id="' + el.attr('id')
            + '_hDivBox"></div>');
            $('div', g.hDiv).append(g.hTable);
            var thead = $("thead:first", el).get(0);
            if (thead)
                $(g.hTable).append(thead);
            thead = null;

            if (!p.colmodel)
                var ci = 0;

            // setup thead
            $('thead tr th', g.hDiv).each(function() {
                if ($(this).attr("colspan") > 1) {
                    // $(this).empty().append(this.innerHTML);
                } else {
                    var thdiv = document.createElement('div');
                    if ($(this).attr('abbr')) {
                        // ���õ����ͷ�¼�
                        $(this).click(function(e) {
                            if (!$(this).hasClass('thOver'))
                                return false;
                            var obj = (e.target || e.srcElement);
                            if (obj.href || obj.type)
                                return true;
                            g.changeSort(this);
                        });

                        if ($(this).attr('abbr') == p.sortname) {
                            this.className = 'sorted';
                            thdiv.className = 's' + p.sortorder;
                        }
                    }

                    if (this.hide)
                        $(this).hide();

                    if (!p.colmodel && !$(this).attr("isch")) {
                        $(this).attr('axis', 'col' + ci++);
                    }
                    $(thdiv).attr("width", this.width);
                    $(thdiv).css({
                        textAlign: this.align,
                        width: this.width + 'px'
                    });
                    thdiv.innerHTML = this.innerHTML;

                    $(this).empty().append($(thdiv)).removeAttr('width');
                }
                if (p.colresize && !$(this).attr("isch")
                    && $(this).attr("isToggle") == 'true') {
                    // ���ƶ�����갴���¼�
                    $(this).mousedown(function(e) {
                        g.dragStart('colMove', e, this);
                    }).hover(function() {

                        if (!g.colresize && !$(this).hasClass('thMove')
                            && !g.colCopy)
                            $(this).addClass('thOver');

                        if ($(this).attr('abbr') != p.sortname && !g.colCopy
                            && !g.colresize && $(this).attr('abbr'))
                            $('div', this).addClass('s' + p.sortorder);
                        else if ($(this).attr('abbr') == p.sortname
                            && !g.colCopy && !g.colresize
                            && $(this).attr('abbr')) {
                            var no = '';
                            if (p.sortorder == 'asc')
                                no = 'desc';
                            else
                                no = 'asc';
                            $('div', this).removeClass('s' + p.sortorder)
                                .addClass('s' + no);
                        }

                        if (g.colCopy) {
                            var n = $('th', g.hDiv).index(this);
                            if (n == g.dcoln)
                                return false;

                            if (n < g.dcoln)
                                $(this).append(g.cdropleft);
                            else
                                $(this).append(g.cdropright);

                            g.dcolt = n;

                        } else if (!g.colresize) {
                            var thsa = g.getTheadVisible();
                            var nv = -1;
                            for (var i = 0, j = 0, l = thsa.length; i < l; i++) {
                                if (thsa[i] == this) {
                                    nv = j;
                                    break;
                                }
                                j++;
                            }
                            // var nv = $('th:visible',
                            // g.hDiv).index(this);
                            var onl = parseInt($('div:eq(' + nv + ')', g.cDrag)
                                .css('left'));
                            var nw = parseInt($(g.nBtn).width())
                                + parseInt($(g.nBtn).css('borderLeftWidth'));
                            nl = onl - nw + Math.floor(p.cgwidth / 2);

                            $(g.nDiv).hide();
                            $(g.nBtn).hide();

                            $(g.nBtn).css({
                                'left': nl,
                                top: g.hDiv.offsetTop
                            }).show();

                            var ndw = parseInt($(g.nDiv).width());
                            var nHeight = $(g.nDiv).height();
                            var bHeight = $(g.bDiv).height();
                            if (nHeight > bHeight) {
                                nHeight = bHeight - 20;
                            }
                            $(g.nDiv).css({
                                top: g.bDiv.offsetTop,
                                height: nHeight
                            });

                            if ((nl + ndw) > $(g.gDiv).width())
                                $(g.nDiv).css('left', onl - ndw + 1);
                            else
                                $(g.nDiv).css('left', nl);

                            if ($(this).hasClass('sorted'))
                                $(g.nBtn).addClass('srtd');
                            else
                                $(g.nBtn).removeClass('srtd');

                        }

                    }, function() {
                        $(this).removeClass('thOver');
                        if ($(this).attr('abbr') != p.sortname)
                            $('div', this).removeClass('s' + p.sortorder);
                        else if ($(this).attr('abbr') == p.sortname) {
                            var no = '';
                            if (p.sortorder == 'asc')
                                no = 'desc';
                            else
                                no = 'asc';

                            $('div', this).addClass('s' + p.sortorder)
                                .removeClass('s' + no);
                        }
                        if (g.colCopy) {
                            $(g.cdropleft).remove();
                            $(g.cdropright).remove();
                            g.dcolt = null;
                        }
                    }); // wrap content
                }
            });
        },
        /**
         * �����������
         */
        _createBody: function() {
            var g = this, el = this.el, p = this.options;
            // set bDiv
            g.bDiv.id = el.attr('id') + '_bDiv';
            g.bDiv.className = 'bDiv';

            // add by chengl 2011-03-21 ����ie6,���������table��������Χ��һ��table
            // if ($(el).context.nodeName != "table") {
            // $(el).wrapInner(document.createElement("table"));
            // }
            $(el).before(g.bDiv);
            // update by chengl ����auto��ȫ����ʾ���߶�
            // document.documentElement.clientHeight-110
            var h = p.height;
            if (p.height == 'auto') {
                h = 'auto';
            } else if (p.height == 'fit') {
                // h = document.documentElement.clientHeight -
                // $(g.pDiv).height()
                // - $(g.tDiv).height() - 70;
                h = $(document).height() - $(g.pDiv).height()
                - $(g.tDiv).height() - $(g.hDiv).height() - 50;
            }
            $(g.bDiv).css({
                height: h
            }).scroll(function(e) {
                g.scroll();
            }).append(el);

            if (p.height == 'auto') {
                $('table', g.bDiv).addClass('autoht');
            }

            // add td properties modify by chengl ��ʱ��֪�����ã�ע��
            // if (p.url == false || p.url == "") {
            // g.addCellProp();
            // // add row properties
            // g.addRowProp();
            // }

            // set cDrag

            var cdcol = $('thead tr:first th:first', g.hDiv).get(0);

            if (cdcol != null) {
                g.cDrag.className = 'cDrag';
                g.cdpad = 0;

                g.cdpad += (isNaN(parseInt($('div', cdcol)
                    .css('borderLeftWidth')))
                    ? 0
                    : parseInt($('div', cdcol).css('borderLeftWidth')));
                g.cdpad += (isNaN(parseInt($('div', cdcol)
                    .css('borderRightWidth'))) ? 0 : parseInt($('div',
                    cdcol).css('borderRightWidth')));
                g.cdpad += (isNaN(parseInt($('div', cdcol).css('paddingLeft')))
                    ? 0
                    : parseInt($('div', cdcol).css('paddingLeft')));
                g.cdpad += (isNaN(parseInt($('div', cdcol).css('paddingRight')))
                    ? 0
                    : parseInt($('div', cdcol).css('paddingRight')));
                g.cdpad += (isNaN(parseInt($(cdcol).css('borderLeftWidth')))
                    ? 0
                    : parseInt($(cdcol).css('borderLeftWidth')));
                g.cdpad += (isNaN(parseInt($(cdcol).css('borderRightWidth')))
                    ? 0
                    : parseInt($(cdcol).css('borderRightWidth')));
                g.cdpad += (isNaN(parseInt($(cdcol).css('paddingLeft')))
                    ? 0
                    : parseInt($(cdcol).css('paddingLeft')));
                g.cdpad += (isNaN(parseInt($(cdcol).css('paddingRight')))
                    ? 0
                    : parseInt($(cdcol).css('paddingRight')));

                $(g.bDiv).before(g.cDrag);

                var cdheight = $(g.bDiv).height();
                var hdheight = $(g.hDiv).height();

                $(g.cDrag).css({
                    top: -hdheight + 'px'
                });
                // ������
                if (p.colresize) {
                    $('thead tr:first th', g.hDiv).each(function(index) {
                        var cgDiv = document.createElement('div');
                        $(cgDiv).attr('index', index);
                        $(g.cDrag).append(cgDiv);
                        if (!p.cgwidth)
                            p.cgwidth = $(cgDiv).width();
                        $(cgDiv).css({
                            height: cdheight + hdheight
                        }).mousedown(function(e) {
                            g.dragStart('colresize', e, this);
                        });
                        if ($.browser.msie && $.browser.version < 7.0) {
                            g.fixHeight($(g.gDiv).height());
                            $(cgDiv).hover(function() {
                                g.fixHeight();
                                $(this).addClass('dragging');
                            }, function() {
                                if (!g.colresize)
                                    $(this)
                                        .removeClass('dragging');
                            });
                        }
                    });
                }
                // g.rePosDrag();
            }

            // add strip
            if (p.striped)
                $('tbody tr:odd', g.bDiv).addClass('erow');

            if (p.resizable && p.height != 'auto') {
                g.vDiv.className = 'vGrip';
                $(g.vDiv).mousedown(function(e) {
                    g.dragStart('vresize', e);
                }).html('<span></span>');
                $(g.bDiv).after(g.vDiv);
            }

            if (p.resizable && p.width != 'auto' && !p.nohresize) {
                g.rDiv.className = 'hGrip';
                $(g.rDiv).mousedown(function(e) {
                    g.dragStart('vresize', e, true);
                }).html('<span></span>').css('height',
                    $(g.gDiv).height());
                if ($.browser.msie && $.browser.version < 7.0) {
                    $(g.rDiv).hover(function() {
                        $(this).addClass('hgOver');
                    }, function() {
                        $(this).removeClass('hgOver');
                    });
                }
                $(g.gDiv).append(g.rDiv);
            }
        },
        /**
         * ������ҳ
         */
        _createPage: function() {
            var g = this, el = this.el, p = this.options;
            // add pager
            if (p.usepager) {
                g.pDiv.className = 'pDiv';
                g.pDiv.innerHTML = '<div class="pDiv2"></div>';
                $(g.bDiv).after(g.pDiv);
                var html = '<div class="pGroup"><div class="pFirst pButton" title="ת����һҳ"><span></span></div><div class="pPrev pButton" title="ת����һҳ"><span></span></div> </div><div class="btnseparator"></div> <div class="pGroup"><span class="pcontrol">��ǰ <input class="txt-auto" type="text" size="1" value="1" /> ,��ҳ�� <span> 1 </span></span></div><div class="btnseparator"></div><div class="pGroup"> <div class="pNext pButton" title="ת����һҳ"><span></span></div><div class="pLast pButton" title="ת�����һҳ"><span></span></div></div><div class="btnseparator"></div><div class="pGroup"> <div class="pReload pButton" title="ˢ��"><span></span></div> </div> <div class="btnseparator"></div><div class="pGroup"><span class="pPageStat"></span></div>';
                $('div', g.pDiv).html(html);

                $('.pReload', g.pDiv).click(function() {
                    g.reload();
                });
                $('.pFirst', g.pDiv).click(function() {
                    g.changePage('first');
                });
                $('.pPrev', g.pDiv).click(function() {
                    g.changePage('prev');
                });
                $('.pNext', g.pDiv).click(function() {
                    g.changePage('next');
                });
                $('.pLast', g.pDiv).click(function() {
                    g.changePage('last');
                });
                $('.pcontrol input', g.pDiv).keydown(function(e) {
                    if (e.keyCode == 13)
                        g.changePage('input');
                });
                if ($.browser.msie && $.browser.version < 7)
                    $('.pButton', g.pDiv).hover(function() {
                        $(this).addClass('pBtnOver');
                    }, function() {
                        $(this).removeClass('pBtnOver');
                    });

                if (p.useRp) {
                    var opt = "";
                    for (var nx = 0; nx < p.pageSizeOptions.length; nx++) {
                        if (p.pageSize == p.pageSizeOptions[nx])
                            sel = 'selected="selected"';
                        else
                            sel = '';
                        opt += "<option value='" + p.pageSizeOptions[nx] + "' "
                        + sel + " >" + p.pageSizeOptions[nx]
                        + "&nbsp;&nbsp;</option>";
                    }
                    $('.pDiv2', g.pDiv)
                        .prepend("<div class='pGroup'>ÿҳ <select class='selectshort' name='pageSize'>"
                        + opt
                        + "</select>��</div> <div class='btnseparator'></div>");
                    $('select', g.pDiv).change(function() {
                        if (p.onRpChange)
                            p.onRpChange(+this.value);
                        else {
                            p.newp = 1;
                            p.pageSize = +this.value;
                            g.reload();
                        }
                    });
                }
            }
            $(g.pDiv, g.sDiv).append("<div style='clear:both'></div>");
        },
        /**
         * ��ʼ���Զ�����
         */
        _initAndFormat: function() {
            var p = this.options;

            if (p.isSetCustom == false) {
                // ������ͷ��Ϣ
                var customJson = p.colModel; // ��ͷ
                var lockJson = p.lockCol ? p.lockCol : []; // ������ͷ
                var colNameArr = [];
                var colWidthArr = [];
                var colShowArr = [];
                for (var i = 0; i < customJson.length; i++) {
                    if ($.inArray(customJson[i].name, lockJson) != -1) continue; // ����ֶ���������ͷ,�����Զ�������
                    if (customJson[i].name == 'test') continue; // ����ֶ���������ͷ,�����Զ�������
                    colNameArr.push(customJson[i].name);
                    customJson[i].width ? colWidthArr.push(customJson[i].width) : colWidthArr.push(100);
                    customJson[i].hide == true ? colShowArr.push(0) : colShowArr.push(1);
                }
                $.ajax({
                    url: "?model=system_gridcustom_gridcustom&action=initAndFormat",
                    type: "POST",
                    data: {
                        colName: colNameArr.toString(),
                        colWidth: colWidthArr.toString(),
                        colShow: colShowArr.toString(),
                        customCode: p.customCode
                    },
                    async: false
                });
                p.isSetCustom = true;
            }
        },
        /**
         * ����һ��ͨ�ñ��ť
         */
        createButton: function(button) {
            var span = "<span  class='" + button.icon + "'>" + button.text
                + "</span>";
            if (!button.text || button.text == '') {
                button.text = "&nbsp;";
                span = "<span style='padding-left:14px' class='" + button.icon
                + "'>" + button.text + "</span>";
            }

            var b = $("<div class='fbutton' title='"
            + (button.title ? button.title : "") + "'><div>" + span
            + "</div></div>");
            if (button.name) {
                var id = $(this.el).attr("id") + "-" + button.name;
                b.attr("id", id);
            }
            if (button.hide == true) {
                b.hide();
            }
            if (button.action) {
                b.click(button.action);
            }
            return b;
        },
        /**
         * ����������
         */
        _createTitle: function() {
            var g = this, el = this.el, p = this.options;
            // add title
            if (p.isTitle) {
                p.title = p.title ? p.title : p.boName + "��Ϣ";
                $(g.tDiv).height('auto');// �Զ��߶�
                // �������
                if (p.showTitle) {
                    var tDiv1Obj = $("<div class='tDiv1'>" + p.title + "</div>");
                    // ������µı���Զ����¼� kzw 2014-06-26
                    if (p.customCode != '') { // TODO
                        // ������ͷ��Ϣ
                        var customJson = p.colModel; // ��ͷ
                        var lockJson = p.lockCol ? p.lockCol : []; // ������ͷ
                        var colTextArr = [];
                        var colNameArr = [];
                        var colWidthArr = [];
                        var colShowArr = [];
                        for (var i = 0; i < customJson.length; i++) {
                            if ($.inArray(customJson[i].name, lockJson) != -1) continue; // ����ֶ���������ͷ,�����Զ�������
                            if (customJson[i].name == 'test') continue; // ����ֶ���������ͷ,�����Զ�������
                            colTextArr.push(customJson[i].display);
                            colNameArr.push(customJson[i].name);
                            customJson[i].width ? colWidthArr.push(customJson[i].width) : colWidthArr.push(100);
                            customJson[i].hide == true ? colShowArr.push(0) : colShowArr.push(1);
                        }

                        tDiv1Obj.bind("click", function() {
                            //��������Զ�������¼�
                            showOpenWin("?model=system_gridcustom_gridcustom&action=customList&customCode=" + p.customCode
                                + "&colText=" + colTextArr.toString()
                                + "&colName=" + colNameArr.toString()
                                + "&colWidth=" + colWidthArr.toString()
                                + "&colShow=" + colShowArr.toString(),
                                1, 600, 800, 'customGrid');
                        }).attr("title", '��������Զ��崰��ҳ��');
                    }
                    $(g.tDiv).append(tDiv1Obj);
                }
                if (p.searchitems) {
                    var sitems = [];
                    var nameArr = [];
                    for (var i = 0; i < p.searchitems.length; i++) {
                        nameArr.push(p.searchitems[i].name);
                    }
                    if (p.setSearchAll) {
                        sitems[0] = {
                            display: '����',
                            name: nameArr.toString()
                        };
                    }
                    sitems = sitems.concat(p.searchitems);

                    var sopt = "";
                    var op = "Eq";
                    var tmp, selected, selectedVal = '';
                    for (var s = 0; s < sitems.length; s++) {
                        // �����Ժ������չ����ѡ������ģʽ��like eq > <��
                        if (sitems[s].operater == "Like") {
                            op = "Like";
                        } else {
                            op = "Eq";
                        }

                        // by kuangzw 2017-12-25
                        if (p.customCode && p.customSearchParam.leftSearch) {
                            tmp = sitems[s].name;
                            if (p.customSearchParam.leftSearch[tmp]) {
                                selected = "selected";
                                selectedVal = p.customSearchParam.leftSearch[tmp];
                                p.qtype = tmp;
                                p.query = selectedVal;
                            } else {
                                selected = "";
                            }
                            
                            sopt += "<option value='" + sitems[s].name + "$" + op
                            + "$" + s + "' " + selected + ">" + sitems[s].display
                            + "&nbsp;&nbsp;</option>";
                        } else {
                            sopt += "<option value='" + sitems[s].name + "$" + op
                            + "$" + s + "'>" + sitems[s].display
                            + "&nbsp;&nbsp;</option>";
                        }
                    }
                    var $div = $("<div class='sDiv2'></div>");

                    g.$inputText = $("<input type='text' size='20' name='q' class='txt' value='" + selectedVal + "' />");
                    if (p.imSearch) {// ��ʱ��������
                        g.eventFlag = 0;
                        g.$inputText.keydown(function(event) {
                            clearTimeout(g.searchReturnFn);
                            g.eventFlag = 0;
                        });
                        var t = function() {
                            if (g.eventFlag) {
                                g.doSearch();
                            }
                        };
                        g.$inputText.keyup(function(event) {
                            if (event.keyCode > 47
                                || event.keyCode == 8) {
                                g.searchReturnFn = setTimeout(t, 1000);
                                g.eventFlag = 1;
                            }
                        });
                    }
                    g.$searchBn = $("<span title='�����������'  class='search-trigger-tool'>&nbsp;</span>");
                    g.$clearBn = $("<span title='����������' class='clear-trigger-tool' >&nbsp;</span>");
                    g.$searchBn.click(function() {
                        g.doSearch();
                    });
                    g.$clearBn.click(function() {
                        $('input[name=q]', g.tDiv).val('');
                        p.query = '';
                        g.doSearch();
                    });

                    $div.append(" <select class='selectauto' name='qtype'>"
                    + sopt + "</select> ");
                    $div.append(g.$inputText);
                    g.$inputText.after(g.$searchBn);
                    g.$inputText.after(g.$clearBn);
                    if (p.isEquSearch) {
                        $div.append("&nbsp;");
                        g.$isEqualSearch = $("<input type='checkbox' />");
                        $div.append(g.$isEqualSearch).append("��ȫƥ��");
                    }
                    $(g.tDiv).append($div);
                    if (p.plusCmpFn) {
                        $(g.tDiv).append(p.plusCmpFn(g));
                    }

                    $('input[name=q],select[name=qtype]', g.tDiv).keydown(
                        function(e) {
                            if (e.keyCode == 13)
                                g.doSearch();
                        });

                }
            }
        },
        /**
         * ����Block
         */
        _createBlock: function() {
            var g = this, el = this.el, p = this.options;
            // add block
            g.block.className = 'gBlock';
            var blockloading = $("<div/>");
            blockloading.addClass("loading");
            $(g.block).append(blockloading);
            g.reloadBlock();
            $(g.block).fadeTo(0, p.blockOpacity);
        },
        /**
         * ����ˢ��Block
         */
        reloadBlock: function() {
            var g = this;
            var gh = $(g.bDiv).height();
            var gtop = g.bDiv.offsetTop;
            $(g.block).css({
                width: g.bDiv.style.width,
                height: gh,
                position: 'relative',
                marginBottom: (gh * -1),
                zIndex: 1,
                top: gtop,
                left: '0px'
            });
        },
        /**
         * �п���
         */
        _createColumnControl: function() {
            var g = this, el = this.el, p = this.options;
            var gh = $(g.bDiv).height();
            var gtop = g.bDiv.offsetTop;
            // add column control
            if ($('th', g.hDiv).length) {
                g.nDiv.className = 'nDiv';
                g.nDiv.innerHTML = "<table cellpadding='0' cellspacing='0'><tbody></tbody></table>";
                $(g.nDiv).css({
                    marginBottom: (gh * -1),
                    display: 'none',
                    top: gtop
                }).noSelect();

                var cn = 0;

                $('th div', g.hDiv).each(function() {
                    var kcol = $("th[axis='col" + cn + "']", g.hDiv)[0];

                    if (kcol == null)
                        return;
                    var chkall = $("input[type='checkbox']", this);
                    if (chkall.length > 0) {
                        chkall.click(function() {
                            g.checkAllOrNot.apply(g, [this]);
                        });
                        return;
                    }
                    if (kcol.toggle == false || this.innerHTML == "") {
                        cn++;
                        return;
                    }
                    var dn = this.innerHTML;
                    // �ж��Ƿ������ add by chengl
                    if (p.isShowNum == true && cn == 0) {
                        dn = '���';
                    }
                    var chk = 'checked="checked"';
                    if (kcol.style.display == 'none')
                        chk = '';

                    $('tbody', g.nDiv)
                        .append('<tr><td class="ndcol1"><input type="checkbox" '
                        + chk
                        + ' class="togCol noborder" value="'
                        + cn
                        + '" /></td><td class="ndcol2">'
                        + dn
                        + '</td></tr>');
                    cn++;
                });

                if ($.browser.msie && $.browser.version < 7.0)
                    $('tr', g.nDiv).hover(function() {
                        $(this).addClass('ndcolover');
                    }, function() {
                        $(this).removeClass('ndcolover');
                    });

                $('td.ndcol2', g.nDiv).click(function() {
                    if ($('input:checked', g.nDiv).length <= p.minColToggle
                        && $(this).prev().find('input')[0].checked) {
                        return false;
                    }
                    return g.toggleCol($(this).prev().find('input').val());
                });

                $('input.togCol', g.nDiv).click(function() {
                    if ($('input:checked', g.nDiv).length < p.minColToggle
                        && this.checked == false) {
                        return false;
                    }
                    $(this).parent().next().trigger('click');
                    // return false;
                });

                $(g.gDiv).prepend(g.nDiv);

                $(g.nBtn).addClass('nBtn').html('<div></div>')
                    // .attr('title', 'Hide/Show Columns')
                    .click(function() {
                        $(g.nDiv).toggle();
                        return true;
                    });

                $(g.gDiv).prepend(g.nBtn);
            }
        },

        hset: {},
        rePosDrag: function() {
            var g = this, el = this.el, p = this.options;
            var cdleft = 0 - this.hDiv.scrollLeft;
            if (this.hDiv.scrollLeft > 0)
                cdleft -= Math.floor(p.cgwidth / 2);

            $(g.cDrag).css({
                top: g.hDiv.offsetTop + 1
            });
            var cdpad = this.cdpad;

            // $('div', g.cDrag).hide();
            // update by xuanye ,����jQuery :visible ��Ч��bug
            var i = 0;

            $('thead tr:first th:visible', this.hDiv).each(function() {
                if ($(this).css("display") == "none") {
                    return;
                }

                var n = i;
                // var n = $('thead tr:first th:visible',
                // g.hDiv).index(this);
                var cdpos = parseInt($('div', this).width());
                var ppos = cdpos;
                if (cdleft == 0)
                    cdleft -= Math.floor(p.cgwidth / 2);

                cdpos = cdpos + cdleft + cdpad;
                var dcdpos = cdpos;
                // ����������ɵ���ƫ��
                if (p.lockCol) {
                    dcdpos += g.lockHDiv.width();
                }
                $('div:eq(' + n + ')', g.cDrag).css({
                    'left': dcdpos + 'px'
                });
                // .show()ͨ�����ܲ��Թ��߼��Ӱ�����ܣ���ʱȥ��

                cdleft = cdpos;
                i++;
            });
        },
        fixHeight: function(newH) {
            var g = this, el = this.el, p = this.options;
            newH = false;
            if (!newH)
                newH = $(g.bDiv).height();
            var hdHeight = $(this.hDiv).height();
            $('div', this.cDrag).each(function() {
                $(this).height(newH + hdHeight);
            });

            var nd = parseInt($(g.nDiv).height());

            if (nd > newH)
                $(g.nDiv).height(newH).width(200);
            else
                $(g.nDiv).height('auto').width('auto');

            $(g.block).css({
                height: newH,
                marginBottom: (newH * -1)
            });

            var hrH = g.bDiv.offsetTop + newH;
            if (p.height != 'auto' && p.resizable)
                hrH = g.vDiv.offsetTop;
            $(g.rDiv).css({
                height: hrH
            });

            // ���������и߶Ȳ�һ������
            if (p.lockCol) {
                var $bodyTr = $(g.bDiv).find('tr');
                $bodyTr.each(function(i) {
                    var lockTr = $(this).data("lockTr");
                    var lh = lockTr.height();
                    var rh = $(this).height();
                    lockTr.height(rh);
                    var lh2 = lockTr.height();
                    if (lh2 > rh) {
                        $(this).height(lh2);
                    }
                });
            }
        },
        /**
         * dragtype
         *
         * @1.colresize :������
         * @2.vresize���������
         * @3.colMove �����ƶ�
         */
        dragStart: function(dragtype, e, obj) { // default drag function
            // start
            var g = this, el = this.el, p = this.options;
            if (dragtype == 'colresize') // column resize
            {
                $(g.nDiv).hide();
                $(g.nBtn).hide();
                var n = $('div', this.cDrag).index(obj);
                // var ow = $('th:visible div:eq(' + n + ')',
                // this.hDiv).width();

                // var ow = $('th:visible:eq(' + n + ') div',
                // this.hDiv).width();
                var col = g.getTheadVisible().eq(n);
                var ow = col.children('div').width();
                $(obj).addClass('dragging').siblings().hide();
                $(obj).prev().addClass('dragging').show();

                this.colresize = {
                    startX: e.pageX,
                    ol: parseInt(obj.style.left),
                    ow: ow,
                    n: n
                };

                $('body').css('cursor', 'col-resize');

            } else if (dragtype == 'vresize') // table resize
            {
                var hgo = false;
                $('body').css('cursor', 'row-resize');
                if (obj) {
                    hgo = true;
                    $('body').css('cursor', 'col-resize');
                }
                this.vresize = {
                    h: p.height,
                    sy: e.pageY,
                    w: p.width,
                    sx: e.pageX,
                    hgo: hgo
                };

            }

            else if (dragtype == 'colMove') // column header drag
            {
                $(g.nDiv).hide();
                $(g.nBtn).hide();
                this.hset = $(this.hDiv).offset();
                this.hset.right = this.hset.left
                + $('table', this.hDiv).width();
                this.hset.bottom = this.hset.top
                + $('table', this.hDiv).height();
                this.dcol = obj;
                this.dcoln = $('th', this.hDiv).index(obj);

                this.colCopy = document.createElement("div");
                this.colCopy.className = "colCopy";
                this.colCopy.innerHTML = obj.innerHTML;
                if ($.browser.msie) {
                    this.colCopy.className = "colCopy ie";
                }

                $(this.colCopy).css({
                    position: 'absolute',
                    "float": 'left',
                    display: 'none',
                    textAlign: obj.align
                });
                $('body').append(this.colCopy);
                $(this.cDrag).hide();

            }

            $('body').noSelect();

        },
        reSize: function() {
            var g = this, el = this.el, p = this.options;
            this.gDiv.style.width = p.width;
            this.bDiv.style.height = p.height;
        },
        dragMove: function(e) {
            var g = this, el = this.el, p = this.options;
            if (this.colresize) // column resize
            {
                var n = this.colresize.n;
                var diff = e.pageX - this.colresize.startX;
                var nleft = this.colresize.ol + diff;
                var nw = this.colresize.ow + diff;
                if (nw > p.minwidth) {
                    $('div:eq(' + n + ')', this.cDrag).css('left', nleft);
                    this.colresize.nw = nw;
                }
            } else if (this.vresize) // table resize
            {
                var v = this.vresize;
                var y = e.pageY;
                var diff = y - v.sy;
                if (!p.defwidth)
                    p.defwidth = p.width;
                if (p.width != 'auto' && !p.nohresize && v.hgo) {
                    var x = e.pageX;
                    var xdiff = x - v.sx;
                    var newW = v.w + xdiff;
                    if (newW > p.defwidth) {
                        this.gDiv.style.width = newW + 'px';
                        p.width = newW;
                    }
                }
                var newH = v.h + diff;
                if ((newH > p.minheight || p.height < p.minheight) && !v.hgo) {
                    this.bDiv.style.height = newH + 'px';
                    p.height = newH;
                    this.fixHeight(newH);
                }
                v = null;
            } else if (this.colCopy) {
                $(this.dcol).addClass('thMove').removeClass('thOver');
                if (e.pageX > this.hset.right || e.pageX < this.hset.left
                    || e.pageY > this.hset.bottom
                    || e.pageY < this.hset.top) {
                    // this.dragEnd();
                    $('body').css('cursor', 'move');
                } else
                    $('body').css('cursor', 'pointer');

                $(this.colCopy).css({
                    top: e.pageY + 10,
                    left: e.pageX + 20,
                    display: 'block'
                });
            }

        },
        dragEnd: function() {
            var g = this, el = this.el, p = this.options;
            if (this.colresize) {
                var n = this.colresize.n;
                var col = g.getTheadVisible().eq(n);
                var nw = this.colresize.nw;
                g.getTheadVisible().eq(n).children('div').css('width', nw);
                $('tr', this.bDiv).each(function() {
                    g.getTheadVisible('td', this).eq(n).children('div')
                        .not(".substdiv").css('width', nw);// ���ӱ��div��Ӧ�ð���
                    // update by
                    // chengl
                    // 2012-09-19
                });
                this.hDiv.scrollLeft = this.bDiv.scrollLeft;
                $('div:eq(' + n + ')', this.cDrag).siblings().show();
                $('.dragging', this.cDrag).removeClass('dragging');
                this.rePosDrag();
                this.fixHeight();
                this.colresize = false;
                // �����п��
                if (p.customCode) {
                    // ���û�г�ʼ��,����ó�ʼ������
                    g._initAndFormat();

                    var colName = col.attr('colid');
                    if (colName && colName != "") {
                        $.ajax({
                            type: 'POST',
                            url: '?model=system_gridcustom_gridcustom&action=updateCol',
                            data: {
                                'gridcustom[colName]': colName,
                                'gridcustom[customCode]': p.customCode,
                                'gridcustom[colWidth]': nw,
                                'gridcustom[colIndex]': col.attr("index"),
                                'gridcustom[isShow]': 1
                            }
                        });
                    }
                }
            } else if (this.vresize) {
                this.vresize = false;
            } else if (this.colCopy) {// �϶���
                $(this.colCopy).remove();
                // alert(this.dcoln);
                // alert(this.dcolt);

                if (this.dcolt != null) {
                    if (p.customCode) {
                        var col1 = g.getThead().eq(this.dcoln);
                        var col1Index = parseInt(col1.attr('index'));

                        var col2 = g.getThead().eq(this.dcolt);
                        var col2Index = parseInt(col2.attr('index'));

                        if (col1.attr('colid')) {
                            // ���û�г�ʼ��,����ó�ʼ������
                            g._initAndFormat();

                            $.ajax({
                                type: 'POST',
                                url: '?model=system_gridcustom_gridcustom&action=switchCol',
                                data: {
                                    customCode: p.customCode,
                                    strartColName: col1.attr('colid'),
                                    endColName: col2.attr('colid'),
                                    startIndex: col1Index,
                                    endIndex: col2Index
                                },
                                success: function() {
                                    if (col2Index > col1Index) {
                                        g.getThead().each(function() {
                                            var i = parseInt($(this)
                                                .attr('index'));
                                            if (i > col1Index && i <= col2Index) {
                                                $(this).attr('index', i - 1);
                                            }
                                        });
                                    } else {
                                        g.getThead().each(function() {
                                            var i = parseInt($(this)
                                                .attr('index'));
                                            if (i < col1Index && i >= col2Index) {
                                                $(this).attr('index', i + 1);
                                            }
                                        });
                                    }
                                    col1.attr('index', col2Index);
                                }
                            });
                        }
                    }
                    if (this.dcoln > this.dcolt) {
                        $('th:eq(' + this.dcolt + ')', this.hDiv)
                            .before(this.dcol);
                    } else {
                        $('th:eq(' + this.dcolt + ')', this.hDiv)
                            .after(this.dcol);
                    }
                    this.switchCol(this.dcoln, this.dcolt);
                    $(this.cdropleft).remove();
                    $(this.cdropright).remove();
                    this.rePosDrag();
                }
                this.dcol = null;
                this.hset = null;
                this.dcoln = null;
                this.dcolt = null;
                this.colCopy = null;
                $('.thMove', this.hDiv).removeClass('thMove');
                $(this.cDrag).show();
            }
            $('body').css('cursor', 'default');
            $('body').noSelect(false);
        },
        /**
         * ���ػ�����ʾ��
         */
        toggleCol: function(cid, visible) {
            var g = this, el = this.el, p = this.options;
            var ncol = $("th[axis='col" + cid + "']", g.hDiv)[0];
            var n = $('thead th', g.hDiv).index(ncol);
            var cb = $('input[value=' + cid + ']', g.nDiv)[0];
            if (visible == null) {
                visible = ncol.hide;
            }
            if ($('input:checked', g.nDiv).length < p.minColToggle && !visible)
                return false;
            if (visible) {
                ncol.hide = false;
                $(ncol).show();
                cb.checked = true;
            } else {
                ncol.hide = true;
                $(ncol).hide();
                cb.checked = false;
            }

            $('tbody tr', g.bDiv).each(function() {
                if (visible)
                    $('td:eq(' + n + ')', this).show();
                else
                    $('td:eq(' + n + ')', this).hide();
            });
            this.rePosDrag();
            // if (p.onToggleCol)
            // p.onToggleCol(cid, visible);
            // ��ʾ�������Զ��ƴ���
            if (p.customCode) {
                var colName = $(ncol).attr('colid');
                if (colName && colName != "") {
                    // ���û�г�ʼ��,����ó�ʼ������
                    g._initAndFormat();

                    $.ajax({
                        type: 'POST',
                        url: '?model=system_gridcustom_gridcustom&action=updateCol',
                        data: {
                            'gridcustom[colName]': colName,
                            'gridcustom[customCode]': p.customCode,
                            'gridcustom[isShow]': visible == true ? 1 : 0,
                            'gridcustom[colIndex]': $(ncol).attr("index")
                        }
                    });
                }
            }
            return visible;
        },
        /**
         * ������
         */
        switchCol: function(cdrag, cdrop) { // switch columns
            // alert(cdrag);alert(cdrop);
            var g = this, el = this.el, p = this.options;
            $('tbody tr', el).each(function() {
                if (cdrag > cdrop)
                    $('td:eq(' + cdrop + ')', this).before($('td:eq(' + cdrag
                    + ')', this));
                else
                    $('td:eq(' + cdrop + ')', this).after($('td:eq(' + cdrag
                    + ')', this));
            });
            // switch order in nDiv
            if (cdrag > cdrop)
                $('tr:eq(' + cdrop + ')', this.nDiv).before($('tr:eq(' + cdrag
                + ')', this.nDiv));
            else
                $('tr:eq(' + cdrop + ')', this.nDiv).after($('tr:eq(' + cdrag
                + ')', this.nDiv));
            if ($.browser.msie && $.browser.version < 7.0)
                $('tr:eq(' + cdrop + ') input', this.nDiv)[0].checked = true;
            this.hDiv.scrollLeft = this.bDiv.scrollLeft;

        },
        /**
         * ����ʱ����ͷ������һ�����
         */
        scroll: function() {
            $(this.hDiv).scrollLeft($(this.bDiv).scrollLeft());
            $(this.lockBDiv).scrollTop($(this.bDiv).scrollTop());
            if ($(this.bDiv).scrollLeft() != 0) {
                $(this.hDiv).css('overflow-y', 'scroll');// ���ˮƽ�϶��и����ݴ�λ����
            }
            this.rePosDrag();// ����λ��
        },
        hideLoading: function() {
            var g = this, el = this.el, p = this.options;
            $('.pReload', this.pDiv).removeClass('loading');
            if (p.hideOnSubmit)
                $(g.block).remove();
            $('.pPageStat', this.pDiv).html(p.errormsg);
            this.loading = false;
        },
        /**
         * ��ȡ���ص�����
         */
        getData: function() {
            return this.data;
        },
        /**
         * ��ӱ������
         */
        addData: function(data) { // parse data
            var g = this, el = this.el, p = this.options;
            g.data = data;
            if (p.preProcess) {
                data = p.preProcess(data);
            }
            if (p.usepager) {
                $('.pReload', this.pDiv).removeClass('loading');
            }
            this.loading = false;

            if (!data) {
                if (p.usepager) {
                    $('.pPageStat', this.pDiv).html(p.errormsg);
                }
                return false;
            }
            var temp = p.total;
            p.total = data[p.totalField];// modify by chengl
            if (p.total < 0) {
                p.total = temp;
            }
            if (p.total == 0) {
                $('tr, a, td, div', el).unbind();
                $(el).empty();
                $(el).html("<div style='text-align:center'>���������Ϣ</div>");
                $(g.lockBDiv).html("");
                p.pages = 1;
                p.page = 1;
                this.buildpager();
                $('.pPageStat', this.pDiv).html(p.nomsg);
                if (p.hideOnSubmit)
                    $(g.block).remove();
                return false;
            }

            p.pages = Math.ceil(p.total / p.pageSize);
            p.page = data.page;

            if (p.usepager) {
                this.buildpager();
            }

            // var ths = $('thead tr:first th', g.hDiv);
            var tbhtml = [];
            tbhtml.push("<tbody>");
            if (p.dataType == 'json') {
                if (data[p.dataField] != null) {
                    // ѭ����
                    $.each(data[p.dataField], function(i, row) {
                        tbhtml.push(g.addOneRow(i, row));
                    });
                }

            }
            tbhtml.push("</tbody>");
            $(el).html(tbhtml.join(""));

            // �����ݲ���data�����洢��ÿһ��jquery������ add by chengl
            $.each(data[p.dataField], function(i, row) {
                row.rowNum = i;
                $("tr[id='row" + row.id + "']", el).data('data', row);
            });
            // this.rePosDrag();
            if (g.isfirst) {// ��һ�μ��ز����������ԣ�ע�������Զ�Ҫ��live���ã������Ժ���ص��ж�����Ч
                this.addRowProp();
            }
            // if (p.callback.onSuccess)
            // p.callback.onSuccess();
            el.trigger('row_success', [g.getRows(), g]);
            if (p.hideOnSubmit)
                $(g.block).remove(); // $(t).show();
            this.hDiv.scrollLeft = this.bDiv.scrollLeft;
            if ($.browser.opera)
                $(t).css('visibility', 'visible');

            if (p.lockCol) {
                if (g.lockHDiv) {
                    g.lockHDiv.empty();
                    g.lockBDiv.empty();
                } else {
                    // ��������div
                    g.lockHDiv = $("<div  class='hDiv' style='float:left;'></div>");
                    g.lockBDiv = $("<div  class='bDiv' style='float:left;overflow: hidden;'></div>");

                }
                g.processLockCol();
            }

            // ����������
            g.rePosDrag();
            g.fixHeight();

            // ����checkbox
            g.processCheckBox();
            g.processSelectRow();

        },
        /**
         * ������
         */
        processLockCol: function() {
            var g = this, el = this.el, p = this.options;
            // ���ø߶��������һ��
            g.lockBDiv.height($(g.bDiv).height());
            g.lockBDiv.css('overflow-x', 'scroll');// ��������ˮƽ����������϶���������λ����
            // style="overflow: hidden;"
            g.lockHDiv.append('<div class="hDivBox"></div>');
            $('div', g.lockHDiv).append("<table cellspacing=0 cellpadding=0 border=0 id='" + el.attr('id')
				+ "_hlTable'><thead><tr></tr></thead></table>");
            $(g.lockBDiv).append("<table cellspacing=0 cellpadding=0 border=0><tbody></tbody></table>");
            // p.lockCol
            $(g.hDiv).before(g.lockHDiv);
            $(g.bDiv).before(g.lockBDiv);

            // ����ͷ
            var $headTr = $(g.hDiv).find('tr').children();
            var hideIndexArr = [];
            // Ĭ���������
            if (p.isShowNum) {
                hideIndexArr.push(0);
            }
            // ���������ͷ
            var $th = $("<th>");
            $th.append("<div style='text-align: left; width:16px;'></div>");
            $('tr', g.lockHDiv).append($th);
            $headTr.each(function() {
                var cm = $(this).data('cm');
                if (cm) {
                    if (p.lockCol.indexOf(cm.name) != -1) {
                        var $th = $("<th colid='"
						+ cm.name + "'></th>");
                        var width = cm.width ? cm.width : 100;
                        $th.append("<div  style='text-align: left; width: "
						+ width
						+ "px;' "
						+ "'>"
						+ cm.display
						+ "</div>");
                        $('tr', g.lockHDiv).append($th);
                        hideIndexArr.push(cm.index + 1);
                    }
                }
            });
            // ������
            var $bodyTr = $(g.bDiv).find('tr');
            $bodyTr.each(function(i) {
                // �õ���
                $bodyTd = $(this).children("td");
                // �������
                $tr = $("<tr>");
                $tr.append("<td class='chboxtd'><div style='text-align: left; width:16px;'>"
                    + (i + 1) + "</div></td>");
                $('tbody', g.lockBDiv).append($tr);

                $bodyTd.each(function(j) {
                    var namex = $(this).attr('namex');
                    if (p.lockCol.indexOf(namex) != -1) {
                        $tr.append("<td>" + $(this).html() + "</td>");
                    }
                });
                $tr.height($(this).height());
                $(this).data("lockTr", $tr);// �������������Ӧ�й�������
            });
            // ���������Ӧ����
            for (var i = 0; i < hideIndexArr.length; i++) {
                g.toggleCol(hideIndexArr[i], false);
            }
        },
        /**
         * ��ԭʼ����תΪ��ʾ����
         */
        transRow: function(row) {
            var g = this, el = this.el, p = this.options;
            var ths = $('thead tr:first', g.hDiv);
            $(ths).children().each(function(j) {
                var namex = "";
                if ($(this).data('cm')) {
                    namex = $(this).data('cm').name;
                }
                var v = row[namex] || "";
                if (this.process) {
                    v = this.process(v, row, g);
                    // ���ô�����ֵ��������
                    var processname = namex + "_name";
                    row[processname] = v;
                }
            });
            return row;
        },
        /**
         * �ڵ�i�в�������row������һ��html�ַ���
         */
        addOneRow: function(i, row) {
            var tbhtml = [];
            var g = this, el = this.el, p = this.options;
            var ths = $('thead tr:first', g.hDiv);
            var ths2 = $('thead tr:last', g.hDiv);
            if (row.id != p.noCheckIdValue) {
                tbhtml.push("<tr id='", "row", row.id, "'");
            } else {
                tbhtml.push("<tr id='", row.id, "'");
            }
            if (i % 2 && p.striped) {
                tbhtml.push(" class='erow'");
            }
            if (p.rowbinddata) {
                tbhtml.push("ch='", row.cell.join("_FG$SP_"), "'");
            }
            tbhtml.push(">");
            var thsArr = [];
            var thsIndex = 0;
            $(ths).children().each(function(j) {
                var colspan = $(this).attr("colspan");
                if (colspan > 1) {
                    for (var i = 0; i < colspan; i++) {
                        thsArr.push(ths2.children().eq(++thsIndex));
                    }
                } else {
                    // thsIndex++;
                    thsArr.push($(this));
                }
            });
            // ѭ����
            $.each(thsArr, function(j) {
                // alert($(this).html())
                var $th = $(this);
                var tdclass = "";
                var idx = $th.attr('axis').substr(3);
                var namex = "";

                if ($th.data('cm')) {
                    namex = $th.data('cm').name;
                }
                var align = $(this).data("align");
                tbhtml.push("<td namex=" + namex + " align='", align
                    ? align
                    : p.bodyAlign, "'");
                var sortname = $th.attr('abbr');
                if (p.sortname && p.sortname == sortname) {
                    tdclass = 'sorted';
                }
                if ($th.css("display") == "none") {
                    tbhtml.push(" style='display:none;'");
                }
                var $thdiv = $th.children("div").eq(0);
                var width = $thdiv.attr("width");
                var width2 = $thdiv.width();
                if (width2) {
                    width = width2;
                }
                var div = [];
                div.push("<div style='text-align:",
                    align ? align : p.bodyAlign, ";width:", width, "px;");
                if (p.nowrap == false) {
                    div.push("white-space:normal");
                }
                div.push("'>");
                if (idx == "-1") { // checkbox
                    if (row.id != p.noCheckIdValue) {
                        div.push("<input type='checkbox' id='chk_", row.id,
                            "' class='itemchk' value='", row[p.keyField],
                            "'/>");
                    }
                    if (tdclass != "") {
                        tdclass += " chboxtd";
                    } else {
                        tdclass += "chboxtd";
                    }
                } else if (p.isShowNum == true && idx == "0") {
                    var num = i + 1;
                    div.push(num);
                    if (tdclass != "") {
                        tdclass += " chboxtd";
                    } else {
                        tdclass += "chboxtd";
                    }
                } else {
                    // var divInner = row.cell[idx] ||
                    // "&nbsp;";
                    var v = row[namex] || "";
                    var process = $th.data("process");
                    if ($th.attr("href")) {
                        var hrefCol = $th.attr("hrefCol")
                            ? $th.attr("hrefCol")
                            : 'id';
                        process = function(v, row) {
                            return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\''
                                + $th.attr("href")
                                + row[hrefCol]
                                + '\')">'
                                + v + '</a>';
                        }
                    }
                    if (process) {
                        v = process(v, row, g);
                        // ���ô�����ֵ��������
                        var processname = namex + "_name"
                        row[processname] = v;
                    }
                    div.push(v);
                }
                div.push("</div>");
                if (tdclass != "") {
                    tbhtml.push(" class='", tdclass, "'");
                }

                // �����ݷŵ������������ӣ��������Ч��
                tbhtml.push(">", div.join(""), "</td>");
            });
            tbhtml.push("</tr>");// alert(tbhtml.join(""))
            return tbhtml.join("");
        },

        changeSort: function(th) { // change sortorder
            var g = this, el = this.el, p = this.options;
            if (this.loading)
                return true;
            $(g.nDiv).hide();
            $(g.nBtn).hide();
            if (p.sortname == $(th).attr('abbr')) {
                if (p.sortorder == 'ASC')
                    p.sortorder = 'DESC';
                else
                    p.sortorder = 'ASC';
            }

            $(th).addClass('sorted').siblings().removeClass('sorted');
            $('.sdesc', this.hDiv).removeClass('sdesc');
            $('.sasc', this.hDiv).removeClass('sasc');
            $('div', th).addClass('s' + p.sortorder);
            p.sortname = $(th).attr('abbr');

            // if (p.callback.onChangeSort)
            // p.callback.onChangeSort(p.sortname, p.sortorder);
            // else
            this.reload();

        },
        buildpager: function() { // rebuild pager based on new properties
            var g = this, el = this.el, p = this.options;
            $('.pcontrol input', this.pDiv).val(p.page);
            $('.pcontrol span', this.pDiv).html(p.pages);

            var r1 = (p.page - 1) * p.pageSize + 1;
            var r2 = r1 + p.pageSize - 1;

            if (p.total < r2)
                r2 = p.total;

            var stat = p.pagestat;

            stat = stat.replace(/{from}/, r1);
            stat = stat.replace(/{to}/, r2);
            stat = stat.replace(/{total}/, p.total);
            $('.pPageStat', this.pDiv).html(stat);
        },
        /**
         * ˢ�±������ ��һ�μ��ز���Ҫ���н�������/�¼�����
         */
        reload: function() { // get latest data
            var g = this, el = this.el, p = this.options;
            // log.trace("��ʼ��������Դ");
            if (this.loading)
                return true;
            el.trigger('beforeload');
            this.loading = true;
            if (!p.url)
                return false;
            $('.pPageStat', this.pDiv).html(p.procmsg);
            $('.pReload', this.pDiv).addClass('loading');
            $(g.block).css({
                top: g.bDiv.offsetTop
            });
            if (p.hideOnSubmit) {
                g.reloadBlock();
                $(this.gDiv).prepend(g.block); // $(t).hide();
            }
            if ($.browser.opera)
                $(t).css('visibility', 'hidden');
            if (!p.newp)
                p.newp = 1;
            if (p.page > p.pages)
                p.page = p.pages;
            var param = {
                page: p.newp,
                pageSize: p.pageSize,
                sort: p.sortname,
                dir: p.sortorder,
                isSearchTag_: true
                // ��ʾΪ������ʶ
            };
            p.searchParam = {};// �洢����������ֵ
            p.searchParam[p.qtype] = param[p.qtype] = p.query;

            //console.log(p.qtype);
            //console.log(p.query);

            // ��������Զ����б���룬���¼��ѯ����
            if (p.customCode) {
                $.ajax({
                    type: "POST",
                    url: "?model=system_gridcustom_gridcustom&action=setCustomSearch",
                    data: {
                        leftSearch: p.searchParam,
                        rightSearch: p.param,
                        customCode: p.customCode
                    },
                    dataType: p.dataType,
                    success: function (data) {

                    }
                });
            }

            // param = jQuery.extend(param, p.param);
            if (p.param) {
                $.extend(param, p.param, p.extParam);
            }
            p.allParam = param;

            $.ajax({
                type: p.method,
                url: p.url,
                data: param,
                dataType: p.dataType,
                success: function(data) {
                    // JSLitmus.test('test', function() {
                    if (data != null && data.error != null) {
                        if (p.onError) {
                            p.onError(data);
                            g.hideLoading();
                        }
                    } else {
                        if (data.advSql) {
                            g.advSql = data.advSql;
                        }
                        if (data.listSql) {
                            g.listSql = data.listSql;
                        }
                        el.trigger('afterload', [data, g]);
                        g.addData(data);
                        el.trigger('afterloaddata', [data, g]);
                    }
                    // add by chengl ������ʾ/�����б��߶ȣ�������߶�auto��ʱ�򿴲�����ѡ������
                    if (g.isfirst) {
                        $(g.nDiv).height('auto');
                    }
                    // ����ǵ�һ�μ��ز��ҷ��ص����ݲ�Ϊ�գ������isfrist�����������ƺ�������Ϊ�¼���������
                    // ���ڵ�һ�μ���������ص�����Ϊ�գ���������ò��������ݣ��޷��������Ϊ�¼�
                    if (g.isfirst == true && data[p.dataField] != false) {
                        g.isfirst = false;
                    }
                    if (p.lockCol) {
                        // ˢ�����ݵ�ʱ������div�������λ��һ��
                        $(g.lockBDiv).scrollTop($(g.bDiv).scrollTop());
                    }
                    // });
                },
                error: function(data) {
                    try {
                        if (p.onError) {
                            p.onError(data);
                        } else {
                            alert("��ȡ���ݷ����쳣;")
                        }
                        g.hideLoading();
                    } catch (e) {
                    }
                }
            });
        },
        doSearch: function() {
            var g = this, el = this.el, p = this.options;
            var queryType = $('select[name=qtype]', g.tDiv).val();
            var qArrType = queryType.split("$");
            var index = -1;
            if (qArrType.length != 3) {
                p.qop = "Eq";
                p.qtype = queryType;
            } else {
                p.qop = qArrType[1];
                p.qtype = qArrType[0];
                index = parseInt(qArrType[2]);
            }
            p.query = $.trim($('input[name=q]', g.tDiv).val());
            // �����֤����
            if (p.query != "" && p.searchitems && index >= 0
                && p.searchitems.length > index) {
                if (p.searchitems[index].reg) {
                    if (!p.searchitems[index].reg.test(p.query)) {
                        alert("������벻����Ҫ��!");
                        return;
                    }
                }
            }
            p.newp = 1;
            if (p.isEquSearch && g.$isEqualSearch.attr('checked') == true) {
                p.extParam = {
                    isEqualSearch: true
                };
            } else {
                if (p.extParam['isEqualSearch']) {
                    delete p.extParam['isEqualSearch'];
                }
//				p.extParam = {};// �����չ����
            }
            g.reload();
            // g.changePage('first');
        },
        /**
         * ������չ����
         */
        setExtParam: function(param) {
            this.options.extParam = param;
        },
        /**
         * �����չ����
         */
        addExtParam: function(key, value) {
            var extParam = this.options.extParam;
            if (extParam) {
                this.options.extParam = {};
            }
            this.options.extParam[key] = value;
        },
        /**
         * ��Ӳ���
         */
        addParam: function(key, value) {
            var param = this.options.param;
            if (param) {
                this.options.param = {};
            }
            this.options.param[key] = value;
        },
        /**
         * ��ȡ��ǰҳ��ʾ��¼��
         */
        getPageSize: function() {
            return this.options.pageSize;
        },
        /**
         * ��ȡ��ǰҳ��
         */
        getCurPageNum: function() {
            return this.options.newp;
        },
        /**
         * ����ҳ��
         */
        changePage: function(ctype) { // change page
            var g = this, el = this.el, p = this.options;
            if (this.loading)
                return true;

            switch (ctype) {
                case 'first' :
                    p.newp = 1;
                    break;
                case 'prev' :
                    if (p.page > 1)
                        p.newp = parseInt(p.page) - 1;
                    break;
                case 'next' :
                    if (p.page < p.pages)
                        p.newp = parseInt(p.page) + 1;
                    break;
                case 'last' :
                    p.newp = p.pages;
                    break;
                case 'input' :
                    var nv = parseInt($('.pcontrol input', this.pDiv).val());
                    if (isNaN(nv))
                        nv = 1;
                    if (nv < 1)
                        nv = 1;
                    else if (nv > p.pages)
                        nv = p.pages;
                    $('.pcontrol input', this.pDiv).val(nv);
                    p.newp = nv;
                    break;
            }

            if (p.newp == p.page)
                return false;

            if (p.onChangePage)
                p.onChangePage(p.newp);
            else
                this.reload();

        },
        /**
         * �����ѡ�����ö�ѡ�¼�������ѡ������
         */
        processCheckBox: function() {
            var g = this, el = this.el, p = this.options;
            if(!p.isAllPageCheckedId){
                p.checkedIdArr = [];
            }
            g.getRows().bind("row_check", function() {
                var $r = $(this);
                var $c = $r.find(":checkbox.itemchk");
                var checked = $c.attr("checked");
                var v = $c.val();
                var index = p.checkedIdArr.indexOf(v);
                if (checked) {
                    if (index == -1) {
                        p.checkedIdArr.push(v);
                    }
                } else {
                    if (index != -1) {
                        p.checkedIdArr.splice(index, 1);
                    }
                }
            });

            g.getCheckboxs().each(function() {
                var v = $(this).val();
                var checkedId = p.checkedIdArr.toString();
                if (checkedId && p.checkedIdArr.indexOf(v) != -1) {
                    var checkbox = $("input[type='checkbox'][value='" + v
                    + "'].itemchk", g.bDiv);
                    checkbox.trigger('click', [true]);
                }
                ;
            });
        },
        /**
         * ����ѡ����
         */
        processSelectRow: function() {
            var g = this, el = this.el, p = this.options;
            if (p.selectRowId) {
                g.selectRow(p.selectRowId);
            }
        },
        /**
         * ��ȡ����ܼ�¼��
         */
        getAllCount: function() {
            return this.options.total;
        },
        /**
         * ��ȡ�����ʾ�ļ�¼��
         */
        getCount: function() {
            var p = this.options;
            if (p.page > 1) {
                return p.pageSize;
            }
            return p.total;
        },
        /**
         * ��ȡcheckboxѡ�е���
         */
        getCheckedRows: function() {
            var rows = [];
            $(":checkbox:checked.itemchk", this.bDiv).each(function() {
                rows.push($(this).parents('tr').data('data'));
            });
            return rows;

        },
        /**
         * ��ȡ����checkboxѡ�е���id
         */
        getAllCheckedRowIds: function() {
            return this.options.checkedIdArr;
        },
        /**
         * �������checkboxѡ�е���id
         */
        removeAllCheckedRowIds: function() {
            this.options.checkedIdArr = [];
        },
        /**
         * ��ȡ��ǰҳcheckboxѡ�е���id
         */
        getCheckedRowIds: function() {
            var ids = [];
            $(":checkbox:checked.itemchk", this.bDiv).each(function() {
                ids.push($(this).val());
            });
            return ids;
        },
        /**
         * �ٷ�ѡ���¼�
         */
        triggerCheckboxs: function(checked) {
            for (var i = 0; i < checked.size(); i++) {
                var row = $(checked[i]).parents("tr");
                var rowData = row.data("data");
                row.trigger('row_check', [$(checked[i]), row, rowData]);
            }
        },
        /**
         * ѡ������checkbox
         */
        checkAll: function() {
            var checked = $(":checkbox.itemchk:[checked!=true]", this.bDiv);
            if (checked.size() > 0) {
                checked.trigger('click', [true]);
                this.triggerCheckboxs(checked);
            }
        },
        /**
         * �������ѡ�е�checkbox
         */
        clearCheckAll: function() {
            if (this.options.showcheckbox) {
                var checked = $(":checkbox:checked.itemchk", this.bDiv);
                if (checked.size() > 0) {
                    checked.trigger('click', [true]);
                    this.triggerCheckboxs(checked);
                }
            } else {
                $("tr.trSelected", this.bDiv).removeClass("trSelected");
                ;
            }
            // g.selectedRow = null;
        },
        checkAllOrNot: function(checkbox) {

            var ischeck = $(checkbox).attr("checked");
            if (ischeck) {
                this.checkAll();
            } else {
                this.clearCheckAll();
            }
        },
        /**
         * ��ȡ��һ��
         */

        getFirstRow: function() {
            var rows = this.getRows();
            if (rows.size() == 0) {
                return null;
            } else {
                return rows.first();
            }
        },
        /**
         * ��ȡѡ�е�һ��
         */
        getFirstSelectedRow: function() {
            var selecteds = $("tr.trSelected", this.bDiv);
            if (selecteds.size() == 0) {
                return null;
            } else {
                return selecteds.first();
            }
        },
        /**
         * ��ѡѡ�е���
         */
        getSelectedRow: function() {
            var selecteds = $("tr.trSelected", this.bDiv);
            if (selecteds.size() == 0) {
                return null;
            } else {
                return selecteds.last();// �����һ��ѡ�е�
            }
        },
        /**
         * ��ȡѡ���е�����
         */
        getSelecteRowData: function() {
            var row = this.getSelectedRow();
            return row.data("data");
        },
        getCellDim: function(obj) // get cell prop for editable event
        {
            var ht = parseInt($(obj).height());
            var pht = parseInt($(obj).parent().height());
            var wt = parseInt(obj.style.width);
            var pwt = parseInt($(obj).parent().width());
            var top = obj.offsetParent.offsetTop;
            var left = obj.offsetParent.offsetLeft;
            var pdl = parseInt($(obj).css('paddingLeft'));
            var pdt = parseInt($(obj).css('paddingTop'));
            return {
                ht: ht,
                wt: wt,
                top: top,
                left: left,
                pdl: pdl,
                pdt: pdt,
                pht: pht,
                pwt: pwt
            };
        },
        /**
         * ���ص�ǰҳ����jquery����
         */
        getRows: function() {
            return $("tr[id^='row']", this.bDiv);
        },
        /**
         * ��ȡ���еĶ�ѡ��
         */
        getCheckboxs: function() {
            return $("input[type='checkbox'].itemchk", this.bDiv);
        },
        /**
         * �����л�ȡ���ڵ�checkbox
         */
        getCheckboxByRow: function(row) {
            var rowData = $(row).data("data");
            return $(row).find("input[id='chk_" + rowData.id + "']");
        },
        /**
         * ѡ��һ����¼
         */
        selectRow: function(rowId) {
            return this.selectRows([rowId]);
        },
        /**
         * ѡ�ж�����¼
         */
        selectRows: function(rowIds) {
            for (var i = 0; i < rowIds.length; i++) {
                var rowId = rowIds[i];
                $("#row" + rowId, this.bDiv).trigger('click', [true]);
            }
        },
        /**
         * ѡ�е�һ������
         */
        selectFirstRow: function() {
            var g = this, el = this.el, p = this.options;
            g.getFirstRow().trigger('click', [true]);
        },
        /**
         * ���ظ߼�����sql
         */
        getAdvSql: function() {
            return this.advSql;
        },
        /**
         * �����б�sql������ҳ��
         */
        getListSql: function() {
            return this.listSql;
        },
        /**
         * ��������Թ��ܣ��������ѡ�������Ƴ���ʽ�л�,�Ҽ��˵���
         */
        addRowProp: function() {
            var g = this, el = this.el, p = this.options;
            var rows = g.getRows();
            // ��������������
            // 1.�������Ҽ������
            // 2.�ֶ����checkbox ��ִ��Ĭ���¼�����ִ��click�¼�
            // 3.���򴥷���checkbox ��ִ��click�¼�����ִ��Ĭ���¼�
            rows.die('click contextmenu');// ���Ƴ��¼�����ֹ�ۼ�
            rows.live('click contextmenu', function(e, isTrigger) {
                var row = $(this);
                var rowData = row.data('data');

                var isRowClick = false;
                var isRightClick = false;
                if (e.type == 'contextmenu') {// �Ƿ��Ҽ�
                    isRightClick = true;
                }
                var nodeName = $(e.target).context.nodeName;
                if (nodeName == "DIV" || nodeName == "TD"
                    || nodeName == "A") {
                    isRowClick = true;
                }
                var checkbox = row.find(":checkbox.itemchk");
                // ����ѡ����
                var selectFn1 = function() {
                    if (!p.showcheckbox) {
                        g.clearCheckAll();
                    }
                    if (!checkbox.attr('checked')) {
                        checkbox.attr('checked', true);
                        row.addClass("trSelected");
                    } else {
                        checkbox.attr('checked', false);
                        row.removeClass("trSelected");
                    }
                    // ����click�¼�
                    row.trigger('row_click', [row, rowData]);
                    if (p.showcheckbox) {
                        row.trigger('row_check', [checkbox, row,
                            rowData]);
                    }
                };
                // �Ҽ���
                var selectFn2 = function() {
                    g.clearCheckAll();
                    if (!checkbox.attr('checked')) {
                        checkbox.attr('checked', true);
                        row.addClass("trSelected");
                        $(e.target).focus();// ������Ԫ���Ҽ��Ϳ��Ը���
                    } else {
                        checkbox.attr('checked', false);
                        row.removeClass("trSelected");
                        $(e.target).focus();
                    }
                    row.trigger('row_rclick', [row, rowData]);// �����Ҽ�����¼�
                };
                // var selectFn2 = function() {
                // checkbox.attr('checked', true);
                // self.addClass("trSelected");
                // }
                // �ֶ����checkbox
                var selectFn3 = function() {
                    if (checkbox.attr('checked')) {
                        row.addClass("trSelected");
                    } else {
                        row.removeClass("trSelected");
                    }
                    row.trigger('row_check', [checkbox, row, rowData]);

                }
                // ���򴥷���checkbox
                var selectFn4 = function() {
                    if (!checkbox.attr('checked')) {
                        row.addClass("trSelected");
                    } else {
                        row.removeClass("trSelected");
                    }
                }
                if (isRightClick) {
                    selectFn2();
                } else if (isRowClick) {
                    selectFn1();
                } else {
                    if (!isTrigger) {
                        selectFn3();
                    } else {
                        selectFn4();
                        // return false;
                    }
                }
                // g.selectedRow = $(this);// �洢���һ��ѡ��ļ�¼

                if (e.type == 'contextmenu' && p.isRightMenu) {
                    e.preventDefault();
                }
            });
            rows.live('dblclick', function(e, isTrigger) {
                var row = $(this);
                // ����click�¼�
                row.trigger('row_dblclick', [row, row.data('data')]);
            });
            if (p.isRightMenu) {
                this.createRightMenu(rows);
            }
            if ($.browser.msie && $.browser.version < 7.0) {
                rows.hover(function() {
                    $(this).addClass('trOver');
                }, function() {
                    $(this).removeClass('trOver');
                });
            }
        },
        /**
         * ����ie tr/td/th:visible��Чbug
         */
        getTheadVisible: function(dom, contain) {
            dom = dom ? dom : "th";
            contain = contain ? contain : this.hDiv;
            return $($.grep($(dom + ':visible', contain), function(t, i) {
                return $(t).css("display") !== 'none';
            }));
        },
        /**
         * ����ie tr/td/th:visible��Чbug
         */
        getThead: function(dom, contain) {
            dom = dom ? dom : "th";
            contain = contain ? contain : this.hDiv;
            return $(dom, contain);
        }

    });

    $.fn.noSelect = function(p) { // no select plugin by me :-)
        if (p == null)
            prevent = true;
        else
            prevent = p;

        if (prevent) {

            return this.each(function() {
                if ($.browser.msie || $.browser.safari)
                    $(this).bind('selectstart', function() {
                        return false;
                    });
                else if ($.browser.mozilla) {
                    $(this).css('MozUserSelect', 'none');
                    $('body').trigger('focus');
                } else if ($.browser.opera)
                    $(this).bind('mousedown', function() {
                        return false;
                    });
                else
                    $(this).attr('unselectable', 'on');
            });

        } else {

            return this.each(function() {
                if ($.browser.msie || $.browser.safari)
                    $(this).unbind('selectstart');
                else if ($.browser.mozilla)
                    $(this).css('MozUserSelect', 'inherit');
                else if ($.browser.opera)
                    $(this).unbind('mousedown');
                else
                    $(this).removeAttr('unselectable', 'on');
            });

        }
        ;

    }; // end noSelect

})(jQuery);