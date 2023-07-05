(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/* global wpforms_gutenberg_form_selector */
/* jshint es3: false, esversion: 6 */

'use strict';

var _wp = wp,
  _wp$serverSideRender = _wp.serverSideRender,
  ServerSideRender = _wp$serverSideRender === void 0 ? wp.components.ServerSideRender : _wp$serverSideRender;
var _wp$element = wp.element,
  createElement = _wp$element.createElement,
  Fragment = _wp$element.Fragment;
var registerBlockType = wp.blocks.registerBlockType;
var _ref = wp.blockEditor || wp.editor,
  InspectorControls = _ref.InspectorControls;
var _wp$components = wp.components,
  SelectControl = _wp$components.SelectControl,
  ToggleControl = _wp$components.ToggleControl,
  PanelBody = _wp$components.PanelBody,
  Placeholder = _wp$components.Placeholder;
var wpformsIcon = createElement('svg', {
  width: 20,
  height: 20,
  viewBox: '0 0 612 612',
  className: 'dashicon'
}, createElement('path', {
  fill: 'currentColor',
  d: 'M544,0H68C30.445,0,0,30.445,0,68v476c0,37.556,30.445,68,68,68h476c37.556,0,68-30.444,68-68V68 C612,30.445,581.556,0,544,0z M464.44,68L387.6,120.02L323.34,68H464.44z M288.66,68l-64.26,52.02L147.56,68H288.66z M544,544H68 V68h22.1l136,92.14l79.9-64.6l79.56,64.6l136-92.14H544V544z M114.24,263.16h95.88v-48.28h-95.88V263.16z M114.24,360.4h95.88 v-48.62h-95.88V360.4z M242.76,360.4h255v-48.62h-255V360.4L242.76,360.4z M242.76,263.16h255v-48.28h-255V263.16L242.76,263.16z M368.22,457.3h129.54V408H368.22V457.3z'
}));
registerBlockType('wpforms/form-selector', {
  title: wpforms_gutenberg_form_selector.strings.title,
  description: wpforms_gutenberg_form_selector.strings.description,
  icon: wpformsIcon,
  keywords: wpforms_gutenberg_form_selector.strings.form_keywords,
  category: 'widgets',
  attributes: {
    formId: {
      type: 'string'
    },
    displayTitle: {
      type: 'boolean'
    },
    displayDesc: {
      type: 'boolean'
    },
    preview: {
      type: 'boolean'
    }
  },
  example: {
    attributes: {
      preview: true
    }
  },
  edit: function edit(props) {
    // eslint-disable-line max-lines-per-function
    var _props$attributes = props.attributes,
      _props$attributes$for = _props$attributes.formId,
      formId = _props$attributes$for === void 0 ? '' : _props$attributes$for,
      _props$attributes$dis = _props$attributes.displayTitle,
      displayTitle = _props$attributes$dis === void 0 ? false : _props$attributes$dis,
      _props$attributes$dis2 = _props$attributes.displayDesc,
      displayDesc = _props$attributes$dis2 === void 0 ? false : _props$attributes$dis2,
      _props$attributes$pre = _props$attributes.preview,
      preview = _props$attributes$pre === void 0 ? false : _props$attributes$pre,
      setAttributes = props.setAttributes;
    var formOptions = wpforms_gutenberg_form_selector.forms.map(function (value) {
      return {
        value: value.ID,
        label: value.post_title
      };
    });
    var strings = wpforms_gutenberg_form_selector.strings;
    var jsx;
    formOptions.unshift({
      value: '',
      label: wpforms_gutenberg_form_selector.strings.form_select
    });
    function selectForm(value) {
      // eslint-disable-line jsdoc/require-jsdoc
      setAttributes({
        formId: value
      });
    }
    function toggleDisplayTitle(value) {
      // eslint-disable-line jsdoc/require-jsdoc
      setAttributes({
        displayTitle: value
      });
    }
    function toggleDisplayDesc(value) {
      // eslint-disable-line jsdoc/require-jsdoc
      setAttributes({
        displayDesc: value
      });
    }
    jsx = [/*#__PURE__*/React.createElement(InspectorControls, {
      key: "wpforms-gutenberg-form-selector-inspector-controls"
    }, /*#__PURE__*/React.createElement(PanelBody, {
      title: wpforms_gutenberg_form_selector.strings.form_settings
    }, /*#__PURE__*/React.createElement(SelectControl, {
      label: wpforms_gutenberg_form_selector.strings.form_selected,
      value: formId,
      options: formOptions,
      onChange: selectForm
    }), /*#__PURE__*/React.createElement(ToggleControl, {
      label: wpforms_gutenberg_form_selector.strings.show_title,
      checked: displayTitle,
      onChange: toggleDisplayTitle
    }), /*#__PURE__*/React.createElement(ToggleControl, {
      label: wpforms_gutenberg_form_selector.strings.show_description,
      checked: displayDesc,
      onChange: toggleDisplayDesc
    }), /*#__PURE__*/React.createElement("p", {
      className: "wpforms-gutenberg-panel-notice"
    }, /*#__PURE__*/React.createElement("strong", null, strings.update_wp_notice_head), strings.update_wp_notice_text, " ", /*#__PURE__*/React.createElement("a", {
      href: strings.update_wp_notice_link,
      rel: "noreferrer",
      target: "_blank"
    }, strings.learn_more))))];
    if (formId) {
      jsx.push( /*#__PURE__*/React.createElement(ServerSideRender, {
        key: "wpforms-gutenberg-form-selector-server-side-renderer",
        block: "wpforms/form-selector",
        attributes: props.attributes
      }));
    } else if (preview) {
      jsx.push( /*#__PURE__*/React.createElement(Fragment, {
        key: "wpforms-gutenberg-form-selector-fragment-block-preview"
      }, /*#__PURE__*/React.createElement("img", {
        src: wpforms_gutenberg_form_selector.block_preview_url,
        style: {
          width: '100%'
        }
      })));
    } else {
      jsx.push( /*#__PURE__*/React.createElement(Placeholder, {
        key: "wpforms-gutenberg-form-selector-wrap",
        className: "wpforms-gutenberg-form-selector-wrap"
      }, /*#__PURE__*/React.createElement("img", {
        src: wpforms_gutenberg_form_selector.logo_url
      }), /*#__PURE__*/React.createElement("h3", null, wpforms_gutenberg_form_selector.strings.title), /*#__PURE__*/React.createElement(SelectControl, {
        key: "wpforms-gutenberg-form-selector-select-control",
        value: formId,
        options: formOptions,
        onChange: selectForm
      })));
    }
    return jsx;
  },
  save: function save() {
    return null;
  }
});
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJfd3AiLCJ3cCIsIl93cCRzZXJ2ZXJTaWRlUmVuZGVyIiwic2VydmVyU2lkZVJlbmRlciIsIlNlcnZlclNpZGVSZW5kZXIiLCJjb21wb25lbnRzIiwiX3dwJGVsZW1lbnQiLCJlbGVtZW50IiwiY3JlYXRlRWxlbWVudCIsIkZyYWdtZW50IiwicmVnaXN0ZXJCbG9ja1R5cGUiLCJibG9ja3MiLCJfcmVmIiwiYmxvY2tFZGl0b3IiLCJlZGl0b3IiLCJJbnNwZWN0b3JDb250cm9scyIsIl93cCRjb21wb25lbnRzIiwiU2VsZWN0Q29udHJvbCIsIlRvZ2dsZUNvbnRyb2wiLCJQYW5lbEJvZHkiLCJQbGFjZWhvbGRlciIsIndwZm9ybXNJY29uIiwid2lkdGgiLCJoZWlnaHQiLCJ2aWV3Qm94IiwiY2xhc3NOYW1lIiwiZmlsbCIsImQiLCJ0aXRsZSIsIndwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3IiLCJzdHJpbmdzIiwiZGVzY3JpcHRpb24iLCJpY29uIiwia2V5d29yZHMiLCJmb3JtX2tleXdvcmRzIiwiY2F0ZWdvcnkiLCJhdHRyaWJ1dGVzIiwiZm9ybUlkIiwidHlwZSIsImRpc3BsYXlUaXRsZSIsImRpc3BsYXlEZXNjIiwicHJldmlldyIsImV4YW1wbGUiLCJlZGl0IiwicHJvcHMiLCJfcHJvcHMkYXR0cmlidXRlcyIsIl9wcm9wcyRhdHRyaWJ1dGVzJGZvciIsIl9wcm9wcyRhdHRyaWJ1dGVzJGRpcyIsIl9wcm9wcyRhdHRyaWJ1dGVzJGRpczIiLCJfcHJvcHMkYXR0cmlidXRlcyRwcmUiLCJzZXRBdHRyaWJ1dGVzIiwiZm9ybU9wdGlvbnMiLCJmb3JtcyIsIm1hcCIsInZhbHVlIiwiSUQiLCJsYWJlbCIsInBvc3RfdGl0bGUiLCJqc3giLCJ1bnNoaWZ0IiwiZm9ybV9zZWxlY3QiLCJzZWxlY3RGb3JtIiwidG9nZ2xlRGlzcGxheVRpdGxlIiwidG9nZ2xlRGlzcGxheURlc2MiLCJSZWFjdCIsImtleSIsImZvcm1fc2V0dGluZ3MiLCJmb3JtX3NlbGVjdGVkIiwib3B0aW9ucyIsIm9uQ2hhbmdlIiwic2hvd190aXRsZSIsImNoZWNrZWQiLCJzaG93X2Rlc2NyaXB0aW9uIiwidXBkYXRlX3dwX25vdGljZV9oZWFkIiwidXBkYXRlX3dwX25vdGljZV90ZXh0IiwiaHJlZiIsInVwZGF0ZV93cF9ub3RpY2VfbGluayIsInJlbCIsInRhcmdldCIsImxlYXJuX21vcmUiLCJwdXNoIiwiYmxvY2siLCJzcmMiLCJibG9ja19wcmV2aWV3X3VybCIsInN0eWxlIiwibG9nb191cmwiLCJzYXZlIl0sInNvdXJjZXMiOlsiZmFrZV9hOWViZjkxNi5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyIvKiBnbG9iYWwgd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3RvciAqL1xuLyoganNoaW50IGVzMzogZmFsc2UsIGVzdmVyc2lvbjogNiAqL1xuXG4ndXNlIHN0cmljdCc7XG5cbmNvbnN0IHsgc2VydmVyU2lkZVJlbmRlcjogU2VydmVyU2lkZVJlbmRlciA9IHdwLmNvbXBvbmVudHMuU2VydmVyU2lkZVJlbmRlciB9ID0gd3A7XG5jb25zdCB7IGNyZWF0ZUVsZW1lbnQsIEZyYWdtZW50IH0gPSB3cC5lbGVtZW50O1xuY29uc3QgeyByZWdpc3RlckJsb2NrVHlwZSB9ID0gd3AuYmxvY2tzO1xuY29uc3QgeyBJbnNwZWN0b3JDb250cm9scyB9ID0gd3AuYmxvY2tFZGl0b3IgfHwgd3AuZWRpdG9yO1xuY29uc3QgeyBTZWxlY3RDb250cm9sLCBUb2dnbGVDb250cm9sLCBQYW5lbEJvZHksIFBsYWNlaG9sZGVyIH0gPSB3cC5jb21wb25lbnRzO1xuXG5jb25zdCB3cGZvcm1zSWNvbiA9IGNyZWF0ZUVsZW1lbnQoICdzdmcnLCB7IHdpZHRoOiAyMCwgaGVpZ2h0OiAyMCwgdmlld0JveDogJzAgMCA2MTIgNjEyJywgY2xhc3NOYW1lOiAnZGFzaGljb24nIH0sXG5cdGNyZWF0ZUVsZW1lbnQoICdwYXRoJywge1xuXHRcdGZpbGw6ICdjdXJyZW50Q29sb3InLFxuXHRcdGQ6ICdNNTQ0LDBINjhDMzAuNDQ1LDAsMCwzMC40NDUsMCw2OHY0NzZjMCwzNy41NTYsMzAuNDQ1LDY4LDY4LDY4aDQ3NmMzNy41NTYsMCw2OC0zMC40NDQsNjgtNjhWNjggQzYxMiwzMC40NDUsNTgxLjU1NiwwLDU0NCwweiBNNDY0LjQ0LDY4TDM4Ny42LDEyMC4wMkwzMjMuMzQsNjhINDY0LjQ0eiBNMjg4LjY2LDY4bC02NC4yNiw1Mi4wMkwxNDcuNTYsNjhIMjg4LjY2eiBNNTQ0LDU0NEg2OCBWNjhoMjIuMWwxMzYsOTIuMTRsNzkuOS02NC42bDc5LjU2LDY0LjZsMTM2LTkyLjE0SDU0NFY1NDR6IE0xMTQuMjQsMjYzLjE2aDk1Ljg4di00OC4yOGgtOTUuODhWMjYzLjE2eiBNMTE0LjI0LDM2MC40aDk1Ljg4IHYtNDguNjJoLTk1Ljg4VjM2MC40eiBNMjQyLjc2LDM2MC40aDI1NXYtNDguNjJoLTI1NVYzNjAuNEwyNDIuNzYsMzYwLjR6IE0yNDIuNzYsMjYzLjE2aDI1NXYtNDguMjhoLTI1NVYyNjMuMTZMMjQyLjc2LDI2My4xNnogTTM2OC4yMiw0NTcuM2gxMjkuNTRWNDA4SDM2OC4yMlY0NTcuM3onLFxuXHR9IClcbik7XG5cbnJlZ2lzdGVyQmxvY2tUeXBlKCAnd3Bmb3Jtcy9mb3JtLXNlbGVjdG9yJywge1xuXHR0aXRsZTogd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5zdHJpbmdzLnRpdGxlLFxuXHRkZXNjcmlwdGlvbjogd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5zdHJpbmdzLmRlc2NyaXB0aW9uLFxuXHRpY29uOiB3cGZvcm1zSWNvbixcblx0a2V5d29yZHM6IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3Iuc3RyaW5ncy5mb3JtX2tleXdvcmRzLFxuXHRjYXRlZ29yeTogJ3dpZGdldHMnLFxuXHRhdHRyaWJ1dGVzOiB7XG5cdFx0Zm9ybUlkOiB7XG5cdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHR9LFxuXHRcdGRpc3BsYXlUaXRsZToge1xuXHRcdFx0dHlwZTogJ2Jvb2xlYW4nLFxuXHRcdH0sXG5cdFx0ZGlzcGxheURlc2M6IHtcblx0XHRcdHR5cGU6ICdib29sZWFuJyxcblx0XHR9LFxuXHRcdHByZXZpZXc6IHtcblx0XHRcdHR5cGU6ICdib29sZWFuJyxcblx0XHR9LFxuXHR9LFxuXHRleGFtcGxlOiB7XG5cdFx0YXR0cmlidXRlczoge1xuXHRcdFx0cHJldmlldzogdHJ1ZSxcblx0XHR9LFxuXHR9LFxuXHRlZGl0KCBwcm9wcyApIHsgLy8gZXNsaW50LWRpc2FibGUtbGluZSBtYXgtbGluZXMtcGVyLWZ1bmN0aW9uXG5cdFx0Y29uc3QgeyBhdHRyaWJ1dGVzOiB7IGZvcm1JZCA9ICcnLCBkaXNwbGF5VGl0bGUgPSBmYWxzZSwgZGlzcGxheURlc2MgPSBmYWxzZSwgcHJldmlldyA9IGZhbHNlIH0sIHNldEF0dHJpYnV0ZXMgfSA9IHByb3BzO1xuXHRcdGNvbnN0IGZvcm1PcHRpb25zID0gd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5mb3Jtcy5tYXAoIHZhbHVlID0+IChcblx0XHRcdHsgdmFsdWU6IHZhbHVlLklELCBsYWJlbDogdmFsdWUucG9zdF90aXRsZSB9XG5cdFx0KSApO1xuXHRcdGNvbnN0IHN0cmluZ3MgPSB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLnN0cmluZ3M7XG5cdFx0bGV0IGpzeDtcblxuXHRcdGZvcm1PcHRpb25zLnVuc2hpZnQoIHsgdmFsdWU6ICcnLCBsYWJlbDogd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5zdHJpbmdzLmZvcm1fc2VsZWN0IH0gKTtcblxuXHRcdGZ1bmN0aW9uIHNlbGVjdEZvcm0oIHZhbHVlICkgeyAvLyBlc2xpbnQtZGlzYWJsZS1saW5lIGpzZG9jL3JlcXVpcmUtanNkb2Ncblx0XHRcdHNldEF0dHJpYnV0ZXMoIHsgZm9ybUlkOiB2YWx1ZSB9ICk7XG5cdFx0fVxuXG5cdFx0ZnVuY3Rpb24gdG9nZ2xlRGlzcGxheVRpdGxlKCB2YWx1ZSApIHsgLy8gZXNsaW50LWRpc2FibGUtbGluZSBqc2RvYy9yZXF1aXJlLWpzZG9jXG5cdFx0XHRzZXRBdHRyaWJ1dGVzKCB7IGRpc3BsYXlUaXRsZTogdmFsdWUgfSApO1xuXHRcdH1cblxuXHRcdGZ1bmN0aW9uIHRvZ2dsZURpc3BsYXlEZXNjKCB2YWx1ZSApIHsgLy8gZXNsaW50LWRpc2FibGUtbGluZSBqc2RvYy9yZXF1aXJlLWpzZG9jXG5cdFx0XHRzZXRBdHRyaWJ1dGVzKCB7IGRpc3BsYXlEZXNjOiB2YWx1ZSB9ICk7XG5cdFx0fVxuXG5cdFx0anN4ID0gW1xuXHRcdFx0PEluc3BlY3RvckNvbnRyb2xzIGtleT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItaW5zcGVjdG9yLWNvbnRyb2xzXCI+XG5cdFx0XHRcdDxQYW5lbEJvZHkgdGl0bGU9eyB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLnN0cmluZ3MuZm9ybV9zZXR0aW5ncyB9PlxuXHRcdFx0XHRcdDxTZWxlY3RDb250cm9sXG5cdFx0XHRcdFx0XHRsYWJlbD17IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3Iuc3RyaW5ncy5mb3JtX3NlbGVjdGVkIH1cblx0XHRcdFx0XHRcdHZhbHVlPXsgZm9ybUlkIH1cblx0XHRcdFx0XHRcdG9wdGlvbnM9eyBmb3JtT3B0aW9ucyB9XG5cdFx0XHRcdFx0XHRvbkNoYW5nZT17IHNlbGVjdEZvcm0gfVxuXHRcdFx0XHRcdC8+XG5cdFx0XHRcdFx0PFRvZ2dsZUNvbnRyb2xcblx0XHRcdFx0XHRcdGxhYmVsPXsgd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5zdHJpbmdzLnNob3dfdGl0bGUgfVxuXHRcdFx0XHRcdFx0Y2hlY2tlZD17IGRpc3BsYXlUaXRsZSB9XG5cdFx0XHRcdFx0XHRvbkNoYW5nZT17IHRvZ2dsZURpc3BsYXlUaXRsZSB9XG5cdFx0XHRcdFx0Lz5cblx0XHRcdFx0XHQ8VG9nZ2xlQ29udHJvbFxuXHRcdFx0XHRcdFx0bGFiZWw9eyB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLnN0cmluZ3Muc2hvd19kZXNjcmlwdGlvbiB9XG5cdFx0XHRcdFx0XHRjaGVja2VkPXsgZGlzcGxheURlc2MgfVxuXHRcdFx0XHRcdFx0b25DaGFuZ2U9eyB0b2dnbGVEaXNwbGF5RGVzYyB9XG5cdFx0XHRcdFx0Lz5cblx0XHRcdFx0XHQ8cCBjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1wYW5lbC1ub3RpY2VcIj5cblx0XHRcdFx0XHRcdDxzdHJvbmc+eyBzdHJpbmdzLnVwZGF0ZV93cF9ub3RpY2VfaGVhZCB9PC9zdHJvbmc+XG5cdFx0XHRcdFx0XHR7IHN0cmluZ3MudXBkYXRlX3dwX25vdGljZV90ZXh0IH0gPGEgaHJlZj17c3RyaW5ncy51cGRhdGVfd3Bfbm90aWNlX2xpbmt9IHJlbD1cIm5vcmVmZXJyZXJcIiB0YXJnZXQ9XCJfYmxhbmtcIj57IHN0cmluZ3MubGVhcm5fbW9yZSB9PC9hPlxuXHRcdFx0XHRcdDwvcD5cblxuXHRcdFx0XHQ8L1BhbmVsQm9keT5cblx0XHRcdDwvSW5zcGVjdG9yQ29udHJvbHM+LFxuXHRcdF07XG5cblx0XHRpZiAoIGZvcm1JZCApIHtcblx0XHRcdGpzeC5wdXNoKFxuXHRcdFx0XHQ8U2VydmVyU2lkZVJlbmRlclxuXHRcdFx0XHRcdGtleT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3Itc2VydmVyLXNpZGUtcmVuZGVyZXJcIlxuXHRcdFx0XHRcdGJsb2NrPVwid3Bmb3Jtcy9mb3JtLXNlbGVjdG9yXCJcblx0XHRcdFx0XHRhdHRyaWJ1dGVzPXsgcHJvcHMuYXR0cmlidXRlcyB9XG5cdFx0XHRcdC8+XG5cdFx0XHQpO1xuXHRcdH0gZWxzZSBpZiAoIHByZXZpZXcgKSB7XG5cdFx0XHRqc3gucHVzaChcblx0XHRcdFx0PEZyYWdtZW50XG5cdFx0XHRcdFx0a2V5PVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1mcmFnbWVudC1ibG9jay1wcmV2aWV3XCI+XG5cdFx0XHRcdFx0PGltZyBzcmM9eyB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLmJsb2NrX3ByZXZpZXdfdXJsIH0gc3R5bGU9e3sgd2lkdGg6ICcxMDAlJyB9fS8+XG5cdFx0XHRcdDwvRnJhZ21lbnQ+XG5cdFx0XHQpO1xuXHRcdH0gZWxzZSB7XG5cdFx0XHRqc3gucHVzaChcblx0XHRcdFx0PFBsYWNlaG9sZGVyXG5cdFx0XHRcdFx0a2V5PVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci13cmFwXCJcblx0XHRcdFx0XHRjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLXdyYXBcIj5cblx0XHRcdFx0XHQ8aW1nIHNyYz17IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3IubG9nb191cmwgfS8+XG5cdFx0XHRcdFx0PGgzPnsgd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5zdHJpbmdzLnRpdGxlIH08L2gzPlxuXHRcdFx0XHRcdDxTZWxlY3RDb250cm9sXG5cdFx0XHRcdFx0XHRrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLXNlbGVjdC1jb250cm9sXCJcblx0XHRcdFx0XHRcdHZhbHVlPXsgZm9ybUlkIH1cblx0XHRcdFx0XHRcdG9wdGlvbnM9eyBmb3JtT3B0aW9ucyB9XG5cdFx0XHRcdFx0XHRvbkNoYW5nZT17IHNlbGVjdEZvcm0gfVxuXHRcdFx0XHRcdC8+XG5cdFx0XHRcdDwvUGxhY2Vob2xkZXI+XG5cdFx0XHQpO1xuXHRcdH1cblxuXHRcdHJldHVybiBqc3g7XG5cdH0sXG5cdHNhdmUoKSB7XG5cdFx0cmV0dXJuIG51bGw7XG5cdH0sXG59ICk7XG4iXSwibWFwcGluZ3MiOiJBQUFBO0FBQ0E7O0FBRUEsWUFBWTs7QUFFWixJQUFBQSxHQUFBLEdBQWdGQyxFQUFFO0VBQUFDLG9CQUFBLEdBQUFGLEdBQUEsQ0FBMUVHLGdCQUFnQjtFQUFFQyxnQkFBZ0IsR0FBQUYsb0JBQUEsY0FBR0QsRUFBRSxDQUFDSSxVQUFVLENBQUNELGdCQUFnQixHQUFB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
},{}]},{},[1])