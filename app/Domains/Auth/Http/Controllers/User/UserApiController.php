<?php
namespace App\Domains\Auth\Http\Controllers\User;

use App\Domains\Auth\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Cactus CRM API",
 *     version="1.0.0",
 *     description="Cactus CRM api documantation in OAS 3.0",
 * )
 */
class UserApiController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle login request and return an API token.
     *
     * @param  Request  $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful login"),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        return $this->userService->apiGetUser($credentials['email'], $credentials['password']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function emailsPaginated(Request $request) : JsonResponse
    {
        $validated = $request->validate([
            'page' => 'required|integer',
            'term' => 'nullable|string',
        ]);

        $page = $validated['page'];
        $resultCount = 25;

        $offset = ($page - 1) * $resultCount;

        /**
         * result['data']
         * result['count']
         */
        $result = $this->userService->emailsPaginated($validated['term'], $offset, $resultCount);


        $subSections = $result['data'];
        $count = $result['count'];


        $endCount = $offset + $resultCount;
        $morePages = $count > $endCount;


        $results = array(
            "results" => $subSections,
            "pagination" => array(
                "more" => $morePages
            )
        );

        return response()->json($results);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function namesPaginated(Request $request, bool $onlyContacts = false) : JsonResponse
    {
        $validated = $request->validate([
            'page' => 'required|integer',
            'term' => 'nullable|string',
        ]);

        $page = $validated['page'];
        $resultCount = 25;

        $offset = ($page - 1) * $resultCount;

        /**
         * result['data']
         * result['count']
         */
        $result = $this->userService->namesPaginated($validated['term'], $offset, $resultCount, $onlyContacts);


        $subSections = $result['data'];
        $count = $result['count'];


        $endCount = $offset + $resultCount;
        $morePages = $count > $endCount;


        $results = array(
            "results" => $subSections,
            "pagination" => array(
                "more" => $morePages
            )
        );

        return response()->json($results);

    }

    public function contactNamesPaginated(Request $request) : JsonResponse
    {
        return $this->namesPaginated($request, true);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @OA\Post(
     *       @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="id",
     *                      type="string"
     *                  ),
     *                  example={"id": "1"}
     *              )
     *          )
     *      ),
     *       security={ {
     *              "sanctum": {}
     *          }},
     *       path="/api/users/user-by-id",
     *       summary="Get a list of users",
     *       tags={"Users"},
     *       @OA\Response(response=200, description="Successful operation"),
     *       @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function getUserById(Request $request): JsonResponse
    {
        if(!$request->json()){
            return response()->json(['message' => 'Invalid request'], 400);
        }
        $validated = $request->validate([
            'id' => 'required',
        ]);

        $user = $this->userService->getById($validated['id']);

        return response()->json($user->getValues());
    }
}
