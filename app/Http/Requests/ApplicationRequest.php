<?php

namespace App\Http\Requests;

use App\Rules\PassportSizePhoto;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'date_of_birth' => date('Y-m-d', strtotime($this->date_of_birth))
        ]);
    }

    public function rules()
    {
        dd($this);
        return [
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'mother_tongue' => 'required|in:Malayalam,Other',
            'educational_qualification' => 'required|in:SSLC,Plus Two,Degree,Above Degree',
            'aadhar_number' => 'required|numeric|digits_between:12,12',
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
            'zone' => 'required|in:Kollam,Ernakulam,Malappuram,Kannur,Jeddah,Dubai,Doha,Bahrain,Muscat,Kuwait',
            'passport_size_photo' => ['required', 'mimes:jpg,jpeg', new PassportSizePhoto()],
            'birth_certificate' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
            'letter_of_recommendation' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'application_id.required' => 'The application ID is required.',
            'application_id.unique' => 'The application ID must be unique.',
            'application_id.max' => 'The application ID may not be greater than 20 characters.',
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
            'aadhar_number.digits_between' => 'The Aadhaar number must be exactly 12 digits long.',
            'job nullable' => 'The job field is optional.',
            'job.string' => 'The job must be a string.',
            'job.max' => 'The job may not be greater than 100 characters.',
            'contact_number.required' => 'The contact number is required.',
            'contact_number.string' => 'The contact number must be a string.',
            'contact_number.regex' => 'The contact number must contain exactly 10 digits.',
            'whatsapp nullable' => 'The WhatsApp number field is optional.',
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
            'pincode nullable' => 'The pincode field is optional.',
            'pincode.string' => 'The pincode must be a string.',
            'pincode.size' => 'The pincode must be exactly 6 characters long.',
            'institution_name nullable' => 'The institution name field is optional.',
            'institution_name.string' => 'The institution name must be a string.',
            'is_completed_ijazah.required' => 'The completion status of Ijazah is required.',
            'is_completed_ijazah.in' => 'Invalid completion status of Ijazah selected.',
            'qirath_with_ijazah nullable' => 'The Qirat with Ijazah field is optional.',
            'qirath_with_ijazah.string' => 'The Qirat with Ijazah must be a string.',
            'primary_competition_participation.required' => 'The primary competition participation is required.',
            'primary_competition_participation.in' => 'Invalid primary competition participation selected.',
            'zone.required' => 'The zone is required.',
            'zone.in' => 'Please select zone.',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        dd($validator->errors());
    }
}
