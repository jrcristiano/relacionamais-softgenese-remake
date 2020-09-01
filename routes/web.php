<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', function () {
    return redirect()->route('admin.home');
});

Route::group(['prefix' => 'admin'], function () {
    Auth::routes(['register' => false]);

    Route::group(['middleware' => 'auth', 'as' => 'admin.'], function () {

        Route::group(['prefix' => '/api', 'as' => 'api.'], function () {
            Route::group(['prefix' => '/client'], function () {
                Route::get('/{cnpj}', 'Api\\ClientControllerApi@show');
            });

            Route::group(['prefix' => '/shipment'], function () {
                Route::post('/store', 'Api\\ShipmentControllerApi@store')->name('shipment-api.store');
                Route::put('/{id}/update', 'Api\\ShipmentControllerApi@update')->name('shipment-api.update');
            });

            Route::group(['prefix' => '/spreadsheet'], function () {
                Route::put('/{id}/update', 'Api\\SpreadsheetControllerApi@update')->name('spreadsheet-api.update');
            });

            Route::group(['prefix' => '/demand'], function () {
                Route::get('/award-value/{awardValue}/cnpj/{cnpj}', 'Api\\DemandControllerApi@show')->name('demand-api.show');
                Route::post('/store', 'Api\\DemandControllerApi@store')->name('demand-api.store');
            });

            Route::group(['prefix' => '/award', 'as' => 'award.'], function () {
                Route::post('/store', 'Api\\AwardControllerApi@store')->name('award-api.store');
            });

            Route::group(['prefix' => '/acesso-card'], function () {
                Route::post('/store', 'Api\\AcessoCardControllerApi@store')->name('acesso-card-api.store');
            });
        });

        Route::get('/home', 'DemandController@index')->name('home');
        Route::get('/pedido', 'DemandController@create')->name('create');
        Route::post('/pedido/salvar', 'DemandController@store')->name('store');
        Route::get('/pedido/{id}/editar', 'DemandController@edit')->name('edit');
        Route::get('/pedido/{id}', 'DemandController@show')->name('show');
        Route::put('/pedido/{id}/atualizar', 'DemandController@update')->name('update');
        Route::post('/pedido/{id}/remover', 'DemandController@destroy')->name('destroy');

        Route::group(['prefix' => 'cadastrar', 'as' => 'register.'], function () {

            Route::get('/clientes', 'ClientController@index')->name('clients');
            Route::get('/cliente', 'ClientController@create')->name('clients.create');
            Route::post('/cliente/salvar', 'ClientController@store')->name('clients.store');
            Route::get('/cliente/{id}/editar/', 'ClientController@edit')->name('clients.edit');
            Route::put('/cliente/{id}/atualizar/', 'ClientController@update')->name('clients.update');
            Route::get('/cliente/{id}', 'ClientController@show')->name('clients.show');
            Route::post('/cliente/{id}/remover', 'ClientController@destroy')->name('clients.destroy');

            Route::get('/premiado', 'AwardController@create')->name('awardeds.create');
            Route::post('/premiado/salvar', 'AwardController@store')->name('awardeds.store');
            Route::get('/premiado/{id}/editar/', 'AwardController@edit')->name('awardeds.edit');
            Route::put('/premiado/{id}/atualizar/', 'AwardController@update')->name('awardeds.update');
            Route::get('/premiado/{id}', 'AwardController@show')->name('awardeds.show');
            Route::post('/premiado/{id}/remover', 'AwardController@destroy')->name('awardeds.destroy');

            Route::get('/pagamento-manual', 'PaymentManualController@create')->name('payment-manuals.create');
            Route::post('/pagamento-manual/salvar', 'PaymentManualController@store')->name('payment-manuals.store');
            Route::put('/pagamento-manual/{id}/atualizar', 'PaymentManualController@update')->name('payment-manuals.update');

            Route::get('/cartao-acesso', 'AcessoCardController@create')->name('acesso-cards.create');
            Route::post('/cartao-acesso/salvar', 'AcessoCardController@store')->name('acesso-cards.store');
            Route::get('/cartao-acesso/{id}', 'AcessoCardController@show')->name('acesso-cards.show');
            Route::put('/cartao-acesso/{id}/atualizar', 'AcessoCardController@update')->name('acesso-cards.update');
            Route::post('/cartao-acesso/{id}/remover', 'AcessoCardController@destroy')->name('acesso-cards.destroy');

            Route::get('/deposito-manual', 'ManualDepositController@create')->name('manual-deposits.create');
            Route::post('/deposito-manual/salvar', 'ManualDepositController@store')->name('manual-deposits.store');
            Route::get('/deposito-manual/{id}/editar', 'ManualDepositController@edit')->name('manual-deposits.edit');
            Route::put('/deposito-manual/{id}/atualizar', 'ManualDepositController@update')->name('manual-deposits.update');
            Route::post('/deposito-manual/{id}/remover', 'ManualDepositController@destroy')->name('manual-deposits.delete');

            Route::get('/planilha/{id}/editar', 'SpreadsheetController@edit')->name('spreadsheets.edit');
            Route::put('/planilha/{id}/atualizar', 'SpreadsheetController@update')->name('spreadsheets.update');
            Route::post('/planilha/{id}/remover', 'SpreadsheetController@destroy')->name('spreadsheets.delete');

            Route::get('/fornecedores', 'ProviderController@index')->name('providers');
            Route::get('/fornecedor', 'ProviderController@create')->name('providers.create');
            Route::post('/fornecedor/salvar', 'ProviderController@store')->name('providers.store');
            Route::get('/fornecedor/{id}/editar/', 'ProviderController@edit')->name('providers.edit');
            Route::put('/fornecedor/{id}/', 'ProviderController@update')->name('providers.update');
            Route::post('/fornecedor/{id}/remover', 'ProviderController@destroy')->name('providers.destroy');

            Route::get('/gerentes', 'ManagerController@index')->name('managers');
            Route::get('/gerente', 'ManagerController@create')->name('managers.create');
            Route::post('/gerente/salvar', 'ManagerController@store')->name('managers.store');
            Route::get('/gerente/{id}/editar/', 'ManagerController@edit')->name('managers.edit');
            Route::put('/gerente/{id}/atualizar/', 'ManagerController@update')->name('managers.update');
            Route::post('/gerente/{id}/remover', 'ManagerController@destroy')->name('managers.destroy');

            Route::get('/bancos', 'BankController@index')->name('banks');
            Route::get('/banco', 'BankController@create')->name('banks.create');
            Route::post('/banco/salvar', 'BankController@store')->name('banks.store');
            Route::get('/banco/{id}/editar/', 'BankController@edit')->name('banks.edit');
            Route::put('/banco/{id}/atualizar/', 'BankController@update')->name('banks.update');
        });

        Route::group(['prefix' => 'financeiro', 'as' => 'financial.'], function () {
            Route::get('/fornecedores', 'ClientController@index')->name('providers');

            Route::get('/contas-a-pagar', 'BillController@index')->name('bills');
            Route::get('/conta-a-pagar', 'BillController@create')->name('bills.create');
            Route::post('/conta-a-pagar/salvar', 'BillController@store')->name('bills.store');
            Route::get('/conta-a-pagar/{id}', 'BillController@show')->name('bills.show');
            Route::get('/conta-a-pagar/{id}/editar', 'BillController@edit')->name('bills.edit');
            Route::put('/conta-a-pagar/{id}/atualizar', 'BillController@update')->name('bills.update');
            Route::post('/conta-a-pagar/{id}/remover', 'BillController@destroy')->name('bills.destroy');

            Route::get('/remessas', 'ShipmentController@index')->name('shipments');

            Route::get('/contas-a-receber', 'ReceiveController@index')->name('receives');
            Route::get('/conta-a-receber', 'ReceiveController@create')->name('receives.create');
            Route::get('/conta-a-receber/{id}', 'ReceiveController@show')->name('receives.show');
            Route::post('/conta-a-receber/salvar', 'ReceiveController@store')->name('receives.store');
            Route::get('/conta-a-receber/{id}/editar', 'ReceiveController@edit')->name('receives.edit');
            Route::post('/conta-a-receber/{id}', 'ReceiveController@destroy')->name('receives.destroy');
            Route::put('/conta-a-receber/{id}/atualizar', 'ReceiveController@update')->name('receives.update');

            Route::get('/fluxo-de-caixa', 'CashFlowController@index')->name('cash-flows');

            Route::get('/comissoes', 'ComissionController@index')->name('comissions');

            Route::get('/transferencias', 'TransferController@index')->name('transfers');
            Route::get('/transferencia', 'TransferController@create')->name('transfers.create');
            Route::post('/transferencia', 'TransferController@store')->name('transfers.store');
            Route::get('/transferencia/{id}/editar', 'TransferController@edit')->name('transfers.edit');
            Route::put('/transferencia/{id}/atualizar', 'TransferController@update')->name('transfers.update');
            Route::post('/transferencia/{id}/delete', 'TransferController@destroy')->name('transfers.destroy');

            Route::get('/nota-fiscal', 'NoteController@create')->name('notes.create');
            Route::post('/nota-fiscal/salvar', 'NoteController@store')->name('notes.store');
            Route::get('/nota-fiscal/{id}/editar', 'NoteController@edit')->name('notes.edit');
            Route::put('/nota-fiscal/{id}/atualizar', 'NoteController@update')->name('notes.update');
            Route::post('/nota-fiscal/{id}/delete', 'NoteController@destroy')->name('notes.destroy');

            Route::get('/recebimento', 'NoteReceiptController@create')->name('note-receipts.create');
            Route::post('/recebimento/salvar', 'NoteReceiptController@store')->name('note-receipts.store');
            Route::get('/recebimento/{id}/editar', 'NoteReceiptController@edit')->name('note-receipts.edit');
            Route::put('/recebimento/{id}/atualizar', 'NoteReceiptController@update')->name('note-receipts.update');
            Route::post('/recebimento/{id}/delete', 'NoteReceiptController@destroy')->name('note-receipts.delete');

        });

        Route::group(['prefix' => 'operacional', 'as' => 'operational.'], function () {
            Route::get('/central-de-atendimento', 'CallCenterController@index')->name('call-center');
        });
    });
});
