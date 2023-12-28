<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'no_telp' => 'required|numeric',
            'jabatan' => 'required|string',
            'role_name' => 'required|in:admin,pegawai,kasubagumum',
            'password' => 'nullable|min:8', // Jika ingin memperbarui password, harus diisi
        ];
    }
}
