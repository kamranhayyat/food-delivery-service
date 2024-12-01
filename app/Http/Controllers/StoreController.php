<?php

namespace App\Http\Controllers;

use App\Mail\DefaultStorePassword;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreController extends Controller
{
    public function registerStore(Request $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            $request->validate([
                'moto' => 'required|string|max:150',
                'promotion_description' => 'string',
                'cover_picture' => 'required|file|max:2048'
            ]);

            $user = Auth::user();
            $store = $user->store()->create($request->only(['moto']));

            if ($request->hasFile('cover_picture')) {
                $response = Http::post(route('file-upload'), [
                    'file' => $request->file('cover_picture'),
                    'fileable_id' => $store->id,
                    'fileable_type' => Store::class,
                    'file_type' => 'cover_picture',
                ]);

                if ($response->failed()) {
                    return response()->json(['message' => 'Failed to upload cover picture'], 500);
                }
            }

            return response()->json([
                'data' => [
                    'store' => $store
                ],
                'message' => 'Store registered successfully'
            ]);
        });

        return response()->json(['error' => 'Failed to create store'], 500);
    }

    public function processStoreRequest(Request $request, Store $store): JsonResponse
    {
        $request->validate([
            'storeId' => 'integer|required',
            'status' => [
                'required',
                Rule::in([Store::APPROVED_STORE, Store::DISAPPROVED_STORE]),
            ]
        ]);

        $store = Store::query()->find($request['storeId']);
        $request['status'] === Store::DISAPPROVED_STORE ? $this->disApproveStore($store) : $this->approveStore($store);
        $store->save();

        return response()->json([
            'data' => [
                'store' => $store
            ],
            'message' => 'Store processed successfully'
        ]);
    }

    private function disApproveStore(Store $store): void
    {
        $store->status = Store::DISAPPROVED_STORE;
    }

    private function approveStore(Store $store): void
    {
        $store->status = Store::APPROVED_STORE;
    }
}
