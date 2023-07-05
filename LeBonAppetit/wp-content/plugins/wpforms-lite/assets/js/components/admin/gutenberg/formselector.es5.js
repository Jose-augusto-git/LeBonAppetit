(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/* global wpforms_gutenberg_form_selector, Choices */
/* jshint es3: false, esversion: 6 */

'use strict';

/**
 * Gutenberg editor block.
 *
 * @since 1.8.1
 */
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _iterableToArrayLimit(arr, i) { var _i = null == arr ? null : "undefined" != typeof Symbol && arr[Symbol.iterator] || arr["@@iterator"]; if (null != _i) { var _s, _e, _x, _r, _arr = [], _n = !0, _d = !1; try { if (_x = (_i = _i.call(arr)).next, 0 === i) { if (Object(_i) !== _i) return; _n = !1; } else for (; !(_n = (_s = _x.call(_i)).done) && (_arr.push(_s.value), _arr.length !== i); _n = !0); } catch (err) { _d = !0, _e = err; } finally { try { if (!_n && null != _i.return && (_r = _i.return(), Object(_r) !== _r)) return; } finally { if (_d) throw _e; } } return _arr; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
var WPForms = window.WPForms || {};
WPForms.FormSelector = WPForms.FormSelector || function (document, window, $) {
  var _wp = wp,
    _wp$serverSideRender = _wp.serverSideRender,
    ServerSideRender = _wp$serverSideRender === void 0 ? wp.components.ServerSideRender : _wp$serverSideRender;
  var _wp$element = wp.element,
    createElement = _wp$element.createElement,
    Fragment = _wp$element.Fragment,
    useState = _wp$element.useState;
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

  /**
   * Blocks runtime data.
   *
   * @since 1.8.1
   *
   * @type {object}
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
   * Public functions and properties.
   *
   * @since 1.8.1
   *
   * @type {object}
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
     * Register block.
     *
     * @since 1.8.1
     */
    registerBlock: function registerBlock() {
      registerBlockType('wpforms/form-selector', {
        title: strings.title,
        description: strings.description,
        icon: app.getIcon(),
        keywords: strings.form_keywords,
        category: 'widgets',
        attributes: app.getBlockAttributes(),
        example: {
          attributes: {
            preview: true
          }
        },
        edit: function edit(props) {
          var attributes = props.attributes;
          var formOptions = app.getFormOptions();
          var sizeOptions = app.getSizeOptions();
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

          // Form style settings & block content.
          if (attributes.formId) {
            jsx.push(app.jsxParts.getStyleSettings(attributes, handlers, sizeOptions), app.jsxParts.getAdvancedSettings(attributes, handlers), app.jsxParts.getBlockFormContent(props));
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
      ['formId', 'copyPasteValue'].forEach(function (key) {
        return delete defaultStyleSettings[key];
      });
    },
    /**
     * Block JSX parts.
     *
     * @since 1.8.1
     *
     * @type {object}
     */
    jsxParts: {
      /**
       * Get main settings JSX code.
       *
       * @since 1.8.1
       *
       * @param {object} attributes  Block attributes.
       * @param {object} handlers    Block event handlers.
       * @param {object} formOptions Form selector options.
       *
       * @returns {JSX.Element} Main setting JSX code.
       */
      getMainSettings: function getMainSettings(attributes, handlers, formOptions) {
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
       * Get Field styles JSX code.
       *
       * @since 1.8.1
       *
       * @param {object} attributes  Block attributes.
       * @param {object} handlers    Block event handlers.
       * @param {object} sizeOptions Size selector options.
       *
       * @returns {JSX.Element} Field styles JSX code.
       */
      getFieldStyles: function getFieldStyles(attributes, handlers, sizeOptions) {
        // eslint-disable-line max-lines-per-function

        return /*#__PURE__*/React.createElement(PanelBody, {
          className: app.getPanelClass(attributes),
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
          value: attributes.fieldSize,
          options: sizeOptions,
          onChange: function onChange(value) {
            return handlers.styleAttrChange('fieldSize', value);
          }
        })), /*#__PURE__*/React.createElement(FlexBlock, null, /*#__PURE__*/React.createElement(__experimentalUnitControl, {
          label: strings.border_radius,
          value: attributes.fieldBorderRadius,
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
            value: attributes.fieldBackgroundColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('fieldBackgroundColor', value);
            },
            label: strings.background
          }, {
            value: attributes.fieldBorderColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('fieldBorderColor', value);
            },
            label: strings.border
          }, {
            value: attributes.fieldTextColor,
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
       * @param {object} attributes  Block attributes.
       * @param {object} handlers    Block event handlers.
       * @param {object} sizeOptions Size selector options.
       *
       * @returns {JSX.Element} Label styles JSX code.
       */
      getLabelStyles: function getLabelStyles(attributes, handlers, sizeOptions) {
        return /*#__PURE__*/React.createElement(PanelBody, {
          className: app.getPanelClass(attributes),
          title: strings.label_styles
        }, /*#__PURE__*/React.createElement(SelectControl, {
          label: strings.size,
          value: attributes.labelSize,
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
            value: attributes.labelColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('labelColor', value);
            },
            label: strings.label
          }, {
            value: attributes.labelSublabelColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('labelSublabelColor', value);
            },
            label: strings.sublabel_hints.replace('&amp;', '&')
          }, {
            value: attributes.labelErrorColor,
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
       * @param {object} attributes  Block attributes.
       * @param {object} handlers    Block event handlers.
       * @param {object} sizeOptions Size selector options.
       *
       * @returns {JSX.Element}  Button styles JSX code.
       */
      getButtonStyles: function getButtonStyles(attributes, handlers, sizeOptions) {
        return /*#__PURE__*/React.createElement(PanelBody, {
          className: app.getPanelClass(attributes),
          title: strings.button_styles
        }, /*#__PURE__*/React.createElement(Flex, {
          gap: 4,
          align: "flex-start",
          className: 'wpforms-gutenberg-form-selector-flex',
          justify: "space-between"
        }, /*#__PURE__*/React.createElement(FlexBlock, null, /*#__PURE__*/React.createElement(SelectControl, {
          label: strings.size,
          value: attributes.buttonSize,
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
          value: attributes.buttonBorderRadius
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
            value: attributes.buttonBackgroundColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('buttonBackgroundColor', value);
            },
            label: strings.background
          }, {
            value: attributes.buttonTextColor,
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
       * @param {object} attributes  Block attributes.
       * @param {object} handlers    Block event handlers.
       * @param {object} sizeOptions Size selector options.
       *
       * @returns {JSX.Element} Inspector controls JSX code.
       */
      getStyleSettings: function getStyleSettings(attributes, handlers, sizeOptions) {
        return /*#__PURE__*/React.createElement(InspectorControls, {
          key: "wpforms-gutenberg-form-selector-style-settings"
        }, app.jsxParts.getFieldStyles(attributes, handlers, sizeOptions), app.jsxParts.getLabelStyles(attributes, handlers, sizeOptions), app.jsxParts.getButtonStyles(attributes, handlers, sizeOptions));
      },
      /**
       * Get advanced settings JSX code.
       *
       * @since 1.8.1
       *
       * @param {object} attributes Block attributes.
       * @param {object} handlers   Block event handlers.
       *
       * @returns {JSX.Element} Inspector advanced controls JSX code.
       */
      getAdvancedSettings: function getAdvancedSettings(attributes, handlers) {
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
          className: app.getPanelClass(attributes)
        }, /*#__PURE__*/React.createElement(TextareaControl, {
          label: strings.copy_paste_settings,
          rows: "4",
          spellCheck: "false",
          value: attributes.copyPasteValue,
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
       * @param {object} props Block properties.
       *
       * @returns {JSX.Element} Block content JSX code.
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
       * @returns {JSX.Element} Block preview JSX code.
       */
      getBlockPreview: function getBlockPreview() {
        return /*#__PURE__*/React.createElement(Fragment, {
          key: "wpforms-gutenberg-form-selector-fragment-block-preview"
        }, /*#__PURE__*/React.createElement("img", {
          src: wpforms_gutenberg_form_selector.block_preview_url,
          style: {
            width: '100%'
          }
        }));
      },
      /**
       * Get block placeholder (form selector) JSX code.
       *
       * @since 1.8.1
       *
       * @param {object} attributes  Block attributes.
       * @param {object} handlers    Block event handlers.
       * @param {object} formOptions Form selector options.
       *
       * @returns {JSX.Element} Block placeholder JSX code.
       */
      getBlockPlaceholder: function getBlockPlaceholder(attributes, handlers, formOptions) {
        return /*#__PURE__*/React.createElement(Placeholder, {
          key: "wpforms-gutenberg-form-selector-wrap",
          className: "wpforms-gutenberg-form-selector-wrap"
        }, /*#__PURE__*/React.createElement("img", {
          src: wpforms_gutenberg_form_selector.logo_url
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
     * @param {object} attributes Block attributes.
     *
     * @returns {string} Style Settings panel class.
     */
    getPanelClass: function getPanelClass(attributes) {
      var cssClass = 'wpforms-gutenberg-panel wpforms-block-settings-' + attributes.clientId;
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
     * @returns {boolean} Whether the full styling is enabled.
     */
    isFullStylingEnabled: function isFullStylingEnabled() {
      return wpforms_gutenberg_form_selector.is_modern_markup && wpforms_gutenberg_form_selector.is_full_styling;
    },
    /**
     * Get block container DOM element.
     *
     * @since 1.8.1
     *
     * @param {object} props Block properties.
     *
     * @returns {Element} Block container.
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
     * @param {object} props Block properties.
     *
     * @returns {object} Object that contains event handlers for the settings fields.
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
            'copyPasteValue': JSON.stringify(content)
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
          pasteAttributes.copyPasteValue = value;
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
     * @returns {boolean|object} Parsed JSON object OR false on error.
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
     * @returns {DOM.element} WPForms icon DOM element.
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
     * @returns {object} Block attributes.
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
        copyPasteValue: {
          type: 'string',
          default: defaults.copyPasteValue
        }
      };
    },
    /**
     * Get form selector options.
     *
     * @since 1.8.1
     *
     * @returns {Array} Form options.
     */
    getFormOptions: function getFormOptions() {
      var formOptions = wpforms_gutenberg_form_selector.forms.map(function (value) {
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
     * @returns {Array} Size options.
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
     * @param {object} e     Event object.
     * @param {object} props Block properties.
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
     * @param {object} e Event object.
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
     * @param {object} e Event object.
     */
    blockClick: function blockClick(e) {
      app.initLeadFormSettings(e.currentTarget);
    },
    /**
     * Update accent colors of some fields in GB block in Modern Markup mode.
     *
     * @since 1.8.1
     *
     * @param {object} detail Event details object.
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
     * @param {object} detail Event details object.
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
     * @param {int} formId Form ID.
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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJfc2xpY2VkVG9BcnJheSIsImFyciIsImkiLCJfYXJyYXlXaXRoSG9sZXMiLCJfaXRlcmFibGVUb0FycmF5TGltaXQiLCJfdW5zdXBwb3J0ZWRJdGVyYWJsZVRvQXJyYXkiLCJfbm9uSXRlcmFibGVSZXN0IiwiVHlwZUVycm9yIiwibyIsIm1pbkxlbiIsIl9hcnJheUxpa2VUb0FycmF5IiwibiIsIk9iamVjdCIsInByb3RvdHlwZSIsInRvU3RyaW5nIiwiY2FsbCIsInNsaWNlIiwiY29uc3RydWN0b3IiLCJuYW1lIiwiQXJyYXkiLCJmcm9tIiwidGVzdCIsImxlbiIsImxlbmd0aCIsImFycjIiLCJfaSIsIlN5bWJvbCIsIml0ZXJhdG9yIiwiX3MiLCJfZSIsIl94IiwiX3IiLCJfYXJyIiwiX24iLCJfZCIsIm5leHQiLCJkb25lIiwicHVzaCIsInZhbHVlIiwiZXJyIiwicmV0dXJuIiwiaXNBcnJheSIsIldQRm9ybXMiLCJ3aW5kb3ciLCJGb3JtU2VsZWN0b3IiLCJkb2N1bWVudCIsIiQiLCJfd3AiLCJ3cCIsIl93cCRzZXJ2ZXJTaWRlUmVuZGVyIiwic2VydmVyU2lkZVJlbmRlciIsIlNlcnZlclNpZGVSZW5kZXIiLCJjb21wb25lbnRzIiwiX3dwJGVsZW1lbnQiLCJlbGVtZW50IiwiY3JlYXRlRWxlbWVudCIsIkZyYWdtZW50IiwidXNlU3RhdGUiLCJyZWdpc3RlckJsb2NrVHlwZSIsImJsb2NrcyIsIl9yZWYiLCJibG9ja0VkaXRvciIsImVkaXRvciIsIkluc3BlY3RvckNvbnRyb2xzIiwiSW5zcGVjdG9yQWR2YW5jZWRDb250cm9scyIsIlBhbmVsQ29sb3JTZXR0aW5ncyIsIl93cCRjb21wb25lbnRzIiwiU2VsZWN0Q29udHJvbCIsIlRvZ2dsZUNvbnRyb2wiLCJQYW5lbEJvZHkiLCJQbGFjZWhvbGRlciIsIkZsZXgiLCJGbGV4QmxvY2siLCJfX2V4cGVyaW1lbnRhbFVuaXRDb250cm9sIiwiVGV4dGFyZWFDb250cm9sIiwiQnV0dG9uIiwiTW9kYWwiLCJfd3Bmb3Jtc19ndXRlbmJlcmdfZm8iLCJ3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yIiwic3RyaW5ncyIsImRlZmF1bHRzIiwic2l6ZXMiLCJkZWZhdWx0U3R5bGVTZXR0aW5ncyIsInRyaWdnZXJTZXJ2ZXJSZW5kZXIiLCJhcHAiLCJpbml0IiwiaW5pdERlZmF1bHRzIiwicmVnaXN0ZXJCbG9jayIsInJlYWR5IiwiZXZlbnRzIiwib24iLCJfIiwiZGVib3VuY2UiLCJibG9ja0VkaXQiLCJmb3JtTG9hZGVkIiwidGl0bGUiLCJkZXNjcmlwdGlvbiIsImljb24iLCJnZXRJY29uIiwia2V5d29yZHMiLCJmb3JtX2tleXdvcmRzIiwiY2F0ZWdvcnkiLCJhdHRyaWJ1dGVzIiwiZ2V0QmxvY2tBdHRyaWJ1dGVzIiwiZXhhbXBsZSIsInByZXZpZXciLCJlZGl0IiwicHJvcHMiLCJmb3JtT3B0aW9ucyIsImdldEZvcm1PcHRpb25zIiwic2l6ZU9wdGlvbnMiLCJnZXRTaXplT3B0aW9ucyIsImhhbmRsZXJzIiwiZ2V0U2V0dGluZ3NGaWVsZHNIYW5kbGVycyIsImNsaWVudElkIiwic2V0QXR0cmlidXRlcyIsImpzeCIsImpzeFBhcnRzIiwiZ2V0TWFpblNldHRpbmdzIiwiZm9ybUlkIiwiZ2V0U3R5bGVTZXR0aW5ncyIsImdldEFkdmFuY2VkU2V0dGluZ3MiLCJnZXRCbG9ja0Zvcm1Db250ZW50IiwidXBkYXRlQ29weVBhc3RlQ29udGVudCIsInRyaWdnZXIiLCJnZXRCbG9ja1ByZXZpZXciLCJnZXRCbG9ja1BsYWNlaG9sZGVyIiwic2F2ZSIsImZvckVhY2giLCJrZXkiLCJSZWFjdCIsImNsYXNzTmFtZSIsImZvcm1fc2V0dGluZ3MiLCJsYWJlbCIsImZvcm1fc2VsZWN0ZWQiLCJvcHRpb25zIiwib25DaGFuZ2UiLCJhdHRyQ2hhbmdlIiwic2hvd190aXRsZSIsImNoZWNrZWQiLCJkaXNwbGF5VGl0bGUiLCJzaG93X2Rlc2NyaXB0aW9uIiwiZGlzcGxheURlc2MiLCJwYW5lbF9ub3RpY2VfaGVhZCIsInBhbmVsX25vdGljZV90ZXh0IiwiaHJlZiIsInBhbmVsX25vdGljZV9saW5rIiwicmVsIiwidGFyZ2V0IiwicGFuZWxfbm90aWNlX2xpbmtfdGV4dCIsImdldEZpZWxkU3R5bGVzIiwiZ2V0UGFuZWxDbGFzcyIsImZpZWxkX3N0eWxlcyIsInVzZV9tb2Rlcm5fbm90aWNlX2hlYWQiLCJ1c2VfbW9kZXJuX25vdGljZV90ZXh0IiwidXNlX21vZGVybl9ub3RpY2VfbGluayIsImxlYXJuX21vcmUiLCJzdHlsZSIsImRpc3BsYXkiLCJsZWFkX2Zvcm1zX3BhbmVsX25vdGljZV9oZWFkIiwibGVhZF9mb3Jtc19wYW5lbF9ub3RpY2VfdGV4dCIsImdhcCIsImFsaWduIiwianVzdGlmeSIsInNpemUiLCJmaWVsZFNpemUiLCJzdHlsZUF0dHJDaGFuZ2UiLCJib3JkZXJfcmFkaXVzIiwiZmllbGRCb3JkZXJSYWRpdXMiLCJpc1VuaXRTZWxlY3RUYWJiYWJsZSIsImNvbG9ycyIsIl9fZXhwZXJpbWVudGFsSXNSZW5kZXJlZEluU2lkZWJhciIsImVuYWJsZUFscGhhIiwic2hvd1RpdGxlIiwiY29sb3JTZXR0aW5ncyIsImZpZWxkQmFja2dyb3VuZENvbG9yIiwiYmFja2dyb3VuZCIsImZpZWxkQm9yZGVyQ29sb3IiLCJib3JkZXIiLCJmaWVsZFRleHRDb2xvciIsInRleHQiLCJnZXRMYWJlbFN0eWxlcyIsImxhYmVsX3N0eWxlcyIsImxhYmVsU2l6ZSIsImxhYmVsQ29sb3IiLCJsYWJlbFN1YmxhYmVsQ29sb3IiLCJzdWJsYWJlbF9oaW50cyIsInJlcGxhY2UiLCJsYWJlbEVycm9yQ29sb3IiLCJlcnJvcl9tZXNzYWdlIiwiZ2V0QnV0dG9uU3R5bGVzIiwiYnV0dG9uX3N0eWxlcyIsImJ1dHRvblNpemUiLCJidXR0b25Cb3JkZXJSYWRpdXMiLCJidXR0b25CYWNrZ3JvdW5kQ29sb3IiLCJidXR0b25UZXh0Q29sb3IiLCJidXR0b25fY29sb3Jfbm90aWNlIiwiX3VzZVN0YXRlIiwiX3VzZVN0YXRlMiIsImlzT3BlbiIsInNldE9wZW4iLCJvcGVuTW9kYWwiLCJjbG9zZU1vZGFsIiwiY29weV9wYXN0ZV9zZXR0aW5ncyIsInJvd3MiLCJzcGVsbENoZWNrIiwiY29weVBhc3RlVmFsdWUiLCJwYXN0ZVNldHRpbmdzIiwiZGFuZ2Vyb3VzbHlTZXRJbm5lckhUTUwiLCJfX2h0bWwiLCJjb3B5X3Bhc3RlX25vdGljZSIsIm9uQ2xpY2siLCJyZXNldF9zdHlsZV9zZXR0aW5ncyIsIm9uUmVxdWVzdENsb3NlIiwicmVzZXRfc2V0dGluZ3NfY29uZmlybV90ZXh0IiwiaXNTZWNvbmRhcnkiLCJidG5fbm8iLCJpc1ByaW1hcnkiLCJyZXNldFNldHRpbmdzIiwiYnRuX3llc19yZXNldCIsImJsb2NrIiwiZ2V0QmxvY2tDb250YWluZXIiLCJpbm5lckhUTUwiLCJibG9ja0hUTUwiLCJsb2FkZWRGb3JtSWQiLCJzcmMiLCJibG9ja19wcmV2aWV3X3VybCIsIndpZHRoIiwibG9nb191cmwiLCJjc3NDbGFzcyIsImlzRnVsbFN0eWxpbmdFbmFibGVkIiwiaXNfbW9kZXJuX21hcmt1cCIsImlzX2Z1bGxfc3R5bGluZyIsImJsb2NrU2VsZWN0b3IiLCJjb25jYXQiLCJxdWVyeVNlbGVjdG9yIiwiZWRpdG9yQ2FudmFzIiwiY29udGVudFdpbmRvdyIsImF0dHJpYnV0ZSIsImNvbnRhaW5lciIsInByb3BlcnR5IiwibGV0dGVyIiwidG9Mb3dlckNhc2UiLCJzZXRBdHRyIiwic2V0UHJvcGVydHkiLCJjb250ZW50IiwiYXR0cyIsImRhdGEiLCJzZWxlY3QiLCJKU09OIiwic3RyaW5naWZ5IiwicGFzdGVBdHRyaWJ1dGVzIiwicGFyc2VWYWxpZGF0ZUpzb24iLCJkaXNwYXRjaCIsImNyZWF0ZUVycm9yTm90aWNlIiwiY29weV9wYXN0ZV9lcnJvciIsImlkIiwicGFyc2UiLCJlcnJvciIsImhlaWdodCIsInZpZXdCb3giLCJmaWxsIiwiZCIsInR5cGUiLCJkZWZhdWx0IiwiZm9ybXMiLCJtYXAiLCJJRCIsInBvc3RfdGl0bGUiLCJ1bnNoaWZ0IiwiZm9ybV9zZWxlY3QiLCJzbWFsbCIsIm1lZGl1bSIsImxhcmdlIiwiZSIsImRhdGFzZXQiLCJpbml0TGVhZEZvcm1TZXR0aW5ncyIsInBhcmVudEVsZW1lbnQiLCIkZm9ybSIsIiRwYW5lbCIsImhhc0NsYXNzIiwiYWRkQ2xhc3MiLCJmaW5kIiwiY3NzIiwicmVtb3ZlQ2xhc3MiLCJkZXRhaWwiLCJ1cGRhdGVBY2NlbnRDb2xvcnMiLCJsb2FkQ2hvaWNlc0pTIiwiaW5pdFJpY2hUZXh0RmllbGQiLCJvZmYiLCJibG9ja0NsaWNrIiwiY3VycmVudFRhcmdldCIsIkZyb250ZW5kTW9kZXJuIiwidXBkYXRlR0JCbG9ja1BhZ2VJbmRpY2F0b3JDb2xvciIsInVwZGF0ZUdCQmxvY2tJY29uQ2hvaWNlc0NvbG9yIiwidXBkYXRlR0JCbG9ja1JhdGluZ0NvbG9yIiwiQ2hvaWNlcyIsImVhY2giLCJpZHgiLCJlbCIsIiRlbCIsImFyZ3MiLCJ3cGZvcm1zX2Nob2ljZXNqc19jb25maWciLCJzZWFyY2hFbmFibGVkIiwiJGZpZWxkIiwiY2xvc2VzdCIsImNhbGxiYWNrT25Jbml0Iiwic2VsZiIsIiRlbGVtZW50IiwicGFzc2VkRWxlbWVudCIsIiRpbnB1dCIsImlucHV0Iiwic2l6ZUNsYXNzIiwiY29udGFpbmVyT3V0ZXIiLCJwcm9wIiwiYXR0ciIsImdldFZhbHVlIiwicmVtb3ZlQXR0ciIsImRpc2FibGUiLCJjaG9pY2VzSW5zdGFuY2UiLCJqUXVlcnkiXSwic291cmNlcyI6WyJmYWtlXzlkNDdlYWMzLmpzIl0sInNvdXJjZXNDb250ZW50IjpbIi8qIGdsb2JhbCB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLCBDaG9pY2VzICovXG4vKiBqc2hpbnQgZXMzOiBmYWxzZSwgZXN2ZXJzaW9uOiA2ICovXG5cbid1c2Ugc3RyaWN0JztcblxuLyoqXG4gKiBHdXRlbmJlcmcgZWRpdG9yIGJsb2NrLlxuICpcbiAqIEBzaW5jZSAxLjguMVxuICovXG52YXIgV1BGb3JtcyA9IHdpbmRvdy5XUEZvcm1zIHx8IHt9O1xuXG5XUEZvcm1zLkZvcm1TZWxlY3RvciA9IFdQRm9ybXMuRm9ybVNlbGVjdG9yIHx8ICggZnVuY3Rpb24oIGRvY3VtZW50LCB3aW5kb3csICQgKSB7XG5cblx0Y29uc3QgeyBzZXJ2ZXJTaWRlUmVuZGVyOiBTZXJ2ZXJTaWRlUmVuZGVyID0gd3AuY29tcG9uZW50cy5TZXJ2ZXJTaWRlUmVuZGVyIH0gPSB3cDtcblx0Y29uc3QgeyBjcmVhdGVFbGVtZW50LCBGcmFnbWVudCwgdXNlU3RhdGUgfSA9IHdwLmVsZW1lbnQ7XG5cdGNvbnN0IHsgcmVnaXN0ZXJCbG9ja1R5cGUgfSA9IHdwLmJsb2Nrcztcblx0Y29uc3QgeyBJbnNwZWN0b3JDb250cm9scywgSW5zcGVjdG9yQWR2YW5jZWRDb250cm9scywgUGFuZWxDb2xvclNldHRpbmdzIH0gPSB3cC5ibG9ja0VkaXRvciB8fCB3cC5lZGl0b3I7XG5cdGNvbnN0IHsgU2VsZWN0Q29udHJvbCwgVG9nZ2xlQ29udHJvbCwgUGFuZWxCb2R5LCBQbGFjZWhvbGRlciwgRmxleCwgRmxleEJsb2NrLCBfX2V4cGVyaW1lbnRhbFVuaXRDb250cm9sLCBUZXh0YXJlYUNvbnRyb2wsIEJ1dHRvbiwgTW9kYWwgfSA9IHdwLmNvbXBvbmVudHM7XG5cdGNvbnN0IHsgc3RyaW5ncywgZGVmYXVsdHMsIHNpemVzIH0gPSB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yO1xuXHRjb25zdCBkZWZhdWx0U3R5bGVTZXR0aW5ncyA9IGRlZmF1bHRzO1xuXG5cdC8qKlxuXHQgKiBCbG9ja3MgcnVudGltZSBkYXRhLlxuXHQgKlxuXHQgKiBAc2luY2UgMS44LjFcblx0ICpcblx0ICogQHR5cGUge29iamVjdH1cblx0ICovXG5cdGxldCBibG9ja3MgPSB7fTtcblxuXHQvKipcblx0ICogV2hldGhlciBpdCBpcyBuZWVkZWQgdG8gdHJpZ2dlciBzZXJ2ZXIgcmVuZGVyaW5nLlxuXHQgKlxuXHQgKiBAc2luY2UgMS44LjFcblx0ICpcblx0ICogQHR5cGUge2Jvb2xlYW59XG5cdCAqL1xuXHRsZXQgdHJpZ2dlclNlcnZlclJlbmRlciA9IHRydWU7XG5cblx0LyoqXG5cdCAqIFB1YmxpYyBmdW5jdGlvbnMgYW5kIHByb3BlcnRpZXMuXG5cdCAqXG5cdCAqIEBzaW5jZSAxLjguMVxuXHQgKlxuXHQgKiBAdHlwZSB7b2JqZWN0fVxuXHQgKi9cblx0Y29uc3QgYXBwID0ge1xuXG5cdFx0LyoqXG5cdFx0ICogU3RhcnQgdGhlIGVuZ2luZS5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqL1xuXHRcdGluaXQ6IGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRhcHAuaW5pdERlZmF1bHRzKCk7XG5cdFx0XHRhcHAucmVnaXN0ZXJCbG9jaygpO1xuXG5cdFx0XHQkKCBhcHAucmVhZHkgKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogRG9jdW1lbnQgcmVhZHkuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKi9cblx0XHRyZWFkeTogZnVuY3Rpb24oKSB7XG5cblx0XHRcdGFwcC5ldmVudHMoKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogRXZlbnRzLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICovXG5cdFx0ZXZlbnRzOiBmdW5jdGlvbigpIHtcblxuXHRcdFx0JCggd2luZG93IClcblx0XHRcdFx0Lm9uKCAnd3Bmb3Jtc0Zvcm1TZWxlY3RvckVkaXQnLCBfLmRlYm91bmNlKCBhcHAuYmxvY2tFZGl0LCAyNTAgKSApXG5cdFx0XHRcdC5vbiggJ3dwZm9ybXNGb3JtU2VsZWN0b3JGb3JtTG9hZGVkJywgXy5kZWJvdW5jZSggYXBwLmZvcm1Mb2FkZWQsIDI1MCApICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIFJlZ2lzdGVyIGJsb2NrLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICovXG5cdFx0cmVnaXN0ZXJCbG9jazogZnVuY3Rpb24oKSB7XG5cblx0XHRcdHJlZ2lzdGVyQmxvY2tUeXBlKCAnd3Bmb3Jtcy9mb3JtLXNlbGVjdG9yJywge1xuXHRcdFx0XHR0aXRsZTogc3RyaW5ncy50aXRsZSxcblx0XHRcdFx0ZGVzY3JpcHRpb246IHN0cmluZ3MuZGVzY3JpcHRpb24sXG5cdFx0XHRcdGljb246IGFwcC5nZXRJY29uKCksXG5cdFx0XHRcdGtleXdvcmRzOiBzdHJpbmdzLmZvcm1fa2V5d29yZHMsXG5cdFx0XHRcdGNhdGVnb3J5OiAnd2lkZ2V0cycsXG5cdFx0XHRcdGF0dHJpYnV0ZXM6IGFwcC5nZXRCbG9ja0F0dHJpYnV0ZXMoKSxcblx0XHRcdFx0ZXhhbXBsZToge1xuXHRcdFx0XHRcdGF0dHJpYnV0ZXM6IHtcblx0XHRcdFx0XHRcdHByZXZpZXc6IHRydWUsXG5cdFx0XHRcdFx0fSxcblx0XHRcdFx0fSxcblx0XHRcdFx0ZWRpdDogZnVuY3Rpb24oIHByb3BzICkge1xuXG5cdFx0XHRcdFx0Y29uc3QgeyBhdHRyaWJ1dGVzIH0gPSBwcm9wcztcblx0XHRcdFx0XHRjb25zdCBmb3JtT3B0aW9ucyA9IGFwcC5nZXRGb3JtT3B0aW9ucygpO1xuXHRcdFx0XHRcdGNvbnN0IHNpemVPcHRpb25zID0gYXBwLmdldFNpemVPcHRpb25zKCk7XG5cdFx0XHRcdFx0Y29uc3QgaGFuZGxlcnMgPSBhcHAuZ2V0U2V0dGluZ3NGaWVsZHNIYW5kbGVycyggcHJvcHMgKTtcblxuXHRcdFx0XHRcdC8vIFN0b3JlIGJsb2NrIGNsaWVudElkIGluIGF0dHJpYnV0ZXMuXG5cdFx0XHRcdFx0aWYgKCAhIGF0dHJpYnV0ZXMuY2xpZW50SWQgKSB7XG5cblx0XHRcdFx0XHRcdC8vIFdlIGp1c3Qgd2FudCBjbGllbnQgSUQgdG8gdXBkYXRlIG9uY2UuXG5cdFx0XHRcdFx0XHQvLyBUaGUgYmxvY2sgZWRpdG9yIGRvZXNuJ3QgaGF2ZSBhIGZpeGVkIGJsb2NrIElELCBzbyB3ZSBuZWVkIHRvIGdldCBpdCBvbiB0aGUgaW5pdGlhbCBsb2FkLCBidXQgb25seSBvbmNlLlxuXHRcdFx0XHRcdFx0cHJvcHMuc2V0QXR0cmlidXRlcyggeyBjbGllbnRJZDogcHJvcHMuY2xpZW50SWQgfSApO1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdC8vIE1haW4gYmxvY2sgc2V0dGluZ3MuXG5cdFx0XHRcdFx0bGV0IGpzeCA9IFtcblx0XHRcdFx0XHRcdGFwcC5qc3hQYXJ0cy5nZXRNYWluU2V0dGluZ3MoIGF0dHJpYnV0ZXMsIGhhbmRsZXJzLCBmb3JtT3B0aW9ucyApLFxuXHRcdFx0XHRcdF07XG5cblx0XHRcdFx0XHQvLyBGb3JtIHN0eWxlIHNldHRpbmdzICYgYmxvY2sgY29udGVudC5cblx0XHRcdFx0XHRpZiAoIGF0dHJpYnV0ZXMuZm9ybUlkICkge1xuXHRcdFx0XHRcdFx0anN4LnB1c2goXG5cdFx0XHRcdFx0XHRcdGFwcC5qc3hQYXJ0cy5nZXRTdHlsZVNldHRpbmdzKCBhdHRyaWJ1dGVzLCBoYW5kbGVycywgc2l6ZU9wdGlvbnMgKSxcblx0XHRcdFx0XHRcdFx0YXBwLmpzeFBhcnRzLmdldEFkdmFuY2VkU2V0dGluZ3MoIGF0dHJpYnV0ZXMsIGhhbmRsZXJzICksXG5cdFx0XHRcdFx0XHRcdGFwcC5qc3hQYXJ0cy5nZXRCbG9ja0Zvcm1Db250ZW50KCBwcm9wcyApLFxuXHRcdFx0XHRcdFx0KTtcblxuXHRcdFx0XHRcdFx0aGFuZGxlcnMudXBkYXRlQ29weVBhc3RlQ29udGVudCgpO1xuXG5cdFx0XHRcdFx0XHQkKCB3aW5kb3cgKS50cmlnZ2VyKCAnd3Bmb3Jtc0Zvcm1TZWxlY3RvckVkaXQnLCBbIHByb3BzIF0gKTtcblxuXHRcdFx0XHRcdFx0cmV0dXJuIGpzeDtcblx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHQvLyBCbG9jayBwcmV2aWV3IHBpY3R1cmUuXG5cdFx0XHRcdFx0aWYgKCBhdHRyaWJ1dGVzLnByZXZpZXcgKSB7XG5cdFx0XHRcdFx0XHRqc3gucHVzaChcblx0XHRcdFx0XHRcdFx0YXBwLmpzeFBhcnRzLmdldEJsb2NrUHJldmlldygpLFxuXHRcdFx0XHRcdFx0KTtcblxuXHRcdFx0XHRcdFx0cmV0dXJuIGpzeDtcblx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHQvLyBCbG9jayBwbGFjZWhvbGRlciAoZm9ybSBzZWxlY3RvcikuXG5cdFx0XHRcdFx0anN4LnB1c2goXG5cdFx0XHRcdFx0XHRhcHAuanN4UGFydHMuZ2V0QmxvY2tQbGFjZWhvbGRlciggcHJvcHMuYXR0cmlidXRlcywgaGFuZGxlcnMsIGZvcm1PcHRpb25zICksXG5cdFx0XHRcdFx0KTtcblxuXHRcdFx0XHRcdHJldHVybiBqc3g7XG5cdFx0XHRcdH0sXG5cdFx0XHRcdHNhdmU6ICgpID0+IG51bGwsXG5cdFx0XHR9ICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEluaXQgZGVmYXVsdCBzdHlsZSBzZXR0aW5ncy5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqL1xuXHRcdGluaXREZWZhdWx0czogZnVuY3Rpb24oKSB7XG5cblx0XHRcdFsgJ2Zvcm1JZCcsICdjb3B5UGFzdGVWYWx1ZScgXS5mb3JFYWNoKCBrZXkgPT4gZGVsZXRlIGRlZmF1bHRTdHlsZVNldHRpbmdzWyBrZXkgXSApO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBCbG9jayBKU1ggcGFydHMuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKlxuXHRcdCAqIEB0eXBlIHtvYmplY3R9XG5cdFx0ICovXG5cdFx0anN4UGFydHM6IHtcblxuXHRcdFx0LyoqXG5cdFx0XHQgKiBHZXQgbWFpbiBzZXR0aW5ncyBKU1ggY29kZS5cblx0XHRcdCAqXG5cdFx0XHQgKiBAc2luY2UgMS44LjFcblx0XHRcdCAqXG5cdFx0XHQgKiBAcGFyYW0ge29iamVjdH0gYXR0cmlidXRlcyAgQmxvY2sgYXR0cmlidXRlcy5cblx0XHRcdCAqIEBwYXJhbSB7b2JqZWN0fSBoYW5kbGVycyAgICBCbG9jayBldmVudCBoYW5kbGVycy5cblx0XHRcdCAqIEBwYXJhbSB7b2JqZWN0fSBmb3JtT3B0aW9ucyBGb3JtIHNlbGVjdG9yIG9wdGlvbnMuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHJldHVybnMge0pTWC5FbGVtZW50fSBNYWluIHNldHRpbmcgSlNYIGNvZGUuXG5cdFx0XHQgKi9cblx0XHRcdGdldE1haW5TZXR0aW5nczogZnVuY3Rpb24oIGF0dHJpYnV0ZXMsIGhhbmRsZXJzLCBmb3JtT3B0aW9ucyApIHtcblxuXHRcdFx0XHRyZXR1cm4gKFxuXHRcdFx0XHRcdDxJbnNwZWN0b3JDb250cm9scyBrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWluc3BlY3Rvci1tYWluLXNldHRpbmdzXCI+XG5cdFx0XHRcdFx0XHQ8UGFuZWxCb2R5IGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLXBhbmVsXCIgdGl0bGU9eyBzdHJpbmdzLmZvcm1fc2V0dGluZ3MgfT5cblx0XHRcdFx0XHRcdFx0PFNlbGVjdENvbnRyb2xcblx0XHRcdFx0XHRcdFx0XHRsYWJlbD17IHN0cmluZ3MuZm9ybV9zZWxlY3RlZCB9XG5cdFx0XHRcdFx0XHRcdFx0dmFsdWU9eyBhdHRyaWJ1dGVzLmZvcm1JZCB9XG5cdFx0XHRcdFx0XHRcdFx0b3B0aW9ucz17IGZvcm1PcHRpb25zIH1cblx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZT17IHZhbHVlID0+IGhhbmRsZXJzLmF0dHJDaGFuZ2UoICdmb3JtSWQnLCB2YWx1ZSApIH1cblx0XHRcdFx0XHRcdFx0Lz5cblx0XHRcdFx0XHRcdFx0PFRvZ2dsZUNvbnRyb2xcblx0XHRcdFx0XHRcdFx0XHRsYWJlbD17IHN0cmluZ3Muc2hvd190aXRsZSB9XG5cdFx0XHRcdFx0XHRcdFx0Y2hlY2tlZD17IGF0dHJpYnV0ZXMuZGlzcGxheVRpdGxlIH1cblx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZT17IHZhbHVlID0+IGhhbmRsZXJzLmF0dHJDaGFuZ2UoICdkaXNwbGF5VGl0bGUnLCB2YWx1ZSApIH1cblx0XHRcdFx0XHRcdFx0Lz5cblx0XHRcdFx0XHRcdFx0PFRvZ2dsZUNvbnRyb2xcblx0XHRcdFx0XHRcdFx0XHRsYWJlbD17IHN0cmluZ3Muc2hvd19kZXNjcmlwdGlvbiB9XG5cdFx0XHRcdFx0XHRcdFx0Y2hlY2tlZD17IGF0dHJpYnV0ZXMuZGlzcGxheURlc2MgfVxuXHRcdFx0XHRcdFx0XHRcdG9uQ2hhbmdlPXsgdmFsdWUgPT4gaGFuZGxlcnMuYXR0ckNoYW5nZSggJ2Rpc3BsYXlEZXNjJywgdmFsdWUgKSB9XG5cdFx0XHRcdFx0XHRcdC8+XG5cdFx0XHRcdFx0XHRcdDxwIGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLXBhbmVsLW5vdGljZVwiPlxuXHRcdFx0XHRcdFx0XHRcdDxzdHJvbmc+eyBzdHJpbmdzLnBhbmVsX25vdGljZV9oZWFkIH08L3N0cm9uZz5cblx0XHRcdFx0XHRcdFx0XHR7IHN0cmluZ3MucGFuZWxfbm90aWNlX3RleHQgfVxuXHRcdFx0XHRcdFx0XHRcdDxhIGhyZWY9e3N0cmluZ3MucGFuZWxfbm90aWNlX2xpbmt9IHJlbD1cIm5vcmVmZXJyZXJcIiB0YXJnZXQ9XCJfYmxhbmtcIj57IHN0cmluZ3MucGFuZWxfbm90aWNlX2xpbmtfdGV4dCB9PC9hPlxuXHRcdFx0XHRcdFx0XHQ8L3A+XG5cdFx0XHRcdFx0XHQ8L1BhbmVsQm9keT5cblx0XHRcdFx0XHQ8L0luc3BlY3RvckNvbnRyb2xzPlxuXHRcdFx0XHQpO1xuXHRcdFx0fSxcblxuXHRcdFx0LyoqXG5cdFx0XHQgKiBHZXQgRmllbGQgc3R5bGVzIEpTWCBjb2RlLlxuXHRcdFx0ICpcblx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0ICpcblx0XHRcdCAqIEBwYXJhbSB7b2JqZWN0fSBhdHRyaWJ1dGVzICBCbG9jayBhdHRyaWJ1dGVzLlxuXHRcdFx0ICogQHBhcmFtIHtvYmplY3R9IGhhbmRsZXJzICAgIEJsb2NrIGV2ZW50IGhhbmRsZXJzLlxuXHRcdFx0ICogQHBhcmFtIHtvYmplY3R9IHNpemVPcHRpb25zIFNpemUgc2VsZWN0b3Igb3B0aW9ucy5cblx0XHRcdCAqXG5cdFx0XHQgKiBAcmV0dXJucyB7SlNYLkVsZW1lbnR9IEZpZWxkIHN0eWxlcyBKU1ggY29kZS5cblx0XHRcdCAqL1xuXHRcdFx0Z2V0RmllbGRTdHlsZXM6IGZ1bmN0aW9uKCBhdHRyaWJ1dGVzLCBoYW5kbGVycywgc2l6ZU9wdGlvbnMgKSB7IC8vIGVzbGludC1kaXNhYmxlLWxpbmUgbWF4LWxpbmVzLXBlci1mdW5jdGlvblxuXG5cdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0PFBhbmVsQm9keSBjbGFzc05hbWU9eyBhcHAuZ2V0UGFuZWxDbGFzcyggYXR0cmlidXRlcyApIH0gdGl0bGU9eyBzdHJpbmdzLmZpZWxkX3N0eWxlcyB9PlxuXHRcdFx0XHRcdFx0PHAgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctcGFuZWwtbm90aWNlIHdwZm9ybXMtdXNlLW1vZGVybi1ub3RpY2VcIj5cblx0XHRcdFx0XHRcdFx0PHN0cm9uZz57IHN0cmluZ3MudXNlX21vZGVybl9ub3RpY2VfaGVhZCB9PC9zdHJvbmc+XG5cdFx0XHRcdFx0XHRcdHsgc3RyaW5ncy51c2VfbW9kZXJuX25vdGljZV90ZXh0IH0gPGEgaHJlZj17c3RyaW5ncy51c2VfbW9kZXJuX25vdGljZV9saW5rfSByZWw9XCJub3JlZmVycmVyXCIgdGFyZ2V0PVwiX2JsYW5rXCI+eyBzdHJpbmdzLmxlYXJuX21vcmUgfTwvYT5cblx0XHRcdFx0XHRcdDwvcD5cblxuXHRcdFx0XHRcdFx0PHAgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctcGFuZWwtbm90aWNlIHdwZm9ybXMtd2FybmluZyB3cGZvcm1zLWxlYWQtZm9ybS1ub3RpY2VcIiBzdHlsZT17eyBkaXNwbGF5OiAnbm9uZScgfX0+XG5cdFx0XHRcdFx0XHRcdDxzdHJvbmc+eyBzdHJpbmdzLmxlYWRfZm9ybXNfcGFuZWxfbm90aWNlX2hlYWQgfTwvc3Ryb25nPlxuXHRcdFx0XHRcdFx0XHR7IHN0cmluZ3MubGVhZF9mb3Jtc19wYW5lbF9ub3RpY2VfdGV4dCB9XG5cdFx0XHRcdFx0XHQ8L3A+XG5cblx0XHRcdFx0XHRcdDxGbGV4IGdhcD17NH0gYWxpZ249XCJmbGV4LXN0YXJ0XCIgY2xhc3NOYW1lPXsnd3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1mbGV4J30ganVzdGlmeT1cInNwYWNlLWJldHdlZW5cIj5cblx0XHRcdFx0XHRcdFx0PEZsZXhCbG9jaz5cblx0XHRcdFx0XHRcdFx0XHQ8U2VsZWN0Q29udHJvbFxuXHRcdFx0XHRcdFx0XHRcdFx0bGFiZWw9eyBzdHJpbmdzLnNpemUgfVxuXHRcdFx0XHRcdFx0XHRcdFx0dmFsdWU9eyBhdHRyaWJ1dGVzLmZpZWxkU2l6ZSB9XG5cdFx0XHRcdFx0XHRcdFx0XHRvcHRpb25zPXsgc2l6ZU9wdGlvbnMgfVxuXHRcdFx0XHRcdFx0XHRcdFx0b25DaGFuZ2U9eyB2YWx1ZSA9PiBoYW5kbGVycy5zdHlsZUF0dHJDaGFuZ2UoICdmaWVsZFNpemUnLCB2YWx1ZSApIH1cblx0XHRcdFx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdFx0XHQ8L0ZsZXhCbG9jaz5cblx0XHRcdFx0XHRcdFx0PEZsZXhCbG9jaz5cblx0XHRcdFx0XHRcdFx0XHQ8X19leHBlcmltZW50YWxVbml0Q29udHJvbFxuXHRcdFx0XHRcdFx0XHRcdFx0bGFiZWw9eyBzdHJpbmdzLmJvcmRlcl9yYWRpdXMgfVxuXHRcdFx0XHRcdFx0XHRcdFx0dmFsdWU9eyBhdHRyaWJ1dGVzLmZpZWxkQm9yZGVyUmFkaXVzIH1cblx0XHRcdFx0XHRcdFx0XHRcdGlzVW5pdFNlbGVjdFRhYmJhYmxlXG5cdFx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZT17IHZhbHVlID0+IGhhbmRsZXJzLnN0eWxlQXR0ckNoYW5nZSggJ2ZpZWxkQm9yZGVyUmFkaXVzJywgdmFsdWUgKSB9XG5cdFx0XHRcdFx0XHRcdFx0Lz5cblx0XHRcdFx0XHRcdFx0PC9GbGV4QmxvY2s+XG5cdFx0XHRcdFx0XHQ8L0ZsZXg+XG5cblx0XHRcdFx0XHRcdDxkaXYgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1jb2xvci1waWNrZXJcIj5cblx0XHRcdFx0XHRcdFx0PGRpdiBjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWNvbnRyb2wtbGFiZWxcIj57IHN0cmluZ3MuY29sb3JzIH08L2Rpdj5cblx0XHRcdFx0XHRcdFx0PFBhbmVsQ29sb3JTZXR0aW5nc1xuXHRcdFx0XHRcdFx0XHRcdF9fZXhwZXJpbWVudGFsSXNSZW5kZXJlZEluU2lkZWJhclxuXHRcdFx0XHRcdFx0XHRcdGVuYWJsZUFscGhhXG5cdFx0XHRcdFx0XHRcdFx0c2hvd1RpdGxlPXsgZmFsc2UgfVxuXHRcdFx0XHRcdFx0XHRcdGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItY29sb3ItcGFuZWxcIlxuXHRcdFx0XHRcdFx0XHRcdGNvbG9yU2V0dGluZ3M9e1tcblx0XHRcdFx0XHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0dmFsdWU6IGF0dHJpYnV0ZXMuZmllbGRCYWNrZ3JvdW5kQ29sb3IsXG5cdFx0XHRcdFx0XHRcdFx0XHRcdG9uQ2hhbmdlOiB2YWx1ZSA9PiBoYW5kbGVycy5zdHlsZUF0dHJDaGFuZ2UoICdmaWVsZEJhY2tncm91bmRDb2xvcicsIHZhbHVlICksXG5cdFx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsOiBzdHJpbmdzLmJhY2tncm91bmQsXG5cdFx0XHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XHRcdFx0e1xuXHRcdFx0XHRcdFx0XHRcdFx0XHR2YWx1ZTogYXR0cmlidXRlcy5maWVsZEJvcmRlckNvbG9yLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZTogdmFsdWUgPT4gaGFuZGxlcnMuc3R5bGVBdHRyQ2hhbmdlKCAnZmllbGRCb3JkZXJDb2xvcicsIHZhbHVlICksXG5cdFx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsOiBzdHJpbmdzLmJvcmRlcixcblx0XHRcdFx0XHRcdFx0XHRcdH0sXG5cdFx0XHRcdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdHZhbHVlOiBhdHRyaWJ1dGVzLmZpZWxkVGV4dENvbG9yLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZTogdmFsdWUgPT4gaGFuZGxlcnMuc3R5bGVBdHRyQ2hhbmdlKCAnZmllbGRUZXh0Q29sb3InLCB2YWx1ZSApLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRsYWJlbDogc3RyaW5ncy50ZXh0LFxuXHRcdFx0XHRcdFx0XHRcdFx0fSxcblx0XHRcdFx0XHRcdFx0XHRdfVxuXHRcdFx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdFx0PC9kaXY+XG5cdFx0XHRcdFx0PC9QYW5lbEJvZHk+XG5cdFx0XHRcdCk7XG5cdFx0XHR9LFxuXG5cdFx0XHQvKipcblx0XHRcdCAqIEdldCBMYWJlbCBzdHlsZXMgSlNYIGNvZGUuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0XHQgKlxuXHRcdFx0ICogQHBhcmFtIHtvYmplY3R9IGF0dHJpYnV0ZXMgIEJsb2NrIGF0dHJpYnV0ZXMuXG5cdFx0XHQgKiBAcGFyYW0ge29iamVjdH0gaGFuZGxlcnMgICAgQmxvY2sgZXZlbnQgaGFuZGxlcnMuXG5cdFx0XHQgKiBAcGFyYW0ge29iamVjdH0gc2l6ZU9wdGlvbnMgU2l6ZSBzZWxlY3RvciBvcHRpb25zLlxuXHRcdFx0ICpcblx0XHRcdCAqIEByZXR1cm5zIHtKU1guRWxlbWVudH0gTGFiZWwgc3R5bGVzIEpTWCBjb2RlLlxuXHRcdFx0ICovXG5cdFx0XHRnZXRMYWJlbFN0eWxlczogZnVuY3Rpb24oIGF0dHJpYnV0ZXMsIGhhbmRsZXJzLCBzaXplT3B0aW9ucyApIHtcblxuXHRcdFx0XHRyZXR1cm4gKFxuXHRcdFx0XHRcdDxQYW5lbEJvZHkgY2xhc3NOYW1lPXsgYXBwLmdldFBhbmVsQ2xhc3MoIGF0dHJpYnV0ZXMgKSB9IHRpdGxlPXsgc3RyaW5ncy5sYWJlbF9zdHlsZXMgfT5cblx0XHRcdFx0XHRcdDxTZWxlY3RDb250cm9sXG5cdFx0XHRcdFx0XHRcdGxhYmVsPXsgc3RyaW5ncy5zaXplIH1cblx0XHRcdFx0XHRcdFx0dmFsdWU9eyBhdHRyaWJ1dGVzLmxhYmVsU2l6ZSB9XG5cdFx0XHRcdFx0XHRcdGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItZml4LWJvdHRvbS1tYXJnaW5cIlxuXHRcdFx0XHRcdFx0XHRvcHRpb25zPXsgc2l6ZU9wdGlvbnN9XG5cdFx0XHRcdFx0XHRcdG9uQ2hhbmdlPXsgdmFsdWUgPT4gaGFuZGxlcnMuc3R5bGVBdHRyQ2hhbmdlKCAnbGFiZWxTaXplJywgdmFsdWUgKSB9XG5cdFx0XHRcdFx0XHQvPlxuXG5cdFx0XHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItY29sb3ItcGlja2VyXCI+XG5cdFx0XHRcdFx0XHRcdDxkaXYgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1jb250cm9sLWxhYmVsXCI+eyBzdHJpbmdzLmNvbG9ycyB9PC9kaXY+XG5cdFx0XHRcdFx0XHRcdDxQYW5lbENvbG9yU2V0dGluZ3Ncblx0XHRcdFx0XHRcdFx0XHRfX2V4cGVyaW1lbnRhbElzUmVuZGVyZWRJblNpZGViYXJcblx0XHRcdFx0XHRcdFx0XHRlbmFibGVBbHBoYVxuXHRcdFx0XHRcdFx0XHRcdHNob3dUaXRsZT17IGZhbHNlIH1cblx0XHRcdFx0XHRcdFx0XHRjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWNvbG9yLXBhbmVsXCJcblx0XHRcdFx0XHRcdFx0XHRjb2xvclNldHRpbmdzPXtbXG5cdFx0XHRcdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdHZhbHVlOiBhdHRyaWJ1dGVzLmxhYmVsQ29sb3IsXG5cdFx0XHRcdFx0XHRcdFx0XHRcdG9uQ2hhbmdlOiB2YWx1ZSA9PiBoYW5kbGVycy5zdHlsZUF0dHJDaGFuZ2UoICdsYWJlbENvbG9yJywgdmFsdWUgKSxcblx0XHRcdFx0XHRcdFx0XHRcdFx0bGFiZWw6IHN0cmluZ3MubGFiZWwsXG5cdFx0XHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XHRcdFx0e1xuXHRcdFx0XHRcdFx0XHRcdFx0XHR2YWx1ZTogYXR0cmlidXRlcy5sYWJlbFN1YmxhYmVsQ29sb3IsXG5cdFx0XHRcdFx0XHRcdFx0XHRcdG9uQ2hhbmdlOiB2YWx1ZSA9PiBoYW5kbGVycy5zdHlsZUF0dHJDaGFuZ2UoICdsYWJlbFN1YmxhYmVsQ29sb3InLCB2YWx1ZSApLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRsYWJlbDogc3RyaW5ncy5zdWJsYWJlbF9oaW50cy5yZXBsYWNlKCAnJmFtcDsnLCAnJicgKSxcblx0XHRcdFx0XHRcdFx0XHRcdH0sXG5cdFx0XHRcdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdHZhbHVlOiBhdHRyaWJ1dGVzLmxhYmVsRXJyb3JDb2xvcixcblx0XHRcdFx0XHRcdFx0XHRcdFx0b25DaGFuZ2U6IHZhbHVlID0+IGhhbmRsZXJzLnN0eWxlQXR0ckNoYW5nZSggJ2xhYmVsRXJyb3JDb2xvcicsIHZhbHVlICksXG5cdFx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsOiBzdHJpbmdzLmVycm9yX21lc3NhZ2UsXG5cdFx0XHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XHRcdF19XG5cdFx0XHRcdFx0XHRcdC8+XG5cdFx0XHRcdFx0XHQ8L2Rpdj5cblx0XHRcdFx0XHQ8L1BhbmVsQm9keT5cblx0XHRcdFx0KTtcblx0XHRcdH0sXG5cblx0XHRcdC8qKlxuXHRcdFx0ICogR2V0IEJ1dHRvbiBzdHlsZXMgSlNYIGNvZGUuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0XHQgKlxuXHRcdFx0ICogQHBhcmFtIHtvYmplY3R9IGF0dHJpYnV0ZXMgIEJsb2NrIGF0dHJpYnV0ZXMuXG5cdFx0XHQgKiBAcGFyYW0ge29iamVjdH0gaGFuZGxlcnMgICAgQmxvY2sgZXZlbnQgaGFuZGxlcnMuXG5cdFx0XHQgKiBAcGFyYW0ge29iamVjdH0gc2l6ZU9wdGlvbnMgU2l6ZSBzZWxlY3RvciBvcHRpb25zLlxuXHRcdFx0ICpcblx0XHRcdCAqIEByZXR1cm5zIHtKU1guRWxlbWVudH0gIEJ1dHRvbiBzdHlsZXMgSlNYIGNvZGUuXG5cdFx0XHQgKi9cblx0XHRcdGdldEJ1dHRvblN0eWxlczogZnVuY3Rpb24oIGF0dHJpYnV0ZXMsIGhhbmRsZXJzLCBzaXplT3B0aW9ucyApIHtcblxuXHRcdFx0XHRyZXR1cm4gKFxuXHRcdFx0XHRcdDxQYW5lbEJvZHkgY2xhc3NOYW1lPXsgYXBwLmdldFBhbmVsQ2xhc3MoIGF0dHJpYnV0ZXMgKSB9IHRpdGxlPXsgc3RyaW5ncy5idXR0b25fc3R5bGVzIH0+XG5cdFx0XHRcdFx0XHQ8RmxleCBnYXA9ezR9IGFsaWduPVwiZmxleC1zdGFydFwiIGNsYXNzTmFtZT17J3dwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItZmxleCd9IGp1c3RpZnk9XCJzcGFjZS1iZXR3ZWVuXCI+XG5cdFx0XHRcdFx0XHRcdDxGbGV4QmxvY2s+XG5cdFx0XHRcdFx0XHRcdFx0PFNlbGVjdENvbnRyb2xcblx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsPXsgc3RyaW5ncy5zaXplIH1cblx0XHRcdFx0XHRcdFx0XHRcdHZhbHVlPXsgYXR0cmlidXRlcy5idXR0b25TaXplIH1cblx0XHRcdFx0XHRcdFx0XHRcdG9wdGlvbnM9eyBzaXplT3B0aW9ucyB9XG5cdFx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZT17IHZhbHVlID0+IGhhbmRsZXJzLnN0eWxlQXR0ckNoYW5nZSggJ2J1dHRvblNpemUnLCB2YWx1ZSApIH1cblx0XHRcdFx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdFx0XHQ8L0ZsZXhCbG9jaz5cblx0XHRcdFx0XHRcdFx0PEZsZXhCbG9jaz5cblx0XHRcdFx0XHRcdFx0XHQ8X19leHBlcmltZW50YWxVbml0Q29udHJvbFxuXHRcdFx0XHRcdFx0XHRcdFx0b25DaGFuZ2U9eyB2YWx1ZSA9PiBoYW5kbGVycy5zdHlsZUF0dHJDaGFuZ2UoICdidXR0b25Cb3JkZXJSYWRpdXMnLCB2YWx1ZSApIH1cblx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsPXsgc3RyaW5ncy5ib3JkZXJfcmFkaXVzIH1cblx0XHRcdFx0XHRcdFx0XHRcdGlzVW5pdFNlbGVjdFRhYmJhYmxlXG5cdFx0XHRcdFx0XHRcdFx0XHR2YWx1ZT17IGF0dHJpYnV0ZXMuYnV0dG9uQm9yZGVyUmFkaXVzIH0gLz5cblx0XHRcdFx0XHRcdFx0PC9GbGV4QmxvY2s+XG5cdFx0XHRcdFx0XHQ8L0ZsZXg+XG5cblx0XHRcdFx0XHRcdDxkaXYgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1jb2xvci1waWNrZXJcIj5cblx0XHRcdFx0XHRcdFx0PGRpdiBjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWNvbnRyb2wtbGFiZWxcIj57IHN0cmluZ3MuY29sb3JzIH08L2Rpdj5cblx0XHRcdFx0XHRcdFx0PFBhbmVsQ29sb3JTZXR0aW5nc1xuXHRcdFx0XHRcdFx0XHRcdF9fZXhwZXJpbWVudGFsSXNSZW5kZXJlZEluU2lkZWJhclxuXHRcdFx0XHRcdFx0XHRcdGVuYWJsZUFscGhhXG5cdFx0XHRcdFx0XHRcdFx0c2hvd1RpdGxlPXsgZmFsc2UgfVxuXHRcdFx0XHRcdFx0XHRcdGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItY29sb3ItcGFuZWxcIlxuXHRcdFx0XHRcdFx0XHRcdGNvbG9yU2V0dGluZ3M9e1tcblx0XHRcdFx0XHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0dmFsdWU6IGF0dHJpYnV0ZXMuYnV0dG9uQmFja2dyb3VuZENvbG9yLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZTogdmFsdWUgPT4gaGFuZGxlcnMuc3R5bGVBdHRyQ2hhbmdlKCAnYnV0dG9uQmFja2dyb3VuZENvbG9yJywgdmFsdWUgKSxcblx0XHRcdFx0XHRcdFx0XHRcdFx0bGFiZWw6IHN0cmluZ3MuYmFja2dyb3VuZCxcblx0XHRcdFx0XHRcdFx0XHRcdH0sXG5cdFx0XHRcdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdHZhbHVlOiBhdHRyaWJ1dGVzLmJ1dHRvblRleHRDb2xvcixcblx0XHRcdFx0XHRcdFx0XHRcdFx0b25DaGFuZ2U6IHZhbHVlID0+IGhhbmRsZXJzLnN0eWxlQXR0ckNoYW5nZSggJ2J1dHRvblRleHRDb2xvcicsIHZhbHVlICksXG5cdFx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsOiBzdHJpbmdzLnRleHQsXG5cdFx0XHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XHRcdF19IC8+XG5cdFx0XHRcdFx0XHRcdDxkaXYgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1sZWdlbmQgd3Bmb3Jtcy1idXR0b24tY29sb3Itbm90aWNlXCI+XG5cdFx0XHRcdFx0XHRcdFx0eyBzdHJpbmdzLmJ1dHRvbl9jb2xvcl9ub3RpY2UgfVxuXHRcdFx0XHRcdFx0XHQ8L2Rpdj5cblx0XHRcdFx0XHRcdDwvZGl2PlxuXHRcdFx0XHRcdDwvUGFuZWxCb2R5PlxuXHRcdFx0XHQpO1xuXHRcdFx0fSxcblxuXHRcdFx0LyoqXG5cdFx0XHQgKiBHZXQgc3R5bGUgc2V0dGluZ3MgSlNYIGNvZGUuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0XHQgKlxuXHRcdFx0ICogQHBhcmFtIHtvYmplY3R9IGF0dHJpYnV0ZXMgIEJsb2NrIGF0dHJpYnV0ZXMuXG5cdFx0XHQgKiBAcGFyYW0ge29iamVjdH0gaGFuZGxlcnMgICAgQmxvY2sgZXZlbnQgaGFuZGxlcnMuXG5cdFx0XHQgKiBAcGFyYW0ge29iamVjdH0gc2l6ZU9wdGlvbnMgU2l6ZSBzZWxlY3RvciBvcHRpb25zLlxuXHRcdFx0ICpcblx0XHRcdCAqIEByZXR1cm5zIHtKU1guRWxlbWVudH0gSW5zcGVjdG9yIGNvbnRyb2xzIEpTWCBjb2RlLlxuXHRcdFx0ICovXG5cdFx0XHRnZXRTdHlsZVNldHRpbmdzOiBmdW5jdGlvbiggYXR0cmlidXRlcywgaGFuZGxlcnMsIHNpemVPcHRpb25zICkge1xuXG5cdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0PEluc3BlY3RvckNvbnRyb2xzIGtleT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3Itc3R5bGUtc2V0dGluZ3NcIj5cblx0XHRcdFx0XHRcdHsgYXBwLmpzeFBhcnRzLmdldEZpZWxkU3R5bGVzKCBhdHRyaWJ1dGVzLCBoYW5kbGVycywgc2l6ZU9wdGlvbnMgKSB9XG5cdFx0XHRcdFx0XHR7IGFwcC5qc3hQYXJ0cy5nZXRMYWJlbFN0eWxlcyggYXR0cmlidXRlcywgaGFuZGxlcnMsIHNpemVPcHRpb25zICkgfVxuXHRcdFx0XHRcdFx0eyBhcHAuanN4UGFydHMuZ2V0QnV0dG9uU3R5bGVzKCBhdHRyaWJ1dGVzLCBoYW5kbGVycywgc2l6ZU9wdGlvbnMgKSB9XG5cdFx0XHRcdFx0PC9JbnNwZWN0b3JDb250cm9scz5cblx0XHRcdFx0KTtcblx0XHRcdH0sXG5cblx0XHRcdC8qKlxuXHRcdFx0ICogR2V0IGFkdmFuY2VkIHNldHRpbmdzIEpTWCBjb2RlLlxuXHRcdFx0ICpcblx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0ICpcblx0XHRcdCAqIEBwYXJhbSB7b2JqZWN0fSBhdHRyaWJ1dGVzIEJsb2NrIGF0dHJpYnV0ZXMuXG5cdFx0XHQgKiBAcGFyYW0ge29iamVjdH0gaGFuZGxlcnMgICBCbG9jayBldmVudCBoYW5kbGVycy5cblx0XHRcdCAqXG5cdFx0XHQgKiBAcmV0dXJucyB7SlNYLkVsZW1lbnR9IEluc3BlY3RvciBhZHZhbmNlZCBjb250cm9scyBKU1ggY29kZS5cblx0XHRcdCAqL1xuXHRcdFx0Z2V0QWR2YW5jZWRTZXR0aW5nczogZnVuY3Rpb24oIGF0dHJpYnV0ZXMsIGhhbmRsZXJzICkge1xuXG5cdFx0XHRcdGNvbnN0IFsgaXNPcGVuLCBzZXRPcGVuIF0gPSB1c2VTdGF0ZSggZmFsc2UgKTtcblx0XHRcdFx0Y29uc3Qgb3Blbk1vZGFsID0gKCkgPT4gc2V0T3BlbiggdHJ1ZSApO1xuXHRcdFx0XHRjb25zdCBjbG9zZU1vZGFsID0gKCkgPT4gc2V0T3BlbiggZmFsc2UgKTtcblxuXHRcdFx0XHRyZXR1cm4gKFxuXHRcdFx0XHRcdDxJbnNwZWN0b3JBZHZhbmNlZENvbnRyb2xzPlxuXHRcdFx0XHRcdFx0PGRpdiBjbGFzc05hbWU9eyBhcHAuZ2V0UGFuZWxDbGFzcyggYXR0cmlidXRlcyApIH0+XG5cdFx0XHRcdFx0XHRcdDxUZXh0YXJlYUNvbnRyb2xcblx0XHRcdFx0XHRcdFx0XHRsYWJlbD17IHN0cmluZ3MuY29weV9wYXN0ZV9zZXR0aW5ncyB9XG5cdFx0XHRcdFx0XHRcdFx0cm93cz1cIjRcIlxuXHRcdFx0XHRcdFx0XHRcdHNwZWxsQ2hlY2s9XCJmYWxzZVwiXG5cdFx0XHRcdFx0XHRcdFx0dmFsdWU9eyBhdHRyaWJ1dGVzLmNvcHlQYXN0ZVZhbHVlIH1cblx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZT17IHZhbHVlID0+IGhhbmRsZXJzLnBhc3RlU2V0dGluZ3MoIHZhbHVlICkgfVxuXHRcdFx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItbGVnZW5kXCIgZGFuZ2Vyb3VzbHlTZXRJbm5lckhUTUw9e3sgX19odG1sOiBzdHJpbmdzLmNvcHlfcGFzdGVfbm90aWNlIH19PjwvZGl2PlxuXG5cdFx0XHRcdFx0XHRcdDxCdXR0b24gY2xhc3NOYW1lPSd3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLXJlc2V0LWJ1dHRvbicgb25DbGljaz17IG9wZW5Nb2RhbCB9Pnsgc3RyaW5ncy5yZXNldF9zdHlsZV9zZXR0aW5ncyB9PC9CdXR0b24+XG5cdFx0XHRcdFx0XHQ8L2Rpdj5cblxuXHRcdFx0XHRcdFx0eyBpc09wZW4gJiYgKFxuXHRcdFx0XHRcdFx0XHQ8TW9kYWwgIGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLW1vZGFsXCJcblx0XHRcdFx0XHRcdFx0XHR0aXRsZT17IHN0cmluZ3MucmVzZXRfc3R5bGVfc2V0dGluZ3N9XG5cdFx0XHRcdFx0XHRcdFx0b25SZXF1ZXN0Q2xvc2U9eyBjbG9zZU1vZGFsIH0+XG5cblx0XHRcdFx0XHRcdFx0XHQ8cD57IHN0cmluZ3MucmVzZXRfc2V0dGluZ3NfY29uZmlybV90ZXh0IH08L3A+XG5cblx0XHRcdFx0XHRcdFx0XHQ8RmxleCBnYXA9ezN9IGFsaWduPVwiY2VudGVyXCIganVzdGlmeT1cImZsZXgtZW5kXCI+XG5cdFx0XHRcdFx0XHRcdFx0XHQ8QnV0dG9uIGlzU2Vjb25kYXJ5IG9uQ2xpY2s9eyBjbG9zZU1vZGFsIH0+XG5cdFx0XHRcdFx0XHRcdFx0XHRcdHtzdHJpbmdzLmJ0bl9ub31cblx0XHRcdFx0XHRcdFx0XHRcdDwvQnV0dG9uPlxuXG5cdFx0XHRcdFx0XHRcdFx0XHQ8QnV0dG9uIGlzUHJpbWFyeSBvbkNsaWNrPXsgKCkgPT4ge1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRjbG9zZU1vZGFsKCk7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdGhhbmRsZXJzLnJlc2V0U2V0dGluZ3MoKTtcblx0XHRcdFx0XHRcdFx0XHRcdH0gfT5cblx0XHRcdFx0XHRcdFx0XHRcdFx0eyBzdHJpbmdzLmJ0bl95ZXNfcmVzZXQgfVxuXHRcdFx0XHRcdFx0XHRcdFx0PC9CdXR0b24+XG5cdFx0XHRcdFx0XHRcdFx0PC9GbGV4PlxuXHRcdFx0XHRcdFx0XHQ8L01vZGFsPlxuXHRcdFx0XHRcdFx0KSB9XG5cdFx0XHRcdFx0PC9JbnNwZWN0b3JBZHZhbmNlZENvbnRyb2xzPlxuXHRcdFx0XHQpO1xuXHRcdFx0fSxcblxuXHRcdFx0LyoqXG5cdFx0XHQgKiBHZXQgYmxvY2sgY29udGVudCBKU1ggY29kZS5cblx0XHRcdCAqXG5cdFx0XHQgKiBAc2luY2UgMS44LjFcblx0XHRcdCAqXG5cdFx0XHQgKiBAcGFyYW0ge29iamVjdH0gcHJvcHMgQmxvY2sgcHJvcGVydGllcy5cblx0XHRcdCAqXG5cdFx0XHQgKiBAcmV0dXJucyB7SlNYLkVsZW1lbnR9IEJsb2NrIGNvbnRlbnQgSlNYIGNvZGUuXG5cdFx0XHQgKi9cblx0XHRcdGdldEJsb2NrRm9ybUNvbnRlbnQ6IGZ1bmN0aW9uKCBwcm9wcyApIHtcblxuXHRcdFx0XHRpZiAoIHRyaWdnZXJTZXJ2ZXJSZW5kZXIgKSB7XG5cblx0XHRcdFx0XHRyZXR1cm4gKFxuXHRcdFx0XHRcdFx0PFNlcnZlclNpZGVSZW5kZXJcblx0XHRcdFx0XHRcdFx0a2V5PVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1zZXJ2ZXItc2lkZS1yZW5kZXJlclwiXG5cdFx0XHRcdFx0XHRcdGJsb2NrPVwid3Bmb3Jtcy9mb3JtLXNlbGVjdG9yXCJcblx0XHRcdFx0XHRcdFx0YXR0cmlidXRlcz17IHByb3BzLmF0dHJpYnV0ZXMgfVxuXHRcdFx0XHRcdFx0Lz5cblx0XHRcdFx0XHQpO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0Y29uc3QgY2xpZW50SWQgPSBwcm9wcy5jbGllbnRJZDtcblx0XHRcdFx0Y29uc3QgYmxvY2sgPSBhcHAuZ2V0QmxvY2tDb250YWluZXIoIHByb3BzICk7XG5cblx0XHRcdFx0Ly8gSW4gdGhlIGNhc2Ugb2YgZW1wdHkgY29udGVudCwgdXNlIHNlcnZlciBzaWRlIHJlbmRlcmVyLlxuXHRcdFx0XHQvLyBUaGlzIGhhcHBlbnMgd2hlbiB0aGUgYmxvY2sgaXMgZHVwbGljYXRlZCBvciBjb252ZXJ0ZWQgdG8gYSByZXVzYWJsZSBibG9jay5cblx0XHRcdFx0aWYgKCAhIGJsb2NrIHx8ICEgYmxvY2suaW5uZXJIVE1MICkge1xuXHRcdFx0XHRcdHRyaWdnZXJTZXJ2ZXJSZW5kZXIgPSB0cnVlO1xuXG5cdFx0XHRcdFx0cmV0dXJuIGFwcC5qc3hQYXJ0cy5nZXRCbG9ja0Zvcm1Db250ZW50KCBwcm9wcyApO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0YmxvY2tzWyBjbGllbnRJZCBdID0gYmxvY2tzWyBjbGllbnRJZCBdIHx8IHt9O1xuXHRcdFx0XHRibG9ja3NbIGNsaWVudElkIF0uYmxvY2tIVE1MID0gYmxvY2suaW5uZXJIVE1MO1xuXHRcdFx0XHRibG9ja3NbIGNsaWVudElkIF0ubG9hZGVkRm9ybUlkID0gcHJvcHMuYXR0cmlidXRlcy5mb3JtSWQ7XG5cblx0XHRcdFx0cmV0dXJuIChcblx0XHRcdFx0XHQ8RnJhZ21lbnQga2V5PVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1mcmFnbWVudC1mb3JtLWh0bWxcIj5cblx0XHRcdFx0XHRcdDxkaXYgZGFuZ2Vyb3VzbHlTZXRJbm5lckhUTUw9e3sgX19odG1sOiBibG9ja3NbIGNsaWVudElkIF0uYmxvY2tIVE1MIH19IC8+XG5cdFx0XHRcdFx0PC9GcmFnbWVudD5cblx0XHRcdFx0KTtcblx0XHRcdH0sXG5cblx0XHRcdC8qKlxuXHRcdFx0ICogR2V0IGJsb2NrIHByZXZpZXcgSlNYIGNvZGUuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0XHQgKlxuXHRcdFx0ICogQHJldHVybnMge0pTWC5FbGVtZW50fSBCbG9jayBwcmV2aWV3IEpTWCBjb2RlLlxuXHRcdFx0ICovXG5cdFx0XHRnZXRCbG9ja1ByZXZpZXc6IGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0PEZyYWdtZW50XG5cdFx0XHRcdFx0XHRrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWZyYWdtZW50LWJsb2NrLXByZXZpZXdcIj5cblx0XHRcdFx0XHRcdDxpbWcgc3JjPXsgd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5ibG9ja19wcmV2aWV3X3VybCB9IHN0eWxlPXt7IHdpZHRoOiAnMTAwJScgfX0gLz5cblx0XHRcdFx0XHQ8L0ZyYWdtZW50PlxuXHRcdFx0XHQpO1xuXHRcdFx0fSxcblxuXHRcdFx0LyoqXG5cdFx0XHQgKiBHZXQgYmxvY2sgcGxhY2Vob2xkZXIgKGZvcm0gc2VsZWN0b3IpIEpTWCBjb2RlLlxuXHRcdFx0ICpcblx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0ICpcblx0XHRcdCAqIEBwYXJhbSB7b2JqZWN0fSBhdHRyaWJ1dGVzICBCbG9jayBhdHRyaWJ1dGVzLlxuXHRcdFx0ICogQHBhcmFtIHtvYmplY3R9IGhhbmRsZXJzICAgIEJsb2NrIGV2ZW50IGhhbmRsZXJzLlxuXHRcdFx0ICogQHBhcmFtIHtvYmplY3R9IGZvcm1PcHRpb25zIEZvcm0gc2VsZWN0b3Igb3B0aW9ucy5cblx0XHRcdCAqXG5cdFx0XHQgKiBAcmV0dXJucyB7SlNYLkVsZW1lbnR9IEJsb2NrIHBsYWNlaG9sZGVyIEpTWCBjb2RlLlxuXHRcdFx0ICovXG5cdFx0XHRnZXRCbG9ja1BsYWNlaG9sZGVyOiBmdW5jdGlvbiggYXR0cmlidXRlcywgaGFuZGxlcnMsIGZvcm1PcHRpb25zICkge1xuXG5cdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0PFBsYWNlaG9sZGVyXG5cdFx0XHRcdFx0XHRrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLXdyYXBcIlxuXHRcdFx0XHRcdFx0Y2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci13cmFwXCI+XG5cdFx0XHRcdFx0XHQ8aW1nIHNyYz17d3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5sb2dvX3VybH0gLz5cblx0XHRcdFx0XHRcdDxoMz57IHN0cmluZ3MudGl0bGUgfTwvaDM+XG5cdFx0XHRcdFx0XHQ8U2VsZWN0Q29udHJvbFxuXHRcdFx0XHRcdFx0XHRrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLXNlbGVjdC1jb250cm9sXCJcblx0XHRcdFx0XHRcdFx0dmFsdWU9eyBhdHRyaWJ1dGVzLmZvcm1JZCB9XG5cdFx0XHRcdFx0XHRcdG9wdGlvbnM9eyBmb3JtT3B0aW9ucyB9XG5cdFx0XHRcdFx0XHRcdG9uQ2hhbmdlPXsgdmFsdWUgPT4gaGFuZGxlcnMuYXR0ckNoYW5nZSggJ2Zvcm1JZCcsIHZhbHVlICkgfVxuXHRcdFx0XHRcdFx0Lz5cblx0XHRcdFx0XHQ8L1BsYWNlaG9sZGVyPlxuXHRcdFx0XHQpO1xuXHRcdFx0fSxcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogR2V0IFN0eWxlIFNldHRpbmdzIHBhbmVsIGNsYXNzLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge29iamVjdH0gYXR0cmlidXRlcyBCbG9jayBhdHRyaWJ1dGVzLlxuXHRcdCAqXG5cdFx0ICogQHJldHVybnMge3N0cmluZ30gU3R5bGUgU2V0dGluZ3MgcGFuZWwgY2xhc3MuXG5cdFx0ICovXG5cdFx0Z2V0UGFuZWxDbGFzczogZnVuY3Rpb24oIGF0dHJpYnV0ZXMgKSB7XG5cblx0XHRcdGxldCBjc3NDbGFzcyA9ICd3cGZvcm1zLWd1dGVuYmVyZy1wYW5lbCB3cGZvcm1zLWJsb2NrLXNldHRpbmdzLScgKyBhdHRyaWJ1dGVzLmNsaWVudElkO1xuXG5cdFx0XHRpZiAoICEgYXBwLmlzRnVsbFN0eWxpbmdFbmFibGVkKCkgKSB7XG5cdFx0XHRcdGNzc0NsYXNzICs9ICcgZGlzYWJsZWRfcGFuZWwnO1xuXHRcdFx0fVxuXG5cdFx0XHRyZXR1cm4gY3NzQ2xhc3M7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIERldGVybWluZSB3aGV0aGVyIHRoZSBmdWxsIHN0eWxpbmcgaXMgZW5hYmxlZC5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHJldHVybnMge2Jvb2xlYW59IFdoZXRoZXIgdGhlIGZ1bGwgc3R5bGluZyBpcyBlbmFibGVkLlxuXHRcdCAqL1xuXHRcdGlzRnVsbFN0eWxpbmdFbmFibGVkOiBmdW5jdGlvbigpIHtcblxuXHRcdFx0cmV0dXJuIHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3IuaXNfbW9kZXJuX21hcmt1cCAmJiB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLmlzX2Z1bGxfc3R5bGluZztcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogR2V0IGJsb2NrIGNvbnRhaW5lciBET00gZWxlbWVudC5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtvYmplY3R9IHByb3BzIEJsb2NrIHByb3BlcnRpZXMuXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJucyB7RWxlbWVudH0gQmxvY2sgY29udGFpbmVyLlxuXHRcdCAqL1xuXHRcdGdldEJsb2NrQ29udGFpbmVyOiBmdW5jdGlvbiggcHJvcHMgKSB7XG5cblx0XHRcdGNvbnN0IGJsb2NrU2VsZWN0b3IgPSBgI2Jsb2NrLSR7cHJvcHMuY2xpZW50SWR9ID4gZGl2YDtcblx0XHRcdGxldCBibG9jayA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoIGJsb2NrU2VsZWN0b3IgKTtcblxuXHRcdFx0Ly8gRm9yIEZTRSAvIEd1dGVuYmVyZyBwbHVnaW4gd2UgbmVlZCB0byB0YWtlIGEgbG9vayBpbnNpZGUgdGhlIGlmcmFtZS5cblx0XHRcdGlmICggISBibG9jayApIHtcblx0XHRcdFx0Y29uc3QgZWRpdG9yQ2FudmFzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ2lmcmFtZVtuYW1lPVwiZWRpdG9yLWNhbnZhc1wiXScgKTtcblxuXHRcdFx0XHRibG9jayA9IGVkaXRvckNhbnZhcyAmJiBlZGl0b3JDYW52YXMuY29udGVudFdpbmRvdy5kb2N1bWVudC5xdWVyeVNlbGVjdG9yKCBibG9ja1NlbGVjdG9yICk7XG5cdFx0XHR9XG5cblx0XHRcdHJldHVybiBibG9jaztcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogR2V0IHNldHRpbmdzIGZpZWxkcyBldmVudCBoYW5kbGVycy5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtvYmplY3R9IHByb3BzIEJsb2NrIHByb3BlcnRpZXMuXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJucyB7b2JqZWN0fSBPYmplY3QgdGhhdCBjb250YWlucyBldmVudCBoYW5kbGVycyBmb3IgdGhlIHNldHRpbmdzIGZpZWxkcy5cblx0XHQgKi9cblx0XHRnZXRTZXR0aW5nc0ZpZWxkc0hhbmRsZXJzOiBmdW5jdGlvbiggcHJvcHMgKSB7IC8vIGVzbGludC1kaXNhYmxlLWxpbmUgbWF4LWxpbmVzLXBlci1mdW5jdGlvblxuXG5cdFx0XHRyZXR1cm4ge1xuXG5cdFx0XHRcdC8qKlxuXHRcdFx0XHQgKiBGaWVsZCBzdHlsZSBhdHRyaWJ1dGUgY2hhbmdlIGV2ZW50IGhhbmRsZXIuXG5cdFx0XHRcdCAqXG5cdFx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0XHQgKlxuXHRcdFx0XHQgKiBAcGFyYW0ge3N0cmluZ30gYXR0cmlidXRlIEF0dHJpYnV0ZSBuYW1lLlxuXHRcdFx0XHQgKiBAcGFyYW0ge3N0cmluZ30gdmFsdWUgICAgIE5ldyBhdHRyaWJ1dGUgdmFsdWUuXG5cdFx0XHRcdCAqL1xuXHRcdFx0XHRzdHlsZUF0dHJDaGFuZ2U6IGZ1bmN0aW9uKCBhdHRyaWJ1dGUsIHZhbHVlICkge1xuXG5cdFx0XHRcdFx0Y29uc3QgYmxvY2sgPSBhcHAuZ2V0QmxvY2tDb250YWluZXIoIHByb3BzICksXG5cdFx0XHRcdFx0XHRjb250YWluZXIgPSBibG9jay5xdWVyeVNlbGVjdG9yKCBgI3dwZm9ybXMtJHtwcm9wcy5hdHRyaWJ1dGVzLmZvcm1JZH1gICksXG5cdFx0XHRcdFx0XHRwcm9wZXJ0eSA9IGF0dHJpYnV0ZS5yZXBsYWNlKCAvW0EtWl0vZywgbGV0dGVyID0+IGAtJHtsZXR0ZXIudG9Mb3dlckNhc2UoKX1gICksXG5cdFx0XHRcdFx0XHRzZXRBdHRyID0ge307XG5cblx0XHRcdFx0XHRpZiAoIGNvbnRhaW5lciApIHtcblx0XHRcdFx0XHRcdHN3aXRjaCAoIHByb3BlcnR5ICkge1xuXHRcdFx0XHRcdFx0XHRjYXNlICdmaWVsZC1zaXplJzpcblx0XHRcdFx0XHRcdFx0Y2FzZSAnbGFiZWwtc2l6ZSc6XG5cdFx0XHRcdFx0XHRcdGNhc2UgJ2J1dHRvbi1zaXplJzpcblx0XHRcdFx0XHRcdFx0XHRmb3IgKCBjb25zdCBrZXkgaW4gc2l6ZXNbIHByb3BlcnR5IF1bIHZhbHVlIF0gKSB7XG5cdFx0XHRcdFx0XHRcdFx0XHRjb250YWluZXIuc3R5bGUuc2V0UHJvcGVydHkoXG5cdFx0XHRcdFx0XHRcdFx0XHRcdGAtLXdwZm9ybXMtJHtwcm9wZXJ0eX0tJHtrZXl9YCxcblx0XHRcdFx0XHRcdFx0XHRcdFx0c2l6ZXNbIHByb3BlcnR5IF1bIHZhbHVlIF1bIGtleSBdLFxuXHRcdFx0XHRcdFx0XHRcdFx0KTtcblx0XHRcdFx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHRcdFx0XHRicmVhaztcblxuXHRcdFx0XHRcdFx0XHRkZWZhdWx0OlxuXHRcdFx0XHRcdFx0XHRcdGNvbnRhaW5lci5zdHlsZS5zZXRQcm9wZXJ0eSggYC0td3Bmb3Jtcy0ke3Byb3BlcnR5fWAsIHZhbHVlICk7XG5cdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0c2V0QXR0clsgYXR0cmlidXRlIF0gPSB2YWx1ZTtcblxuXHRcdFx0XHRcdHByb3BzLnNldEF0dHJpYnV0ZXMoIHNldEF0dHIgKTtcblxuXHRcdFx0XHRcdHRyaWdnZXJTZXJ2ZXJSZW5kZXIgPSBmYWxzZTtcblxuXHRcdFx0XHRcdHRoaXMudXBkYXRlQ29weVBhc3RlQ29udGVudCgpO1xuXG5cdFx0XHRcdFx0JCggd2luZG93ICkudHJpZ2dlciggJ3dwZm9ybXNGb3JtU2VsZWN0b3JTdHlsZUF0dHJDaGFuZ2UnLCBbIGJsb2NrLCBwcm9wcywgYXR0cmlidXRlLCB2YWx1ZSBdICk7XG5cdFx0XHRcdH0sXG5cblx0XHRcdFx0LyoqXG5cdFx0XHRcdCAqIEZpZWxkIHJlZ3VsYXIgYXR0cmlidXRlIGNoYW5nZSBldmVudCBoYW5kbGVyLlxuXHRcdFx0XHQgKlxuXHRcdFx0XHQgKiBAc2luY2UgMS44LjFcblx0XHRcdFx0ICpcblx0XHRcdFx0ICogQHBhcmFtIHtzdHJpbmd9IGF0dHJpYnV0ZSBBdHRyaWJ1dGUgbmFtZS5cblx0XHRcdFx0ICogQHBhcmFtIHtzdHJpbmd9IHZhbHVlICAgICBOZXcgYXR0cmlidXRlIHZhbHVlLlxuXHRcdFx0XHQgKi9cblx0XHRcdFx0YXR0ckNoYW5nZTogZnVuY3Rpb24oIGF0dHJpYnV0ZSwgdmFsdWUgKSB7XG5cblx0XHRcdFx0XHRjb25zdCBzZXRBdHRyID0ge307XG5cblx0XHRcdFx0XHRzZXRBdHRyWyBhdHRyaWJ1dGUgXSA9IHZhbHVlO1xuXG5cdFx0XHRcdFx0cHJvcHMuc2V0QXR0cmlidXRlcyggc2V0QXR0ciApO1xuXG5cdFx0XHRcdFx0dHJpZ2dlclNlcnZlclJlbmRlciA9IHRydWU7XG5cblx0XHRcdFx0XHR0aGlzLnVwZGF0ZUNvcHlQYXN0ZUNvbnRlbnQoKTtcblx0XHRcdFx0fSxcblxuXHRcdFx0XHQvKipcblx0XHRcdFx0ICogUmVzZXQgRm9ybSBTdHlsZXMgc2V0dGluZ3MgdG8gZGVmYXVsdHMuXG5cdFx0XHRcdCAqXG5cdFx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0XHQgKi9cblx0XHRcdFx0cmVzZXRTZXR0aW5nczogZnVuY3Rpb24oKSB7XG5cblx0XHRcdFx0XHRmb3IgKCBsZXQga2V5IGluIGRlZmF1bHRTdHlsZVNldHRpbmdzICkge1xuXHRcdFx0XHRcdFx0dGhpcy5zdHlsZUF0dHJDaGFuZ2UoIGtleSwgZGVmYXVsdFN0eWxlU2V0dGluZ3NbIGtleSBdICk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9LFxuXG5cdFx0XHRcdC8qKlxuXHRcdFx0XHQgKiBVcGRhdGUgY29udGVudCBvZiB0aGUgXCJDb3B5L1Bhc3RlXCIgZmllbGRzLlxuXHRcdFx0XHQgKlxuXHRcdFx0XHQgKiBAc2luY2UgMS44LjFcblx0XHRcdFx0ICovXG5cdFx0XHRcdHVwZGF0ZUNvcHlQYXN0ZUNvbnRlbnQ6IGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRcdFx0bGV0IGNvbnRlbnQgPSB7fTtcblx0XHRcdFx0XHRsZXQgYXR0cyA9IHdwLmRhdGEuc2VsZWN0KCAnY29yZS9ibG9jay1lZGl0b3InICkuZ2V0QmxvY2tBdHRyaWJ1dGVzKCBwcm9wcy5jbGllbnRJZCApO1xuXG5cdFx0XHRcdFx0Zm9yICggbGV0IGtleSBpbiBkZWZhdWx0U3R5bGVTZXR0aW5ncyApIHtcblx0XHRcdFx0XHRcdGNvbnRlbnRba2V5XSA9IGF0dHNbIGtleSBdO1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdHByb3BzLnNldEF0dHJpYnV0ZXMoIHsgJ2NvcHlQYXN0ZVZhbHVlJzogSlNPTi5zdHJpbmdpZnkoIGNvbnRlbnQgKSB9ICk7XG5cdFx0XHRcdH0sXG5cblx0XHRcdFx0LyoqXG5cdFx0XHRcdCAqIFBhc3RlIHNldHRpbmdzIGhhbmRsZXIuXG5cdFx0XHRcdCAqXG5cdFx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0XHQgKlxuXHRcdFx0XHQgKiBAcGFyYW0ge3N0cmluZ30gdmFsdWUgTmV3IGF0dHJpYnV0ZSB2YWx1ZS5cblx0XHRcdFx0ICovXG5cdFx0XHRcdHBhc3RlU2V0dGluZ3M6IGZ1bmN0aW9uKCB2YWx1ZSApIHtcblxuXHRcdFx0XHRcdGxldCBwYXN0ZUF0dHJpYnV0ZXMgPSBhcHAucGFyc2VWYWxpZGF0ZUpzb24oIHZhbHVlICk7XG5cblx0XHRcdFx0XHRpZiAoICEgcGFzdGVBdHRyaWJ1dGVzICkge1xuXG5cdFx0XHRcdFx0XHR3cC5kYXRhLmRpc3BhdGNoKCAnY29yZS9ub3RpY2VzJyApLmNyZWF0ZUVycm9yTm90aWNlKFxuXHRcdFx0XHRcdFx0XHRzdHJpbmdzLmNvcHlfcGFzdGVfZXJyb3IsXG5cdFx0XHRcdFx0XHRcdHsgaWQ6ICd3cGZvcm1zLWpzb24tcGFyc2UtZXJyb3InIH1cblx0XHRcdFx0XHRcdCk7XG5cblx0XHRcdFx0XHRcdHRoaXMudXBkYXRlQ29weVBhc3RlQ29udGVudCgpO1xuXG5cdFx0XHRcdFx0XHRyZXR1cm47XG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0cGFzdGVBdHRyaWJ1dGVzLmNvcHlQYXN0ZVZhbHVlID0gdmFsdWU7XG5cblx0XHRcdFx0XHRwcm9wcy5zZXRBdHRyaWJ1dGVzKCBwYXN0ZUF0dHJpYnV0ZXMgKTtcblxuXHRcdFx0XHRcdHRyaWdnZXJTZXJ2ZXJSZW5kZXIgPSB0cnVlO1xuXHRcdFx0XHR9LFxuXHRcdFx0fTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogUGFyc2UgYW5kIHZhbGlkYXRlIEpTT04gc3RyaW5nLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge3N0cmluZ30gdmFsdWUgSlNPTiBzdHJpbmcuXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJucyB7Ym9vbGVhbnxvYmplY3R9IFBhcnNlZCBKU09OIG9iamVjdCBPUiBmYWxzZSBvbiBlcnJvci5cblx0XHQgKi9cblx0XHRwYXJzZVZhbGlkYXRlSnNvbjogZnVuY3Rpb24oIHZhbHVlICkge1xuXG5cdFx0XHRpZiAoIHR5cGVvZiB2YWx1ZSAhPT0gJ3N0cmluZycgKSB7XG5cdFx0XHRcdHJldHVybiBmYWxzZTtcblx0XHRcdH1cblxuXHRcdFx0bGV0IGF0dHM7XG5cblx0XHRcdHRyeSB7XG5cdFx0XHRcdGF0dHMgPSBKU09OLnBhcnNlKCB2YWx1ZSApO1xuXHRcdFx0fSBjYXRjaCAoIGVycm9yICkge1xuXHRcdFx0XHRhdHRzID0gZmFsc2U7XG5cdFx0XHR9XG5cblx0XHRcdHJldHVybiBhdHRzO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBHZXQgV1BGb3JtcyBpY29uIERPTSBlbGVtZW50LlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJucyB7RE9NLmVsZW1lbnR9IFdQRm9ybXMgaWNvbiBET00gZWxlbWVudC5cblx0XHQgKi9cblx0XHRnZXRJY29uOiBmdW5jdGlvbigpIHtcblxuXHRcdFx0cmV0dXJuIGNyZWF0ZUVsZW1lbnQoXG5cdFx0XHRcdCdzdmcnLFxuXHRcdFx0XHR7IHdpZHRoOiAyMCwgaGVpZ2h0OiAyMCwgdmlld0JveDogJzAgMCA2MTIgNjEyJywgY2xhc3NOYW1lOiAnZGFzaGljb24nIH0sXG5cdFx0XHRcdGNyZWF0ZUVsZW1lbnQoXG5cdFx0XHRcdFx0J3BhdGgnLFxuXHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdGZpbGw6ICdjdXJyZW50Q29sb3InLFxuXHRcdFx0XHRcdFx0ZDogJ001NDQsMEg2OEMzMC40NDUsMCwwLDMwLjQ0NSwwLDY4djQ3NmMwLDM3LjU1NiwzMC40NDUsNjgsNjgsNjhoNDc2YzM3LjU1NiwwLDY4LTMwLjQ0NCw2OC02OFY2OCBDNjEyLDMwLjQ0NSw1ODEuNTU2LDAsNTQ0LDB6IE00NjQuNDQsNjhMMzg3LjYsMTIwLjAyTDMyMy4zNCw2OEg0NjQuNDR6IE0yODguNjYsNjhsLTY0LjI2LDUyLjAyTDE0Ny41Niw2OEgyODguNjZ6IE01NDQsNTQ0SDY4IFY2OGgyMi4xbDEzNiw5Mi4xNGw3OS45LTY0LjZsNzkuNTYsNjQuNmwxMzYtOTIuMTRINTQ0VjU0NHogTTExNC4yNCwyNjMuMTZoOTUuODh2LTQ4LjI4aC05NS44OFYyNjMuMTZ6IE0xMTQuMjQsMzYwLjRoOTUuODggdi00OC42MmgtOTUuODhWMzYwLjR6IE0yNDIuNzYsMzYwLjRoMjU1di00OC42MmgtMjU1VjM2MC40TDI0Mi43NiwzNjAuNHogTTI0Mi43NiwyNjMuMTZoMjU1di00OC4yOGgtMjU1VjI2My4xNkwyNDIuNzYsMjYzLjE2eiBNMzY4LjIyLDQ1Ny4zaDEyOS41NFY0MDhIMzY4LjIyVjQ1Ny4zeicsXG5cdFx0XHRcdFx0fSxcblx0XHRcdFx0KSxcblx0XHRcdCk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEdldCBibG9jayBhdHRyaWJ1dGVzLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJucyB7b2JqZWN0fSBCbG9jayBhdHRyaWJ1dGVzLlxuXHRcdCAqL1xuXHRcdGdldEJsb2NrQXR0cmlidXRlczogZnVuY3Rpb24oKSB7IC8vIGVzbGludC1kaXNhYmxlLWxpbmUgbWF4LWxpbmVzLXBlci1mdW5jdGlvblxuXG5cdFx0XHRyZXR1cm4ge1xuXHRcdFx0XHRjbGllbnRJZDoge1xuXHRcdFx0XHRcdHR5cGU6ICdzdHJpbmcnLFxuXHRcdFx0XHRcdGRlZmF1bHQ6ICcnLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRmb3JtSWQ6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5mb3JtSWQsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGRpc3BsYXlUaXRsZToge1xuXHRcdFx0XHRcdHR5cGU6ICdib29sZWFuJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5kaXNwbGF5VGl0bGUsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGRpc3BsYXlEZXNjOiB7XG5cdFx0XHRcdFx0dHlwZTogJ2Jvb2xlYW4nLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmRpc3BsYXlEZXNjLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRwcmV2aWV3OiB7XG5cdFx0XHRcdFx0dHlwZTogJ2Jvb2xlYW4nLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRmaWVsZFNpemU6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5maWVsZFNpemUsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGZpZWxkQm9yZGVyUmFkaXVzOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMuZmllbGRCb3JkZXJSYWRpdXMsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGZpZWxkQmFja2dyb3VuZENvbG9yOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMuZmllbGRCYWNrZ3JvdW5kQ29sb3IsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGZpZWxkQm9yZGVyQ29sb3I6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5maWVsZEJvcmRlckNvbG9yLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRmaWVsZFRleHRDb2xvcjoge1xuXHRcdFx0XHRcdHR5cGU6ICdzdHJpbmcnLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmZpZWxkVGV4dENvbG9yLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRsYWJlbFNpemU6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5sYWJlbFNpemUsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGxhYmVsQ29sb3I6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5sYWJlbENvbG9yLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRsYWJlbFN1YmxhYmVsQ29sb3I6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5sYWJlbFN1YmxhYmVsQ29sb3IsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGxhYmVsRXJyb3JDb2xvcjoge1xuXHRcdFx0XHRcdHR5cGU6ICdzdHJpbmcnLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmxhYmVsRXJyb3JDb2xvcixcblx0XHRcdFx0fSxcblx0XHRcdFx0YnV0dG9uU2l6ZToge1xuXHRcdFx0XHRcdHR5cGU6ICdzdHJpbmcnLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmJ1dHRvblNpemUsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGJ1dHRvbkJvcmRlclJhZGl1czoge1xuXHRcdFx0XHRcdHR5cGU6ICdzdHJpbmcnLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmJ1dHRvbkJvcmRlclJhZGl1cyxcblx0XHRcdFx0fSxcblx0XHRcdFx0YnV0dG9uQmFja2dyb3VuZENvbG9yOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMuYnV0dG9uQmFja2dyb3VuZENvbG9yLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRidXR0b25UZXh0Q29sb3I6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5idXR0b25UZXh0Q29sb3IsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGNvcHlQYXN0ZVZhbHVlOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMuY29weVBhc3RlVmFsdWUsXG5cdFx0XHRcdH0sXG5cdFx0XHR9O1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBHZXQgZm9ybSBzZWxlY3RvciBvcHRpb25zLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJucyB7QXJyYXl9IEZvcm0gb3B0aW9ucy5cblx0XHQgKi9cblx0XHRnZXRGb3JtT3B0aW9uczogZnVuY3Rpb24oKSB7XG5cblx0XHRcdGNvbnN0IGZvcm1PcHRpb25zID0gd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5mb3Jtcy5tYXAoIHZhbHVlID0+IChcblx0XHRcdFx0eyB2YWx1ZTogdmFsdWUuSUQsIGxhYmVsOiB2YWx1ZS5wb3N0X3RpdGxlIH1cblx0XHRcdCkgKTtcblxuXHRcdFx0Zm9ybU9wdGlvbnMudW5zaGlmdCggeyB2YWx1ZTogJycsIGxhYmVsOiBzdHJpbmdzLmZvcm1fc2VsZWN0IH0gKTtcblxuXHRcdFx0cmV0dXJuIGZvcm1PcHRpb25zO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBHZXQgc2l6ZSBzZWxlY3RvciBvcHRpb25zLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJucyB7QXJyYXl9IFNpemUgb3B0aW9ucy5cblx0XHQgKi9cblx0XHRnZXRTaXplT3B0aW9uczogZnVuY3Rpb24oKSB7XG5cblx0XHRcdHJldHVybiBbXG5cdFx0XHRcdHtcblx0XHRcdFx0XHRsYWJlbDogc3RyaW5ncy5zbWFsbCxcblx0XHRcdFx0XHR2YWx1ZTogJ3NtYWxsJyxcblx0XHRcdFx0fSxcblx0XHRcdFx0e1xuXHRcdFx0XHRcdGxhYmVsOiBzdHJpbmdzLm1lZGl1bSxcblx0XHRcdFx0XHR2YWx1ZTogJ21lZGl1bScsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdHtcblx0XHRcdFx0XHRsYWJlbDogc3RyaW5ncy5sYXJnZSxcblx0XHRcdFx0XHR2YWx1ZTogJ2xhcmdlJyxcblx0XHRcdFx0fSxcblx0XHRcdF07XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEV2ZW50IGB3cGZvcm1zRm9ybVNlbGVjdG9yRWRpdGAgaGFuZGxlci5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtvYmplY3R9IGUgICAgIEV2ZW50IG9iamVjdC5cblx0XHQgKiBAcGFyYW0ge29iamVjdH0gcHJvcHMgQmxvY2sgcHJvcGVydGllcy5cblx0XHQgKi9cblx0XHRibG9ja0VkaXQ6IGZ1bmN0aW9uKCBlLCBwcm9wcyApIHtcblxuXHRcdFx0Y29uc3QgYmxvY2sgPSBhcHAuZ2V0QmxvY2tDb250YWluZXIoIHByb3BzICk7XG5cblx0XHRcdGlmICggISBibG9jayB8fCAhIGJsb2NrLmRhdGFzZXQgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0YXBwLmluaXRMZWFkRm9ybVNldHRpbmdzKCBibG9jay5wYXJlbnRFbGVtZW50ICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEluaXQgTGVhZCBGb3JtIFNldHRpbmdzIHBhbmVscy5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtFbGVtZW50fSBibG9jayBCbG9jayBlbGVtZW50LlxuXHRcdCAqL1xuXHRcdGluaXRMZWFkRm9ybVNldHRpbmdzOiBmdW5jdGlvbiggYmxvY2sgKSB7XG5cblx0XHRcdGlmICggISBibG9jayB8fCAhIGJsb2NrLmRhdGFzZXQgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0aWYgKCAhIGFwcC5pc0Z1bGxTdHlsaW5nRW5hYmxlZCgpICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdGNvbnN0IGNsaWVudElkID0gYmxvY2suZGF0YXNldC5ibG9jaztcblx0XHRcdGNvbnN0ICRmb3JtID0gJCggYmxvY2sucXVlcnlTZWxlY3RvciggJy53cGZvcm1zLWNvbnRhaW5lcicgKSApO1xuXHRcdFx0Y29uc3QgJHBhbmVsID0gJCggYC53cGZvcm1zLWJsb2NrLXNldHRpbmdzLSR7Y2xpZW50SWR9YCApO1xuXG5cdFx0XHRpZiAoICRmb3JtLmhhc0NsYXNzKCAnd3Bmb3Jtcy1sZWFkLWZvcm1zLWNvbnRhaW5lcicgKSApIHtcblxuXHRcdFx0XHQkcGFuZWxcblx0XHRcdFx0XHQuYWRkQ2xhc3MoICdkaXNhYmxlZF9wYW5lbCcgKVxuXHRcdFx0XHRcdC5maW5kKCAnLndwZm9ybXMtZ3V0ZW5iZXJnLXBhbmVsLW5vdGljZS53cGZvcm1zLWxlYWQtZm9ybS1ub3RpY2UnIClcblx0XHRcdFx0XHQuY3NzKCAnZGlzcGxheScsICdibG9jaycgKTtcblxuXHRcdFx0XHQkcGFuZWxcblx0XHRcdFx0XHQuZmluZCggJy53cGZvcm1zLWd1dGVuYmVyZy1wYW5lbC1ub3RpY2Uud3Bmb3Jtcy11c2UtbW9kZXJuLW5vdGljZScgKVxuXHRcdFx0XHRcdC5jc3MoICdkaXNwbGF5JywgJ25vbmUnICk7XG5cblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHQkcGFuZWxcblx0XHRcdFx0LnJlbW92ZUNsYXNzKCAnZGlzYWJsZWRfcGFuZWwnIClcblx0XHRcdFx0LmZpbmQoICcud3Bmb3Jtcy1ndXRlbmJlcmctcGFuZWwtbm90aWNlLndwZm9ybXMtbGVhZC1mb3JtLW5vdGljZScgKVxuXHRcdFx0XHQuY3NzKCAnZGlzcGxheScsICdub25lJyApO1xuXG5cdFx0XHQkcGFuZWxcblx0XHRcdFx0LmZpbmQoICcud3Bmb3Jtcy1ndXRlbmJlcmctcGFuZWwtbm90aWNlLndwZm9ybXMtdXNlLW1vZGVybi1ub3RpY2UnIClcblx0XHRcdFx0LmNzcyggJ2Rpc3BsYXknLCBudWxsICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEV2ZW50IGB3cGZvcm1zRm9ybVNlbGVjdG9yRm9ybUxvYWRlZGAgaGFuZGxlci5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtvYmplY3R9IGUgRXZlbnQgb2JqZWN0LlxuXHRcdCAqL1xuXHRcdGZvcm1Mb2FkZWQ6IGZ1bmN0aW9uKCBlICkge1xuXG5cdFx0XHRhcHAuaW5pdExlYWRGb3JtU2V0dGluZ3MoIGUuZGV0YWlsLmJsb2NrICk7XG5cdFx0XHRhcHAudXBkYXRlQWNjZW50Q29sb3JzKCBlLmRldGFpbCApO1xuXHRcdFx0YXBwLmxvYWRDaG9pY2VzSlMoIGUuZGV0YWlsICk7XG5cdFx0XHRhcHAuaW5pdFJpY2hUZXh0RmllbGQoIGUuZGV0YWlsLmZvcm1JZCApO1xuXG5cdFx0XHQkKCBlLmRldGFpbC5ibG9jayApXG5cdFx0XHRcdC5vZmYoICdjbGljaycgKVxuXHRcdFx0XHQub24oICdjbGljaycsIGFwcC5ibG9ja0NsaWNrICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIENsaWNrIG9uIHRoZSBibG9jayBldmVudCBoYW5kbGVyLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge29iamVjdH0gZSBFdmVudCBvYmplY3QuXG5cdFx0ICovXG5cdFx0YmxvY2tDbGljazogZnVuY3Rpb24oIGUgKSB7XG5cblx0XHRcdGFwcC5pbml0TGVhZEZvcm1TZXR0aW5ncyggZS5jdXJyZW50VGFyZ2V0ICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIFVwZGF0ZSBhY2NlbnQgY29sb3JzIG9mIHNvbWUgZmllbGRzIGluIEdCIGJsb2NrIGluIE1vZGVybiBNYXJrdXAgbW9kZS5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtvYmplY3R9IGRldGFpbCBFdmVudCBkZXRhaWxzIG9iamVjdC5cblx0XHQgKi9cblx0XHR1cGRhdGVBY2NlbnRDb2xvcnM6IGZ1bmN0aW9uKCBkZXRhaWwgKSB7XG5cblx0XHRcdGlmIChcblx0XHRcdFx0ISB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLmlzX21vZGVybl9tYXJrdXAgfHxcblx0XHRcdFx0ISB3aW5kb3cuV1BGb3JtcyB8fFxuXHRcdFx0XHQhIHdpbmRvdy5XUEZvcm1zLkZyb250ZW5kTW9kZXJuIHx8XG5cdFx0XHRcdCEgZGV0YWlsLmJsb2NrXG5cdFx0XHQpIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRjb25zdCAkZm9ybSA9ICQoIGRldGFpbC5ibG9jay5xdWVyeVNlbGVjdG9yKCBgI3dwZm9ybXMtJHtkZXRhaWwuZm9ybUlkfWAgKSApLFxuXHRcdFx0XHRGcm9udGVuZE1vZGVybiA9IHdpbmRvdy5XUEZvcm1zLkZyb250ZW5kTW9kZXJuO1xuXG5cdFx0XHRGcm9udGVuZE1vZGVybi51cGRhdGVHQkJsb2NrUGFnZUluZGljYXRvckNvbG9yKCAkZm9ybSApO1xuXHRcdFx0RnJvbnRlbmRNb2Rlcm4udXBkYXRlR0JCbG9ja0ljb25DaG9pY2VzQ29sb3IoICRmb3JtICk7XG5cdFx0XHRGcm9udGVuZE1vZGVybi51cGRhdGVHQkJsb2NrUmF0aW5nQ29sb3IoICRmb3JtICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEluaXQgTW9kZXJuIHN0eWxlIERyb3Bkb3duIGZpZWxkcyAoPHNlbGVjdD4pLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge29iamVjdH0gZGV0YWlsIEV2ZW50IGRldGFpbHMgb2JqZWN0LlxuXHRcdCAqL1xuXHRcdGxvYWRDaG9pY2VzSlM6IGZ1bmN0aW9uKCBkZXRhaWwgKSB7XG5cblx0XHRcdGlmICggdHlwZW9mIHdpbmRvdy5DaG9pY2VzICE9PSAnZnVuY3Rpb24nICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdGNvbnN0ICRmb3JtID0gJCggZGV0YWlsLmJsb2NrLnF1ZXJ5U2VsZWN0b3IoIGAjd3Bmb3Jtcy0ke2RldGFpbC5mb3JtSWR9YCApICk7XG5cblx0XHRcdCRmb3JtLmZpbmQoICcuY2hvaWNlc2pzLXNlbGVjdCcgKS5lYWNoKCBmdW5jdGlvbiggaWR4LCBlbCApIHtcblxuXHRcdFx0XHRjb25zdCAkZWwgPSAkKCBlbCApO1xuXG5cdFx0XHRcdGlmICggJGVsLmRhdGEoICdjaG9pY2UnICkgPT09ICdhY3RpdmUnICkge1xuXHRcdFx0XHRcdHJldHVybjtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdHZhciBhcmdzID0gd2luZG93LndwZm9ybXNfY2hvaWNlc2pzX2NvbmZpZyB8fCB7fSxcblx0XHRcdFx0XHRzZWFyY2hFbmFibGVkID0gJGVsLmRhdGEoICdzZWFyY2gtZW5hYmxlZCcgKSxcblx0XHRcdFx0XHQkZmllbGQgPSAkZWwuY2xvc2VzdCggJy53cGZvcm1zLWZpZWxkJyApO1xuXG5cdFx0XHRcdGFyZ3Muc2VhcmNoRW5hYmxlZCA9ICd1bmRlZmluZWQnICE9PSB0eXBlb2Ygc2VhcmNoRW5hYmxlZCA/IHNlYXJjaEVuYWJsZWQgOiB0cnVlO1xuXHRcdFx0XHRhcmdzLmNhbGxiYWNrT25Jbml0ID0gZnVuY3Rpb24oKSB7XG5cblx0XHRcdFx0XHR2YXIgc2VsZiA9IHRoaXMsXG5cdFx0XHRcdFx0XHQkZWxlbWVudCA9ICQoIHNlbGYucGFzc2VkRWxlbWVudC5lbGVtZW50ICksXG5cdFx0XHRcdFx0XHQkaW5wdXQgPSAkKCBzZWxmLmlucHV0LmVsZW1lbnQgKSxcblx0XHRcdFx0XHRcdHNpemVDbGFzcyA9ICRlbGVtZW50LmRhdGEoICdzaXplLWNsYXNzJyApO1xuXG5cdFx0XHRcdFx0Ly8gQWRkIENTUy1jbGFzcyBmb3Igc2l6ZS5cblx0XHRcdFx0XHRpZiAoIHNpemVDbGFzcyApIHtcblx0XHRcdFx0XHRcdCQoIHNlbGYuY29udGFpbmVyT3V0ZXIuZWxlbWVudCApLmFkZENsYXNzKCBzaXplQ2xhc3MgKTtcblx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHQvKipcblx0XHRcdFx0XHQgKiBJZiBhIG11bHRpcGxlIHNlbGVjdCBoYXMgc2VsZWN0ZWQgY2hvaWNlcyAtIGhpZGUgYSBwbGFjZWhvbGRlciB0ZXh0LlxuXHRcdFx0XHRcdCAqIEluIGNhc2UgaWYgc2VsZWN0IGlzIGVtcHR5IC0gd2UgcmV0dXJuIHBsYWNlaG9sZGVyIHRleHQgYmFjay5cblx0XHRcdFx0XHQgKi9cblx0XHRcdFx0XHRpZiAoICRlbGVtZW50LnByb3AoICdtdWx0aXBsZScgKSApIHtcblxuXHRcdFx0XHRcdFx0Ly8gT24gaW5pdCBldmVudC5cblx0XHRcdFx0XHRcdCRpbnB1dC5kYXRhKCAncGxhY2Vob2xkZXInLCAkaW5wdXQuYXR0ciggJ3BsYWNlaG9sZGVyJyApICk7XG5cblx0XHRcdFx0XHRcdGlmICggc2VsZi5nZXRWYWx1ZSggdHJ1ZSApLmxlbmd0aCApIHtcblx0XHRcdFx0XHRcdFx0JGlucHV0LnJlbW92ZUF0dHIoICdwbGFjZWhvbGRlcicgKTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHR0aGlzLmRpc2FibGUoKTtcblx0XHRcdFx0XHQkZmllbGQuZmluZCggJy5pcy1kaXNhYmxlZCcgKS5yZW1vdmVDbGFzcyggJ2lzLWRpc2FibGVkJyApO1xuXHRcdFx0XHR9O1xuXG5cdFx0XHRcdHRyeSB7XG5cdFx0XHRcdFx0Y29uc3QgY2hvaWNlc0luc3RhbmNlID0gIG5ldyBDaG9pY2VzKCBlbCwgYXJncyApO1xuXG5cdFx0XHRcdFx0Ly8gU2F2ZSBDaG9pY2VzLmpzIGluc3RhbmNlIGZvciBmdXR1cmUgYWNjZXNzLlxuXHRcdFx0XHRcdCRlbC5kYXRhKCAnY2hvaWNlc2pzJywgY2hvaWNlc0luc3RhbmNlICk7XG5cblx0XHRcdFx0fSBjYXRjaCAoIGUgKSB7fSAvLyBlc2xpbnQtZGlzYWJsZS1saW5lIG5vLWVtcHR5XG5cdFx0XHR9ICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEluaXRpYWxpemUgUmljaFRleHQgZmllbGQuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7aW50fSBmb3JtSWQgRm9ybSBJRC5cblx0XHQgKi9cblx0XHRpbml0UmljaFRleHRGaWVsZDogZnVuY3Rpb24oIGZvcm1JZCApIHtcblxuXHRcdFx0Ly8gU2V0IGRlZmF1bHQgdGFiIHRvIGBWaXN1YWxgLlxuXHRcdFx0JCggYCN3cGZvcm1zLSR7Zm9ybUlkfSAud3AtZWRpdG9yLXdyYXBgICkucmVtb3ZlQ2xhc3MoICdodG1sLWFjdGl2ZScgKS5hZGRDbGFzcyggJ3RtY2UtYWN0aXZlJyApO1xuXHRcdH0sXG5cdH07XG5cblx0Ly8gUHJvdmlkZSBhY2Nlc3MgdG8gcHVibGljIGZ1bmN0aW9ucy9wcm9wZXJ0aWVzLlxuXHRyZXR1cm4gYXBwO1xuXG59KCBkb2N1bWVudCwgd2luZG93LCBqUXVlcnkgKSApO1xuXG4vLyBJbml0aWFsaXplLlxuV1BGb3Jtcy5Gb3JtU2VsZWN0b3IuaW5pdCgpO1xuIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUNBOztBQUVBLFlBQVk7O0FBRVo7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUpBLFNBQUFBLGVBQUFDLEdBQUEsRUFBQUMsQ0FBQSxXQUFBQyxlQUFBLENBQUFGLEdBQUEsS0FBQUcscUJBQUEsQ0FBQUgsR0FBQSxFQUFBQyxDQUFBLEtBQUFHLDJCQUFBLENBQUFKLEdBQUEsRUFBQUMsQ0FBQSxLQUFBSSxnQkFBQTtBQUFBLFNBQUFBLGlCQUFBLGNBQUFDLFNBQUE7QUFBQSxTQUFBRiw0QkFBQUcsQ0FBQSxFQUFBQyxNQUFBLFNBQUFELENBQUEscUJBQUFBLENBQUEsc0JBQUFFLGlCQUFBLENBQUFGLENBQUEsRUFBQUMsTUFBQSxPQUFBRSxDQUFBLEdBQUFDLE1BQUEsQ0FBQUMsU0FBQSxDQUFBQyxRQUFBLENBQUFDLElBQUEs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
},{}]},{},[1])