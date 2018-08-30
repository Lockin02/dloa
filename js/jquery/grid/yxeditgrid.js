/**
 * �ɱ༭���1.0
 */
(function($) {
	$.woo.component.subclass('woo.yxeditgrid', {
		options: {
			/**
			 * �ؼ�����ǰ׺
			 */
			objName: '',
			/**
			 * Ĭ�Ͽؼ�����
			 */
			defaultType: 'text',
			/**
			 * �ؼ�Ĭ����ʽ
			 */
			defaultClass: "txtmiddle",
			/**
			 * ��ģ��
			 *
			 * @1.display ����ʾ����
			 * @2.name ������
			 * @3.type �ؼ�����
			 * @4.event �ؼ��¼�
			 * @5.width �п��
			 * @6.value ֵ
			 */
			colModel: [],
			/**
			 * ��̬����
			 */
			data: [],
			/**
			 * ��ȡ����url
			 */
			url: '',
			/**
			 * ɾ����־����(��̨���ݴ˱�־����ɾ��)
			 */
			delTagName: 'isDelTag',
			/**
			 * ����:view��Ϊ�鿴
			 */
			type: '',
			/**
			 * �ܷ�������ɾ��
			 */
			isAddAndDel: true,
			/**
			 * �Ƿ���ʾ��Ӱ�ť
			 */
			isAdd: true,
			/**
			 * Ĭ���Ƿ����һ��������
			 */
			isAddOneRow: true,
			/**
			 * ��һ���Ƿ�����ɾ��
			 */
			isFristRowDenyDel: false,
			/**
			 * Ĭ�ϳ�ʼ������
			 */
			initAddRowNum: 1,
			/**
			 * Ĭ�ϸ߶�
			 */
			height: 100,
			/**
			 * �¼�
			 */
			event: {},
			/**
			 * �����ʽ
			 */
			tableClass: 'main_table',
			/**
			 * �ӱ��ͷ
			 */
			title: '',
			/**
			 * �Ƿ�������ɾ��������ǣ���ÿ��ɾ������ɾ���ڵ�
			 */
			realDel: false,
			/**
			 * �����Ƿ�������������
			 */
			hideRowNum: false,
			/**
			 * ��ͷ�Ƿ�Ҳ��Ҫ��λ
			 */
			titleAlign: false
		},
		/**
		 * ��ʼ�����
		 */
		_create: function() {
			var g = this, el = this.el, p = this.options;
			g.table = $("<table class='" + p.tableClass + "'>");
			if (p.width) {
				g.table.width(p.width);
			}
			$(el).append(g.table);
			$(el).attr("style", "overflow-x:auto;overflow-y:hidden;");
			// ��������а�ť
			var $addBn = $('<span class="addBn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>');
			// ��ʼ������ͷ
			var $tr = $("<tr class='main_tr_header'>");
			var $th;
			if (p.type != 'view' && p.isAddAndDel == true) {
				$th = $("<th width='10'></th>");
				if (p.isAdd) {
					$th.append($addBn);
				}
				$tr.append($th);
			}
			g.addBn = $addBn;
			if (p.hideRowNum == false) {
				$th = $("<th width='30' nowrap='nowrap'></th>");
				$th.append("���");
				$tr.append($th);
			}
			var config;
			for (var i = 0; i < p.colModel.length; i++) {
				config = p.colModel[i];
				$th = $("<th></th>");
				if (config.width) {
					$th.width(config.width);
				}
				if (config.type == 'hidden') {
					$th.hide();
				}
				// ���ֶ�λ
				if (p.titleAlign) {
					$th.css("text-align",
						config.align ? config.align : "center");
				}
				//TODO ��С���
				$th.append("<div class='divChangeLine'>" + config.display + "</div>");
				$tr.append($th);
			}
			var $thead = $("<thead>");
			// ��ͷ��Ӵ���
			if (p.title != '') {
				$thead.append("<tr><td colspan='99' class='form_header' id='" + el.attr('id') + "_cmp_table_title" + "'>"
				+ p.title + "</td></tr>");
			}
			$thead.append($tr);
			g.table.append($thead);
			g.tbody = $("<tbody>");
			g.table.append(g.tbody);
			// ������ͷ����
			// ���������ֵ�
			g.processDatadict();
			// �������¼�
			g.curRowNum = 0;// ����ͳ�Ƶ�ǰ����������ɾ�����صģ�
			g.curShowRowNum = 0;// ����ͳ�Ƶ�ǰ������������ɾ�����صģ�
			g.allAddRowNum = 0;// ����ͳ���������
			$addBn.click(function() {
				g.addBtnClick();
				$tr.trigger('clickAddRow', [g.allAddRowNum - 1, g]);
			});
			if (p.data.length > 0 || p.url != '') {
				g.processData();
			} else if (p.isAddOneRow) {// û�г�ʼ�����ݵ�ʱ��Ĭ�����һ��
				for (i = 0; i < p.initAddRowNum; i++) {
					$addBn.trigger('click');
				}
			}
		},
		/**
		 * ������ť�����¼�
		 */
		addBtnClick: function() {
			this.addRow(this.allAddRowNum);
		},
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
						data = data ? data : [];
						g.reloadData(data);
						$(el).trigger('reloadData', [g, data]);
					}
				});
			}
		},
		/**
		 * ����ˢ������
		 */
		reloadData: function(data) {
			this.removeAll(true);
			this.addRows(data);
		},
		/**
		 * �����������
		 */
		addRows: function(data) {
			var g = this;
			if (data) { // add by zengzx data����Ϊ��ʱ����2012��12��18�� 17:18:38
				for (var i = 0; i < data.length; i++) {
					g.addRow(i, data[i]);
				}
			}
		},
		/**
		 * ����һ��
		 */
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
			// ��һ���Ƿ���ʾɾ����ť���Ҳ����ǵ�һ�� ���Ҳ��ǲ鿴��� �K��isAddAndDel��true
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
			g.tbody.append($tr);

			$tr.trigger('beforeAddRow', [rowNum, rowData, g]);
			for (var i = 0; i < p.colModel.length; i++) {
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
				if (p.type == 'view' && config.type != 'hidden'
					&& config.type != 'file') {
					type = "statictext";
					config.tclass = "";
				}
				switch (type) {
					case 'select' :// ����ѡ��
						$input = $("<select>");
						// ���������ֵ�
						if (config.datacode) {
							if (emptyOption == true) {
								var $option = $("<option " + selected
								+ " value=''></option>");
								$input.append($option);
							}
							var data = g.datadict[name].datadictData;
							if (data) {
								for (var d = 0; d < data.length; d++) {
									var option = data[d];
									var selected = "";
									if (val == option.dataCode) {
										selected = "selected";
									}
									var $option = $("<option " + selected
									+ " value='" + option.dataCode
									+ "' title='" + option.dataName
									+ "'>" + option.dataName
									+ "</option>");
									$input.append($option);
								}
							}
						} else {// ����̬����
							if (config.options) {
								if (emptyOption == true) {
									var $option = $("<option " + selected
									+ " value=''></option>");
									$input.append($option);
								}
								for (var j = 0; j < config.options.length; j++) {
									var option = config.options[j];
									var selected = "";
									if (val == option.value) {
										selected = "selected";
									}
									var $option = $("<option " + selected
									+ " title='" + option.name
									+ "' value='" + option.value + "'>"
									+ option.name + "</option>");
									for (var key in option) {
										if (key != "value" && key != "name") {
											if (!$.isFunction(option[key])) {
												$option.data(key, option[key]);
											}
										}
									}
									$input.append($option);
								}
							}
						}
						break;
					case 'statictext' :// ��̬�ı�
						if (!config.tclass) {
							tclass = "";// ��̬�ı�������Ĭ�ϵ���ʽ
						}
						$input = $("<div   class='divChangeLine'>");
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
						// �����Ҫ�ύֵ����̬����һ��������
						if (config.isSubmit) {
							var $h = $("<input type='hidden'>");
							$h.attr('name', cmpName);
							$h.val(oldHtml);
							$input.append($h);
						}
						break;
					case 'hidden' :// ������
						$input = $("<input type='hidden'>");
						break;
					case 'checkbox' :// ��ѡ
						if (!config.tclass) {
							tclass = "";// ��̬�ı�������Ĭ�ϵ���ʽ
						}
						var cv = config.checkVal;
						cv = cv ? cv : "on";

						if (val == cv) {
							config.checked = val == cv;
						} else {
							config.checked = false;
						}
						$input = $("<input type='checkbox' "
						+ (config.checked == true ? "checked" : '')
						+ ">");
						if (config.checkVal) {
							$input.val(config.checkVal);
						}
						break;
					case 'textarea' :// ���ı���
						$input = $("<textarea></textarea>");
						if (config.cols) {
							$input.attr("cols", config.cols);
						}
						if (config.rows) {
							$input.attr("rows", config.rows);
						}
						if (!config.tclass) {
							tclass = "";
						}
						break;
					case 'file' :// �������ͣ���Ҫ�����������
						if (!config.tclass) {
							tclass = "";
						}
						var fpId = elId + "_" + name + "_fp_" + +rowNum;
						var swfId = elId + "_" + name + "_swf_" + +rowNum;
						var btcId = elId + "_" + name + "_btc_" + +rowNum;
						var uflId = elId + "_" + name + "_ufl_" + +rowNum;
						if (p.type == "view") {
							$input = $('<div class="upload"><div id="' + uflId
							+ '" class="upload"></div></div>');
						} else {
							$input = $('<div class="upload">'
							+ '<div class="upload" id="'
							+ fpId
							+ '"></div>'
							+ '<div class="upload">'
							+ '<span id="'
							+ swfId
							+ '"></span> '
							+ '<input id="'
							+ btcId
							+ '" type="button" value="��ֹ�ϴ�" onclick="cancelQueue(uploadfile);" disabled="disabled" /> <br />'
							+ '</div>' + '<div id="' + uflId
							+ '" class="upload"></div>' + '</div>');
						}
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

				// һЩ��������Ĵ���
				// ���ڿؼ�����
				if (type == 'date') {
					$input.click(function() {
						WdatePicker({
							dateFmt: 'yyyy-MM-dd'
						});
					});
				} else if (type == 'int') {// ����,��������
					$input.keypress(function(event) {
						return event.keyCode >= 48 && event.keyCode <= 57;
					});
				} else if (type == 'float') {// ������
					$input.keypress(function(event) {
						return (event.keyCode >= 48 && event.keyCode <= 57)
							|| event.keyCode == 46
							|| event.keyCode == 45;
					});
				} else if (type == 'money') {// ����ǧ��λ,С���㣩
					$input.blur(function(event) {
					});
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

				/** * �ƶ�һ���¼��Ĵ���˳������ǽ��Ļ����ȴ���ǧ��λ���� ** */
				/** * Modify by kuangzw ** */
				// ������һЩ����,����
				if (type == 'moneySix') { // 6λС���Ľ��
					$input.attr("etype", "moneySix");
					createFormatOnClick($input.attr("id"), null, null, null, 6);
				} else if (type == 'moneyAndNegative') { // �������
					$input.attr("etype", "moneyAndNegative");
					createFormatOnClick($input.attr("id"), null, null, null, 2, true);
				} else if (type == 'money') {
					$input.attr("etype", "money");
					createFormatOnClick($input.attr("id"));
				} else if (type == 'file') {
					if (p.type != "view") {
						$input.removeAttr("name");
						createSWFUpload({
							"serviceType": config.serviceType,// ҵ��ģ����룬һ��ȡ����
							"serviceId": rowData && rowData.id ? rowData.id : $input.attr("id")
						}, {
							button_placeholder_id: swfId,
							cancelButtonId: btcId,
							fileListId: uflId,
							progressTarget: fpId,
							ajaxList: true,
							fileNamePre: cmpName // �ļ�ǰ׺
						});
					} else {
						$.ajax({
							url: '?model=file_uploadfile_management&action=getFilelist',
							type: "POST",
							data: {
								serviceId: rowData.id,
								serviceType: config.serviceType,
								isShowDel: false
							},
							success: function(data) {
								$("#" + uflId).append(data);
							}
						})
					}
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
			$tr.trigger('addRow', [rowNum, rowData, g, $tr]);
		},
		/**
		 * �����������ֵ䣬�õ����������������ֵ�����,��ɱ����ַ����ύ����̨��ȡ�����ֵ�������
		 */
		processDatadict: function() {
			var g = this, p = this.options, cm = p.colModel;
			g.datadict = {};
			for (var v in cm) {
				if (cm[v].datacode) {
					g.datadict[cm[v].name] = cm[v];
				}
			}
			var codes = [];
			for (var v in g.datadict) {
				codes.push(g.datadict[v].datacode);
			}
			if (codes.length > 0) {
				$.ajax({
					type: 'POST',
					url: "?model=system_datadict_datadict&action=getDataJsonByCodes",
					data: {
						codes: codes.toString()
					},
					async: false,
					dataType: 'json',
					success: function(datadictData) {
						for (var v in g.datadict) {
							var code = g.datadict[v].datacode;
							if ($.isFunction(g.datadict[v].processData)) {
								datadictData[code] = g.datadict[v]
									.processData(datadictData[code]);
							}
							g.datadict[v].datadictData = datadictData[code];
							if (!g.datadict[v].process && datadictData[code]) {
								// ����ʹ�ñհ���Ŀ����Ϊ���ڷ��ص�function��ʹ�ñհ��е�������
								g.datadict[v].process = (function() {
									var data = datadictData[code];
									return function(v) {
										return $.woo.getDataName(v, data);
									}
								})();
							}
						}
					}
				});
			}
		},
		/**
		 * ���һ������
		 */
		clearRowValue: function(rowNum) {
			var g = this, p = this.options;
			for (var i = 0; i < p.colModel.length; i++) {
				g.getCmpByRowAndCol(rowNum, p.colModel[i].name).val("");
			}
		},
		/**
		 * ��ȡĳһ��jquery����
		 */
		getRowByRowNum: function(rowNum) {
			return $("tr[rownum='" + rowNum + "']", this.el);
		},
		/**
		 * ��ȡĳ��ĳ�еĿؼ�����
		 */
		getCmpByRowAndCol: function(rowNum, colName, trueVal) {
			var el = this.el;
			var elId = el.attr('id');
			var cmp = $("#" + elId + "_cmp_" + colName + rowNum + "_v", el);
			if (cmp[0] && !trueVal) {
				return cmp;
			}
			return $("#" + elId + "_cmp_" + colName + rowNum, el);
		},
		/**
		 * ��ȡĳ�еĿؼ����󼯺�
		 */
		getCmpByCol: function(colName) {
			var el = this.el;
			var elId = el.attr('id');
			var colId = elId + "_cmp_" + colName;
			// �����һ����������
			// �޸����Ƚ����ص�bug��colName�����ǰ���ַ����������colNameһ�£����ҵ��˶���
			var hiddenCmps = $("[id^='" + colId + "'][type='hidden']", el);
			if (hiddenCmps.size() > 0) {
				var rhiddenCmps = hiddenCmps.filter(function() {
					var s = $(this).attr('id').substr(colId.length);
					return ($.woo.isNumber(Number(s))) && (!$(this).parents("tr").is(":hidden"));
				});
				return rhiddenCmps;
			}

			var cmps = $("[id^='" + colId + "']:visible", el);
			var rcmps = cmps.filter(function() {
				var s = $(this).attr('id').substr(colId.length);
				return $.woo.isNumber(Number(s));
			});
			return rcmps;
		},
		/**
		 * ��ȡ��ɾ��ĳ�еĿؼ����󼯺�
		 */
		getDelCmpByCol: function(colName) {
			var el = this.el;
			var elId = el.attr('id');
			return $("[id^='" + elId + "_cmp_" + colName + "']:hidden", el
				.find("tr:hidden"));
		},
		/**
		 * �Ƴ������� isDel:�Ƿ���ɾ�� true ��ӽ���ɾ��Ԫ��
		 */
		removeAll: function(isDel) {
			var g = this, el = this.el;

			$(el).find("tbody").children().each(function() {
				var rowNum = $(this).attr("rowNum");
				if ($(this).is(":hidden")) {// ��ɾ����ֱ���Ƴ�
					$(this).remove();
				} else {
					g.removeRow(rowNum, isDel);
				}
			});
			g.curRowNum = 0;
			g.curShowRowNum = 0;
		},
		/**
		 * �ж����Ƿ񱻼�ɾ��
		 */
		isRowDel: function(rowNum) {
			var el = this.el, p = this.options;
			// ��ɾ��
			return ($("#" + el.attr('id') + "_cmp_" + p.delTagName + rowNum).val() == 1);
		},
		/**
		 * ��ʾ��
		 */
		showRow: function(rowNum) {
			var el = this.el;
			var $tr = $(el).find("tr[rowNum='" + rowNum + "']").show();
			$tr.nextAll("tr").find("td[type='rowNum']").each(function(v) {
				var index = $(this).html();
				index = parseInt(index);
				if (index > rowNum) {
					index++;
					$(this).html(index);
				}
			});
		},
		/**
		 * �Ƴ�һ������
		 */
		removeRow: function(rowNum, isDel) {
			var g = this, el = this.el, p = this.options;
			var $tr = $(el).find("tr[rowNum='" + rowNum + "']");
			var index = $tr.data("index");
			var rowData = $tr.data("rowData");
			g.curShowRowNum--;
			if (!isDel && rowData && rowData.id && !p.realDel) {
				// ��ɾ��
				var elId = el.attr('id');
				var $delTag = $("<input type='hidden' id='" + elId + "_cmp_"
				+ p.delTagName + index + "' name='" + p.objName
				+ "[" + index + "][" + p.delTagName + "]' value='1' />");

				$tr.append($delTag);
				$tr.hide();
				$tr.trigger('removeRow', [rowNum, rowData, index, g]);
				//����һ��������ʽ����,��Ҫ��Ϊ�˽��ɾ���к󣬴ӱ���֤��Ȼ�ڵ�����
				//2013-05-24 by kuangzw
				$tr.find("input").each(function(i, n) {
					$(this).attr("class", "");
				});
				$tr.find("select").each(function(i, n) {
					$(this).attr("class", "");
				});
			} else {
				g.curRowNum--;
				// rowNum����ʵ�ʵ�������ɾ��������һ�е�ʱ��ᵼ�������иı��ֵ index�򲻻�ı�
				$tr.trigger('removeRow', [rowNum, rowData, index, g]);
				$tr.remove();
			}

			// ���кŴ���
			g.processRowNum();
		},
		/**
		 * �������
		 */
		processRowNum: function() {
			var el = this.el;
			var i = 1;
			$(el).find("td[type='rowNum']").each(function() {
				if ($(this).is(":hidden")) {// ��ɾ����ֱ���Ƴ�

				} else {
					$(this).html(i++);
				}
			});
		},
		/**
		 * ��ȡ��ǰ����,�������ص�
		 */
		getCurRowNum: function() {
			return this.curRowNum;
		},
		/**
		 * ��ȡ��ǰ����,�����ص�
		 */
		getCurShowRowNum: function() {
			return this.curShowRowNum;
		},
		/**
		 * ��ȡ�������������
		 */
		getAllAddRowNum: function() {
			return this.allAddRowNum;
		},
		/**
		 * ��̬��ʾ/��������а�ť
		 */
		showAddBn: function() {
			this.addBn.show();
		},
		hideAddBn: function() {
			this.addBn.hide();
		},
		/**
		 * �ݻ����
		 */
		remove: function() {
			this.el.empty();
			this.el.unbind();
			this.destroy();
		},
		/**
		 * �ⲿ�ӿ�:��̬����Ĭ��ֵ
		 */
		setConfigValue: function(colName, val) {
			var p = this.options;
			for (var i = 0; i < p.colModel.length; i++) {
				var config = p.colModel[i];
				if (config.name == colName) {
					p.colModel[i].value = val;
					break;
				}
			}
		},
		/**
		 * ������ֵ������Ĭ��ֵ���Ѿ����ڵ���ֵ
		 */
		setColValue: function(colName, val) {
			this.setConfigValue(colName, val);
			this.getCmpByCol(colName).val(val);
		},
		/**
		 * ����ĳһ����ֵ isBlur:�Ƿ�ٷ�ʧ�����ԣ���һЩ�����������Ҫ�õ�
		 */
		setRowColValue: function(rowNum, colName, val, isBlur) {
			var $cmp = this.getCmpByRowAndCol(rowNum, colName);
			$cmp.val(val);
			if (isBlur) {
				$cmp.trigger('blur');
			}
		},
		/**
		 * ��ȡĳһ��ĳһ�����id
		 */
		getRowColId: function(rowNum, colName) {
			return this.el.attr('id') + "_cmp_" + colName + rowNum;
		},
		/**
		 * ������ֵ������Ĭ��ֵ���Ѿ����ڵ���ֵ
		 */
		setParam: function(thisParam) {
			this.options.param = thisParam;
		},
		/**
		 * ��ȡ��ͷ
		 */
		getTitleObj: function() {
			return $("#" + this.el.attr('id') + "_cmp_table_title");
		}
	});
})(jQuery);