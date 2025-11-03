<?php

namespace App\Domains\Quotes\Http\Controllers;

use App\Domains\Quotes\Enums\QuoteStatusEnum;
use App\Domains\Quotes\Http\Requests\DeleteQuoteRequest;
use App\Domains\Quotes\Http\Requests\ManageQuoteRequest;
use App\Domains\Quotes\Http\Requests\StoreQuoteRequest;
use App\Domains\Quotes\Http\Requests\UpdateQuoteRequest;
use App\Domains\Quotes\Models\Quote;
use App\Domains\Quotes\Models\QuoteItem;
use App\Domains\Quotes\Services\QuoteService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;

class QuoteController extends Controller
{
    public function __construct(
        protected QuoteService $quoteService
    )
    {}

    public function index(ManageQuoteRequest $request)
    {
        $columns = $this->quoteService->getTableColumns();

        return view('backend.content.quotes.index', compact('columns'));
    }

    public function show(ManageQuoteRequest $request, string $quoteId)
    {
        $quote = $this->quoteService->getById($quoteId);

        return view('backend.content.quotes.show', compact('quote'));
    }

    public function create()
    {
        return view('backend.content.quotes.create');
    }

    public function store(StoreQuoteRequest $request)
    {
        $quoteDTO = new Quote();
        $quoteDTO = $quoteDTO->fromRequest($request);
        $quoteDTO->setStatus(QuoteStatusEnum::DRAFT);
        $quoteDTO->setContacts($request->contacts ?? []);
        $quoteDTO->setAssignees($request->assignees ?? []);

        $itemArr = [];
        foreach($request->items ?? [] as $item){
            $itemRequest = new Request($item);

            $quoteItemDTO = new QuoteItem();
            $quoteItemDTO = $quoteItemDTO->fromRequest($itemRequest);
            $itemArr[] = $quoteItemDTO;
        }

        $quoteDTO->setItems($itemArr);

        $quote = $this->quoteService->store($quoteDTO);

        return redirect()->route('admin.quotes.index')->with('success','Επιτυχής αποθήκευση!');
    }

    public function edit(Request $request, $quoteId)
    {
        return redirect()->route('admin.quotes.show', $quoteId);
    }

    public function update(UpdateQuoteRequest $request, string $quoteId)
    {
        $quoteDTO = new Quote();
        $quoteDTO = $quoteDTO->fromRequest($request);
        $quoteDTO->setContacts($request->contacts ?? []);
        $quoteDTO->setAssignees($request->assignees ?? []);
        $quoteDTO->setQuoteStatusAttribute($request->status ?? QuoteStatusEnum::DRAFT);

        $itemArr = [];
        foreach($request->items ?? [] as $item){
            $itemRequest = new Request($item);

            $quoteItemDTO = new QuoteItem();
            $quoteItemDTO = $quoteItemDTO->fromRequest($itemRequest);
            $itemArr[] = $quoteItemDTO;
        }

        $quoteDTO->setItems($itemArr);

        $quote = $this->quoteService->update($quoteDTO, $quoteId);

        return redirect()->route('admin.quotes.index')->with('success','Επιτυχής αποθήκευση!');
    }

    public function destroy(DeleteQuoteRequest $request, string $id): JsonResponse|RedirectResponse
    {
        $response = $this->quoteService->deleteById($id);

        if($request['ajax'] && $response){
            return response()->json(['message' => 'Επιτυχής διαγραφή', 'status'=>200], 200);
        }elseif ($request['ajax']){
            return response()->json(['message' => 'Υπήρξε κάποιο πρόβλημα κατά την διαγραφή', 'status'=>302], 302);
        }

        if($response){
            return redirect()->back()->with('success','Επιτυχής διαγραφή');
        }
        return redirect()->back()->with('error','Υπήρξε κάποιο πρόβλημα κατά την διαγραφή');

    }

    public function generatePdf(ManageQuoteRequest $request, $slug)
    {
        $quote = $this->quoteService->getByUuid($slug);

        $html = View::make('backend.content.quotes.pdf', compact('quote'))->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->set_option('chroot', public_path());
        $dompdf->render();

        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="offer_'.$quote->getReferenceCode().'.pdf"');

//        return $dompdf->stream($quote->getReferenceCode() . '.pdf');
    }

}
