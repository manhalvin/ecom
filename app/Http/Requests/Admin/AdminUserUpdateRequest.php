<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserUpdateRequest extends FormRequest
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
            'email' => 'unique:users,email,' . $this->user,
            'role' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Trường họ tên không được để trống',
            'name.min' => 'Họ tên có độ dài ít nhất 6 ký tự.',
            'name.max' => 'Họ tên có độ dài tối đa 255 ký tự.',
            'email.email' => 'Trường email phải là một địa chỉ email hợp lệ.',
            'email.unique' => 'Trường email đã tồn tại trong bảng users',
            'role.required' => 'Vai trò bắt buộc phải chọn',
        ];
    }
}
