/**
 * menu - tui ����jQuery1.4.2+
 *
 * ����˵���� ֧�ֶ༶�˵� ֧�ֲ˵�һҳ���� �˵���֧��html��js�����
 *
 * ȱ�ٹ��ܣ� ��̬����˵� �Ӳ˵�ֻ�ܴ�ֱ��ˮƽ���һ��
 */
(function($) {
	// $.woo.addCssStyle('../../themes/default/menu.css');
	// $.woo.addCssStyle('../../themes/default/menu.theme.css');
	var waitKillMenus = [];// ���ڴ��ҳ��/������������Ҫ���ݻٵĲ˵�
	$.woo.component.subclass('woo.yxmenu', {
		options : {
			/**
			 * ���������飬���ô����Ժ���Զ�ת��content
			 */
			items : [],
			/**
			 * ����������Դ
			 */
			content : null,
			/**
			 * �˵�Ĭ�Ͽ��
			 */
			width : 200,
			/**
			 * �˵������
			 */
			maxHeight : 200,
			/**
			 * �Ƿ����kill����
			 */
			isCanKill : true,
			/**
			 * �˵�λ���������
			 */
			positionOpts : {
				posX : 'left',
				posY : 'bottom',
				offsetX : 0,
				offsetY : 0,
				/**
				 * �˵�����չ��
				 */
				directionH : 'right',
				/**
				 * �˵�����չ��
				 */
				directionV : 'down',
				/**
				 * �Ƿ����ˮƽ�����⣬������ò˵�����չ��������������ұ�չ����ʱ�򵽴���Ļ�߽磬�Զ�����չ��
				 */
				detectH : true,
				/**
				 * �Ƿ���д�ֱ�����⣬������ò˵�����չ��������������±�չ����ʱ�򵽴���Ļ�߽磬�Զ�����չ��
				 */
				detectV : true
			},
			/**
			 * �ٶ���ʾ/���ز˵�����
			 */
			showSpeed : 200,
			/**
			 * ��ʾ�˵��ķ�ʽ��1.inner ����Ⱦ�Ľڵ��ڲ�������ĵط���ʾ�˵� 2.down����Ⱦ�Ľڵ��·���ʾ�˵�
			 */
			showMenuType : 'inner',
			/**
			 * ���ɼ��˵�Ч�����ٶ�
			 */
			crossSpeed : 200,
			/**
			 * �Ƿ�����˵�ð��
			 */
			isBubble : false,

			// ----- ����Ϊ��ʽ���� -----
			/**
			 * һҳ������ʽ
			 */
			groupClass : 'ui-group',
			/**
			 * �˵��ӳټ���ʱ��ʽ
			 */
			loadingState : 'ui-state-loading',
			/**
			 * �ƶ����˵���ʱ��ʽ
			 */
			linkHover : 'ui-state-hover',
			/**
			 * �˵��ָ�����ʽ
			 */
			lineClass : 'ui-line',
			/**
			 * ����ʽ�༶�˵��ƶ�����һ����ǰ�˵������ʽ
			 */
			flyOutOnState : 'ui-state-default',
			/**
			 * ���Ӳ˵��Ĳ˵�����ʽ
			 */
			nextMenuLink : 'ui-icon-triangle-1-e',
			/**
			 * �˵����ı���ʽ
			 */
			itemTextClass : 'item-text'
		},

		_create : function() {
			var menu = this, el = this.el, options = this.options;

			// items��������content������ͨ��items��̬����content
			if ($.isArray(options.items)) {
				options.content = this.createContentByItems(options.items);
			}
			// ���û����ʾ�˵���ֱ��kill add by chengl 2011-06-30
			if (this.showMenuItems.length == 0) {
				killAllMenus();
				return;
			}
			// alert(options.content.html())
			// ����dom�ڵ�
			this.container = $('<div class="fg-menu-container ui-widget ui-widget-content ui-corner-all"></div>');
			this.container.append($(options.content));
			this.menuOpen = false;// ��ʶ�˵��Ƿ񱻴�
			this.menuExists = false;// ��ʶ�˵��Ƿ����

			// ������������˵�
			if (options.type == 'click') {
				waitKillMenus.push(menu);// ���뱻ɱ����
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
							}// ����return false��������õ��ۼӼ�ð���¼��򲻻ᴥ��
						});
			} else if (options.type == 'rclick') {
				waitKillMenus.push(menu);// ���뱻ɱ����
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
			} else {// ֱ�Ӳ����˵�
				if (options.isCanKill == true) {
					waitKillMenus.push(menu);
				}
				menu.showMenu();
			}
		},

		/**
		 * ��ʾ�˵�
		 *
		 * @param {pos}
		 *            ��ʾ�˵���λ��
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
			// ���ò˵�λ��
			menu.setPosition(pos);
			el.addClass('fg-menu-open').addClass(options.callerOnState);
			container.hide().slideDown(options.showSpeed)
					.find('.fg-menu:eq(0)');
			menu.menuOpen = true;
			el.removeClass(options.loadingState);

		},

		/**
		 * �����˵����ĺ���
		 *
		 * @param {pos}
		 *            ��ʾ�˵���λ��
		 */
		create : function(pos) {
			var menu = this, el = this.el, container = this.container, options = this.options;
			var maxWidth = options.width;
			//�Ҽ��˵��������Ӧ update by chengl 2011-09-15
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
			container.find('ul, li a').addClass('ui-corner-all');// ��������Բ�Ǿ��ε�css,ie8��Ч

			// �ָ���
			container.find("li[menuType$='line']").addClass(options.lineClass);

			if (container.find('ul').size() > 1) {
				menu.flyout(container);// �༶�˵�����
			} else {
				container.find('a[disabled!="true"]').click(function() {
							menu.chooseItem(this);
						});
			};
			// ��������
			container.find('a[hide="true"]').hide();
			// ���õ���˵��¼���������live�޷�ʵ�֣�container.find('.fg-menu li')

			container.find(':data(item)').bind("click", function(e) {
						var item = $(this).data("item");
						item.action.apply($(this), [item, e, el]);
					});

			if (options.linkHover) {
				var allLinks = container
						.find('.fg-menu li a[disabled!="true"]');
				allLinks.hover(function() {
							// ��ҳ�˵���û������ƶ�Ч��
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
		 * �ݻٵ����˵�����
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
		 * ��ʾ������
		 */
		showLoading : function() {
			this.el.addClass(this.options.loadingState);
		},

		/**
		 * ����˵����¼�
		 */
		chooseItem : function(item) {
			this.kill();
		},

		/**
		 * ����ʽ�Ķ༶�˵�
		 */
		flyout : function(caller) {
			var menu = this, options = this.options;
			if (caller == this.container) {
				caller.addClass('fg-menu-flyout');
			}
			caller.children("ul").children("li").each(function() {
						// ������е�ҳ���飬�¼����ϼ���ʾ��ͬһҳ��
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
		 * ��ÿһ���˵�����е���ʽ�༶����
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

									// �˵���ˮƽ����ֱ���
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
							clearTimeout(hideTimer);// ����ƶ��������Ӳ˵���������˵��Ƴ������Ӳ˵��¼�
							// ���ø��˵������ʽ
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
		 * ���õ���ʽ�༶�˵�
		 */
		hideFlyoutMenu : function() {
			var allLists = this.container.find('ul ul');
			allLists.removeClass('ui-widget-content').hide();
		},
		/**
		 * ��һҳ�Ͻ��в˵�����
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
		 * ���ò˵���ʾλ��
		 *
		 * @param {pos}
		 *            ��ʾ�˵���λ��
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

			// ��������Ѿ���positionHelper��װ��ֱ�Ӹ���positionHelperλ�ã�������Ҫ����һ��λ�ð�װ��
			var helper = container.parent(".positionHelper");
			var isHeperExist = (helper.size() == 0) ? false : true;
			if (!isHeperExist) {
				helper = $('<div class="positionHelper"></div>');
			}
			// ֮ǰ�˵������أ���Ҫ��ʾ������λ�����
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
			if (options.positionOpts.directionV == 'up') {// �˵�����չ��
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
			} else {// �˵�����չ��
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
			if (options.positionOpts.directionH == 'left') {// �˵�����չ��
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
			} else {// �˵�����չ��
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
		 * �ݹ�ͨ��items�������ul li��ɵ�content
		 *
		 * @param {items}
		 *            �˵�����
		 * @param {j}
		 *            �ݹ���
		 */
		createContentByItems : function(items, j) {
			var options = this.options, t = this;
			if (!j) {
				t.showMenuItems = [];
			}
			j = j ? j : 0;// ��¼��j��
			var domTemp;
			if (j == 0) {
				domTemp = $("<div/>");// ��ʱdiv������ʱͨ��.children()ȥ��
			} else {
				domTemp = this;
			}

			var ul = $("<ul id='menu" + j + "'/>");

			for (var i = 0, l = items.length; i < l; i++) {
				// �����items��Ϊ�˵��飬����Ϊ�˵���
				var item = items[i];
				item.type = item.type ? item.type : 'item';
				if (item.type == 'line') {// �ָ������⴦��
					var li = $("<li menuType='line'/>");
					ul.append(li);
				} else {

					var items_ = item.items;
					var text = item.text;
					if ($.isFunction(item.renderer)) {
						text = item.renderer.apply(item, [text]);
					}

					if ($.isFunction(item.showMenuFn)) {
						if (item.showMenuFn.apply(item, [t]) == false) {// ���������ȥ
							item.hide = true;
						} else {
							t.showMenuItems.push(item);
						}
					} else {
						t.showMenuItems.push(item);
					}
					if ($.isFunction(item.enableMenuFn)) {
						if (item.enableMenuFn.apply(item, [t]) == false) {// ���������ȥ
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
					if (i == 0) {// �����ѭ����һ��������ul
						domTemp.append(ul);
					}
					// ���������
					if (items_) {
						// callee ������ arguments �����һ����Ա������ʾ�Ժ��������������
						arguments.callee.apply(li, [items_, ++j]);
					}
				}
			}
			// alert(domTemp.html())

			return domTemp.children();
		}
	});

	/**
	 * ����ҳ��/���������в˵�����
	 */
	var killAllMenus = function() {
		$.each(waitKillMenus, function(i) {
					if (waitKillMenus[i].menuOpen) {
						waitKillMenus[i].kill();
					};
				});
	};
	$(document).click(function(e, isTrigger) {
				if (!isTrigger) {// ��������ɳ��򴥷��ĵ��
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
	 * ���Ԫ���Ƿ�ˮƽ���
	 */
	function fitHorizontal(el, leftOffset) {
		var leftVal = parseInt(leftOffset) || $(el).offset().left;
		var scrollLeft = $(self).scrollLeft();
		return (leftVal + $(el).width() <= $(self).width() + scrollLeft && leftVal
				- scrollLeft >= 0);
	};
	/**
	 * ���Ԫ���Ƿ�ֱ���
	 */
	function fitVertical(el, topOffset) {
		var topVal = parseInt(topOffset) || $(el).offset().top;
		var scrollTop = $(self).scrollTop();
		return (topVal + $(el).height() <= $(self).height() + scrollTop && topVal
				- scrollTop >= 0);
	};

})(jQuery);