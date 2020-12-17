const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/admin/app.js', 'public/js/admin.js')
   .sass('resources/sass/app.scss', 'public/css')
   .scripts([
        'resources/js/helpers.js',
        'resources/js/myApp.js',
   ], 'public/js/my-app-and-helpers.js')
   .scripts([
        'resources/js/demands/create.js',
        'resources/js/awards/create.js',
        'resources/js/save-button.js'
   ], 'public/js/demands/create-edit-demand.js')
   .scripts([
        'resources/js/payment-manuals/create.js',
        'resources/js/save-button.js'
   ], 'public/js/payment-manuals/create-edit-payment-manual.js')
   .scripts([
       'resources/js/save-button.js',
   ], 'public/js/banks/create-edit-bank.js')
   .scripts([
      'resources/js/demands/index.js',
   ], 'public/js/demands/index-demand.js')
   .scripts([
      'resources/js/clients/index.js'
   ], 'public/js/clients/index-client.js')
   .scripts([
      'resources/js/clients/create.js',
      'resources/js/save-button.js'
   ], 'public/js/clients/create-edit-client.js')
   .scripts([
      'resources/js/providers/index.js'
   ], 'public/js/providers/index-provider.js')
   .scripts([
      'resources/js/providers/create.js',
      'resources/js/save-button.js'
   ], 'public/js/providers/create-edit-provider.js')
   .scripts([
      'resources/js/managers/index.js'
   ], 'public/js/managers/index-manager.js')
   .scripts([
      'resources/js/managers/create.js',
      'resources/js/save-button.js'
   ], 'public/js/managers/create-edit-manager.js')
   .scripts([
        'resources/js/bills/create.js',
        'resources/js/save-button.js'
   ], 'public/js/bills/create-edit-bill.js')
   .scripts([
    'resources/js/bills/index.js',
    'resources/js/save-button.js'
    ], 'public/js/bills/index-bill.js')
   .scripts([
       'resources/js/cash_flows/index.js',
       'resources/js/save-button.js'
   ], 'public/js/cash_flows/index-cash-flow.js')
   .scripts([
      'resources/js/demands/index.js'
   ], 'public/js/receives/index-receive.js')
   .scripts([
    'resources/js/confirm.js',
    'resources/js/shipments/deposit-account.js'
 ], 'public/js/shipments/deposit-account.js')
 .scripts([
    'resources/js/confirm.js',
 ], 'public/js/shipments/confirm.js')
 .scripts([
    'resources/js/confirm.js',
    'resources/js/shipments/acesso-card.js'
 ], 'public/js/shipments/acesso-card.js')
 .scripts([
    'resources/js/confirm.js',
    'resources/js/shipments/acesso-card-shopping.js'
 ], 'public/js/shipments/acesso-card-shopping.js')
   .scripts([
      'resources/js/receives/create.js',
      'resources/js/save-button.js'
   ], 'public/js/receives/create-edit-receive.js')
   .scripts([
      'resources/js/awards/create.js',
      'resources/js/save-button.js'
   ], 'public/js/awards/create-edit-award.js')
   .scripts([
        'resources/js/confirm.js',
        'resources/js/awards/show.js'
   ], 'public/js/awards/show-spreadsheets.js')
   .scripts([
       'resources/js/notes/create.js',
       'resources/js/save-button.js'
   ], 'public/js/notes/create-edit-notes.js')
   .scripts([
        'resources/js/transfer/create.js',
        'resources/js/save-button.js'
    ], 'public/js/transfer/create-edit-transfer.js')
    .scripts([
        'resources/js/manual-deposits/create.js',
        'resources/js/save-button.js'
    ], 'public/js/manual-deposits/create-edit-manual.js')
    .scripts([
        'resources/js/call-center/create.js',
        'resources/js/save-button.js'
    ], 'public/js/call-center/create-edit-call-center.js')
   .extract(['vue', 'jquery']);
