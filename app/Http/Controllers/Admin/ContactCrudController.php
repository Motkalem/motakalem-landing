<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Mhelper;
use App\Http\Requests\ContactUs\ContactUsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use function PHPSTORM_META\type;

/**
 * Class ContactCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ContactCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\ContactUs::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/contact');
        CRUD::setEntityNameStrings(Mhelper::t('contact'), Mhelper::t('contacts'));
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
            'name' => 'name',
            'type' => 'text',
            'label' => Mhelper::t('name'),
        ]);

        CRUD::addColumn([
            'name' => 'phone',
            'type' => 'text',
            'label' => Mhelper::t('phone'),
        ]);

        CRUD::addColumn([
            'name' => 'email',
            'type' => 'text',
            'label' => Mhelper::t('email'),
        ]);

        CRUD::addColumn([
            'name' => 'message', // Assuming you have a message field in your database
            'type' => 'text',
            'label' => Mhelper::t('message'),
        ]);
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }


    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ContactUsRequest::class);  // Your validation rules

        CRUD::addField([
            'name' => 'name',
            'label' => Mhelper::t('name'),
            'type' => 'text'
        ]);

        CRUD::addField([
            'name' => 'phone',
            'label' => Mhelper::t('phone'),
            'type' => 'number'
        ]);

        CRUD::addField([
            'name' => 'email',
            'label' => Mhelper::t('email'),
            'type' => 'email'
        ]);

        CRUD::addField([
            'name' => 'message',
            'label' => Mhelper::t('message'),
            'type' => 'textarea'
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
        $this->setupCreateOperation();
    }
}
