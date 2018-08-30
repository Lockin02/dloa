/**
 * combobox - tui ����jQuery1.4.2+
 *
 * ����˵���� ������壬�ɷ���HTML
 *
 * ȱ�ٹ��ܣ� �̳м���
 */
(function($) {
	var waitKillCombo = [];// ���ڴ��ҳ��/������������Ҫ���ݻٵ��������
	var docWidth = 0;
	var docHeight = 0;
	$.woo.component.subclass('woo.yxcombo', {
		options : {
			/**
			 * html
			 */
			html : '',
			/**
			 * ����������Դ
			 */
			content : null,
			/**
			 * �������Ĭ�Ͽ��
			 */
			width : 400,
			/**
			 * �������Ĭ�ϸ߶�
			 */
			height : null,
			/** ******����5�����Թ��̳���ʹ�ã����������������������ѡ���************* */
			/**
			 * ���ؿؼ�id
			 */
			hiddenId : null,
			/**
			 * idֵ����
			 */
			idArr : [],
			/**
			 * ����ֵ����
			 */
			nameArr : [],
			/**
			 * idֵ����,����
			 */
			idStr : "",
			/**
			 * ����ֵ����,����
			 */
			nameStr : "",
			/**
			 * �������λ���������
			 */
			positionOpts : {
				posX : 'left',
				posY : 'bottom',
				offsetX : 0,
				offsetY : 0,
				/**
				 * �����������չ��
				 */
				directionH : 'right',
				/**
				 * �����������չ��
				 */
				directionV : 'down',
				/**
				 * �Ƿ����ˮƽ�����⣬������������������չ��������������ұ�չ����ʱ�򵽴���Ļ�߽磬�Զ�����չ��
				 */
				detectH : true,
				/**
				 * �Ƿ���д�ֱ�����⣬������������������չ��������������±�չ����ʱ�򵽴���Ļ�߽磬�Զ�����չ��
				 */
				detectV : true
			},
			/**
			 * �ٶ���ʾ/��������������
			 */
			showSpeed : 200,
			scroll : 'scroll',// �Ƿ��������
			valueSeparator : ','
		},

		_create : function() {
			var combo = this, el = this.el, options = this.options;
			// ����dom�ڵ�
			this.container = $('<div   class="fg-menu-container ui-widget ui-widget-content ui-corner-all">'
					+ options.html + '</div>');
			this.container.append($(options.content));
			this.comboOpen = false;// ��ʶ��������Ƿ񱻴�
			this.comboExists = false;// ��ʶ��������Ƿ����

			// ���������������
			waitKillCombo.push(combo);// ���뱻ɱ����
			var fn = function(e) {
				if (combo.comboOpen == false) {
					var pos = getEPos(e);
					combo.showCombo(pos);
				}
				return false;// ����return false��������õ��ۼӼ�ð���¼��򲻻ᴥ��
			};
			el.bind('click', fn);
			// el.bind('focus', fn);//�����������ѡ������������ʱȥ��

			// ʧ��ȥ������
			// el.bind('focusout', function(){
			// combo.kill();
			// });
			el.bind('keydown', function(event) {
						if (event.keyCode == 9) {
							combo.kill();
						}
					});

			this.container.click(function(e) {
						e.stopPropagation();
					});
		},
		/**
		 * �Ƴ��������
		 */
		remove : function() {
			this.container.empty();
			this.el.unbind();
			this.destroy();
		},
		/**
		 * �ݻٿؼ�:�˷���������ʹ��(ʹ��remove)
		 */
		destroy : function() {
			// this.container.remove();
		},
		/**
		 * ��ʾ�������
		 *
		 * @param {pos}
		 *            ��ʾ��������λ��
		 */
		showCombo : function(pos) {
			var combo = this, el = this.el, container = this.container, options = this.options;
			killAllCombo();
			$(el).trigger('show_combo');// TODO:������Ϸ���ֵ�ж�
			if (!combo.comboExists) {
				combo.create(pos);
			};
			// �����������λ��
			combo.setPosition(pos);
			container.hide().slideDown(options.showSpeed);
			combo.comboOpen = true;

		},

		/**
		 * �������������ĺ���
		 *
		 * @param {pos}
		 *            ��ʾ��������λ��
		 */
		create : function(pos) {
			var combo = this, el = this.el, container = this.container, options = this.options;

			container.css({
				width : options.width,
				height : options.height,
				overflow : options.scroll
					// ���ֹ�����
				}).appendTo('body');
			combo.comboExists = true;
			combo.setInitValue();
		},

		/**
		 * �ݻٵ�������������
		 */
		kill : function() {
			var combo = this, el = this.el, container = this.container, options = this.options;
			container.parent().hide();
			combo.comboOpen = false;
		},

		/**
		 * �������������ʾλ��
		 *
		 * @param {pos}
		 *            ��ʾ��������λ��
		 */
		setPosition : function(pos) {
			var combo = this, el = this.el, container = this.container, options = this.options, dims;
			docWidth = $(document).width();
			docHeight = $(document).height();
			dims = {
				refX : el.offset().left,
				refY : el.offset().top,
				refW : el.getTotalWidth(),
				refH : el.getTotalHeight()
			};

			var xVal, yVal;

			// ��������Ѿ���positionHelper��װ��ֱ�Ӹ���positionHelperλ�ã�������Ҫ����һ��λ�ð�װ��
			var helper = container.parent(".positionHelper");
			var isHeperExist = (helper.size() == 0) ? false : true;
			if (!isHeperExist) {
				helper = $('<div class="positionHelper"></div>');
			}
			// ֮ǰ������������أ���Ҫ��ʾ������λ�����
			helper.show();
			helper.css({
						position : 'absolute',
						left : dims.refX,
						top : dims.refY,
						width : dims.refW,
						height : dims.refH
					});
			if (!isHeperExist) {
				container.wrap(helper);
			}
			// get X pos
			switch (options.positionOpts.posX) {
				case 'left' :
					xVal = 0;
					break;
				case 'center' :
					xVal = dims.refW / 2;
					break;
				case 'right' :
					xVal = dims.refW;
					break;
			};

			// get Y pos
			switch (options.positionOpts.posY) {
				case 'top' :
					yVal = 0;
					break;
				case 'center' :
					yVal = dims.refH / 2;
					break;
				case 'bottom' :
					yVal = dims.refH;
					break;
			};

			// add the offsets (zero by default)
			xVal += options.positionOpts.offsetX;
			yVal += options.positionOpts.offsetY;

			// position the object vertically
			if (options.positionOpts.directionV == 'up') {// �����������չ��
				container.css({
							top : 'auto',
							bottom : yVal
						});
				if (options.positionOpts.detectV && fitTopVertical(container)) {
					container.css({
								bottom : 'auto',
								top : yVal
							});
				}
			} else {// �����������չ��
				container.css({
							bottom : 'auto',
							top : yVal
						});
				if (options.positionOpts.detectV && fitDownVertical(container)) {
					if (fitTopVertical(container)) {
					} else {
						container.css({
									top : 'auto',
									bottom : yVal
								});
					}
				}
			};

			// and horizontally
			if (options.positionOpts.directionH == 'left') {// �����������չ��
				container.css({
							left : 'auto',
							right : xVal,
							'z-index' : 9999
						});
				if (options.positionOpts.detectH
						&& fitLeftHorizontal(container)) {
					if (fitRigHorizontal(container)) {
						// container.css({
						// left : -$(el).width(),
						// 'z-index' : 9999
						// });
					} else {
						container.css({
									right : 'auto',
									left : xVal,
									'z-index' : 9999
								});
					}
				}
			} else {// �����������չ��
				container.css({
							right : 'auto',
							left : xVal,
							'z-index' : 9999
						});
				if (options.positionOpts.detectH && fitRigHorizontal(container)) {
					if (fitLeftHorizontal(container)) {
						// container.css({
						// left : -$(el).width(),
						// 'z-index' : 9999
						// });
					} else {
						container.css({
									left : 'auto',
									right : xVal,
									'z-index' : 9999
								});
					}
				}
			};
		},

		/**
		 * ����combo��ʼֵ
		 */
		setInitValue : function() {
			var p = this.options, el = this.el;
			if (p.hiddenId && !$.woo.isEmpty($("#" + p.hiddenId).val())) {
				p.idStr = $("#" + p.hiddenId).val();
				p.idArr = p.idStr.split(p.valueSeparator);
			}
			p.nameStr = el.val();
			if (!$.woo.isEmpty(p.nameStr)) {
				p.nameArr = p.nameStr.split(p.valueSeparator);
			}
		}

	});

	/**
	 * ����ҳ��/��������������������
	 */
	var killAllCombo = function() {
		$.each(waitKillCombo, function(i) {
					if (waitKillCombo[i].comboOpen) {
						waitKillCombo[i].kill();
					};
				});
	};

	$(document).click(function(e, isTrigger) {
				if (!isTrigger) {// ��������ɳ��򴥷��ĵ��
					killAllCombo();
				}
			});
	$.fn.getTotalWidth = function() {
		var thisWidth = $(this).height();
		var totalWidth = thisWidth + this.getPadding('paddingRight')
				+ this.getPadding('paddingLeft')
				+ this.getPadding('borderRightWidth')
				+ this.getPadding('borderLeftWidth')
		return totalWidth;
	};

	$.fn.getTotalHeight = function() {
		var thisHeight = $(this).height();
		var totalHeight = thisHeight + this.getPadding('paddingTop')
				+ this.getPadding('paddingBottom')
				+ this.getPadding('borderTopWidth')
				+ this.getPadding('borderBottomWidth')
		return totalHeight;
	};

	$.fn.getPadding = function($cssName) {
		var padding = parseInt($(this).css($cssName));
		return padding ? padding : 0;
	};

	/**
	 * ��ȡ�����¼���λ��
	 */
	function getEPos(e) {
		var pos = {
			left : e.pageX,
			top : e.pageY
			// position : 'absolute'
		}
		return pos;
	}

	/**
	 * ���Ԫ���Ƿ����ˮƽ���
	 */
	function fitLeftHorizontal(el, leftOffset) {
		var leftVal = parseInt(leftOffset) || $(el).offset().left;
		return (leftVal <= $(el).width());
	};
	/**
	 * ���Ԫ���Ƿ��ұ�ˮƽ���
	 */
	function fitRigHorizontal(el, rightOffset) {
		var rightVal = parseInt(rightOffset)
				|| (docWidth - $(el).offset().left);
		return (rightVal <= $(el).width());
	};
	/**
	 * ���Ԫ���Ƿ�ֱ�����
	 */
	function fitTopVertical(el, topOffset) {
		var topVal = parseInt(topOffset) || $(el).offset().top;
		return (topVal <= $(el).height());
	};

	/**
	 * ���Ԫ���Ƿ�ֱ�����
	 */
	function fitDownVertical(el, topOffset) {
		var downVal = parseInt(topOffset) || (docHeight - $(el).offset().top);
		return (downVal <= $(el).height());
	};

})(jQuery);