<?php


namespace App\Services;


use App\Models\Supplier;
use App\Repositories\InvoiceRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use PHPHtmlParser\Dom;

class SupplierService extends CRUDService
{
    /**
     * @var Supplier
     */
    protected $modelClass = Supplier::class;

    /**
     * @param Supplier $model
     * @param array $data
     */
    public function fill(&$model, $data)
    {
        $model->company_name = $data['company_name'];
        $model->fantasy_name = $data['fantasy_name'] ?? ($model->fantasy_name ?? null);
        $model->cnpj = cleanNumber($data['cnpj']);
        $model->address = $data['address'];
        $model->address_number = $data['address_number'];
        $model->neighborhood = $data['neighborhood'];
        $model->city = $data['city'];
        $model->state = $data['state'];
    }


    public function getSupplierData(Dom $dom)
    {
        $data = [];
        $supplierData = $dom->find("#conteudo .txtCenter .text");
        $data['cnpj'] = cleanNumber($supplierData[0]->text);
        $data['company_name'] = $dom->find("#u20")->text;
        [
            $data['address'],
            $data['address_number'],
            $data['complement'],
            $data['neighborhood'],
            $data['city'],
            $data['state'],
        ] = explode(',', $supplierData[1]->text);
        return $data;
    }

    public function getCnpjSupplier(Dom $dom)
    {
        $supplierData = $dom->find("#conteudo .txtCenter .text");
        return cleanNumber($supplierData[0]->text);
    }

    /**
     * @param Dom $dom
     * @return Model|string|Supplier
     * @throws Exception
     */
    public function findOrCreateSupplierByDom(Dom $dom)
    {
        $supplier = (new InvoiceRepository())->findOneSupplierByCnpj($this->getCnpjSupplier($dom));
        $data = $this->getSupplierData($dom);
        if(!isset($supplier)){
            return $this->create($data);
        }
        return $this->update($supplier, $data);
    }
}
