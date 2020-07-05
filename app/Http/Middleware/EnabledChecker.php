<?php

namespace App\Http\Middleware;

use App\Repositories\User\UserRepositoryInterface;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnabledChecker
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(Request $request, Closure $next)
    {
        $name = $request->route()->getName();

        if ($name === 'passport.token' && $request->isMethod('POST')) {
            try {
                $email = $request->get('username');
                $user = $this->userRepository->findOneByEmail($email);

                if (!$user->isEnabled()) {
                    return response()->json(
                        ['message' => 'Account is disabled.'],
                        Response::HTTP_FORBIDDEN
                    );
                }
            } catch (ModelNotFoundException $modelNotFoundException) {
                //
            }
        }

        return $next($request);
    }
}
