<?php

namespace App\Domains\Projects\Http\Controllers;

use App\Domains\Projects\Enums\ProjectCategoryEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectApiController extends Controller
{
    /**
     * @param Request $request
     * @param string $categoryId
     * @return JsonResponse
     */
    public function getCategoryOptions(Request $request, string $categoryId)
    {
        $category = ProjectCategoryEnum::tryFrom($categoryId);

        if (!$category) {
            return response()->json([
                'error' => 'Invalid category ID.'
            ], 422);
        }

        $options = $category->options();

        $data = [];
        foreach($options as $option) {
            $data[] = [
                'value' => $option->value,
                'label' => $option->value,
            ];
        }

        return response()->json($data, 200);
    }
}