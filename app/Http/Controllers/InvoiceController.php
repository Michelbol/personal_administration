<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Http\Requests\InvoiceQrCodeRequest;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\CurlException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Yajra\DataTables\DataTables;

class InvoiceController extends CrudController
{
    /**
     * @var InvoiceService
     */
    protected $service;

    protected $msgStore = 'Nota incluida com sucesso';

    protected $msgUpdate = 'Nota atualizada com sucesso';

    protected $msgDestroy = 'Nota deletada com sucesso';

    protected $requestValidator = CarRequest::class;

    public function __construct(InvoiceService $service = null, Invoice $model = null)
    {
        parent::__construct($service, $model);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function get(){
        $model = Invoice::select('*');

        $response = DataTables::of($model)
            ->addColumn('actions', function ($model){
                return getBtnAction([
                    ['type'=>'edit',    'url' => routeTenant('invoice.edit',['id' => $model->id])],
                    ['type'=>'delete',  'url' => routeTenant('invoice.destroy',['id' => $model->id]), 'id' => $model->id]
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
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws CurlException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function storeByQrCode(InvoiceQrCodeRequest $request)
    {
        return $this->service->createInvoiceByQrCode($request->validated()['url_qr_code']);
    }
}
