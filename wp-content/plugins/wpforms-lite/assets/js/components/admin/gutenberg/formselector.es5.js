(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t.return && (u = t.return(), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw new Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator.return && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw new Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, catch: function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw new Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }
function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }
/* global wpforms_gutenberg_form_selector, Choices, JSX, DOM */
/* jshint es3: false, esversion: 6 */

/**
 * Gutenberg editor block.
 *
 * @since 1.8.1
 */
var WPForms = window.WPForms || {};
WPForms.FormSelector = WPForms.FormSelector || function (document, window, $) {
  var _wp = wp,
    _wp$serverSideRender = _wp.serverSideRender,
    ServerSideRender = _wp$serverSideRender === void 0 ? wp.components.ServerSideRender : _wp$serverSideRender;
  var _wp$element = wp.element,
    createElement = _wp$element.createElement,
    Fragment = _wp$element.Fragment,
    useState = _wp$element.useState,
    createInterpolateElement = _wp$element.createInterpolateElement;
  var registerBlockType = wp.blocks.registerBlockType;
  var _ref = wp.blockEditor || wp.editor,
    InspectorControls = _ref.InspectorControls,
    InspectorAdvancedControls = _ref.InspectorAdvancedControls,
    PanelColorSettings = _ref.PanelColorSettings;
  var _wp$components = wp.components,
    SelectControl = _wp$components.SelectControl,
    ToggleControl = _wp$components.ToggleControl,
    PanelBody = _wp$components.PanelBody,
    Placeholder = _wp$components.Placeholder,
    Flex = _wp$components.Flex,
    FlexBlock = _wp$components.FlexBlock,
    __experimentalUnitControl = _wp$components.__experimentalUnitControl,
    TextareaControl = _wp$components.TextareaControl,
    Button = _wp$components.Button,
    Modal = _wp$components.Modal;
  var _wpforms_gutenberg_fo = wpforms_gutenberg_form_selector,
    strings = _wpforms_gutenberg_fo.strings,
    defaults = _wpforms_gutenberg_fo.defaults,
    sizes = _wpforms_gutenberg_fo.sizes;
  var defaultStyleSettings = defaults;
  var __ = wp.i18n.__;

  /**
   * List of forms.
   *
   * Default value is localized in FormSelector.php.
   *
   * @since 1.8.4
   *
   * @type {Object}
   */
  var formList = wpforms_gutenberg_form_selector.forms;

  /**
   * Blocks runtime data.
   *
   * @since 1.8.1
   *
   * @type {Object}
   */
  var blocks = {};

  /**
   * Whether it is needed to trigger server rendering.
   *
   * @since 1.8.1
   *
   * @type {boolean}
   */
  var triggerServerRender = true;

  /**
   * Popup container.
   *
   * @since 1.8.3
   *
   * @type {Object}
   */
  var $popup = {};

  /**
   * Track fetch status.
   *
   * @since 1.8.4
   *
   * @type {boolean}
   */
  var isFetching = false;

  /**
   * Public functions and properties.
   *
   * @since 1.8.1
   *
   * @type {Object}
   */
  var app = {
    /**
     * Start the engine.
     *
     * @since 1.8.1
     */
    init: function init() {
      app.initDefaults();
      app.registerBlock();
      $(app.ready);
    },
    /**
     * Document ready.
     *
     * @since 1.8.1
     */
    ready: function ready() {
      app.events();
    },
    /**
     * Events.
     *
     * @since 1.8.1
     */
    events: function events() {
      $(window).on('wpformsFormSelectorEdit', _.debounce(app.blockEdit, 250)).on('wpformsFormSelectorFormLoaded', _.debounce(app.formLoaded, 250));
    },
    /**
     * Get fresh list of forms via REST-API.
     *
     * @since 1.8.4
     *
     * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-api-fetch/
     */
    getForms: function getForms() {
      return _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
        var response;
        return _regeneratorRuntime().wrap(function _callee$(_context) {
          while (1) switch (_context.prev = _context.next) {
            case 0:
              if (!isFetching) {
                _context.next = 2;
                break;
              }
              return _context.abrupt("return");
            case 2:
              // Set the flag to true indicating a fetch is in progress.
              isFetching = true;
              _context.prev = 3;
              _context.next = 6;
              return wp.apiFetch({
                path: '/wpforms/v1/forms/',
                method: 'GET',
                cache: 'no-cache'
              });
            case 6:
              response = _context.sent;
              // Update the form list.
              formList = response.forms;
              _context.next = 13;
              break;
            case 10:
              _context.prev = 10;
              _context.t0 = _context["catch"](3);
              // eslint-disable-next-line no-console
              console.error(_context.t0);
            case 13:
              _context.prev = 13;
              isFetching = false;
              return _context.finish(13);
            case 16:
            case "end":
              return _context.stop();
          }
        }, _callee, null, [[3, 10, 13, 16]]);
      }))();
    },
    /**
     * Open builder popup.
     *
     * @since 1.6.2
     *
     * @param {string} clientID Block Client ID.
     */
    openBuilderPopup: function openBuilderPopup(clientID) {
      if ($.isEmptyObject($popup)) {
        var tmpl = $('#wpforms-gutenberg-popup');
        var parent = $('#wpwrap');
        parent.after(tmpl);
        $popup = parent.siblings('#wpforms-gutenberg-popup');
      }
      var url = wpforms_gutenberg_form_selector.get_started_url,
        $iframe = $popup.find('iframe');
      app.builderCloseButtonEvent(clientID);
      $iframe.attr('src', url);
      $popup.fadeIn();
    },
    /**
     * Close button (inside the form builder) click event.
     *
     * @since 1.8.3
     *
     * @param {string} clientID Block Client ID.
     */
    builderCloseButtonEvent: function builderCloseButtonEvent(clientID) {
      $popup.off('wpformsBuilderInPopupClose').on('wpformsBuilderInPopupClose', function (e, action, formId, formTitle) {
        if (action !== 'saved' || !formId) {
          return;
        }

        // Insert a new block when a new form is created from the popup to update the form list and attributes.
        var newBlock = wp.blocks.createBlock('wpforms/form-selector', {
          formId: formId.toString() // Expects string value, make sure we insert string.
        });

        // eslint-disable-next-line camelcase
        formList = [{
          ID: formId,
          post_title: formTitle
        }];

        // Insert a new block.
        wp.data.dispatch('core/block-editor').removeBlock(clientID);
        wp.data.dispatch('core/block-editor').insertBlocks(newBlock);
      });
    },
    /**
     * Register block.
     *
     * @since 1.8.1
     */
    // eslint-disable-next-line max-lines-per-function
    registerBlock: function registerBlock() {
      registerBlockType('wpforms/form-selector', {
        title: strings.title,
        description: strings.description,
        icon: app.getIcon(),
        keywords: strings.form_keywords,
        category: 'widgets',
        attributes: app.getBlockAttributes(),
        supports: {
          customClassName: app.hasForms()
        },
        example: {
          attributes: {
            preview: true
          }
        },
        edit: function edit(props) {
          // Get fresh list of forms.
          app.getForms();
          var attributes = props.attributes;
          var formOptions = app.getFormOptions();
          var handlers = app.getSettingsFieldsHandlers(props);

          // Store block clientId in attributes.
          if (!attributes.clientId) {
            // We just want client ID to update once.
            // The block editor doesn't have a fixed block ID, so we need to get it on the initial load, but only once.
            props.setAttributes({
              clientId: props.clientId
            });
          }

          // Main block settings.
          var jsx = [app.jsxParts.getMainSettings(attributes, handlers, formOptions)];

          // Block preview picture.
          if (!app.hasForms()) {
            jsx.push(app.jsxParts.getEmptyFormsPreview(props));
            return jsx;
          }
          var sizeOptions = app.getSizeOptions();

          // Form style settings & block content.
          if (attributes.formId) {
            jsx.push(app.jsxParts.getStyleSettings(props, handlers, sizeOptions), app.jsxParts.getAdvancedSettings(props, handlers), app.jsxParts.getBlockFormContent(props));
            handlers.updateCopyPasteContent();
            $(window).trigger('wpformsFormSelectorEdit', [props]);
            return jsx;
          }

          // Block preview picture.
          if (attributes.preview) {
            jsx.push(app.jsxParts.getBlockPreview());
            return jsx;
          }

          // Block placeholder (form selector).
          jsx.push(app.jsxParts.getBlockPlaceholder(props.attributes, handlers, formOptions));
          return jsx;
        },
        save: function save() {
          return null;
        }
      });
    },
    /**
     * Init default style settings.
     *
     * @since 1.8.1
     */
    initDefaults: function initDefaults() {
      ['formId', 'copyPasteJsonValue'].forEach(function (key) {
        return delete defaultStyleSettings[key];
      });
    },
    /**
     * Check if site has forms.
     *
     * @since 1.8.3
     *
     * @return {boolean} Whether site has at least one form.
     */
    hasForms: function hasForms() {
      return formList.length >= 1;
    },
    /**
     * Block JSX parts.
     *
     * @since 1.8.1
     *
     * @type {Object}
     */
    jsxParts: {
      /**
       * Get main settings JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} attributes  Block attributes.
       * @param {Object} handlers    Block event handlers.
       * @param {Object} formOptions Form selector options.
       *
       * @return {JSX.Element} Main setting JSX code.
       */
      getMainSettings: function getMainSettings(attributes, handlers, formOptions) {
        if (!app.hasForms()) {
          return app.jsxParts.printEmptyFormsNotice(attributes.clientId);
        }
        return /*#__PURE__*/React.createElement(InspectorControls, {
          key: "wpforms-gutenberg-form-selector-inspector-main-settings"
        }, /*#__PURE__*/React.createElement(PanelBody, {
          className: "wpforms-gutenberg-panel",
          title: strings.form_settings
        }, /*#__PURE__*/React.createElement(SelectControl, {
          label: strings.form_selected,
          value: attributes.formId,
          options: formOptions,
          onChange: function onChange(value) {
            return handlers.attrChange('formId', value);
          }
        }), /*#__PURE__*/React.createElement(ToggleControl, {
          label: strings.show_title,
          checked: attributes.displayTitle,
          onChange: function onChange(value) {
            return handlers.attrChange('displayTitle', value);
          }
        }), /*#__PURE__*/React.createElement(ToggleControl, {
          label: strings.show_description,
          checked: attributes.displayDesc,
          onChange: function onChange(value) {
            return handlers.attrChange('displayDesc', value);
          }
        }), /*#__PURE__*/React.createElement("p", {
          className: "wpforms-gutenberg-panel-notice"
        }, /*#__PURE__*/React.createElement("strong", null, strings.panel_notice_head), strings.panel_notice_text, /*#__PURE__*/React.createElement("a", {
          href: strings.panel_notice_link,
          rel: "noreferrer",
          target: "_blank"
        }, strings.panel_notice_link_text))));
      },
      /**
       * Print empty forms notice.
       *
       * @since 1.8.3
       *
       * @param {string} clientId Block client ID.
       *
       * @return {JSX.Element} Field styles JSX code.
       */
      printEmptyFormsNotice: function printEmptyFormsNotice(clientId) {
        return /*#__PURE__*/React.createElement(InspectorControls, {
          key: "wpforms-gutenberg-form-selector-inspector-main-settings"
        }, /*#__PURE__*/React.createElement(PanelBody, {
          className: "wpforms-gutenberg-panel",
          title: strings.form_settings
        }, /*#__PURE__*/React.createElement("p", {
          className: "wpforms-gutenberg-panel-notice wpforms-warning wpforms-empty-form-notice",
          style: {
            display: 'block'
          }
        }, /*#__PURE__*/React.createElement("strong", null, __('You haven’t created a form, yet!', 'wpforms-lite')), __('What are you waiting for?', 'wpforms-lite')), /*#__PURE__*/React.createElement("button", {
          type: "button",
          className: "get-started-button components-button is-secondary",
          onClick: function onClick() {
            app.openBuilderPopup(clientId);
          }
        }, __('Get Started', 'wpforms-lite'))));
      },
      /**
       * Get Field styles JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} props       Block properties.
       * @param {Object} handlers    Block event handlers.
       * @param {Object} sizeOptions Size selector options.
       *
       * @return {Object} Field styles JSX code.
       */
      getFieldStyles: function getFieldStyles(props, handlers, sizeOptions) {
        // eslint-disable-line max-lines-per-function
        return /*#__PURE__*/React.createElement(PanelBody, {
          className: app.getPanelClass(props),
          title: strings.field_styles
        }, /*#__PURE__*/React.createElement("p", {
          className: "wpforms-gutenberg-panel-notice wpforms-use-modern-notice"
        }, /*#__PURE__*/React.createElement("strong", null, strings.use_modern_notice_head), strings.use_modern_notice_text, " ", /*#__PURE__*/React.createElement("a", {
          href: strings.use_modern_notice_link,
          rel: "noreferrer",
          target: "_blank"
        }, strings.learn_more)), /*#__PURE__*/React.createElement("p", {
          className: "wpforms-gutenberg-panel-notice wpforms-warning wpforms-lead-form-notice",
          style: {
            display: 'none'
          }
        }, /*#__PURE__*/React.createElement("strong", null, strings.lead_forms_panel_notice_head), strings.lead_forms_panel_notice_text), /*#__PURE__*/React.createElement(Flex, {
          gap: 4,
          align: "flex-start",
          className: 'wpforms-gutenberg-form-selector-flex',
          justify: "space-between"
        }, /*#__PURE__*/React.createElement(FlexBlock, null, /*#__PURE__*/React.createElement(SelectControl, {
          label: strings.size,
          value: props.attributes.fieldSize,
          options: sizeOptions,
          onChange: function onChange(value) {
            return handlers.styleAttrChange('fieldSize', value);
          }
        })), /*#__PURE__*/React.createElement(FlexBlock, null, /*#__PURE__*/React.createElement(__experimentalUnitControl, {
          label: strings.border_radius,
          value: props.attributes.fieldBorderRadius,
          isUnitSelectTabbable: true,
          onChange: function onChange(value) {
            return handlers.styleAttrChange('fieldBorderRadius', value);
          }
        }))), /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-color-picker"
        }, /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-control-label"
        }, strings.colors), /*#__PURE__*/React.createElement(PanelColorSettings, {
          __experimentalIsRenderedInSidebar: true,
          enableAlpha: true,
          showTitle: false,
          className: "wpforms-gutenberg-form-selector-color-panel",
          colorSettings: [{
            value: props.attributes.fieldBackgroundColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('fieldBackgroundColor', value);
            },
            label: strings.background
          }, {
            value: props.attributes.fieldBorderColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('fieldBorderColor', value);
            },
            label: strings.border
          }, {
            value: props.attributes.fieldTextColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('fieldTextColor', value);
            },
            label: strings.text
          }]
        })));
      },
      /**
       * Get Label styles JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} props       Block properties.
       * @param {Object} handlers    Block event handlers.
       * @param {Object} sizeOptions Size selector options.
       *
       * @return {Object} Label styles JSX code.
       */
      getLabelStyles: function getLabelStyles(props, handlers, sizeOptions) {
        return /*#__PURE__*/React.createElement(PanelBody, {
          className: app.getPanelClass(props),
          title: strings.label_styles
        }, /*#__PURE__*/React.createElement(SelectControl, {
          label: strings.size,
          value: props.attributes.labelSize,
          className: "wpforms-gutenberg-form-selector-fix-bottom-margin",
          options: sizeOptions,
          onChange: function onChange(value) {
            return handlers.styleAttrChange('labelSize', value);
          }
        }), /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-color-picker"
        }, /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-control-label"
        }, strings.colors), /*#__PURE__*/React.createElement(PanelColorSettings, {
          __experimentalIsRenderedInSidebar: true,
          enableAlpha: true,
          showTitle: false,
          className: "wpforms-gutenberg-form-selector-color-panel",
          colorSettings: [{
            value: props.attributes.labelColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('labelColor', value);
            },
            label: strings.label
          }, {
            value: props.attributes.labelSublabelColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('labelSublabelColor', value);
            },
            label: strings.sublabel_hints.replace('&amp;', '&')
          }, {
            value: props.attributes.labelErrorColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('labelErrorColor', value);
            },
            label: strings.error_message
          }]
        })));
      },
      /**
       * Get Button styles JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} props       Block properties.
       * @param {Object} handlers    Block event handlers.
       * @param {Object} sizeOptions Size selector options.
       *
       * @return {Object}  Button styles JSX code.
       */
      getButtonStyles: function getButtonStyles(props, handlers, sizeOptions) {
        return /*#__PURE__*/React.createElement(PanelBody, {
          className: app.getPanelClass(props),
          title: strings.button_styles
        }, /*#__PURE__*/React.createElement(Flex, {
          gap: 4,
          align: "flex-start",
          className: 'wpforms-gutenberg-form-selector-flex',
          justify: "space-between"
        }, /*#__PURE__*/React.createElement(FlexBlock, null, /*#__PURE__*/React.createElement(SelectControl, {
          label: strings.size,
          value: props.attributes.buttonSize,
          options: sizeOptions,
          onChange: function onChange(value) {
            return handlers.styleAttrChange('buttonSize', value);
          }
        })), /*#__PURE__*/React.createElement(FlexBlock, null, /*#__PURE__*/React.createElement(__experimentalUnitControl, {
          onChange: function onChange(value) {
            return handlers.styleAttrChange('buttonBorderRadius', value);
          },
          label: strings.border_radius,
          isUnitSelectTabbable: true,
          value: props.attributes.buttonBorderRadius
        }))), /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-color-picker"
        }, /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-control-label"
        }, strings.colors), /*#__PURE__*/React.createElement(PanelColorSettings, {
          __experimentalIsRenderedInSidebar: true,
          enableAlpha: true,
          showTitle: false,
          className: "wpforms-gutenberg-form-selector-color-panel",
          colorSettings: [{
            value: props.attributes.buttonBackgroundColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('buttonBackgroundColor', value);
            },
            label: strings.background
          }, {
            value: props.attributes.buttonTextColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('buttonTextColor', value);
            },
            label: strings.text
          }]
        }), /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-legend wpforms-button-color-notice"
        }, strings.button_color_notice)));
      },
      /**
       * Get style settings JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} props       Block properties.
       * @param {Object} handlers    Block event handlers.
       * @param {Object} sizeOptions Size selector options.
       *
       * @return {Object} Inspector controls JSX code.
       */
      getStyleSettings: function getStyleSettings(props, handlers, sizeOptions) {
        return /*#__PURE__*/React.createElement(InspectorControls, {
          key: "wpforms-gutenberg-form-selector-style-settings"
        }, app.jsxParts.getFieldStyles(props, handlers, sizeOptions), app.jsxParts.getLabelStyles(props, handlers, sizeOptions), app.jsxParts.getButtonStyles(props, handlers, sizeOptions));
      },
      /**
       * Get advanced settings JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} props    Block properties.
       * @param {Object} handlers Block event handlers.
       *
       * @return {Object} Inspector advanced controls JSX code.
       */
      getAdvancedSettings: function getAdvancedSettings(props, handlers) {
        // eslint-disable-next-line react-hooks/rules-of-hooks
        var _useState = useState(false),
          _useState2 = _slicedToArray(_useState, 2),
          isOpen = _useState2[0],
          setOpen = _useState2[1];
        var openModal = function openModal() {
          return setOpen(true);
        };
        var closeModal = function closeModal() {
          return setOpen(false);
        };
        return /*#__PURE__*/React.createElement(InspectorAdvancedControls, null, /*#__PURE__*/React.createElement("div", {
          className: app.getPanelClass(props)
        }, /*#__PURE__*/React.createElement(TextareaControl, {
          label: strings.copy_paste_settings,
          rows: "4",
          spellCheck: "false",
          value: props.attributes.copyPasteJsonValue,
          onChange: function onChange(value) {
            return handlers.pasteSettings(value);
          }
        }), /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-legend",
          dangerouslySetInnerHTML: {
            __html: strings.copy_paste_notice
          }
        }), /*#__PURE__*/React.createElement(Button, {
          className: "wpforms-gutenberg-form-selector-reset-button",
          onClick: openModal
        }, strings.reset_style_settings)), isOpen && /*#__PURE__*/React.createElement(Modal, {
          className: "wpforms-gutenberg-modal",
          title: strings.reset_style_settings,
          onRequestClose: closeModal
        }, /*#__PURE__*/React.createElement("p", null, strings.reset_settings_confirm_text), /*#__PURE__*/React.createElement(Flex, {
          gap: 3,
          align: "center",
          justify: "flex-end"
        }, /*#__PURE__*/React.createElement(Button, {
          isSecondary: true,
          onClick: closeModal
        }, strings.btn_no), /*#__PURE__*/React.createElement(Button, {
          isPrimary: true,
          onClick: function onClick() {
            closeModal();
            handlers.resetSettings();
          }
        }, strings.btn_yes_reset))));
      },
      /**
       * Get block content JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} props Block properties.
       *
       * @return {JSX.Element} Block content JSX code.
       */
      getBlockFormContent: function getBlockFormContent(props) {
        if (triggerServerRender) {
          return /*#__PURE__*/React.createElement(ServerSideRender, {
            key: "wpforms-gutenberg-form-selector-server-side-renderer",
            block: "wpforms/form-selector",
            attributes: props.attributes
          });
        }
        var clientId = props.clientId;
        var block = app.getBlockContainer(props);

        // In the case of empty content, use server side renderer.
        // This happens when the block is duplicated or converted to a reusable block.
        if (!block || !block.innerHTML) {
          triggerServerRender = true;
          return app.jsxParts.getBlockFormContent(props);
        }
        blocks[clientId] = blocks[clientId] || {};
        blocks[clientId].blockHTML = block.innerHTML;
        blocks[clientId].loadedFormId = props.attributes.formId;
        return /*#__PURE__*/React.createElement(Fragment, {
          key: "wpforms-gutenberg-form-selector-fragment-form-html"
        }, /*#__PURE__*/React.createElement("div", {
          dangerouslySetInnerHTML: {
            __html: blocks[clientId].blockHTML
          }
        }));
      },
      /**
       * Get block preview JSX code.
       *
       * @since 1.8.1
       *
       * @return {JSX.Element} Block preview JSX code.
       */
      getBlockPreview: function getBlockPreview() {
        return /*#__PURE__*/React.createElement(Fragment, {
          key: "wpforms-gutenberg-form-selector-fragment-block-preview"
        }, /*#__PURE__*/React.createElement("img", {
          src: wpforms_gutenberg_form_selector.block_preview_url,
          style: {
            width: '100%'
          },
          alt: ""
        }));
      },
      /**
       * Get block empty JSX code.
       *
       * @since 1.8.3
       *
       * @param {Object} props Block properties.
       * @return {JSX.Element} Block empty JSX code.
       */
      getEmptyFormsPreview: function getEmptyFormsPreview(props) {
        var clientId = props.clientId;
        return /*#__PURE__*/React.createElement(Fragment, {
          key: "wpforms-gutenberg-form-selector-fragment-block-empty"
        }, /*#__PURE__*/React.createElement("div", {
          className: "wpforms-no-form-preview"
        }, /*#__PURE__*/React.createElement("img", {
          src: wpforms_gutenberg_form_selector.block_empty_url,
          alt: ""
        }), /*#__PURE__*/React.createElement("p", null, createInterpolateElement(__('You can use <b>WPForms</b> to build contact forms, surveys, payment forms, and more with just a few clicks.', 'wpforms-lite'), {
          b: /*#__PURE__*/React.createElement("strong", null)
        })), /*#__PURE__*/React.createElement("button", {
          type: "button",
          className: "get-started-button components-button is-primary",
          onClick: function onClick() {
            app.openBuilderPopup(clientId);
          }
        }, __('Get Started', 'wpforms-lite')), /*#__PURE__*/React.createElement("p", {
          className: "empty-desc"
        }, createInterpolateElement(__('Need some help? Check out our <a>comprehensive guide.</a>', 'wpforms-lite'), {
          // eslint-disable-next-line jsx-a11y/anchor-has-content
          a: /*#__PURE__*/React.createElement("a", {
            href: wpforms_gutenberg_form_selector.wpforms_guide,
            target: "_blank",
            rel: "noopener noreferrer"
          })
        })), /*#__PURE__*/React.createElement("div", {
          id: "wpforms-gutenberg-popup",
          className: "wpforms-builder-popup"
        }, /*#__PURE__*/React.createElement("iframe", {
          src: "about:blank",
          width: "100%",
          height: "100%",
          id: "wpforms-builder-iframe",
          title: "WPForms Builder Popup"
        }))));
      },
      /**
       * Get block placeholder (form selector) JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} attributes  Block attributes.
       * @param {Object} handlers    Block event handlers.
       * @param {Object} formOptions Form selector options.
       *
       * @return {JSX.Element} Block placeholder JSX code.
       */
      getBlockPlaceholder: function getBlockPlaceholder(attributes, handlers, formOptions) {
        return /*#__PURE__*/React.createElement(Placeholder, {
          key: "wpforms-gutenberg-form-selector-wrap",
          className: "wpforms-gutenberg-form-selector-wrap"
        }, /*#__PURE__*/React.createElement("img", {
          src: wpforms_gutenberg_form_selector.logo_url,
          alt: ""
        }), /*#__PURE__*/React.createElement("h3", null, strings.title), /*#__PURE__*/React.createElement(SelectControl, {
          key: "wpforms-gutenberg-form-selector-select-control",
          value: attributes.formId,
          options: formOptions,
          onChange: function onChange(value) {
            return handlers.attrChange('formId', value);
          }
        }));
      }
    },
    /**
     * Get Style Settings panel class.
     *
     * @since 1.8.1
     *
     * @param {Object} props Block properties.
     *
     * @return {string} Style Settings panel class.
     */
    getPanelClass: function getPanelClass(props) {
      var cssClass = 'wpforms-gutenberg-panel wpforms-block-settings-' + props.clientId;
      if (!app.isFullStylingEnabled()) {
        cssClass += ' disabled_panel';
      }
      return cssClass;
    },
    /**
     * Determine whether the full styling is enabled.
     *
     * @since 1.8.1
     *
     * @return {boolean} Whether the full styling is enabled.
     */
    isFullStylingEnabled: function isFullStylingEnabled() {
      return wpforms_gutenberg_form_selector.is_modern_markup && wpforms_gutenberg_form_selector.is_full_styling;
    },
    /**
     * Get block container DOM element.
     *
     * @since 1.8.1
     *
     * @param {Object} props Block properties.
     *
     * @return {Element} Block container.
     */
    getBlockContainer: function getBlockContainer(props) {
      var blockSelector = "#block-".concat(props.clientId, " > div");
      var block = document.querySelector(blockSelector);

      // For FSE / Gutenberg plugin we need to take a look inside the iframe.
      if (!block) {
        var editorCanvas = document.querySelector('iframe[name="editor-canvas"]');
        block = editorCanvas && editorCanvas.contentWindow.document.querySelector(blockSelector);
      }
      return block;
    },
    /**
     * Get settings fields event handlers.
     *
     * @since 1.8.1
     *
     * @param {Object} props Block properties.
     *
     * @return {Object} Object that contains event handlers for the settings fields.
     */
    getSettingsFieldsHandlers: function getSettingsFieldsHandlers(props) {
      // eslint-disable-line max-lines-per-function
      return {
        /**
         * Field style attribute change event handler.
         *
         * @since 1.8.1
         *
         * @param {string} attribute Attribute name.
         * @param {string} value     New attribute value.
         */
        styleAttrChange: function styleAttrChange(attribute, value) {
          var block = app.getBlockContainer(props),
            container = block.querySelector("#wpforms-".concat(props.attributes.formId)),
            property = attribute.replace(/[A-Z]/g, function (letter) {
              return "-".concat(letter.toLowerCase());
            }),
            setAttr = {};
          if (container) {
            switch (property) {
              case 'field-size':
              case 'label-size':
              case 'button-size':
                for (var key in sizes[property][value]) {
                  container.style.setProperty("--wpforms-".concat(property, "-").concat(key), sizes[property][value][key]);
                }
                break;
              default:
                container.style.setProperty("--wpforms-".concat(property), value);
            }
          }
          setAttr[attribute] = value;
          props.setAttributes(setAttr);
          triggerServerRender = false;
          this.updateCopyPasteContent();
          $(window).trigger('wpformsFormSelectorStyleAttrChange', [block, props, attribute, value]);
        },
        /**
         * Field regular attribute change event handler.
         *
         * @since 1.8.1
         *
         * @param {string} attribute Attribute name.
         * @param {string} value     New attribute value.
         */
        attrChange: function attrChange(attribute, value) {
          var setAttr = {};
          setAttr[attribute] = value;
          props.setAttributes(setAttr);
          triggerServerRender = true;
          this.updateCopyPasteContent();
        },
        /**
         * Reset Form Styles settings to defaults.
         *
         * @since 1.8.1
         */
        resetSettings: function resetSettings() {
          for (var key in defaultStyleSettings) {
            this.styleAttrChange(key, defaultStyleSettings[key]);
          }
        },
        /**
         * Update content of the "Copy/Paste" fields.
         *
         * @since 1.8.1
         */
        updateCopyPasteContent: function updateCopyPasteContent() {
          var content = {};
          var atts = wp.data.select('core/block-editor').getBlockAttributes(props.clientId);
          for (var key in defaultStyleSettings) {
            content[key] = atts[key];
          }
          props.setAttributes({
            copyPasteJsonValue: JSON.stringify(content)
          });
        },
        /**
         * Paste settings handler.
         *
         * @since 1.8.1
         *
         * @param {string} value New attribute value.
         */
        pasteSettings: function pasteSettings(value) {
          var pasteAttributes = app.parseValidateJson(value);
          if (!pasteAttributes) {
            wp.data.dispatch('core/notices').createErrorNotice(strings.copy_paste_error, {
              id: 'wpforms-json-parse-error'
            });
            this.updateCopyPasteContent();
            return;
          }
          pasteAttributes.copyPasteJsonValue = value;
          props.setAttributes(pasteAttributes);
          triggerServerRender = true;
        }
      };
    },
    /**
     * Parse and validate JSON string.
     *
     * @since 1.8.1
     *
     * @param {string} value JSON string.
     *
     * @return {boolean|object} Parsed JSON object OR false on error.
     */
    parseValidateJson: function parseValidateJson(value) {
      if (typeof value !== 'string') {
        return false;
      }
      var atts;
      try {
        atts = JSON.parse(value);
      } catch (error) {
        atts = false;
      }
      return atts;
    },
    /**
     * Get WPForms icon DOM element.
     *
     * @since 1.8.1
     *
     * @return {DOM.element} WPForms icon DOM element.
     */
    getIcon: function getIcon() {
      return createElement('svg', {
        width: 20,
        height: 20,
        viewBox: '0 0 612 612',
        className: 'dashicon'
      }, createElement('path', {
        fill: 'currentColor',
        d: 'M544,0H68C30.445,0,0,30.445,0,68v476c0,37.556,30.445,68,68,68h476c37.556,0,68-30.444,68-68V68 C612,30.445,581.556,0,544,0z M464.44,68L387.6,120.02L323.34,68H464.44z M288.66,68l-64.26,52.02L147.56,68H288.66z M544,544H68 V68h22.1l136,92.14l79.9-64.6l79.56,64.6l136-92.14H544V544z M114.24,263.16h95.88v-48.28h-95.88V263.16z M114.24,360.4h95.88 v-48.62h-95.88V360.4z M242.76,360.4h255v-48.62h-255V360.4L242.76,360.4z M242.76,263.16h255v-48.28h-255V263.16L242.76,263.16z M368.22,457.3h129.54V408H368.22V457.3z'
      }));
    },
    /**
     * Get block attributes.
     *
     * @since 1.8.1
     *
     * @return {Object} Block attributes.
     */
    getBlockAttributes: function getBlockAttributes() {
      // eslint-disable-line max-lines-per-function
      return {
        clientId: {
          type: 'string',
          default: ''
        },
        formId: {
          type: 'string',
          default: defaults.formId
        },
        displayTitle: {
          type: 'boolean',
          default: defaults.displayTitle
        },
        displayDesc: {
          type: 'boolean',
          default: defaults.displayDesc
        },
        preview: {
          type: 'boolean'
        },
        fieldSize: {
          type: 'string',
          default: defaults.fieldSize
        },
        fieldBorderRadius: {
          type: 'string',
          default: defaults.fieldBorderRadius
        },
        fieldBackgroundColor: {
          type: 'string',
          default: defaults.fieldBackgroundColor
        },
        fieldBorderColor: {
          type: 'string',
          default: defaults.fieldBorderColor
        },
        fieldTextColor: {
          type: 'string',
          default: defaults.fieldTextColor
        },
        labelSize: {
          type: 'string',
          default: defaults.labelSize
        },
        labelColor: {
          type: 'string',
          default: defaults.labelColor
        },
        labelSublabelColor: {
          type: 'string',
          default: defaults.labelSublabelColor
        },
        labelErrorColor: {
          type: 'string',
          default: defaults.labelErrorColor
        },
        buttonSize: {
          type: 'string',
          default: defaults.buttonSize
        },
        buttonBorderRadius: {
          type: 'string',
          default: defaults.buttonBorderRadius
        },
        buttonBackgroundColor: {
          type: 'string',
          default: defaults.buttonBackgroundColor
        },
        buttonTextColor: {
          type: 'string',
          default: defaults.buttonTextColor
        },
        copyPasteJsonValue: {
          type: 'string',
          default: defaults.copyPasteJsonValue
        }
      };
    },
    /**
     * Get form selector options.
     *
     * @since 1.8.1
     *
     * @return {Array} Form options.
     */
    getFormOptions: function getFormOptions() {
      var formOptions = formList.map(function (value) {
        return {
          value: value.ID,
          label: value.post_title
        };
      });
      formOptions.unshift({
        value: '',
        label: strings.form_select
      });
      return formOptions;
    },
    /**
     * Get size selector options.
     *
     * @since 1.8.1
     *
     * @return {Array} Size options.
     */
    getSizeOptions: function getSizeOptions() {
      return [{
        label: strings.small,
        value: 'small'
      }, {
        label: strings.medium,
        value: 'medium'
      }, {
        label: strings.large,
        value: 'large'
      }];
    },
    /**
     * Event `wpformsFormSelectorEdit` handler.
     *
     * @since 1.8.1
     *
     * @param {Object} e     Event object.
     * @param {Object} props Block properties.
     */
    blockEdit: function blockEdit(e, props) {
      var block = app.getBlockContainer(props);
      if (!block || !block.dataset) {
        return;
      }
      app.initLeadFormSettings(block.parentElement);
    },
    /**
     * Init Lead Form Settings panels.
     *
     * @since 1.8.1
     *
     * @param {Element} block Block element.
     */
    initLeadFormSettings: function initLeadFormSettings(block) {
      if (!block || !block.dataset) {
        return;
      }
      if (!app.isFullStylingEnabled()) {
        return;
      }
      var clientId = block.dataset.block;
      var $form = $(block.querySelector('.wpforms-container'));
      var $panel = $(".wpforms-block-settings-".concat(clientId));
      if ($form.hasClass('wpforms-lead-forms-container')) {
        $panel.addClass('disabled_panel').find('.wpforms-gutenberg-panel-notice.wpforms-lead-form-notice').css('display', 'block');
        $panel.find('.wpforms-gutenberg-panel-notice.wpforms-use-modern-notice').css('display', 'none');
        return;
      }
      $panel.removeClass('disabled_panel').find('.wpforms-gutenberg-panel-notice.wpforms-lead-form-notice').css('display', 'none');
      $panel.find('.wpforms-gutenberg-panel-notice.wpforms-use-modern-notice').css('display', null);
    },
    /**
     * Event `wpformsFormSelectorFormLoaded` handler.
     *
     * @since 1.8.1
     *
     * @param {Object} e Event object.
     */
    formLoaded: function formLoaded(e) {
      app.initLeadFormSettings(e.detail.block);
      app.updateAccentColors(e.detail);
      app.loadChoicesJS(e.detail);
      app.initRichTextField(e.detail.formId);
      $(e.detail.block).off('click').on('click', app.blockClick);
    },
    /**
     * Click on the block event handler.
     *
     * @since 1.8.1
     *
     * @param {Object} e Event object.
     */
    blockClick: function blockClick(e) {
      app.initLeadFormSettings(e.currentTarget);
    },
    /**
     * Update accent colors of some fields in GB block in Modern Markup mode.
     *
     * @since 1.8.1
     *
     * @param {Object} detail Event details object.
     */
    updateAccentColors: function updateAccentColors(detail) {
      if (!wpforms_gutenberg_form_selector.is_modern_markup || !window.WPForms || !window.WPForms.FrontendModern || !detail.block) {
        return;
      }
      var $form = $(detail.block.querySelector("#wpforms-".concat(detail.formId))),
        FrontendModern = window.WPForms.FrontendModern;
      FrontendModern.updateGBBlockPageIndicatorColor($form);
      FrontendModern.updateGBBlockIconChoicesColor($form);
      FrontendModern.updateGBBlockRatingColor($form);
    },
    /**
     * Init Modern style Dropdown fields (<select>).
     *
     * @since 1.8.1
     *
     * @param {Object} detail Event details object.
     */
    loadChoicesJS: function loadChoicesJS(detail) {
      if (typeof window.Choices !== 'function') {
        return;
      }
      var $form = $(detail.block.querySelector("#wpforms-".concat(detail.formId)));
      $form.find('.choicesjs-select').each(function (idx, el) {
        var $el = $(el);
        if ($el.data('choice') === 'active') {
          return;
        }
        var args = window.wpforms_choicesjs_config || {},
          searchEnabled = $el.data('search-enabled'),
          $field = $el.closest('.wpforms-field');
        args.searchEnabled = 'undefined' !== typeof searchEnabled ? searchEnabled : true;
        args.callbackOnInit = function () {
          var self = this,
            $element = $(self.passedElement.element),
            $input = $(self.input.element),
            sizeClass = $element.data('size-class');

          // Add CSS-class for size.
          if (sizeClass) {
            $(self.containerOuter.element).addClass(sizeClass);
          }

          /**
           * If a multiple select has selected choices - hide a placeholder text.
           * In case if select is empty - we return placeholder text back.
           */
          if ($element.prop('multiple')) {
            // On init event.
            $input.data('placeholder', $input.attr('placeholder'));
            if (self.getValue(true).length) {
              $input.removeAttr('placeholder');
            }
          }
          this.disable();
          $field.find('.is-disabled').removeClass('is-disabled');
        };
        try {
          var choicesInstance = new Choices(el, args);

          // Save Choices.js instance for future access.
          $el.data('choicesjs', choicesInstance);
        } catch (e) {} // eslint-disable-line no-empty
      });
    },
    /**
     * Initialize RichText field.
     *
     * @since 1.8.1
     *
     * @param {number} formId Form ID.
     */
    initRichTextField: function initRichTextField(formId) {
      // Set default tab to `Visual`.
      $("#wpforms-".concat(formId, " .wp-editor-wrap")).removeClass('html-active').addClass('tmce-active');
    }
  };

  // Provide access to public functions/properties.
  return app;
}(document, window, jQuery);

