<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetPasswordRequest;
use App\Mail\RemindPasswordMail;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/remind-password",
     *     tags={"Auth"},
     *     operationId="remindPassword",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="email", type="email", example="example@email.com"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message",type="string", example="Mail has been sent."),
     *             ),
     *         )
     *     ),
     * )
     */
    public function remindPassword(Request $request)
    {
        try {
            $user = $this->userRepository->findOneByEmail($request->email);

            $user->setConfirmationToken();
            $user->update();

            Mail::to($user->email)->send(new RemindPasswordMail($user));
        } catch (ModelNotFoundException $exception) {
            //
        }

        return response()->json(
            ['message' => 'Mail has been sent.'],
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Post(
     *     path="/api/set-password",
     *     tags={"Auth"},
     *     operationId="setPassword",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJ..."),
     *                 @OA\Property(property="password", type="string", example="password123"),
     *                 @OA\Property(property="password_confirmation", type="string", example="password123"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message",type="string", example="Password has been changed."),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message",type="string", example="The given data was invalid."),
     *                 @OA\Property(
     *                     property="errors",
     *                     type="object",
     *                     @OA\Property(
     *                         property="field",
     *                         type="array",
     *                         @OA\Items(type="string", example="Validation error")
     *                     ),
     *                 )
     *             ),
     *         )
     *     ),
     * )
     */
    public function setPassword(SetPasswordRequest $request)
    {
        $this->userRepository->setPasswordByToken([
            'token' => $request->token,
            'password' => $request->password
        ]);

        return response()->json(
            ['message' => 'Password has been changed.'],
            Response::HTTP_CREATED
        );
    }
}
