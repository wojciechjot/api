<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\ConfirmationRegistrationMail;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     operationId="register",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="Jan"),
     *                 @OA\Property(property="email", type="email", example="example@email.com"),
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
     *                 @OA\Property(property="message",type="string", example="Account has been created."),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message",type="string", example="Invalid login credentials."),
     *             ),
     *         )
     *     ),
     * )
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->userRepository->create($request->all());

        Mail::to($user->email)->send(new ConfirmationRegistrationMail($user));

        return response()->json(
            ['message' => 'Account has been created.'],
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Post(
     *     path="/api/enable-user/{token}",
     *     tags={"Auth"},
     *     operationId="enableUser",
     *     @OA\Parameter(
     *         in="path",
     *         name="token",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message",type="string", example="Account has been enabled."),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     ),
     * )
     */
    public function enable(string $token)
    {
        $user = $this->userRepository->findOneByConfirmationToken($token);

        $user->enable();
        $user->update();

        return response()->json(
            ['message' => 'Account has been enabled.'],
            Response::HTTP_CREATED
        );
    }
}
