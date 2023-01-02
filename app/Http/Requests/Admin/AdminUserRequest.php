<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:6|max:255',
            'email' => 'required|min:6|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'province' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'role_id' => 'required',
            'group_id' => ['required',function($attribute, $value, $fail){
                if($value==0){
                        $fail('Vui lòng chọn nhóm');
                }
            }]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Trường họ tên không được để trống',
            'name.min' => 'Họ tên có độ dài ít nhất 6 ký tự.',
            'name.max' => 'Họ tên có độ dài tối đa 255 ký tự.',
            'email.required' => 'Trường gmail không được để trống',
            'email.email' => 'Trường email phải là một địa chỉ email hợp lệ.',
            'email.unique' => 'Email đã có người sử dụng',
            'email.min' => 'Email có độ dài ít nhất 6 ký tự.',
            'password.required' => 'Trường mật khẩu không được đê trống',
            'password_confirmation.required' => 'Xác nhận mật khẩu không được đê trống',
            'password.min' => 'Mật khẩu có độ dài ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không thành công.',
            'role_id.required' => 'Vai trò bắt buộc phải chọn',
            'province.required' => 'Tỉnh bắt buộc phải chọn',
            'district.required' => 'Quận bắt buộc phải chọn',
            'ward.required' => 'Phường bắt buộc phải chọn'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                ['errors' => $validator->errors()], 422
            )
        );
    }

}
