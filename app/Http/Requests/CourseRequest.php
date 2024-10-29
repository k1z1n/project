<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $courseId = $this->route('course')->id;

        return [
            'title' => 'required|max:255|unique:courses,title,' . $courseId,
            'description' => 'required|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg|max:10048',
        ];
    }

    public function messages(): array
    {
        return
            [
                'title.required' => 'Поле "Название" обязательно для заполнения.',
                'title.max' => 'Поле "Название" не должно превышать 255 символов.',
                'title.unique' => 'Курс с таким названием уже существует.',

                'description.required' => 'Поле "Описание" обязательно для заполнения.',
                'description.max' => 'Поле "Описание" не должно превышать 255 символов.',

                'logo.required' => 'Поле "Логотип" обязательно для заполнения.',
                'logo.image' => 'Файл, загружаемый в поле "Логотип", должен быть изображением.',
                'logo.mimes' => 'Поле "Логотип" должно быть файлом формата: jpeg, png, jpg, svg.',
                'logo.max' => 'Размер файла "Логотип" не должен превышать 10 МБ.',
            ];
    }
}
