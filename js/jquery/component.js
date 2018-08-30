/**
 * jQuery-wooUI component
 *
 * @VERSION 组件基类，所有组件均继承其默认实现
 * @1.继承
 * @2.桥接，在jquery对象中缓存组件对象，通过jquery对象调用组件属性及方法
 * @3.面向切面
 * @4.鼠标事件
 *
 *
 *
 * 所有模块都是基于其中的component方法进行扩展，使用统一的命名规范和编码风格。 先来说一下原理：
 * $.component此函数完成了对jQuery本身的扩展，根据第一个参数来确定模块的命名空间和函数名；第二个参数确定模块的基类（默认是$.Component）；第三个参数实现模块本身的方法。比如标签切换插件tabs.js中开始：
 * $.component(“woo.tabs”, {…});//这里只有两个参数，那么基类就默认是$.Component
 * 第一个参数：”woo.tabs”用来表示在jQuery上选择（或增加）一个命名空间，即如果jQuery.woo不存在，则定义jQuery.woo =
 * {},然后在jQuery.woo上增加一个函数，名称为tabs.最后调用$.component.bridge将tabs方法挂在jQuery对象上。这样，所有的jquery对象将拥有tabs方法。
 *
 * 注意：jquery woo有严格的命名规范，每个控件对外只暴露一个借口。控件所有方法或属性通过向此借口传递不同参数来调用和获取。
 *
 * jquery
 * ui的大部分控件是基于$.Component基类实现的。所以一般我们做控件是都要重写$.Component类中的一些方法。一般来说，一个ui控件需要实现下列的方法或属性：
 * 属性：
 * @1.options 用来缓存控件各项参数 私有方法，使用“$(xx).tabs(私有方法)”这种方式来调用私有方法时会立刻返回，调用不能成功：
 * @2._create 控件初始化调用,多次调用$(xx).tabs()这样不带参数的方法只会执行一次
 * @3._init 一般不用实现，默认为空函数，每次“$(xx).tabs()”这样调用时会调用此方法
 * @4._setOption “$(xx).tabs(‘option’,xxx)”这种调用方式会调用此方法 公开方法：
 * @5.destroy 销毁模块
 * @6.option 设置或获取参数
 * @7.enable 启用模块功能
 * @8.disable 禁用功能
 *
 * 几乎所有的jquery ui控件都会重写这些接口，同时增加控件相关的私有或公有方法。
 *
 * 记住，jquery woo的实例是和元素关联起来的，作为数据保存起来了。暴露给用户使用的只是jquery对象上增加的方法。一般我们不需要获取ui的实例。
 */
