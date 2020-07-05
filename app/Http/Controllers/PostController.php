<?php
namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Repositories\Post\PostRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     tags={"Post"},
     *     operationId="indexPost",
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
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page') ? (int)$request->input('per_page') : null;
        $page = $request->input('page') ? (int)$request->input('page') : null;

        $filterParams = $this->getFilterableFields($request, $this->postRepository->getFilterOptions());

        $sortParam = $request->input('sort_by');

        $results = $this->postRepository->findWithPaginate($perPage, $page, $sortParam, $filterParams);

        $body = [
            'total' => $results->total(),
            'data' => PostResource::collection($results)
        ];

        return response()->json($body);
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     tags={"Post"},
     *     operationId="storePost",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/PostResource"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/PostResource"),
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
     *     security={
     *         {"token": {}}
     *     }
     * )
     */
    public function store(PostRequest $request): JsonResponse
    {
        $fields = $request->only([
            'title',
            'content',
            'publication_date',
            'beginning',
            'end'
        ]);

        $fields['user_id'] = (string)$request->user()->id;

        $post = $this->postRepository->create($fields);

        return response()->json(new PostResource($post));
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     tags={"Post"},
     *     operationId="showPost",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/PostResource"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *     ),
     *     security={
     *         {"token": {}}
     *     }
     * )
     */
    public function show(int $id): JsonResponse
    {
        $post = $this->postRepository->find($id);

        try {
            $this->authorize('view', $post);
        } catch(AuthorizationException $exception) {
            throw new NotFoundHttpException();
        }

        return response()->json(new PostResource($post));
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     tags={"Post"},
     *     operationId="updatePost",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/PostResource"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/PostResource"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
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
     *     security={
     *         {"token": {}}
     *     }
     * )
     */
    public function update(PostRequest $request, int $id): JsonResponse
    {
        $fields = $request->only([
            'title',
            'content',
            'publication_date',
            'beginning',
            'end'
        ]);

        $post = $this->postRepository->find($id);

        try {
            $this->authorize('update', $post);
        } catch(AuthorizationException $exception) {
            throw new NotFoundHttpException();
        }

        $post = $this->postRepository->update($id, $fields);

        return response()->json(new PostResource($post));
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     tags={"Post"},
     *     operationId="destroyPost",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(type="object"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *     ),
     *     security={
     *         {"token": {}}
     *     }
     * )
     *
     * PostObserver::deleting
     */
    public function destroy(int $id): JsonResponse
    {
        $post = $this->postRepository->find($id);

        try {
            $this->authorize('delete', $post);
        } catch(AuthorizationException $exception) {
            throw new NotFoundHttpException();
        }

        $this->postRepository->delete($id);

        return response()->json(null, 204);
    }
}
