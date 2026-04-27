<?php

class Validador {
    public function validarSenhaForte($senha) {
        return strlen($senha) >= 6;
    }

    public function testarHash($senha, $hash) {
        return password_verify($senha, $hash);
    }

    public function gerarHash($senha) {
        return password_hash($senha, PASSWORD_DEFAULT);
    }

    public function validarUsuarioNaoVazio($usuario) {
        return !empty(trim($usuario));
    }

    public function validarEmailPadrao($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}