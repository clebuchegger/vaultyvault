<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VaultItem;

class VaultItemController extends Controller
{
    public function tokenize(Request $request, VaultItem $vaultItem)
    {

        $request->validate([
            'id' => 'required',
            'data' => 'required',
        ]);
        $data = $request->input('data');
        $id = $request->input('id');
        $tokens = [];
        foreach ($data as $field_name => $value) {
            $token = $vaultItem->tokenize($value);
            $tokens[$field_name] = $token;
        }
        return response()->json(array('id' => $id, 'data' => $tokens))->setStatusCode(201);
    }

    public function detokenize(Request $request, VaultItem $vaultItem)
    {
        $request->validate([
            'id' => 'required',
            'data' => 'required',
        ]);
        $data = $request->input('data');
        $id = $request->input('id');
        $values = [];
        foreach ($data as $field_name => $token) {
            $decrypted = $vaultItem->detokenize($token);
            if($decrypted === false) {
                $values[$field_name] = ['found' => false, 'value' => ''];
            } else {
                $values[$field_name] = ['found' => true, 'value' => $decrypted];
            }
        }
        return response()->json(array('id' => $id, 'data' => $values));
    }
}
