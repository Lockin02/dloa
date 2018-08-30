(function($) {

	$.woo.component.subclass('woo.yxoutlook', {
		options : {
			data : [],
			// 菜单展开触发事件
			revealtype : "click",
			// 当触发事件为 onmouseover时，设置鼠标移动的延迟时间
			mouseoverdelay : 50,
			// 是否只有一个展开项，为true时defaultexpanded属性只能设置唯一值
			collapseprev : false,
			// 默认展开的菜单项
			defaultexpanded : [0],
			// 全部收缩后，是否预留最后一项为展开
			onemustopen : false,
			// Should contents open by default be animated into view?
			animatedefault : false,
			// 设置记录当前展开项状态
			persiststate : false,
			// 设置菜单展开时速度
			animatespeed : 'fast'
		},
		// 初始化组件
		_create : function() {
			p = this.options;

			// var html = "";
			var html = $("<div></div>");
			for (var i = 0; i < p.data.menu.length; i++) {
				var fmenu = p.data.menu[i];
				var $f = $("<h3 class='menuheader expandable'>"
						+ "<img src='js/jquery/images/outlook/titlebullet.png' "
						+ "width='15' height = '15' style='vertical-align:bottom;'/>&nbsp;"
						+ fmenu.name + "</h3>");
				if (fmenu.url) {
					$f.data("title", fmenu.name);
					$f.data("url", fmenu.url);
					$f.click(function() {
								$(this).parent().find("h3")
										.removeClass("menuheaderclick");
								$(this).parent().find("h3")
										.addClass("menuheader");
								$(this).removeClass("menuheader");
								$(this).addClass("menuheaderclick");
								var title = $(this).data("title");
								var url = $(this).data("url");
								openTab(url, title);
							});
				}
				html.append($f);

				if (p.data.menu[i].item.length) {
					var $ul = $("<ul class='categoryitems'></ul>");
					html.append($ul);
					for (var j = 0; j < p.data.menu[i].item.length; j++) {
						var name = p.data.menu[i].item[j].name;
						var $li = $("<li><a href='javascript:void(0)'>" + name
								+ "</a></li>");
						$li.data("url", p.data.menu[i].item[j].url);
						$li.data("title", name);
						$li.click(function() {
									$(this).parent().parent().find("a").attr(
											"style", "background-color:ffffff");
									$(this).children().attr("style",
											"background-color:#CAD0E4");
									var url = $(this).data("url");
									var title = $(this).data("title");
									openTab(url, title);
								});
						$ul.append($li);
					}
				}

			}
			$(this.el).addClass("arrowlistmenu").append(html);

			ddaccordion.init({
						headerclass : "expandable",
						contentclass : "categoryitems",
						revealtype : p.revealtype,
						mouseoverdelay : p.mouseoverdelay,
						collapseprev : p.collapseprev,
						defaultexpanded : p.defaultexpanded,
						onemustopen : p.onemustopen,
						animatedefault : p.animatedefault,
						persiststate : p.persiststate,
						toggleclass : ["", "openheader"],
						togglehtml : ["prefix", "", ""],
						animatespeed : p.animatespeed,
						oninit : function(headers, expandedindices) {

						},
						onopenclose : function(header, index, state,
								isuseractivated) {
						}
					});

		}
	})
})(jQuery);
