/**
 * yxcombotree ����jQuery1.4.2+
 *
 * ����˵���� ������ѡ������ѡ��
 *
 * ȱ�ٹ��ܣ� ��һ��չ���޷��Զ�ѡ��
 */
(function($) {
	$.woo.yxcombo.subclass('woo.yxcombotree', {
		options : {
			/**
			 * ��Ҷ�ӽڵ��Ƿ��ܱ�ѡ�в�����ֵ
			 */
			isParentSelect : false,
			/**
			 * ����ѡ����Ĭ�Ͽ��
			 */
			width : 300,
			/**
			 * ����ѡ����Ĭ�ϸ߶�
			 */
			height : 200,
			/**
			 * ��Ϊ��������������
			 */
			valueCol : 'id',
			/**
			 * ��Ϊ������ʾ������
			 */
			nameCol : 'name',
			/**
			 * ��Ĭ����������
			 */
			treeOptions : {
				callback : {}
			},
			/**
			 * ֵ�ָ���
			 */
			valueSeparator : ","
		},
		create : function() {
			this._super();
			var t = this, p = t.options, el = t.el;
			var demoIframe;
			var id = el.attr('id') + "_tree";
			var treeEl = $("<ul id='" + id + "'></ul>");

			this.container.append(treeEl);
			var treeOptions = p.treeOptions;
			treeOptions.height = p.height;
			treeEl.yxtree(treeOptions);
			t.treeEl=treeEl;
			// ����Ϊ��ѡ���¼�
			if (treeOptions.checkable == true) {

				var changFn = function(event, treeId, treeNode) {
					// var nodes = treeEl.yxtree('getCheckedNodes', true);//
					// ��ȡѡ�е�ֵ
					if (treeNode.nodes) {
						for (var i = 0; i < treeNode.nodes.length; i++) {
							changFn(null, null, treeNode.nodes[i]);
						}
					}
					if (p.hiddenId) {
						if (p.isParentSelect || !treeNode.isParent) {
							if (treeNode.checked) {
								if (p.idArr.indexOf(treeNode[p.valueCol]) == -1) {
									p.idArr.push(treeNode[p.valueCol]);
									p.nameArr.push(treeNode[p.nameCol]);
								}
							} else {
								// ���ֵ���ڣ�ɾ��������
								var index = p.idArr
										.indexOf(treeNode[p.valueCol]);
								if (index != -1) {
									p.idArr.splice(index, 1);
									p.nameArr.splice(index, 1);
								}
							}
							p.nameStr = p.nameArr.join(p.valueSeparator);
							p.idStr = p.idArr.join(p.valueSeparator);
							$("#" + el.attr('id')).val(p.nameStr);
							$("#" + p.hiddenId).val(p.idStr);
						}
					}
					/* start:edit by huangzf 20101211 */
					// treeOptions.callback.node_change(event, treeId,
					// treeNode);
					/* end:edit by huangzf 20101211 */
					treeEl.trigger("after_node_change", [treeId, treeNode]);
				}
				// ��ѡ�����¼�
				treeEl.bind("node_change", changFn);
				// չ���ڵ��ʱ���Զ���ѡֵ
				treeEl.bind("node_success", function(event, treeId, treeNode) {
					if (treeNode.nodes) {
						for (var i = 0; i < treeNode.nodes.length; i++) {
							if (treeNode.nodes[i][p.valueCol]) {
								if ((p.valueSeparator + p.idStr + p.valueSeparator).indexOf(p.valueSeparator
										+ treeNode.nodes[i][p.valueCol] + p.valueSeparator) != -1) {
									var node = treeNode.nodes[i];
									var checkObj = $("#" + node.tId + "_check");
									node.checkedNew = true;
									//checkObj.click();
									treeEl
											.yxtree('setChkClass', checkObj,
													node);
									treeEl
											.yxtree('setParentNodeCheckBox', node,true);
									//treeEl.trigger("node_change", [treeId, treeNode]);
								}
							}
						}
					}
				});

			}
			// ����Ϊ��ѡ���¼�
			else {
				treeEl.bind("node_click", function(event, treeId, treeNode) {
							if (p.hiddenId) {
								p.idStr = treeNode[p.valueCol];
								$("#" + p.hiddenId).val(p.idStr);
							}
							p.nameStr = treeNode[p.nameCol];
							$("#" + el.attr('id')).val(p.nameStr);
						});

				treeEl.bind("node_dblclick", function() {
							t.kill();
						});
			}
			// ���ñ���
			$(el).mouseover(function() {
						if ($(el).val() != "") {
							$(el).attr('title', $(el).val());
						}
					});
		},
		/**
		 * ��ȫչ
		 */
		expandAll:function(){
			var t = this, p = t.options, el = t.el;
			t.treeEl.yxtree('expandAll');
		}
	});

})(jQuery);