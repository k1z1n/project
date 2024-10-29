<?php

namespace App\Services;

class GroupService
{
    public function createGroup($request)
    {
        return $this->validateCreateGroup($request);
    }

    private function validateCreateGroup($request)
    {
        return $request->validate([
            'title' => 'required|max:255|unique:groups,title',
            'link' => 'max:255',
        ], [
            'title.required' => 'Поле "Название" обязательно для заполнения.',
            'title.max' => 'Поле "Название" не должно превышать 255 символов.',
            'title.unique' => 'Поле "Название" должно быть уникальным.',
            'link.max' => 'Поле "Ссылка" не должно превышать 255 символов.',
        ]);
    }

    public function updateGroup($request, $groupId){
        return $this->validateUpdateGroup($request, $groupId);
    }

    private function validateUpdateGroup($request, $groupId){
        return $request->validate([
            'title' => 'required|max:255|unique:groups,title,' . $groupId,
            'link' => 'max:255',
        ], [
            'title.required' => 'Поле "Название" обязательно для заполнения.',
            'title.max' => 'Поле "Название" не должно превышать 255 символов.',
            'title.unique' => 'Поле "Название" должно быть уникальным.',
            'link.max' => 'Поле "Ссылка" не должно превышать 255 символов.',
        ]);
    }
}
