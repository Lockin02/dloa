/**
 * 下拉报销模板
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_expensemodel', {
		_create : function() {
			if (this.options.isShowButton) {
				var t = this, p = t.options, el = t.el;
				$button = $("<span class='search-trigger' title='选择模板'>&nbsp;</span>");
				$button.click(function() {

				});
				$(el).after($button);
				if ($(el).attr("wchangeTag2") != 'true') {
					// 更改宽度
					var w = $(el).width();
					if(w>$button.width()){
						$(el).width(w - $button.width());
						$(el).attr("wchangeTag2", true);
					}
				}
			}
		},
		options : {
            isFocusoutCheck : false,
			hiddenId : 'modelType',
			nameCol : 'modelTypeName',
			gridOptions : {
				showcheckbox : false,
				model : 'finance_expense_customtemplate',
				action : 'myJson',
				// 列信息
				colModel : [{
					display : '模板名称',
					name : 'templateName',
					width : 120
				},{
					display : '默认字段',
					name : 'content',
					width : 400
				}],
				sortname : "c.updateTime"
			}
		}
	});
})(jQuery);