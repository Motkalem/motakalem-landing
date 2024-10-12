<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Mhelper;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Validation\Rule;
/**
 * Class StudentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StudentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
      use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
      #use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
      #use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
      #use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Student::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/client-pay-order');
        CRUD::setEntityNameStrings(Mhelper::t(('student')), Mhelper::t(('students')));
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
            'name' => 'id',
            'type' => 'text',
            'label' => __('client ID'),

        ]);
        CRUD::addColumn([
            'name' => 'name',
            'type' => 'text',
            'label' => Mhelper::t('name'),
        ]);
        CRUD::addColumn([
            'name' => 'is_paid',
            'type' => 'text',
            'label' => __('paid status'),
        ]);
        CRUD::addColumn([
            'name' => 'age',
            'type' => 'text',
            'label' => __('age'),
        ]);
        CRUD::addColumn([
            'name' => 'phone',
            'type' => 'text',
            'label' => __('phone'),
        ]);

        CRUD::addColumn([
            'name' => 'city',
            'type' => 'text',
            'label' => __('city'),
        ]);

        CRUD::addColumn([
            'name' => 'created_at',
            'type' => 'text',
            'label' => __('created at'),
        ]);


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
             'name' => 'required|min:2',
             'age' => 'required|max:100',
             'phone' => 'required|digits:10|unique:students,phone',
             'email' => 'required|email|unique:students,email',
             'city' => 'required|string|max:50',
        ]);

        CRUD::field('name')->label(__('Name'));
        CRUD::field('age')->label(__('age'));
        CRUD::field('phone')->label(__('phone'));
        CRUD::field('email')->type('email')->label(__('Email'));
        CRUD::field('city')->label(__('city'));
    }

    protected function setupShowOperation()
    {

        CRUD::addColumn([
            'name'  => 'id',
            'label' => __('client ID'),
            'type'  => 'text',
         ]);

        CRUD::addColumn([
            'name'  => 'name',
            'label' => __('Name'),
            'type'  => 'text',
         ]);

        CRUD::addColumn([
            'name'  => 'age',
            'label' => __('age'),
          ]);

          CRUD::addColumn([
              'name'  => 'phone',
              'label' => __('phone'),
            ]);

            CRUD::addColumn([
                'name'  => 'email',
                'label' => __('Email'),
              ]);

        CRUD::addColumn([
            'name'  => 'city',
            'label' => __('city'),
          ]);

        CRUD::addColumn([
            'name'  => 'created_at',
            'label' => __('created at'),
          ]);


    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {

        CRUD::setValidation([
            'name' => 'required|min:2',
            'age' => 'required|max:100',
            'phone' => Rule::unique('students', 'phone')->ignore(request('id')),
            'email' => Rule::unique('students', 'email')->ignore(request('id')),
            'city' => 'required|string|max:50',
       ]);


       CRUD::field('name')->label(__('Name'));
       CRUD::field('age')->label(__('age'));
       CRUD::field('phone')->label(__('phone'));
       CRUD::field('email')->label(__('email'));
       CRUD::field('city')->label(__('city'));

    }
}
