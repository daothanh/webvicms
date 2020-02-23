$(document).ready(() => {
  const $fileCount = $('.jsFileCount');
  const sortableWrapper = $('.jsThumbnailImageWrapper');
  const sortableSelection = sortableWrapper.not('.jsSingleThumbnailWrapper');

  // This comes from new-file-link-single
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
      let mediaPlaceholder;

      if (mediaType === 'image') {
        mediaPlaceholder = `<img src="${filePath}" alt=""/>`;
      } else if (mediaType === 'video') {
        mediaPlaceholder = `<video src="${filePath}" controls width="320"></video>`;
      } else if (mediaType === 'audio') {
        mediaPlaceholder = `<audio controls><source src="${filePath}" type="${mimetype}"></audio>`;
      } else {
        mediaPlaceholder = `<div class="file"><i class="fa fa-file" style="font-size: 50px;"></i><br>${filePath}</div>`;
      }

      const html = `<li data-id="${mediaId}">`
                + '<div class="preview">\n'
                + `<button class="jsRemoveSimpleLink" data-id="${mediaId}"><i class="fa fa-times"></i></button>`
                + '                <div class="thumbnail">\n'
                + `                    <div class="centered">${mediaPlaceholder
                }</div></div></div>`
                + '</li>';
      window.zoneWrapper.append(html).fadeIn('slow', function () {
        toggleButton($(this));
      });
      window.zoneWrapper.children('input').val(mediaId);
    };
  }

  // This comes from new-file-link-multiple
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
      let mediaPlaceholder;
      let ids = [];
      const wpElm = window.zoneWrapper.find('#orders');
      if (wpElm.val() !== undefined && wpElm.val() !== '') {
        ids = window.zoneWrapper.find('#orders').val().split(',');
      }
      if (ids.indexOf(String(mediaId)) === -1) {
        if (mediaType === 'image') {
          mediaPlaceholder = `<img src="${filePath}" alt=""/>`;
        } else if (mediaType === 'video') {
          mediaPlaceholder = `<video src="${filePath}" controls width="320"></video>`;
        } else if (mediaType === 'audio') {
          mediaPlaceholder = `<audio controls><source src="${filePath}" type="${mimetype}"></audio>`;
        } else {
          mediaPlaceholder = `<div class="file"><i class="fa fa-file" style="font-size: 50px;"></i><br>${filePath}</div>`;
        }

        const html = `<li data-id="${mediaId}"><div class="preview">\n`
                    + `<button class="jsRemoveLink" data-id="${mediaId}"><i class="fa fa-times"></i></button>`
                    + '                <div class="thumbnail">\n'
                    + `                    <div class="centered">${mediaPlaceholder
                    }<input type="hidden" name="medias_multi[${window.mediaZone}][files][]" value="${mediaId}">`
                    + '</div></div></div></li>';

        ids.push(String(mediaId));
        window.zoneWrapper.append(html).fadeIn();
        // window.zoneWrapper.trigger('sortupdate', [mediaId]);
        if ($fileCount.length > 0) {
          const count = parseInt($fileCount.text(), 10);
          $fileCount.text(count + 1);
        }
        window.zoneWrapper.find('#orders').val(ids.join(','));
      }
    };
  }
  // This comes from new-file-link-multiple
  sortableWrapper.on('click', '.jsRemoveLink', function (e) {
    e.preventDefault();
    const pictureWrapper = $(this).parent().parent();
    const pictureSortable = pictureWrapper.parent();
    const mId = $(this).data('id');

    pictureWrapper.fadeOut().remove();
    pictureSortable.trigger('sortupdate');
    const wpElm = pictureSortable.find('#orders');
    if (wpElm !== undefined && wpElm.val() !== undefined) {
      let ids = pictureSortable.find('#orders').val().split(',');
      const idIndex = ids.indexOf(String(mId));
      if (idIndex !== -1) {
        const tmpIds = [];
        for (const mI in ids) {
          if (ids.hasOwnProperty(mI) && String(idIndex) !== mI) {
            tmpIds.push(ids[mI]);
          }
        }
        ids = tmpIds;
        wpElm.val(ids.join(','));
      }
    }

    if ($fileCount.length > 0) {
      const count = parseInt($fileCount.text(), 10);
      $fileCount.text(count - 1);
    }
  });
  // This comes from new-file-link-single
  sortableWrapper.off('click', '.jsRemoveSimpleLink');
  sortableWrapper.on('click', '.jsRemoveSimpleLink', (e) => {
    e.preventDefault();
    $(e.delegateTarget).fadeOut('slow', function () {
      toggleButton($(this));
    }).children('li').remove();
    $(e.delegateTarget).children('input').val('');
  });

  function toggleButton(el) {
    const browseButton = el.parent().find('.btn-browse');
    browseButton.toggle();
  }
});
