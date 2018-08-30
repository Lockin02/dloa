// / <reference path="../intellisense/jquery-1.2.6-vsdoc-cn.js" />
// / <reference path="../lib/blackbird.js" />
/**
 * 通用表格组件 
 * 具有功能：
 * 
 * @1.排序
 * @2.列拖动，拖拽
 * @3.显示隐藏列
 * @4.按钮
 * @5.右键菜单
 * @6.分页
 * @7.多选
 * 
 * 待完善功能
 * @1.右键菜单通过条件自动隐藏/显示
 * @2.对外提供的接口
 * @3.可编辑表格（考虑通过另外的扩展实现）
 * @4.树形表格（考虑通过另外的扩展实现）
 * @5.序号
 * @6.静态数据
 */
(function($) {
	// $.woo.importJs("js/thickbox.js");
	// $.woo.addCssStyle("js/thickbox.css");
	// t为渲染的jquery对象,p为传入的参数
	$.addFlex = function(t, p) {
		if (t.grid)
			return false; // 如果Grid已经存在则返回
		// 引用默认属性 深度镶套对象
		p = $.extend(true, {
			height : 350, // flexigrid插件的高度，单位为px
			width : 'auto', // 宽度值，auto表示根据每列的宽度自动计算
			striped : true, // 是否显示斑纹效果，默认是奇偶交互的形式
			novstripe : false,
			minwidth : 30, // 列的最小宽度
			minheight : 80, // 列的最小高度
			resizable : true, // 是否可伸缩
			url : false, // ajax方式对应的url地址
			method : 'POST', // 数据发送方式
			dataType : 'json', // 数据加载的类型，xml,json
			errormsg : '获取数据失败', // 错误提升信息
			nowrap : true, // 是否不换行

			// ------ 分页相关信息开始-------
			page : 1, // 默认当前页
			total : 1, // 总页面数
			pagestat : '显示记录从{from}到{to}，总数 {total} 条', // 显示当前页和总页面的样式
			usepager : true, // 是否分页
			rp : 15, // 每页默认的结果数
			rpOptions : [1, 10, 15, 20, 25, 40, 100], // 可选择设定的每页结果数
			useRp : true, // 是否可以动态设置每页显示的结果数
			dataField : 'collection',// 返回的数据中表示列表的属性名
			totalField : 'totalSize',// 返回的数据中表示列表总数属性名
			// ------ 分页相关信息结束-------

			title : '', // 表格标题
			isTitle : true, // 是否包含标题
			procmsg : '正在处理数据，请稍候 ...', // 正在处理的提示信息
			query : '', // 搜索查询的条件
			qtype : '', // 搜索查询的类别
			qop : "Eq", // 搜索的操作符
			nomsg : '没有符合条件的记录存在', // 无结果的提示信息
			minColToggle : 1, // 最小能被隐藏的列数
			showToggleBtn : true, // 是否允许显示隐藏列
			hideOnSubmit : true, // 显示遮盖
			showTableToggleBtn : true, // 表格收缩按钮
			autoload : true, // 自动加载
			blockOpacity : 0.5, // 透明度设置
			onToggleCol : false, // 当在行之间转换时
			onChangeSort : false, // 当改变排序时
			onSuccess : false, // 成功后执行
			onSubmit : false, // 提交的时候调用自定义的计算函数
			showcheckbox : true, // 是否显示checkbox
			rowhandler : false, // 是否启用行的扩展事情功能
			rowbinddata : false,
			extParam : {},
			gridClass : "bbit-grid",// 表格样式
			onrowchecked : false,// 行选中的时候触发事件

			// ------ 扩展属性开始-------
			/**
			 * 业务对象名称
			 */
			boName : '',
			/**
			 * 传入业务对象model,如customer_customer_customer
			 */
			model : '',
			/**
			 * 调用的后台方法名
			 */
			action : 'pageJson',
			/**
			 * 是否显示右键菜单，如果为flase，则右键菜单失效
			 * 
			 * @type Boolean
			 */
			isRightMenu : true,
			/**
			 * 是否显示工具栏
			 * 
			 * @type Boolean
			 */
			isToolBar : true,
			/**
			 * 是否显示添加按钮/菜单
			 * 
			 * @type Boolean
			 */
			isAddAction : true,
			/**
			 * 是否显示查看按钮/菜单
			 * 
			 * @type Boolean
			 */
			isViewAction : false,
			/**
			 * 是否显示修改按钮/菜单
			 * 
			 * @type Boolean
			 */
			isEditAction : true,
			/**
			 * 是否显示删除按钮/菜单
			 * 
			 * @type Boolean
			 */
			isDelAction : true,
			/**
			 * 是否显示高级搜索按钮（待扩展）
			 * 
			 * @type Boolean
			 */
			isAdvanceSearch : true,
			/**
			 * 是否显示导出excel按钮
			 * 
			 * @type Boolean
			 */
			isToExcel : false,
			/**
			 * 表单默认宽度
			 */
			formWidth : 700,
			/**
			 * 表单默认宽度
			 */
			formHeight : 400,
			/**
			 * 新增表单属性配置
			 */
			toAddConfig : {
				/**
				 * 默认点击新增按钮触发事件
				 */
				toAddFn : function() {
					var w = p.toAddConfig.formWidth
							? p.toAddConfig.formWidth
							: p.formWidth;
					var h = p.toAddConfig.formHeight
							? p.toAddConfig.formHeight
							: p.formHeight;
					showThickboxWin("?model="
							+ p.model
							+ "&action="
							+ p.toAddConfig.action
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ h + "&width=" + w);
				},
				/**
				 * 新增表单调用的后台方法
				 */
				action : 'toAdd',
				/**
				 * 新增表单默认宽度
				 */
				formWidth : 0,
				/**
				 * 新增表单默认高度
				 */
				formHeight : 0
			},
			/**
			 * 查看表单属性配置
			 */
			toViewConfig : {
				/**
				 * 默认点击查看按钮触发事件
				 */
				toEditFn : function() {
					var w = p.toViewConfig.formWidth
							? p.toViewConfig.formWidth
							: p.formWidth;
					var h = p.toViewConfig.formHeight
							? p.toViewConfig.formHeight
							: p.formHeight;
					var rowObj = g.getSelectedRow();
					if (rowObj) {
						showThickboxWin("?model="
								+ p.model
								+ "&action="
								+ p.toViewConfig.action
								+ "&id="
								+ rowObj.data('data').id
								+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
								+ h + "&width=" + w);
					} else {
						alert('请选择一行记录！');
					}
				},
				/**
				 * 加载表单默认调用的后台方法
				 */
				action : 'init',
				/**
				 * 查看表单默认宽度
				 */
				formWidth : 0,
				/**
				 * 查看表单默认高度
				 */
				formHeight : 0
			},
			/**
			 * 编辑表单属性配置
			 */
			toEditConfig : {
				/**
				 * 默认点击编辑按钮触发事件
				 */
				toEditFn : function() {
					var w = p.toEditConfig.formWidth
							? p.toEditConfig.formWidth
							: p.formWidth;
					var h = p.toEditConfig.formHeight
							? p.toEditConfig.formHeight
							: p.formHeight;
					var rowObj = g.getSelectedRow();
					if (rowObj) {
						showThickboxWin("?model="
								+ p.model
								+ "&action="
								+ p.toEditConfig.action
								+ "&id="
								+ rowObj.data('data').id
								+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
								+ h + "&width=" + w);
					} else {
						alert('请选择一行记录！');
					}
				},
				/**
				 * 加载表单默认调用的后台方法
				 */
				action : 'init',
				/**
				 * 编辑表单默认宽度
				 */
				formWidth : 0,
				/**
				 * 编辑表单默认高度
				 */
				formHeight : 0
			},
			/**
			 * 删除属性配置
			 */
			toDelConfig : {
				/**
				 * 默认点击删除按钮触发事件
				 */
				toDelFn : function() {
					var rowIds = g.getCheckedRowIds();
					if (rowIds[0]) {
						if (window.confirm("确认要删除?")) {
							$.ajax({
										type : "POST",
										url : "?model=" + p.model + "&action="
												+ p.toDelConfig.action,
										data : {
											id : g.getCheckedRowIds()
													.toString()
											// 转换成以,隔开方式
										},
										success : function(msg) {
											if (msg == 1) {
												t.grid.populate();
												alert('删除成功！');
											}
										}
									});
						}
					} else {
						alert('请选择一行记录！');
					}
				},
				/**
				 * 删除默认调用的后台方法
				 */
				action : 'ajaxdeletes'
			}
				// ------ 扩展属性结束-------

		}, p);

		$(t).show() // show if hidden
				.attr({
							cellPadding : 0,
							cellSpacing : 0,
							border : 0
						}) // remove padding and spacing
				.removeAttr('width'); // remove width properties

		// create grid class
		var g = {
			hset : {},
			rePosDrag : function() {

				var cdleft = 0 - this.hDiv.scrollLeft;
				if (this.hDiv.scrollLeft > 0)
					cdleft -= Math.floor(p.cgwidth / 2);

				$(g.cDrag).css({
							top : g.hDiv.offsetTop + 1
						});
				var cdpad = this.cdpad;

				$('div', g.cDrag).hide();
				// update by xuanye ,避免jQuery :visible 无效的bug
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

							$('div:eq(' + n + ')', g.cDrag).css({
										'left' : cdpos + 'px'
									}).show();

							cdleft = cdpos;
							i++;
						});

			},
			fixHeight : function(newH) {
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
							height : newH,
							marginBottom : (newH * -1)
						});

				var hrH = g.bDiv.offsetTop + newH;
				if (p.height != 'auto' && p.resizable)
					hrH = g.vDiv.offsetTop;
				$(g.rDiv).css({
							height : hrH
						});

			},
			dragStart : function(dragtype, e, obj) { // default drag function
				// start

				if (dragtype == 'colresize') // column resize
				{
					$(g.nDiv).hide();
					$(g.nBtn).hide();
					var n = $('div', this.cDrag).index(obj);
					// var ow = $('th:visible div:eq(' + n + ')',
					// this.hDiv).width();
					var ow = $('th:visible:eq(' + n + ') div', this.hDiv)
							.width();
					$(obj).addClass('dragging').siblings().hide();
					$(obj).prev().addClass('dragging').show();

					this.colresize = {
						startX : e.pageX,
						ol : parseInt(obj.style.left),
						ow : ow,
						n : n
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
						h : p.height,
						sy : e.pageY,
						w : p.width,
						sx : e.pageX,
						hgo : hgo
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
								position : 'absolute',
								float : 'left',
								display : 'none',
								textAlign : obj.align
							});
					$('body').append(this.colCopy);
					$(this.cDrag).hide();

				}

				$('body').noSelect();

			},
			reSize : function() {
				this.gDiv.style.width = p.width;
				this.bDiv.style.height = p.height;
			},
			dragMove : function(e) {

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
					if ((newH > p.minheight || p.height < p.minheight)
							&& !v.hgo) {
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
								top : e.pageY + 10,
								left : e.pageX + 20,
								display : 'block'
							});
				}

			},
			dragEnd : function() {
				if (this.colresize) {
					var n = this.colresize.n;
					var nw = this.colresize.nw;
					// $('th:visible div:eq(' + n + ')', this.hDiv).css('width',
					// nw);
					$('th:visible:eq(' + n + ') div', this.hDiv).css('width',
							nw);

					$('tr', this.bDiv).each(function() {
						// $('td:visible div:eq(' + n + ')', this).css('width',
						// nw);
						$('td:visible:eq(' + n + ') div', this)
								.css('width', nw);
					});
					this.hDiv.scrollLeft = this.bDiv.scrollLeft;
					$('div:eq(' + n + ')', this.cDrag).siblings().show();
					$('.dragging', this.cDrag).removeClass('dragging');
					this.rePosDrag();
					this.fixHeight();
					this.colresize = false;
				} else if (this.vresize) {
					this.vresize = false;
				} else if (this.colCopy) {
					$(this.colCopy).remove();
					if (this.dcolt != null) {
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
			toggleCol : function(cid, visible) {
				var ncol = $("th[axis='col" + cid + "']", this.hDiv)[0];
				var n = $('thead th', g.hDiv).index(ncol);
				var cb = $('input[value=' + cid + ']', g.nDiv)[0];
				if (visible == null) {
					visible = ncol.hide;
				}
				if ($('input:checked', g.nDiv).length < p.minColToggle
						&& !visible)
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
				$('tbody tr', t).each(function() {
							if (visible)
								$('td:eq(' + n + ')', this).show();
							else
								$('td:eq(' + n + ')', this).hide();
						});
				this.rePosDrag();
				if (p.onToggleCol)
					p.onToggleCol(cid, visible);
				return visible;
			},
			switchCol : function(cdrag, cdrop) { // switch columns
				$('tbody tr', t).each(function() {
					if (cdrag > cdrop)
						$('td:eq(' + cdrop + ')', this).before($('td:eq('
										+ cdrag + ')', this));
					else
						$('td:eq(' + cdrop + ')', this).after($('td:eq('
										+ cdrag + ')', this));
				});
				// switch order in nDiv
				if (cdrag > cdrop)
					$('tr:eq(' + cdrop + ')', this.nDiv).before($('tr:eq('
									+ cdrag + ')', this.nDiv));
				else
					$('tr:eq(' + cdrop + ')', this.nDiv).after($('tr:eq('
									+ cdrag + ')', this.nDiv));
				if ($.browser.msie && $.browser.version < 7.0)
					$('tr:eq(' + cdrop + ') input', this.nDiv)[0].checked = true;
				this.hDiv.scrollLeft = this.bDiv.scrollLeft;
			},
			scroll : function() {
				this.hDiv.scrollLeft = this.bDiv.scrollLeft;
				this.rePosDrag();
			},
			hideLoading : function() {
				$('.pReload', this.pDiv).removeClass('loading');
				if (p.hideOnSubmit)
					$(g.block).remove();
				$('.pPageStat', this.pDiv).html(p.errormsg);
				this.loading = false;
			},
			addData : function(data) { // parse data
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
					$('tr, a, td, div', t).unbind();
					$(t).empty();
					p.pages = 1;
					p.page = 1;
					this.buildpager();
					$('.pPageStat', this.pDiv).html(p.nomsg);
					if (p.hideOnSubmit)
						$(g.block).remove();
					return false;
				}

				p.pages = Math.ceil(p.total / p.rp);
				p.page = data.page;

				if (p.usepager) {
					this.buildpager();
				}

				// var ths = $('thead tr:first th', g.hDiv);
				var ths = $('thead tr:first', g.hDiv);
				var thsdivs = $('thead tr:first th div', g.hDiv);
				var tbhtml = [];
				tbhtml.push("<tbody>");
				if (p.dataType == 'json') {
					if (data[p.dataField] != null) {
						// 循环行
						$.each(data[p.dataField], function(i, row) {
							tbhtml.push("<tr id='", "row", row.id, "'");

							if (i % 2 && p.striped) {
								tbhtml.push(" class='erow'");
							}
							if (p.rowbinddata) {
								tbhtml.push("ch='", row.cell.join("_FG$SP_"),
										"'");
							}
							tbhtml.push(">");
							var trid = row.id;
							// 循环列
							$(ths).children().each(function(j) {
								var tddata = "";
								var tdclass = "";
								tbhtml.push("<td align='", this.align, "'");
								var idx = $(this).attr('axis').substr(3);
								var namex = $(this).attr('abbr');
								if (p.sortname && p.sortname == namex) {
									tdclass = 'sorted';
								}
								if (this.hide) {
									tbhtml.push(" style='display:none;'");
								}
								var width = thsdivs[j].style.width;
								var div = [];
								div.push("<div style='text-align:", this.align,
										";width:", width, ";");
								if (p.nowrap == false) {
									div.push("white-space:normal");
								}
								div.push("'>");
								if (idx == "-1") { // checkbox
									div.push("<input type='checkbox' id='chk_",
											row.id,
											"' class='itemchk' value='",
											row.id, "'/>");
									if (tdclass != "") {
										tdclass += " chboxtd";
									} else {
										tdclass += "chboxtd";
									}
								} else {
									// var divInner = row.cell[idx] || "&nbsp;";
									var divInner = row[namex] || "&nbsp;";
									if (this.process) {
										divInner = this.process(divInner, row);
									}
									div.push(divInner);
								}
								div.push("</div>");
								if (tdclass != "") {
									tbhtml.push(" class='", tdclass, "'");
								}
								// 把数据放到数组后进行连接，可以提高效率
								tbhtml.push(">", div.join(""), "</td>");
							});
							tbhtml.push("</tr>");
						});
					}

				}
				tbhtml.push("</tbody>");
				$(t).html(tbhtml.join(""));

				// 把数据采用data函数存储到每一行jquery对象中 add by chengl
				$.each(data[p.dataField], function(i, row) {
							$("tr[id='row" + row.id + "']", t)
									.data('data', row);
						});

				// this.rePosDrag();
				this.addRowProp();
				if (p.onSuccess)
					p.onSuccess();
				if (p.hideOnSubmit)
					$(g.block).remove(); // $(t).show();
				this.hDiv.scrollLeft = this.bDiv.scrollLeft;
				if ($.browser.opera)
					$(t).css('visibility', 'visible');

			},
			changeSort : function(th) { // change sortorder
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

				if (p.onChangeSort)
					p.onChangeSort(p.sortname, p.sortorder);
				else
					this.populate();

			},
			buildpager : function() { // rebuild pager based on new properties

				$('.pcontrol input', this.pDiv).val(p.page);
				$('.pcontrol span', this.pDiv).html(p.pages);

				var r1 = (p.page - 1) * p.rp + 1;
				var r2 = r1 + p.rp - 1;

				if (p.total < r2)
					r2 = p.total;

				var stat = p.pagestat;

				stat = stat.replace(/{from}/, r1);
				stat = stat.replace(/{to}/, r2);
				stat = stat.replace(/{total}/, p.total);
				$('.pPageStat', this.pDiv).html(stat);
			},
			populate : function() { // get latest data
				// log.trace("开始访问数据源");
				if (this.loading)
					return true;
				if (p.onSubmit) {
					var gh = p.onSubmit();
					if (!gh)
						return false;
				}
				this.loading = true;
				if (!p.url)
					return false;
				$('.pPageStat', this.pDiv).html(p.procmsg);
				$('.pReload', this.pDiv).addClass('loading');
				$(g.block).css({
							top : g.bDiv.offsetTop
						});
				if (p.hideOnSubmit)
					$(this.gDiv).prepend(g.block); // $(t).hide();
				if ($.browser.opera)
					$(t).css('visibility', 'hidden');
				if (!p.newp)
					p.newp = 1;
				if (p.page > p.pages)
					p.page = p.pages;
				// var param = {page:p.newp, rp: p.rp, sortname: p.sortname,
				// sortorder: p.sortorder, query: p.query, qtype: p.qtype};
				var param = [{
							name : 'page',
							value : p.newp
						}, {
							name : 'pageSize',
							value : p.rp
						}, {
							name : 'sort',
							value : p.sortname
						}, {
							name : 'dir',
							value : p.sortorder
						}, {
							name : p.qtype,
							value : p.query
							// 搜索以key value方式传到后台 modify by
						// chengl
					}	, {
							name : 'qop',
							value : p.qop
							// 条件类型 eq like <>等等
					}];
				// param = jQuery.extend(param, p.extParam);
				if (p.extParam) {
					for (var pi = 0; pi < p.extParam.length; pi++)
						param[param.length] = p.extParam[pi];
				}

				$.ajax({
							type : p.method,
							url : p.url,
							data : param,
							dataType : p.dataType,
							success : function(data) {
								if (data != null && data.error != null) {
									if (p.onError) {
										p.onError(data);
										g.hideLoading();
									}
								} else {
									g.addData(data);
								}
							},
							error : function(data) {
								try {
									if (p.onError) {
										p.onError(data);
									} else {
										alert("获取数据发生异常;")
									}
									g.hideLoading();
								} catch (e) {
								}
							}
						});
			},
			doSearch : function() {
				var queryType = $('select[name=qtype]', g.sDiv).val();
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
				p.query = $('input[name=q]', g.sDiv).val();
				// 添加验证代码
				if (p.query != "" && p.searchitems && index >= 0
						&& p.searchitems.length > index) {
					if (p.searchitems[index].reg) {
						if (!p.searchitems[index].reg.test(p.query)) {
							alert("你的输入不符合要求!");
							return;
						}
					}
				}
				p.newp = 1;
				this.populate();
			},
			changePage : function(ctype) { // change page

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
					this.populate();

			},
			cellProp : function(n, ptr, pth) {
				var tdDiv = document.createElement('div');
				if (pth != null) {
					if (p.sortname == $(pth).attr('abbr') && p.sortname) {
						this.className = 'sorted';
					}
					$(tdDiv).css({
								textAlign : pth.align,
								width : $('div:first', pth)[0].style.width
							});
					if (pth.hide)
						$(this).css('display', 'none');
				}
				if (p.nowrap == false)
					$(tdDiv).css('white-space', 'normal');

				if (this.innerHTML == '')
					this.innerHTML = '&nbsp;';

				// tdDiv.value = this.innerHTML; //store preprocess value
				tdDiv.innerHTML = this.innerHTML;

				var prnt = $(this).parent()[0];
				var pid = false;
				if (prnt.id)
					pid = prnt.id.substr(3);
				if (pth != null) {
					if (pth.process) {
						pth.process(tdDiv, pid);
					}
				}

				$(this).empty().append(tdDiv).removeAttr('width'); // wrap
				// content
				// add editable event here 'dblclick',如果需要可编辑在这里添加可编辑代码
			},
			addCellProp : function() {
				var $gF = this.cellProp;

				$('tbody tr td', g.bDiv).each(function() {
							var n = $('td', $(this).parent()).index(this);
							var pth = $('th:eq(' + n + ')', g.hDiv).get(0);
							var ptr = $(this).parent();
							$gF.call(this, n, ptr, pth);
						});
				$gF = null;
			},
			/**
			 * 获取checkbox选中的行
			 */
			getCheckedRows : function() {
				return $(":checkbox:checked", g.bDiv);
			},
			/**
			 * 获取checkbox选中的行id
			 */
			getCheckedRowIds : function() {
				var ids = [];
				$(":checkbox:checked", g.bDiv).each(function() {
							ids.push($(this).val());
						});
				return ids;
			},
			/**
			 * 选中所有checkbox
			 */
			checkAll : function() {
				var checked = $(":checkbox:[checked!=true]", g.bDiv);
				if (checked.size() > 0) {
					checked.trigger('click', [true]);
				}
			},
			/**
			 * 清除所有选中的checkbox
			 */
			clearCheckAll : function() {
				var checked = $(":checkbox:checked", g.bDiv);
				if (checked.size() > 0) {
					checked.trigger('click', [true]);
				}
				// g.selectedRow = null;
			},
			checkAllOrNot : function() {
				var ischeck = $(this).attr("checked");
				if (ischeck) {
					g.checkAll();
				} else {
					g.clearCheckAll();
				}
			},
			/**
			 * 单选选中的行
			 */
			getSelectedRow : function() {
				if ($(":checkbox:checked", g.bDiv).size() == 0) {
					return null;
				}
				return g.selectedRow;
			},
			getCellDim : function(obj) // get cell prop for editable event
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
					ht : ht,
					wt : wt,
					top : top,
					left : left,
					pdl : pdl,
					pdt : pdt,
					pht : pht,
					pwt : pwt
				};
			},
			/**
			 * 添加行属性功能，包括点击选择，移入移出样式切换,右键菜单等
			 */
			addRowProp : function() {
				// 如果由trigger导致的点击，先执行click事件，再执行默认事件
				// 如果单独点击checkbox，先执行默认事件，再执行click事件
				$(":checkbox.itemchk", g.bDiv).bind('click',
						function(e, isTrigger) {
							// return false;
							var ptr = $(this).parents('tr');
							if ((!isTrigger && this.checked)
									|| (isTrigger && !this.checked)) {
								ptr.addClass("trSelected");
							} else {
								ptr.removeClass("trSelected");
							}
							if (p.onrowchecked) {
								p.onrowchecked.call(this);
							}
							g.selectedRow = $(this).parents("tr");
							e.stopPropagation();// 阻止冒泡
						});
				// 单击行checkbox自动选择
				var row = $("tr[id^='row']", g.bDiv);
				row.live('click contextmenu', function(e) {
							g.clearCheckAll();
							var checkbox = $(this).find(":checkbox");
							checkbox.trigger('click', [true]);
							g.selectedRow = $(this);// 存储最后一条选择的记录
							if (e.type == 'click') {// 左键点击隐藏所有菜单
								$.woo.yxmenu.killAllMenus();
							}
							return false;// 阻止冒泡及默认行为
						});
				if (p.isRightMenu) {
					this.createRightMenu(row);
				}
				if (p.rowhandler) {
					p.rowhandler(this);
				}
				if ($.browser.msie && $.browser.version < 7.0) {
					row.hover(function() {
								$(this).addClass('trOver');
							}, function() {
								$(this).removeClass('trOver');
							});
				}
			},
			/**
			 * 创建表格右键菜单
			 */
			createRightMenu : function(row) {
				var menus = [];
				if (p.isViewAction) {
					menus.push({
								text : '查看',
								icon : 'view',
								action : p.toViewConfig.toViewFn
							});
				};
				if (p.isEditAction) {
					menus.push({
								text : '编辑',
								icon : 'edit',
								action : p.toEditConfig.toEditFn
							});
				};
				if (p.isDelAction) {
					menus.push({
								text : '删除',
								icon : 'delete',
								action : p.toDelConfig.toDelFn
							});
				};
				if (p.menusEx) {
					menus = menus.concat(p.menusEx);
				}
				row.yxmenu({
							type : 'rclick',
							isBubble : true,// 是否允许菜单冒泡
							width : 100,
							items : menus
						});
			},
			pager : 0
		};

		// create model if any
		if (p.colModel) {
			thead = document.createElement('thead');
			tr = document.createElement('tr');
			// p.showcheckbox ==true;
			if (p.showcheckbox) {
				var cth = jQuery('<th/>');
				var cthch = jQuery('<input type="checkbox"/>');
				cthch.addClass("noborder");
				cth.addClass("cth").attr({
							'axis' : "col-1",
							width : "15",
							"isch" : true
						}).append(cthch);
				$(tr).append(cth);
			}
			for (i = 0; i < p.colModel.length; i++) {
				var cm = p.colModel[i];
				var th = document.createElement('th');

				th.innerHTML = cm.display;

				// if (cm.name && cm.sortable) modify by chengl
				$(th).attr('abbr', cm.name);

				// th.idx = i;
				$(th).attr('axis', 'col' + i);

				if (cm.align)
					th.align = cm.align;

				$(th).attr('width', cm.width ? cm.width : 100);

				if (cm.hide) {
					th.hide = true;
				}
				if (cm.toggle != undefined) {
					th.toggle = cm.toggle;
				}
				if (cm.process) {
					th.process = cm.process;
				}

				$(tr).append(th);
			}
			$(thead).append(tr);
			$(t).prepend(thead);
		} // end if p.colmodel

		// init divs
		g.gDiv = document.createElement('div'); // create global container 全局容器
		g.mDiv = document.createElement('div'); // create title container 标题容器
		g.hDiv = document.createElement('div'); // create header container 表格列容器
		g.bDiv = document.createElement('div'); // create body container body主容器
		g.vDiv = document.createElement('div'); // create grip 表格拉动容器
		g.rDiv = document.createElement('div'); // create horizontal resizer
		// 拉动列容器
		g.cDrag = document.createElement('div'); // create column drag 拖拽列容器
		g.block = document.createElement('div'); // creat blocker 进度条？
		g.nDiv = document.createElement('div'); // create column show/hide popup
		// 选择列菜单
		g.nBtn = document.createElement('div'); // create column show/hide
		// button 选择列菜单按钮
		g.iDiv = document.createElement('div'); // create editable layer
		g.tDiv = document.createElement('div'); // create toolbar //工具栏
		g.sDiv = document.createElement('div'); // 快速搜索容器

		if (p.usepager)
			g.pDiv = document.createElement('div'); // create pager container
		g.hTable = document.createElement('table');

		// set gDiv
		g.gDiv.className = p.gridClass;
		if (p.width != 'auto')
			g.gDiv.style.width = p.width + 'px';

		// add conditional classes
		if ($.browser.msie)
			$(g.gDiv).addClass('ie');

		if (p.novstripe)
			$(g.gDiv).addClass('novstripe');

		$(t).before(g.gDiv);
		$(g.gDiv).append(t);

		// set toolbar
		if (p.isToolBar == true) {
			var buttons = [];
			g.tDiv.className = 'tDiv';
			var tDiv2 = document.createElement('div');
			tDiv2.className = 'tDiv2';

			if (p.isAddAction) {
				buttons.push({
							name : 'Add',
							text : "新增",
							icon : 'add',
							action : p.toAddConfig.toAddFn
						});
			}
			if (p.isViewAction) {
				buttons.push({
							name : 'View',
							text : "查看",
							icon : 'view',
							action : p.toViewConfig.toViewFn
						});
			}
			if (p.isEditAction) {
				buttons.push({
							name : 'Edit',
							text : "编辑",
							icon : 'edit',
							action : p.toEditConfig.toEditFn
						});
			}
			if (p.isDelAction) {
				buttons.push({
							name : 'Delete',
							text : "删除",
							icon : 'delete',
							action : p.toDelConfig.toDelFn
						});
			}
			if (p.buttonsEx) {
				buttons = buttons.concat(p.buttonsEx);
			}
			if (buttons) {
				for (i = 0; i < buttons.length; i++) {
					var btn = buttons[i];
					if (!btn.separator) {
						var btnDiv = document.createElement('div');
						btnDiv.className = 'fbutton';
						btnDiv.innerHTML = "<div><span>" + btn.text
								+ "</span></div>";
						if (btn.title) {
							btnDiv.title = btn.title;
						}
						if (btn.icon)
							$('span', btnDiv).addClass(btn.icon);
						btnDiv.action = btn.action;
						btnDiv.name = btn.name;
						if (btn.action) {
							$(btnDiv).click(function() {
										this.action(this.name, g.gDiv);
									});
						}
						$(tDiv2).append(btnDiv);
						if ($.browser.msie && $.browser.version < 7.0) {
							$(btnDiv).hover(function() {
										$(this).addClass('fbOver');
									}, function() {
										$(this).removeClass('fbOver');
									});
						}

					} else {
						$(tDiv2).append("<div class='btnseparator'></div>");
					}
				}
				$(g.tDiv).append(tDiv2);
				$(g.tDiv).append("<div style='clear:both'></div>");
				$(g.gDiv).prepend(g.tDiv);
			}
		}
		// set hDiv
		g.hDiv.className = 'hDiv';

		$(t).before(g.hDiv);

		// set hTable
		g.hTable.cellPadding = 0;
		g.hTable.cellSpacing = 0;
		$(g.hDiv).append('<div class="hDivBox"></div>');
		$('div', g.hDiv).append(g.hTable);
		var thead = $("thead:first", t).get(0);
		if (thead)
			$(g.hTable).append(thead);
		thead = null;

		if (!p.colmodel)
			var ci = 0;

		// setup thead
		$('thead tr:first th', g.hDiv).each(function() {
			var thdiv = document.createElement('div');
			if ($(this).attr('abbr')) {
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

			$(thdiv).css({
						textAlign : this.align,
						width : this.width + 'px'
					});
			thdiv.innerHTML = this.innerHTML;

			$(this).empty().append(thdiv).removeAttr('width');
			if (!$(this).attr("isch")) {
				$(this).mousedown(function(e) {
							g.dragStart('colMove', e, this);
						}).hover(function() {

					if (!g.colresize && !$(this).hasClass('thMove')
							&& !g.colCopy)
						$(this).addClass('thOver');

					if ($(this).attr('abbr') != p.sortname && !g.colCopy
							&& !g.colresize && $(this).attr('abbr'))
						$('div', this).addClass('s' + p.sortorder);
					else if ($(this).attr('abbr') == p.sortname && !g.colCopy
							&& !g.colresize && $(this).attr('abbr')) {
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
						var thsa = $('th:visible', g.hDiv);
						var nv = -1;
						for (var i = 0, j = 0, l = thsa.length; i < l; i++) {
							if ($(thsa[i]).css("display") != "none") {
								if (thsa[i] == this) {
									nv = j;
									break;
								}
								j++;
							}
						}
						// var nv = $('th:visible', g.hDiv).index(this);
						var onl = parseInt($('div:eq(' + nv + ')', g.cDrag)
								.css('left'));
						var nw = parseInt($(g.nBtn).width())
								+ parseInt($(g.nBtn).css('borderLeftWidth'));
						nl = onl - nw + Math.floor(p.cgwidth / 2);

						$(g.nDiv).hide();
						$(g.nBtn).hide();

						$(g.nBtn).css({
									'left' : nl,
									top : g.hDiv.offsetTop
								}).show();

						var ndw = parseInt($(g.nDiv).width());

						$(g.nDiv).css({
									top : g.bDiv.offsetTop
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

		// set bDiv
		g.bDiv.className = 'bDiv';
		$(t).before(g.bDiv);
		$(g.bDiv).css({
					height : (p.height == 'auto') ? 'auto' : p.height + "px"
				}).scroll(function(e) {
					g.scroll();
				}).append(t);

		if (p.height == 'auto') {
			$('table', g.bDiv).addClass('autoht');
		}

		// add td properties modify by chengl 暂时不知道作用，注释
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

			g.cdpad += (isNaN(parseInt($('div', cdcol).css('borderLeftWidth')))
					? 0
					: parseInt($('div', cdcol).css('borderLeftWidth')));
			g.cdpad += (isNaN(parseInt($('div', cdcol).css('borderRightWidth')))
					? 0
					: parseInt($('div', cdcol).css('borderRightWidth')));
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
						top : -hdheight + 'px'
					});

			$('thead tr:first th', g.hDiv).each(function() {
						var cgDiv = document.createElement('div');
						$(g.cDrag).append(cgDiv);
						if (!p.cgwidth)
							p.cgwidth = $(cgDiv).width();
						$(cgDiv).css({
									height : cdheight + hdheight
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
											$(this).removeClass('dragging');
									});
						}
					});

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
					}).html('<span></span>').css('height', $(g.gDiv).height());
			if ($.browser.msie && $.browser.version < 7.0) {
				$(g.rDiv).hover(function() {
							$(this).addClass('hgOver');
						}, function() {
							$(this).removeClass('hgOver');
						});
			}
			$(g.gDiv).append(g.rDiv);
		}

		// add pager
		if (p.usepager) {
			g.pDiv.className = 'pDiv';
			g.pDiv.innerHTML = '<div class="pDiv2"></div>';
			$(g.bDiv).after(g.pDiv);
			var html = '<div class="pGroup"><div class="pFirst pButton" title="转到第一页"><span></span></div><div class="pPrev pButton" title="转到上一页"><span></span></div> </div><div class="btnseparator"></div> <div class="pGroup"><span class="pcontrol">当前 <input type="text" size="1" value="1" /> ,总页数 <span> 1 </span></span></div><div class="btnseparator"></div><div class="pGroup"> <div class="pNext pButton" title="转到下一页"><span></span></div><div class="pLast pButton" title="转到最后一页"><span></span></div></div><div class="btnseparator"></div><div class="pGroup"> <div class="pReload pButton" title="刷新"><span></span></div> </div> <div class="btnseparator"></div><div class="pGroup"><span class="pPageStat"></span></div>';
			$('div', g.pDiv).html(html);

			$('.pReload', g.pDiv).click(function() {
						g.populate();
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
				for (var nx = 0; nx < p.rpOptions.length; nx++) {
					if (p.rp == p.rpOptions[nx])
						sel = 'selected="selected"';
					else
						sel = '';
					opt += "<option value='" + p.rpOptions[nx] + "' " + sel
							+ " >" + p.rpOptions[nx] + "&nbsp;&nbsp;</option>";
				};
				$('.pDiv2', g.pDiv)
						.prepend("<div class='pGroup'>每页 <select name='rp'>"
								+ opt
								+ "</select>条</div> <div class='btnseparator'></div>");
				$('select', g.pDiv).change(function() {
							if (p.onRpChange)
								p.onRpChange(+this.value);
							else {
								p.newp = 1;
								p.rp = +this.value;
								g.populate();
							}
						});
			}

			// add search button
			if (p.searchitems) {
				$('.pDiv2', g.pDiv)
						.prepend("<div class='pGroup'> <div class='pSearch pButton'><span></span></div> </div>  <div class='btnseparator'></div>");
				$('.pSearch', g.pDiv).click(function() {
					$(g.sDiv).slideToggle('fast', function() {
						$('.sDiv:visible input:first', g.gDiv).trigger('focus');
					});
				});
				// add search box
				g.sDiv.className = 'sDiv';

				sitems = p.searchitems;

				var sopt = "";
				var op = "Eq";
				for (var s = 0; s < sitems.length; s++) {
					if (p.qtype == '' && sitems[s].isdefault == true) {
						p.qtype = sitems[s].name;
						sel = 'selected="selected"';
					} else
						sel = '';
					// 这里以后可以扩展下拉选择搜索模式，like eq > <等
					if (sitems[s].operater == "Like") {
						op = "Like";
					} else {
						op = "Eq";
					}
					sopt += "<option value='" + sitems[s].name + "$" + op + "$"
							+ s + "' " + sel + " >" + sitems[s].display
							+ "&nbsp;&nbsp;</option>";
				}

				if (p.qtype == '')
					p.qtype = sitems[0].name;

				$(g.sDiv)
						.append("<div class='sDiv2'>快速检索：<input type='text' size='30' name='q' class='qsbox' /> <select name='qtype'>"
								+ sopt
								+ "</select><input type='button' name='qsearchbtn' value='搜索' /> <input type='button' name='qclearbtn' value='清空' /></div>");

				$('input[name=q],select[name=qtype]', g.sDiv).keydown(
						function(e) {
							if (e.keyCode == 13)
								g.doSearch();
						});
				$('input[name=qsearchbtn]', g.sDiv).click(function() {
							g.doSearch();
						});
				$('input[name=qclearbtn]', g.sDiv).click(function() {
							$('input[name=q]', g.sDiv).val('');
							p.query = '';
							g.doSearch();
						});
				$(g.bDiv).after(g.sDiv);

			}

		}
		$(g.pDiv, g.sDiv).append("<div style='clear:both'></div>");

		// add title
		if (p.isTitle) {
			p.title = p.title ? p.title : p.boName + "信息";
			g.mDiv.className = 'mDiv';
			g.mDiv.innerHTML = '<div class="ftitle">' + p.title + '</div>';
			$(g.gDiv).prepend(g.mDiv);
			if (p.showTableToggleBtn) {
				$(g.mDiv)
						.append('<div class="ptogtitle" title="Minimize/Maximize Table"><span></span></div>');
				$('div.ptogtitle', g.mDiv).click(function() {
							$(g.gDiv).toggleClass('hideBody');
							$(this).toggleClass('vsble');
						});
			}
			// g.rePosDrag();
		}

		// setup cdrops
		g.cdropleft = document.createElement('span');
		g.cdropleft.className = 'cdropleft';
		g.cdropright = document.createElement('span');
		g.cdropright.className = 'cdropright';

		// add block
		g.block.className = 'gBlock';
		var blockloading = $("<div/>");
		blockloading.addClass("loading");
		$(g.block).append(blockloading);
		var gh = $(g.bDiv).height();
		var gtop = g.bDiv.offsetTop;
		$(g.block).css({
					width : g.bDiv.style.width,
					height : gh,
					position : 'relative',
					marginBottom : (gh * -1),
					zIndex : 1,
					top : gtop,
					left : '0px'
				});
		$(g.block).fadeTo(0, p.blockOpacity);

		// add column control
		if ($('th', g.hDiv).length) {
			g.nDiv.className = 'nDiv';
			g.nDiv.innerHTML = "<table cellpadding='0' cellspacing='0'><tbody></tbody></table>";
			$(g.nDiv).css({
						marginBottom : (gh * -1),
						display : 'none',
						top : gtop
					}).noSelect();

			var cn = 0;

			$('th div', g.hDiv).each(function() {
				var kcol = $("th[axis='col" + cn + "']", g.hDiv)[0];
				if (kcol == null)
					return;
				var chkall = $("input[type='checkbox']", this);
				if (chkall.length > 0) {
					chkall[0].onclick = g.checkAllOrNot;
					return;
				}
				if (kcol.toggle == false || this.innerHTML == "") {
					cn++;
					return;
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
								+ this.innerHTML + '</td></tr>');
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
						&& $(this).prev().find('input')[0].checked)
					return false;
				return g.toggleCol($(this).prev().find('input').val());
			});

			$('input.togCol', g.nDiv).click(function() {

				if ($('input:checked', g.nDiv).length < p.minColToggle
						&& this.checked == false)
					return false;
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

			if (p.showToggleBtn)
				$(g.gDiv).prepend(g.nBtn);

		}

		// add date edit layer
		$(g.iDiv).addClass('iDiv').css({
					display : 'none'
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
						width : '100%'
					});
			$(g.gDiv).addClass('ie6');
			if (p.width != 'auto')
				$(g.gDiv).addClass('ie6fullwidthbug');
		}

		g.rePosDrag();
		g.fixHeight();

		// make grid functions accessible
		t.p = p;
		t.grid = g;

		// load data
		p.url = p.url ? p.url : "?model=" + p.model + "&action=" + p.action;
		if (p.url && p.autoload) {
			g.populate();
		}

		return t;

	};

	var docloaded = false;

	$(document).ready(function() {
				docloaded = true
			});

	$.fn.yxgrid = function(p) {

		return this.each(function() {
					if (!docloaded) {
						$(this).hide();
						var t = this;
						$(document).ready(function() {
									$.addFlex(t, p);
								});
					} else {
						$.addFlex(this, p);
					}
				});

	}; // end flexigrid

	$.fn.reload = function(p) { // function to reload grid
		return this.each(function() {
					if (this.grid && this.p.url)
						this.grid.populate();
				});

	}; // end flexReload
	// 重新指定宽度和高度
	$.fn.flexResize = function(w, h) {
		var p = {
			width : w,
			height : h
		};
		return this.each(function() {
					if (this.grid) {
						$.extend(this.p, p);
						this.grid.reSize();
					}
				});
	};
	$.fn.ChangePage = function(type) {
		return this.each(function() {
					if (this.grid) {
						this.grid.changePage(type);
					}
				})
	};
	$.fn.flexOptions = function(p) { // function to update general options

		return this.each(function() {
					if (this.grid)
						$.extend(this.p, p);
				});

	}; // end flexOptions
	$.fn.GetOptions = function() {
		if (this[0].grid) {
			return this[0].p;
		}
		return null;
	};
	$.fn.getCheckedRowIds = function() {
		if (this[0].grid) {
			return this[0].grid.getCheckedRowIds();
		}
		return [];
	};
	$.fn.flexToggleCol = function(cid, visible) { // function to reload grid

		return this.each(function() {
					if (this.grid)
						this.grid.toggleCol(cid, visible);
				});

	}; // end flexToggleCol

	$.fn.flexAddData = function(data) { // function to add data to grid

		return this.each(function() {
					if (this.grid)
						this.grid.addData(data);
				});

	};

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

		};

	}; // end noSelect

})(jQuery);