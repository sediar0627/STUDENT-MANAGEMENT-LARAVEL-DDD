<?php

namespace App\Http\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Requests\ApiLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiLoginController extends Controller
{
    public function handle(ApiLoginRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(
                data: [
                    'status' => ControllerStatusDescription::UNAUTHORIZED->value,
                ],
                status: ControllerStatusDescription::UNAUTHORIZED->httpCode()
            );
        }

        $token = $user->createToken('api')->plainTextToken;
        $token = explode('|', $token)[1]; // EJ: "2|Uo6nEYNNE..." -> "Uo6nEYNNE..."

        return response()->json(
            data: [
                'status' => ControllerStatusDescription::OK->value,
                'token' => $token,
            ],
            status: ControllerStatusDescription::OK->httpCode()
        );
    }
}
