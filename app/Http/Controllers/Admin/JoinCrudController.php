<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Mhelper;
use App\Http\Requests\JoinRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use function PHPSTORM_META\type;

/**
 * Class JoinCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class JoinCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Join::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/join');
        CRUD::setEntityNameStrings(Mhelper::t('join'), Mhelper::t('joins'));
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        CRUD::addColumn(
            [
                'name'=> 'is_read',
                'type' => 'boolean',
                'label'=>Mhelper::t('is_read'),
            ]
        );

        CRUD::addColumn([
            'name'=>'name',
            'type'=>'text',
            'label'=>Mhelper::t('name'),
        ]);
        CRUD::addColumn([
            'name'=>'type',
            'type'=>'text',
            'label'=>Mhelper::t('type'),
        ]);
        CRUD::addColumn([
            'name'=>'nationality',
            'type'=>'text',
            'label'=>Mhelper::t('nationality'),
        ]);
        CRUD::addColumn([
            'name'=>'age',
            'type'=>'text',
            'label'=>Mhelper::t('age'),
        ]);
        CRUD::addColumn([
            'name'=>'address',
            'type'=>'text',
            'label'=>Mhelper::t('address'),
        ]);
        CRUD::addColumn([
            'name'=>'phone',
            'type'=>'text',
            'label'=>Mhelper::t('phone'),
        ]);
        CRUD::addColumn([
            'name'=>'another_phone',
            'type'=>'text',
            'label'=>Mhelper::t('another_phone'),
        ]);
        CRUD::addColumn([
            'name'=>'email',
            'type'=>'text',
            'label'=>Mhelper::t('email'),
        ]);
        CRUD::addColumn([
            'name'=>'severe_stuttering',
            'type'=>'text',
            'label'=>Mhelper::t('severe_stuttering'),
        ]);
        CRUD::addColumn([
            'name'=>'effect_stuttering_social_life',
            'type'=>'text',
            'label'=>Mhelper::t('effect_stuttering_social_life'),
        ]);
        CRUD::addColumn([
            'name'=>'impact_stuttering_professional_study_life',
            'type'=>'text',
            'label'=>Mhelper::t('impact_stuttering_professional_study_life'),
        ]);
        CRUD::addColumn([
            'name'=>'excited_overcome_stuttering',
            'type'=>'text',
            'label'=>Mhelper::t('excited_overcome_stuttering'),
        ]);
        CRUD::addColumn([
            'name'=>'have_physical_disability',
            'type'=>'text',
            'label'=>Mhelper::t('have_physical_disability'),
        ]);
        CRUD::addColumn([
            'name'=>'type_disability',
            'type'=>'text',
            'label'=>Mhelper::t('type_disability'),
        ]);
        CRUD::addColumn([
            'name'=>'have_physical_mental_illness',
            'type'=>'text',
            'label'=>Mhelper::t('have_physical_mental_illness'),
        ]);
        CRUD::addColumn([
            'name'=>'type_disease',
            'type'=>'text',
            'label'=>Mhelper::t('type_disease'),
        ]);
        CRUD::addColumn([
            'name'=>'anything_related_health',
            'type'=>'text',
            'label'=>Mhelper::t('anything_related_health'),
        ]);
        CRUD::addColumn([
            'name'=>'notice',
            'type'=>'text',
            'label'=>Mhelper::t('notice'),
        ]);
        CRUD::addColumn([
            'name'=>'treatments_entered_club_anything_related_stuttering_before',
            'type'=>'text',
            'label'=>Mhelper::t('treatments_entered_club_anything_related_stuttering_before'),
        ]);
        CRUD::addColumn([
            'name'=>'write_down_notes_dates',
            'type'=>'text',
            'label'=>Mhelper::t('write_down_notes_dates'),
        ]);
        CRUD::addColumn([
            'name'=>'anything_out_it',
            'type'=>'text',
            'label'=>Mhelper::t('anything_out_it'),
        ]);
        CRUD::addColumn([
            'name'=>'write_what_got',
            'type'=>'text',
            'label'=>Mhelper::t('write_what_got'),
        ]);
        CRUD::addColumn([
            'name'=>'write_reasons_not_benefiting',
            'type'=>'text',
            'label'=>Mhelper::t('write_reasons_not_benefiting'),
        ]);
        CRUD::addColumn([
            'name'=>'how_find_out_about_us',
            'type'=>'text',
            'label'=>Mhelper::t('how_find_out_about_us'),
        ]);
        CRUD::addColumn([
            'name'=>'improvement_points_ideas_like_change_programs_clubs',
            'type'=>'text',
            'label'=>Mhelper::t('improvement_points_ideas_like_change_programs_clubs'),
        ]);
        CRUD::addColumn([
            'name'=>'admin_note',
            'type'=>'text',
            'label'=>Mhelper::t('admin_note'),
        ]);
        CRUD::addColumn([
            'name'=>'created_at',
            'type'=>'text',
            'label'=>Mhelper::t('created_at'),
        ]);
        CRUD::addColumn([
            'name'=>'updated_at',
            'type'=>'text',
            'label'=>Mhelper::t('updated_at'),
        ]);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
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
        CRUD::setValidation(JoinRequest::class);

        CRUD::addField([
            'name'=>'name',
            'label'=>Mhelper::t('name'),
            'type'=>'text'
        ]);
        CRUD::addField([
            'name'=>'is_read',
            'label'=>Mhelper::t('is_read'),
            'type'=>'switch',
            'default'=>false
        ]);
        CRUD::addField([
            'name'=>'type',
            'label'=>Mhelper::t('type'),
            'type'=>'select_from_array',
            'options'     => ['ذكر' => 'ذكر', 'انثي' => 'انثي'],
        ]);
        CRUD::addField([
            'name'=>'nationality',
            'label'=>Mhelper::t('nationality'),
            'type'=>'text'
        ]);
        CRUD::addField([
            'name'=>'age',
            'label'=>Mhelper::t('age'),
            'type'=>'number'
        ]);
        CRUD::addField([
            'name'=>'address',
            'label'=>Mhelper::t('address'),
            'type'=>'text'
        ]);
        CRUD::addField([
            'name'=>'phone',
            'label'=>Mhelper::t('phone'),
            'type'=>'number'
        ]);
        CRUD::addField([
            'name'=>'another_phone',
            'label'=>Mhelper::t('another_phone'),
            'type'=>'number'
        ]);
        CRUD::addField([
            'name'=>'email',
            'label'=>Mhelper::t('email'),
            'type'=>'email'
        ]);
        CRUD::addField([
            'name'=>'severe_stuttering',
            'label'=>Mhelper::t('severe_stuttering'),
            'type'=>'select_from_array',
            'options'     => ['شديدة' => 'شديدة', 'خفيفة' => 'خفيفة','متوسطة'=>'متوسطة'],
        ]);
        CRUD::addField([
            'name'=>'effect_stuttering_social_life',
            'label'=>Mhelper::t('effect_stuttering_social_life'),
            'type'=>'select_from_array',
            'options'     => ['شديدة' => 'شديدة', 'خفيفة' => 'خفيفة','متوسطة'=>'متوسطة'],
        ]);
        CRUD::addField([
            'name'=>'impact_stuttering_professional_study_life',
            'label'=>Mhelper::t('impact_stuttering_professional_study_life'),
            'type'=>'select_from_array',
            'options'     => ['شديدة' => 'شديدة', 'خفيفة' => 'خفيفة','متوسطة'=>'متوسطة'],
        ]);
        CRUD::addField([
            'name'=>'excited_overcome_stuttering',
            'label'=>Mhelper::t('excited_overcome_stuttering'),
            'type'=>'select_from_array',
            'options'     => ['شديدة' => 'شديدة', 'خفيفة' => 'خفيفة','متوسطة'=>'متوسطة'],
        ]);
        CRUD::addField([
            'name'=>'have_physical_disability',
            'label'=>Mhelper::t('have_physical_disability'),
            'type'=>'select_from_array',
            'options'     => ['yes' => 'Yes', 'no' => 'No'],
        ]);
        CRUD::addField([
            'name'=>'type_disability',
            'label'=>Mhelper::t('type_disability'),
            'type'=>'text'
        ]);
        CRUD::addField([
            'name'=>'have_physical_mental_illness',
            'label'=>Mhelper::t('have_physical_mental_illness'),
            'type'=>'select_from_array',
            'options'     => ['yes' => 'Yes', 'no' => 'No'],
        ]);
        CRUD::addField([
            'name'=>'type_disease',
            'label'=>Mhelper::t('type_disease'),
            'type'=>'text'
        ]);
        CRUD::addField([
            'name'=>'anything_related_health',
            'label'=>Mhelper::t('anything_related_health'),
            'type'=>'select_from_array',
            'options'     => ['yes' => 'Yes', 'no' => 'No'],
        ]);
        CRUD::addField([
            'name'=>'notice',
            'label'=>Mhelper::t('notice'),
            'type'=>'text'
        ]);
        CRUD::addField([
            'name'=>'treatments_entered_club_anything_related_stuttering_before',
            'label'=>Mhelper::t('treatments_entered_club_anything_related_stuttering_before'),
            'type'=>'select_from_array',
            'options'     => ['yes' => 'Yes', 'no' => 'No'],
        ]);
        CRUD::addField([
            'name'=>'write_down_notes_dates',
            'label'=>Mhelper::t('write_down_notes_dates'),
            'type'=>'text'
        ]);
        CRUD::addField([
            'name'=>'anything_out_it',
            'label'=>Mhelper::t('anything_out_it'),
            'type'=>'select_from_array',
            'options'     => ['yes' => 'Yes', 'no' => 'No'],
        ]);
        CRUD::addField([
            'name'=>'write_what_got',
            'label'=>Mhelper::t('write_what_got'),
            'type'=>'text'
        ]);
        CRUD::addField([
            'name'=>'write_reasons_not_benefiting',
            'label'=>Mhelper::t('write_reasons_not_benefiting'),
            'type'=>'text'
        ]);
        CRUD::addField([
            'name'=>'how_find_out_about_us',
            'label'=>Mhelper::t('how_find_out_about_us'),
            'type'=>'select_from_array',
            'options'     => ['website' => 'ويب سايت', 'social' => 'سوشيال','man'=>'شخص','other'=>'اخر'],
        ]);
        CRUD::addField([
            'name'=>'improvement_points_ideas_like_change_programs_clubs',
            'label'=>Mhelper::t('improvement_points_ideas_like_change_programs_clubs'),
            'type'=>'text'
        ]);
        CRUD::addField([
            'name'=>'admin_note',
            'label'=>Mhelper::t('admin_note'),
            'type'=>'textarea'
        ]);


        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
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
