/**
 * jQuery-wooUI component
 *
 * @VERSION ������࣬����������̳���Ĭ��ʵ��
 * @1.�̳�
 * @2.�Žӣ���jquery�����л����������ͨ��jquery�������������Լ�����
 * @3.��������
 * @4.����¼�
 *
 *
 *
 * ����ģ�鶼�ǻ������е�component����������չ��ʹ��ͳһ�������淶�ͱ����� ����˵һ��ԭ��
 * $.component�˺�������˶�jQuery�������չ�����ݵ�һ��������ȷ��ģ��������ռ�ͺ��������ڶ�������ȷ��ģ��Ļ��ࣨĬ����$.Component��������������ʵ��ģ�鱾��ķ����������ǩ�л����tabs.js�п�ʼ��
 * $.component(��woo.tabs��, {��});//����ֻ��������������ô�����Ĭ����$.Component
 * ��һ����������woo.tabs��������ʾ��jQuery��ѡ�񣨻����ӣ�һ�������ռ䣬�����jQuery.woo�����ڣ�����jQuery.woo =
 * {},Ȼ����jQuery.woo������һ������������Ϊtabs.������$.component.bridge��tabs��������jQuery�����ϡ����������е�jquery����ӵ��tabs������
 *
 * ע�⣺jquery woo���ϸ�������淶��ÿ���ؼ�����ֻ��¶һ����ڡ��ؼ����з���������ͨ����˽�ڴ��ݲ�ͬ���������úͻ�ȡ��
 *
 * jquery
 * ui�Ĵ󲿷ֿؼ��ǻ���$.Component����ʵ�ֵġ�����һ���������ؼ��Ƕ�Ҫ��д$.Component���е�һЩ������һ����˵��һ��ui�ؼ���Ҫʵ�����еķ��������ԣ�
 * ���ԣ�
 * @1.options ��������ؼ�������� ˽�з�����ʹ�á�$(xx).tabs(˽�з���)�����ַ�ʽ������˽�з���ʱ�����̷��أ����ò��ܳɹ���
 * @2._create �ؼ���ʼ������,��ε���$(xx).tabs()�������������ķ���ֻ��ִ��һ��
 * @3._init һ�㲻��ʵ�֣�Ĭ��Ϊ�պ�����ÿ�Ρ�$(xx).tabs()����������ʱ����ô˷���
 * @4._setOption ��$(xx).tabs(��option��,xxx)�����ֵ��÷�ʽ����ô˷��� ����������
 * @5.destroy ����ģ��
 * @6.option ���û��ȡ����
 * @7.enable ����ģ�鹦��
 * @8.disable ���ù���
 *
 * �������е�jquery ui�ؼ�������д��Щ�ӿڣ�ͬʱ���ӿؼ���ص�˽�л��з�����
 *
 * ��ס��jquery woo��ʵ���Ǻ�Ԫ�ع��������ģ���Ϊ���ݱ��������ˡ���¶���û�ʹ�õ�ֻ��jquery���������ӵķ�����һ�����ǲ���Ҫ��ȡui��ʵ����
 */
