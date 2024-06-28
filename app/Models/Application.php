<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id', 'full_name', 'gender', 'date_of_birth', 'mother_tongue', 'educational_qualification', 
        'aadhar_number', 'job', 'contact_number', 'whatsapp', 'email', 'c_address', 'pr_address', 'district', 
        'pincode', 'institution_name', 'is_completed_ijazah', 'qirath_with_ijazah', 'primary_competition_participation', 
        'zone', 'passport_size_photo', 'birth_certificate', 'letter_of_recommendation'
    ];

    const GENDER = [
        'Male' => 'Male',
        'Female' => 'Female',
    ];

    const MOTHERTONGUE = [
        'Malayalam' => 'Malayalam',
        'Other' => 'Other',
    ];
    const EDUCATION_QUALIFICATION = [
        'SSLC' => 'SSLC',
        'Plus Two' => 'Plus Two',
        'Degree' => 'Degree',
        'Above Degree' => 'Above Degree',
    ];

    const DISTRICT = [
        'Kasaragod' => 'Kasaragod',
        'Kannur' => 'Kannur',
        'Wayanad' => 'Wayanad',
        'Kozhikode' => 'Kozhikode',
        'Malappuram' => 'Malappuram',
        'Palakkad' => 'Palakkad',
        'Thrissur' => 'Thrissur',
        'Ernakulam' => 'Ernakulam',
        'Idukki' => 'Idukki',
        'Kottayam' => 'Kottayam',
        'Alappuzha' => 'Alappuzha',
        'Pathanamthitta' => 'Pathanamthitta',
        'Kollam' => 'Kollam',
        'Thiruvananthapuram' => 'Thiruvananthapuram'
    ];

    const IS_COMPLETED_IJAZAH=[
        'Yes'=>'Yes',
        'No'=>'No'
    ];

    const PRIMARY_COMPETITION_PARTICIPATION=[
      'Kerala'=>'Kerala',
      'native'=>'native'
    ];

    const ZONE = [
        'Kollam' => 'Kollam',
        'Ernakulam' => 'Ernakulam',
        'Malappuram' => 'Malappuram',
        'Kannur' => 'Kannur',
        'Jeddah' => 'Jeddah',
        'Dubai' => 'Dubai',
        'Doha' => 'Doha',
        'Bahrain' => 'Bahrain',
        'Muscat' => 'Muscat',
        'Kuwait' => 'Kuwait'
    ];
}
