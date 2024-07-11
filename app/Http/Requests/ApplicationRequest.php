<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

class ApplicationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if (isset($this->date_of_birth)) {
            $this->merge([
                'date_of_birth' => date('Y-m-d', strtotime($this->date_of_birth))
            ]);
        }
        if (isset($this->zone) || isset($this->abroad_zone)) {
            $this->merge([
                'zone' => $this->primary_competition_participation == 'Native' ? $this->zone : $this->abroad_zone
            ]);
        }
    }

    public function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'mother_tongue' => 'required|in:Malayalam,Other',
            'educational_qualification' => 'required|in:SSLC,Plus Two,Degree,Above Degree',
            'aadhar_number' => 'required|numeric|digits:12',
            'job' => 'nullable|string|max:100',
            'contact_number' => 'required|string|regex:/^\d{10}$/',
            'whatsapp' => 'nullable|string|regex:/^\d{10}$/',
            'email' => 'required|string|email|max:255',
            'c_address' => 'required|string',
            'pr_address' => 'required|string',
            'district' => 'required|in:Kasaragod,Kannur,Wayanad,Kozhikode,Malappuram,Palakkad,Thrissur,Ernakulam,Idukki,Kottayam,Alappuzha,Pathanamthitta,Kollam,Thiruvananthapuram',
            'pincode' => 'nullable|string|size:6',
            'institution_name' => 'nullable|string',
            'is_completed_ijazah' => 'required|in:Yes,No',
            'qirath_with_ijazah' => 'nullable|string',
            'primary_competition_participation' => 'required|in:Native,Abroad',
            'zone' => 'required',
            'passport_size_photo' => 'required|mimes:jpg,jpeg,max:100', // Max file size in kilobytes
            // File::image()->dimensions(
            //     Rule::dimensions()
            //         ->minWidth(150)
            //         ->maxWidth(600)
            //         ->minHeight(200)
            //         ->maxHeight(800)
            //         ->ratio(3 / 2)
            // ),
            'birth_certificate' => 'required|mimes:pdf,jpg,jpeg|max:2048',
            'letter_of_recommendation' => 'required|mimes:pdf,jpg,jpeg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'The full name is required.',
            'full_name.string' => 'The full name must be a string.',
            'full_name.max' => 'The full name may not be greater than 255 characters.',
            'gender.required' => 'The gender is required.',
            'gender.in' => 'Invalid gender selected.',
            'date_of_birth.required' => 'The date of birth is required.',
            'date_of_birth.date' => 'The date of birth must be a valid date.',
            'mother_tongue.required' => 'The mother tongue is required.',
            'mother_tongue.in' => 'Invalid mother tongue selected.',
            'educational_qualification.required' => 'The educational qualification is required.',
            'educational_qualification.in' => 'Invalid educational qualification selected.',
            'aadhar_number.required' => 'The Aadhaar number is required.',
            'aadhar_number.numeric' => 'The Aadhaar number must be numeric.',
            'aadhar_number.digits' => 'The Aadhaar number must be exactly 12 digits long.',
            'job.string' => 'The job must be a string.',
            'job.max' => 'The job may not be greater than 100 characters.',
            'contact_number.required' => 'The contact number is required.',
            'contact_number.string' => 'The contact number must be a string.',
            'contact_number.regex' => 'The contact number must contain exactly 10 digits.',
            'whatsapp.string' => 'The WhatsApp number must be a string.',
            'whatsapp.regex' => 'The WhatsApp number must contain exactly 10 digits.',
            'email.required' => 'The email address is required.',
            'email.string' => 'The email address must be a string.',
            'email.email' => 'The email address must be a valid email address.',
            'email.max' => 'The email address may not be greater than 255 characters.',
            'c_address.required' => 'The current address is required.',
            'c_address.string' => 'The current address must be a string.',
            'pr_address.required' => 'The permanent address is required.',
            'pr_address.string' => 'The permanent address must be a string.',
            'district.required' => 'The district is required.',
            'district.in' => 'Invalid district selected.',
            'pincode.string' => 'The pincode must be a string.',
            'pincode.size' => 'The pincode must be exactly 6 characters long.',
            'institution_name.string' => 'The institution name must be a string.',
            'is_completed_ijazah.required' => 'The completion status of Ijazah is required.',
            'is_completed_ijazah.in' => 'Invalid completion status of Ijazah selected.',
            'qirath_with_ijazah.string' => 'The Qirat with Ijazah must be a string.',
            'primary_competition_participation.required' => 'The primary competition participation is required.',
            'primary_competition_participation.in' => 'Invalid primary competition participation selected.',
            'zone.required' => 'The zone is required.',
            'passport_size_photo.required' => 'A passport size photo is required.',
            'passport_size_photo.mimes' => 'The photo must be a JPG or JPEG file.',
            'passport_size_photo.max' => 'The photo must not be larger than 100 kilobytes.',
            'passport_size_photo.image' => 'The file must be an image.',
            'passport_size_photo.dimensions' => 'The photo must be between 150-600px width and 200-800px height with a 3:2 aspect ratio.',
            'birth_certificate.required' => 'The birth certificate is required.',
            'birth_certificate.mimes' => 'The birth certificate must be a PDF, JPG, JPEG, or PNG file.',
            'birth_certificate.max' => 'The birth certificate must not be larger than 2 MB.',
            'letter_of_recommendation.required' => 'The letter of recommendation is required.',
            'letter_of_recommendation.mimes' => 'The letter of recommendation must be a PDF, JPG, JPEG, or PNG file.',
            'letter_of_recommendation.max' => 'The letter of recommendation must not be larger than 2 MB.',
        ];
    }

    // public function afterValidation()
    // {
    //     $this->zone = $this->abroad_zone ? $this->abroad_zone : $this->zone;
    // }
    // public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    // {
    //     dd($validator->errors());
    // }
}
