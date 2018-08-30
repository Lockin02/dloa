
/**
 * �߼�����������ṩ����ͨ���ʹ��
 */
(function($) {
	$.woo.component.subclass('woo.yxadvsort', {
		options : {},
		/**
		 * ��ʼ�����
		 */
		_create : function() {
			var g = this, el = this.el, p = this.options;
			$.window({
				showModal : true,
				modalOpacity : 0.5,
				width : 500,
				height : 200,
				title : "����",
				content : "<div id='sortgrid'></div>"
						+ "<div style='text-align:center'>"
						+ "<input id='confirmAdvsearch' type='button' class='txt_btn_a' value='����'> "
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

			// ��������
			g.processSortGrid();

			// ȷ������
			$("#confirmAdvsearch").click(function() {
						getSearchCondition();
						if (p.grid) {
							p.grid.reload();
						}
						// �����¼�
						$(el).trigger('confirmAdvsearch');
					});

		},
		/**
		 * ����������
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
									display : '�����ֶ�',
									name : 'sortField',
									type : 'select',
									options : sortConfig
								}, {
									display : '������',
									name : 'sort',
									type : 'select',
									options : [{
												name : '����',
												value : 'ASC'
											}, {
												name : '����',
												value : 'DESC'
											}]
								}]
					});
		},
		/**
		 * ������������
		 */
		getAdvSortArr : function() {
			return this.advSortArr;
		},
		/**
		 * ��������׷��url
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