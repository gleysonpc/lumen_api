<?php


namespace App;


class UserValidation
{
    const RULES = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users'
    ];

    const MESSAGES = [
        'required' => 'O campo :attribute campo é obrigatório.',
        'min'      => 'O campo :attribute deve conter pelo menos :min caracteres.',
        'email'    => 'Informe um email válido',
        'unique'   => 'O email informado já está em uso.',
    ];
}