(function($) {

 	//jQuery.fx.off = true;//�رն���
	/**
	 *
	 * @param {name}
	 *            ��'woo.mycomponent'
	 * @param {base}
	 *            ��д��ȡ���ӵ����Գ�Ա����{options : {v : 10}}
	 * @param {prototype}
	 * @return {Boolean} $.component("woo.mycomponent",{})
	 *         $.component("woo.mycomponent", $.Component,{})
	 */

	$.component = function(name, base, prototype) {
		var ns = name.split('.')[0], fullName;// woo
		name = name.split('.')[1];// mycomponent
		fullName = ns + '-' + name;// woo-mycomponent
		// ���û�ж���prototype prototypeΪ�����ԣ�baseΪ$.Component
		if (!prototype) {
			prototype = base;
			base = $.Component;
		}

		/**
		 * create selector for plugin ��չѡ��������Ĺ��� $(":woo-mycomponent")
		 * $(":woo-menu") ����ѡ�����и����������󼯺�
		 */
		$.expr[":"][fullName] = function(elem) {
			return !!$.data(elem, name);
		};

		/**
		 * ���������ռ� $['woo']
		 */
		$[ns] = $[ns] || {};
		// ����$['woo']['mycomponent']���캯��
		$[ns][name] = function(options, element) {
			if (arguments.length) {
				this._createComp(options, element);
			}
		};
		var basePrototype = new base();// ����һ���������$.Component
		// we need to make the options hash a property directly on the new
		// instance
		// otherwise we'll modify the options hash on the prototype that we're
		// inheriting from
		// $.each( basePrototype, function( key, val ) {
		// if ( $.isPlainObject(val) ) {
		// basePrototype[ key ] = $.extend( {}, val );
		// }
		// });
		// extend(dest,src1,src2,src3...srcN) ,�ϲ��������.
		basePrototype.options = $.extend({}, basePrototype.options);// �����ʲô���壿��֤basePrototype.options����Ϊ{}
		/**
		 * 1.extend(boolean,dest,src1,src2...), ������׶���
		 * 2.����$['woo']['mycomponent']ԭ�ͣ�������ԭ��ָ��ϲ���ĸ����������ͨ���������Ҳ����ֱ�ӵ��û���prototype�ķ���
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

	// ��������ܸ��ӵ�jQuery������,��ʵ�ǹ��������ӹ�ȥ������jquery�������ͨ���������������ʵ�����������ԣ���ʵԭ������ҵ�jquery�����е�data�����ʵ����������ʵ�������Ը�������ʵ����data�洢��_createComp��ʵ��
	$.component.bridge = function(name, object) {
		$.fn[name] = function(options) {
			var isMethodCall = typeof options === "string", args = Array.prototype.slice
					.call(arguments, 1), returnValue = this;
			// �����һ��������string���ͣ�����Ϊ�ǵ���ģ�鷽��
			// ʣ�µĲ�����Ϊ�����Ĳ�����������õ�

			// allow multiple hashes to be passed on init
			// ���Լ���Ϊ��$.extend(true,options,args[0],...),args������һ��������������
			options = !isMethodCall && args.length ? $.extend.apply(null, [
							true, options].concat(args)) : options;
			// prevent calls to internal methods
			// ��ͷ���»��ߵķ�������˽�з��������õ���
			if (isMethodCall && options.substring(0, 1) === "_") {
				return returnValue;
			}

			if (isMethodCall) {// ����ǵ��ú���
				this.each(function() {
							var instance = $.data(this, name), // �õ�ʵ����ʵ����Ϊһ�����ݺ�Ԫ�ع�����
							methodValue = instance
									&& $.isFunction(instance[options])
									? instance[options].apply(instance, args)
									: instance;// ���ʵ���ͷ��������ڣ����÷�������args��Ϊ��������ȥ
							// ���methodValue����jquery����Ҳ����undefined
							if (methodValue !== instance
									&& methodValue !== undefined) {
								returnValue = methodValue;
								return false;// ����each��һ���ȡoptions��ֵ���������֧
							}
						});
			} else {// ���Ǻ������õĻ�
				//this.each(function() {
							var instance = $.data(this, name);
							if (instance) {// ʵ������
								if (options) {// �в���
									instance.option(options);// ����option������һ��������״̬֮��Ĳ���
								}
								instance._init();// �ٴε��ô˺���������options����
							} else {
								// û��ʵ���Ļ�����Ԫ�ذ�һ��ʵ����ע�������this��dom��object��ģ����
								$.data(this, name, new object(options, this));
							}
						//});
			}

			return returnValue;// ���أ��п�����jquery�����п���������ֵ
		};
	};
	// ����ģ��Ļ���
	$.Component = function(options, element) {
		if (arguments.length) {// ����в��������ó�ʼ������
			this._createComp(options, element);
		}
	}

	$.Component.prototype = {
		cmpName : 'component',
		eventProfix : '',
		options : {
			disabled : false,

			// ����options
			cancel : ':input,option',
			distance : 1,
			delay : 0
		},
		_createComp : function(options, element) {
			// so that it's stored even before the _create function runs
			// �����ʵ���洢����$.data�У���$.component.bridgeʹ��

			this.element = this.el = $(element).data(this.cmpName, this);
			this.options = $.extend(true, {}, this.options, $.metadata
							&& $.metadata.get(element)[this.cmpName], options);
			//Ϊ�˽�������<table>��Ϊ�ڵ���Ⱦ���ȷ�ʽ
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
			this.element.data('cmp', this);//��һ�����ư�������������� add by chengl 2011-05-07
			var self = this;
			this.el.bind("remove." + this.cmpName, function() {
						self.destroy();
					});
			this._bindEvent();// ���¼�
			this._create();// ����
			this._init();// ��ʼ��

			//add by chengl 2012-04-27
			this.isRender=true;
		},
		_create : $.noop,
		_init : $.noop,

		destroy : function() {// ����ģ�飺ȥ�����¼���ȥ�����ݡ�ȥ����ʽ������
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

		// ����������AOP��ʵ��
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

		// ����¼���ʼ��
		_mouseInit : function() {
			var self = this;

			this.el.bind('mousedown.' + this.cmpName, function(event) {
						return self._mouseDown(event);
					}).bind('click.' + this.cmpName, function(event) {
						if (self._preventClickEvent) {
							// ��ֹ����¼�ð��
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
			// ���������������ִ��_mouseCapture ����Ϊfalse,��������
			if (!btnIsLeft || elIsCancel || !this._mouseCapture(event)) {
				return true;
			}
			// �Ƿ��ӳ�,0 : this.mouseDelayMet = true
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
		 * ���¼�
		 */
		_bindEvent : function() {
			var el = this.el, p = this.options, event = p.event;
			for (var e in event) {
				el.bind(e, event[e]);
			}
		},
		/**
		 *�ж�����Ƿ���Ⱦ
		 */
		getIsRender:function(){
			return this.isRender;
		}

	}

	$.component('woo.component');

	// JavaScript�̳�ʵ�ַ�������
	// http://groups.google.com/group/comp.lang.javascript/msg/e04726a66face2a2
	// http://webreflection.blogspot.com/2008/10/big-douglas-begetobject-revisited.html
	// �����������һ���࣬�Ѹ���ԭ��ָ��������ԭ�ͣ������������ʵ��
	var object = (function(F) {// FΪһ���յĹ��캯������Ϊ��ʹ��ԭ�ͷ���֮ǰ��Ҫ����һ������
		return (function(o) {
			F.prototype = o;// oΪsuperproto
			return new F();
		});
	})(function() {
			});

	// ����һ�����������
	var OVERRIDE = /xyz/.test(function() {
				xyz;
			}) ? /\b_super\b/ : /.*/;

	// ���һ������
	$.woo.component.subclass = function subclass(name) {

		$.component(name); // Slightly inefficient to create a widget only to
		// discard its prototype, but it's not too bad
		name = name.split('.');
		var component = $[name[0]][name[1]], superclass = this, superproto = superclass.prototype;
		for (key in superproto) {
			// alert(key)
		}
		// �������ԭ��ָ�����ʵ��
		var o = $.extend( object(superproto), component.prototype);// update by chengl ���������ǰԭ�͵�������cmpName
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
						if(arguments[1]){//�޸���������޷����ݵ�bug
							options=$.extend(true, {}, options,
								arguments[1]);
						}
						// update by chengl �����ȼ̳����⣬������������Ƕ�׵Ķ��󲻻ᱻ����
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