<?php

namespace App\Models;

use App\Models\Zone;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'application_id',
        'full_name',
        'gender',
        'date_of_birth',
        'mother_tongue',
        'educational_qualification',
        'aadhar_number',
        'job',
        'contact_number',
        'whatsapp',
        'email',
        'c_address',
        'pr_address',
        'district',
        'pincode',
        'institution_name',
        'is_completed_ijazah',
        'qirath_with_ijazah',
        'primary_competition_participation',
        'zone_id',
        'passport_size_photo',
        'birth_certificate',
        'letter_of_recommendation',
        'status',
        'marks'
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

    const IS_COMPLETED_IJAZAH = [
        'Yes' => 'Yes',
        'No' => 'No'
    ];

    const PRIMARY_COMPETITION_PARTICIPATION = [
        'Native' => 'Native',
        'Abroad' => 'Abroad'
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

    const STATUS = [
        'Created' => 'Created',
        'Approved' => 'Approved',
        'Rejected' => 'Rejected',
        'withheld' => 'withheld',
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

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
        // Fetch the last used application ID from the database
        $lastApplicationId = self::getLastApplicationId();

        // If no ID exists, start from the initial value
        if (!$lastApplicationId) {
            return 'APQ241001';
        }

        // Increment the last application ID
        $newApplicationId = self::incrementApplicationId($lastApplicationId);

        // Return the new unique application ID
        return $newApplicationId;
    }

    /**
     * Get the last used application ID from the database.
     *
     * @return string|null
     */
    protected static function getLastApplicationId()
    {
        // Adjust this query according to your database setup
        $lastRecord = self::orderBy('application_id', 'desc')->first();
        return $lastRecord ? $lastRecord->application_id : null;
    }

    /**
     * Increment the application ID.
     *
     * @param string $applicationId
     * @return string
     */
    protected static function incrementApplicationId($applicationId)
    {
        // Extract the numeric part of the ID
        $numericPart = substr($applicationId, 5);

        // Increment the numeric part
        $incrementedNumericPart = (int) $numericPart + 1;

        // Format the new ID with leading zeros
        $newApplicationId = 'APQ24' . str_pad($incrementedNumericPart, 4, '0', STR_PAD_LEFT);

        return $newApplicationId;
    }
    
}
