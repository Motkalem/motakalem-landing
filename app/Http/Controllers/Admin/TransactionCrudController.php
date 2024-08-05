<?php

namespace App\Http\Controllers\Admin;

 use App\CPU\Mhelper;
 use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TransactionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TransactionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
     use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
       
        CRUD::setModel(\App\Models\Transaction::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/transaction');
        CRUD::setEntityNameStrings(Mhelper::t('transaction'), Mhelper::t('transactions'));
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name' => 'transaction_id',
            'type' => 'text',
            'label' => Mhelper::t('transaction ID'),
        ]);
        CRUD::addColumn([
            'name' => 'client_pay_order_id',
            'type' => 'text',
            'label' => __('client ID'),
        ]);
        CRUD::addColumn([
            'name' => 'success',
            'type' => 'text',
            'label' => Mhelper::t('status'),
        ]);
        CRUD::addColumn([
            'name' => 'amount',
            'type' => 'text',
            'label' => Mhelper::t('amount'),
        ]);

        CRUD::enableResponsiveTable();
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            // 'name' => 'required|min:2',
        ]);

        CRUD::field('transaction_id');
        CRUD::field('client_pay_order_id');
        CRUD::field('success');
        CRUD::field('amount');
        CRUD::field('status');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    protected function setupShowOperation()
    {
        CRUD::addColumn([
            'name' => 'transaction_id',
            'type' => 'text',
            'label' => Mhelper::t('transaction ID'),
        ]);
        CRUD::addColumn([
            'name' => 'client_pay_order_id',
            'type' => 'text',
            'label' => __('client ID'),
        ]);
        CRUD::addColumn([
            'name' => 'success',
            'type' => 'text',
            'label' => Mhelper::t('status'),
         ]);
        CRUD::addColumn([
            'name' => 'amount',
            'type' => 'text',
            'label' => Mhelper::t('amount'),
        ]);

        CRUD::addColumn([
            'name'  => 'created_at',
            'label' => __('created at'),
        ]);
    }



}
