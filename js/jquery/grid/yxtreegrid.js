// / <reference path="../intellisense/jquery-1.2.6-vsdoc-cn.js" />
// / <reference path="../lib/blackbird.js" />

/**
 * 主从表格
 * 
 */
(function($) {
	$.woo.yxgrid.subclass('woo.yxtreegrid', {
				options : {
					showcheckbox : false
				},
				/**
				 * 初始化组件
				 */
				_beforeCreate : function() {
					var g = this, el = this.el, p = this.options;

					// 拿第一个不是隐藏的列
					var treeRenderCol;
					for (var i = 0; i < p.colModel.length; i++) {
						var col = p.colModel[i];
						if (col.hide != true) {
							treeRenderCol = col;
							break;
						}
					}
					var treeProcess = function(v, row) {
						// 注意这个函数里面不能写注释
						var openFn = function() {
							return function(rowId, gridId) {
								var $subHref = $("#tree" + gridId + "_" + rowId);
								var $row = $("#row" + rowId);
								var childrenRow = $row.data("childrenRow");

								var status = $subHref.data("status");
								if (status) {
									if (status == "open") {
										for (var i = 0; i < childrenRow.length; i++) {
											childrenRow[i].hide();
										}
										$subHref.html("+");
										$subHref.data("status", "close");
									} else {
										for (var i = 0; i < childrenRow.length; i++) {
											childrenRow[i].show();
										}
										$subHref.html("-");
										$subHref.data("status", "open");
									}
								} else {
									$subHref.data("status", "open");
									$subHref.html("-");
									var row = $row.data("data");
									var gridCmp = $("#" + gridId)
											.data("yxtreegrid");
									var gridOptions = gridCmp.options;
									var data = gridCmp.getTreegridData(row);
									gridCmp.processSubData(data, $row);
								}
							}
						}
						var gridId = g.el.attr('id');
						var rowJson = $.obj2json(row);
						if (!p.idColumn) {
							p.idColumn = 'id';
						}
						return '<a id=\'tree' + gridId + '_' + row.id
								+ '\' href=\'javascript:(' + openFn() + ')("'
								+ row[p.idColumn] + '","' + gridId
								+ '")\'>+</a>' + v;
					}
					var process = treeRenderCol.process;
					if (process) {
						treeRenderCol.process = function(v, row) {
							var v = process(v, row);
							return treeProcess(v, row);
						};
					} else {
						treeRenderCol.process = treeProcess;
					}
				},
				/**
				 * 获取从表表格数据
				 */
				getTreegridData : function(row) {
					var g = this, el = this.el, p = this.options;
					// alert(g.options.subGridOptions);
					var sp = g.options;
					if (sp.param) {
						var param = {};
						for (var i = 0; i < sp.param.length; i++) {
							var key = sp.param[i].paramId;
							var val = sp.param[i].colId;
							param[key] = row[val];
						}
					}
					var html = $.ajax({
								type : "POST",
								url : sp.url,
								data : param,
								async : false
							}).responseText;
					return html;
					// alert(html)
				},
				/**
				 * 处理获取的数据，进行从表显示
				 */
				processSubData : function(data, $row) {
					var row = $row.data("data");
					var g = this, el = this.el, p = this.options;
					var gridOptions = g.options;
					var obj = $.json2obj(data);
					// 内容
					if (obj.collection) {
						var $curRow = $row;
						var rowArr = [];
						for (var i = 0; i < obj.collection.length; i++) {
							$addRow = $(g.addOneRow(i, obj.collection[i]));
							rowArr.push($addRow);
							$addRow.data("data", obj.collection[i]);
							// treeLayer记录点击行的层次
							var treeLayer = $row.data('treeLayer');
							if (treeLayer) {
								treeLayer = treeLayer + 1;
							} else {
								treeLayer = 1;
							}
							$addRow.data('treeLayer', treeLayer);
							$curRow.after($addRow);
							$curRow = $addRow;
						}
						$row.data("childrenRow", rowArr);
					}

				}
			});

})(jQuery);