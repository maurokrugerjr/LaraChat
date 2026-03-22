<?php

use App\Models\User;

// ─── Register ────────────────────────────────────────────────────────────────

it('registra um novo usuário e retorna token JWT', function () {
    $response = $this->postJson('/api/auth/register', [
        'nome'               => 'João Silva',
        'email'              => 'joao@exemplo.com',
        'senha'              => 'senha123',
        'senha_confirmation' => 'senha123',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
            'user' => ['id', 'nome', 'email'],
        ]);
});

it('não registra com email duplicado', function () {
    User::factory()->create(['email' => 'joao@exemplo.com']);

    $response = $this->postJson('/api/auth/register', [
        'nome'               => 'João Silva',
        'email'              => 'joao@exemplo.com',
        'senha'              => 'senha123',
        'senha_confirmation' => 'senha123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('não registra sem confirmação de senha', function () {
    $response = $this->postJson('/api/auth/register', [
        'nome'  => 'João Silva',
        'email' => 'joao@exemplo.com',
        'senha' => 'senha123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['senha']);
});

it('não registra com senha menor que 8 caracteres', function () {
    $response = $this->postJson('/api/auth/register', [
        'nome'               => 'João Silva',
        'email'              => 'joao@exemplo.com',
        'senha'              => '123',
        'senha_confirmation' => '123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['senha']);
});

// ─── Login ───────────────────────────────────────────────────────────────────

it('faz login com credenciais válidas e retorna token JWT', function () {
    User::factory()->create([
        'email' => 'joao@exemplo.com',
        'senha' => 'senha123',
    ]);

    $response = $this->postJson('/api/auth/login', [
        'email' => 'joao@exemplo.com',
        'senha' => 'senha123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
            'user',
        ]);
});

it('não faz login com senha incorreta', function () {
    User::factory()->create(['email' => 'joao@exemplo.com']);

    $response = $this->postJson('/api/auth/login', [
        'email' => 'joao@exemplo.com',
        'senha' => 'senha_errada',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('não faz login com email inexistente', function () {
    $response = $this->postJson('/api/auth/login', [
        'email' => 'naoexiste@exemplo.com',
        'senha' => 'senha123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

// ─── Me ──────────────────────────────────────────────────────────────────────

it('retorna dados do usuário autenticado', function () {
    $user = User::factory()->create();
    $token = auth('api')->login($user);

    $response = $this->withToken($token)->getJson('/api/auth/me');

    $response->assertStatus(200)
        ->assertJsonFragment(['email' => $user->email]);
});

it('não retorna dados sem token', function () {
    $response = $this->getJson('/api/auth/me');

    $response->assertStatus(401);
});

// ─── Logout ──────────────────────────────────────────────────────────────────

it('faz logout com sucesso', function () {
    $user = User::factory()->create();
    $token = auth('api')->login($user);

    $response = $this->withToken($token)->postJson('/api/auth/logout');

    $response->assertStatus(200)
        ->assertJsonFragment(['message' => 'Logged out successfully.']);
});

it('não faz logout sem token', function () {
    $response = $this->postJson('/api/auth/logout');

    $response->assertStatus(401);
});

// ─── Refresh ─────────────────────────────────────────────────────────────────

it('renova o token JWT com sucesso', function () {
    $user = User::factory()->create();
    $token = auth('api')->login($user);

    $response = $this->withToken($token)->postJson('/api/auth/refresh');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
            'user',
        ]);
});
