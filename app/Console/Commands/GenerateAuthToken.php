<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GenerateAuthToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-auth-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an auth token for a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Http::asForm()->post( env('APP_URL') . '/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => env('VAULTYVAULT_CLIENT_ID'),
            'client_secret' => env('VAULTYVAULT_CLIENT_SECRET'),
            'scope' => 'api-tokenize',
        ]);
        $this->info( PHP_EOL . 'Tokenize token:');
        if($response->successful()) {
            $this->info($response->json()['access_token'] . PHP_EOL. PHP_EOL . PHP_EOL);
        } else {
            $this->error('Failed to generate tokenize token' . PHP_EOL . PHP_EOL . PHP_EOL);
        }

        $response = Http::asForm()->post( env('APP_URL') . '/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => env('VAULTYVAULT_CLIENT_ID'),
            'client_secret' => env('VAULTYVAULT_CLIENT_SECRET'),
            'scope' => 'api-detokenize',
        ]);

        $this->info( PHP_EOL . 'Detokenize token:');
        if($response->successful()) {
            $this->info($response->json()['access_token'] . PHP_EOL. PHP_EOL . PHP_EOL);
        } else {
            $this->error('Failed to generate detokenize token' . PHP_EOL . PHP_EOL . PHP_EOL);
        }

        $response = Http::asForm()->post( env('APP_URL') . '/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => env('VAULTYVAULT_CLIENT_ID'),
            'client_secret' => env('VAULTYVAULT_CLIENT_SECRET'),
            'scope' => 'api-detokenize api-tokenize',
        ]);

        $this->info('Tokenize + detokenize token:');
        if ($response->successful()) {
            $this->info($response->json()['access_token'] . PHP_EOL);
        } else {
            $this->error('Failed to generate Tokenize + detokenize scopes token' . PHP_EOL);
        }

    }
}
