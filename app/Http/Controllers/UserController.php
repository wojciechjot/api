<?php
namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Repositories\Post\PostRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users/{id}/posts",
     *     tags={"User"},
     *     operationId="indexPostsForUser",
     *     summary="Return a list of posts belongs to the user.",
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="total",type="integer", example=10),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/PostResource")
     *                 ),
     *             ),
     *         )
     *     ),
     *     security={
     *         {"token": {}}
     *     }
     * )
     */
    public function indexPostsForUser(
        Request $request,
        PostRepositoryInterface $postRepository,
        int $id
    ): JsonResponse
    {
        $currentUserId = $request->user()->id;
        $userId = $id;

        if ($currentUserId !== $userId) {
            throw new NotFoundHttpException();
        }

        $perPage = $request->input('per_page') ? (int)$request->input('per_page') : null;
        $page = $request->input('page') ? (int)$request->input('page') : null;

        $filterParams = $this->getFilterableFields($request, $postRepository->getFilterOptions());

        $sortParam = $request->input('sort_by');

        $results = $postRepository->findWithPaginateByUser($perPage, $page, $sortParam, $userId, $filterParams);

        $body = [
            'total' => $results->total(),
            'data' => PostResource::collection($results)
        ];

        return response()->json($body);
    }

    /**
     * @OA\Get(
     *     path="/api/users/me",
     *     tags={"User"},
     *     operationId="me",
     *     summary="Return a current user.",
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/UserResource"),
     *         )
     *     ),
     *     security={
     *         {"token": {}}
     *     }
     * )
     */
    public function me(Request $request): JsonResponse
    {
        $currentUser = $request->user();

        return response()->json(new UserResource($currentUser));
    }
}
