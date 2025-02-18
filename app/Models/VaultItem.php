<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Contracts\Encryption\DecryptException;



class VaultItem extends Model
{
    protected $fillable = ['token', 'value', 'field_name'];

    public function tokenize(string $value): string
    {
        do {
            $token = Str::random(10);
        } while ($this->where('token', $token)->exists());
        $encrypted = Crypt::encryptString($value);
        $this->create(  [
            'token' => $token,
            'value' => $encrypted
        ]);
        return $token;
    }

    public function detokenize(string $token): string|bool
    {
        $vaultItem = $this->where('token', $token)->first();
        if (!$vaultItem) {
            return false;
        }
        try {
            $decrypted = Crypt::decryptString($vaultItem->value);
        } catch (DecryptException $e) {
            return false;
        }

        return $decrypted;
    }
}
