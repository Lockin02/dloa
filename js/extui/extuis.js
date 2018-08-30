mini.CheckColumn = function($) {
	return mini.copyTo({
		width: 24,
		cellCls: "mini-checkcolumn",
		headerCls: "mini-checkcolumn",
		_multiRowSelect: true,
		header: function($) {
			var A = this.uid + "checkall",
			_ = "<input type=\"checkbox\" id=\"" + A + "\" />";
			if (this[OOl0lo] == false) _ = "";
			return _
		},
		getCheckId: function($) {
			return this._gridUID + "$checkcolumn$" + $[this._rowIdField]
		},
		init: function($) {
			$[l1O00l]("selectionchanged", this.o101, this);
			$[l1O00l]("HeaderCellClick", this.OO0o, this)
		},
		renderer: function(C) {
			var B = this.getCheckId(C.record),
			_ = C.sender[O1011] ? C.sender[O1011](C.record) : false,
			A = "checkbox",
			$ = C.sender;
			if ($[OOl0lo] == false) A = "radio";
			return "<input type=\"" + A + "\" id=\"" + B + "\" " + (_ ? "checked": "") + " hidefocus style=\"outline:none;\" onclick=\"return false\"/>"
		},
		OO0o: function(B) {
			var $ = B.sender;
			if (B.column != this) return;
			var A = $.uid + "checkall",
			_ = document.getElementById(A);
			if (_) {
				if ($[Oo1o1l]()) {
					if (_.checked) $[l0OO0]();
					else $[O100ll]()
				} else {
					$[O100ll]();
					if (_.checked) $[Ol11O](0)
				}
				$[l011l]("checkall")
			}
		},
		o101: function(H) {
			var $ = H.sender,
			C = $[l11o0]();
			for (var A = 0,
			E = C.length; A < E; A++) {
				var _ = C[A],
				G = $[O1011](_),
				F = $.uid + "$checkcolumn$" + _[$._rowIdField],
				B = document.getElementById(F);
				if (B) B.checked = G
			}
			var D = this;
			if (!this._timer) this._timer = setTimeout(function() {
				D._doCheckState($);
				D._timer = null
			},
			10)
		},
		_doCheckState: function($) {
			var B = $.uid + "checkall",
			_ = document.getElementById(B);
			if (_ && $[Oo11o]) {
				var A = $[Oo11o]();
				if (A == "has") {
					_.indeterminate = true;
					_.checked = true
				} else {
					_.indeterminate = false;
					_.checked = A
				}
			}
		}
	},
	$)
};
mini.Ooo0o["checkcolumn"] = mini.CheckColumn;
mini.IndexColumn = function($) {
	return mini.copyTo({
		width: 24,
		cellCls: "mini-IndexColumn",
		align: "center",
		draggable: false,
		allowDrag: true,
		init: function($) {
			$[l1O00l]("addrow", this.__OnIndexChanged, this);
			$[l1O00l]("removerow", this.__OnIndexChanged, this);
			$[l1O00l]("moverow", this.__OnIndexChanged, this);
			if ($.isTree) {
				$[l1O00l]("loadnode", this.__OnIndexChanged, this);
				this._gridUID = $.uid;
				this[O0o0lO] = "_id"
			}
		},
		getNumberId: function($) {
			return this._gridUID + "$number$" + $[this._rowIdField]
		},
		createNumber: function($, _) {
			if (mini.isNull($[o10O1])) return _ + 1;
			else return ($[o10O1] * $[o101oo]) + _ + 1
		},
		renderer: function(A) {
			var $ = A.sender;
			if (this.draggable) {
				if (!A.cellStyle) A.cellStyle = "";
				A.cellStyle += ";cursor:move;"
			}
			var _ = "<div id=\"" + this.getNumberId(A.record) + "\">";
			if (mini.isNull($[o10O1])) _ += A.rowIndex + 1;
			else _ += ($[o10O1] * $[o101oo]) + A.rowIndex + 1;
			_ += "</div>";
			return _
		},
		__OnIndexChanged: function(F) {
			var $ = F.sender,
			C = $[l11o0]();
			for (var A = 0,
			D = C.length; A < D; A++) {
				var _ = C[A],
				E = this.getNumberId(_),
				B = document.getElementById(E);
				if (B) B.innerHTML = this.createNumber($, A)
			}
		}
	},
	$)
};
mini.Ooo0o["indexcolumn"] = mini.IndexColumn;
mini.RemoveColumn = function($) {
	return mini.copyTo({
		width: 24,
		cellCls: "mini-removecolumn",
		headerCls: "mini-removecolumn",
		align : "center",
		draggable : false,
		allowDrag : true,
		header: function($) {
			var A = this.uid + "-addRow-"+this._id, _ = "<span class=\"addBn\" id=\""
					+ A + "\"  onclick=\"addRow('"+this.id+"')\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
			if (this[OOl0lo] == false)
				_ = "";
			return _
		},
		getCheckId: function($) {
			return this._gridUID + "$checkcolumn$" + $[this._rowIdField]
		},
		init : function($) {
			this._gridUID=$.uid;
			this._gridID=$.id;
			},
		renderer: function(C) {
			return "<span class=\"removeBn\"id=\""
									+this._gridUID + "-removeBn-"+this._id+" \" onclick=\"removeRow('"+this._gridID+"')\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
		}
	},
	$)
};
mini.Ooo0o["removecolumn"] = mini.RemoveColumn;
mini.AddColumn = function($) {
	return mini
			.copyTo(
					{
						width : 24,
						cellCls : "mini-addcolumn",
						headerCls : "mini-addcolumn",
						align : "center",
						draggable : false,
						allowDrag : true,
						header : function($) {
							var A = this.uid + "-addRow", _ = "<span class=\"addBn\" id=\""
									+ A + "\"  onclick=\"addRow()\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
							if (this[OOl0lo] == false)
								_ = "";
							return _
						},
						init : function($) {
							this._gridUID=this.uid;
						},
						getCheckId : function($) {
							return this._gridUID + "$checkcolumn$"
									+ $[this._rowIdField]
						},
						renderer : function($) {
							return "<span class=\"addBn\"id=\""
									+ this._gridUID + "-addRow \" onclick=\"removeRow()\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
						},
						_doCheckState : function($) {
						 	//var newRow = { name: "New Row" };
            					//this.addRow(newRow, 0);
								//alert(1)
							
						}
					}, $)
};
mini.Ooo0o["addcolumn"] = mini.AddColumn;
//input 宽设置
mini.copyTo(oO0O01.prototype, {
	width : 250,
	height : 26
	
});
l10lO = function() {
	if (!this[o1O00O]()) return;
	o1olOo[lllo0o][oo11O1][O11O10](this);
	if (this._closeEl) this._closeEl.style.display = this.showClose ? "": "none";
	var _ = o0O11(this.el);
	if (this.el.style.width == "100%") _ -= 1;
	if (this.o0o1l) _ -= 18;
	_ -= 2;
	var $ = this.el.style.width.toString();
	if ($[oO110o]("%") != -1) _ -= 1;
	if (_ < 0) _ = 0;
	this.ll1O00.style.width = _ + "px";
	_ -= this._buttonWidth;
	if (this.el.style.width == "100%") _ -= 1;
	if (this.showClose) _ -= this._closeWidth;
	if (_ < 0) _ = 0;
	this.l11ll.style.width = _ -5+ "px"
};
OolO1[oo11O1] = l10lO;
//mini.copyTo(OllOOl.style, {
	//width: width-20
