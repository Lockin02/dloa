/**
 * combobox - tui 基于jQuery1.4.2+
 *
 * 功能说明： 下拉面板，可放入HTML
 *
 * 缺少功能： 继承加入
 */
(function($) {
	var waitKillCombo = [];// 用于存放页面/容器上所有需要被摧毁的下拉面板
	var docWidth = 0;
	var docHeight = 0;
	$.woo.component.subclass('woo.yxcombo', {
		options : {
			/**
			 * html
			 */
			html : '',
			/**
			 * 数据内容来源
			 */
			content : null,
			/**
			 * 下拉面板默认宽度
			 */
			width : 400,
			/**
			 * 下拉面板默认高度
			 */
			height : null,
			/** ******以下5个属性供继承类使用，如下拉表格，下拉树，下拉选择等************* */
			/**
			 * 隐藏控件id
			 */
			hiddenId : null,
			/**
			 * id值数组
			 */
			idArr : [],
			/**
			 * 名称值数组
			 */
			nameArr : [],
			/**
			 * id值，以,隔开
			 */
			idStr : "",
			/**
			 * 名称值，以,隔开
			 */
			nameStr : "",
			/**
			 * 下拉面板位置相关设置
			 */
			positionOpts : {
				posX : 'left',
				posY : 'bottom',
				offsetX : 0,
				offsetY : 0,
				/**
				 * 下拉面板往左展开
				 */
				directionH : 'right',
				/**
				 * 下拉面板往下展开
				 */
				directionV : 'down',
				/**
				 * 是否进行水平溢出检测，如果设置下拉面板往右展开，当在浏览器右边展开的时候到达屏幕边界，自动往左展开
				 */
				detectH : true,
				/**
				 * 是否进行垂直溢出检测，如果设置下拉面板往下展开，当在浏览器下边展开的时候到达屏幕边界，自动往上展开
				 */
				detectV : true
			},
			/**
			 * 速度显示/隐藏下拉面板毫秒
			 */
			showSpeed : 200,
			scroll : 'scroll',// 是否允许滚动
			valueSeparator : ','
		},

		_create : function() {
			var combo = this, el = this.el, options = this.options;
			// 容器dom节点
			this.container = $('<div   class="fg-menu-container ui-widget ui-widget-content ui-corner-all">'
					+ options.html + '</div>');
			this.container.append($(options.content));
			this.comboOpen = false;// 标识下拉面板是否被打开
			this.comboExists = false;// 标识下拉面板是否存在

			// 单击产生下拉面板
			waitKillCombo.push(combo);// 加入被杀队列
			var fn = function(e) {
				if (combo.comboOpen == false) {
					var pos = getEPos(e);
					combo.showCombo(pos);
				}
				return false;// 这里return false后后面设置的累加及冒泡事件则不会触发
			};
			el.bind('click', fn);
			// el.bind('focus', fn);//如果弹出窗口选择后会下拉，暂时去掉

			// 失焦去除下拉
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
		 * 移除下拉面板
		 */
		remove : function() {
			this.container.empty();
			this.el.unbind();
			this.destroy();
		},
		/**
		 * 摧毁控件:此方法不建议使用(使用remove)
		 */
		destroy : function() {
			// this.container.remove();
		},
		/**
		 * 显示下拉面板
		 *
		 * @param {pos}
		 *            显示下拉面板的位置
		 */
		showCombo : function(pos) {
			var combo = this, el = this.el, container = this.container, options = this.options;
			killAllCombo();
			$(el).trigger('show_combo');// TODO:后面加上返回值判断
			if (!combo.comboExists) {
				combo.create(pos);
			};
			// 设置下拉面板位置
			combo.setPosition(pos);
			container.hide().slideDown(options.showSpeed);
			combo.comboOpen = true;

		},

		/**
		 * 创建下拉面板核心函数
		 *
		 * @param {pos}
		 *            显示下拉面板的位置
		 */
		create : function(pos) {
			var combo = this, el = this.el, container = this.container, options = this.options;

			container.css({
				width : options.width,
				height : options.height,
				overflow : options.scroll
					// 出现滚动条
				}).appendTo('body');
			combo.comboExists = true;
			combo.setInitValue();
		},

		/**
		 * 摧毁单个下拉面板对象
		 */
		kill : function() {
			var combo = this, el = this.el, container = this.container, options = this.options;
			container.parent().hide();
			combo.comboOpen = false;
		},

		/**
		 * 设置下拉面板显示位置
		 *
		 * @param {pos}
		 *            显示下拉面板的位置
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

			// 容器如果已经被positionHelper包装，直接更改positionHelper位置，否则需要创建一个位置包装器
			var helper = container.parent(".positionHelper");
			var isHeperExist = (helper.size() == 0) ? false : true;
			if (!isHeperExist) {
				helper = $('<div class="positionHelper"></div>');
			}
			// 之前下拉面板已隐藏，需要显示，否则定位会出错
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
			if (options.positionOpts.directionV == 'up') {// 下拉面板往上展开
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
			} else {// 下拉面板往下展开
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
			if (options.positionOpts.directionH == 'left') {// 下拉面板往左展开
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
			} else {// 下拉面板往右展开
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
		 * 设置combo初始值
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
	 * 隐藏页面/容器上所有下拉面板对象
	 */
	var killAllCombo = function() {
		$.each(waitKillCombo, function(i) {
					if (waitKillCombo[i].comboOpen) {
						waitKillCombo[i].kill();
					};
				});
	};

	$(document).click(function(e, isTrigger) {
				if (!isTrigger) {// 如果不是由程序触发的点击
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
	 * 获取触发事件的位置
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
	 * 检测元素是否左边水平溢出
	 */
	function fitLeftHorizontal(el, leftOffset) {
		var leftVal = parseInt(leftOffset) || $(el).offset().left;
		return (leftVal <= $(el).width());
	};
	/**
	 * 检测元素是否右边水平溢出
	 */
	function fitRigHorizontal(el, rightOffset) {
		var rightVal = parseInt(rightOffset)
				|| (docWidth - $(el).offset().left);
		return (rightVal <= $(el).width());
	};
	/**
	 * 检测元素是否垂直上溢出
	 */
	function fitTopVertical(el, topOffset) {
		var topVal = parseInt(topOffset) || $(el).offset().top;
		return (topVal <= $(el).height());
	};

	/**
	 * 检测元素是否垂直下溢出
	 */
	function fitDownVertical(el, topOffset) {
		var downVal = parseInt(topOffset) || (docHeight - $(el).offset().top);
		return (downVal <= $(el).height());
	};

})(jQuery);