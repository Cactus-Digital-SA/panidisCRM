<?php

namespace App\Domains\Widgets\Http\Controllers;

use App\Domains\Auth\Services\RoleService;
use App\Domains\Widgets\Http\Requests\StoreWidgetsRoleRequest;
use App\Domains\Widgets\Models\Widget;
use App\Domains\Widgets\Services\WidgetService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    public function __construct(private WidgetService $widgetService, private RoleService $roleService)
    {

    }

    public function index ()
    {
        return view('backend.content.widgets.index');
    }

    public function create()
    {
        return view('backend.content.widgets.create');
    }

    public function store(Request $request)
    {
        $widgetDTO = new Widget();
        $widgetDTO->setName($request->name);
        $widgetDTO->setLabel($request->label);
        $widgetDTO->setDescription($request->description);

        $this->widgetService->store($widgetDTO);

        return redirect()->route('admin.widgets.index')->with('success', 'Widget created successfully');
    }

    public function edit($id)
    {
        $widget = $this->widgetService->getById($id);
        return view('backend.content.widgets.edit', compact('widget'));
    }

    public function update(Request $request, $id)
    {
        $widgetDTO = $this->widgetService->getById($id);
        $widgetDTO->setName($request->name);
        $widgetDTO->setLabel($request->label);
        $widgetDTO->setDescription($request->description);

        $this->widgetService->update($widgetDTO, $id);

        return redirect()->route('admin.widgets.index')->with('success', 'Widget updated successfully');
    }

    public function assignWidgetsToRoleIndex(Request $request)
    {
        $widgets = $this->widgetService->get();
        $roles = $this->roleService->get();
        $selectedData = [];

        // Loop through extraData to populate selected data
        foreach($roles ?? [] as $role) {
            foreach ($widgets as $widget) {
                foreach ($widget->getRoles() as $role) {
                    if($role->getId() == $role->getId()) {
                        $selectedData[$role->getId()][] = $widget->getId();
                        break;
                    }
                }
            }
        }


        return view('backend.content.widgets.assign', compact('widgets', 'roles', 'selectedData'));
    }

    public function assignWidgetToRole(StoreWidgetsRoleRequest $request){

        $this->widgetService->assignWidgetToRole($request['widgets'] ?? []);

        return redirect()->back()->with('success', 'Οι ρυθμίσεις αποθηκεύτηκαν με επιτυχία');

    }

}