//});
/*
rowmouseover = function(_, $) {
	this[o0lo]("rowmouseover", _, $);
	alert(2);
};
var gridRowMouseOver = "onRowMouseOver"
ll1Ol[gridRowMouseOver] = rowmouseover;

buttonsearch = function() {
	this.el = document.createElement("span");
	this.el.className = "mini-buttonsearch";
	var $ = this.OOoo0OHtml() + "<span class=\"mini-buttonsearch-close\"></span>";
	this.el.innerHTML = "<span class=\"mini-buttonsearch-border\"><input type=\"input\" class=\"mini-buttonsearch-input\" autocomplete=\"off\"/>" + $ + "</span><input name=\"" + this.name + "\" type=\"hidden\"/>";
	this.lo10 = this.el.firstChild;
	this.OllOOl = this.lo10.firstChild;
	this.l0O01 = this.el.lastChild;
	this._closeEl = this.lo10.lastChild;
	this._buttonEl = this._closeEl.previousSibling;
	this.OOo01O()
};
lloOls = function() {
	lloOls[Ol1oo0][OO1l1O][O0o00O](this);
	var $ = this[O0OO]();
	if ($ || this.allowInput == false) this.OllOOl[lOOo01] = true;
	if (this.enabled == false) this[o11oo](this.l000lO);
	if ($) this[o11oo](this.o0O1O0);
	if (this.required) this[o11oo](this.loOO11)
};

OOOO1[O1olo] = buttonsearch;
OOOO1[lloOls] = l0o0l;
l0oo(lloOls, "buttonsearch");
oloO(lloOls, O0OOO, {
	name: "",
	formField: true,
	selectOnFocus: false,
	showClose: false,
	emptyText: "",
	defaultValue: "",
	value: "",
	text: "",
	maxLength: 1000,
	minLength: 0,
	width: 125,
	height: 21,
	inputAsValue: false,
	allowInput: true,
	o1oO1o: "mini-buttonedit-noInput",
	o0O1O0: "mini-buttonedit-readOnly",
	l000lO: "mini-buttonedit-disabled",
	l00111: "mini-buttonedit-empty",
	o0o0lO: "mini-buttonedit-focus",
	l11001: "mini-buttonedit-button",
	O1oo: "mini-buttonedit-button-hover",
	Oo0l: "mini-buttonedit-button-pressed",
	_closeCls: "mini-buttonedit-close",
	uiCls: "mini-buttonsearch",
	OOOo0: false,
	_buttonWidth: 20,
	_closeWidth: 20,
	lO11O: null,
	textName: ""
});
this.grid[O1lOO1]()
$('a').mousemove()

mini.copyTo(this.grid.el,"mousemove", {
	 this.grid.beginEditRow()
});

O1l1(this.grid.el, "mousemove",
			function(C) {
				this.grid[O1lOO1]();
			},
			this);
this.grid = $;*/
