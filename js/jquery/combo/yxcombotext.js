/**
 * combobox - tui 基于jQuery1.4.2+
 * 
 * 功能说明： 下拉面板，可放入HTML
 * 
 * 缺少功能： 继承加入
 */
(function($) {
	$.woo.yxcombo.subclass('woo.yxcombotext', {
		options : {
			/**
			 * 隐藏属性控件id
			 */
			hiddenId : '',
			/**
			 * 是否显示CheckBox
			 */
			checkable : false,
			/**
			 * ajax提交方式
			 */
			ajaxType : "POST",
			/**
			 * 请求数据url
			 */
			url : 'yx-text-json.php', // url
			/**
			 * 作为下拉表格值的列名
			 */
			valueCol : 'id',
			/**
			 * 作为下拉表格显示的列名
			 */
			nameCol : 'name',
			// ajaxData : "val=",
			// ajaxJson : null,
			/**
			 * 移动到选项时样式
			 */
			linkHover : 'ui-state-hover',
			/**
			 * 下拉面板默认宽度
			 */
			width : 200,
			/**
			 * 下拉面板默认高度
			 */
			height : 200
		},
		create : function() {
			this._super();
			var t = this, p = t.options, el = t.el;
			t.comboCreate();

		},
		/**
		 * 创建下拉
		 */
		comboCreate : function() {
			var t = this, p = t.options, el = t.el;
			t.getData();
			t.container.append(t.createContentByItems());
		},
		/**
		 * 获取数据
		 */
		getData : function() {
			var t = this, p = t.options, el = t.el;
			$.ajax({
						type : p.ajaxType,
						url : p.url,
						// data: p.ajaxData+el.val(),
						// dataType: p.dataType,
						async : false,
						success : function(msg) {
							msg = eval("(" + msg + ")");
							p.data = msg;
						}
					});
		},
		createContentByItems : function() {
			var t = this, p = t.options, el = t.el;
			var data = t.options.data;
			var objId = el.attr("id");
			var i = 0;
			var textEl = $("<ul class='fg-menu ui-corner-all'></ul>");
			// t.kill();
			var contId = $(this.container).attr('id');
			var items = data.collection;
			for (var i = 0, l = items.length; i < l; i++) {
				var liQuery = $("<li class='menu-link' valuea='"
						+ items[i][p.valueCol] + "' title='"
						+ items[i][p.nameCol] + "'></li>");
				liQuery.data('data', items[i]);// 把数据绑定到节点
				if (p.checkable) {
					var checkQuery = $("<input type='checkbox' name='yxcobcheck-"
							+ p.hiddenId + "' >");
					liQuery.append(checkQuery);

					liQuery.append(items[i][p.nameCol]);
					textEl.append(liQuery);
					liQuery.click(function() {
								// el.attr("value",$(this).attr("valuea") );
								// t.kill();
								var checkbox = $("input[type='checkbox']", this);
								checkbox.attr('checked', !checkbox
												.attr('checked'));
								var data = $(this).data('data');
								var v = data[p.valueCol];
								var n = data[p.nameCol];
								if (p.hiddenId) {
									if (checkbox.attr('checked')) {
										if (p.idArr.indexOf(v) == -1) {
											p.idArr.push(v);
											p.nameArr.push(n);
										}
									} else {
										// 如果值存在，删除数组项
										var index = p.idArr.indexOf($(this)
												.attr("valuea"));
										if (index != -1) {
											p.idArr.splice(index, 1);
											p.nameArr.splice(index, 1);
										}
									}
									p.nameStr = p.nameArr.toString();
									p.idStr = p.idArr.toString();
									$("#" + el.attr('id')).val(p.nameStr);
									$("#" + p.hiddenId).val(p.idStr);
								}
								el.trigger('row_check', [data]);
							});
					// 单选处理
				} else {
					liQuery
							.append("<a class='ui-corner-all'><span class='item-text'>"
									+ items[i][p.nameCol] + "</span></a>");
					textEl.append(liQuery);
					liQuery.click(function() {

								var data = $(this).data('data');
								var v = data[p.valueCol];
								var n = data[p.nameCol];
								el.attr("value", n);
								$(p.hiddenId).val(v);
								// 触发click事件
								el.trigger('row_click', [data]);
								t.kill();
							});
				}
				liQuery.hover(function() {
							// 单页菜单组没有鼠标移动效果
							var menuitem = $(this);
							$('.' + p.linkHover).removeClass(p.linkHover)
									.blur().parent().removeAttr('id');
							$(this).addClass(p.linkHover).focus().parent()
									.attr('id', 'active-menuitem');
						}, function() {
							$(this).removeClass(p.linkHover).blur().parent()
									.removeAttr('id');
						});

			}
			return textEl;
		}
	});

})(jQuery);