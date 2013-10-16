var sCalc = function(targ, v, f, c, r, logger) {
	this.displayBox = document.getElementById(targ);
	this.elems = {};

	this.variables = v;
	this.formStruc = f;
	this.calculator = c;
	this.report = r;

	this.reportFields = {};


	this.logger = logger;
	this.initialize();

}


	sCalc.prototype.initialize = function() {
	this.log("CALL sCalc.prototype.initialize = function()");
		this.v = JSON.parse(this.variables);
		this.f = JSON.parse(this.formStruc);
		this.r = JSON.parse(this.report);

		this.buildForm();
		this.buildReport();
		this.update();
	this.log("FINISH sCalc.prototype.initialize = function()");
	}


	sCalc.prototype.update = function(inputObj) {
	this.log ("CALL sCalc.prototype.update = function(inputObj)");
		if (inputObj) {
			if (inpObj.inpDef && inputObj.inpDef.inpType === "text") {
				this.v[inputObj.inpDef.map] = inputObj.value;

				this.calculate();
			} else {
				if ( isNaN(inputObj.value) ) {
					inputObj.value = this.v[inputObj.inpDef.map];
				}
				else {
					this.v[inputObj.inpDef.map] = parseInt(inputObj.value,10);
				}
				this.calculate();
			}
		}
	this.log("FINISH sCalc.prototype.update = function(inputObj)");
	}


	sCalc.prototype.calculate = function() {
	this.log("CALL sCalc.prototype.calculate = function() ");
		try {
			eval ("var v = this.v; " + this.calculator);
		} 
		catch (exception) {
			alert(exception);
		}
	this.log("FINISH sCalc.prototype.calculate = function() ");
	}


	sCalc.prototype.buildForm = function() {
	this.log("CALL sCalc.prototype.buildForm = function()");
		var formBox = createSuperElement("div", ["class", "spellCalcForm"], ["innerHTML", "Form Box"]);
		this.elems.formBox = formBox;

		appendChildren(this.displayBox,formBox);

		if (usefulTypeOf(this.f) === "[object Array]") {
			var formTable = createSuperElement("table", ["class", "spellCalcForm"]);
			appendChildren(formBox, formTable);
			for(var i = 0; i < this.f.length; i++) {
				this.buildFormRow(this.f[i], formTable)
			}
		}
	this.log("FINISH sCalc.prototype.buildForm = function()");
	}


	sCalc.prototype.buildFormRow = function(rdef, table) {
		this.log("CALL sCalc.prototype.buildFormRow = function(rdef, table)");
		var tr;
		var td;

		tr = createSuperElement("tr");
//		td = createSuperElement("td",["innerHTML", "Row " + usefulTypeOf(rdef)] );
//		appendChildren(tr,td);

		if (usefulTypeOf(rdef) === "[object Array]" ) {
			for (var j = 0; j < rdef.length; j++) {
				this.buildFormCell(rdef[j], tr);
			}
		}

		appendChildren(table, tr);
		this.log("FINISH sCalc.prototype.buildFormRow = function(rdef, table)");
	}


	sCalc.prototype.buildFormCell = function(cellDef, row) {
		this.log("CALL sCalc.prototype.buildFormCell = function(cellDef, row)");
		var tdH;
		var tdI;
		var inp;

		if (usefulTypeOf(cellDef) === "[object Object]" ) {
			tdH = createSuperElement("td", ["innerHTML",cellDef.label], ["class","spellCalcHead"], ["colspan", (cellDef.hCol) ? cellDef.hCol : 1]);
			tdI = createSuperElement("td", ["colspan", (cellDef.fCol) ? cellDef.fCol : 1]);
			inp = createSuperElement("input", 
				["size",2], ["maxlength",4], 
				["value", (this.v[cellDef.map]) ? this.v[cellDef.map] : 0 ],
				["onchange", "this.SCobj.update(this);"]);
			inp.inpDef = cellDef;
			inp.SCobj = this;
			appendChildren(tdI,inp);

			appendChildren(row, tdH,tdI);
		}
		this.log("FINISH sCalc.prototype.buildFormCell = function(cellDef, row)");
	}






	sCalc.prototype.buildReport = function() {
		this.log("CALL sCalc.prototype.buildReport = function()");
		var repBox = createSuperElement("div", ["class", "spellCalcRep"], ["innerHTML","Report Box"])
		this.elems.repBox = repBox;

		appendChildren(this.displayBox,repBox);

		if (usefulTypeOf(this.r) === "[object Array]") {
			var repTable = createSuperElement("table", ["class", "spellCalcRep"]);
			appendChildren(repBox, repTable);
			for(var i = 0; i < this.r.length; i++) {
				this.buildReportRow(this.r[i], repTable);
			}
		}		
		this.log("FINISH sCalc.prototype.buildReport = function()");
	}

	sCalc.prototype.buildReportRow = function(rdef, table) {
		this.log("CALL sCalc.prototype.buildReportRow = function(rdef, table)");
		var tr;
		var td;

		tr = createSuperElement("tr");
		td = createSuperElement("td",["innerHTML", "Row " + usefulTypeOf(rdef)] );
		appendChildren(tr,td);

		if (usefulTypeOf(rdef) === "[object Array]" ) {
			for (var j = 0; j < rdef.length; j++) {
				this.buildReportCell(rdef[j], tr);
			}
		}

		appendChildren(table, tr);
		this.log("FINISH sCalc.prototype.buildReportRow = function(rdef, table)");
	}

	sCalc.prototype.buildReportCell = function(cellDef, row) {
		this.log("CALL sCalc.prototype.buildReportCell = function(cellDef, row)");
		var cell;
		if (usefulTypeOf(cellDef) === "[object String]") {
			if (cellDef.charAt(0) === "$") {
				cell = createSuperElement("td");
				appendChildren(cell,this.buildReportDynamicSpan(cellDef));
			} else {
				cell = createSuperElement("td", ["class", "spellCalcRep"], ["innerHTML",cellDef]);
			}
		} 

		if (!cell) {
			cell = createSuperElement("td",["innerHTML","error"] );
		}
		appendChildren(row, cell);

/*
		else if (usefulTypeOf(cellDef) === "[object Object]" ) {
			cell = createSuperElement("td", 
					["class", "spellCalcRep" + cellDef["class"] ],
					["colspan", (cellDef.cols) ? cellDef.cols : 1],
					["innerHTML",usefulTypeOf(cellDef.text)]);
		}
/*			tdI = createSuperElement("td", ["colspan", (cellDef.fCol) ? cellDef.fCol : 1]);
			inp = createSuperElement("input", 
				["size",2], ["maxlength",4], 
				["value", (this.v[cellDef.map]) ? this.v[cellDef.map] : 0 ],
				["onchange", "this.SCobj.update(this);"]);
			inp.inpDef = cellDef;
			inp.SCobj = this;
			appendChildren(tdI,inp);
*/
		this.log("FINISH sCalc.prototype.buildReportCell = function(cellDef, row)");
	}

	sCalc.prototype.buildReportDynamicSpan = function(varName) {
		this.log("CALL sCalc.prototype.buildReportDynamicSpan = function(" + varName + ")");
		var fieldName = varName.substring(1,varName.length);
		this.reportFields[fieldName] = createSuperElement("span", ["innerHTML", fieldName + " " + this.v[fieldName] ]);
		this.log("FINISH sCalc.prototype.buildReportDynamicSpan = function(" + fieldName + ")");
		return this.reportFields[fieldName];
	}




