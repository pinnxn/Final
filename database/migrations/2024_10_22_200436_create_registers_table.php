<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registers', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->foreignId('user_id')->constrained('users')->nullable();
            $table->text('prefix_name');
            $table->text('name');
            $table->text('lastname');
            $table->text('nickname'); 
            $table->text('id_card');
            $table->text('date_of_birth');
            $table->text('gender');
            $table->integer('age');
            $table->text('nationality');
            $table->text('ethnicity');
            $table->text('address');
            $table->text('district');
            $table->text('province');
            $table->text('postcode');
            $table->text('shipping_address');
            $table->text('shipping_address_detail')->nullable();
            $table->string('phone_number');
            $table->string('email');
            $table->text('line_id');
            $table->text('facebook');
            $table->text('name_parent');
            $table->text('phone_parent');
            $table->text('sick');
            $table->text('sick_detail')->nullable();
            $table->text('name_emergency_contact');
            $table->text('phone_emergency_contact');
            $table->text('food_allergy');
            $table->text('food_allergy_detail')->nullable();
            $table->text('education_status');
            $table->text('name_education');
            $table->text('address_education');
            $table->text('province_education');
            $table->text('study_plan');
            $table->decimal('gpax', 3, 2);
            $table->decimal('gpa_english', 3, 2);
            $table->decimal('gpa_maths', 3, 2);
            $table->text('experience');
            $table->text('experience_other')->nullable();
            $table->text('reward');
            $table->text('hobby');
            $table->text('link_intro')->nullable();
            $table->text('link_transcript')->nullable();
            $table->text('link_portfolio')->nullable();
            $table->text('link_egd')->nullable();
            $table->text('news');
            $table->text('news_other')->nullable();
            $table->text('pdpa');
            $table->text('condition');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registers');
        
    }
};
