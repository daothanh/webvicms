$(document).ready(() => {
    let selectedItem = null;
    let selectedItems = [];
    const isMultiple = false;
    $.ajaxSetup({
        headers: {Authorization: AuthorizationHeaderValue}
    });
    $('.jsShowUploadForm').on('click', (event) => {
        event.preventDefault();
        $('#my-dropzone').fadeToggle();
    });

    $('body').on('click', '.jsInsertImage', (e) => {
        e.preventDefault();
        function getUrlParam(paramName) {
            var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
            var match = window.location.search.match(reParam);

            return (match && match.length > 1) ? match[1] : null;
        }

        var funcNum = getUrlParam('CKEditorFuncNum');

        window.opener.CKEDITOR.tools.callFunction(funcNum, selectedItem.path);
        window.close();
    });
    $('body').on('click', '#thumbnails li', function (e) {
        const item = {
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
    const per_page = 25;

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

                    const ele = `<li title="${item.filename}" class="${
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
                        item.media_type === 'image' ? `<img src="${item.thumbnail}"/>` : `<div class="file"><i class="far fa-file"></i></div>`
                    }
          </div>

          </div>
          </div>
          <div class="file-name">${item.filename}</div>
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
