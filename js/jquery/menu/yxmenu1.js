/**
 * menu - tui 基于jQuery1.4.2+
 *
 * 功能说明： 支持多级菜单 支持菜单一页分类 菜单项支持html，js数组等
 *
 * 缺少功能： 动态加入菜单 子菜单只能垂直及水平检测一次
 */
(function($) {
	// $.woo.addCssStyle('../../themes/default/menu.css');
	// $.woo.addCssStyle('../../themes/default/menu.theme.css');
	var waitKillMenus = [];// 用于存放页面/容器上所有需要被摧毁的菜单
	$.woo.component.subclass('woo.yxmenu', {
		options : {
			/**
			 * 数据项数组，设置此属性后会自动转成content
			 */
			items : [],
			/**
			 * 数据内容来源
			 */
			content : null,
			/**
			 * 菜单默认宽度
			 */
			width : 200,
			/**
			 * 菜单最大宽度
			 */
			maxHeight : 200,
			/**
			 * 是否加入kill队列
			 */
			isCanKill : true,
			/**
			 * 菜单位置相关设置
			 */
			positionOpts : {
				posX : 'left',
				posY : 'bottom',
				offsetX : 0,
				offsetY : 0,
				/**
				 * 菜单往左展开
				 */
				directionH : 'right',
				/**
				 * 菜单往下展开
				 */
				directionV : 'down',
				/**
				 * 是否进行水平溢出检测，如果设置菜单往右展开，当在浏览器右边展开的时候到达屏幕边界，自动往左展开
				 */
				detectH : true,
				/**
				 * 是否进行垂直溢出检测，如果设置菜单往下展开，当在浏览器下边展开的时候到达屏幕边界，自动往上展开
				 */
				detectV : true
			},
			/**
			 * 速度显示/隐藏菜单毫秒
			 */
			showSpeed : 200,
			/**
			 * 显示菜单的方式：1.inner 在渲染的节点内部即点击的地方显示菜单 2.down在渲染的节点下方显示菜单
			 */
			showMenuType : 'inner',
			/**
			 * 过渡级菜单效果的速度
			 */
			crossSpeed : 200,
			/**
			 * 是否允许菜单冒泡
			 */
			isBubble : false,

			// ----- 以下为样式设置 -----
			/**
			 * 一页分组样式
			 */
			groupClass : 'ui-group',
			/**
			 * 菜单延迟加载时样式
			 */
			loadingState : 'ui-state-loading',
			/**
			 * 移动到菜单项时样式
			 */
			linkHover : 'ui-state-hover',
			/**
			 * 菜单分割线样式
			 */
			lineClass : 'ui-line',
			/**
			 * 弹出式多级菜单移动后到下一级当前菜单项的样式
			 */
			flyOutOnState : 'ui-state-default',
			/**
			 * 有子菜单的菜单项样式
			 */
			nextMenuLink : 'ui-icon-triangle-1-e',
			/**
			 * 菜单项文本样式
			 */
			itemTextClass : 'item-text'
		},

		_create : function() {
			var menu = this, el = this.el, options = this.options;

			// items参数优先content参数，通过items动态产生content
			if ($.isArray(options.items)) {
				options.content = this.createContentByItems(options.items);
			}
			// 如果没有显示菜单，直接kill add by chengl 2011-06-30
			if (this.showMenuItems.length == 0) {
				killAllMenus();
				return;
			}
			// alert(options.content.html())
			// 容器dom节点
			this.container = $('<div class="fg-menu-container ui-widget ui-widget-content ui-corner-all"></div>');
			this.container.append($(options.content));
			this.menuOpen = false;// 标识菜单是否被打开
			this.menuExists = false;// 标识菜单是否存在

			// 左键单击产生菜单
			if (options.type == 'click') {
				waitKillMenus.push(menu);// 加入被杀队列
				el.mousedown(function(e) {
							if (!menu.menuOpen) {
								menu.showLoading();
							};
						}).click(function(e) {
							if (menu.menuOpen == false) {
								var pos = getEPos(e);
								menu.showMenu(pos);
							} else {
								menu.kill();
							};
							if (!options.isBubble) {
								return false;
							}// 这里return false后后面设置的累加及冒泡事件则不会触发
						});
			} else if (options.type == 'rclick') {
				waitKillMenus.push(menu);// 加入被杀队列
				el.mousedown(function(e) {
							if (!menu.menuOpen) {
								menu.showLoading();
							};
						}).bind('contextmenu', function(e,pos) {
							if (menu.menuOpen == true) {
								menu.kill();
							}
							if(!pos)
							  pos = getEPos(e);
							menu.showMenu(pos);
							if (!options.isBubble) {
								return false;
							}
						});
			} else {// 直接产生菜单
				if (options.isCanKill == true) {
					waitKillMenus.push(menu);
				}
				menu.showMenu();
			}
		},

		/**
		 * 显示菜单
		 *
		 * @param {pos}
		 *            显示菜单的位置
		 */
		showMenu : function(pos) {
			if (this.showMenuItems.length == 0) {
				killAllMenus();
				return;
			}
			var menu = this, el = this.el, container = this.container, options = this.options;
			killAllMenus();
			if (!menu.menuExists) {
				menu.create(pos);
			};
			// 设置菜单位置
			menu.setPosition(pos);
			el.addClass('fg-menu-open').addClass(options.callerOnState);
			container.hide().slideDown(options.showSpeed)
					.find('.fg-menu:eq(0)');
			menu.menuOpen = true;
			el.removeClass(options.loadingState);

		},

		/**
		 * 创建菜单核心函数
		 *
		 * @param {pos}
		 *            显示菜单的位置
		 */
		create : function(pos) {
			var menu = this, el = this.el, container = this.container, options = this.options;
			var maxWidth = options.width;
			//右键菜单宽度自适应 update by chengl 2011-09-15
			for (var i = 0; i < options.items.length; i++) {
				var text = options.items[i].text;
				var textLen = text.length * 21;
				if (textLen > maxWidth) {
					maxWidth = textLen;
				}
			}

			container.css({
						width : maxWidth
					}).appendTo('body').find('ul:first')
					.not('.fg-menu-breadcrumb').addClass('fg-menu');
			container.find('ul, li a').addClass('ui-corner-all');// 加上制作圆角矩形的css,ie8无效

			// 分割线
			container.find("li[menuType$='line']").addClass(options.lineClass);

			if (container.find('ul').size() > 1) {
				menu.flyout(container);// 多级菜单处理
			} else {
				container.find('a[disabled!="true"]').click(function() {
							menu.chooseItem(this);
						});
			};
			// 设置隐藏
			container.find('a[hide="true"]').hide();
			// 设置点击菜单事件，这里用live无法实现？container.find('.fg-menu li')

			container.find(':data(item)').bind("click", function(e) {
						var item = $(this).data("item");
						item.action.apply($(this), [item, e, el]);
					});

			if (options.linkHover) {
				var allLinks = container
						.find('.fg-menu li a[disabled!="true"]');
				allLinks.hover(function() {
							// 单页菜单组没有鼠标移动效果
							if ($(this).parent().attr("menuType") != 'group') {
								var menuitem = $(this);
								$('.' + options.linkHover)
										.removeClass(options.linkHover).blur()
										.parent().removeAttr('id');
								$(this).addClass(options.linkHover).focus()
										.parent().attr('id', 'active-menuitem');
							}
						}, function() {
							if ($(this).parent().attr("menuType") != 'group') {
								$(this).removeClass(options.linkHover).blur()
										.parent().removeAttr('id');
							}
						});
			};

			// menu.setPosition(pos);
			menu.menuExists = true;
		},

		/**
		 * 摧毁单个菜单对象
		 */
		kill : function() {
			var menu = this, el = this.el, container = this.container, options = this.options;

			// el.removeClass(options.loadingState).removeClass('fg-menu-open')
			// .removeClass(options.callerOnState);
			container.find('li').removeClass(options.linkHoverSecondary)
					.find('a').removeClass(options.linkHover);
			if (options.flyOutOnState) {
				container.find('li a').removeClass(options.flyOutOnState);
			};
			if (options.callerOnState) {
				el.removeClass(options.callerOnState);
			};
			if (container.is('.fg-menu-flyout')) {
				menu.hideFlyoutMenu();
			};
			container.parent().hide();
			menu.menuOpen = false;
		},

		/**
		 * 显示进度条
		 */
		showLoading : function() {
			this.el.addClass(this.options.loadingState);
		},

		/**
		 * 点击菜单项事件
		 */
		chooseItem : function(item) {
			this.kill();
		},

		/**
		 * 弹出式的多级菜单
		 */
		flyout : function(caller) {
			var menu = this, options = this.options;
			if (caller == this.container) {
				caller.addClass('fg-menu-flyout');
			}
			caller.children("ul").children("li").each(function() {
						// 如果进行单页分组，下级与上级显示在同一页上
						var menuType = $(this).attr('menuType');
						if (menuType == 'group') {
							menu.onePageGroupMenu($(this));
						} else if (menuType == 'line') {

						} else {
							menu.flyoutItemFn($(this));
						}
					});
		},

		/**
		 * 对每一个菜单项进行弹出式多级处理
		 */
		flyoutItemFn : function(item) {
			var menu = this, container = this.container, options = this.options;
			var linkWidth = container.width();
			var showTimer, hideTimer;
			var allSubLists = item.children('ul');
			if (allSubLists.size() > 0) {
				allSubLists.css({
							left : linkWidth,
							width : linkWidth
						}).hide();

				item.children('a[disabled!="true"]:eq(0)')
						.addClass('fg-menu-indicator').html('<span>'
								+ item.children('a:eq(0)').html()
								+ '</span><span class="ui-icon '
								+ options.nextMenuLink + '"></span>').hover(
								function() {
									clearTimeout(hideTimer);
									var subList = $(this).next();

									// 菜单项水平跟垂直检测
									if (!fitVertical(subList, item.offset().top)) {
										subList.css({
													top : 'auto',
													bottom : 0
												});
									};
									if (!fitHorizontal(subList,
											item.offset().left + 100)) {
										subList.css({
													left : 'auto',
													right : linkWidth,
													'z-index' : 999
												});
									};

									showTimer = setTimeout(function() {
												subList
														.addClass('ui-widget-content')
														.show(options.showSpeed)
														.attr('aria-expanded',
																'true');
											}, 300);
								}, function() {
									clearTimeout(showTimer);
									var subList = $(this).next();
									hideTimer = setTimeout(function() {
										subList
												.removeClass('ui-widget-content')
												.hide(options.showSpeed).attr(
														'aria-expanded',
														'false');
									}, 400);
								});
				item.children('ul').hover(function() {
							clearTimeout(hideTimer);// 如果移动到的是子菜单，清除父菜单移出隐藏子菜单事件
							// 设置父菜单项的样式
							if ($(this).prev().is('a.fg-menu-indicator')) {
								$(this).prev().addClass(options.flyOutOnState);
							}
						}, function() {
							hideTimer = setTimeout(function() {
										allSubLists.hide(options.showSpeed);
										container
												.children(options.flyOutOnState)
												.removeClass(options.flyOutOnState);
									}, 500);
						});
				menu.flyout(item);
			}

		},
		/**
		 * 重置弹出式多级菜单
		 */
		hideFlyoutMenu : function() {
			var allLists = this.container.find('ul ul');
			allLists.removeClass('ui-widget-content').hide();
		},
		/**
		 * 在一页上进行菜单分组
		 */
		onePageGroupMenu : function(caller) {
			var menu = this, options = this.options;
			var callerHref = caller.children("a");
			callerHref.addClass(options.groupClass);
			caller.children("ul").children("li").each(function() {
						caller.append($(this));
						if ($(this).children('ul').size() > 0) {
							menu.flyoutItemFn($(this));
							// menu.flyout($(this));
						}
					});
			caller.children("ul").remove();

		},
		/**
		 * 设置菜单显示位置
		 *
		 * @param {pos}
		 *            显示菜单的位置
		 */
		setPosition : function(pos) {
			var menu = this, el = this.el, container = this.container, options = this.options, dims;

			if (options.showMenuType == 'inner' && pos) {
				dims = {
					refX : pos.left,
					refY : pos.top,
					refW : 0,
					refH : 0
				};
			} else {
				dims = {
					refX : el.offset().left,
					refY : el.offset().top,
					refW : el.getTotalWidth(),
					refH : el.getTotalHeight()
				};
			};

			var xVal, yVal;

			// 容器如果已经被positionHelper包装，直接更改positionHelper位置，否则需要创建一个位置包装器
			var helper = container.parent(".positionHelper");
			var isHeperExist = (helper.size() == 0) ? false : true;
			if (!isHeperExist) {
				helper = $('<div class="positionHelper"></div>');
			}
			// 之前菜单已隐藏，需要显示，否则定位会出错
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
			if (options.positionOpts.directionV == 'up') {// 菜单往上展开
				container.css({
							top : 'auto',
							bottom : yVal
						});
				if (options.positionOpts.detectV && !fitVertical(container)) {
					container.css({
								bottom : 'auto',
								top : yVal
							});
				}
			} else {// 菜单往下展开
				container.css({
							bottom : 'auto',
							top : yVal
						});
				if (options.positionOpts.detectV && !fitVertical(container)) {
					container.css({
								top : 'auto',
								bottom : yVal
							});
				}
			};

			// and horizontally
			if (options.positionOpts.directionH == 'left') {// 菜单往左展开
				container.css({
							left : 'auto',
							right : xVal,
							'z-index' : 9999
						});
				if (options.positionOpts.detectH && !fitHorizontal(container)) {
					container.css({
								right : 'auto',
								left : xVal,
								'z-index' : 9999
							});
				}
			} else {// 菜单往右展开
				container.css({
							right : 'auto',
							left : xVal,
							'z-index' : 9999
						});
				if (options.positionOpts.detectH && !fitHorizontal(container)) {
					container.css({
								left : 'auto',
								right : xVal,
								'z-index' : 9999
							});
				}
			};
		},
		showMenuItems : [],
		/**
		 * 递归通过items数组产生ul li组成的content
		 *
		 * @param {items}
		 *            菜单数组
		 * @param {j}
		 *            递归层次
		 */
		createContentByItems : function(items, j) {
			var options = this.options, t = this;
			if (!j) {
				t.showMenuItems = [];
			}
			j = j ? j : 0;// 记录第j层
			var domTemp;
			if (j == 0) {
				domTemp = $("<div/>");// 临时div，返回时通过.children()去掉
			} else {
				domTemp = this;
			}

			var ul = $("<ul id='menu" + j + "'/>");

			for (var i = 0, l = items.length; i < l; i++) {
				// 如果有items则为菜单组，否则为菜单项
				var item = items[i];
				item.type = item.type ? item.type : 'item';
				if (item.type == 'line') {// 分割线特殊处理
					var li = $("<li menuType='line'/>");
					ul.append(li);
				} else {

					var items_ = item.items;
					var text = item.text;
					if ($.isFunction(item.renderer)) {
						text = item.renderer.apply(item, [text]);
					}

					if ($.isFunction(item.showMenuFn)) {
						if (item.showMenuFn.apply(item, [t]) == false) {// 把组件传回去
							item.hide = true;
						} else {
							t.showMenuItems.push(item);
						}
					} else {
						t.showMenuItems.push(item);
					}
					if ($.isFunction(item.enableMenuFn)) {
						if (item.enableMenuFn.apply(item, [t]) == false) {// 把组件传回去
							item.disabled = true;
						}
					}
					var textClass = item.disabled
							? "item-disabled"
							: options.itemTextClass;
					var li = $([
							"<li class='menu-link' menuType='" + item.type
									+ "'>",
							"<a  href='javascript:void(0)'"
									+ (item.hide ? "hide=true" : "")
									+ (item.disabled ? "disabled=true" : "")
									+ ">",
							"<span class='item-icon "
									+ (item.icon ? item.icon : '')
									+ "'></span><span type='text' class='"
									+ textClass + "'>" + text + "</span>",
							"</a>", "</li>"].join(""));

					if ($.isFunction(item.action) && !item.disabled) {
						li.data("item", item);
					};

					ul.append(li);
					if (i == 0) {// 如果是循环第一个，加入ul
						domTemp.append(ul);
					}
					// 如果有子项
					if (items_) {
						// callee 属性是 arguments 对象的一个成员，它表示对函数对象本身的引用
						arguments.callee.apply(li, [items_, ++j]);
					}
				}
			}
			// alert(domTemp.html())

			return domTemp.children();
		}
	});

	/**
	 * 隐藏页面/容器上所有菜单对象
	 */
	var killAllMenus = function() {
		$.each(waitKillMenus, function(i) {
					if (waitKillMenus[i].menuOpen) {
						waitKillMenus[i].kill();
					};
				});
	};
	$(document).click(function(e, isTrigger) {
				if (!isTrigger) {// 如果不是由程序触发的点击
					killAllMenus()
				}
			});
	$.woo.yxmenu.killAllMenus = killAllMenus;

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
	 * 检测元素是否水平溢出
	 */
	function fitHorizontal(el, leftOffset) {
		var leftVal = parseInt(leftOffset) || $(el).offset().left;
		var scrollLeft = $(self).scrollLeft();
		return (leftVal + $(el).width() <= $(self).width() + scrollLeft && leftVal
				- scrollLeft >= 0);
	};
	/**
	 * 检测元素是否垂直溢出
	 */
	function fitVertical(el, topOffset) {
		var topVal = parseInt(topOffset) || $(el).offset().top;
		var scrollTop = $(self).scrollTop();
		return (topVal + $(el).height() <= $(self).height() + scrollTop && topVal
				- scrollTop >= 0);
	};

})(jQuery);