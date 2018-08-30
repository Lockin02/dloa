/**
 * combobox - tui ����jQuery1.4.2+
 * 
 * ����˵���� ������壬�ɷ���HTML
 * 
 * ȱ�ٹ��ܣ� �̳м���
 */
(function($) {
	$.woo.yxcombo.subclass('woo.yxcombotext', {
		options : {
			/**
			 * �������Կؼ�id
			 */
			hiddenId : '',
			/**
			 * �Ƿ���ʾCheckBox
			 */
			checkable : false,
			/**
			 * ajax�ύ��ʽ
			 */
			ajaxType : "POST",
			/**
			 * ��������url
			 */
			url : 'yx-text-json.php', // url
			/**
			 * ��Ϊ�������ֵ������
			 */
			valueCol : 'id',
			/**
			 * ��Ϊ���������ʾ������
			 */
			nameCol : 'name',
			// ajaxData : "val=",
			// ajaxJson : null,
			/**
			 * �ƶ���ѡ��ʱ��ʽ
			 */
			linkHover : 'ui-state-hover',
			/**
			 * �������Ĭ�Ͽ��
			 */
			width : 200,
			/**
			 * �������Ĭ�ϸ߶�
			 */
			height : 200
		},
		create : function() {
			this._super();
			var t = this, p = t.options, el = t.el;
			t.comboCreate();

		},
		/**
		 * ��������
		 */
		comboCreate : function() {
			var t = this, p = t.options, el = t.el;
			t.getData();
			t.container.append(t.createContentByItems());
		},
		/**
		 * ��ȡ����
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
				liQuery.data('data', items[i]);// �����ݰ󶨵��ڵ�
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
										// ���ֵ���ڣ�ɾ��������
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
					// ��ѡ����
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
								// ����click�¼�
								el.trigger('row_click', [data]);
								t.kill();
							});
				}
				liQuery.hover(function() {
							// ��ҳ�˵���û������ƶ�Ч��
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