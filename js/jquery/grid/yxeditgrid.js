/**
 * 可编辑表格1.0
 */
(function($) {
	$.woo.component.subclass('woo.yxeditgrid', {
		options: {
			/**
			 * 控件名称前缀
			 */
			objName: '',
			/**
			 * 默认控件类型
			 */
			defaultType: 'text',
			/**
			 * 控件默认样式
			 */
			defaultClass: "txtmiddle",
			/**
			 * 列模型
			 *
			 * @1.display 列显示名称
			 * @2.name 列名称
			 * @3.type 控件类型
			 * @4.event 控件事件
			 * @5.width 列宽度
			 * @6.value 值
			 */
			colModel: [],
			/**
			 * 静态数据
			 */
			data: [],
			/**
			 * 获取数据url
			 */
			url: '',
			/**
			 * 删除标志名称(后台根据此标志进行删除)
			 */
			delTagName: 'isDelTag',
			/**
			 * 类型:view则为查看
			 */
			type: '',
			/**
			 * 能否新增及删除
			 */
			isAddAndDel: true,
			/**
			 * 是否显示添加按钮
			 */
			isAdd: true,
			/**
			 * 默认是否添加一条空数据
			 */
			isAddOneRow: true,
			/**
			 * 第一行是否允许删除
			 */
			isFristRowDenyDel: false,
			/**
			 * 默认初始的行数
			 */
			initAddRowNum: 1,
			/**
			 * 默认高度
			 */
			height: 100,
			/**
			 * 事件
			 */
			event: {},
			/**
			 * 表格样式
			 */
			tableClass: 'main_table',
			/**
			 * 从表表头
			 */
			title: '',
			/**
			 * 是否总是真删除，如果是，则每次删除都是删除节点
			 */
			realDel: false,
			/**
			 * 设置是否可以隐藏序号列
			 */
			hideRowNum: false,
			/**
			 * 表头是否也需要定位
			 */
			titleAlign: false
		},
		/**
		 * 初始化组件
		 */
		_create: function() {
			var g = this, el = this.el, p = this.options;
			g.table = $("<table class='" + p.tableClass + "'>");
			if (p.width) {
				g.table.width(p.width);
			}
			$(el).append(g.table);
			$(el).attr("style", "overflow-x:auto;overflow-y:hidden;");
			// 加入添加行按钮
			var $addBn = $('<span class="addBn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>');
			// 开始构建列头
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
				$th.append("序号");
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
				// 文字定位
				if (p.titleAlign) {
					$th.css("text-align",
						config.align ? config.align : "center");
				}
				//TODO 最小宽度
				$th.append("<div class='divChangeLine'>" + config.display + "</div>");
				$tr.append($th);
			}
			var $thead = $("<thead>");
			// 表头添加处理
			if (p.title != '') {
				$thead.append("<tr><td colspan='99' class='form_header' id='" + el.attr('id') + "_cmp_table_title" + "'>"
				+ p.title + "</td></tr>");
			}
			$thead.append($tr);
			g.table.append($thead);
			g.tbody = $("<tbody>");
			g.table.append(g.tbody);
			// 构建列头结束
			// 处理数据字典
			g.processDatadict();
			// 新增行事件
			g.curRowNum = 0;// 用来统计当前行数（包括删除隐藏的）
			g.curShowRowNum = 0;// 用来统计当前行数（不包括删除隐藏的）
			g.allAddRowNum = 0;// 用来统计添加数量
			$addBn.click(function() {
				g.addBtnClick();
				$tr.trigger('clickAddRow', [g.allAddRowNum - 1, g]);
			});
			if (p.data.length > 0 || p.url != '') {
				g.processData();
			} else if (p.isAddOneRow) {// 没有初始化数据的时候默认添加一条
				for (i = 0; i < p.initAddRowNum; i++) {
					$addBn.trigger('click');
				}
			}
		},
		/**
		 * 新增按钮单击事件
		 */
		addBtnClick: function() {
			this.addRow(this.allAddRowNum);
		},
		/**
		 * 处理表格初始化数据
		 */
		processData: function() {
			var g = this, el = this.el, p = this.options;
			// 处理静态数据
			if (p.data.length > 0) {
				g.reloadData(p.data);
			} else if (p.url) {// 后台异步处理
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
		 * 重新刷新数据
		 */
		reloadData: function(data) {
			this.removeAll(true);
			this.addRows(data);
		},
		/**
		 * 批量添加数据
		 */
		addRows: function(data) {
			var g = this;
			if (data) { // add by zengzx data返回为空时报错。2012年12月18日 17:18:38
				for (var i = 0; i < data.length; i++) {
					g.addRow(i, data[i]);
				}
			}
		},
		/**
		 * 加入一行
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
			// 第一行是否显示删除按钮并且不是是第一行 并且不是查看類型 並且isAddAndDel為true
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
			// 序号
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
				if (g.isRemoveAction !== false) {// 支持在事件里面设置此参数控制是否删除
					g.removeRow(n);
				}
			});
			g.tbody.append($tr);

			$tr.trigger('beforeAddRow', [rowNum, rowData, g]);
			for (var i = 0; i < p.colModel.length; i++) {
				var config = p.colModel[i];
				var emptyOption = config.emptyOption ? config.emptyOption : false;//选择首行空项
				var type = config.type ? config.type : p.defaultType;// 控件类型
				var tclass = config.tclass ? config.tclass : p.defaultClass;// 控件样式
				var name = config.name;
				var val = rowData ? rowData[name] : config.value;
				if (config.staticVal) {
					val = config.staticVal;
				}
				var cmpId = elId + "_cmp_" + name + rowNum;// 控件id
				var cmpName = p.objName + "[" + rowNum + "][" + name + "]";// 控件名称
				var $input;
				if (p.type == 'view' && config.type != 'hidden'
					&& config.type != 'file') {
					type = "statictext";
					config.tclass = "";
				}
				switch (type) {
					case 'select' :// 下拉选择
						$input = $("<select>");
						// 处理数据字典
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
						} else {// 处理静态数据
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
					case 'statictext' :// 静态文本
						if (!config.tclass) {
							tclass = "";// 静态文本不能用默认的样式
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
						// 如果需要提交值，动态创建一个隐藏域
						if (config.isSubmit) {
							var $h = $("<input type='hidden'>");
							$h.attr('name', cmpName);
							$h.val(oldHtml);
							$input.append($h);
						}
						break;
					case 'hidden' :// 隐藏域
						$input = $("<input type='hidden'>");
						break;
					case 'checkbox' :// 多选
						if (!config.tclass) {
							tclass = "";// 静态文本不能用默认的样式
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
					case 'textarea' :// 大文本框
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
					case 'file' :// 附件类型，需要依赖附件组件
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
							+ '" type="button" value="中止上传" onclick="cancelQueue(uploadfile);" disabled="disabled" /> <br />'
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
				// 加入初始值存储,提供变更时判断是否已经修改
				$input.data("oldVal", $input.val());
				if (config.validation) {
					$input.validation(config.validation);
				}

				// 一些特殊组件的处理
				// 日期控件处理
				if (type == 'date') {
					$input.click(function() {
						WdatePicker({
							dateFmt: 'yyyy-MM-dd'
						});
					});
				} else if (type == 'int') {// 整数,比如数量
					$input.keypress(function(event) {
						return event.keyCode >= 48 && event.keyCode <= 57;
					});
				} else if (type == 'float') {// 浮点型
					$input.keypress(function(event) {
						return (event.keyCode >= 48 && event.keyCode <= 57)
							|| event.keyCode == 46
							|| event.keyCode == 45;
					});
				} else if (type == 'money') {// 金额处理（千分位,小数点）
					$input.blur(function(event) {
					});
				}

				// 给控件加入属性
				$input.data("rowNum", rowNum);// 第几行
				$input.data("colNum", i);// 第几列
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

				/** * 移动一下事件的处理顺序，如果是金额的话优先处理千分位部分 ** */
				/** * Modify by kuangzw ** */
				// 其他的一些处理,如金额
				if (type == 'moneySix') { // 6位小数的金额
					$input.attr("etype", "moneySix");
					createFormatOnClick($input.attr("id"), null, null, null, 6);
				} else if (type == 'moneyAndNegative') { // 负数金额
					$input.attr("etype", "moneyAndNegative");
					createFormatOnClick($input.attr("id"), null, null, null, 2, true);
				} else if (type == 'money') {
					$input.attr("etype", "money");
					createFormatOnClick($input.attr("id"));
				} else if (type == 'file') {
					if (p.type != "view") {
						$input.removeAttr("name");
						createSWFUpload({
							"serviceType": config.serviceType,// 业务模块编码，一般取表名
							"serviceId": rowData && rowData.id ? rowData.id : $input.attr("id")
						}, {
							button_placeholder_id: swfId,
							cancelButtonId: btcId,
							fileListId: uflId,
							progressTarget: fpId,
							ajaxList: true,
							fileNamePre: cmpName // 文件前缀
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
				// 事件处理
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
		 * 处理列数据字典，拿到列中有设置数据字典编码的,组成编码字符串提交到后台获取数据字典项数据
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
								// 这里使用闭包的目的是为了在返回的function中使用闭包中的数据项
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
		 * 清空一行数据
		 */
		clearRowValue: function(rowNum) {
			var g = this, p = this.options;
			for (var i = 0; i < p.colModel.length; i++) {
				g.getCmpByRowAndCol(rowNum, p.colModel[i].name).val("");
			}
		},
		/**
		 * 获取某一行jquery对象
		 */
		getRowByRowNum: function(rowNum) {
			return $("tr[rownum='" + rowNum + "']", this.el);
		},
		/**
		 * 获取某行某列的控件对象
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
		 * 获取某列的控件对象集合
		 */
		getCmpByCol: function(colName) {
			var el = this.el;
			var elId = el.attr('id');
			var colId = elId + "_cmp_" + colName;
			// 如果这一列是隐藏域
			// 修复个比较严重的bug，colName如果是前面字符串与另外的colName一致，则找到了多条
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
		 * 获取被删除某列的控件对象集合
		 */
		getDelCmpByCol: function(colName) {
			var el = this.el;
			var elId = el.attr('id');
			return $("[id^='" + elId + "_cmp_" + colName + "']:hidden", el
				.find("tr:hidden"));
		},
		/**
		 * 移除所有行 isDel:是否真删除 true 则从界面删除元素
		 */
		removeAll: function(isDel) {
			var g = this, el = this.el;

			$(el).find("tbody").children().each(function() {
				var rowNum = $(this).attr("rowNum");
				if ($(this).is(":hidden")) {// 假删除的直接移除
					$(this).remove();
				} else {
					g.removeRow(rowNum, isDel);
				}
			});
			g.curRowNum = 0;
			g.curShowRowNum = 0;
		},
		/**
		 * 判断行是否被假删除
		 */
		isRowDel: function(rowNum) {
			var el = this.el, p = this.options;
			// 假删除
			return ($("#" + el.attr('id') + "_cmp_" + p.delTagName + rowNum).val() == 1);
		},
		/**
		 * 显示行
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
		 * 移除一行数据
		 */
		removeRow: function(rowNum, isDel) {
			var g = this, el = this.el, p = this.options;
			var $tr = $(el).find("tr[rowNum='" + rowNum + "']");
			var index = $tr.data("index");
			var rowData = $tr.data("rowData");
			g.curShowRowNum--;
			if (!isDel && rowData && rowData.id && !p.realDel) {
				// 假删除
				var elId = el.attr('id');
				var $delTag = $("<input type='hidden' id='" + elId + "_cmp_"
				+ p.delTagName + index + "' name='" + p.objName
				+ "[" + index + "][" + p.delTagName + "]' value='1' />");

				$tr.append($delTag);
				$tr.hide();
				$tr.trigger('removeRow', [rowNum, rowData, index, g]);
				//加入一个撤销样式处理,主要是为了解决删除行后，从表验证依然在的问题
				//2013-05-24 by kuangzw
				$tr.find("input").each(function(i, n) {
					$(this).attr("class", "");
				});
				$tr.find("select").each(function(i, n) {
					$(this).attr("class", "");
				});
			} else {
				g.curRowNum--;
				// rowNum代表实际的行数，删除及新增一行的时候会导致其他行改变此值 index则不会改变
				$tr.trigger('removeRow', [rowNum, rowData, index, g]);
				$tr.remove();
			}

			// 序列号处理
			g.processRowNum();
		},
		/**
		 * 处理序号
		 */
		processRowNum: function() {
			var el = this.el;
			var i = 1;
			$(el).find("td[type='rowNum']").each(function() {
				if ($(this).is(":hidden")) {// 假删除的直接移除

				} else {
					$(this).html(i++);
				}
			});
		},
		/**
		 * 获取当前行数,包括隐藏的
		 */
		getCurRowNum: function() {
			return this.curRowNum;
		},
		/**
		 * 获取当前行数,不隐藏的
		 */
		getCurShowRowNum: function() {
			return this.curShowRowNum;
		},
		/**
		 * 获取最大新增行数号
		 */
		getAllAddRowNum: function() {
			return this.allAddRowNum;
		},
		/**
		 * 动态显示/隐藏添加行按钮
		 */
		showAddBn: function() {
			this.addBn.show();
		},
		hideAddBn: function() {
			this.addBn.hide();
		},
		/**
		 * 摧毁组件
		 */
		remove: function() {
			this.el.empty();
			this.el.unbind();
			this.destroy();
		},
		/**
		 * 外部接口:动态设置默认值
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
		 * 设置列值，包括默认值与已经存在的行值
		 */
		setColValue: function(colName, val) {
			this.setConfigValue(colName, val);
			this.getCmpByCol(colName).val(val);
		},
		/**
		 * 设置某一行列值 isBlur:是否促发失焦属性，在一些金额类型上需要用到
		 */
		setRowColValue: function(rowNum, colName, val, isBlur) {
			var $cmp = this.getCmpByRowAndCol(rowNum, colName);
			$cmp.val(val);
			if (isBlur) {
				$cmp.trigger('blur');
			}
		},
		/**
		 * 获取某一行某一列组件id
		 */
		getRowColId: function(rowNum, colName) {
			return this.el.attr('id') + "_cmp_" + colName + rowNum;
		},
		/**
		 * 设置列值，包括默认值与已经存在的行值
		 */
		setParam: function(thisParam) {
			this.options.param = thisParam;
		},
		/**
		 * 获取表头
		 */
		getTitleObj: function() {
			return $("#" + this.el.attr('id') + "_cmp_table_title");
		}
	});
})(jQuery);