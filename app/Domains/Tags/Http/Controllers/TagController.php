<?php

namespace App\Domains\Tags\Http\Controllers;

use App\Domains\Tags\Http\Requests\StoreTagRequest;
use App\Domains\Tags\Http\Requests\UpdateTagRequest;
use App\Domains\Tags\Models\Tag;
use App\Domains\Tags\Services\TagService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

final class TagController extends Controller
{
    /**
     * @var TagService
     */
    protected TagService $tagService;

    /**
     * @param TagService $tagService
     */
    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }


    /**
     * @param StoreTagRequest $request
     * @return RedirectResponse
     */
    public function store(StoreTagRequest $request): RedirectResponse
    {
        $tagDTO = new Tag();
        $tagDTO->setName($request['name']);

        $this->tagService->store($tagDTO);

        return redirect()->back()->with('success', 'Η ετικέτα αποθηκεύτηκε με επιτυχία');
    }

    /**
     * @param UpdateTagRequest $request
     * @param string $tagId
     * @return RedirectResponse
     */
    public function update(UpdateTagRequest $request, string $tagId): RedirectResponse
    {
        $tagDTO = new Tag();
        $tagDTO->setName($request['name']);

        $this->tagService->update($tagDTO, $tagId);

        return redirect()->back()->with('success', 'Η ετικέτα ενημερώθηκε με επιτυχία');
    }


}
