<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/oauth/token",
     *     tags={"Auth"},
     *     operationId="login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="grant_type", type="string", example="password"),
     *                 @OA\Property(property="client_id", type="string", example="2"),
     *                 @OA\Property(property="client_secret", type="string", example="kwf51EBiX5gaoJTZYsei0TJHEG9X"),
     *                 @OA\Property(property="username", type="email", example="example@email.com"),
     *                 @OA\Property(property="password", type="string", example="password123"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="token_type", type="string", example="Bearer"),
     *                 @OA\Property(property="expires_in", type="int", example=31536000),
     *                 @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJ..."),
     *                 @OA\Property(property="refresh_token", type="string", example="def50200d572b23068c037589a2..."),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="error",type="string", example="invalid_grant"),
     *                 @OA\Property(property="error_description",type="string", example="The provided ..."),
     *                 @OA\Property(property="hint",type="string", example=""),
     *                 @OA\Property(property="message",type="string", example="The provided ..."),
     *             ),
     *         )
     *     ),
     * )
     */
    public function token()
    {

    }
}
