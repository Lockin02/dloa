/**
 * yxcombotree 基于jQuery1.4.2+
 *
 * 功能说明： 下拉单选树，多选树
 *
 * 缺少功能： 第一级展开无法自动选择
 */
(function($) {
	$.woo.yxcombo.subclass('woo.yxcombotree', {
		options : {
			/**
			 * 非叶子节点是否能被选中并设置值
			 */
			isParentSelect : false,
			/**
			 * 下拉选择树默认宽度
			 */
			width : 300,
			/**
			 * 下拉选择树默认高度
			 */
			height : 200,
			/**
			 * 作为树的隐藏属性名
			 */
			valueCol : 'id',
			/**
			 * 作为树的显示属性名
			 */
			nameCol : 'name',
			/**
			 * 树默认属性设置
			 */
			treeOptions : {
				callback : {}
			},
			/**
			 * 值分隔符
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
			// 以下为多选树事件
			if (treeOptions.checkable == true) {

				var changFn = function(event, treeId, treeNode) {
					// var nodes = treeEl.yxtree('getCheckedNodes', true);//
					// 获取选中的值
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
								// 如果值存在，删除数组项
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
				// 多选触发事件
				treeEl.bind("node_change", changFn);
				// 展开节点的时候自动勾选值
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
			// 以下为单选树事件
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
			// 设置标题
			$(el).mouseover(function() {
						if ($(el).val() != "") {
							$(el).attr('title', $(el).val());
						}
					});
		},
		/**
		 * 树全展
		 */
		expandAll:function(){
			var t = this, p = t.options, el = t.el;
			t.treeEl.yxtree('expandAll');
		}
	});

})(jQuery);