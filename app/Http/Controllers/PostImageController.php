<?php
namespace App\Http\Controllers;

use App\Http\Requests\PostImageRequest;
use App\Http\Resources\PostImageResource;
use App\Repositories\PostImage\PostImageRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostImageController extends Controller
{
    private PostImageRepositoryInterface $postImageRepository;

    public function __construct(PostImageRepositoryInterface $postImageRepository)
    {
        $this->postImageRepository = $postImageRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/post-images",
     *     tags={"PostImage"},
     *     operationId="indexPostImage",
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
     *                     @OA\Items(ref="#/components/schemas/PostImageResource")
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

        $filterParams = [];

        $sortParam = $request->input('sort_by');

        $results = $this->postImageRepository->findWithPaginate($perPage, $page, $sortParam, $filterParams);

        $body = [
            'total' => $results->total(),
            'data' => PostImageResource::collection($results)
        ];

        return response()->json($body);
    }

    /**
     * @OA\Post(
     *     path="/api/post-images",
     *     tags={"PostImage"},
     *     operationId="storePostImage",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/PostImageResource"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/PostImageResource"),
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
    public function store(PostImageRequest $request): JsonResponse
    {
        $fields = $request->only([
            'path',
            'post_id'
        ]);

        $postImage = $this->postImageRepository->create($fields);

        return response()->json(new PostImageResource($postImage));
    }

    /**
     * @OA\Get(
     *     path="/api/post-images/{id}",
     *     tags={"PostImage"},
     *     operationId="showPostImage",
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
     *             @OA\Schema(ref="#/components/schemas/PostImageResource"),
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
        $postImage = $this->postImageRepository->find($id);

        try {
            $this->authorize('view', $postImage);
        } catch(AuthorizationException $exception) {
            throw new NotFoundHttpException();
        }

        return response()->json(new PostImageResource($postImage));
    }

    /**
     * @OA\Put(
     *     path="/api/post-images/{id}",
     *     tags={"PostImage"},
     *     operationId="updatePostImage",
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
     *             @OA\Schema(ref="#/components/schemas/PostImageResource"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/PostImageResource"),
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
    public function update(PostImageRequest $request, int $id): JsonResponse
    {
        $fields = $request->only([
            'path',
        ]);

        $postImage = $this->postImageRepository->find($id);

        try {
            $this->authorize('update', $postImage);
        } catch(AuthorizationException $exception) {
            throw new NotFoundHttpException();
        }

        $postImage = $this->postImageRepository->update($id, $fields);

        return response()->json(new PostImageResource($postImage));
    }

    /**
     * @OA\Delete(
     *     path="/api/post-images/{id}",
     *     tags={"PostImage"},
     *     operationId="destroyPostImage",
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
     */
    public function destroy(int $id): JsonResponse
    {
        $postImage = $this->postImageRepository->find($id);

        try {
            $this->authorize('delete', $postImage);
        } catch(AuthorizationException $exception) {
            throw new NotFoundHttpException();
        }

        $this->postImageRepository->delete($id);

        return response()->json(null, 204);
    }
}
