/**
 * ���ӱ��
 */
(function($) {
	$.woo.yxgrid.subclass('woo.yxsubgrid', {
		options : {
			subGridOptions : {
                dblclickAutoLoad : true,
				subgridcheck : false
			}
		},
		/**
		 * ��ʼ�����
		 */
		_create : function() {
			var g = this, el = this.el, p = this.options;
			$rows = g.getRows();
			var gridId = g.el.attr('id');
            if(p.subGridOptions.dblclickAutoLoad == true){
                $rows.live('dblclick', function() {
                    var row = $(this).data('data');
                    var rowId = row.id;
                    var openFn = g.openFn;
                    openFn()(rowId, gridId);
                });
            }
			g.processSubDatadict();
		},
		/**
		 * ���ӱ����+-��
		 */
		processExtgridCol : function() {
			var g = this, el = this.el, p = this.options;
			var col = {
				name : 'test',
				display : '<a href="###" title="���ȫ��չ��">+</a>',
				width : 10,
				isToggle : false,
				rowspan : 2,
				headProcess : function(t, gridId) {
					var $subHref = $("a[id^='sub" + gridId + "']");
					var status = $(t).data('status');
					$subHref.data("status", status);
					if (status == 'close') {
						$subHref.data("status", 'open');
					} else if (status == 'open') {
						$subHref.data("status", 'close');
					}
					$subHref.trigger('click');

					var aHref = $(t).find("a");
					if (status == 'close') {
						aHref.html("+");
						$(t).data('status', 'open');
						aHref.attr('title', '���ȫ��չ��');
					} else {
						aHref.html("-");
						$(t).data('status', 'close');
						aHref.attr('title', '���ȫ������');
					}
				},
				process : function(v, row) {
					// ע������������治��дע��
					g.openFn = function() {
						return function(rowId, gridId) {
							var $subHref = $("#sub" + gridId + "_" + rowId);
							var $row = $("#row" + rowId, $("#" + gridId));
							var $subRow = $row.data("subRow");
							var status = $subHref.data("status");
							if (status) {
								if (status == "open") {
									$subRow.hide();
									$subHref.html("+");
									$subHref.data("status", "close");
								} else {
									$subRow.show();
									$subHref.html("-");
									$subHref.data("status", "open");
								}
							} else {
								$subHref.data("status", "open");
								$subHref.html("-");
								var row = $row.data("data");
								var gridCmp = $("#" + gridId).data("cmp");
								var gridOptions = gridCmp.options;
								var subGridOptions = gridOptions.subGridOptions;
								if (subGridOptions) {
									var data = gridCmp.getSubgridData(row);
									gridCmp.processSubData(data, $row);
								}
							}
						}
					};
					var gridId = g.el.attr('id');
					if (row.id != p.noCheckIdValue) {
						return '<a href="###" id=\'sub' + gridId + '_' + row.id
                            + '\' onclick=\'(' + g.openFn() + ')("'
                            + row.id + '","' + gridId + '")\'>+</a>';
					}
					return "";
				}
			};
			if (p.colModel) {
				p.colModel.unshift(col);
			}
			if (p.complexColModel) {
				p.complexColModel[0].unshift(col);
			}
		},
		/**
		 * ��ȡ�ӱ�������
		 */
		getSubgridData : function(row) {
			var g = this, el = this.el, p = this.options;
			var sp = g.options.subGridOptions;
			if (sp.param) {
				var param = {};

				for (var i = 0; i < sp.param.length; i++) {
					if (sp.param[i].paramId) {
						var key = sp.param[i].paramId;
						var val = sp.param[i].colId;
						param[key] = row[val];
					} else {
						for (var k in sp.param[i]) {
							param[k] = sp.param[i][k];
						}
					}
				}
			}
			param.pageSize = 999;// ���ӱ�ÿҳ��ʾ��999
			var html = $.ajax({
                type : "POST",
                url : sp.url,
                data : param,
                async : false,
                success : function(data) {
                }
            }).responseText;
			return html;
		},
		/**
		 * ����ӱ������ֵ�
		 */
		processSubDatadict : function() {
			var g = this, el = this.el, p = this.options;
			var gridOptions = g.options;
			var subGridOptions = gridOptions.subGridOptions;
			var cm = subGridOptions.colModel;
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
					type : 'POST',
					url : "?model=system_datadict_datadict&action=getDataJsonByCodes",
					data : {
						codes : codes.toString()
					},
					async : false
				}).responseText;
				datadictData = eval("(" + datadictData + ")");
				for (var v in datadict) {
					var code = datadict[v].datacode;
					datadict[v].datadictData = datadictData[code];
					if (!datadict[v].process && datadictData[code]) {
						// ����ʹ�ñհ���Ŀ����Ϊ���ڷ��ص�function��ʹ�ñհ��е�������
						datadict[v].process = (function() {
							var data = datadictData[code];
							return function(v) {
								$val = $.woo.getDataName(v, data);
								if ($val) {
									return $val;
								}
								return v;
							}
						})();
					}
				}
			}
		},
		/**
		 * �����ȡ�����ݣ����дӱ���ʾ
		 */
		processSubData : function(data, $row) {
			var g = this, el = this.el, p = this.options;
			var gridOptions = g.options;
			var subGridOptions = gridOptions.subGridOptions;
			var obj = $.json2obj(data);
			var mainData = $row.data("data");
			var mainDataId = mainData.id;
			var $subRow = $("<tr class='subrow' id='subrow" + mainDataId + "'>");
			var $subTd = $("<td colspan='100' class='sub-grid'>");
			// var $hDiv = $('<div class="shDiv">');
			var $htable = $("<table class='sub-table' style='margin:0px' cellspacing='0' cellpadding='0'></table>");
			$subRow.insertAfter($row);
			$row.data('subRow', $subRow);// �����ж�������ӱ����
			$subRow.append($subTd);
			$subTd.append($htable);
			// $htable.wrap("<div class='hDiv'></div>");

			// $hdiv.append($hDiv);
			// $hDiv.append($htable);

			// ��ͷ
			if (subGridOptions.colModel) {
				var $thead = $("<thead>");
				var $tr = $("<tr class='subtr'>");
				// var $td = $("<th class='subline'><div style='text-align:
				// center; width:10px;'></div></td>");
				// $tr.append($td);
				$thead.append($tr);
				// ��ѡ����
				if (subGridOptions.subgridcheck) {
					var $checkAll = $("<input mainDataId='" + mainDataId
                        + "' type='checkbox' id='maincheckbox" + mainDataId
                        + "' />");

					$checkAll.click(function() {
                        var mId = $(this).attr("mainDataId");
                        var isCheck = $(this).attr("checked");
                        // var $checkbox = $(g.bDiv)
                        // .find("input[type='checkbox'][mainDataId='"
                        // + mId + "']");
                        // $checkbox.attr("checked", isCheck);
                        g.checkSubRows($(g.bDiv).find("tr[mainDataId='" + mId + "']"), isCheck);
                    });
					var $th = $("<th class='shtd'></th>");
					$th.append($checkAll);
					$tr.append($th);
				}
				for (var i = 0; i < subGridOptions.colModel.length; i++) {
					var col = subGridOptions.colModel[i];
					col.width = col.width ? col.width : 100;
					col.align = col.align ? col.align : 'left';
					$tr.append("<th class='shtd'><div style='text-align:" + col.align + "; width: "
                        + col.width
                        + "px;'>"
                        + col.display
                        + "</div></th>");
				}
				$htable.append($thead);
			}
			$htable.append("</table>");
			var $btable = $("<table class='sub-table' cellspacing='0' cellpadding='0' ></table>");
			$subTd.append($btable);

			// ����
			if (obj.collection) {
				for (var i = 0; i < obj.collection.length; i++) {
					var data = obj.collection[i];
					var dataId = data.id;
					var $tr = $("<tr class='subtr' mainDataId='" + mainDataId
                        + "' dataId='" + dataId + "' id='subtr" + dataId
                        + "'>");
					$tr.data("data", data);
					$tr.hover(function() {
                        $(this).children("[class='shtd']")
                                .addClass("trOver");
                    }, function() {
                        $(this).children().removeClass("trOver");
                    });
					$tr.click(function(e) {
                        var $chekcbox = g.getSubRowCheckbox($(this));
                        g.checkSubRows($(this), !$chekcbox.attr("checked"));
                    });
					if (subGridOptions.subgridcheck) {
						var $checkbox = "";
						var isShowCheckbox = true;
						if ($.isFunction(subGridOptions.checkShowFn)) {
							isShowCheckbox = subGridOptions.checkShowFn(data);
						}
						if (isShowCheckbox) {
							$checkbox = $("<input mainDataId='"
                                + mainDataId
                                + "' dataId='"
                                + dataId
                                + "' type='checkbox' class='subCheck' id='subchekcbox"
                                + dataId + "'/>");
							$checkbox.click(function(e) {
                                var $row = $(this).parent().parent();
                                if ($(this).attr("checked")) {
                                    $row.children("[class^='shtd']").addClass("selected");
                                } else {
                                    $row.children().removeClass("selected");
                                }
                                e.stopPropagation();
                            });
							$checkbox.data("data",data);
						}
						var $td = $("<td class='shtd'></td>");
						$td.append($checkbox);
						$tr.append($td);
					}
					for (var j = 0; j < subGridOptions.colModel.length; j++) {
						var col = subGridOptions.colModel[j];
						col.width = col.width ? col.width : 100;
						col.align = col.align ? col.align : 'left';
						var v = data[col.name];
						if (col.process) {
							v = col.process(v, data, $row.data('data'), $row,$tr);
						}
						$tr.append("<td class='shtd'><div class='substdiv' style='text-align: " + col.align + "; width: "
                            + col.width
                            + "px;'>"
                            + v
                            + "</div></td>");
					}
					if (subGridOptions.afterProcess) {
						subGridOptions.afterProcess(data, $row.data('data'),$tr);
					}
					$btable.append($tr);
				}

			}
			var subh = 200;// �Ժ������óɲ���
			if ($btable.height() < subh) {
				subh = $btable.height() + 10;
			}
			$btable.wrap("<div style='padding:0px;border:0px;overflow-y:auto;width:"
                + ($btable.width() + 37)
                + "px;height:"
                + subh
                + "px;'></div>");
		},
		/**
		 * ѡ��/����ĳһ���µôӱ���
		 */
		checkSubRows : function($row, isCheck) {
			var $checkbox = $row
					.find("input.subCheck[type='checkbox'][id^='subchekcbox']");
			if ($checkbox.size() > 0) {
				$checkbox.attr("checked", isCheck);
				if (isCheck) {
					$row.children("[class^='shtd']").addClass("selected");
				} else {
					$row.children().removeClass("selected");
				}
			} else {
				$row.children("[class^='shtd']").toggleClass("selected");
			}
		},
		/**
		 * ��ȡ�ӱ�ĳһ�е�checkbox
		 */
		getSubRowCheckbox : function($tr) {
			var mainDataId = $tr.attr("mainDataId");
			return $tr.find("input.subCheck[type='checkbox'][mainDataId='" + mainDataId + "']");
		},
		/**
		 * ��ȡѡ���дӱ�ѡ������id
		 */
		getSubSelectRowCheckIds : function($selectRow) {
			var mainDataId = $selectRow.data("data").id;
			var $selectedCheck = $(this.bDiv).find("input.subCheck[type='checkbox'][checked=true][mainDataId='"+ mainDataId + "']");
			var idArr = [];
			$selectedCheck.each(function() {
                idArr.push($(this).attr("dataId"));
            });
			return idArr;
		},
		/**
		 * ��ȡѡ���дӱ�ѡ����jquery����
		 */
		getSubSelectRows : function($selectRow) {
			var mainDataId = $selectRow.data("data").id;
			var $selectedCheck = $(this.bDiv)
                .find("input.subCheck[type='checkbox'][checked=true][mainDataId='"
                + mainDataId + "']").closest("tr");
			return $selectedCheck;
		},
		/**
		 * ��ȡ����ѡ�еĴӱ�����
		 */
		getAllSubSelectRowCheckIds : function() {
			var $selectedCheck = $(this.bDiv).find("input.subCheck[type='checkbox'][checked=true]");
			var idArr = [];
			$selectedCheck.each(function() {
                idArr.push($(this).attr("dataId"));
            });
			return idArr;
		},
		/**
		 * ��ȡ����ѡ�еĴӱ�������
		 */
		getAllSubSelectRowDatas : function() {
			var $selectedCheck = $(this.bDiv).find("input.subCheck[type='checkbox'][checked=true]");
			var datas = [];
			$selectedCheck.each(function() {
                datas.push($(this).data("data"));
            });
			return datas;
		}
	});
})(jQuery);