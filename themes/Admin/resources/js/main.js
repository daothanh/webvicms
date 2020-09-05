window.Popper = require('popper.js').default;
window.$ = window.jQuery = require('jquery');
window.bootstrap = require('bootstrap')
window.axios = require('axios')
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

let token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
  window.$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': token.content}
  })
}

let userApiToken = document.head.querySelector('meta[name="user-api-token"]')
if (userApiToken) {
  window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + userApiToken.content
  window.$.ajaxSetup({
    headers: {'Authorization': 'Bearer ' + userApiToken.content}
  })
}

window.Dropzone = require('dropzone')
window.Dropzone.autoDiscover = false
window.Swal = require('sweetalert2')
window.swal = function (config) {
  return Swal.fire(config);
};
window.$.fn.DataTable = require('admin-lte/plugins/datatables/jquery.dataTables.min')
require('admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min')
require('admin-lte/plugins/select2/js/select2.full.min')
window.toastr = require('toastr');
window.slugify = function(string) {
  let value;
  // string = string.toString();
  value = string.replace(/^\s+|\s+$/g, ''); // trim
  value = value.toLowerCase();

  // remove accents, swap ñ for n, etc
  const from = 'äëïîöüûñçáàảạãăắằẳẵặâấầẩẫậéèẻẽẹêếềểễệíìỉĩịóòỏõọôốồổỗộơớờởỡợúùủũụưứừửữựýỳỷỹỵđ·/_,:;';
  const to = 'aeiiouuncaaaaaaaaaaaaaaaaaeeeeeeeeeeeiiiiiooooooooooooooooouuuuuuuuuuuyyyyyd------';
  for (let i = 0, l = from.length; i < l; i++) {
    value = value.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
  }

  value = value.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
    .replace(/\s+/g, '-') // collapse whitespace and replace by -
    .replace(/-+/g, '-'); // collapse dashes

  return value;
};

window.notify = function($title, $message, $type = 'success') {
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  };

  toastr[$type]($message, $title);
};

require('admin-lte/dist/js/adminlte.min')
