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
        'category_id',
        'passport_size_photo',
        'birth_certificate',
        'letter_of_recommendation',
        'status',
        'marks',
        'token_number',
        'admit_status'
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
        'Other'=>'Other'
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($application) {
            $application->application_id = self::generateUniqueApplicationId($application->category_id);
        });
    }

    /**
     * Generate a unique application ID in the format APQ + last two digits of year + category number + 4-digit serial.
     *
     * @param int $categoryId
     * @return string
     */
    protected static function generateUniqueApplicationId($categoryId)
    {
        $year = date('y'); // last two digits of current year
        $category = str_pad($categoryId, 1, '0', STR_PAD_LEFT); // category number, can pad if needed

        // Find the last application for this year and category
        $lastApplication = self::whereRaw('SUBSTRING(application_id, 4, 2) = ?', [$year])
            ->whereRaw('SUBSTRING(application_id, 6, 1) = ?', [$categoryId])
            ->orderBy('application_id', 'desc')
            ->first();

        if ($lastApplication) {
            $lastSerial = (int)substr($lastApplication->application_id, 7, 4);
            $newSerial = $lastSerial + 1;
        } else {
            $newSerial = 1;
        }

        $serial = str_pad($newSerial, 4, '0', STR_PAD_LEFT);
        return 'APQ' . $year . $category . $serial;
    }
    
}
