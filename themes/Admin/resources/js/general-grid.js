$(document).ready(() => {
  let selectedItem = null;
  let selectedItems = [];
  const isMultiple = window.opener !== null && !window.opener.single;
  $.ajaxSetup({
    headers: { Authorization: AuthorizationHeaderValue }
  });
  $('.jsShowUploadForm').on('click', (event) => {
    event.preventDefault();
    $('#my-dropzone').fadeToggle();
  });

  $('body').on('click', '.jsInsertImage', (e) => {
    e.preventDefault();
    if (window.opener.old) {
      if (window.opener.single) {
        if (selectedItem !== null) {
          window.opener.includeMediaSingleOld(
            selectedItem.id,
            selectedItem.path
          );
        }
        window.close();
      } else {
        $.each(selectedItems, (index, item) => {
          window.opener.includeMediaMultiple(
            item.id,
            item.path,
            item.type,
            item.mimetype
          );
        });
      }
    } else if (window.opener.single) {
      if (selectedItem !== null) {
        window.opener.includeMediaSingle(
          selectedItem.id,
          selectedItem.path,
          selectedItem.type,
          selectedItem.mimetype
        );
      }
      window.close();
    } else {
      $.each(selectedItems, (index, item) => {
        window.opener.includeMediaMultiple(
          item.id,
          item.path,
          item.type,
          item.mimetype
        );
      });
      window.close();
    }
  });
  $('body').on('click', '#thumbnails li', function (e) {
    const item = {
      id: $(this).data('id'),
      path: $(this).data('file-path'),
      type: $(this).data('mediaType'),
      mimetype: $(this).data('mimetype')
    };
    if (isMultiple) {
      if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        if (selectedItems.length > 0) {
          selectedItems = $.grep(selectedItems, value => value.id != item.id);
        } else {
          selectedItems.push(item);
        }
      } else {
        $(this).addClass('active');
        selectedItems.push(item);
      }
      $('#selected-count').html(
        selectedItems.length > 0 ? selectedItems.length : 0
      );
    } else {
      if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        selectedItem = null;
      } else {
        $('#thumbnails li').removeClass('active');
        $(this).addClass('active');
        selectedItem = item;
      }

      $('#selected-count').html(selectedItem !== null ? 1 : 0);
    }
  });
  function isSelectedItem(id) {
    let isIn = false;
    if (isMultiple) {
      if (selectedItems.length > 0) {
        $.each(selectedItems, (index, item) => {
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
  const cPage = 1;
  let prevPage = 1;
  let nextPage = 1;
  const per_page = 16;
  function loadMedias(page = 1) {
    page = parseInt(page);
    axios
      .get(
        `${MediaUrls.mediaGridSelectUrl
        }?page=${
          page
        }&per_page=${
          per_page}`
      )
      .then((rs) => {
        const items = rs.data.data;
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
        $('#cr-page').html(`${page}/${lastPage}`);
        $('#thumbnails').html('');
        $.each(items, (index, item) => {
          const cls = isSelectedItem(item.id) ? 'active' : '';

          const ele =                        `<li class="${
            cls
          }" data-id="${
            item.id
          }" data-file-path="${
            item.thumbnail
          }" data-mimetype="${
            item.mimetype
          }" data-media-type="${
            item.media_type
          }"><div class="preview">
            <button><span class="fa fa-check"></span></button>
            <div class="thumbnail">
            <div class="centered">${
  item.media_type === 'image' ? `<img src="${item.thumbnail}"/>` : `<div class="file"><i class="la la-file"></i><br>${item.filename}</div>`
}
          </div>
          </div>
          </div>
          </li>`;
          $('#thumbnails').append(ele);
        });
      })
      .catch((error) => {
        console.log(error);
      });
  }

  loadMedias();

  $('.load-media').click(function () {
    const page = $(this).attr('data-page');
    loadMedias(page);
  });
});
