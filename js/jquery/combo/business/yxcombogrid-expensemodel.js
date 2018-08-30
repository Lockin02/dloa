/**
 * ��������ģ��
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_expensemodel', {
		_create : function() {
			if (this.options.isShowButton) {
				var t = this, p = t.options, el = t.el;
				$button = $("<span class='search-trigger' title='ѡ��ģ��'>&nbsp;</span>");
				$button.click(function() {

				});
				$(el).after($button);
				if ($(el).attr("wchangeTag2") != 'true') {
					// ���Ŀ��
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
				// ����Ϣ
				colModel : [{
					display : 'ģ������',
					name : 'templateName',
					width : 120
				},{
					display : 'Ĭ���ֶ�',
					name : 'content',
					width : 400
				}],
				sortname : "c.updateTime"
			}
		}
	});
})(jQuery);