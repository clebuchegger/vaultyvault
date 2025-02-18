<?php
use Illuminate\Support\Facades\Http;

it('needs auth when POST to tokenize', function () {
    $response = $this->postJson('/tokenize', [
        'id' => '1',
        'value' => array(
            'field1' => 'value1',
            'field2' => 'value2',
            'field3' => 'value3',
        )
    ], [
        'Accept' => 'application/json',
    ]);

    $response->assertStatus(401);
});


it('needs auth when POST to detokenize', function () {
    $response = $this->postJson('/detokenize', [
        'id' => '1',
        'value' => array(
            'field1' => 'value1',
            'field2' => 'value2',
            'field3' => 'value3',
        )
    ], [
        'Accept' => 'application/json',
    ]);

    $response->assertStatus(401);
});

it('gets an auth token when POST to tokenize (with auth)', function () {
    $OAuthResponse = Http::asForm()->post( env('APP_URL') . '/oauth/token', [
        'grant_type' => 'client_credentials',
        'client_id' => env('VAULTYVAULT_CLIENT_ID'),
        'client_secret' => env('VAULTYVAULT_CLIENT_SECRET'),
        'scope' => 'api-detokenize api-tokenize',
    ]);

    $token = $OAuthResponse->json()['access_token'];
    expect($token)->toBeString();
});


it('tokenizes a value when POST to tokenize (with auth)', function () {
    $OAuthResponse = Http::asForm()->post( env('APP_URL') . '/oauth/token', [
        'grant_type' => 'client_credentials',
        'client_id' => env('VAULTYVAULT_CLIENT_ID'),
        'client_secret' => env('VAULTYVAULT_CLIENT_SECRET'),
        'scope' => 'api-tokenize api-detokenize',
    ]);

    $token = $OAuthResponse->json()['access_token'];
    expect($token)->toBeString();

    $response = $this->postJson('/tokenize', [
        'id' => '1',
        'data' => array(
            'field1' => 'value1',
            'field2' => 'value2',
            'field3' => 'value3',
        )
    ], [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response->assertStatus(201);


    $token1 = $response->json()['data']['field1'];
    $token2 = $response->json()['data']['field2'];
    $token3 = $response->json()['data']['field3'];

    expect($token1)->toBeString();
    expect($token2)->toBeString();
    expect($token3)->toBeString();

    $response = $this->postJson('/detokenize', [
        'id' => '1',
        'data' => array(
            'field1' => $token1,
            'field2' => $token2,
            'field3' => $token3,
        )
    ], [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response->assertStatus(200);

    expect($response->json()['data']['field1']['value'])->toBe('value1');
    expect($response->json()['data']['field2']['value'])->toBe('value2');
    expect($response->json()['data']['field3']['value'])->toBe('value3');
});