/*
var sCalc = {
	"version": "20131014a",
	"formname": "sCalcInterface",
	"CSSname": "sCalc",
	"displayBox": "sCalc",
	"Manager": "Manager", // used to target the Manager object in dynamically generated onClick, onChange, and other objects.
	"debug":  true,
	"traceLog": "",
	"logCalls": 0,
	"storageName": "chronSpellCalc"
}; 

*/


	sCalc.prototype.log = function(msg) {
		if (this.logger && this.logger.log) {
			this.logger.log(msg);
		}
	}

	sCalc.prototype.clearLog = function() {
		this.traceLog = "";
	}
	
	sCalc.prototype.eval = function(cmd) {
		this.log(cmd);
		try {
			eval(cmd);
		} 
		catch (exception) {
			this.log(exception);
		}
	}
	


	

	/*
	Service cleanup functions
	*/
	sCalc.prototype.destroy = function() {
		if (sCalc.debug) sCalc.log("[DESTROY]" + this.jsCLASSNAME + " " + this.jsOBJNAME);
		this.destroyFlag = 1;
		for (var svc in this) {
			if (this[svc].destroy && typeof this[svc].destroy == "function" && !this[svc].destroyFlag) {
				this[svc].destroy();
				delete this[svc];
			}
		}	
	}

	sCalc.prototype.extend = function(child, parent) {
		var f = function() {};
		f.prototype = parent.prototype
		child.prototype = new f();
	}

	sCalc.prototype.shallowMerge = function(p, c) {
		if (typeof c === "object") {
			for (var i in p) {
				if (typeof p[i] !== "object") {
					c[i] = p[i];
				}
			}
		}
	}

	sCalc.prototype.deepCopy = function(p, c) {
		var c = c || {};
		for (var i in p) {
			if (p[i] === null) {
				c[i] = p[i];
			}
			else if (typeof p[i] === 'object') {
				c[i] = (p[i].constructor === Array) ? [] : {}; // array or object
				sCalc.deepCopy(p[i], c[i]);
			} else {
				c[i] = p[i];
			}
		}
		return c;
	}
	
	
	
function addSlashes(str) {
str=str.replace(/\\/g,'\\\\');
str=str.replace(/\'/g,'\\\'');
str=str.replace(/\"/g,'\\"');
str=str.replace(/\0/g,'\\0');
return str;
}
function stripSlashes(str) {
str=str.replace(/\\'/g,'\'');
str=str.replace(/\\"/g,'"');
str=str.replace(/\\0/g,'\0');
str=str.replace(/\\\\/g,'\\');
return str;
}	
function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}
function ltrim(stringToTrim) {
	return stringToTrim.replace(/^\s+/,"");
}
function rtrim(stringToTrim) {
	return stringToTrim.replace(/\s+$/,"");
}

function appendChildren() {
	if (arguments[0] && arguments[0].appendChild) {
		var n = undefined;
		for (i = 1; i < arguments.length; i++) {
			if (arguments[i] === "\n") {
				n = document.createElement("br");
				arguments[0].appendChild(n);
				n = undefined;
			}
			else if (typeof arguments[i] == "string" || typeof arguments[i] == "number") {
				n = document.createTextNode(arguments[i]);
				arguments[0].appendChild(n);
				n = undefined;
			} else {
				arguments[0].appendChild(arguments[i]);
			}
		}
	}
}

function createSuperElement () {
	if (typeof arguments[0] === "string") {
		var el = document.createElement(arguments[0]);
		for (var i = 1; i < arguments.length; i++) {
			if (arguments[i].constructor == Array && arguments[i].length > 1) {
				if (arguments[i][0] == "innerHTML") {
					el.innerHTML = arguments[i][1];
				}
				else {
					el.setAttribute(arguments[i][0], arguments[i][1]);				
				}
			}
		}
		return el;
	}
}

function usefulTypeOf (obj) {
	return Object.prototype.toString.call(obj);
}