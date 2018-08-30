/**
 * 下拉供应商表格组件
 */
var supplier = '';
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_supplier', {

		_create : function() {
			if (this.options.isShowButton) {
				var t = this, p = t.options, el = t.el;
				$button = $("<span class='add-trigger'  title='添加供应商'>&nbsp;</span>");
				t.addButton = $button;
				$button.click(function() {
					el.click();
					// flibrary-add.htm
					var openWin = window.open(
							"?model=supplierManage_formal_flibrary&action=toAdd&valPlus="
									+ el.attr('id'), '',
							"width=1200px,height=600px,resizable=yes");

					if (openWin) {
						var oldsupplier = "";
						setInterval(function() {
									var supplier = $('#valHidden'
											+ el.attr('id')).val();
									if (supplier != oldsupplier)
										if (supplier != '') {
											var objRv = $.json2obj(supplier);
											t.setValue(objRv);
											var $row = $(t.grid.addOneRow(1,
													objRv));
											t.grid.el
													.trigger(
															'row_dblclick',
															[
																	$row,
																	t.grid
																			.transRow(objRv)]);
											t.kill();
											oldsupplier = supplier;
										};

								}, 1000);

						// t.setValue(objRv);
						// var $row = $(t.grid.addOneRow(1, objRv));
						// t.grid.el.trigger('row_dblclick', [$row,
						// t.grid.transRow(objRv)]);
						// t.kill();
					}

				});
				$(el).next().after($button);
				$valHidden = $("<input id='valHidden" + el.attr('id')
						+ "' type='hidden' />");
				$(el).next().after($valHidden);

				if ($(el).attr("wchangeTag2") != 'true') {
					// 更改宽度
					var w = $(el).width();
					$(el).width(w - $button.width());
					$(el).attr("wchangeTag2", true);
				}
			}
		},
		remove : function() {
			var t = this, p = t.options, el = t.el;
			this._super();
			if (t.addButton)
				t.addButton.remove();
		},
		options : {
			hiddenId : 'id',
			nameCol : 'suppName',
			width : 500,
			isShowButton : true,
			gridOptions : {
				showcheckbox : false,
				model : 'supplierManage_formal_flibrary',
				pageSize : 10,
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : 'manageUserId',
							name : 'manageUserId',
							sortable : true,
							hide : true
						}, {
							display : '供应商编号',
							name : 'busiCode',
							sortable : true
						}, {
							display : '供应商名称',
							name : 'suppName',
							sortable : true,
							width : 180
						}, {
							display : '主营产品',
							name : 'products',
							sortable : true,
							width : 150
						}, {
							display : '负责人',
							name : 'manageUserName',
							sortable : true,
							width : 100
						}],
				// 快速搜索
				searchitems : [{
							display : '供应商名称',
							name : 'suppName'
						}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);