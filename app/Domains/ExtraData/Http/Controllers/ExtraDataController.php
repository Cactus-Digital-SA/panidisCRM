<?php

namespace App\Domains\ExtraData\Http\Controllers;

use App\Domains\ExtraData\Enums\ExtraDataModelsEnum;
use App\Domains\ExtraData\Enums\ExtraDataTypesEnum;
use App\Domains\ExtraData\Http\Requests\DeleteExtraDataRequest;
use App\Domains\ExtraData\Http\Requests\StoreExtraDataModelRequest;
use App\Domains\ExtraData\Http\Requests\StoreExtraDataRequest;
use App\Domains\ExtraData\Http\Requests\UpdateExtraDataRequest;
use App\Domains\ExtraData\Models\ExtraData;
use App\Domains\ExtraData\Services\ExtraDataService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExtraDataController extends Controller
{
    public function __construct(private ExtraDataService $extraDataService)
    {
    }

    public function index()
    {
        $columns = $this->extraDataService->getTableColumns();
        return view('backend.content.extraData.index', compact('columns'));
    }

    public function create()
    {
        $types = ExtraDataTypesEnum::values();
        return view('backend.content.extraData.create', compact('types'));
    }

    public function store(StoreExtraDataRequest $request)
    {
        $optionKeys = $request['option_keys'] ?? null;
        $optionValues = $request['option_values'] ?? null;
        if (count($optionKeys) === count($optionValues)) {
            $extraOptions = [];
            foreach ($optionKeys as $index => $value) {
                $extraOptions[$value] = $optionValues[$index];
            }

            $extraOptionsJson = json_encode($extraOptions);
            $request['options'] = $extraOptionsJson;
        }

        $extraData = ExtraData::fromRequest($request);

        $this->extraDataService->store($extraData);

        return redirect()->route('admin.extraData.index')->with('success', 'Η εγγραφή δημιουργήθηκε με επιτυχία');
    }

    public function edit(string $extraDataId)
    {
        $extraData = $this->extraDataService->getById($extraDataId);
        $types = ExtraDataTypesEnum::values();
        return view('backend.content.extraData.edit', compact('extraData', 'types'));
    }

    public function update(UpdateExtraDataRequest $request, string $extraDataId)
    {
        $optionKeys = $request['option_keys'] ?? null;
        $optionValues = $request['option_values'] ?? null;
        if (count($optionKeys) === count($optionValues)) {
            $extraOptions = null;
            foreach ($optionKeys as $index => $value) {
                if($optionValues[$index] !== null){
                    $extraOptions[$value] = $optionValues[$index];
                }
            }

            if($extraOptions !== null){
                $extraOptionsJson = json_encode($extraOptions);
                $request['options'] = $extraOptionsJson;
            }

        }
        $extraData = ExtraData::fromRequest($request);

        $this->extraDataService->update($extraData, $extraDataId);

        return redirect()->route('admin.extraData.index')->with('success', 'Η εγγραφή ενημερώθηκε με επιτυχία');
    }

    public function destroy(DeleteExtraDataRequest $request, string $extraDataId)
    {
        $response = $this->extraDataService->deleteById($extraDataId);

        if($response){
            return redirect()->route('admin.extraData.index')->with('success', 'Η εγγραφή διαγράφηκε με επιτυχία');
        }

        return redirect()->route('admin.extraData.index')->with('error', 'Υπήρξε κάποιο πρόβλημα κατά την διαγραφή');
    }

    public function assignExtraDataToModelIndex(Request $request)
    {
        $extraData = $this->extraDataService->get();
        $selectedData = [];

        // Loop through extraData to populate selected data
        foreach(ExtraDataModelsEnum::values() ?? [] as $model) {
            foreach ($extraData as $data) {
                foreach ($data->getModels() as $selectedModel) {
                    if ($selectedModel->getModel() == $model) {
                        $selectedData[$model][] = $data->getId(); // Store selected IDs
                        break;
                    }
                }
            }
        }

        return view('backend.content.extraData.assign', compact('extraData', 'selectedData'));
    }

    public function assignExtraDataToModelStore(StoreExtraDataModelRequest $request){

        $this->extraDataService->assignExtraDataToModel($request['extraData'] ?? []);

        return redirect()->back()->with('success', 'Οι ρυθμίσεις αποθηκεύτηκαν με επιτυχία');

    }

}
