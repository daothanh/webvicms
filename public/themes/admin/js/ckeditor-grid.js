/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/ckeditor-grid.js":
/*!***************************************!*\
  !*** ./resources/js/ckeditor-grid.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  var selectedItem = null;
  var selectedItems = [];
  var isMultiple = false;
  $.ajaxSetup({
    headers: {
      Authorization: AuthorizationHeaderValue
    }
  });
  $('.jsShowUploadForm').on('click', function (event) {
    event.preventDefault();
    $('#my-dropzone').fadeToggle();
  });
  $('body').on('click', '.jsInsertImage', function (e) {
    e.preventDefault();

    function getUrlParam(paramName) {
      var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
      var match = window.location.search.match(reParam);
      return match && match.length > 1 ? match[1] : null;
    }

    var funcNum = getUrlParam('CKEditorFuncNum');
    window.opener.CKEDITOR.tools.callFunction(funcNum, selectedItem.path);
    window.close();
  });
  $('body').on('click', '#thumbnails li', function (e) {
    var item = {
      id: $(this).data('id'),
      path: $(this).data('file-path'),
      type: $(this).data('mediaType'),
      mimetype: $(this).data('mimetype')
    };

    if ($(this).hasClass('active')) {
      $(this).removeClass('active');
      selectedItem = null;
    } else {
      $('#thumbnails li').removeClass('active');
      $(this).addClass('active');
      selectedItem = item;
    }

    $('#selected-count').html(selectedItem !== null ? 1 : 0);

    if (selectedItem === null) {
      $('.jsInsertImage').attr('disabled', 'disabled');
    } else {
      $('.jsInsertImage').removeAttr('disabled');
    }
  });

  function isSelectedItem(id) {
    var isIn = false;

    if (isMultiple) {
      if (selectedItems.length > 0) {
        $.each(selectedItems, function (index, item) {
          if (item.id === id) {
            isIn = true;
          }
        });
      }
    } else if (selectedItem !== null) {
      isIn = id === selectedItem.id;
    }

    return isIn;
  }

  var cPage = 1;
  var prevPage = 1;
  var nextPage = 1;
  var per_page = 25;

  function loadMedias() {
    var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 1;
    var search = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
    page = parseInt(page);
    axios.get("".concat(MediaUrls.mediaGridSelectUrl, "?page=").concat(page, "&per_page=").concat(per_page, "&search=").concat(search)).then(function (rs) {
      var items = rs.data.data;

      if (items !== undefined && items.length > 0) {
        prevPage = page - 1;

        if (prevPage < 1) {
          prevPage = 1;
        }

        lastPage = Math.ceil(rs.data.recordsTotal / per_page);
        nextPage = page + 1;
        console.log(nextPage, lastPage);

        if (nextPage > lastPage) {
          nextPage = lastPage;
        }

        $('#chevron-left').attr('data-page', prevPage);
        $('#chevron-right').attr('data-page', nextPage);
        $('#cr-page').html("".concat(page, "/").concat(lastPage));
        $('#thumbnails').html('');
        $.each(items, function (index, item) {
          var cls = isSelectedItem(item.id) ? 'active' : '';
          var ele = "<li title=\"".concat(item.filename, "\" class=\"").concat(cls, "\" data-id=\"").concat(item.id, "\" data-file-path=\"").concat(item.thumbnail, "\" data-mimetype=\"").concat(item.mimetype, "\" data-media-type=\"").concat(item.media_type, "\"><div class=\"preview\">\n            <button><span class=\"fa fa-check\"></span></button>\n            <div class=\"thumbnail\">\n            <div class=\"centered\">").concat(item.media_type === 'image' ? "<img src=\"".concat(item.thumbnail, "\"/>") : "<div class=\"file\"><i class=\"far fa-file\"></i></div>", "\n          </div>\n\n          </div>\n          </div>\n          <div class=\"file-name\">").concat(item.filename, "</div>\n          </li>");
          $('#thumbnails').append(ele);
        });
      } else {
        $('#thumbnails').html('Không tìm thấy file nào');
      }
    })["catch"](function (error) {
      console.log(error);
    });
  }

  loadMedias();
  $('.load-media').click(function () {
    var page = $(this).attr('data-page');
    loadMedias(page);
  });
  $('#search-file').keyup(function () {
    loadMedias(1, $(this).value);
  });
});

/***/ }),

/***/ 3:
/*!*********************************************!*\
  !*** multi ./resources/js/ckeditor-grid.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/dao/www/webvicms/themes/Admin/resources/js/ckeditor-grid.js */"./resources/js/ckeditor-grid.js");


/***/ })

/******/ });