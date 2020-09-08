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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/media.js":
/*!*******************************!*\
  !*** ./resources/js/media.js ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  var $fileCount = $('.jsFileCount');
  var sortableWrapper = $('.jsThumbnailImageWrapper');
  var sortableSelection = sortableWrapper.not('.jsSingleThumbnailWrapper'); // This comes from new-file-link-single

  if (typeof window.openMediaWindowSingle === 'undefined') {
    window.mediaZone = '';

    window.openMediaWindowSingle = function (event, zone) {
      window.single = true;
      window.old = false;
      window.mediaZone = zone;
      window.zoneWrapper = $(event.currentTarget).siblings('.jsThumbnailImageWrapper');
      window.open(MediaUrls.mediaGridSelectUrl, '_blank', 'menubar=no,status=no,toolbar=no,scrollbars=yes,height=550,width=1000');
    };
  }

  if (typeof window.includeMediaSingle === 'undefined') {
    window.includeMediaSingle = function (mediaId, filePath, mediaType, mimetype) {
      var mediaPlaceholder;

      if (mediaType === 'image') {
        mediaPlaceholder = "<img src=\"".concat(filePath, "\" alt=\"\"/>");
      } else if (mediaType === 'video') {
        mediaPlaceholder = "<video src=\"".concat(filePath, "\" controls width=\"320\"></video>");
      } else if (mediaType === 'audio') {
        mediaPlaceholder = "<audio controls><source src=\"".concat(filePath, "\" type=\"").concat(mimetype, "\"></audio>");
      } else {
        mediaPlaceholder = "<div class=\"file\"><i class=\"fa fa-file\" style=\"font-size: 50px;\"></i><br>".concat(filePath, "</div>");
      }

      var html = "<li data-id=\"".concat(mediaId, "\">") + '<div class="preview">\n' + "<button class=\"jsRemoveSimpleLink\" data-id=\"".concat(mediaId, "\"><i class=\"fa fa-times\"></i></button>") + '                <div class="thumbnail">\n' + "                    <div class=\"centered\">".concat(mediaPlaceholder, "</div></div></div>") + '</li>';
      window.zoneWrapper.append(html).fadeIn('slow', function () {
        toggleButton($(this));
      });
      window.zoneWrapper.children('input').val(mediaId);
    };
  } // This comes from new-file-link-multiple


  if (typeof window.openMediaWindowMultiple === 'undefined') {
    window.mediaZone = '';

    window.openMediaWindowMultiple = function (event, zone) {
      window.single = false;
      window.old = false;
      window.mediaZone = zone;
      window.zoneWrapper = $(event.currentTarget).siblings('.jsThumbnailImageWrapper');
      window.open(MediaUrls.mediaGridSelectUrl, '_blank', 'menubar=no,status=no,toolbar=no,scrollbars=yes,height=500,width=1000');
    };
  }

  if (typeof window.includeMediaMultiple === 'undefined') {
    window.includeMediaMultiple = function (mediaId, filePath, mediaType, mimetype) {
      var mediaPlaceholder;
      var ids = [];
      var wpElm = window.zoneWrapper.find('#orders');

      if (wpElm.val() !== undefined && wpElm.val() !== '') {
        ids = window.zoneWrapper.find('#orders').val().split(',');
      }

      if (ids.indexOf(String(mediaId)) === -1) {
        if (mediaType === 'image') {
          mediaPlaceholder = "<img src=\"".concat(filePath, "\" alt=\"\"/>");
        } else if (mediaType === 'video') {
          mediaPlaceholder = "<video src=\"".concat(filePath, "\" controls width=\"320\"></video>");
        } else if (mediaType === 'audio') {
          mediaPlaceholder = "<audio controls><source src=\"".concat(filePath, "\" type=\"").concat(mimetype, "\"></audio>");
        } else {
          mediaPlaceholder = "<div class=\"file\"><i class=\"fa fa-file\" style=\"font-size: 50px;\"></i><br>".concat(filePath, "</div>");
        }

        var html = "<li data-id=\"".concat(mediaId, "\"><div class=\"preview\">\n") + "<button class=\"jsRemoveLink\" data-id=\"".concat(mediaId, "\"><i class=\"fa fa-times\"></i></button>") + '                <div class="thumbnail">\n' + "                    <div class=\"centered\">".concat(mediaPlaceholder, "<input type=\"hidden\" name=\"medias_multi[").concat(window.mediaZone, "][files][]\" value=\"").concat(mediaId, "\">") + '</div></div></div></li>';
        ids.push(String(mediaId));
        window.zoneWrapper.append(html).fadeIn(); // window.zoneWrapper.trigger('sortupdate', [mediaId]);

        if ($fileCount.length > 0) {
          var count = parseInt($fileCount.text(), 10);
          $fileCount.text(count + 1);
        }

        window.zoneWrapper.find('#orders').val(ids.join(','));
      }
    };
  } // This comes from new-file-link-multiple


  sortableWrapper.on('click', '.jsRemoveLink', function (e) {
    e.preventDefault();
    var pictureWrapper = $(this).parent().parent();
    var pictureSortable = pictureWrapper.parent();
    var mId = $(this).data('id');
    pictureWrapper.fadeOut().remove();
    pictureSortable.trigger('sortupdate');
    var wpElm = pictureSortable.find('#orders');

    if (wpElm !== undefined && wpElm.val() !== undefined) {
      var ids = pictureSortable.find('#orders').val().split(',');
      var idIndex = ids.indexOf(String(mId));

      if (idIndex !== -1) {
        var tmpIds = [];

        for (var mI in ids) {
          if (ids.hasOwnProperty(mI) && String(idIndex) !== mI) {
            tmpIds.push(ids[mI]);
          }
        }

        ids = tmpIds;
        wpElm.val(ids.join(','));
      }
    }

    if ($fileCount.length > 0) {
      var count = parseInt($fileCount.text(), 10);
      $fileCount.text(count - 1);
    }
  }); // This comes from new-file-link-single

  sortableWrapper.off('click', '.jsRemoveSimpleLink');
  sortableWrapper.on('click', '.jsRemoveSimpleLink', function (e) {
    e.preventDefault();
    $(e.delegateTarget).fadeOut('slow', function () {
      toggleButton($(this));
    }).children('li').remove();
    $(e.delegateTarget).children('input').val('');
  });

  function toggleButton(el) {
    var browseButton = el.parent().find('.btn-browse');
    browseButton.toggle();
  }
});

/***/ }),

/***/ 1:
/*!*************************************!*\
  !*** multi ./resources/js/media.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Volumes/Data/www/webvicms/themes/Admin/resources/js/media.js */"./resources/js/media.js");


/***/ })

/******/ });