<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joins', function (Blueprint $table) {

            $table->id();

            $table->string('name');
            $table->enum('type',['ذكر','انثي']);
            $table->string('nationality');
            $table->integer('age');
            $table->string('address');
            $table->integer('phone');
            $table->integer('another_phone');
            $table->string('email');
            $table->enum('severe_stuttering',['متوسطة','خفيفة','شديدة']);
            $table->enum('effect_stuttering_social_life',['متوسطة','خفيفة','شديدة']);
            $table->enum('impact_stuttering_professional_study_life',['متوسطة','خفيفة','شديدة']);
            $table->enum('excited_overcome_stuttering',['متوسطة','خفيفة','شديدة']);
            $table->enum('have_physical_disability',['yes','no']);
            $table->string('type_disability')->nullable();

            $table->enum('have_physical_mental_illness',['yes','no']);
            $table->string('type_disease')->nullable();

            $table->enum('anything_related_health',['yes','no']);
            $table->string('notice')->nullable();

            $table->enum('treatments_entered_club_anything_related_stuttering_before',['yes','no']);
            $table->string('write_down_notes_dates')->nullable();

            $table->enum('anything_out_it',['yes','no']);
            $table->string('write_what_got')->nullable();
            $table->string('write_reasons_not_benefiting')->nullable();

            $table->enum('how_find_out_about_us',['website','social','man','other']);
            $table->string('improvement_points_ideas_like_change_programs_clubs')->nullable();

            $table->string('admin_note')->nullable();
            $table->string('is_read')->default(false);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('joins');
    }
};


/*

$table->string('name');
$table->enum('type',['male','female']);
$table->string('nationality');
$table->integer('age');
$table->string('address');
$table->integer('phone');
$table->integer('another_phone');
$table->string('email');
$table->enum('severe_stuttering',['متوسطة','خفيفة','شديدة']);
$table->enum('effect_stuttering_social_life',['متوسطة','خفيفة','شديدة']);
$table->enum('impact_stuttering_professional_study_life',['متوسطة','خفيفة','شديدة']);
$table->enum('excited_overcome_stuttering',['متوسطة','خفيفة','شديدة']);
$table->enum('have_physical_disability',['yes','no']);
$table->string('type_disability')->nullable();

$table->enum('have_physical_mental_illness',['yes','no']);
$table->string('type_disease')->nullable();

$table->enum('anything_related_health',['yes','no']);
$table->string('notice')->nullable();

$table->enum('treatments_entered_club_anything_related_stuttering_before',['yes','no']);
$table->string('write_down_notes_dates')->nullable();

$table->enum('anything_out_it',['yes','no']);
$table->string('write_what_got')->nullable();
$table->string('write_reasons_not_benefiting')->nullable();

$table->enum('how_find_out_about_us',['website','social','man','other']);
$table->string('improvement_points_ideas_like_change_programs_clubs')->nullable();

$table->string('admin_note')->nullable();
$table->string('is_read')->default(false);
*/
