/**
 * ��ɫ����������
 */
 var position = '';
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_position', {
		_create : function() {
			if (this.options.isShowButton) {
				var t = this, p = t.options, el = t.el;
				$button = $("<span class='add-trigger'  title='���ְλ˵����'>&nbsp;</span>");
				t.addButton = $button;
				$button.click(function() {
					el.click();
					// flibrary-add.htm
					var openWin = window.open(
							"?model=hr_position_positiondescript&action=toAdd&valPlus="
									+ el.attr('id'), '',
							"dialogWidth=1200px,dialogHeight=600px,resizable=yes,scrollbars=yes");

					if (openWin) {
						var oldposition = "";
						setInterval(function() {
									var position = $('#valHidden'
											+ el.attr('id')).val();
									if (position != oldposition)
										if (position != '') {
											var objRv = $.json2obj(position);
											t.setValue(objRv);
											var $row = $(t.grid.addOneRow(1,objRv));
											t.grid.el.trigger('row_dblclick',[$row,t.grid.transRow(objRv)]);
											t.kill();
											oldposition = position;
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
					// ���Ŀ��
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
					hiddenId : 'jobId',
					nameCol : 'name',
					isShowButton : true,
					gridOptions : {
						showcheckbox : false,
						model : 'deptuser_jobs_jobs',
						action : 'pageJson',
						pageSize : 10,
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'name',
									width:200,
									display : 'ְλ����',
									sortable : true

								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);