<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Http\Requests\InvoiceQrCodeRequest;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Repositories\ProductSupplierRepository;
use App\Services\InvoiceService;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class InvoiceController extends CrudController
{
    /**
     * @var InvoiceService
     */
    protected $service;

    /**
     * @var string
     */
    protected $msgStore = 'Nota incluida com sucesso';

    /**
     * @var string
     */
    protected $msgUpdate = 'Nota atualizada com sucesso';

    /**
     * @var string
     */
    protected $msgDestroy = 'Nota deletada com sucesso';

    /**
     * @var string
     */
    protected $requestValidator = CarRequest::class;

    /**
     * @var ProductSupplierRepository
     */
    protected $productSupplierRepository;

    /**
     * InvoiceController constructor.
     * @param ProductSupplierRepository $productSupplierRepository
     * @param InvoiceService|null $service
     * @param Invoice|null $model
     */
    public function __construct(ProductSupplierRepository $productSupplierRepository, InvoiceService $service = null, Invoice $model = null)
    {
        $this->productSupplierRepository = $productSupplierRepository;
        parent::__construct($service, $model);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function get(){
        $model = Invoice
            ::select(['invoices.*', 's.fantasy_name'])
            ->join('suppliers as s', 's.id', 'invoices.supplier_id');

        $response = DataTables::of($model)
            ->addColumn('emission_at', function($model){
                return formatGetData($model->emission_at);
            })
            ->addColumn('taxes', function($model){
                return getFormatReal($model->taxes);
            })
            ->addColumn('discount', function($model){
                return getFormatReal($model->discount);
            })
            ->addColumn('total_paid', function($model){
                return getFormatReal($model->total_paid);
            })
            ->addColumn('total_products', function($model){
                return getFormatReal($model->total_products);
            })
            ->addColumn('actions', function ($model){
                return getBtnAction([
                    ['type'=>'edit', 'url' => routeTenant('invoice.edit',[$model->id])],
                    ['type'=>'other-a', 'name' => 'Visualizar', 'url' => routeTenant('invoice.show',[$model->id])],
                    ['type'=>'delete', 'url' => routeTenant('invoice.destroy',[$model->id]), 'id' => $model->id]
                ]);
            })
            ->rawColumns(['actions'])
            ->toJson();
        return $response->original;
    }

    /**
     * @return Application|Factory|View
     */
    public function createByQrCode()
    {
        return view('invoice.create.qr_code');
    }

    /**
     * @param InvoiceQrCodeRequest $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function storeByQrCode(InvoiceQrCodeRequest $request)
    {
        try {
            DB::beginTransaction();
            $invoice = $this->service->createInvoiceByQrCode($request->validated()['url_qr_code']);
            DB::commit();
            $this->successMessage('Nota salva com sucesso');
            return redirect()->routeTenant('invoice.edit', [$invoice->id]);
        }catch (Exception $e){
            DB::rollBack();
            $this->errorMessage($e->getMessage());
            return redirect()->routeTenant('invoice.create.qr_code');
        }
    }

    public function show($tenant, string $id)
    {
        $data = [
            'invoice' => $this->service->find($id),
            'invoiceProducts' => $this->service->listProducts($id),
            'suppliers' => $this->service->getAllSuppliers()
        ];
        return view('invoice.show', $data);
    }

    public function edit($tenant, $id, Request $request)
    {
        $data = [
            'invoice' => Invoice::with('supplier')->find($id),
            'invoiceProducts' => InvoiceProduct
                ::whereInvoiceId($id)
                ->get(),
            'products' => $this->service->getOptionsSelectProductSupplier()
        ];
        return view('invoice.edit', $data);
    }
}