(function($) {

 	//jQuery.fx.off = true;//关闭动画
	/**
	 *
	 * @param {name}
	 *            如'woo.mycomponent'
	 * @param {base}
	 *            重写获取增加的属性成员，如{options : {v : 10}}
	 * @param {prototype}
	 * @return {Boolean} $.component("woo.mycomponent",{})
	 *         $.component("woo.mycomponent", $.Component,{})
	 */

	$.component = function(name, base, prototype) {
		var ns = name.split('.')[0], fullName;// woo
		name = name.split('.')[1];// mycomponent
		fullName = ns + '-' + name;// woo-mycomponent
		// 如果没有定义prototype prototype为新属性，base为$.Component
		if (!prototype) {
			prototype = base;
			base = $.Component;
		}

		/**
		 * create selector for plugin 扩展选择器引擎的功能 $(":woo-mycomponent")
		 * $(":woo-menu") 可以选择所有该类的组件对象集合
		 */
		$.expr[":"][fullName] = function(elem) {
			return !!$.data(elem, name);
		};

		/**
		 * 创建命名空间 $['woo']
		 */
		$[ns] = $[ns] || {};
		// 定义$['woo']['mycomponent']构造函数
		$[ns][name] = function(options, element) {
			if (arguments.length) {
				this._createComp(options, element);
			}
		};
		var basePrototype = new base();// 创建一个基类对象$.Component
		// we need to make the options hash a property directly on the new
		// instance
		// otherwise we'll modify the options hash on the prototype that we're
		// inheriting from
		// $.each( basePrototype, function( key, val ) {
		// if ( $.isPlainObject(val) ) {
		// basePrototype[ key ] = $.extend( {}, val );
		// }
		// });
		// extend(dest,src1,src2,src3...srcN) ,合并多个对象.
		basePrototype.options = $.extend({}, basePrototype.options);// 这句有什么意义？保证basePrototype.options至少为{}
		/**
		 * 1.extend(boolean,dest,src1,src2...), 深度镶套对象
		 * 2.定义$['woo']['mycomponent']原型，把子类原型指向合并后的父类对象，这样通过子类对象也可以直接调用基类prototype的方法
		 */
		$[ns][name].prototype = $.extend(true, basePrototype, {
					ns : ns,
					cmpName : name,
					eventProfix : $[ns][name].prototype.eventProfix || name,
					widgetBaseClass : fullName
				}, prototype);
//		for(var i in basePrototype){
//			alert(i)
//			if($.isArray(prototype[i])){
//				$[ns][name].prototype[i]=prototype[i];
//			}
//		}
		$.component.bridge(name, $[ns][name]);
	};

	// 将组件功能附加到jQuery对象中,其实是构造器附加过去，于是jquery对象可以通过构造器调用组件实例方法及属性，其实原理就是找到jquery对象中的data的组件实例，并调用实例的属性跟方法，实例的data存储在_createComp中实现
	$.component.bridge = function(name, object) {
		$.fn[name] = function(options) {
			var isMethodCall = typeof options === "string", args = Array.prototype.slice
					.call(arguments, 1), returnValue = this;
			// 如果第一个参数是string类型，就认为是调用模块方法
			// 剩下的参数作为方法的参数，后面会用到

			// allow multiple hashes to be passed on init
			// 可以简单认为是$.extend(true,options,args[0],...),args可以是一个参数或是数组
			options = !isMethodCall && args.length ? $.extend.apply(null, [
							true, options].concat(args)) : options;
			// prevent calls to internal methods
			// 开头带下划线的方法都是私有方法，不让调用
			if (isMethodCall && options.substring(0, 1) === "_") {
				return returnValue;
			}

			if (isMethodCall) {// 如果是调用函数
				this.each(function() {
							var instance = $.data(this, name), // 得到实例，实例作为一个数据和元素关联上
							methodValue = instance
									&& $.isFunction(instance[options])
									? instance[options].apply(instance, args)
									: instance;// 如果实例和方法均存在，调用方法，把args作为参数传进去
							// 如果methodValue不是jquery对象也不是undefined
							if (methodValue !== instance
									&& methodValue !== undefined) {
								returnValue = methodValue;
								return false;// 跳出each，一般获取options的值会走这个分支
							}
						});
			} else {// 不是函数调用的话
				//this.each(function() {
							var instance = $.data(this, name);
							if (instance) {// 实例存在
								if (options) {// 有参数
									instance.option(options);// 调用option函数，一般是设置状态之类的操作
								}
								instance._init();// 再次调用此函数，根据options调整
							} else {
								// 没有实例的话，给元素绑定一个实例。注意这里的this是dom，object是模块类
								$.data(this, name, new object(options, this));
							}
						//});
			}

			return returnValue;// 返回，有可能是jquery对象，有可能是其他值
		};
	};
	// 所有模块的基类
	$.Component = function(options, element) {
		if (arguments.length) {// 如果有参数，调用初始化函数
			this._createComp(options, element);
		}
	}

	$.Component.prototype = {
		cmpName : 'component',
		eventProfix : '',
		options : {
			disabled : false,

			// 鼠标的options
			cancel : ':input,option',
			distance : 1,
			delay : 0
		},
		_createComp : function(options, element) {
			// so that it's stored even before the _create function runs
			// 将组件实例存储到了$.data中，供$.component.bridge使用

			this.element = this.el = $(element).data(this.cmpName, this);
			this.options = $.extend(true, {}, this.options, $.metadata
							&& $.metadata.get(element)[this.cmpName], options);
			//为了解决表格不以<table>作为节点渲染补救方式
			var cmpTag=this.options.cmpTag;
			if(cmpTag&&this.el.context.nodeName != cmpTag){
				var t=$("<"+cmpTag+">");
				t.attr('id',$(element).attr('id'));
				t.attr('class',$(element).attr('class'));
				$(element).after(t);
				$(element).remove();
				element=t;
				this.element = this.el = $(element).data(this.cmpName, this);
			}
			this.element.data('cmp', this);//用一个名称板绑定组件，方便调用 add by chengl 2011-05-07
			var self = this;
			this.el.bind("remove." + this.cmpName, function() {
						self.destroy();
					});
			this._bindEvent();// 绑定事件
			this._create();// 创建
			this._init();// 初始化

			//add by chengl 2012-04-27
			this.isRender=true;
		},
		_create : $.noop,
		_init : $.noop,

		destroy : function() {// 销毁模块：去除绑定事件、去除数据、去除样式、属性
			this.el.unbind("." + this.cmpName).removeData(this.cmpName);
			this.comp().unbind("." + this.cmpName).removeAttr("aria-disabled")
					.removeClass(this.widgetBaseClass + "-disabled "
							+ this.namespace + "-state-disabled");
			//add by chengl 2012-04-27
			this.isRender=false;
		},

		comp : function() {
			return this.el;
		},

		option : function(key, value) {
			var options = key, self = this;

			if (arguments.length === 0) {
				// don't return a reference to the internal hash
				return $.extend({}, self.options);
			}

			if (typeof key === "string") {
				if (value === undefined) {
					return this.options[key];
				}
				options = {};
				options[key] = value;
			}

			$.each(options, function(key, value) {
						self._setOption(key, value);
					});

			return self;
		},
		_setOption : function(key, value) {
			this.options[key] = value;

			if (key === "disabled") {
				this.comp()[value ? "addClass" : "removeClass"](this.widgetBaseClass
						+ "-disabled"
						+ " "
						+ this.namespace
						+ "-state-disabled").attr("aria-disabled", value);
			}

			return this;
		},

		enable : function() {
			return this._setOption("disabled", false);
		},
		disable : function() {
			return this._setOption("disabled", true);
		},

		fireEvent : function(type, event, data) {
			var callback = this.options[type];

			event = $.Event(event);
			event.type = (type === this.widgetEventPrefix
					? type
					: this.widgetEventPrefix + type).toLowerCase();
			data = data || {};

			// copy original event properties over to the new event
			// this would happen if we could call $.event.fix instead of $.Event
			// but we don't have a way to force an event to be fixed multiple
			// times
			if (event.originalEvent) {
				for (var i = $.event.props.length, prop; i;) {
					prop = $.event.props[--i];
					event[prop] = event.originalEvent[prop];
				}
			}

			this.el.trigger(event, data);

			return !($.isFunction(callback)
					&& callback.call(this.el[0], event, data) === false || event
					.isDefaultPrevented());
		},

		// 面向切面编程AOP的实现
		yield : null,
		returnValues : {},
		before : function(method, f) {
			var original = this[method];
			this[method] = function() {
				f.apply(this, arguments);
				return original.apply(this, arguments);
			};
		},
		after : function(method, f) {
			var original = this[method];
			this[method] = function() {
				this.returnValues[method] = original.apply(this, arguments);
				return f.apply(this, arguments);
			}
		},
		around : function(method, f) {
			var original = this[method];
			this[method] = function() {
				var tmp = this.yield;
				this.yield = original;
				var ret = f.apply(this, arguments);
				this.yield = tmp;
				return ret;
			}
		},

		// 鼠标事件初始化
		_mouseInit : function() {
			var self = this;

			this.el.bind('mousedown.' + this.cmpName, function(event) {
						return self._mouseDown(event);
					}).bind('click.' + this.cmpName, function(event) {
						if (self._preventClickEvent) {
							// 阻止鼠标事件冒泡
							self._preventClickEvent = false;
							event.stopImmediatePropagation();
							return false;
						}
					});

			this.started = false;
		},

		// TODO: make sure destroying one instance of mouse doesn't mess with
		// other instances of mouse
		_mouseDestroy : function() {
			this.el.unbind('.' + this.cmpName);
		},

		_mouseDown : function(event) {
			// don't let more than one widget handle mouseStart
			// TODO: figure out why we have to use originalEvent
			event.originalEvent = event.originalEvent || {};
			if (event.originalEvent.mouseHandled) {
				return;
			}

			// we may have missed mouseup (out of window)
			(this._mouseStarted && this._mouseUp(event));

			this._mouseDownEvent = event;

			var self = this, btnIsLeft = (event.which == 1), elIsCancel = (typeof this.options.cancel == "string"
					? $(event.target).parents().add(event.target)
							.filter(this.options.cancel).length
					: false);
			// 如果点击的是左键，执行_mouseCapture 返回为false,则函数返回
			if (!btnIsLeft || elIsCancel || !this._mouseCapture(event)) {
				return true;
			}
			// 是否延迟,0 : this.mouseDelayMet = true
			this.mouseDelayMet = !this.options.delay;
			if (!this.mouseDelayMet) {
				this._mouseDelayTimer = setTimeout(function() {
							self.mouseDelayMet = true;
						}, this.options.delay);
			}

			if (this._mouseDistanceMet(event) && this._mouseDelayMet(event)) {
				this._mouseStarted = (this._mouseStart(event) !== false);
				if (!this._mouseStarted) {
					event.preventDefault();
					return true;
				}
			}

			// these delegates are required to keep context
			this._mouseMoveDelegate = function(event) {
				return self._mouseMove(event);
			};
			this._mouseUpDelegate = function(event) {
				return self._mouseUp(event);
			};
			$(document).bind('mousemove.' + this.cmpName,
					this._mouseMoveDelegate).bind('mouseup.' + this.cmpName,
					this._mouseUpDelegate);

			// preventDefault() is used to prevent the selection of text here -
			// however, in Safari, this causes select boxes not to be selectable
			// anymore, so this fix is needed
			($.browser.safari || event.preventDefault());

			event.originalEvent.mouseHandled = true;
			return true;
		},

		_mouseMove : function(event) {
			// IE mouseup check - mouseup happened when mouse was out of window
			if ($.browser.msie && !event.button) {
				return this._mouseUp(event);
			}

			if (this._mouseStarted) {
				this._mouseDrag(event);
				return event.preventDefault();
			}

			if (this._mouseDistanceMet(event) && this._mouseDelayMet(event)) {
				this._mouseStarted = (this._mouseStart(this._mouseDownEvent,
						event) !== false);
				(this._mouseStarted ? this._mouseDrag(event) : this
						._mouseUp(event));
			}

			return !this._mouseStarted;
		},

		_mouseUp : function(event) {
			$(document).unbind('mousemove.' + this.cmpName,
					this._mouseMoveDelegate).unbind('mouseup.' + this.cmpName,
					this._mouseUpDelegate);

			if (this._mouseStarted) {
				this._mouseStarted = false;
				this._preventClickEvent = (event.target == this._mouseDownEvent.target);
				this._mouseStop(event);
			}

			return false;
		},

		_mouseDistanceMet : function(event) {
			return (Math.max(
					Math.abs(this._mouseDownEvent.pageX - event.pageX), Math
							.abs(this._mouseDownEvent.pageY - event.pageY)) >= this.options.distance);
		},

		_mouseDelayMet : function(event) {
			return this.mouseDelayMet;
		},

		_mouseStart : $.noop,
		_mouseDrag : $.noop,
		_mouseStop : $.noop,
		_mouseCapture : function(event) {
			return true;
		},

		/**
		 * 绑定事件
		 */
		_bindEvent : function() {
			var el = this.el, p = this.options, event = p.event;
			for (var e in event) {
				el.bind(e, event[e]);
			}
		},
		/**
		 *判断组件是否渲染
		 */
		getIsRender:function(){
			return this.isRender;
		}

	}

	$.component('woo.component');

	// JavaScript继承实现方法，见
	// http://groups.google.com/group/comp.lang.javascript/msg/e04726a66face2a2
	// http://webreflection.blogspot.com/2008/10/big-douglas-begetobject-revisited.html
	// 这里就是虚拟一个类，把该类原型指向传入的类的原型，并创建该类的实例
	var object = (function(F) {// F为一个空的构造函数，因为在使用原型方法之前需要定义一个函数
		return (function(o) {
			F.prototype = o;// o为superproto
			return new F();
		});
	})(function() {
			});

	// 创建一个组件的子类
	var OVERRIDE = /xyz/.test(function() {
				xyz;
			}) ? /\b_super\b/ : /.*/;

	// 添加一个方法
	$.woo.component.subclass = function subclass(name) {

		$.component(name); // Slightly inefficient to create a widget only to
		// discard its prototype, but it's not too bad
		name = name.split('.');
		var component = $[name[0]][name[1]], superclass = this, superproto = superclass.prototype;
		for (key in superproto) {
			// alert(key)
		}
		// 把子类的原型指向父类的实例
		var o = $.extend( object(superproto), component.prototype);// update by chengl 如果复制以前原型的属性如cmpName
		var proto = arguments[0] = component.prototype = o; // inherit
		// from
		// the
		// superclass
		$.extend.apply(null, arguments); // and add other add-in methods to
		// the prototype
		component.subclass = subclass;

		// Subtle point: we want to call superclass init and destroy if they
		// exist
		// (otherwise the user of this function would have to keep track of all
		// that)
		for (key in proto) {
			if (proto.hasOwnProperty(key))
				switch (key) {

					case '_create' :
						var create = proto._create;
						proto._create = function() {
							superproto._create.apply(this);
							create.apply(this);
						};
						break;
					case '_init' :
						var init = proto._init;
						proto._init = function() {
							superproto._init.apply(this);
							init.apply(this);
						};
						break;
					case 'destroy' :
						var destroy = proto.destroy;
						proto.destroy = function() {
							destroy.apply(this);
							superproto.destroy.apply(this);
						};
						break;
					case 'options' :
						var options = proto.options;
						if(arguments[1]){//修改子类参数无法传递的bug
							options=$.extend(true, {}, options,
								arguments[1]);
						}
						// update by chengl 解决深度继承问题，这样在属性中嵌套的对象不会被覆盖
						proto.options = $.extend(true, {}, superproto.options,
								options);
						break;
					default :
						if ($.isFunction(proto[key])
								&& $.isFunction(superproto[key])
								&& OVERRIDE.test(proto[key])) {
							proto[key] = (function(name, fn) {
								return function() {
									var tmp = this._super;
									this._super = superproto[name];
									try {
										var ret = fn.apply(this, arguments);
									} finally {
										this._super = tmp;
									}
									return ret;
								};
							})(key, proto[key]);
						}
						break;
				}
		}
	};
})(jQuery);