// Initialize.
WPForms.FormSelector.init();
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJfcmVnZW5lcmF0b3JSdW50aW1lIiwiZSIsInQiLCJyIiwiT2JqZWN0IiwicHJvdG90eXBlIiwibiIsImhhc093blByb3BlcnR5IiwibyIsImRlZmluZVByb3BlcnR5IiwidmFsdWUiLCJpIiwiU3ltYm9sIiwiYSIsIml0ZXJhdG9yIiwiYyIsImFzeW5jSXRlcmF0b3IiLCJ1IiwidG9TdHJpbmdUYWciLCJkZWZpbmUiLCJlbnVtZXJhYmxlIiwiY29uZmlndXJhYmxlIiwid3JpdGFibGUiLCJ3cmFwIiwiR2VuZXJhdG9yIiwiY3JlYXRlIiwiQ29udGV4dCIsIm1ha2VJbnZva2VNZXRob2QiLCJ0cnlDYXRjaCIsInR5cGUiLCJhcmciLCJjYWxsIiwiaCIsImwiLCJmIiwicyIsInkiLCJHZW5lcmF0b3JGdW5jdGlvbiIsIkdlbmVyYXRvckZ1bmN0aW9uUHJvdG90eXBlIiwicCIsImQiLCJnZXRQcm90b3R5cGVPZiIsInYiLCJ2YWx1ZXMiLCJnIiwiZGVmaW5lSXRlcmF0b3JNZXRob2RzIiwiZm9yRWFjaCIsIl9pbnZva2UiLCJBc3luY0l0ZXJhdG9yIiwiaW52b2tlIiwiX3R5cGVvZiIsInJlc29sdmUiLCJfX2F3YWl0IiwidGhlbiIsImNhbGxJbnZva2VXaXRoTWV0aG9kQW5kQXJnIiwiRXJyb3IiLCJkb25lIiwibWV0aG9kIiwiZGVsZWdhdGUiLCJtYXliZUludm9rZURlbGVnYXRlIiwic2VudCIsIl9zZW50IiwiZGlzcGF0Y2hFeGNlcHRpb24iLCJhYnJ1cHQiLCJyZXR1cm4iLCJUeXBlRXJyb3IiLCJyZXN1bHROYW1lIiwibmV4dCIsIm5leHRMb2MiLCJwdXNoVHJ5RW50cnkiLCJ0cnlMb2MiLCJjYXRjaExvYyIsImZpbmFsbHlMb2MiLCJhZnRlckxvYyIsInRyeUVudHJpZXMiLCJwdXNoIiwicmVzZXRUcnlFbnRyeSIsImNvbXBsZXRpb24iLCJyZXNldCIsImlzTmFOIiwibGVuZ3RoIiwiZGlzcGxheU5hbWUiLCJpc0dlbmVyYXRvckZ1bmN0aW9uIiwiY29uc3RydWN0b3IiLCJuYW1lIiwibWFyayIsInNldFByb3RvdHlwZU9mIiwiX19wcm90b19fIiwiYXdyYXAiLCJhc3luYyIsIlByb21pc2UiLCJrZXlzIiwicmV2ZXJzZSIsInBvcCIsInByZXYiLCJjaGFyQXQiLCJzbGljZSIsInN0b3AiLCJydmFsIiwiaGFuZGxlIiwiY29tcGxldGUiLCJmaW5pc2giLCJjYXRjaCIsIl9jYXRjaCIsImRlbGVnYXRlWWllbGQiLCJhc3luY0dlbmVyYXRvclN0ZXAiLCJnZW4iLCJyZWplY3QiLCJfbmV4dCIsIl90aHJvdyIsImtleSIsImluZm8iLCJlcnJvciIsIl9hc3luY1RvR2VuZXJhdG9yIiwiZm4iLCJzZWxmIiwiYXJncyIsImFyZ3VtZW50cyIsImFwcGx5IiwiZXJyIiwidW5kZWZpbmVkIiwiV1BGb3JtcyIsIndpbmRvdyIsIkZvcm1TZWxlY3RvciIsImRvY3VtZW50IiwiJCIsIl93cCIsIndwIiwiX3dwJHNlcnZlclNpZGVSZW5kZXIiLCJzZXJ2ZXJTaWRlUmVuZGVyIiwiU2VydmVyU2lkZVJlbmRlciIsImNvbXBvbmVudHMiLCJfd3AkZWxlbWVudCIsImVsZW1lbnQiLCJjcmVhdGVFbGVtZW50IiwiRnJhZ21lbnQiLCJ1c2VTdGF0ZSIsImNyZWF0ZUludGVycG9sYXRlRWxlbWVudCIsInJlZ2lzdGVyQmxvY2tUeXBlIiwiYmxvY2tzIiwiX3JlZiIsImJsb2NrRWRpdG9yIiwiZWRpdG9yIiwiSW5zcGVjdG9yQ29udHJvbHMiLCJJbnNwZWN0b3JBZHZhbmNlZENvbnRyb2xzIiwiUGFuZWxDb2xvclNldHRpbmdzIiwiX3dwJGNvbXBvbmVudHMiLCJTZWxlY3RDb250cm9sIiwiVG9nZ2xlQ29udHJvbCIsIlBhbmVsQm9keSIsIlBsYWNlaG9sZGVyIiwiRmxleCIsIkZsZXhCbG9jayIsIl9fZXhwZXJpbWVudGFsVW5pdENvbnRyb2wiLCJUZXh0YXJlYUNvbnRyb2wiLCJCdXR0b24iLCJNb2RhbCIsIl93cGZvcm1zX2d1dGVuYmVyZ19mbyIsIndwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3IiLCJzdHJpbmdzIiwiZGVmYXVsdHMiLCJzaXplcyIsImRlZmF1bHRTdHlsZVNldHRpbmdzIiwiX18iLCJpMThuIiwiZm9ybUxpc3QiLCJmb3JtcyIsInRyaWdnZXJTZXJ2ZXJSZW5kZXIiLCIkcG9wdXAiLCJpc0ZldGNoaW5nIiwiYXBwIiwiaW5pdCIsImluaXREZWZhdWx0cyIsInJlZ2lzdGVyQmxvY2siLCJyZWFkeSIsImV2ZW50cyIsIm9uIiwiXyIsImRlYm91bmNlIiwiYmxvY2tFZGl0IiwiZm9ybUxvYWRlZCIsImdldEZvcm1zIiwiX2NhbGxlZSIsInJlc3BvbnNlIiwiX2NhbGxlZSQiLCJfY29udGV4dCIsImFwaUZldGNoIiwicGF0aCIsImNhY2hlIiwidDAiLCJjb25zb2xlIiwib3BlbkJ1aWxkZXJQb3B1cCIsImNsaWVudElEIiwiaXNFbXB0eU9iamVjdCIsInRtcGwiLCJwYXJlbnQiLCJhZnRlciIsInNpYmxpbmdzIiwidXJsIiwiZ2V0X3N0YXJ0ZWRfdXJsIiwiJGlmcmFtZSIsImZpbmQiLCJidWlsZGVyQ2xvc2VCdXR0b25FdmVudCIsImF0dHIiLCJmYWRlSW4iLCJvZmYiLCJhY3Rpb24iLCJmb3JtSWQiLCJmb3JtVGl0bGUiLCJuZXdCbG9jayIsImNyZWF0ZUJsb2NrIiwidG9TdHJpbmciLCJJRCIsInBvc3RfdGl0bGUiLCJkYXRhIiwiZGlzcGF0Y2giLCJyZW1vdmVCbG9jayIsImluc2VydEJsb2NrcyIsInRpdGxlIiwiZGVzY3JpcHRpb24iLCJpY29uIiwiZ2V0SWNvbiIsImtleXdvcmRzIiwiZm9ybV9rZXl3b3JkcyIsImNhdGVnb3J5IiwiYXR0cmlidXRlcyIsImdldEJsb2NrQXR0cmlidXRlcyIsInN1cHBvcnRzIiwiY3VzdG9tQ2xhc3NOYW1lIiwiaGFzRm9ybXMiLCJleGFtcGxlIiwicHJldmlldyIsImVkaXQiLCJwcm9wcyIsImZvcm1PcHRpb25zIiwiZ2V0Rm9ybU9wdGlvbnMiLCJoYW5kbGVycyIsImdldFNldHRpbmdzRmllbGRzSGFuZGxlcnMiLCJjbGllbnRJZCIsInNldEF0dHJpYnV0ZXMiLCJqc3giLCJqc3hQYXJ0cyIsImdldE1haW5TZXR0aW5ncyIsImdldEVtcHR5Rm9ybXNQcmV2aWV3Iiwic2l6ZU9wdGlvbnMiLCJnZXRTaXplT3B0aW9ucyIsImdldFN0eWxlU2V0dGluZ3MiLCJnZXRBZHZhbmNlZFNldHRpbmdzIiwiZ2V0QmxvY2tGb3JtQ29udGVudCIsInVwZGF0ZUNvcHlQYXN0ZUNvbnRlbnQiLCJ0cmlnZ2VyIiwiZ2V0QmxvY2tQcmV2aWV3IiwiZ2V0QmxvY2tQbGFjZWhvbGRlciIsInNhdmUiLCJwcmludEVtcHR5Rm9ybXNOb3RpY2UiLCJSZWFjdCIsImNsYXNzTmFtZSIsImZvcm1fc2V0dGluZ3MiLCJsYWJlbCIsImZvcm1fc2VsZWN0ZWQiLCJvcHRpb25zIiwib25DaGFuZ2UiLCJhdHRyQ2hhbmdlIiwic2hvd190aXRsZSIsImNoZWNrZWQiLCJkaXNwbGF5VGl0bGUiLCJzaG93X2Rlc2NyaXB0aW9uIiwiZGlzcGxheURlc2MiLCJwYW5lbF9ub3RpY2VfaGVhZCIsInBhbmVsX25vdGljZV90ZXh0IiwiaHJlZiIsInBhbmVsX25vdGljZV9saW5rIiwicmVsIiwidGFyZ2V0IiwicGFuZWxfbm90aWNlX2xpbmtfdGV4dCIsInN0eWxlIiwiZGlzcGxheSIsIm9uQ2xpY2siLCJnZXRGaWVsZFN0eWxlcyIsImdldFBhbmVsQ2xhc3MiLCJmaWVsZF9zdHlsZXMiLCJ1c2VfbW9kZXJuX25vdGljZV9oZWFkIiwidXNlX21vZGVybl9ub3RpY2VfdGV4dCIsInVzZV9tb2Rlcm5fbm90aWNlX2xpbmsiLCJsZWFybl9tb3JlIiwibGVhZF9mb3Jtc19wYW5lbF9ub3RpY2VfaGVhZCIsImxlYWRfZm9ybXNfcGFuZWxfbm90aWNlX3RleHQiLCJnYXAiLCJhbGlnbiIsImp1c3RpZnkiLCJzaXplIiwiZmllbGRTaXplIiwic3R5bGVBdHRyQ2hhbmdlIiwiYm9yZGVyX3JhZGl1cyIsImZpZWxkQm9yZGVyUmFkaXVzIiwiaXNVbml0U2VsZWN0VGFiYmFibGUiLCJjb2xvcnMiLCJfX2V4cGVyaW1lbnRhbElzUmVuZGVyZWRJblNpZGViYXIiLCJlbmFibGVBbHBoYSIsInNob3dUaXRsZSIsImNvbG9yU2V0dGluZ3MiLCJmaWVsZEJhY2tncm91bmRDb2xvciIsImJhY2tncm91bmQiLCJmaWVsZEJvcmRlckNvbG9yIiwiYm9yZGVyIiwiZmllbGRUZXh0Q29sb3IiLCJ0ZXh0IiwiZ2V0TGFiZWxTdHlsZXMiLCJsYWJlbF9zdHlsZXMiLCJsYWJlbFNpemUiLCJsYWJlbENvbG9yIiwibGFiZWxTdWJsYWJlbENvbG9yIiwic3VibGFiZWxfaGludHMiLCJyZXBsYWNlIiwibGFiZWxFcnJvckNvbG9yIiwiZXJyb3JfbWVzc2FnZSIsImdldEJ1dHRvblN0eWxlcyIsImJ1dHRvbl9zdHlsZXMiLCJidXR0b25TaXplIiwiYnV0dG9uQm9yZGVyUmFkaXVzIiwiYnV0dG9uQmFja2dyb3VuZENvbG9yIiwiYnV0dG9uVGV4dENvbG9yIiwiYnV0dG9uX2NvbG9yX25vdGljZSIsIl91c2VTdGF0ZSIsIl91c2VTdGF0ZTIiLCJfc2xpY2VkVG9BcnJheSIsImlzT3BlbiIsInNldE9wZW4iLCJvcGVuTW9kYWwiLCJjbG9zZU1vZGFsIiwiY29weV9wYXN0ZV9zZXR0aW5ncyIsInJvd3MiLCJzcGVsbENoZWNrIiwiY29weVBhc3RlSnNvblZhbHVlIiwicGFzdGVTZXR0aW5ncyIsImRhbmdlcm91c2x5U2V0SW5uZXJIVE1MIiwiX19odG1sIiwiY29weV9wYXN0ZV9ub3RpY2UiLCJyZXNldF9zdHlsZV9zZXR0aW5ncyIsIm9uUmVxdWVzdENsb3NlIiwicmVzZXRfc2V0dGluZ3NfY29uZmlybV90ZXh0IiwiaXNTZWNvbmRhcnkiLCJidG5fbm8iLCJpc1ByaW1hcnkiLCJyZXNldFNldHRpbmdzIiwiYnRuX3llc19yZXNldCIsImJsb2NrIiwiZ2V0QmxvY2tDb250YWluZXIiLCJpbm5lckhUTUwiLCJibG9ja0hUTUwiLCJsb2FkZWRGb3JtSWQiLCJzcmMiLCJibG9ja19wcmV2aWV3X3VybCIsIndpZHRoIiwiYWx0IiwiYmxvY2tfZW1wdHlfdXJsIiwiYiIsIndwZm9ybXNfZ3VpZGUiLCJpZCIsImhlaWdodCIsImxvZ29fdXJsIiwiY3NzQ2xhc3MiLCJpc0Z1bGxTdHlsaW5nRW5hYmxlZCIsImlzX21vZGVybl9tYXJrdXAiLCJpc19mdWxsX3N0eWxpbmciLCJibG9ja1NlbGVjdG9yIiwiY29uY2F0IiwicXVlcnlTZWxlY3RvciIsImVkaXRvckNhbnZhcyIsImNvbnRlbnRXaW5kb3ciLCJhdHRyaWJ1dGUiLCJjb250YWluZXIiLCJwcm9wZXJ0eSIsImxldHRlciIsInRvTG93ZXJDYXNlIiwic2V0QXR0ciIsInNldFByb3BlcnR5IiwiY29udGVudCIsImF0dHMiLCJzZWxlY3QiLCJKU09OIiwic3RyaW5naWZ5IiwicGFzdGVBdHRyaWJ1dGVzIiwicGFyc2VWYWxpZGF0ZUpzb24iLCJjcmVhdGVFcnJvck5vdGljZSIsImNvcHlfcGFzdGVfZXJyb3IiLCJwYXJzZSIsInZpZXdCb3giLCJmaWxsIiwiZGVmYXVsdCIsIm1hcCIsInVuc2hpZnQiLCJmb3JtX3NlbGVjdCIsInNtYWxsIiwibWVkaXVtIiwibGFyZ2UiLCJkYXRhc2V0IiwiaW5pdExlYWRGb3JtU2V0dGluZ3MiLCJwYXJlbnRFbGVtZW50IiwiJGZvcm0iLCIkcGFuZWwiLCJoYXNDbGFzcyIsImFkZENsYXNzIiwiY3NzIiwicmVtb3ZlQ2xhc3MiLCJkZXRhaWwiLCJ1cGRhdGVBY2NlbnRDb2xvcnMiLCJsb2FkQ2hvaWNlc0pTIiwiaW5pdFJpY2hUZXh0RmllbGQiLCJibG9ja0NsaWNrIiwiY3VycmVudFRhcmdldCIsIkZyb250ZW5kTW9kZXJuIiwidXBkYXRlR0JCbG9ja1BhZ2VJbmRpY2F0b3JDb2xvciIsInVwZGF0ZUdCQmxvY2tJY29uQ2hvaWNlc0NvbG9yIiwidXBkYXRlR0JCbG9ja1JhdGluZ0NvbG9yIiwiQ2hvaWNlcyIsImVhY2giLCJpZHgiLCJlbCIsIiRlbCIsIndwZm9ybXNfY2hvaWNlc2pzX2NvbmZpZyIsInNlYXJjaEVuYWJsZWQiLCIkZmllbGQiLCJjbG9zZXN0IiwiY2FsbGJhY2tPbkluaXQiLCIkZWxlbWVudCIsInBhc3NlZEVsZW1lbnQiLCIkaW5wdXQiLCJpbnB1dCIsInNpemVDbGFzcyIsImNvbnRhaW5lck91dGVyIiwicHJvcCIsImdldFZhbHVlIiwicmVtb3ZlQXR0ciIsImRpc2FibGUiLCJjaG9pY2VzSW5zdGFuY2UiLCJqUXVlcnkiXSwic291cmNlcyI6WyJmYWtlXzk0NDEwYzguanMiXSwic291cmNlc0NvbnRlbnQiOlsiLyogZ2xvYmFsIHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3IsIENob2ljZXMsIEpTWCwgRE9NICovXG4vKiBqc2hpbnQgZXMzOiBmYWxzZSwgZXN2ZXJzaW9uOiA2ICovXG5cbi8qKlxuICogR3V0ZW5iZXJnIGVkaXRvciBibG9jay5cbiAqXG4gKiBAc2luY2UgMS44LjFcbiAqL1xuY29uc3QgV1BGb3JtcyA9IHdpbmRvdy5XUEZvcm1zIHx8IHt9O1xuXG5XUEZvcm1zLkZvcm1TZWxlY3RvciA9IFdQRm9ybXMuRm9ybVNlbGVjdG9yIHx8ICggZnVuY3Rpb24oIGRvY3VtZW50LCB3aW5kb3csICQgKSB7XG5cdGNvbnN0IHsgc2VydmVyU2lkZVJlbmRlcjogU2VydmVyU2lkZVJlbmRlciA9IHdwLmNvbXBvbmVudHMuU2VydmVyU2lkZVJlbmRlciB9ID0gd3A7XG5cdGNvbnN0IHsgY3JlYXRlRWxlbWVudCwgRnJhZ21lbnQsIHVzZVN0YXRlLCBjcmVhdGVJbnRlcnBvbGF0ZUVsZW1lbnQgfSA9IHdwLmVsZW1lbnQ7XG5cdGNvbnN0IHsgcmVnaXN0ZXJCbG9ja1R5cGUgfSA9IHdwLmJsb2Nrcztcblx0Y29uc3QgeyBJbnNwZWN0b3JDb250cm9scywgSW5zcGVjdG9yQWR2YW5jZWRDb250cm9scywgUGFuZWxDb2xvclNldHRpbmdzIH0gPSB3cC5ibG9ja0VkaXRvciB8fCB3cC5lZGl0b3I7XG5cdGNvbnN0IHsgU2VsZWN0Q29udHJvbCwgVG9nZ2xlQ29udHJvbCwgUGFuZWxCb2R5LCBQbGFjZWhvbGRlciwgRmxleCwgRmxleEJsb2NrLCBfX2V4cGVyaW1lbnRhbFVuaXRDb250cm9sLCBUZXh0YXJlYUNvbnRyb2wsIEJ1dHRvbiwgTW9kYWwgfSA9IHdwLmNvbXBvbmVudHM7XG5cdGNvbnN0IHsgc3RyaW5ncywgZGVmYXVsdHMsIHNpemVzIH0gPSB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yO1xuXHRjb25zdCBkZWZhdWx0U3R5bGVTZXR0aW5ncyA9IGRlZmF1bHRzO1xuXHRjb25zdCB7IF9fIH0gPSB3cC5pMThuO1xuXG5cdC8qKlxuXHQgKiBMaXN0IG9mIGZvcm1zLlxuXHQgKlxuXHQgKiBEZWZhdWx0IHZhbHVlIGlzIGxvY2FsaXplZCBpbiBGb3JtU2VsZWN0b3IucGhwLlxuXHQgKlxuXHQgKiBAc2luY2Uge1ZFUlNJT059XG5cdCAqXG5cdCAqIEB0eXBlIHtPYmplY3R9XG5cdCAqL1xuXHRsZXQgZm9ybUxpc3QgPSB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLmZvcm1zO1xuXG5cdC8qKlxuXHQgKiBCbG9ja3MgcnVudGltZSBkYXRhLlxuXHQgKlxuXHQgKiBAc2luY2UgMS44LjFcblx0ICpcblx0ICogQHR5cGUge09iamVjdH1cblx0ICovXG5cdGNvbnN0IGJsb2NrcyA9IHt9O1xuXG5cdC8qKlxuXHQgKiBXaGV0aGVyIGl0IGlzIG5lZWRlZCB0byB0cmlnZ2VyIHNlcnZlciByZW5kZXJpbmcuXG5cdCAqXG5cdCAqIEBzaW5jZSAxLjguMVxuXHQgKlxuXHQgKiBAdHlwZSB7Ym9vbGVhbn1cblx0ICovXG5cdGxldCB0cmlnZ2VyU2VydmVyUmVuZGVyID0gdHJ1ZTtcblxuXHQvKipcblx0ICogUG9wdXAgY29udGFpbmVyLlxuXHQgKlxuXHQgKiBAc2luY2UgMS44LjNcblx0ICpcblx0ICogQHR5cGUge09iamVjdH1cblx0ICovXG5cdGxldCAkcG9wdXAgPSB7fTtcblxuXHQvKipcblx0ICogVHJhY2sgZmV0Y2ggc3RhdHVzLlxuXHQgKlxuXHQgKiBAc2luY2Uge1ZFUlNJT059XG5cdCAqXG5cdCAqIEB0eXBlIHtib29sZWFufVxuXHQgKi9cblx0bGV0IGlzRmV0Y2hpbmcgPSBmYWxzZTtcblxuXHQvKipcblx0ICogUHVibGljIGZ1bmN0aW9ucyBhbmQgcHJvcGVydGllcy5cblx0ICpcblx0ICogQHNpbmNlIDEuOC4xXG5cdCAqXG5cdCAqIEB0eXBlIHtPYmplY3R9XG5cdCAqL1xuXHRjb25zdCBhcHAgPSB7XG5cblx0XHQvKipcblx0XHQgKiBTdGFydCB0aGUgZW5naW5lLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICovXG5cdFx0aW5pdCgpIHtcblx0XHRcdGFwcC5pbml0RGVmYXVsdHMoKTtcblx0XHRcdGFwcC5yZWdpc3RlckJsb2NrKCk7XG5cblx0XHRcdCQoIGFwcC5yZWFkeSApO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBEb2N1bWVudCByZWFkeS5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqL1xuXHRcdHJlYWR5KCkge1xuXHRcdFx0YXBwLmV2ZW50cygpO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBFdmVudHMuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKi9cblx0XHRldmVudHMoKSB7XG5cdFx0XHQkKCB3aW5kb3cgKVxuXHRcdFx0XHQub24oICd3cGZvcm1zRm9ybVNlbGVjdG9yRWRpdCcsIF8uZGVib3VuY2UoIGFwcC5ibG9ja0VkaXQsIDI1MCApIClcblx0XHRcdFx0Lm9uKCAnd3Bmb3Jtc0Zvcm1TZWxlY3RvckZvcm1Mb2FkZWQnLCBfLmRlYm91bmNlKCBhcHAuZm9ybUxvYWRlZCwgMjUwICkgKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogR2V0IGZyZXNoIGxpc3Qgb2YgZm9ybXMgdmlhIFJFU1QtQVBJLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIHtWRVJTSU9OfVxuXHRcdCAqXG5cdFx0ICogQHNlZSBodHRwczovL2RldmVsb3Blci53b3JkcHJlc3Mub3JnL2Jsb2NrLWVkaXRvci9yZWZlcmVuY2UtZ3VpZGVzL3BhY2thZ2VzL3BhY2thZ2VzLWFwaS1mZXRjaC9cblx0XHQgKi9cblx0XHRhc3luYyBnZXRGb3JtcygpIHtcblx0XHRcdC8vIElmIGEgZmV0Y2ggaXMgYWxyZWFkeSBpbiBwcm9ncmVzcywgZXhpdCB0aGUgZnVuY3Rpb24uXG5cdFx0XHRpZiAoIGlzRmV0Y2hpbmcgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0Ly8gU2V0IHRoZSBmbGFnIHRvIHRydWUgaW5kaWNhdGluZyBhIGZldGNoIGlzIGluIHByb2dyZXNzLlxuXHRcdFx0aXNGZXRjaGluZyA9IHRydWU7XG5cblx0XHRcdHRyeSB7XG5cdFx0XHRcdC8vIEZldGNoIGZvcm1zLlxuXHRcdFx0XHRjb25zdCByZXNwb25zZSA9IGF3YWl0IHdwLmFwaUZldGNoKCB7XG5cdFx0XHRcdFx0cGF0aDogJy93cGZvcm1zL3YxL2Zvcm1zLycsXG5cdFx0XHRcdFx0bWV0aG9kOiAnR0VUJyxcblx0XHRcdFx0XHRjYWNoZTogJ25vLWNhY2hlJyxcblx0XHRcdFx0fSApO1xuXG5cdFx0XHRcdC8vIFVwZGF0ZSB0aGUgZm9ybSBsaXN0LlxuXHRcdFx0XHRmb3JtTGlzdCA9IHJlc3BvbnNlLmZvcm1zO1xuXHRcdFx0fSBjYXRjaCAoIGVycm9yICkge1xuXHRcdFx0XHQvLyBlc2xpbnQtZGlzYWJsZS1uZXh0LWxpbmUgbm8tY29uc29sZVxuXHRcdFx0XHRjb25zb2xlLmVycm9yKCBlcnJvciApO1xuXHRcdFx0fSBmaW5hbGx5IHtcblx0XHRcdFx0aXNGZXRjaGluZyA9IGZhbHNlO1xuXHRcdFx0fVxuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBPcGVuIGJ1aWxkZXIgcG9wdXAuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS42LjJcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7c3RyaW5nfSBjbGllbnRJRCBCbG9jayBDbGllbnQgSUQuXG5cdFx0ICovXG5cdFx0b3BlbkJ1aWxkZXJQb3B1cCggY2xpZW50SUQgKSB7XG5cdFx0XHRpZiAoICQuaXNFbXB0eU9iamVjdCggJHBvcHVwICkgKSB7XG5cdFx0XHRcdGNvbnN0IHRtcGwgPSAkKCAnI3dwZm9ybXMtZ3V0ZW5iZXJnLXBvcHVwJyApO1xuXHRcdFx0XHRjb25zdCBwYXJlbnQgPSAkKCAnI3dwd3JhcCcgKTtcblxuXHRcdFx0XHRwYXJlbnQuYWZ0ZXIoIHRtcGwgKTtcblxuXHRcdFx0XHQkcG9wdXAgPSBwYXJlbnQuc2libGluZ3MoICcjd3Bmb3Jtcy1ndXRlbmJlcmctcG9wdXAnICk7XG5cdFx0XHR9XG5cblx0XHRcdGNvbnN0IHVybCA9IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3IuZ2V0X3N0YXJ0ZWRfdXJsLFxuXHRcdFx0XHQkaWZyYW1lID0gJHBvcHVwLmZpbmQoICdpZnJhbWUnICk7XG5cblx0XHRcdGFwcC5idWlsZGVyQ2xvc2VCdXR0b25FdmVudCggY2xpZW50SUQgKTtcblx0XHRcdCRpZnJhbWUuYXR0ciggJ3NyYycsIHVybCApO1xuXHRcdFx0JHBvcHVwLmZhZGVJbigpO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBDbG9zZSBidXR0b24gKGluc2lkZSB0aGUgZm9ybSBidWlsZGVyKSBjbGljayBldmVudC5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguM1xuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtzdHJpbmd9IGNsaWVudElEIEJsb2NrIENsaWVudCBJRC5cblx0XHQgKi9cblx0XHRidWlsZGVyQ2xvc2VCdXR0b25FdmVudCggY2xpZW50SUQgKSB7XG5cdFx0XHQkcG9wdXBcblx0XHRcdFx0Lm9mZiggJ3dwZm9ybXNCdWlsZGVySW5Qb3B1cENsb3NlJyApXG5cdFx0XHRcdC5vbiggJ3dwZm9ybXNCdWlsZGVySW5Qb3B1cENsb3NlJywgZnVuY3Rpb24oIGUsIGFjdGlvbiwgZm9ybUlkLCBmb3JtVGl0bGUgKSB7XG5cdFx0XHRcdFx0aWYgKCBhY3Rpb24gIT09ICdzYXZlZCcgfHwgISBmb3JtSWQgKSB7XG5cdFx0XHRcdFx0XHRyZXR1cm47XG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0Ly8gSW5zZXJ0IGEgbmV3IGJsb2NrIHdoZW4gYSBuZXcgZm9ybSBpcyBjcmVhdGVkIGZyb20gdGhlIHBvcHVwIHRvIHVwZGF0ZSB0aGUgZm9ybSBsaXN0IGFuZCBhdHRyaWJ1dGVzLlxuXHRcdFx0XHRcdGNvbnN0IG5ld0Jsb2NrID0gd3AuYmxvY2tzLmNyZWF0ZUJsb2NrKCAnd3Bmb3Jtcy9mb3JtLXNlbGVjdG9yJywge1xuXHRcdFx0XHRcdFx0Zm9ybUlkOiBmb3JtSWQudG9TdHJpbmcoKSwgLy8gRXhwZWN0cyBzdHJpbmcgdmFsdWUsIG1ha2Ugc3VyZSB3ZSBpbnNlcnQgc3RyaW5nLlxuXHRcdFx0XHRcdH0gKTtcblxuXHRcdFx0XHRcdC8vIGVzbGludC1kaXNhYmxlLW5leHQtbGluZSBjYW1lbGNhc2Vcblx0XHRcdFx0XHRmb3JtTGlzdCA9IFsgeyBJRDogZm9ybUlkLCBwb3N0X3RpdGxlOiBmb3JtVGl0bGUgfSBdO1xuXG5cdFx0XHRcdFx0Ly8gSW5zZXJ0IGEgbmV3IGJsb2NrLlxuXHRcdFx0XHRcdHdwLmRhdGEuZGlzcGF0Y2goICdjb3JlL2Jsb2NrLWVkaXRvcicgKS5yZW1vdmVCbG9jayggY2xpZW50SUQgKTtcblx0XHRcdFx0XHR3cC5kYXRhLmRpc3BhdGNoKCAnY29yZS9ibG9jay1lZGl0b3InICkuaW5zZXJ0QmxvY2tzKCBuZXdCbG9jayApO1xuXHRcdFx0XHR9ICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIFJlZ2lzdGVyIGJsb2NrLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICovXG5cdFx0Ly8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lIG1heC1saW5lcy1wZXItZnVuY3Rpb25cblx0XHRyZWdpc3RlckJsb2NrKCkge1xuXHRcdFx0cmVnaXN0ZXJCbG9ja1R5cGUoICd3cGZvcm1zL2Zvcm0tc2VsZWN0b3InLCB7XG5cdFx0XHRcdHRpdGxlOiBzdHJpbmdzLnRpdGxlLFxuXHRcdFx0XHRkZXNjcmlwdGlvbjogc3RyaW5ncy5kZXNjcmlwdGlvbixcblx0XHRcdFx0aWNvbjogYXBwLmdldEljb24oKSxcblx0XHRcdFx0a2V5d29yZHM6IHN0cmluZ3MuZm9ybV9rZXl3b3Jkcyxcblx0XHRcdFx0Y2F0ZWdvcnk6ICd3aWRnZXRzJyxcblx0XHRcdFx0YXR0cmlidXRlczogYXBwLmdldEJsb2NrQXR0cmlidXRlcygpLFxuXHRcdFx0XHRzdXBwb3J0czoge1xuXHRcdFx0XHRcdGN1c3RvbUNsYXNzTmFtZTogYXBwLmhhc0Zvcm1zKCksXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGV4YW1wbGU6IHtcblx0XHRcdFx0XHRhdHRyaWJ1dGVzOiB7XG5cdFx0XHRcdFx0XHRwcmV2aWV3OiB0cnVlLFxuXHRcdFx0XHRcdH0sXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGVkaXQoIHByb3BzICkge1xuXHRcdFx0XHRcdC8vIEdldCBmcmVzaCBsaXN0IG9mIGZvcm1zLlxuXHRcdFx0XHRcdGFwcC5nZXRGb3JtcygpO1xuXG5cdFx0XHRcdFx0Y29uc3QgeyBhdHRyaWJ1dGVzIH0gPSBwcm9wcztcblx0XHRcdFx0XHRjb25zdCBmb3JtT3B0aW9ucyA9IGFwcC5nZXRGb3JtT3B0aW9ucygpO1xuXHRcdFx0XHRcdGNvbnN0IGhhbmRsZXJzID0gYXBwLmdldFNldHRpbmdzRmllbGRzSGFuZGxlcnMoIHByb3BzICk7XG5cblx0XHRcdFx0XHQvLyBTdG9yZSBibG9jayBjbGllbnRJZCBpbiBhdHRyaWJ1dGVzLlxuXHRcdFx0XHRcdGlmICggISBhdHRyaWJ1dGVzLmNsaWVudElkICkge1xuXHRcdFx0XHRcdFx0Ly8gV2UganVzdCB3YW50IGNsaWVudCBJRCB0byB1cGRhdGUgb25jZS5cblx0XHRcdFx0XHRcdC8vIFRoZSBibG9jayBlZGl0b3IgZG9lc24ndCBoYXZlIGEgZml4ZWQgYmxvY2sgSUQsIHNvIHdlIG5lZWQgdG8gZ2V0IGl0IG9uIHRoZSBpbml0aWFsIGxvYWQsIGJ1dCBvbmx5IG9uY2UuXG5cdFx0XHRcdFx0XHRwcm9wcy5zZXRBdHRyaWJ1dGVzKCB7IGNsaWVudElkOiBwcm9wcy5jbGllbnRJZCB9ICk7XG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0Ly8gTWFpbiBibG9jayBzZXR0aW5ncy5cblx0XHRcdFx0XHRjb25zdCBqc3ggPSBbXG5cdFx0XHRcdFx0XHRhcHAuanN4UGFydHMuZ2V0TWFpblNldHRpbmdzKCBhdHRyaWJ1dGVzLCBoYW5kbGVycywgZm9ybU9wdGlvbnMgKSxcblx0XHRcdFx0XHRdO1xuXG5cdFx0XHRcdFx0Ly8gQmxvY2sgcHJldmlldyBwaWN0dXJlLlxuXHRcdFx0XHRcdGlmICggISBhcHAuaGFzRm9ybXMoKSApIHtcblx0XHRcdFx0XHRcdGpzeC5wdXNoKFxuXHRcdFx0XHRcdFx0XHRhcHAuanN4UGFydHMuZ2V0RW1wdHlGb3Jtc1ByZXZpZXcoIHByb3BzICksXG5cdFx0XHRcdFx0XHQpO1xuXG5cdFx0XHRcdFx0XHRyZXR1cm4ganN4O1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdGNvbnN0IHNpemVPcHRpb25zID0gYXBwLmdldFNpemVPcHRpb25zKCk7XG5cblx0XHRcdFx0XHQvLyBGb3JtIHN0eWxlIHNldHRpbmdzICYgYmxvY2sgY29udGVudC5cblx0XHRcdFx0XHRpZiAoIGF0dHJpYnV0ZXMuZm9ybUlkICkge1xuXHRcdFx0XHRcdFx0anN4LnB1c2goXG5cdFx0XHRcdFx0XHRcdGFwcC5qc3hQYXJ0cy5nZXRTdHlsZVNldHRpbmdzKCBwcm9wcywgaGFuZGxlcnMsIHNpemVPcHRpb25zICksXG5cdFx0XHRcdFx0XHRcdGFwcC5qc3hQYXJ0cy5nZXRBZHZhbmNlZFNldHRpbmdzKCBwcm9wcywgaGFuZGxlcnMgKSxcblx0XHRcdFx0XHRcdFx0YXBwLmpzeFBhcnRzLmdldEJsb2NrRm9ybUNvbnRlbnQoIHByb3BzICksXG5cdFx0XHRcdFx0XHQpO1xuXG5cdFx0XHRcdFx0XHRoYW5kbGVycy51cGRhdGVDb3B5UGFzdGVDb250ZW50KCk7XG5cblx0XHRcdFx0XHRcdCQoIHdpbmRvdyApLnRyaWdnZXIoICd3cGZvcm1zRm9ybVNlbGVjdG9yRWRpdCcsIFsgcHJvcHMgXSApO1xuXG5cdFx0XHRcdFx0XHRyZXR1cm4ganN4O1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdC8vIEJsb2NrIHByZXZpZXcgcGljdHVyZS5cblx0XHRcdFx0XHRpZiAoIGF0dHJpYnV0ZXMucHJldmlldyApIHtcblx0XHRcdFx0XHRcdGpzeC5wdXNoKFxuXHRcdFx0XHRcdFx0XHRhcHAuanN4UGFydHMuZ2V0QmxvY2tQcmV2aWV3KCksXG5cdFx0XHRcdFx0XHQpO1xuXG5cdFx0XHRcdFx0XHRyZXR1cm4ganN4O1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdC8vIEJsb2NrIHBsYWNlaG9sZGVyIChmb3JtIHNlbGVjdG9yKS5cblx0XHRcdFx0XHRqc3gucHVzaChcblx0XHRcdFx0XHRcdGFwcC5qc3hQYXJ0cy5nZXRCbG9ja1BsYWNlaG9sZGVyKCBwcm9wcy5hdHRyaWJ1dGVzLCBoYW5kbGVycywgZm9ybU9wdGlvbnMgKSxcblx0XHRcdFx0XHQpO1xuXG5cdFx0XHRcdFx0cmV0dXJuIGpzeDtcblx0XHRcdFx0fSxcblx0XHRcdFx0c2F2ZTogKCkgPT4gbnVsbCxcblx0XHRcdH0gKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogSW5pdCBkZWZhdWx0IHN0eWxlIHNldHRpbmdzLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICovXG5cdFx0aW5pdERlZmF1bHRzKCkge1xuXHRcdFx0WyAnZm9ybUlkJywgJ2NvcHlQYXN0ZUpzb25WYWx1ZScgXS5mb3JFYWNoKCAoIGtleSApID0+IGRlbGV0ZSBkZWZhdWx0U3R5bGVTZXR0aW5nc1sga2V5IF0gKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogQ2hlY2sgaWYgc2l0ZSBoYXMgZm9ybXMuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjNcblx0XHQgKlxuXHRcdCAqIEByZXR1cm4ge2Jvb2xlYW59IFdoZXRoZXIgc2l0ZSBoYXMgYXQgbGVhc3Qgb25lIGZvcm0uXG5cdFx0ICovXG5cdFx0aGFzRm9ybXMoKSB7XG5cdFx0XHRyZXR1cm4gZm9ybUxpc3QubGVuZ3RoID49IDE7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEJsb2NrIEpTWCBwYXJ0cy5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHR5cGUge09iamVjdH1cblx0XHQgKi9cblx0XHRqc3hQYXJ0czoge1xuXG5cdFx0XHQvKipcblx0XHRcdCAqIEdldCBtYWluIHNldHRpbmdzIEpTWCBjb2RlLlxuXHRcdFx0ICpcblx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0ICpcblx0XHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBhdHRyaWJ1dGVzICBCbG9jayBhdHRyaWJ1dGVzLlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IGhhbmRsZXJzICAgIEJsb2NrIGV2ZW50IGhhbmRsZXJzLlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IGZvcm1PcHRpb25zIEZvcm0gc2VsZWN0b3Igb3B0aW9ucy5cblx0XHRcdCAqXG5cdFx0XHQgKiBAcmV0dXJuIHtKU1guRWxlbWVudH0gTWFpbiBzZXR0aW5nIEpTWCBjb2RlLlxuXHRcdFx0ICovXG5cdFx0XHRnZXRNYWluU2V0dGluZ3MoIGF0dHJpYnV0ZXMsIGhhbmRsZXJzLCBmb3JtT3B0aW9ucyApIHtcblx0XHRcdFx0aWYgKCAhIGFwcC5oYXNGb3JtcygpICkge1xuXHRcdFx0XHRcdHJldHVybiBhcHAuanN4UGFydHMucHJpbnRFbXB0eUZvcm1zTm90aWNlKCBhdHRyaWJ1dGVzLmNsaWVudElkICk7XG5cdFx0XHRcdH1cblxuXHRcdFx0XHRyZXR1cm4gKFxuXHRcdFx0XHRcdDxJbnNwZWN0b3JDb250cm9scyBrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWluc3BlY3Rvci1tYWluLXNldHRpbmdzXCI+XG5cdFx0XHRcdFx0XHQ8UGFuZWxCb2R5IGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLXBhbmVsXCIgdGl0bGU9eyBzdHJpbmdzLmZvcm1fc2V0dGluZ3MgfT5cblx0XHRcdFx0XHRcdFx0PFNlbGVjdENvbnRyb2xcblx0XHRcdFx0XHRcdFx0XHRsYWJlbD17IHN0cmluZ3MuZm9ybV9zZWxlY3RlZCB9XG5cdFx0XHRcdFx0XHRcdFx0dmFsdWU9eyBhdHRyaWJ1dGVzLmZvcm1JZCB9XG5cdFx0XHRcdFx0XHRcdFx0b3B0aW9ucz17IGZvcm1PcHRpb25zIH1cblx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZT17ICggdmFsdWUgKSA9PiBoYW5kbGVycy5hdHRyQ2hhbmdlKCAnZm9ybUlkJywgdmFsdWUgKSB9XG5cdFx0XHRcdFx0XHRcdC8+XG5cdFx0XHRcdFx0XHRcdDxUb2dnbGVDb250cm9sXG5cdFx0XHRcdFx0XHRcdFx0bGFiZWw9eyBzdHJpbmdzLnNob3dfdGl0bGUgfVxuXHRcdFx0XHRcdFx0XHRcdGNoZWNrZWQ9eyBhdHRyaWJ1dGVzLmRpc3BsYXlUaXRsZSB9XG5cdFx0XHRcdFx0XHRcdFx0b25DaGFuZ2U9eyAoIHZhbHVlICkgPT4gaGFuZGxlcnMuYXR0ckNoYW5nZSggJ2Rpc3BsYXlUaXRsZScsIHZhbHVlICkgfVxuXHRcdFx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdFx0XHQ8VG9nZ2xlQ29udHJvbFxuXHRcdFx0XHRcdFx0XHRcdGxhYmVsPXsgc3RyaW5ncy5zaG93X2Rlc2NyaXB0aW9uIH1cblx0XHRcdFx0XHRcdFx0XHRjaGVja2VkPXsgYXR0cmlidXRlcy5kaXNwbGF5RGVzYyB9XG5cdFx0XHRcdFx0XHRcdFx0b25DaGFuZ2U9eyAoIHZhbHVlICkgPT4gaGFuZGxlcnMuYXR0ckNoYW5nZSggJ2Rpc3BsYXlEZXNjJywgdmFsdWUgKSB9XG5cdFx0XHRcdFx0XHRcdC8+XG5cdFx0XHRcdFx0XHRcdDxwIGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLXBhbmVsLW5vdGljZVwiPlxuXHRcdFx0XHRcdFx0XHRcdDxzdHJvbmc+eyBzdHJpbmdzLnBhbmVsX25vdGljZV9oZWFkIH08L3N0cm9uZz5cblx0XHRcdFx0XHRcdFx0XHR7IHN0cmluZ3MucGFuZWxfbm90aWNlX3RleHQgfVxuXHRcdFx0XHRcdFx0XHRcdDxhIGhyZWY9eyBzdHJpbmdzLnBhbmVsX25vdGljZV9saW5rIH0gcmVsPVwibm9yZWZlcnJlclwiIHRhcmdldD1cIl9ibGFua1wiPnsgc3RyaW5ncy5wYW5lbF9ub3RpY2VfbGlua190ZXh0IH08L2E+XG5cdFx0XHRcdFx0XHRcdDwvcD5cblx0XHRcdFx0XHRcdDwvUGFuZWxCb2R5PlxuXHRcdFx0XHRcdDwvSW5zcGVjdG9yQ29udHJvbHM+XG5cdFx0XHRcdCk7XG5cdFx0XHR9LFxuXG5cdFx0XHQvKipcblx0XHRcdCAqIFByaW50IGVtcHR5IGZvcm1zIG5vdGljZS5cblx0XHRcdCAqXG5cdFx0XHQgKiBAc2luY2UgMS44LjNcblx0XHRcdCAqXG5cdFx0XHQgKiBAcGFyYW0ge3N0cmluZ30gY2xpZW50SWQgQmxvY2sgY2xpZW50IElELlxuXHRcdFx0ICpcblx0XHRcdCAqIEByZXR1cm4ge0pTWC5FbGVtZW50fSBGaWVsZCBzdHlsZXMgSlNYIGNvZGUuXG5cdFx0XHQgKi9cblx0XHRcdHByaW50RW1wdHlGb3Jtc05vdGljZSggY2xpZW50SWQgKSB7XG5cdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0PEluc3BlY3RvckNvbnRyb2xzIGtleT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItaW5zcGVjdG9yLW1haW4tc2V0dGluZ3NcIj5cblx0XHRcdFx0XHRcdDxQYW5lbEJvZHkgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctcGFuZWxcIiB0aXRsZT17IHN0cmluZ3MuZm9ybV9zZXR0aW5ncyB9PlxuXHRcdFx0XHRcdFx0XHQ8cCBjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1wYW5lbC1ub3RpY2Ugd3Bmb3Jtcy13YXJuaW5nIHdwZm9ybXMtZW1wdHktZm9ybS1ub3RpY2VcIiBzdHlsZT17IHsgZGlzcGxheTogJ2Jsb2NrJyB9IH0+XG5cdFx0XHRcdFx0XHRcdFx0PHN0cm9uZz57IF9fKCAnWW91IGhhdmVu4oCZdCBjcmVhdGVkIGEgZm9ybSwgeWV0IScsICd3cGZvcm1zLWxpdGUnICkgfTwvc3Ryb25nPlxuXHRcdFx0XHRcdFx0XHRcdHsgX18oICdXaGF0IGFyZSB5b3Ugd2FpdGluZyBmb3I/JywgJ3dwZm9ybXMtbGl0ZScgKSB9XG5cdFx0XHRcdFx0XHRcdDwvcD5cblx0XHRcdFx0XHRcdFx0PGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3NOYW1lPVwiZ2V0LXN0YXJ0ZWQtYnV0dG9uIGNvbXBvbmVudHMtYnV0dG9uIGlzLXNlY29uZGFyeVwiXG5cdFx0XHRcdFx0XHRcdFx0b25DbGljaz17XG5cdFx0XHRcdFx0XHRcdFx0XHQoKSA9PiB7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdGFwcC5vcGVuQnVpbGRlclBvcHVwKCBjbGllbnRJZCApO1xuXHRcdFx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdFx0PlxuXHRcdFx0XHRcdFx0XHRcdHsgX18oICdHZXQgU3RhcnRlZCcsICd3cGZvcm1zLWxpdGUnICkgfVxuXHRcdFx0XHRcdFx0XHQ8L2J1dHRvbj5cblx0XHRcdFx0XHRcdDwvUGFuZWxCb2R5PlxuXHRcdFx0XHRcdDwvSW5zcGVjdG9yQ29udHJvbHM+XG5cdFx0XHRcdCk7XG5cdFx0XHR9LFxuXG5cdFx0XHQvKipcblx0XHRcdCAqIEdldCBGaWVsZCBzdHlsZXMgSlNYIGNvZGUuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0XHQgKlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IHByb3BzICAgICAgIEJsb2NrIHByb3BlcnRpZXMuXG5cdFx0XHQgKiBAcGFyYW0ge09iamVjdH0gaGFuZGxlcnMgICAgQmxvY2sgZXZlbnQgaGFuZGxlcnMuXG5cdFx0XHQgKiBAcGFyYW0ge09iamVjdH0gc2l6ZU9wdGlvbnMgU2l6ZSBzZWxlY3RvciBvcHRpb25zLlxuXHRcdFx0ICpcblx0XHRcdCAqIEByZXR1cm4ge09iamVjdH0gRmllbGQgc3R5bGVzIEpTWCBjb2RlLlxuXHRcdFx0ICovXG5cdFx0XHRnZXRGaWVsZFN0eWxlcyggcHJvcHMsIGhhbmRsZXJzLCBzaXplT3B0aW9ucyApIHsgLy8gZXNsaW50LWRpc2FibGUtbGluZSBtYXgtbGluZXMtcGVyLWZ1bmN0aW9uXG5cdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0PFBhbmVsQm9keSBjbGFzc05hbWU9eyBhcHAuZ2V0UGFuZWxDbGFzcyggcHJvcHMgKSB9IHRpdGxlPXsgc3RyaW5ncy5maWVsZF9zdHlsZXMgfT5cblx0XHRcdFx0XHRcdDxwIGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLXBhbmVsLW5vdGljZSB3cGZvcm1zLXVzZS1tb2Rlcm4tbm90aWNlXCI+XG5cdFx0XHRcdFx0XHRcdDxzdHJvbmc+eyBzdHJpbmdzLnVzZV9tb2Rlcm5fbm90aWNlX2hlYWQgfTwvc3Ryb25nPlxuXHRcdFx0XHRcdFx0XHR7IHN0cmluZ3MudXNlX21vZGVybl9ub3RpY2VfdGV4dCB9IDxhIGhyZWY9eyBzdHJpbmdzLnVzZV9tb2Rlcm5fbm90aWNlX2xpbmsgfSByZWw9XCJub3JlZmVycmVyXCIgdGFyZ2V0PVwiX2JsYW5rXCI+eyBzdHJpbmdzLmxlYXJuX21vcmUgfTwvYT5cblx0XHRcdFx0XHRcdDwvcD5cblxuXHRcdFx0XHRcdFx0PHAgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctcGFuZWwtbm90aWNlIHdwZm9ybXMtd2FybmluZyB3cGZvcm1zLWxlYWQtZm9ybS1ub3RpY2VcIiBzdHlsZT17IHsgZGlzcGxheTogJ25vbmUnIH0gfT5cblx0XHRcdFx0XHRcdFx0PHN0cm9uZz57IHN0cmluZ3MubGVhZF9mb3Jtc19wYW5lbF9ub3RpY2VfaGVhZCB9PC9zdHJvbmc+XG5cdFx0XHRcdFx0XHRcdHsgc3RyaW5ncy5sZWFkX2Zvcm1zX3BhbmVsX25vdGljZV90ZXh0IH1cblx0XHRcdFx0XHRcdDwvcD5cblxuXHRcdFx0XHRcdFx0PEZsZXggZ2FwPXsgNCB9IGFsaWduPVwiZmxleC1zdGFydFwiIGNsYXNzTmFtZT17ICd3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWZsZXgnIH0ganVzdGlmeT1cInNwYWNlLWJldHdlZW5cIj5cblx0XHRcdFx0XHRcdFx0PEZsZXhCbG9jaz5cblx0XHRcdFx0XHRcdFx0XHQ8U2VsZWN0Q29udHJvbFxuXHRcdFx0XHRcdFx0XHRcdFx0bGFiZWw9eyBzdHJpbmdzLnNpemUgfVxuXHRcdFx0XHRcdFx0XHRcdFx0dmFsdWU9eyBwcm9wcy5hdHRyaWJ1dGVzLmZpZWxkU2l6ZSB9XG5cdFx0XHRcdFx0XHRcdFx0XHRvcHRpb25zPXsgc2l6ZU9wdGlvbnMgfVxuXHRcdFx0XHRcdFx0XHRcdFx0b25DaGFuZ2U9eyAoIHZhbHVlICkgPT4gaGFuZGxlcnMuc3R5bGVBdHRyQ2hhbmdlKCAnZmllbGRTaXplJywgdmFsdWUgKSB9XG5cdFx0XHRcdFx0XHRcdFx0Lz5cblx0XHRcdFx0XHRcdFx0PC9GbGV4QmxvY2s+XG5cdFx0XHRcdFx0XHRcdDxGbGV4QmxvY2s+XG5cdFx0XHRcdFx0XHRcdFx0PF9fZXhwZXJpbWVudGFsVW5pdENvbnRyb2xcblx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsPXsgc3RyaW5ncy5ib3JkZXJfcmFkaXVzIH1cblx0XHRcdFx0XHRcdFx0XHRcdHZhbHVlPXsgcHJvcHMuYXR0cmlidXRlcy5maWVsZEJvcmRlclJhZGl1cyB9XG5cdFx0XHRcdFx0XHRcdFx0XHRpc1VuaXRTZWxlY3RUYWJiYWJsZVxuXHRcdFx0XHRcdFx0XHRcdFx0b25DaGFuZ2U9eyAoIHZhbHVlICkgPT4gaGFuZGxlcnMuc3R5bGVBdHRyQ2hhbmdlKCAnZmllbGRCb3JkZXJSYWRpdXMnLCB2YWx1ZSApIH1cblx0XHRcdFx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdFx0XHQ8L0ZsZXhCbG9jaz5cblx0XHRcdFx0XHRcdDwvRmxleD5cblxuXHRcdFx0XHRcdFx0PGRpdiBjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWNvbG9yLXBpY2tlclwiPlxuXHRcdFx0XHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItY29udHJvbC1sYWJlbFwiPnsgc3RyaW5ncy5jb2xvcnMgfTwvZGl2PlxuXHRcdFx0XHRcdFx0XHQ8UGFuZWxDb2xvclNldHRpbmdzXG5cdFx0XHRcdFx0XHRcdFx0X19leHBlcmltZW50YWxJc1JlbmRlcmVkSW5TaWRlYmFyXG5cdFx0XHRcdFx0XHRcdFx0ZW5hYmxlQWxwaGFcblx0XHRcdFx0XHRcdFx0XHRzaG93VGl0bGU9eyBmYWxzZSB9XG5cdFx0XHRcdFx0XHRcdFx0Y2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1jb2xvci1wYW5lbFwiXG5cdFx0XHRcdFx0XHRcdFx0Y29sb3JTZXR0aW5ncz17IFtcblx0XHRcdFx0XHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0dmFsdWU6IHByb3BzLmF0dHJpYnV0ZXMuZmllbGRCYWNrZ3JvdW5kQ29sb3IsXG5cdFx0XHRcdFx0XHRcdFx0XHRcdG9uQ2hhbmdlOiAoIHZhbHVlICkgPT4gaGFuZGxlcnMuc3R5bGVBdHRyQ2hhbmdlKCAnZmllbGRCYWNrZ3JvdW5kQ29sb3InLCB2YWx1ZSApLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRsYWJlbDogc3RyaW5ncy5iYWNrZ3JvdW5kLFxuXHRcdFx0XHRcdFx0XHRcdFx0fSxcblx0XHRcdFx0XHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0dmFsdWU6IHByb3BzLmF0dHJpYnV0ZXMuZmllbGRCb3JkZXJDb2xvcixcblx0XHRcdFx0XHRcdFx0XHRcdFx0b25DaGFuZ2U6ICggdmFsdWUgKSA9PiBoYW5kbGVycy5zdHlsZUF0dHJDaGFuZ2UoICdmaWVsZEJvcmRlckNvbG9yJywgdmFsdWUgKSxcblx0XHRcdFx0XHRcdFx0XHRcdFx0bGFiZWw6IHN0cmluZ3MuYm9yZGVyLFxuXHRcdFx0XHRcdFx0XHRcdFx0fSxcblx0XHRcdFx0XHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0dmFsdWU6IHByb3BzLmF0dHJpYnV0ZXMuZmllbGRUZXh0Q29sb3IsXG5cdFx0XHRcdFx0XHRcdFx0XHRcdG9uQ2hhbmdlOiAoIHZhbHVlICkgPT4gaGFuZGxlcnMuc3R5bGVBdHRyQ2hhbmdlKCAnZmllbGRUZXh0Q29sb3InLCB2YWx1ZSApLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRsYWJlbDogc3RyaW5ncy50ZXh0LFxuXHRcdFx0XHRcdFx0XHRcdFx0fSxcblx0XHRcdFx0XHRcdFx0XHRdIH1cblx0XHRcdFx0XHRcdFx0Lz5cblx0XHRcdFx0XHRcdDwvZGl2PlxuXHRcdFx0XHRcdDwvUGFuZWxCb2R5PlxuXHRcdFx0XHQpO1xuXHRcdFx0fSxcblxuXHRcdFx0LyoqXG5cdFx0XHQgKiBHZXQgTGFiZWwgc3R5bGVzIEpTWCBjb2RlLlxuXHRcdFx0ICpcblx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0ICpcblx0XHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBwcm9wcyAgICAgICBCbG9jayBwcm9wZXJ0aWVzLlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IGhhbmRsZXJzICAgIEJsb2NrIGV2ZW50IGhhbmRsZXJzLlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IHNpemVPcHRpb25zIFNpemUgc2VsZWN0b3Igb3B0aW9ucy5cblx0XHRcdCAqXG5cdFx0XHQgKiBAcmV0dXJuIHtPYmplY3R9IExhYmVsIHN0eWxlcyBKU1ggY29kZS5cblx0XHRcdCAqL1xuXHRcdFx0Z2V0TGFiZWxTdHlsZXMoIHByb3BzLCBoYW5kbGVycywgc2l6ZU9wdGlvbnMgKSB7XG5cdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0PFBhbmVsQm9keSBjbGFzc05hbWU9eyBhcHAuZ2V0UGFuZWxDbGFzcyggcHJvcHMgKSB9IHRpdGxlPXsgc3RyaW5ncy5sYWJlbF9zdHlsZXMgfT5cblx0XHRcdFx0XHRcdDxTZWxlY3RDb250cm9sXG5cdFx0XHRcdFx0XHRcdGxhYmVsPXsgc3RyaW5ncy5zaXplIH1cblx0XHRcdFx0XHRcdFx0dmFsdWU9eyBwcm9wcy5hdHRyaWJ1dGVzLmxhYmVsU2l6ZSB9XG5cdFx0XHRcdFx0XHRcdGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItZml4LWJvdHRvbS1tYXJnaW5cIlxuXHRcdFx0XHRcdFx0XHRvcHRpb25zPXsgc2l6ZU9wdGlvbnMgfVxuXHRcdFx0XHRcdFx0XHRvbkNoYW5nZT17ICggdmFsdWUgKSA9PiBoYW5kbGVycy5zdHlsZUF0dHJDaGFuZ2UoICdsYWJlbFNpemUnLCB2YWx1ZSApIH1cblx0XHRcdFx0XHRcdC8+XG5cblx0XHRcdFx0XHRcdDxkaXYgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1jb2xvci1waWNrZXJcIj5cblx0XHRcdFx0XHRcdFx0PGRpdiBjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWNvbnRyb2wtbGFiZWxcIj57IHN0cmluZ3MuY29sb3JzIH08L2Rpdj5cblx0XHRcdFx0XHRcdFx0PFBhbmVsQ29sb3JTZXR0aW5nc1xuXHRcdFx0XHRcdFx0XHRcdF9fZXhwZXJpbWVudGFsSXNSZW5kZXJlZEluU2lkZWJhclxuXHRcdFx0XHRcdFx0XHRcdGVuYWJsZUFscGhhXG5cdFx0XHRcdFx0XHRcdFx0c2hvd1RpdGxlPXsgZmFsc2UgfVxuXHRcdFx0XHRcdFx0XHRcdGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItY29sb3ItcGFuZWxcIlxuXHRcdFx0XHRcdFx0XHRcdGNvbG9yU2V0dGluZ3M9eyBbXG5cdFx0XHRcdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdHZhbHVlOiBwcm9wcy5hdHRyaWJ1dGVzLmxhYmVsQ29sb3IsXG5cdFx0XHRcdFx0XHRcdFx0XHRcdG9uQ2hhbmdlOiAoIHZhbHVlICkgPT4gaGFuZGxlcnMuc3R5bGVBdHRyQ2hhbmdlKCAnbGFiZWxDb2xvcicsIHZhbHVlICksXG5cdFx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsOiBzdHJpbmdzLmxhYmVsLFxuXHRcdFx0XHRcdFx0XHRcdFx0fSxcblx0XHRcdFx0XHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0dmFsdWU6IHByb3BzLmF0dHJpYnV0ZXMubGFiZWxTdWJsYWJlbENvbG9yLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZTogKCB2YWx1ZSApID0+IGhhbmRsZXJzLnN0eWxlQXR0ckNoYW5nZSggJ2xhYmVsU3VibGFiZWxDb2xvcicsIHZhbHVlICksXG5cdFx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsOiBzdHJpbmdzLnN1YmxhYmVsX2hpbnRzLnJlcGxhY2UoICcmYW1wOycsICcmJyApLFxuXHRcdFx0XHRcdFx0XHRcdFx0fSxcblx0XHRcdFx0XHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0dmFsdWU6IHByb3BzLmF0dHJpYnV0ZXMubGFiZWxFcnJvckNvbG9yLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZTogKCB2YWx1ZSApID0+IGhhbmRsZXJzLnN0eWxlQXR0ckNoYW5nZSggJ2xhYmVsRXJyb3JDb2xvcicsIHZhbHVlICksXG5cdFx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsOiBzdHJpbmdzLmVycm9yX21lc3NhZ2UsXG5cdFx0XHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XHRcdF0gfVxuXHRcdFx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdFx0PC9kaXY+XG5cdFx0XHRcdFx0PC9QYW5lbEJvZHk+XG5cdFx0XHRcdCk7XG5cdFx0XHR9LFxuXG5cdFx0XHQvKipcblx0XHRcdCAqIEdldCBCdXR0b24gc3R5bGVzIEpTWCBjb2RlLlxuXHRcdFx0ICpcblx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0ICpcblx0XHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBwcm9wcyAgICAgICBCbG9jayBwcm9wZXJ0aWVzLlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IGhhbmRsZXJzICAgIEJsb2NrIGV2ZW50IGhhbmRsZXJzLlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IHNpemVPcHRpb25zIFNpemUgc2VsZWN0b3Igb3B0aW9ucy5cblx0XHRcdCAqXG5cdFx0XHQgKiBAcmV0dXJuIHtPYmplY3R9ICBCdXR0b24gc3R5bGVzIEpTWCBjb2RlLlxuXHRcdFx0ICovXG5cdFx0XHRnZXRCdXR0b25TdHlsZXMoIHByb3BzLCBoYW5kbGVycywgc2l6ZU9wdGlvbnMgKSB7XG5cdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0PFBhbmVsQm9keSBjbGFzc05hbWU9eyBhcHAuZ2V0UGFuZWxDbGFzcyggcHJvcHMgKSB9IHRpdGxlPXsgc3RyaW5ncy5idXR0b25fc3R5bGVzIH0+XG5cdFx0XHRcdFx0XHQ8RmxleCBnYXA9eyA0IH0gYWxpZ249XCJmbGV4LXN0YXJ0XCIgY2xhc3NOYW1lPXsgJ3dwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItZmxleCcgfSBqdXN0aWZ5PVwic3BhY2UtYmV0d2VlblwiPlxuXHRcdFx0XHRcdFx0XHQ8RmxleEJsb2NrPlxuXHRcdFx0XHRcdFx0XHRcdDxTZWxlY3RDb250cm9sXG5cdFx0XHRcdFx0XHRcdFx0XHRsYWJlbD17IHN0cmluZ3Muc2l6ZSB9XG5cdFx0XHRcdFx0XHRcdFx0XHR2YWx1ZT17IHByb3BzLmF0dHJpYnV0ZXMuYnV0dG9uU2l6ZSB9XG5cdFx0XHRcdFx0XHRcdFx0XHRvcHRpb25zPXsgc2l6ZU9wdGlvbnMgfVxuXHRcdFx0XHRcdFx0XHRcdFx0b25DaGFuZ2U9eyAoIHZhbHVlICkgPT4gaGFuZGxlcnMuc3R5bGVBdHRyQ2hhbmdlKCAnYnV0dG9uU2l6ZScsIHZhbHVlICkgfVxuXHRcdFx0XHRcdFx0XHRcdC8+XG5cdFx0XHRcdFx0XHRcdDwvRmxleEJsb2NrPlxuXHRcdFx0XHRcdFx0XHQ8RmxleEJsb2NrPlxuXHRcdFx0XHRcdFx0XHRcdDxfX2V4cGVyaW1lbnRhbFVuaXRDb250cm9sXG5cdFx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZT17ICggdmFsdWUgKSA9PiBoYW5kbGVycy5zdHlsZUF0dHJDaGFuZ2UoICdidXR0b25Cb3JkZXJSYWRpdXMnLCB2YWx1ZSApIH1cblx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsPXsgc3RyaW5ncy5ib3JkZXJfcmFkaXVzIH1cblx0XHRcdFx0XHRcdFx0XHRcdGlzVW5pdFNlbGVjdFRhYmJhYmxlXG5cdFx0XHRcdFx0XHRcdFx0XHR2YWx1ZT17IHByb3BzLmF0dHJpYnV0ZXMuYnV0dG9uQm9yZGVyUmFkaXVzIH0gLz5cblx0XHRcdFx0XHRcdFx0PC9GbGV4QmxvY2s+XG5cdFx0XHRcdFx0XHQ8L0ZsZXg+XG5cblx0XHRcdFx0XHRcdDxkaXYgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1jb2xvci1waWNrZXJcIj5cblx0XHRcdFx0XHRcdFx0PGRpdiBjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWNvbnRyb2wtbGFiZWxcIj57IHN0cmluZ3MuY29sb3JzIH08L2Rpdj5cblx0XHRcdFx0XHRcdFx0PFBhbmVsQ29sb3JTZXR0aW5nc1xuXHRcdFx0XHRcdFx0XHRcdF9fZXhwZXJpbWVudGFsSXNSZW5kZXJlZEluU2lkZWJhclxuXHRcdFx0XHRcdFx0XHRcdGVuYWJsZUFscGhhXG5cdFx0XHRcdFx0XHRcdFx0c2hvd1RpdGxlPXsgZmFsc2UgfVxuXHRcdFx0XHRcdFx0XHRcdGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItY29sb3ItcGFuZWxcIlxuXHRcdFx0XHRcdFx0XHRcdGNvbG9yU2V0dGluZ3M9eyBbXG5cdFx0XHRcdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdHZhbHVlOiBwcm9wcy5hdHRyaWJ1dGVzLmJ1dHRvbkJhY2tncm91bmRDb2xvcixcblx0XHRcdFx0XHRcdFx0XHRcdFx0b25DaGFuZ2U6ICggdmFsdWUgKSA9PiBoYW5kbGVycy5zdHlsZUF0dHJDaGFuZ2UoICdidXR0b25CYWNrZ3JvdW5kQ29sb3InLCB2YWx1ZSApLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRsYWJlbDogc3RyaW5ncy5iYWNrZ3JvdW5kLFxuXHRcdFx0XHRcdFx0XHRcdFx0fSxcblx0XHRcdFx0XHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0dmFsdWU6IHByb3BzLmF0dHJpYnV0ZXMuYnV0dG9uVGV4dENvbG9yLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZTogKCB2YWx1ZSApID0+IGhhbmRsZXJzLnN0eWxlQXR0ckNoYW5nZSggJ2J1dHRvblRleHRDb2xvcicsIHZhbHVlICksXG5cdFx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsOiBzdHJpbmdzLnRleHQsXG5cdFx0XHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XHRcdF0gfSAvPlxuXHRcdFx0XHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItbGVnZW5kIHdwZm9ybXMtYnV0dG9uLWNvbG9yLW5vdGljZVwiPlxuXHRcdFx0XHRcdFx0XHRcdHsgc3RyaW5ncy5idXR0b25fY29sb3Jfbm90aWNlIH1cblx0XHRcdFx0XHRcdFx0PC9kaXY+XG5cdFx0XHRcdFx0XHQ8L2Rpdj5cblx0XHRcdFx0XHQ8L1BhbmVsQm9keT5cblx0XHRcdFx0KTtcblx0XHRcdH0sXG5cblx0XHRcdC8qKlxuXHRcdFx0ICogR2V0IHN0eWxlIHNldHRpbmdzIEpTWCBjb2RlLlxuXHRcdFx0ICpcblx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0ICpcblx0XHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBwcm9wcyAgICAgICBCbG9jayBwcm9wZXJ0aWVzLlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IGhhbmRsZXJzICAgIEJsb2NrIGV2ZW50IGhhbmRsZXJzLlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IHNpemVPcHRpb25zIFNpemUgc2VsZWN0b3Igb3B0aW9ucy5cblx0XHRcdCAqXG5cdFx0XHQgKiBAcmV0dXJuIHtPYmplY3R9IEluc3BlY3RvciBjb250cm9scyBKU1ggY29kZS5cblx0XHRcdCAqL1xuXHRcdFx0Z2V0U3R5bGVTZXR0aW5ncyggcHJvcHMsIGhhbmRsZXJzLCBzaXplT3B0aW9ucyApIHtcblx0XHRcdFx0cmV0dXJuIChcblx0XHRcdFx0XHQ8SW5zcGVjdG9yQ29udHJvbHMga2V5PVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1zdHlsZS1zZXR0aW5nc1wiPlxuXHRcdFx0XHRcdFx0eyBhcHAuanN4UGFydHMuZ2V0RmllbGRTdHlsZXMoIHByb3BzLCBoYW5kbGVycywgc2l6ZU9wdGlvbnMgKSB9XG5cdFx0XHRcdFx0XHR7IGFwcC5qc3hQYXJ0cy5nZXRMYWJlbFN0eWxlcyggcHJvcHMsIGhhbmRsZXJzLCBzaXplT3B0aW9ucyApIH1cblx0XHRcdFx0XHRcdHsgYXBwLmpzeFBhcnRzLmdldEJ1dHRvblN0eWxlcyggcHJvcHMsIGhhbmRsZXJzLCBzaXplT3B0aW9ucyApIH1cblx0XHRcdFx0XHQ8L0luc3BlY3RvckNvbnRyb2xzPlxuXHRcdFx0XHQpO1xuXHRcdFx0fSxcblxuXHRcdFx0LyoqXG5cdFx0XHQgKiBHZXQgYWR2YW5jZWQgc2V0dGluZ3MgSlNYIGNvZGUuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0XHQgKlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IHByb3BzICAgIEJsb2NrIHByb3BlcnRpZXMuXG5cdFx0XHQgKiBAcGFyYW0ge09iamVjdH0gaGFuZGxlcnMgQmxvY2sgZXZlbnQgaGFuZGxlcnMuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHJldHVybiB7T2JqZWN0fSBJbnNwZWN0b3IgYWR2YW5jZWQgY29udHJvbHMgSlNYIGNvZGUuXG5cdFx0XHQgKi9cblx0XHRcdGdldEFkdmFuY2VkU2V0dGluZ3MoIHByb3BzLCBoYW5kbGVycyApIHtcblx0XHRcdFx0Ly8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lIHJlYWN0LWhvb2tzL3J1bGVzLW9mLWhvb2tzXG5cdFx0XHRcdGNvbnN0IFsgaXNPcGVuLCBzZXRPcGVuIF0gPSB1c2VTdGF0ZSggZmFsc2UgKTtcblx0XHRcdFx0Y29uc3Qgb3Blbk1vZGFsID0gKCkgPT4gc2V0T3BlbiggdHJ1ZSApO1xuXHRcdFx0XHRjb25zdCBjbG9zZU1vZGFsID0gKCkgPT4gc2V0T3BlbiggZmFsc2UgKTtcblxuXHRcdFx0XHRyZXR1cm4gKFxuXHRcdFx0XHRcdDxJbnNwZWN0b3JBZHZhbmNlZENvbnRyb2xzPlxuXHRcdFx0XHRcdFx0PGRpdiBjbGFzc05hbWU9eyBhcHAuZ2V0UGFuZWxDbGFzcyggcHJvcHMgKSB9PlxuXHRcdFx0XHRcdFx0XHQ8VGV4dGFyZWFDb250cm9sXG5cdFx0XHRcdFx0XHRcdFx0bGFiZWw9eyBzdHJpbmdzLmNvcHlfcGFzdGVfc2V0dGluZ3MgfVxuXHRcdFx0XHRcdFx0XHRcdHJvd3M9XCI0XCJcblx0XHRcdFx0XHRcdFx0XHRzcGVsbENoZWNrPVwiZmFsc2VcIlxuXHRcdFx0XHRcdFx0XHRcdHZhbHVlPXsgcHJvcHMuYXR0cmlidXRlcy5jb3B5UGFzdGVKc29uVmFsdWUgfVxuXHRcdFx0XHRcdFx0XHRcdG9uQ2hhbmdlPXsgKCB2YWx1ZSApID0+IGhhbmRsZXJzLnBhc3RlU2V0dGluZ3MoIHZhbHVlICkgfVxuXHRcdFx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItbGVnZW5kXCIgZGFuZ2Vyb3VzbHlTZXRJbm5lckhUTUw9eyB7IF9faHRtbDogc3RyaW5ncy5jb3B5X3Bhc3RlX25vdGljZSB9IH0+PC9kaXY+XG5cblx0XHRcdFx0XHRcdFx0PEJ1dHRvbiBjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLXJlc2V0LWJ1dHRvblwiIG9uQ2xpY2s9eyBvcGVuTW9kYWwgfT57IHN0cmluZ3MucmVzZXRfc3R5bGVfc2V0dGluZ3MgfTwvQnV0dG9uPlxuXHRcdFx0XHRcdFx0PC9kaXY+XG5cblx0XHRcdFx0XHRcdHsgaXNPcGVuICYmIChcblx0XHRcdFx0XHRcdFx0PE1vZGFsIGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLW1vZGFsXCJcblx0XHRcdFx0XHRcdFx0XHR0aXRsZT17IHN0cmluZ3MucmVzZXRfc3R5bGVfc2V0dGluZ3MgfVxuXHRcdFx0XHRcdFx0XHRcdG9uUmVxdWVzdENsb3NlPXsgY2xvc2VNb2RhbCB9PlxuXG5cdFx0XHRcdFx0XHRcdFx0PHA+eyBzdHJpbmdzLnJlc2V0X3NldHRpbmdzX2NvbmZpcm1fdGV4dCB9PC9wPlxuXG5cdFx0XHRcdFx0XHRcdFx0PEZsZXggZ2FwPXsgMyB9IGFsaWduPVwiY2VudGVyXCIganVzdGlmeT1cImZsZXgtZW5kXCI+XG5cdFx0XHRcdFx0XHRcdFx0XHQ8QnV0dG9uIGlzU2Vjb25kYXJ5IG9uQ2xpY2s9eyBjbG9zZU1vZGFsIH0+XG5cdFx0XHRcdFx0XHRcdFx0XHRcdHsgc3RyaW5ncy5idG5fbm8gfVxuXHRcdFx0XHRcdFx0XHRcdFx0PC9CdXR0b24+XG5cblx0XHRcdFx0XHRcdFx0XHRcdDxCdXR0b24gaXNQcmltYXJ5IG9uQ2xpY2s9eyAoKSA9PiB7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdGNsb3NlTW9kYWwoKTtcblx0XHRcdFx0XHRcdFx0XHRcdFx0aGFuZGxlcnMucmVzZXRTZXR0aW5ncygpO1xuXHRcdFx0XHRcdFx0XHRcdFx0fSB9PlxuXHRcdFx0XHRcdFx0XHRcdFx0XHR7IHN0cmluZ3MuYnRuX3llc19yZXNldCB9XG5cdFx0XHRcdFx0XHRcdFx0XHQ8L0J1dHRvbj5cblx0XHRcdFx0XHRcdFx0XHQ8L0ZsZXg+XG5cdFx0XHRcdFx0XHRcdDwvTW9kYWw+XG5cdFx0XHRcdFx0XHQpIH1cblx0XHRcdFx0XHQ8L0luc3BlY3RvckFkdmFuY2VkQ29udHJvbHM+XG5cdFx0XHRcdCk7XG5cdFx0XHR9LFxuXG5cdFx0XHQvKipcblx0XHRcdCAqIEdldCBibG9jayBjb250ZW50IEpTWCBjb2RlLlxuXHRcdFx0ICpcblx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0ICpcblx0XHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBwcm9wcyBCbG9jayBwcm9wZXJ0aWVzLlxuXHRcdFx0ICpcblx0XHRcdCAqIEByZXR1cm4ge0pTWC5FbGVtZW50fSBCbG9jayBjb250ZW50IEpTWCBjb2RlLlxuXHRcdFx0ICovXG5cdFx0XHRnZXRCbG9ja0Zvcm1Db250ZW50KCBwcm9wcyApIHtcblx0XHRcdFx0aWYgKCB0cmlnZ2VyU2VydmVyUmVuZGVyICkge1xuXHRcdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0XHQ8U2VydmVyU2lkZVJlbmRlclxuXHRcdFx0XHRcdFx0XHRrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLXNlcnZlci1zaWRlLXJlbmRlcmVyXCJcblx0XHRcdFx0XHRcdFx0YmxvY2s9XCJ3cGZvcm1zL2Zvcm0tc2VsZWN0b3JcIlxuXHRcdFx0XHRcdFx0XHRhdHRyaWJ1dGVzPXsgcHJvcHMuYXR0cmlidXRlcyB9XG5cdFx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdCk7XG5cdFx0XHRcdH1cblxuXHRcdFx0XHRjb25zdCBjbGllbnRJZCA9IHByb3BzLmNsaWVudElkO1xuXHRcdFx0XHRjb25zdCBibG9jayA9IGFwcC5nZXRCbG9ja0NvbnRhaW5lciggcHJvcHMgKTtcblxuXHRcdFx0XHQvLyBJbiB0aGUgY2FzZSBvZiBlbXB0eSBjb250ZW50LCB1c2Ugc2VydmVyIHNpZGUgcmVuZGVyZXIuXG5cdFx0XHRcdC8vIFRoaXMgaGFwcGVucyB3aGVuIHRoZSBibG9jayBpcyBkdXBsaWNhdGVkIG9yIGNvbnZlcnRlZCB0byBhIHJldXNhYmxlIGJsb2NrLlxuXHRcdFx0XHRpZiAoICEgYmxvY2sgfHwgISBibG9jay5pbm5lckhUTUwgKSB7XG5cdFx0XHRcdFx0dHJpZ2dlclNlcnZlclJlbmRlciA9IHRydWU7XG5cblx0XHRcdFx0XHRyZXR1cm4gYXBwLmpzeFBhcnRzLmdldEJsb2NrRm9ybUNvbnRlbnQoIHByb3BzICk7XG5cdFx0XHRcdH1cblxuXHRcdFx0XHRibG9ja3NbIGNsaWVudElkIF0gPSBibG9ja3NbIGNsaWVudElkIF0gfHwge307XG5cdFx0XHRcdGJsb2Nrc1sgY2xpZW50SWQgXS5ibG9ja0hUTUwgPSBibG9jay5pbm5lckhUTUw7XG5cdFx0XHRcdGJsb2Nrc1sgY2xpZW50SWQgXS5sb2FkZWRGb3JtSWQgPSBwcm9wcy5hdHRyaWJ1dGVzLmZvcm1JZDtcblxuXHRcdFx0XHRyZXR1cm4gKFxuXHRcdFx0XHRcdDxGcmFnbWVudCBrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWZyYWdtZW50LWZvcm0taHRtbFwiPlxuXHRcdFx0XHRcdFx0PGRpdiBkYW5nZXJvdXNseVNldElubmVySFRNTD17IHsgX19odG1sOiBibG9ja3NbIGNsaWVudElkIF0uYmxvY2tIVE1MIH0gfSAvPlxuXHRcdFx0XHRcdDwvRnJhZ21lbnQ+XG5cdFx0XHRcdCk7XG5cdFx0XHR9LFxuXG5cdFx0XHQvKipcblx0XHRcdCAqIEdldCBibG9jayBwcmV2aWV3IEpTWCBjb2RlLlxuXHRcdFx0ICpcblx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0ICpcblx0XHRcdCAqIEByZXR1cm4ge0pTWC5FbGVtZW50fSBCbG9jayBwcmV2aWV3IEpTWCBjb2RlLlxuXHRcdFx0ICovXG5cdFx0XHRnZXRCbG9ja1ByZXZpZXcoKSB7XG5cdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0PEZyYWdtZW50XG5cdFx0XHRcdFx0XHRrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWZyYWdtZW50LWJsb2NrLXByZXZpZXdcIj5cblx0XHRcdFx0XHRcdDxpbWcgc3JjPXsgd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5ibG9ja19wcmV2aWV3X3VybCB9IHN0eWxlPXsgeyB3aWR0aDogJzEwMCUnIH0gfSBhbHQ9XCJcIiAvPlxuXHRcdFx0XHRcdDwvRnJhZ21lbnQ+XG5cdFx0XHRcdCk7XG5cdFx0XHR9LFxuXG5cdFx0XHQvKipcblx0XHRcdCAqIEdldCBibG9jayBlbXB0eSBKU1ggY29kZS5cblx0XHRcdCAqXG5cdFx0XHQgKiBAc2luY2UgMS44LjNcblx0XHRcdCAqXG5cdFx0XHQgKiBAcGFyYW0ge09iamVjdH0gcHJvcHMgQmxvY2sgcHJvcGVydGllcy5cblx0XHRcdCAqIEByZXR1cm4ge0pTWC5FbGVtZW50fSBCbG9jayBlbXB0eSBKU1ggY29kZS5cblx0XHRcdCAqL1xuXHRcdFx0Z2V0RW1wdHlGb3Jtc1ByZXZpZXcoIHByb3BzICkge1xuXHRcdFx0XHRjb25zdCBjbGllbnRJZCA9IHByb3BzLmNsaWVudElkO1xuXG5cdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0PEZyYWdtZW50XG5cdFx0XHRcdFx0XHRrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWZyYWdtZW50LWJsb2NrLWVtcHR5XCI+XG5cdFx0XHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT1cIndwZm9ybXMtbm8tZm9ybS1wcmV2aWV3XCI+XG5cdFx0XHRcdFx0XHRcdDxpbWcgc3JjPXsgd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5ibG9ja19lbXB0eV91cmwgfSBhbHQ9XCJcIiAvPlxuXHRcdFx0XHRcdFx0XHQ8cD5cblx0XHRcdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdFx0XHRjcmVhdGVJbnRlcnBvbGF0ZUVsZW1lbnQoXG5cdFx0XHRcdFx0XHRcdFx0XHRcdF9fKFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdZb3UgY2FuIHVzZSA8Yj5XUEZvcm1zPC9iPiB0byBidWlsZCBjb250YWN0IGZvcm1zLCBzdXJ2ZXlzLCBwYXltZW50IGZvcm1zLCBhbmQgbW9yZSB3aXRoIGp1c3QgYSBmZXcgY2xpY2tzLicsXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3dwZm9ybXMtbGl0ZSdcblx0XHRcdFx0XHRcdFx0XHRcdFx0KSxcblx0XHRcdFx0XHRcdFx0XHRcdFx0e1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGI6IDxzdHJvbmcgLz4sXG5cdFx0XHRcdFx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdFx0XHRcdClcblx0XHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHRcdDwvcD5cblx0XHRcdFx0XHRcdFx0PGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3NOYW1lPVwiZ2V0LXN0YXJ0ZWQtYnV0dG9uIGNvbXBvbmVudHMtYnV0dG9uIGlzLXByaW1hcnlcIlxuXHRcdFx0XHRcdFx0XHRcdG9uQ2xpY2s9e1xuXHRcdFx0XHRcdFx0XHRcdFx0KCkgPT4ge1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRhcHAub3BlbkJ1aWxkZXJQb3B1cCggY2xpZW50SWQgKTtcblx0XHRcdFx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHRcdD5cblx0XHRcdFx0XHRcdFx0XHR7IF9fKCAnR2V0IFN0YXJ0ZWQnLCAnd3Bmb3Jtcy1saXRlJyApIH1cblx0XHRcdFx0XHRcdFx0PC9idXR0b24+XG5cdFx0XHRcdFx0XHRcdDxwIGNsYXNzTmFtZT1cImVtcHR5LWRlc2NcIj5cblx0XHRcdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdFx0XHRjcmVhdGVJbnRlcnBvbGF0ZUVsZW1lbnQoXG5cdFx0XHRcdFx0XHRcdFx0XHRcdF9fKFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdOZWVkIHNvbWUgaGVscD8gQ2hlY2sgb3V0IG91ciA8YT5jb21wcmVoZW5zaXZlIGd1aWRlLjwvYT4nLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCd3cGZvcm1zLWxpdGUnXG5cdFx0XHRcdFx0XHRcdFx0XHRcdCksXG5cdFx0XHRcdFx0XHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBlc2xpbnQtZGlzYWJsZS1uZXh0LWxpbmUganN4LWExMXkvYW5jaG9yLWhhcy1jb250ZW50XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0YTogPGEgaHJlZj17IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3Iud3Bmb3Jtc19ndWlkZSB9IHRhcmdldD1cIl9ibGFua1wiIHJlbD1cIm5vb3BlbmVyIG5vcmVmZXJyZXJcIiAvPixcblx0XHRcdFx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0XHRcdFx0KVxuXHRcdFx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdFx0PC9wPlxuXG5cdFx0XHRcdFx0XHRcdHsgLyogVGVtcGxhdGUgZm9yIHBvcHVwIHdpdGggYnVpbGRlciBpZnJhbWUgKi8gfVxuXHRcdFx0XHRcdFx0XHQ8ZGl2IGlkPVwid3Bmb3Jtcy1ndXRlbmJlcmctcG9wdXBcIiBjbGFzc05hbWU9XCJ3cGZvcm1zLWJ1aWxkZXItcG9wdXBcIj5cblx0XHRcdFx0XHRcdFx0XHQ8aWZyYW1lIHNyYz1cImFib3V0OmJsYW5rXCIgd2lkdGg9XCIxMDAlXCIgaGVpZ2h0PVwiMTAwJVwiIGlkPVwid3Bmb3Jtcy1idWlsZGVyLWlmcmFtZVwiIHRpdGxlPVwiV1BGb3JtcyBCdWlsZGVyIFBvcHVwXCI+PC9pZnJhbWU+XG5cdFx0XHRcdFx0XHRcdDwvZGl2PlxuXHRcdFx0XHRcdFx0PC9kaXY+XG5cdFx0XHRcdFx0PC9GcmFnbWVudD5cblx0XHRcdFx0KTtcblx0XHRcdH0sXG5cblx0XHRcdC8qKlxuXHRcdFx0ICogR2V0IGJsb2NrIHBsYWNlaG9sZGVyIChmb3JtIHNlbGVjdG9yKSBKU1ggY29kZS5cblx0XHRcdCAqXG5cdFx0XHQgKiBAc2luY2UgMS44LjFcblx0XHRcdCAqXG5cdFx0XHQgKiBAcGFyYW0ge09iamVjdH0gYXR0cmlidXRlcyAgQmxvY2sgYXR0cmlidXRlcy5cblx0XHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBoYW5kbGVycyAgICBCbG9jayBldmVudCBoYW5kbGVycy5cblx0XHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBmb3JtT3B0aW9ucyBGb3JtIHNlbGVjdG9yIG9wdGlvbnMuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHJldHVybiB7SlNYLkVsZW1lbnR9IEJsb2NrIHBsYWNlaG9sZGVyIEpTWCBjb2RlLlxuXHRcdFx0ICovXG5cdFx0XHRnZXRCbG9ja1BsYWNlaG9sZGVyKCBhdHRyaWJ1dGVzLCBoYW5kbGVycywgZm9ybU9wdGlvbnMgKSB7XG5cdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0PFBsYWNlaG9sZGVyXG5cdFx0XHRcdFx0XHRrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLXdyYXBcIlxuXHRcdFx0XHRcdFx0Y2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci13cmFwXCI+XG5cdFx0XHRcdFx0XHQ8aW1nIHNyYz17IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3IubG9nb191cmwgfSBhbHQ9XCJcIiAvPlxuXHRcdFx0XHRcdFx0PGgzPnsgc3RyaW5ncy50aXRsZSB9PC9oMz5cblx0XHRcdFx0XHRcdDxTZWxlY3RDb250cm9sXG5cdFx0XHRcdFx0XHRcdGtleT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3Itc2VsZWN0LWNvbnRyb2xcIlxuXHRcdFx0XHRcdFx0XHR2YWx1ZT17IGF0dHJpYnV0ZXMuZm9ybUlkIH1cblx0XHRcdFx0XHRcdFx0b3B0aW9ucz17IGZvcm1PcHRpb25zIH1cblx0XHRcdFx0XHRcdFx0b25DaGFuZ2U9eyAoIHZhbHVlICkgPT4gaGFuZGxlcnMuYXR0ckNoYW5nZSggJ2Zvcm1JZCcsIHZhbHVlICkgfVxuXHRcdFx0XHRcdFx0Lz5cblx0XHRcdFx0XHQ8L1BsYWNlaG9sZGVyPlxuXHRcdFx0XHQpO1xuXHRcdFx0fSxcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogR2V0IFN0eWxlIFNldHRpbmdzIHBhbmVsIGNsYXNzLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge09iamVjdH0gcHJvcHMgQmxvY2sgcHJvcGVydGllcy5cblx0XHQgKlxuXHRcdCAqIEByZXR1cm4ge3N0cmluZ30gU3R5bGUgU2V0dGluZ3MgcGFuZWwgY2xhc3MuXG5cdFx0ICovXG5cdFx0Z2V0UGFuZWxDbGFzcyggcHJvcHMgKSB7XG5cdFx0XHRsZXQgY3NzQ2xhc3MgPSAnd3Bmb3Jtcy1ndXRlbmJlcmctcGFuZWwgd3Bmb3Jtcy1ibG9jay1zZXR0aW5ncy0nICsgcHJvcHMuY2xpZW50SWQ7XG5cblx0XHRcdGlmICggISBhcHAuaXNGdWxsU3R5bGluZ0VuYWJsZWQoKSApIHtcblx0XHRcdFx0Y3NzQ2xhc3MgKz0gJyBkaXNhYmxlZF9wYW5lbCc7XG5cdFx0XHR9XG5cblx0XHRcdHJldHVybiBjc3NDbGFzcztcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogRGV0ZXJtaW5lIHdoZXRoZXIgdGhlIGZ1bGwgc3R5bGluZyBpcyBlbmFibGVkLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJuIHtib29sZWFufSBXaGV0aGVyIHRoZSBmdWxsIHN0eWxpbmcgaXMgZW5hYmxlZC5cblx0XHQgKi9cblx0XHRpc0Z1bGxTdHlsaW5nRW5hYmxlZCgpIHtcblx0XHRcdHJldHVybiB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLmlzX21vZGVybl9tYXJrdXAgJiYgd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5pc19mdWxsX3N0eWxpbmc7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEdldCBibG9jayBjb250YWluZXIgRE9NIGVsZW1lbnQuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBwcm9wcyBCbG9jayBwcm9wZXJ0aWVzLlxuXHRcdCAqXG5cdFx0ICogQHJldHVybiB7RWxlbWVudH0gQmxvY2sgY29udGFpbmVyLlxuXHRcdCAqL1xuXHRcdGdldEJsb2NrQ29udGFpbmVyKCBwcm9wcyApIHtcblx0XHRcdGNvbnN0IGJsb2NrU2VsZWN0b3IgPSBgI2Jsb2NrLSR7IHByb3BzLmNsaWVudElkIH0gPiBkaXZgO1xuXHRcdFx0bGV0IGJsb2NrID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggYmxvY2tTZWxlY3RvciApO1xuXG5cdFx0XHQvLyBGb3IgRlNFIC8gR3V0ZW5iZXJnIHBsdWdpbiB3ZSBuZWVkIHRvIHRha2UgYSBsb29rIGluc2lkZSB0aGUgaWZyYW1lLlxuXHRcdFx0aWYgKCAhIGJsb2NrICkge1xuXHRcdFx0XHRjb25zdCBlZGl0b3JDYW52YXMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCAnaWZyYW1lW25hbWU9XCJlZGl0b3ItY2FudmFzXCJdJyApO1xuXG5cdFx0XHRcdGJsb2NrID0gZWRpdG9yQ2FudmFzICYmIGVkaXRvckNhbnZhcy5jb250ZW50V2luZG93LmRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoIGJsb2NrU2VsZWN0b3IgKTtcblx0XHRcdH1cblxuXHRcdFx0cmV0dXJuIGJsb2NrO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBHZXQgc2V0dGluZ3MgZmllbGRzIGV2ZW50IGhhbmRsZXJzLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge09iamVjdH0gcHJvcHMgQmxvY2sgcHJvcGVydGllcy5cblx0XHQgKlxuXHRcdCAqIEByZXR1cm4ge09iamVjdH0gT2JqZWN0IHRoYXQgY29udGFpbnMgZXZlbnQgaGFuZGxlcnMgZm9yIHRoZSBzZXR0aW5ncyBmaWVsZHMuXG5cdFx0ICovXG5cdFx0Z2V0U2V0dGluZ3NGaWVsZHNIYW5kbGVycyggcHJvcHMgKSB7IC8vIGVzbGludC1kaXNhYmxlLWxpbmUgbWF4LWxpbmVzLXBlci1mdW5jdGlvblxuXHRcdFx0cmV0dXJuIHtcblxuXHRcdFx0XHQvKipcblx0XHRcdFx0ICogRmllbGQgc3R5bGUgYXR0cmlidXRlIGNoYW5nZSBldmVudCBoYW5kbGVyLlxuXHRcdFx0XHQgKlxuXHRcdFx0XHQgKiBAc2luY2UgMS44LjFcblx0XHRcdFx0ICpcblx0XHRcdFx0ICogQHBhcmFtIHtzdHJpbmd9IGF0dHJpYnV0ZSBBdHRyaWJ1dGUgbmFtZS5cblx0XHRcdFx0ICogQHBhcmFtIHtzdHJpbmd9IHZhbHVlICAgICBOZXcgYXR0cmlidXRlIHZhbHVlLlxuXHRcdFx0XHQgKi9cblx0XHRcdFx0c3R5bGVBdHRyQ2hhbmdlKCBhdHRyaWJ1dGUsIHZhbHVlICkge1xuXHRcdFx0XHRcdGNvbnN0IGJsb2NrID0gYXBwLmdldEJsb2NrQ29udGFpbmVyKCBwcm9wcyApLFxuXHRcdFx0XHRcdFx0Y29udGFpbmVyID0gYmxvY2sucXVlcnlTZWxlY3RvciggYCN3cGZvcm1zLSR7IHByb3BzLmF0dHJpYnV0ZXMuZm9ybUlkIH1gICksXG5cdFx0XHRcdFx0XHRwcm9wZXJ0eSA9IGF0dHJpYnV0ZS5yZXBsYWNlKCAvW0EtWl0vZywgKCBsZXR0ZXIgKSA9PiBgLSR7IGxldHRlci50b0xvd2VyQ2FzZSgpIH1gICksXG5cdFx0XHRcdFx0XHRzZXRBdHRyID0ge307XG5cblx0XHRcdFx0XHRpZiAoIGNvbnRhaW5lciApIHtcblx0XHRcdFx0XHRcdHN3aXRjaCAoIHByb3BlcnR5ICkge1xuXHRcdFx0XHRcdFx0XHRjYXNlICdmaWVsZC1zaXplJzpcblx0XHRcdFx0XHRcdFx0Y2FzZSAnbGFiZWwtc2l6ZSc6XG5cdFx0XHRcdFx0XHRcdGNhc2UgJ2J1dHRvbi1zaXplJzpcblx0XHRcdFx0XHRcdFx0XHRmb3IgKCBjb25zdCBrZXkgaW4gc2l6ZXNbIHByb3BlcnR5IF1bIHZhbHVlIF0gKSB7XG5cdFx0XHRcdFx0XHRcdFx0XHRjb250YWluZXIuc3R5bGUuc2V0UHJvcGVydHkoXG5cdFx0XHRcdFx0XHRcdFx0XHRcdGAtLXdwZm9ybXMtJHsgcHJvcGVydHkgfS0keyBrZXkgfWAsXG5cdFx0XHRcdFx0XHRcdFx0XHRcdHNpemVzWyBwcm9wZXJ0eSBdWyB2YWx1ZSBdWyBrZXkgXSxcblx0XHRcdFx0XHRcdFx0XHRcdCk7XG5cdFx0XHRcdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0XHRcdFx0YnJlYWs7XG5cblx0XHRcdFx0XHRcdFx0ZGVmYXVsdDpcblx0XHRcdFx0XHRcdFx0XHRjb250YWluZXIuc3R5bGUuc2V0UHJvcGVydHkoIGAtLXdwZm9ybXMtJHsgcHJvcGVydHkgfWAsIHZhbHVlICk7XG5cdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0c2V0QXR0clsgYXR0cmlidXRlIF0gPSB2YWx1ZTtcblxuXHRcdFx0XHRcdHByb3BzLnNldEF0dHJpYnV0ZXMoIHNldEF0dHIgKTtcblxuXHRcdFx0XHRcdHRyaWdnZXJTZXJ2ZXJSZW5kZXIgPSBmYWxzZTtcblxuXHRcdFx0XHRcdHRoaXMudXBkYXRlQ29weVBhc3RlQ29udGVudCgpO1xuXG5cdFx0XHRcdFx0JCggd2luZG93ICkudHJpZ2dlciggJ3dwZm9ybXNGb3JtU2VsZWN0b3JTdHlsZUF0dHJDaGFuZ2UnLCBbIGJsb2NrLCBwcm9wcywgYXR0cmlidXRlLCB2YWx1ZSBdICk7XG5cdFx0XHRcdH0sXG5cblx0XHRcdFx0LyoqXG5cdFx0XHRcdCAqIEZpZWxkIHJlZ3VsYXIgYXR0cmlidXRlIGNoYW5nZSBldmVudCBoYW5kbGVyLlxuXHRcdFx0XHQgKlxuXHRcdFx0XHQgKiBAc2luY2UgMS44LjFcblx0XHRcdFx0ICpcblx0XHRcdFx0ICogQHBhcmFtIHtzdHJpbmd9IGF0dHJpYnV0ZSBBdHRyaWJ1dGUgbmFtZS5cblx0XHRcdFx0ICogQHBhcmFtIHtzdHJpbmd9IHZhbHVlICAgICBOZXcgYXR0cmlidXRlIHZhbHVlLlxuXHRcdFx0XHQgKi9cblx0XHRcdFx0YXR0ckNoYW5nZSggYXR0cmlidXRlLCB2YWx1ZSApIHtcblx0XHRcdFx0XHRjb25zdCBzZXRBdHRyID0ge307XG5cblx0XHRcdFx0XHRzZXRBdHRyWyBhdHRyaWJ1dGUgXSA9IHZhbHVlO1xuXG5cdFx0XHRcdFx0cHJvcHMuc2V0QXR0cmlidXRlcyggc2V0QXR0ciApO1xuXG5cdFx0XHRcdFx0dHJpZ2dlclNlcnZlclJlbmRlciA9IHRydWU7XG5cblx0XHRcdFx0XHR0aGlzLnVwZGF0ZUNvcHlQYXN0ZUNvbnRlbnQoKTtcblx0XHRcdFx0fSxcblxuXHRcdFx0XHQvKipcblx0XHRcdFx0ICogUmVzZXQgRm9ybSBTdHlsZXMgc2V0dGluZ3MgdG8gZGVmYXVsdHMuXG5cdFx0XHRcdCAqXG5cdFx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0XHQgKi9cblx0XHRcdFx0cmVzZXRTZXR0aW5ncygpIHtcblx0XHRcdFx0XHRmb3IgKCBjb25zdCBrZXkgaW4gZGVmYXVsdFN0eWxlU2V0dGluZ3MgKSB7XG5cdFx0XHRcdFx0XHR0aGlzLnN0eWxlQXR0ckNoYW5nZSgga2V5LCBkZWZhdWx0U3R5bGVTZXR0aW5nc1sga2V5IF0gKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH0sXG5cblx0XHRcdFx0LyoqXG5cdFx0XHRcdCAqIFVwZGF0ZSBjb250ZW50IG9mIHRoZSBcIkNvcHkvUGFzdGVcIiBmaWVsZHMuXG5cdFx0XHRcdCAqXG5cdFx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0XHQgKi9cblx0XHRcdFx0dXBkYXRlQ29weVBhc3RlQ29udGVudCgpIHtcblx0XHRcdFx0XHRjb25zdCBjb250ZW50ID0ge307XG5cdFx0XHRcdFx0Y29uc3QgYXR0cyA9IHdwLmRhdGEuc2VsZWN0KCAnY29yZS9ibG9jay1lZGl0b3InICkuZ2V0QmxvY2tBdHRyaWJ1dGVzKCBwcm9wcy5jbGllbnRJZCApO1xuXG5cdFx0XHRcdFx0Zm9yICggY29uc3Qga2V5IGluIGRlZmF1bHRTdHlsZVNldHRpbmdzICkge1xuXHRcdFx0XHRcdFx0Y29udGVudFsga2V5IF0gPSBhdHRzWyBrZXkgXTtcblx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHRwcm9wcy5zZXRBdHRyaWJ1dGVzKCB7IGNvcHlQYXN0ZUpzb25WYWx1ZTogSlNPTi5zdHJpbmdpZnkoIGNvbnRlbnQgKSB9ICk7XG5cdFx0XHRcdH0sXG5cblx0XHRcdFx0LyoqXG5cdFx0XHRcdCAqIFBhc3RlIHNldHRpbmdzIGhhbmRsZXIuXG5cdFx0XHRcdCAqXG5cdFx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0XHQgKlxuXHRcdFx0XHQgKiBAcGFyYW0ge3N0cmluZ30gdmFsdWUgTmV3IGF0dHJpYnV0ZSB2YWx1ZS5cblx0XHRcdFx0ICovXG5cdFx0XHRcdHBhc3RlU2V0dGluZ3MoIHZhbHVlICkge1xuXHRcdFx0XHRcdGNvbnN0IHBhc3RlQXR0cmlidXRlcyA9IGFwcC5wYXJzZVZhbGlkYXRlSnNvbiggdmFsdWUgKTtcblxuXHRcdFx0XHRcdGlmICggISBwYXN0ZUF0dHJpYnV0ZXMgKSB7XG5cdFx0XHRcdFx0XHR3cC5kYXRhLmRpc3BhdGNoKCAnY29yZS9ub3RpY2VzJyApLmNyZWF0ZUVycm9yTm90aWNlKFxuXHRcdFx0XHRcdFx0XHRzdHJpbmdzLmNvcHlfcGFzdGVfZXJyb3IsXG5cdFx0XHRcdFx0XHRcdHsgaWQ6ICd3cGZvcm1zLWpzb24tcGFyc2UtZXJyb3InIH1cblx0XHRcdFx0XHRcdCk7XG5cblx0XHRcdFx0XHRcdHRoaXMudXBkYXRlQ29weVBhc3RlQ29udGVudCgpO1xuXG5cdFx0XHRcdFx0XHRyZXR1cm47XG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0cGFzdGVBdHRyaWJ1dGVzLmNvcHlQYXN0ZUpzb25WYWx1ZSA9IHZhbHVlO1xuXG5cdFx0XHRcdFx0cHJvcHMuc2V0QXR0cmlidXRlcyggcGFzdGVBdHRyaWJ1dGVzICk7XG5cblx0XHRcdFx0XHR0cmlnZ2VyU2VydmVyUmVuZGVyID0gdHJ1ZTtcblx0XHRcdFx0fSxcblx0XHRcdH07XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIFBhcnNlIGFuZCB2YWxpZGF0ZSBKU09OIHN0cmluZy5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtzdHJpbmd9IHZhbHVlIEpTT04gc3RyaW5nLlxuXHRcdCAqXG5cdFx0ICogQHJldHVybiB7Ym9vbGVhbnxvYmplY3R9IFBhcnNlZCBKU09OIG9iamVjdCBPUiBmYWxzZSBvbiBlcnJvci5cblx0XHQgKi9cblx0XHRwYXJzZVZhbGlkYXRlSnNvbiggdmFsdWUgKSB7XG5cdFx0XHRpZiAoIHR5cGVvZiB2YWx1ZSAhPT0gJ3N0cmluZycgKSB7XG5cdFx0XHRcdHJldHVybiBmYWxzZTtcblx0XHRcdH1cblxuXHRcdFx0bGV0IGF0dHM7XG5cblx0XHRcdHRyeSB7XG5cdFx0XHRcdGF0dHMgPSBKU09OLnBhcnNlKCB2YWx1ZSApO1xuXHRcdFx0fSBjYXRjaCAoIGVycm9yICkge1xuXHRcdFx0XHRhdHRzID0gZmFsc2U7XG5cdFx0XHR9XG5cblx0XHRcdHJldHVybiBhdHRzO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBHZXQgV1BGb3JtcyBpY29uIERPTSBlbGVtZW50LlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJuIHtET00uZWxlbWVudH0gV1BGb3JtcyBpY29uIERPTSBlbGVtZW50LlxuXHRcdCAqL1xuXHRcdGdldEljb24oKSB7XG5cdFx0XHRyZXR1cm4gY3JlYXRlRWxlbWVudChcblx0XHRcdFx0J3N2ZycsXG5cdFx0XHRcdHsgd2lkdGg6IDIwLCBoZWlnaHQ6IDIwLCB2aWV3Qm94OiAnMCAwIDYxMiA2MTInLCBjbGFzc05hbWU6ICdkYXNoaWNvbicgfSxcblx0XHRcdFx0Y3JlYXRlRWxlbWVudChcblx0XHRcdFx0XHQncGF0aCcsXG5cdFx0XHRcdFx0e1xuXHRcdFx0XHRcdFx0ZmlsbDogJ2N1cnJlbnRDb2xvcicsXG5cdFx0XHRcdFx0XHRkOiAnTTU0NCwwSDY4QzMwLjQ0NSwwLDAsMzAuNDQ1LDAsNjh2NDc2YzAsMzcuNTU2LDMwLjQ0NSw2OCw2OCw2OGg0NzZjMzcuNTU2LDAsNjgtMzAuNDQ0LDY4LTY4VjY4IEM2MTIsMzAuNDQ1LDU4MS41NTYsMCw1NDQsMHogTTQ2NC40NCw2OEwzODcuNiwxMjAuMDJMMzIzLjM0LDY4SDQ2NC40NHogTTI4OC42Niw2OGwtNjQuMjYsNTIuMDJMMTQ3LjU2LDY4SDI4OC42NnogTTU0NCw1NDRINjggVjY4aDIyLjFsMTM2LDkyLjE0bDc5LjktNjQuNmw3OS41Niw2NC42bDEzNi05Mi4xNEg1NDRWNTQ0eiBNMTE0LjI0LDI2My4xNmg5NS44OHYtNDguMjhoLTk1Ljg4VjI2My4xNnogTTExNC4yNCwzNjAuNGg5NS44OCB2LTQ4LjYyaC05NS44OFYzNjAuNHogTTI0Mi43NiwzNjAuNGgyNTV2LTQ4LjYyaC0yNTVWMzYwLjRMMjQyLjc2LDM2MC40eiBNMjQyLjc2LDI2My4xNmgyNTV2LTQ4LjI4aC0yNTVWMjYzLjE2TDI0Mi43NiwyNjMuMTZ6IE0zNjguMjIsNDU3LjNoMTI5LjU0VjQwOEgzNjguMjJWNDU3LjN6Jyxcblx0XHRcdFx0XHR9LFxuXHRcdFx0XHQpLFxuXHRcdFx0KTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogR2V0IGJsb2NrIGF0dHJpYnV0ZXMuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKlxuXHRcdCAqIEByZXR1cm4ge09iamVjdH0gQmxvY2sgYXR0cmlidXRlcy5cblx0XHQgKi9cblx0XHRnZXRCbG9ja0F0dHJpYnV0ZXMoKSB7IC8vIGVzbGludC1kaXNhYmxlLWxpbmUgbWF4LWxpbmVzLXBlci1mdW5jdGlvblxuXHRcdFx0cmV0dXJuIHtcblx0XHRcdFx0Y2xpZW50SWQ6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiAnJyxcblx0XHRcdFx0fSxcblx0XHRcdFx0Zm9ybUlkOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMuZm9ybUlkLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRkaXNwbGF5VGl0bGU6IHtcblx0XHRcdFx0XHR0eXBlOiAnYm9vbGVhbicsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMuZGlzcGxheVRpdGxlLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRkaXNwbGF5RGVzYzoge1xuXHRcdFx0XHRcdHR5cGU6ICdib29sZWFuJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5kaXNwbGF5RGVzYyxcblx0XHRcdFx0fSxcblx0XHRcdFx0cHJldmlldzoge1xuXHRcdFx0XHRcdHR5cGU6ICdib29sZWFuJyxcblx0XHRcdFx0fSxcblx0XHRcdFx0ZmllbGRTaXplOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMuZmllbGRTaXplLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRmaWVsZEJvcmRlclJhZGl1czoge1xuXHRcdFx0XHRcdHR5cGU6ICdzdHJpbmcnLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmZpZWxkQm9yZGVyUmFkaXVzLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRmaWVsZEJhY2tncm91bmRDb2xvcjoge1xuXHRcdFx0XHRcdHR5cGU6ICdzdHJpbmcnLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmZpZWxkQmFja2dyb3VuZENvbG9yLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRmaWVsZEJvcmRlckNvbG9yOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMuZmllbGRCb3JkZXJDb2xvcixcblx0XHRcdFx0fSxcblx0XHRcdFx0ZmllbGRUZXh0Q29sb3I6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5maWVsZFRleHRDb2xvcixcblx0XHRcdFx0fSxcblx0XHRcdFx0bGFiZWxTaXplOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMubGFiZWxTaXplLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRsYWJlbENvbG9yOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMubGFiZWxDb2xvcixcblx0XHRcdFx0fSxcblx0XHRcdFx0bGFiZWxTdWJsYWJlbENvbG9yOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMubGFiZWxTdWJsYWJlbENvbG9yLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRsYWJlbEVycm9yQ29sb3I6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5sYWJlbEVycm9yQ29sb3IsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGJ1dHRvblNpemU6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5idXR0b25TaXplLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRidXR0b25Cb3JkZXJSYWRpdXM6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5idXR0b25Cb3JkZXJSYWRpdXMsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGJ1dHRvbkJhY2tncm91bmRDb2xvcjoge1xuXHRcdFx0XHRcdHR5cGU6ICdzdHJpbmcnLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmJ1dHRvbkJhY2tncm91bmRDb2xvcixcblx0XHRcdFx0fSxcblx0XHRcdFx0YnV0dG9uVGV4dENvbG9yOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMuYnV0dG9uVGV4dENvbG9yLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRjb3B5UGFzdGVKc29uVmFsdWU6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5jb3B5UGFzdGVKc29uVmFsdWUsXG5cdFx0XHRcdH0sXG5cdFx0XHR9O1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBHZXQgZm9ybSBzZWxlY3RvciBvcHRpb25zLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJuIHtBcnJheX0gRm9ybSBvcHRpb25zLlxuXHRcdCAqL1xuXHRcdGdldEZvcm1PcHRpb25zKCkge1xuXHRcdFx0Y29uc3QgZm9ybU9wdGlvbnMgPSBmb3JtTGlzdC5tYXAoICggdmFsdWUgKSA9PiAoXG5cdFx0XHRcdHsgdmFsdWU6IHZhbHVlLklELCBsYWJlbDogdmFsdWUucG9zdF90aXRsZSB9XG5cdFx0XHQpICk7XG5cblx0XHRcdGZvcm1PcHRpb25zLnVuc2hpZnQoIHsgdmFsdWU6ICcnLCBsYWJlbDogc3RyaW5ncy5mb3JtX3NlbGVjdCB9ICk7XG5cblx0XHRcdHJldHVybiBmb3JtT3B0aW9ucztcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogR2V0IHNpemUgc2VsZWN0b3Igb3B0aW9ucy5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHJldHVybiB7QXJyYXl9IFNpemUgb3B0aW9ucy5cblx0XHQgKi9cblx0XHRnZXRTaXplT3B0aW9ucygpIHtcblx0XHRcdHJldHVybiBbXG5cdFx0XHRcdHtcblx0XHRcdFx0XHRsYWJlbDogc3RyaW5ncy5zbWFsbCxcblx0XHRcdFx0XHR2YWx1ZTogJ3NtYWxsJyxcblx0XHRcdFx0fSxcblx0XHRcdFx0e1xuXHRcdFx0XHRcdGxhYmVsOiBzdHJpbmdzLm1lZGl1bSxcblx0XHRcdFx0XHR2YWx1ZTogJ21lZGl1bScsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdHtcblx0XHRcdFx0XHRsYWJlbDogc3RyaW5ncy5sYXJnZSxcblx0XHRcdFx0XHR2YWx1ZTogJ2xhcmdlJyxcblx0XHRcdFx0fSxcblx0XHRcdF07XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEV2ZW50IGB3cGZvcm1zRm9ybVNlbGVjdG9yRWRpdGAgaGFuZGxlci5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtPYmplY3R9IGUgICAgIEV2ZW50IG9iamVjdC5cblx0XHQgKiBAcGFyYW0ge09iamVjdH0gcHJvcHMgQmxvY2sgcHJvcGVydGllcy5cblx0XHQgKi9cblx0XHRibG9ja0VkaXQoIGUsIHByb3BzICkge1xuXHRcdFx0Y29uc3QgYmxvY2sgPSBhcHAuZ2V0QmxvY2tDb250YWluZXIoIHByb3BzICk7XG5cblx0XHRcdGlmICggISBibG9jayB8fCAhIGJsb2NrLmRhdGFzZXQgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0YXBwLmluaXRMZWFkRm9ybVNldHRpbmdzKCBibG9jay5wYXJlbnRFbGVtZW50ICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEluaXQgTGVhZCBGb3JtIFNldHRpbmdzIHBhbmVscy5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtFbGVtZW50fSBibG9jayBCbG9jayBlbGVtZW50LlxuXHRcdCAqL1xuXHRcdGluaXRMZWFkRm9ybVNldHRpbmdzKCBibG9jayApIHtcblx0XHRcdGlmICggISBibG9jayB8fCAhIGJsb2NrLmRhdGFzZXQgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0aWYgKCAhIGFwcC5pc0Z1bGxTdHlsaW5nRW5hYmxlZCgpICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdGNvbnN0IGNsaWVudElkID0gYmxvY2suZGF0YXNldC5ibG9jaztcblx0XHRcdGNvbnN0ICRmb3JtID0gJCggYmxvY2sucXVlcnlTZWxlY3RvciggJy53cGZvcm1zLWNvbnRhaW5lcicgKSApO1xuXHRcdFx0Y29uc3QgJHBhbmVsID0gJCggYC53cGZvcm1zLWJsb2NrLXNldHRpbmdzLSR7IGNsaWVudElkIH1gICk7XG5cblx0XHRcdGlmICggJGZvcm0uaGFzQ2xhc3MoICd3cGZvcm1zLWxlYWQtZm9ybXMtY29udGFpbmVyJyApICkge1xuXHRcdFx0XHQkcGFuZWxcblx0XHRcdFx0XHQuYWRkQ2xhc3MoICdkaXNhYmxlZF9wYW5lbCcgKVxuXHRcdFx0XHRcdC5maW5kKCAnLndwZm9ybXMtZ3V0ZW5iZXJnLXBhbmVsLW5vdGljZS53cGZvcm1zLWxlYWQtZm9ybS1ub3RpY2UnIClcblx0XHRcdFx0XHQuY3NzKCAnZGlzcGxheScsICdibG9jaycgKTtcblxuXHRcdFx0XHQkcGFuZWxcblx0XHRcdFx0XHQuZmluZCggJy53cGZvcm1zLWd1dGVuYmVyZy1wYW5lbC1ub3RpY2Uud3Bmb3Jtcy11c2UtbW9kZXJuLW5vdGljZScgKVxuXHRcdFx0XHRcdC5jc3MoICdkaXNwbGF5JywgJ25vbmUnICk7XG5cblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHQkcGFuZWxcblx0XHRcdFx0LnJlbW92ZUNsYXNzKCAnZGlzYWJsZWRfcGFuZWwnIClcblx0XHRcdFx0LmZpbmQoICcud3Bmb3Jtcy1ndXRlbmJlcmctcGFuZWwtbm90aWNlLndwZm9ybXMtbGVhZC1mb3JtLW5vdGljZScgKVxuXHRcdFx0XHQuY3NzKCAnZGlzcGxheScsICdub25lJyApO1xuXG5cdFx0XHQkcGFuZWxcblx0XHRcdFx0LmZpbmQoICcud3Bmb3Jtcy1ndXRlbmJlcmctcGFuZWwtbm90aWNlLndwZm9ybXMtdXNlLW1vZGVybi1ub3RpY2UnIClcblx0XHRcdFx0LmNzcyggJ2Rpc3BsYXknLCBudWxsICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEV2ZW50IGB3cGZvcm1zRm9ybVNlbGVjdG9yRm9ybUxvYWRlZGAgaGFuZGxlci5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtPYmplY3R9IGUgRXZlbnQgb2JqZWN0LlxuXHRcdCAqL1xuXHRcdGZvcm1Mb2FkZWQoIGUgKSB7XG5cdFx0XHRhcHAuaW5pdExlYWRGb3JtU2V0dGluZ3MoIGUuZGV0YWlsLmJsb2NrICk7XG5cdFx0XHRhcHAudXBkYXRlQWNjZW50Q29sb3JzKCBlLmRldGFpbCApO1xuXHRcdFx0YXBwLmxvYWRDaG9pY2VzSlMoIGUuZGV0YWlsICk7XG5cdFx0XHRhcHAuaW5pdFJpY2hUZXh0RmllbGQoIGUuZGV0YWlsLmZvcm1JZCApO1xuXG5cdFx0XHQkKCBlLmRldGFpbC5ibG9jayApXG5cdFx0XHRcdC5vZmYoICdjbGljaycgKVxuXHRcdFx0XHQub24oICdjbGljaycsIGFwcC5ibG9ja0NsaWNrICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIENsaWNrIG9uIHRoZSBibG9jayBldmVudCBoYW5kbGVyLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge09iamVjdH0gZSBFdmVudCBvYmplY3QuXG5cdFx0ICovXG5cdFx0YmxvY2tDbGljayggZSApIHtcblx0XHRcdGFwcC5pbml0TGVhZEZvcm1TZXR0aW5ncyggZS5jdXJyZW50VGFyZ2V0ICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIFVwZGF0ZSBhY2NlbnQgY29sb3JzIG9mIHNvbWUgZmllbGRzIGluIEdCIGJsb2NrIGluIE1vZGVybiBNYXJrdXAgbW9kZS5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtPYmplY3R9IGRldGFpbCBFdmVudCBkZXRhaWxzIG9iamVjdC5cblx0XHQgKi9cblx0XHR1cGRhdGVBY2NlbnRDb2xvcnMoIGRldGFpbCApIHtcblx0XHRcdGlmIChcblx0XHRcdFx0ISB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLmlzX21vZGVybl9tYXJrdXAgfHxcblx0XHRcdFx0ISB3aW5kb3cuV1BGb3JtcyB8fFxuXHRcdFx0XHQhIHdpbmRvdy5XUEZvcm1zLkZyb250ZW5kTW9kZXJuIHx8XG5cdFx0XHRcdCEgZGV0YWlsLmJsb2NrXG5cdFx0XHQpIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRjb25zdCAkZm9ybSA9ICQoIGRldGFpbC5ibG9jay5xdWVyeVNlbGVjdG9yKCBgI3dwZm9ybXMtJHsgZGV0YWlsLmZvcm1JZCB9YCApICksXG5cdFx0XHRcdEZyb250ZW5kTW9kZXJuID0gd2luZG93LldQRm9ybXMuRnJvbnRlbmRNb2Rlcm47XG5cblx0XHRcdEZyb250ZW5kTW9kZXJuLnVwZGF0ZUdCQmxvY2tQYWdlSW5kaWNhdG9yQ29sb3IoICRmb3JtICk7XG5cdFx0XHRGcm9udGVuZE1vZGVybi51cGRhdGVHQkJsb2NrSWNvbkNob2ljZXNDb2xvciggJGZvcm0gKTtcblx0XHRcdEZyb250ZW5kTW9kZXJuLnVwZGF0ZUdCQmxvY2tSYXRpbmdDb2xvciggJGZvcm0gKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogSW5pdCBNb2Rlcm4gc3R5bGUgRHJvcGRvd24gZmllbGRzICg8c2VsZWN0PikuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBkZXRhaWwgRXZlbnQgZGV0YWlscyBvYmplY3QuXG5cdFx0ICovXG5cdFx0bG9hZENob2ljZXNKUyggZGV0YWlsICkge1xuXHRcdFx0aWYgKCB0eXBlb2Ygd2luZG93LkNob2ljZXMgIT09ICdmdW5jdGlvbicgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0Y29uc3QgJGZvcm0gPSAkKCBkZXRhaWwuYmxvY2sucXVlcnlTZWxlY3RvciggYCN3cGZvcm1zLSR7IGRldGFpbC5mb3JtSWQgfWAgKSApO1xuXG5cdFx0XHQkZm9ybS5maW5kKCAnLmNob2ljZXNqcy1zZWxlY3QnICkuZWFjaCggZnVuY3Rpb24oIGlkeCwgZWwgKSB7XG5cdFx0XHRcdGNvbnN0ICRlbCA9ICQoIGVsICk7XG5cblx0XHRcdFx0aWYgKCAkZWwuZGF0YSggJ2Nob2ljZScgKSA9PT0gJ2FjdGl2ZScgKSB7XG5cdFx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0Y29uc3QgYXJncyA9IHdpbmRvdy53cGZvcm1zX2Nob2ljZXNqc19jb25maWcgfHwge30sXG5cdFx0XHRcdFx0c2VhcmNoRW5hYmxlZCA9ICRlbC5kYXRhKCAnc2VhcmNoLWVuYWJsZWQnICksXG5cdFx0XHRcdFx0JGZpZWxkID0gJGVsLmNsb3Nlc3QoICcud3Bmb3Jtcy1maWVsZCcgKTtcblxuXHRcdFx0XHRhcmdzLnNlYXJjaEVuYWJsZWQgPSAndW5kZWZpbmVkJyAhPT0gdHlwZW9mIHNlYXJjaEVuYWJsZWQgPyBzZWFyY2hFbmFibGVkIDogdHJ1ZTtcblx0XHRcdFx0YXJncy5jYWxsYmFja09uSW5pdCA9IGZ1bmN0aW9uKCkge1xuXHRcdFx0XHRcdGNvbnN0IHNlbGYgPSB0aGlzLFxuXHRcdFx0XHRcdFx0JGVsZW1lbnQgPSAkKCBzZWxmLnBhc3NlZEVsZW1lbnQuZWxlbWVudCApLFxuXHRcdFx0XHRcdFx0JGlucHV0ID0gJCggc2VsZi5pbnB1dC5lbGVtZW50ICksXG5cdFx0XHRcdFx0XHRzaXplQ2xhc3MgPSAkZWxlbWVudC5kYXRhKCAnc2l6ZS1jbGFzcycgKTtcblxuXHRcdFx0XHRcdC8vIEFkZCBDU1MtY2xhc3MgZm9yIHNpemUuXG5cdFx0XHRcdFx0aWYgKCBzaXplQ2xhc3MgKSB7XG5cdFx0XHRcdFx0XHQkKCBzZWxmLmNvbnRhaW5lck91dGVyLmVsZW1lbnQgKS5hZGRDbGFzcyggc2l6ZUNsYXNzICk7XG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0LyoqXG5cdFx0XHRcdFx0ICogSWYgYSBtdWx0aXBsZSBzZWxlY3QgaGFzIHNlbGVjdGVkIGNob2ljZXMgLSBoaWRlIGEgcGxhY2Vob2xkZXIgdGV4dC5cblx0XHRcdFx0XHQgKiBJbiBjYXNlIGlmIHNlbGVjdCBpcyBlbXB0eSAtIHdlIHJldHVybiBwbGFjZWhvbGRlciB0ZXh0IGJhY2suXG5cdFx0XHRcdFx0ICovXG5cdFx0XHRcdFx0aWYgKCAkZWxlbWVudC5wcm9wKCAnbXVsdGlwbGUnICkgKSB7XG5cdFx0XHRcdFx0XHQvLyBPbiBpbml0IGV2ZW50LlxuXHRcdFx0XHRcdFx0JGlucHV0LmRhdGEoICdwbGFjZWhvbGRlcicsICRpbnB1dC5hdHRyKCAncGxhY2Vob2xkZXInICkgKTtcblxuXHRcdFx0XHRcdFx0aWYgKCBzZWxmLmdldFZhbHVlKCB0cnVlICkubGVuZ3RoICkge1xuXHRcdFx0XHRcdFx0XHQkaW5wdXQucmVtb3ZlQXR0ciggJ3BsYWNlaG9sZGVyJyApO1xuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdHRoaXMuZGlzYWJsZSgpO1xuXHRcdFx0XHRcdCRmaWVsZC5maW5kKCAnLmlzLWRpc2FibGVkJyApLnJlbW92ZUNsYXNzKCAnaXMtZGlzYWJsZWQnICk7XG5cdFx0XHRcdH07XG5cblx0XHRcdFx0dHJ5IHtcblx0XHRcdFx0XHRjb25zdCBjaG9pY2VzSW5zdGFuY2UgPSBuZXcgQ2hvaWNlcyggZWwsIGFyZ3MgKTtcblxuXHRcdFx0XHRcdC8vIFNhdmUgQ2hvaWNlcy5qcyBpbnN0YW5jZSBmb3IgZnV0dXJlIGFjY2Vzcy5cblx0XHRcdFx0XHQkZWwuZGF0YSggJ2Nob2ljZXNqcycsIGNob2ljZXNJbnN0YW5jZSApO1xuXHRcdFx0XHR9IGNhdGNoICggZSApIHt9IC8vIGVzbGludC1kaXNhYmxlLWxpbmUgbm8tZW1wdHlcblx0XHRcdH0gKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogSW5pdGlhbGl6ZSBSaWNoVGV4dCBmaWVsZC5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtudW1iZXJ9IGZvcm1JZCBGb3JtIElELlxuXHRcdCAqL1xuXHRcdGluaXRSaWNoVGV4dEZpZWxkKCBmb3JtSWQgKSB7XG5cdFx0XHQvLyBTZXQgZGVmYXVsdCB0YWIgdG8gYFZpc3VhbGAuXG5cdFx0XHQkKCBgI3dwZm9ybXMtJHsgZm9ybUlkIH0gLndwLWVkaXRvci13cmFwYCApLnJlbW92ZUNsYXNzKCAnaHRtbC1hY3RpdmUnICkuYWRkQ2xhc3MoICd0bWNlLWFjdGl2ZScgKTtcblx0XHR9LFxuXHR9O1xuXG5cdC8vIFByb3ZpZGUgYWNjZXNzIHRvIHB1YmxpYyBmdW5jdGlvbnMvcHJvcGVydGllcy5cblx0cmV0dXJuIGFwcDtcbn0oIGRvY3VtZW50LCB3aW5kb3csIGpRdWVyeSApICk7XG5cbi8vIEluaXRpYWxpemUuXG5XUEZvcm1zLkZv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
},{}]},{},[1])