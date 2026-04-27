<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/Validador.php';

class ValidadorTest extends TestCase {
    
    public function testSenhaDeveTerSeisOuMaisCaracteres() {
        $validador = new Validador();
        $this->assertTrue($validador->validarSenhaForte("doce123"));
        $this->assertFalse($validador->validarSenhaForte("123"));
    }

    public function testHashGeradoDeveSerDiferenteDaSenha() {
        $validador = new Validador();
        $hash = $validador->gerarHash("minhasenha");
        $this->assertNotEquals("minhasenha", $hash);
    }

    public function testPasswordVerifyDeveValidarCorretamente() {
        $validador = new Validador();
        $hash = password_hash("senha123", PASSWORD_DEFAULT);
        $this->assertTrue($validador->testarHash("senha123", $hash));
    }

    public function testUsuarioNaoPodeSerVazio() {
        $validador = new Validador();
        $this->assertFalse($validador->validarUsuarioNaoVazio("   "));
        $this->assertTrue($validador->validarUsuarioNaoVazio("admin"));
    }

    public function testValidacaoDeEmailFormatoCorreto() {
        $validador = new Validador();
        $this->assertTrue($validador->validarEmailPadrao("teste@teste.com"));
        $this->assertFalse($validador->validarEmailPadrao("testesemarroba.com"));
    }
}