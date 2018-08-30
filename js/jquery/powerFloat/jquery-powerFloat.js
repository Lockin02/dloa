/*!
 * jquery-powerFloat.js
 * jQuery 涓囪兘娴姩灞傛彃浠�
 * http://www.zhangxinxu.com/wordpress/?p=1328
 * 漏 by zhangxinxu
 * 2010-12-06 v1.0.0	鎻掍欢缂栧啓锛屽垵姝ヨ皟璇�
 * 2010-12-30 v1.0.1	闄愬畾灏栬瀛楃瀛椾綋锛岄伩鍏嶅彈娴忚鍣ㄨ嚜瀹氫箟瀛椾綋骞叉壈
 * 2011-01-03 v1.1.0	淇杩炵画鑾峰緱鐒︾偣鏄剧ず鍚庡張闅愯棌鐨刡ug
 						淇鍥剧墖鍔犺浇姝ｅ垯鍒ゆ柇涓嶅噯纭殑闂
 * 2011-02-15 v1.1.1	鍏充簬灞呬腑瀵归綈浣嶇疆鍒ゆ柇鐨勭壒娈婂鐞�
 * 2011-04-15 v1.2.0	淇娴姩灞傚惈鏈夎繃楂榮elect妗嗗湪IE涓嬬偣鍑讳細闅愯棌娴姩灞傜殑闂锛屽悓鏃朵紭鍖栦簨浠剁粦瀹�
 * 2011-09-13 v1.3.0 	淇涓や釜鑿滃崟hover鏃堕棿闂撮殧杩囩煭闅愯棌鍥炶皟涓嶆墽琛岀殑闂
 * 2012-01-13 v1.4.0	鍘婚櫎ajax鍔犺浇鐨勫瓨鍌�
                    	淇涔嬪墠鎸夌収ajax鍦板潃鍚庣紑鍒ゆ柇鏄惁鍥剧墖鐨勯棶棰�
						淇涓�浜涜剼鏈繍琛屽嚭閿�
						淇hover寤舵椂鏄剧ず鏃讹紝鍏冪礌娌℃湁鏄剧ず浣嗛紶鏍囩Щ鍑轰緷鐒惰Е鍙戦殣钘忓洖璋冪殑闂
 * 2012-02-27 v1.5.0	涓烘棤id瀹瑰櫒鍒涘缓id閫昏緫浣跨敤閿欒鐨勯棶棰�
 						淇鏃犱簨浠舵诞鍔ㄥ嚭鐜版椂鍚岄〉闈㈢偣鍑荤┖鐧藉尯鍩熸诞鍔ㄥ眰涓嶉殣钘忕殑闂
						淇鐐瑰嚮涓巋over骞跺瓨鏃剁壒瀹氭椂鍊檕.trigger鎶ヤ负null閿欒鐨勯棶棰�
 */

