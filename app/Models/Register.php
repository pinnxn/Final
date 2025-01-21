<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'prefix_name', 'name', 'lastname', 'nickname', 'id_card', 'date_of_birth', 
     'gender','age','nationality', 'ethnicity', 'address', 'district', 'province', 'postcode', 'shipping_address', 'shipping_address_detail',
     'phone_number', 'email', 'line_id', 'facebook', 'name_parent', 'phone_parent', 'sick', 'sick_detail', 
     'name_emergency_contact', 'phone_emergency_contact', 'food_allergy', 'food_allergy_detail', 'education_status', 
     'name_education', 'address_education', 'province_education', 'study_plan', 'gpax', 'gpa_english', 'gpa_maths', 
     'experience', 'experience_other', 'reward', 'hobby', 'link_intro', 'link_transcript', 'link_portfolio', 'link_egd','news',
     'news_other' ,'pdpa', 'condition',];

    protected $casts = [
        'news' => 'array',
        'food_allergy' => 'array',
        'experience' => 'array',
    ];
}
