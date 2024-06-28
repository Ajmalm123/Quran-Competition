<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

     /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($recitation) {
            $recitation->application_id = self::generateUniqueApplicationId();
        });
    }

   /**
     * Generate a unique application ID.
     *
     * @return string
     */
    protected static function generateUniqueApplicationId()
    {
        do {
            $applicationId = self::generateShortId();
        } while (self::where('application_id', $applicationId)->exists());

        return $applicationId;
    }

    /**
     * Generate a random string of 10 characters (alphanumeric).
     *
     * @return string
     */
    protected static function generateShortId()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $length = 10;
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}