(function($) {
	$.fn.powerFloat = function(options) {
		return $(this).each(function() {
			var s = $.extend({}, defaults, options || {});
			var init = function(pms, trigger) {
				if (o.target && o.target.css("display") !== "none") {
					o.targetHide();
				}
				o.s = pms;
				o.trigger = trigger;
			}, hoverTimer;

			switch (s.eventType) {
				case "hover": {
					$(this).hover(function() {
						if (o.timerHold) {
							o.flagDisplay = true;
							return false;
						}

						var numShowDelay = parseInt(s.showDelay, 10);

						init(s, $(this));
						//榧犳爣hover寤舵椂
						if (numShowDelay) {
							if (hoverTimer) {
								clearTimeout(hoverTimer);
							}
							hoverTimer = setTimeout(function() {
								o.targetGet.call(o);
							}, numShowDelay);
						} else {
							o.targetGet();
						}
					}, function() {
						if (hoverTimer) {
							clearTimeout(hoverTimer);
						}
						if (o.timerHold) {
							clearTimeout(o.timerHold);
						}

						o.flagDisplay = false;

						o.targetHold();
					});
					if (s.hoverFollow) {
						//榧犳爣璺熼殢
						$(this).mousemove(function(e) {
							o.cacheData.left = e.pageX;
							o.cacheData.top = e.pageY;
							o.targetGet.call(o);
							return false;
						});
					}
					break;
				}
				case "click": {
					$(this).click(function(e) {
						if (o.display && o.trigger && e.target === o.trigger.get(0)) {
							o.flagDisplay = false;
							o.displayDetect();
						} else {
							init(s, $(this));
							o.targetGet();

							if (!$(document).data("mouseupBind")) {
								$(document).bind("mouseup", function(e) {
									var flag = false;
									var idTarget = o.target && !o.target.attr("id");
									if (idTarget) {
										idTarget = "R_" + Math.random();
										o.target.attr("id", idTarget);
									}
									$(e.target).parents().each(function() {
										if (o.target && $(this).attr("id") === idTarget) {
											flag = true;
										}
									});
									if (s.eventType === "click" && o.display && o.trigger && e.target != o.trigger.get(0) && !flag) {
										o.flagDisplay = false;
										o.displayDetect();
									}
									return false;
								}).data("mouseupBind", true);
							}
						}
					});

					break;
				}
				case "focus": {
					$(this).focus(function() {
						var self = $(this);
						setTimeout(function() {
							init(s, self);
							o.targetGet();
						}, 200);
					}).blur(function() {
						o.flagDisplay = false;
						setTimeout(function() {
							o.displayDetect();
						}, 190);
					});
					break;
				}
				default: {
					init(s, $(this));
					o.targetGet();
					// 鏀剧疆椤甸潰鐐瑰嚮鍚庢樉绀虹殑娴姩鍐呭闅愭帀
					$(document).unbind("mouseup").data("mouseupBind", false);
				}
			}
		});
	};

	var o = {
		targetGet: function() {
			//涓�鍒囨樉绀虹殑瑙﹀彂鏉ユ簮
			if (!this.trigger) { return this; }
			var attr = this.trigger.attr(this.s.targetAttr), target = this.s.target;
			switch (this.s.targetMode) {
				case "common": {
					if (target) {
						var type = typeof(target);
						if (type === "object") {
							if (target.size()) {
								o.target = target.eq(0);
							}
						} else if (type === "string") {
							if ($(target).size()) {
								o.target = $(target).eq(0);
							}
						}
					} else {
						if (attr && $("#" + attr).size()) {
							o.target = $("#" + attr);
						}
					}
					if (o.target) {
						o.targetShow();
					} else {
						return this;
					}

					break;
				}
				case "ajax": {
					//ajax鍏冪礌锛屽鍥剧墖锛岄〉闈㈠湴鍧�
					var url = target || attr;
					this.targetProtect = false;

					if (!url) { return; }

					if (!o.cacheData[url]) {
						o.loading();
					}

					//浼樺厛璁ゅ畾涓哄浘鐗囧姞杞�
					var tempImage = new Image();

					tempImage.onload = function() {
						var w = tempImage.width, h = tempImage.height;
						var winw = $(window).width(), winh = $(window).height();
						var imgScale = w / h, winScale = winw / winh;
						if (imgScale > winScale) {
							//鍥剧墖鐨勫楂樻瘮澶т簬鏄剧ず灞忓箷
							if (w > winw / 2) {
								w = winw / 2;
								h = w / imgScale;
							}
						} else {
							//鍥剧墖楂樺害杈冮珮
							if (h > winh / 2) {
								h = winh / 2;
								w = h * imgScale;
							}
						}
						var imgHtml = '<img class="float_ajax_image" src="' + url + '" width="' + w + '" height = "' + h + '" />';
						o.cacheData[url] = true;
						o.target = $(imgHtml);
						o.targetShow();
					};
					tempImage.onerror = function() {
						//濡傛灉鍥剧墖鍔犺浇澶辫触锛屼袱绉嶅彲鑳斤紝涓�鏄�100%鍥剧墖锛屽垯鎻愮ず锛涘惁鍒欎綔涓洪〉闈㈠姞杞�
						if (/(\.jpg|\.png|\.gif|\.bmp|\.jpeg)$/i.test(url)) {
							o.target = $('<div class="float_ajax_error">鍥剧墖鍔犺浇澶辫触銆�</div>');
							o.targetShow();
						} else {
							$.ajax({
								url: url,
								success: function(data) {
									if (typeof(data) === "string") {
										o.cacheData[url] = true;
										o.target = $('<div class="float_ajax_data">' + data + '</div>');
										o.targetShow();
									}
								},
								error: function() {
									o.target = $('<div class="float_ajax_error">鏁版嵁娌℃湁鍔犺浇鎴愬姛銆�</div>');
									o.targetShow();
								}
							});
						}
					};
					tempImage.src = url;

					break;
				}
				case "list": {
					//涓嬫媺鍒楄〃
					var targetHtml = '<ul class="float_list_ul">',  arrLength;
					if ($.isArray(target) && (arrLength = target.length)) {
						$.each(target, function(i, obj) {
							var list = "", strClass = "", text, href;
							if (i === 0) {
								strClass = ' class="float_list_li_first"';
							}
							if (i === arrLength - 1) {
								strClass = ' class="float_list_li_last"';
							}
							if (typeof(obj) === "object" && (text = obj.text.toString())) {
								if (href = (obj.href || "javascript:")) {
									list = '<a href="' + href + '" class="float_list_a">' + text + '</a>';
								} else {
									list = text;
								}
							} else if (typeof(obj) === "string" && obj) {
								list = obj;
							}
							if (list) {
								targetHtml += '<li' + strClass + '>' + list + '</li>';
							}
						});
					} else {
						targetHtml += '<li class="float_list_null">鍒楄〃鏃犳暟鎹��</li>';
					}
					targetHtml += '</ul>';
					o.target = $(targetHtml);
					this.targetProtect = false;
					o.targetShow();
					break;
				}
				case "remind": {
					//鍐呭鍧囨槸瀛楃涓�
					var strRemind = target || attr;
					this.targetProtect = false;
					if (typeof(strRemind) === "string") {
						o.target = $('<span>' + strRemind + '</span>');
						o.targetShow();
					}
					break;
				}
				default: {
					var objOther = target || attr, type = typeof(objOther);
					if (objOther) {
						if (type === "string") {
							//閫夋嫨鍣�
							if (/<.*>/.test(objOther)) {
								//绾补瀛楃涓蹭簡
								o.target = $('<div>' + objOther + '</div>');
								this.targetProtect = false;
							} else if ($(objOther).size()) {
								o.target = $(objOther).eq(0);
								this.targetProtect = true;
							} else if ($("#" + objOther).size()) {
								o.target = $("#" + objOther).eq(0);
								this.targetProtect = true;
							} else {
								o.target = $('<div>' + objOther + '</div>');
								this.targetProtect = false;
							}
							o.targetShow();
						} else if (type === "object") {
							if (!$.isArray(objOther) && objOther.size()) {
								o.target = objOther.eq(0);
								this.targetProtect = true;
								o.targetShow();
							}
						}
					}
				}
			}
			return this;
		},
		container: function() {
			//瀹瑰櫒(濡傛灉鏈�)閲嶈target
			var cont = this.s.container, mode = this.s.targetMode || "mode";
			if (mode === "ajax" || mode === "remind") {
				//鏄剧ず涓夎
				this.s.sharpAngle = true;
			} else {
				this.s.sharpAngle = false;
			}
			//鏄惁鍙嶅悜
			if (this.s.reverseSharp) {
				this.s.sharpAngle = !this.s.sharpAngle;
			}

			if (mode !== "common") {
				//common妯″紡鏃犳柊瀹瑰櫒瑁呰浇
				if (cont === null) {
					cont = "plugin";
				}
				if ( cont === "plugin" ) {
					if (!$("#floatBox_" + mode).size()) {
						$('<div id="floatBox_' + mode + '" class="float_' + mode + '_box"></div>').appendTo($("body")).hide();
					}
					cont = $("#floatBox_" + mode);
				}

				if (cont && typeof(cont) !== "string" && cont.size()) {
					if (this.targetProtect) {
						o.target.show().css("position", "static");
					}
					o.target = cont.empty().append(o.target);
				}
			}
			return this;
		},
		setWidth: function() {
			var w = this.s.width;
			if (w === "auto") {
				if (this.target.get(0).style.width) {
					this.target.css("width", "auto");
				}
			} else if (w === "inherit") {
				this.target.width(this.trigger.width());
			} else {
				this.target.css("width", w);
			}
			return this;
		},
		position: function() {
			if (!this.trigger || !this.target) {
				return this;
			}
			var pos, tri_h = 0, tri_w = 0, cor_w = 0, cor_h = 0, tri_l, tri_t, tar_l, tar_t, cor_l, cor_t,
				tar_h = this.target.data("height"), tar_w = this.target.data("width"),
				st = $(window).scrollTop(),

				off_x = parseInt(this.s.offsets.x, 10) || 0, off_y = parseInt(this.s.offsets.y, 10) || 0,
				mousePos = this.cacheData;

			//缂撳瓨鐩爣瀵硅薄楂樺害锛屽搴︼紝鎻愰珮榧犳爣璺熼殢鏃舵樉绀烘�ц兘锛屽厓绱犻殣钘忔椂缂撳瓨娓呴櫎
			if (!tar_h) {
				tar_h = this.target.outerHeight();
				if (this.s.hoverFollow) {
					this.target.data("height", tar_h);
				}
			}
			if (!tar_w) {
				tar_w = this.target.outerWidth();
				if (this.s.hoverFollow) {
					this.target.data("width", tar_w);
				}
			}

			pos = this.trigger.offset();
			tri_h = this.trigger.outerHeight();
			tri_w = this.trigger.outerWidth();
			tri_l = pos.left;
			tri_t = pos.top;

			var funMouseL = function() {
				if (tri_l < 0) {
					tri_l = 0;
				} else if (tri_l + tri_h > $(window).width()) {
					tri_l = $(window).width() = tri_h;
				}
			}, funMouseT = function() {
				if (tri_t < 0) {
					tri_t = 0;
				} else if (tri_t + tri_h > $(document).height()) {
					tri_t = $(document).height() - tri_h;
				}
			};
			//濡傛灉鏄紶鏍囪窡闅�
			if (this.s.hoverFollow && mousePos.left && mousePos.top) {
				if (this.s.hoverFollow === "x") {
					//姘村钩鏂瑰悜绉诲姩锛岃鏄庣旱鍧愭爣鍥哄畾
					tri_l = mousePos.left
					funMouseL();
				} else if (this.s.hoverFollow === "y") {
					//鍨傜洿鏂瑰悜绉诲姩锛岃鏄庢í鍧愭爣鍥哄畾锛岀旱鍧愭爣璺熼殢榧犳爣绉诲姩
					tri_t = mousePos.top;
					funMouseT();
				} else {
					tri_l = mousePos.left;
					tri_t = mousePos.top;
					funMouseL();
					funMouseT();
				}
			}


			var arrLegalPos = ["4-1", "1-4", "5-7", "2-3", "2-1", "6-8", "3-4", "4-3", "8-6", "1-2", "7-5", "3-2"],
				align = this.s.position, alignMatch = false, strDirect;
			$.each(arrLegalPos, function(i, n) {
				if (n === align) {
					alignMatch = true;
					return;
				}
			});
			if (!alignMatch) {
				align = "4-1";
			}

			var funDirect = function(a) {
				var dir = "bottom";
				//纭畾鏂瑰悜
				switch (a) {
					case "1-4": case "5-7": case "2-3": {
						dir = "top";
						break;
					}
					case "2-1": case "6-8": case "3-4": {
						dir = "right";
						break;
					}
					case "1-2": case "8-6": case "4-3": {
						dir = "left";
						break;
					}
					case "4-1": case "7-5": case "3-2": {
						dir = "bottom";
						break;
					}
				}
				return dir;
			};

			//灞呬腑鍒ゆ柇
			var funCenterJudge = function(a) {
				if (a === "5-7" || a === "6-8" || a === "8-6" || a === "7-5") {
					return true;
				}
				return false;
			};

			var funJudge = function(dir) {
				var totalHeight = 0, totalWidth = 0, flagCorner = (o.s.sharpAngle && o.corner) ? true: false;
				if (dir === "right") {
					totalWidth = tri_l + tri_w + tar_w + off_x;
					if (flagCorner) {
						totalWidth += o.corner.width();
					}
					if (totalWidth > $(window).width()) {
						return false;
					}
				} else if (dir === "bottom") {
					totalHeight = tri_t + tri_h + tar_h + off_y;
					if (flagCorner) {
						totalHeight += 	o.corner.height();
					}
					if (totalHeight > st + $(window).height()) {
						return false;
					}
				} else if (dir === "top") {
					totalHeight = tar_h + off_y;
					if (flagCorner) {
						totalHeight += 	o.corner.height();
					}
					if (totalHeight > tri_t - st) {
						return false;
					}
				} else if (dir === "left") {
					totalWidth = tar_w + off_x;
					if (flagCorner) {
						totalWidth += o.corner.width();
					}
					if (totalWidth > tri_l) {
						return false;
					}
				}
				return true;
			};
			//姝ゆ椂鐨勬柟鍚�
			strDirect = funDirect(align);

			if (this.s.sharpAngle) {
				//鍒涘缓灏栬
				this.createSharp(strDirect);
			}

			//杈圭紭杩囩晫鍒ゆ柇
			if (this.s.edgeAdjust) {
				//鏍规嵁浣嶇疆鏄惁婧㈠嚭鏄剧ず鐣岄潰閲嶆柊鍒ゅ畾瀹氫綅
				if (funJudge(strDirect)) {
					//璇ユ柟鍚戜笉婧㈠嚭
					(function() {
						if (funCenterJudge(align)) { return; }
						var obj = {
							top: {
								right: "2-3",
								left: "1-4"
							},
							right: {
								top: "2-1",
								bottom: "3-4"
							},
							bottom: {
								right: "3-2",
								left: "4-1"
							},
							left: {
								top: "1-2",
								bottom: "4-3"
							}
						};
						var o = obj[strDirect], name;
						if (o) {
							for (name in o) {
								if (!funJudge(name)) {
									align = o[name];
								}
							}
						}
					})();
				} else {
					//璇ユ柟鍚戞孩鍑�
					(function() {
						if (funCenterJudge(align)) {
							var center = {
								"5-7": "7-5",
								"7-5": "5-7",
								"6-8": "8-6",
								"8-6": "6-8"
							};
							align = center[align];
						} else {
							var obj = {
								top: {
									left: "3-2",
									right: "4-1"
								},
								right: {
									bottom: "1-2",
									top: "4-3"
								},
								bottom: {
									left: "2-3",
									right: "1-4"
								},
								left: {
									bottom: "2-1",
									top: "3-4"
								}
							};
							var o = obj[strDirect], arr = [];
							for (name in o) {
								arr.push(name);
							}
							if (funJudge(arr[0]) || !funJudge(arr[1])) {
								align = o[arr[0]];
							} else {
								align = o[arr[1]];
							}
						}
					})();
				}
			}

			//宸茬‘瀹氱殑灏栬
			var strNewDirect = funDirect(align), strFirst = align.split("-")[0];
			if (this.s.sharpAngle) {
				//鍒涘缓灏栬
				this.createSharp(strNewDirect);
				cor_w = this.corner.width(), cor_h = this.corner.height();
			}

			//纭畾left, top鍊�
			if (this.s.hoverFollow) {
				//濡傛灉榧犳爣璺熼殢
				if (this.s.hoverFollow === "x") {
					//浠呮按骞虫柟鍚戣窡闅�
					tar_l = tri_l + off_x;
					if (strFirst === "1" || strFirst === "8" || strFirst === "4" ) {
						//鏈�宸�
						tar_l = tri_l - (tar_w - tri_w) / 2 + off_x;
					} else {
						//鍙充晶
						tar_l = tri_l - (tar_w - tri_w) + off_x;
					}

					//杩欐槸鍨傜洿浣嶇疆锛屽浐瀹氫笉鍔�
					if (strFirst === "1" || strFirst === "5" || strFirst === "2" ) {
						tar_t = tri_t - off_y - tar_h - cor_h;
						//灏栬
						cor_t = tri_t - cor_h - off_y - 1;

					} else {
						//涓嬫柟
						tar_t = tri_t + tri_h + off_y + cor_h;
						cor_t = tri_t + tri_h + off_y + 1;
					}
					cor_l = pos.left - (cor_w - tri_w) / 2;
				} else if (this.s.hoverFollow === "y") {
					//浠呭瀭鐩存柟鍚戣窡闅�
					if (strFirst === "1" || strFirst === "5" || strFirst === "2" ) {
						//椤堕儴
						tar_t = tri_t - (tar_h - tri_h) / 2 + off_y;
					} else {
						//搴曢儴
						tar_t = tri_t - (tar_h - tri_h) + off_y;
					}

					if (strFirst === "1" || strFirst === "8" || strFirst === "4" ) {
						//宸︿晶
						tar_l = tri_l - tar_w - off_x - cor_w;
						cor_l = tri_l - cor_w - off_x - 1;
					} else {
						//鍙充晶
						tar_l = tri_l + tri_w - off_x + cor_w;
						cor_l = tri_l + tri_w + off_x + 1;
					}
					cor_t = pos.top - (cor_h - tri_h) / 2;
				} else {
					tar_l = tri_l + off_x;
					tar_t = tri_t + off_y;
				}

			} else {
				switch (strNewDirect) {
					case "top": {
						tar_t = tri_t - off_y - tar_h - cor_h;
						if (strFirst == "1") {
							tar_l = tri_l - off_x;
						} else if (strFirst === "5") {
							tar_l = tri_l - (tar_w - tri_w) / 2 - off_x;
						} else {
							tar_l = tri_l - (tar_w - tri_w) - off_x;
						}
						cor_t = tri_t - cor_h - off_y - 1;
						cor_l = tri_l - (cor_w - tri_w) / 2;
						break;
					}
					case "right": {
						tar_l = tri_l + tri_w + off_x + cor_w;
						if (strFirst == "2") {
							tar_t = tri_t + off_y;
						} else if (strFirst === "6") {
							tar_t = tri_t - (tar_h - tri_h) / 2 + off_y;
						} else {
							tar_t = tri_t - (tar_h - tri_h) + off_y;
						}
						cor_l = tri_l + tri_w + off_x + 1;
						cor_t = tri_t - (cor_h - tri_h) / 2;
						break;
					}
					case "bottom": {
						tar_t = tri_t + tri_h + off_y + cor_h;
						if (strFirst == "4") {
							tar_l = tri_l + off_x;
						} else if (strFirst === "7") {
							tar_l = tri_l - (tar_w - tri_w) / 2 + off_x;
						} else {
							tar_l = tri_l - (tar_w - tri_w) + off_x;
						}
						cor_t = tri_t + tri_h + off_y + 1;
						cor_l = tri_l - (cor_w - tri_w) / 2;
						break;
					}
					case "left": {
						tar_l = tri_l - tar_w - off_x - cor_w;
						if (strFirst == "2") {
							tar_t = tri_t - off_y;
						} else if (strFirst === "6") {
							tar_t = tri_t - (tar_w - tri_w) / 2 - off_y;
						} else {
							tar_t = tri_t - (tar_h - tri_h) - off_y;
						}
						cor_l = tar_l + cor_w;
						cor_t = tri_t - (tar_w - cor_w) / 2;
						break;
					}
				}
			}
			//灏栬鐨勬樉绀�
			if (cor_h && cor_w && this.corner) {
				this.corner.css({
					left: cor_l,
					top: cor_t,
					zIndex: this.s.zIndex + 1
				});
			}
			//娴姩妗嗘樉绀�
			this.target.css({
				position: "absolute",
				left: tar_l,
				top: tar_t,
				zIndex: this.s.zIndex
			});
			return this;
		},
		createSharp: function(dir) {
			var bgColor, bdColor, color1 = "", color2 = "";
			var objReverse = {
				left: "right",
				right: "left",
				bottom: "top",
				top: "bottom"
			}, dirReverse = objReverse[dir] || "top";

			if (this.target) {
				bgColor = this.target.css("background-color");
				if (parseInt(this.target.css("border-" + dirReverse + "-width")) > 0) {
					bdColor = this.target.css("border-" + dirReverse + "-color");
				}

				if (bdColor &&  bdColor !== "transparent") {
					color1 = 'style="color:' + bdColor + ';"';
				} else {
					color1 = 'style="display:none;"';
				}
				if (bgColor && bgColor !== "transparent") {
					color2 = 'style="color:' + bgColor + ';"';
				}else {
					color2 = 'style="display:none;"';
				}
			}

			var html = '<div id="floatCorner_' + dir + '" class="float_corner float_corner_' + dir + '">' +
					'<span class="corner corner_1" ' + color1 + '>鈼�</span>' +
					'<span class="corner corner_2" ' + color2 + '>鈼�</span>' +
				'</div>';
			if (!$("#floatCorner_" + dir).size()) {
				$("body").append($(html));
			}
			this.corner = $("#floatCorner_" + dir);
			return this;
		},
		targetHold: function() {
			if (this.s.hoverHold) {
				var delay = parseInt(this.s.hideDelay, 10) || 200;
				if (this.target) {
					this.target.hover(function() {
						o.flagDisplay = true;
					}, function() {
						if (o.timerHold) {
							clearTimeout(o.timerHold);
						}
						o.flagDisplay = false;
						o.targetHold();
					});
				}

				o.timerHold = setTimeout(function() {
					o.displayDetect.call(o);
				}, delay);
			} else {
				this.displayDetect();
			}
			return this;
		},
		loading: function() {
			this.target = $('<div class="float_loading"></div>');
			this.targetShow();
			this.target.removeData("width").removeData("height");
			return this;
		},
		displayDetect: function() {
			//鏄剧ず涓庡惁妫�娴嬩笌瑙﹀彂
			if (!this.flagDisplay && this.display) {
				this.targetHide();
				this.timerHold = null;
			}
			return this;
		},
		targetShow: function() {
			o.cornerClear();
			this.display = true;
			this.container().setWidth().position();
			this.target.show();
			if ($.isFunction(this.s.showCall)) {
				this.s.showCall.call(this.trigger, this.target);
			}
			return this;
		},
		targetHide: function() {
			this.display = false;
			this.targetClear();
			this.cornerClear();
			if ($.isFunction(this.s.hideCall)) {
				this.s.hideCall.call(this.trigger);
			}
			this.target = null;
			this.trigger = null;
			this.s = {};
			this.targetProtect = false;
			return this;
		},
		targetClear: function() {
			if (this.target) {
				if (this.target.data("width")) {
					this.target.removeData("width").removeData("height");
				}
				if (this.targetProtect) {
					//淇濇姢瀛╁瓙
					this.target.children().hide().appendTo($("body"));
				}
				this.target.unbind().hide();
			}
		},
		cornerClear: function() {
			if (this.corner) {
				//浣跨敤remove閬垮厤娼滃湪鐨勫皷瑙掗鑹插啿绐侀棶棰�
				this.corner.remove();
			}
		},
		target: null,
		trigger: null,
		s: {},
		cacheData: {},
		targetProtect: false
	};

	$.powerFloat = {};
	$.powerFloat.hide = function() {
		o.targetHide();
	};

	var defaults  = {
		width: "auto", //鍙�夊弬鏁帮細inherit锛屾暟鍊�(px)
		offsets: {
			x: 0,
			y: 0
		},
		zIndex: 999,

		eventType: "hover", //浜嬩欢绫诲瀷锛屽叾浠栧彲閫夊弬鏁版湁锛歝lick, focus

		showDelay: 0, //榧犳爣hover鏄剧ず寤惰繜
		hideDelay: 0, //榧犳爣绉诲嚭闅愯棌寤舵椂

		hoverHold: true,
		hoverFollow: false, //true鎴栨槸鍏抽敭瀛梮, y

		targetMode: "common", //娴姩灞傜殑绫诲瀷锛屽叾浠栧彲閫夊弬鏁版湁锛歛jax, list, remind
		target: null, //target瀵硅薄鑾峰彇鏉ユ簮锛屼紭鍏堣幏鍙栵紝濡傛灉涓簄ull锛屽垯浠巘argetAttr涓幏鍙栥��
		targetAttr: "rel", //target瀵硅薄鑾峰彇鏉ユ簮锛屽綋targetMode涓簂ist鏃舵棤鏁�

		container: null, //杞浇target鐨勫鍣紝鍙互浣跨敤"plugin"鍏抽敭瀛楋紝鍒欒〃绀轰娇鐢ㄦ彃浠惰嚜甯﹀鍣ㄧ被鍨�
		reverseSharp: false, //鏄惁鍙嶅悜灏忎笁瑙掔殑鏄剧ず锛岄粯璁jax, remind鏄樉绀轰笁瑙掔殑锛屽叾浠栧list鍜岃嚜瀹氫箟褰㈠紡鏄笉鏄剧ず鐨�

		position: "4-1", //trigger-target
		edgeAdjust: true, //杈圭紭浣嶇疆鑷姩璋冩暣

		showCall: $.noop,
		hideCall: $.noop

	};
})(jQuery);