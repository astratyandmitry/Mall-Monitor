window.numeral = require('numeral');

global.$ = global.jQuery = require('jquery');

$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});
