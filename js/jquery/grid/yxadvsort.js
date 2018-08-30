
/**
 * 高级排序组件，提供给普通表格使用
 */
(function($) {
	$.woo.component.subclass('woo.yxadvsort', {
		options : {},
		/**
		 * 初始化组件
		 */
		_create : function() {
			var g = this, el = this.el, p = this.options;
			$.window({
				showModal : true,
				modalOpacity : 0.5,
				width : 500,
				height : 200,
				title : "排序",
				content : "<div id='sortgrid'></div>"
						+ "<div style='text-align:center'>"
						+ "<input id='confirmAdvsearch' type='button' class='txt_btn_a' value='搜索'> "
			});

			function getSearchCondition() {
				g.advSortArr = [];
				var $sortCmps = $("#sortgrid").yxeditgrid("getCmpByCol",
						'sortField');
				$sortCmps.each(function() {
							var rowNum = $(this).data("rowNum");
							var sortField = $("#sortgrid").yxeditgrid(
									"getCmpByRowAndCol", rowNum, 'sortField')
									.val();
							var sort = $("#sortgrid").yxeditgrid(
									"getCmpByRowAndCol", rowNum, 'sort').val();
							var sortItem = {
								sortField : sortField,
								sort : sort
							};
							g.advSortArr.push(sortItem);
						});
				if (p.grid) {
					p.grid.options.extParam.sortArr = g.advSortArr;
				}

			}

			// 处理排序
			g.processSortGrid();

			// 确认搜索
			$("#confirmAdvsearch").click(function() {
						getSearchCondition();
						if (p.grid) {
							p.grid.reload();
						}
						// 触发事件
						$(el).trigger('confirmAdvsearch');
					});

		},
		/**
		 * 处理排序表格
		 */
		processSortGrid : function() {
			var g = this, el = this.el, p = this.options;
			var sortConfig = p.sortConfig;
			$("#sortgrid").yxeditgrid({
						objName : 'advSort',
						colModel : [{
									display : 'id',
									name : 'id',
									type : 'hidden'
								}, {
									display : '排序字段',
									name : 'sortField',
									type : 'select',
									options : sortConfig
								}, {
									display : '升降序',
									name : 'sort',
									type : 'select',
									options : [{
												name : '升序',
												value : 'ASC'
											}, {
												name : '降序',
												value : 'DESC'
											}]
								}]
					});
		},
		/**
		 * 返回排序数组
		 */
		getAdvSortArr : function() {
			return this.advSortArr;
		},
		/**
		 * 返回排序追加url
		 */
		getSortPlus : function() {
			var arr = this.advSortArr;
			var plus = "";
			for (var i = 0; i < arr.length; i++) {
				var field = arr[i].sortField;
				var sort = arr[i].sort;
				plus += "sortArr[" + i + "][sortField]=" + field + "&";
				plus += "sortArr[" + i + "][sort]=" + sort + "&";
			}
			return plus;
		}
	});

})(jQuery);