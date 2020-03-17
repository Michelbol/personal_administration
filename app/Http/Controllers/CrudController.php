<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Str;
use Exception;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\CRUDService;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\View\Factory;

class CrudController extends Controller
{
    /**
     * @var CRUDService
     */
    protected $service;

    /**
     * @var CRUDService
     */
    protected $model;

    /**
     * @var string
     */
    protected $msgStore;

    /**
     * @var string
     */
    protected $msgUpdate;

    /**
     * @var string
     */
    protected $msgDestroy;

    /**
     * @var string
     */
    private $snakeModel;

    /**
     * Controller constructor.
     *
     * @param CRUDService|null $service
     * @param Model|null $model
     */
    public function __construct(CRUDService $service = null, Model $model = null)
    {
        $this->service = $service;
        $this->model = $model;
        $this->snakeModel = Str::snake(getClass($model));
    }

    /**
     * Display a listing of the resource.
     *
     * @param $tenant
     * @param Request $request
     * @return Factory|View
     */
    public function index($tenant, Request $request)
    {
        return view("$this->snakeModel.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $tenant
     * @return Factory|View
     */
    public function create($tenant)
    {
        return view("$this->snakeModel.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response|RedirectResponse
     */
    public function store(Request $request)
    {
        try{
            $this->service->create($request->all());
            $this->successMessage($this->msgStore);
            return redirect()->routeTenant($this->snakeModel."s.index");
        }catch (Exception $e){
            $this->errorMessage($e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $tenant
     * @param Request $request
     * @param int $id
     * @return Response|RedirectResponse
     */
    public function update($tenant, Request $request, $id)
    {
        try{
            $this->service->update($id, $request->all());
            $this->successMessage($this->msgUpdate);
            return redirect()->routeTenant($this->snakeModel."s.index");
        }catch (Exception $e){
            $this->errorMessage($e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $tenant
     * @param Request $request
     * @param int $id
     * @return Response|RedirectResponse
     */
    public function destroy($tenant, Request $request ,$id)
    {
        try{
            $this->service->delete($id);

            $this->successMessage($this->msgDestroy);
            return redirect()->routeTenant($this->snakeModel."s.index");
        }catch (Exception $e){
            $this->errorMessage($e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
    }
}
