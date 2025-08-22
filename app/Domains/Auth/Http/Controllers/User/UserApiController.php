<?php
namespace App\Domains\Auth\Http\Controllers\User;

use App\Domains\Auth\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function emailsPaginated(Request $request){
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

}
